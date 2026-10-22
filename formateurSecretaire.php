<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'secretaire') {
  header('Location: loginEcole221.php');
  exit;
}

require 'config.php';

// Nom et email de la personne connectée (affichage en-tête)
$nomAffiche   = (isset($_SESSION['nom_complet']) && trim($_SESSION['nom_complet']) !== '') ? $_SESSION['nom_complet'] : ($_SESSION['login'] ?? 'Utilisateur');
$emailAffiche = $_SESSION['email'] ?? ($_SESSION['login'] ?? '');

$erreurMatricule = "";
$erreurNom       = "";
$erreurPrenom    = "";
$erreurEmail     = "";
$erreurTel       = "";
$erreurPhoto     = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // ----- Suppression -----
  if (isset($_POST['delete_id'])) {
    $q = $pdo->prepare("SELECT matricule_formateur, nom, prenom, sexe, email, telephone, specialite, statut FROM formateur WHERE id_formateur = :id");
    $q->execute(['id' => $_POST['delete_id']]);
    $old = $q->fetch();

    try {
      $stmt = $pdo->prepare("DELETE FROM formateur WHERE id_formateur = :id");
      $stmt->execute(['id' => $_POST['delete_id']]);
    } catch (PDOException $e) {
      // Le formateur est sûrement rattaché à des séances ou des matières
      header('Location: formateurSecretaire.php?err=suppression');
      exit;
    }

    $url = 'formateurSecretaire.php?ok=suppression';
    if ($old) {
      $url .= '&matricule=' . urlencode($old['matricule_formateur'])
            . '&nom='       . urlencode($old['nom'])
            . '&prenom='    . urlencode($old['prenom'])
            . '&sexe='      . urlencode($old['sexe'])
            . '&email='     . urlencode($old['email'] ?? '')
            . '&tel='       . urlencode($old['telephone'] ?? '')
            . '&spec='      . urlencode($old['specialite'] ?? '')
            . '&statut='    . urlencode($old['statut'] ?? 'Actif');
    }
    header('Location: ' . $url);
    exit;
  }

  // ----- Ajout ou Modification -----
  $matricule = trim($_POST['matricule'] ?? '');
  $nom       = trim($_POST['nom'] ?? '');
  $prenom    = trim($_POST['prenom'] ?? '');
  $sexe      = ($_POST['sexe'] ?? 'M') === 'F' ? 'F' : 'M';
  $email     = trim($_POST['email'] ?? '');
  $tel       = trim($_POST['telephone'] ?? '');
  $spec      = trim($_POST['specialite'] ?? '');
  $statut    = $_POST['statut'] ?? 'Actif';
  $id        = $_POST['id_formateur'] ?? '';

  // Le statut doit être une des valeurs autorisées
  if (!in_array($statut, ['Actif', 'Conge', 'Inactif'])) { $statut = 'Actif'; }

  // ----- Gestion de la photo -----
  $photoChemin = trim($_POST['photo_actuelle'] ?? '');
  if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    $extAutorisees = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array($ext, $extAutorisees)) {
      $erreurPhoto = "Format d'image non supporté (jpg, png ou webp).";
    } elseif ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
      $erreurPhoto = "L'image ne doit pas dépasser 2 Mo.";
    } else {
      if (!is_dir('uploads/formateurs')) { mkdir('uploads/formateurs', 0777, true); }
      $nomFichier = 'uploads/formateurs/' . uniqid('form_') . '.' . $ext;
      if (move_uploaded_file($_FILES['photo']['tmp_name'], $nomFichier)) {
        $photoChemin = $nomFichier;
      } else {
        $erreurPhoto = "Échec de l'enregistrement de l'image.";
      }
    }
  }

  // Champs vides -> NULL
  $email = $email === '' ? null : $email;
  $tel   = $tel   === '' ? null : $tel;
  $spec  = $spec  === '' ? null : $spec;
  $photo = $photoChemin === '' ? null : $photoChemin;

  // Validation (avec regex)
  if (!preg_match('/^[A-Za-z0-9\-\/]{4,30}$/', $matricule)) {
    $erreurMatricule = "Matricule invalide (lettres, chiffres et tirets).";
  }
  if (!preg_match('/^[A-Za-zÀ-ÿ\' -]{2,60}$/u', $nom)) {
    $erreurNom = "Le nom ne doit contenir que des lettres (2 à 60 caractères).";
  }
  if (!preg_match('/^[A-Za-zÀ-ÿ\' -]{2,60}$/u', $prenom)) {
    $erreurPrenom = "Le prénom ne doit contenir que des lettres (2 à 60 caractères).";
  }
  if ($email !== null && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erreurEmail = "L'adresse e-mail n'est pas valide.";
  }
  if ($tel !== null && !preg_match('/^\+?[0-9 ]{9,15}$/', $tel)) {
    $erreurTel = "Le téléphone doit contenir 9 à 15 chiffres (ex: +221 77 123 45 67).";
  }

  if ($erreurMatricule === "" && $erreurNom === "" && $erreurPrenom === "" && $erreurEmail === "" && $erreurTel === "" && $erreurPhoto === "") {
    try {
      if ($id === '') {
        // Ajout
        $stmt = $pdo->prepare("INSERT INTO formateur (matricule_formateur, nom, prenom, sexe, email, telephone, specialite, photo, statut)
                               VALUES (:matricule, :nom, :prenom, :sexe, :email, :tel, :spec, :photo, :statut)");
        $stmt->execute(['matricule' => $matricule, 'nom' => $nom, 'prenom' => $prenom, 'sexe' => $sexe,
                        'email' => $email, 'tel' => $tel, 'spec' => $spec, 'photo' => $photo, 'statut' => $statut]);
        $idFormateur = $pdo->lastInsertId();
      } else {
        // Modification
        $stmt = $pdo->prepare("UPDATE formateur SET matricule_formateur = :matricule, nom = :nom, prenom = :prenom, sexe = :sexe,
                               email = :email, telephone = :tel, specialite = :spec, photo = :photo, statut = :statut WHERE id_formateur = :id");
        $stmt->execute(['matricule' => $matricule, 'nom' => $nom, 'prenom' => $prenom, 'sexe' => $sexe,
                        'email' => $email, 'tel' => $tel, 'spec' => $spec, 'photo' => $photo, 'statut' => $statut, 'id' => $id]);
        $idFormateur = $id;
      }

      // ----- Matières enseignées (table enseigner) -----
      // On efface les anciennes affectations, puis on remet les cases cochées
      $pdo->prepare("DELETE FROM enseigner WHERE id_formateur = :id")->execute(['id' => $idFormateur]);
      $matieresChoisies = $_POST['matieres'] ?? [];
      if (!empty($matieresChoisies)) {
        $ins = $pdo->prepare("INSERT INTO enseigner (id_formateur, id_matiere) VALUES (:idf, :idm)");
        foreach ($matieresChoisies as $idm) {
          $ins->execute(['idf' => $idFormateur, 'idm' => $idm]);
        }
      }

      header('Location: formateurSecretaire.php?ok=' . ($id === '' ? 'ajout' : 'modification'));
      exit;
    } catch (PDOException $e) {
      $erreurMatricule = "Ce matricule ou cet e-mail existe déjà.";
    }
  }
}

// Recherche + filtre statut
$q       = trim($_GET['q'] ?? '');
$filStat = $_GET['statut'] ?? '';

$stmt = $pdo->prepare("
  SELECT fo.id_formateur, fo.matricule_formateur, fo.nom, fo.prenom, fo.sexe,
         fo.email, fo.telephone, fo.specialite, fo.photo, fo.statut,
         GROUP_CONCAT(m.nom_matiere SEPARATOR ', ') AS matieres,
         GROUP_CONCAT(DISTINCT m.id_matiere) AS matieres_ids
  FROM formateur fo
  LEFT JOIN enseigner en ON en.id_formateur = fo.id_formateur
  LEFT JOIN matiere   m  ON m.id_matiere    = en.id_matiere
  WHERE (:q1 = '' OR fo.nom LIKE CONCAT('%', :q2, '%') OR fo.prenom LIKE CONCAT('%', :q3, '%') OR fo.matricule_formateur LIKE CONCAT('%', :q4, '%'))
    AND (:st1 = '' OR fo.statut = :st2)
  GROUP BY fo.id_formateur, fo.matricule_formateur, fo.nom, fo.prenom, fo.sexe, fo.email, fo.telephone, fo.specialite, fo.photo, fo.statut
  ORDER BY fo.nom, fo.prenom
");
$stmt->execute([
  'q1' => $q, 'q2' => $q, 'q3' => $q, 'q4' => $q,
  'st1' => $filStat, 'st2' => $filStat,
]);
$formateurs = $stmt->fetchAll();

// Statistiques
$stats = $pdo->query("SELECT COUNT(*) AS total, SUM(statut='Actif') AS actifs, SUM(statut='Conge') AS conges FROM formateur")->fetch();

// Liste de toutes les matières (pour les cases à cocher)
$matieresListe = $pdo->query("SELECT id_matiere, nom_matiere FROM matiere ORDER BY nom_matiere")->fetchAll();

// Libellé + couleur du statut
function statutInfo($s) {
  if ($s === 'Conge')   return ['En congé', 'border-[#fde68a] bg-[#fffbeb] text-[#b45309]'];
  if ($s === 'Inactif') return ['Inactif',  'border-[#e5e7eb] bg-[#f3f4f6] text-[#6b7280]'];
  return ['Actif', 'border-[#bbf7d0] bg-[#f0fdf4] text-[#15803d]'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Formateurs</title>
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: { extend: {
        fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
        colors: { primary: '#e11d2a', ink: '#141414', muted: '#5f5e5e', page: '#f4f4f5', primarydark: '#8f1119' },
      } },
    };
  </script>
</head>
<body class="font-sans bg-page text-ink">

  <svg class="hidden" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M2 21v-1a6 6 0 0 1 12 0v1M16 4.5a3.5 3.5 0 0 1 0 7M22 21v-1a6 6 0 0 0-4-5.6"/></symbol>
      <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></symbol>
      <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
      <symbol id="i-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
      <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
      <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></symbol>
      <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
      <symbol id="i-user-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="4"/><path d="M3 21v-1a6 6 0 0 1 12 0v1"/><path d="M17 9v6M20 12h-6"/></symbol>
    </defs>
  </svg>

  <!-- ============ SIDEBAR ============ -->
  <?php $active = 'formateurs'; include 'sidebarSecretaire.php'; ?>

  <div class="app-content min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <h2 class="text-sm font-semibold">Tableau de bord - Secrétaire</h2>
      </div>
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
          <img src="https://i.pravatar.cc/64?img=47" alt="Photo" class="h-8 w-8 rounded-full object-cover" />
          <div class="hidden sm:block"><p class="text-sm font-semibold leading-[17.5px]"><?= htmlspecialchars($nomAffiche) ?></p><p class="text-xs leading-3 text-muted"><?= htmlspecialchars($emailAffiche) ?></p></div>
        </div>
        <span class="hidden h-6 w-px bg-[#e3e1ec] sm:block"></span>
        <span class="whitespace-nowrap rounded-full bg-primary px-2 py-1 text-xs font-semibold text-white shadow-sm">Secrétaire / Admin</span>
      </div>
    </header>

    <main class="px-4 py-8 sm:px-6 lg:px-12">
      <div class="mx-auto flex max-w-[1180px] flex-col gap-6">

        <!-- Cartes stats -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
          <div class="flex items-center justify-between rounded-xl bg-white p-5 shadow-sm">
            <div><p class="text-xs text-muted">Total formateurs</p><p class="mt-1 text-3xl font-bold text-[#1a1b22]"><?= (int)$stats['total'] ?></p></div>
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-5 w-5"><use href="#i-users" /></svg></span>
          </div>
          <div class="flex items-center justify-between rounded-xl bg-white p-5 shadow-sm">
            <div><p class="text-xs text-muted">Actifs</p><p class="mt-1 text-3xl font-bold text-[#1a1b22]"><?= (int)$stats['actifs'] ?></p></div>
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#f0fdf4] text-[#15803d]"><svg class="h-5 w-5"><use href="#i-check" /></svg></span>
          </div>
          <div class="flex items-center justify-between rounded-xl bg-white p-5 shadow-sm">
            <div><p class="text-xs text-muted">En congé</p><p class="mt-1 text-3xl font-bold text-[#1a1b22]"><?= (int)$stats['conges'] ?></p></div>
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#fffbeb] text-[#b45309]"><svg class="h-5 w-5"><use href="#i-clock" /></svg></span>
          </div>
        </div>

        <!-- Gestion des formateurs -->
        <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">
          <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
              <h1 class="text-xl font-bold text-[#1a1b22]">Gestion des formateurs</h1>
              <p class="mt-1 text-xs text-muted">Liste, coordonnées et affectations du corps enseignant</p>
            </div>
            <button type="button" id="btnAdd" class="flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark"><svg class="h-3.5 w-3.5"><use href="#i-plus" /></svg> Ajouter un formateur</button>
          </div>

          <form method="get" class="mt-5 flex flex-wrap items-center justify-between gap-3">
            <div class="relative">
              <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-search" /></svg></span>
              <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Rechercher un formateur…" class="w-64 rounded-lg border border-[#e5e7eb] py-2.5 pl-9 pr-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
            </div>
            <div class="flex items-center gap-2">
              <select name="statut" onchange="this.form.submit()" class="rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                <option value="">Statut : Tous</option>
                <option value="Actif"   <?= $filStat === 'Actif'   ? 'selected' : '' ?>>Actif</option>
                <option value="Conge"   <?= $filStat === 'Conge'   ? 'selected' : '' ?>>En congé</option>
                <option value="Inactif" <?= $filStat === 'Inactif' ? 'selected' : '' ?>>Inactif</option>
              </select>
              <button type="submit" class="rounded-lg border border-[#e5e7eb] p-2.5 text-muted hover:bg-page" aria-label="Rechercher"><svg class="h-4 w-4"><use href="#i-search" /></svg></button>
            </div>
          </form>

          <div class="mt-5 overflow-x-auto">
            <table class="w-full min-w-[920px] text-left text-sm">
              <thead class="border-b border-[#e5e7eb]">
                <tr class="text-xs font-semibold uppercase tracking-[0.5px] text-[#6b7280]">
                  <th class="px-4 py-3 font-semibold">Matricule</th>
                  <th class="px-4 py-3 font-semibold">Nom complet</th>
                  <th class="px-4 py-3 font-semibold">Spécialité</th>
                  <th class="px-4 py-3 font-semibold">Email</th>
                  <th class="px-4 py-3 font-semibold">Téléphone</th>
                  <th class="px-4 py-3 font-semibold">Matières</th>
                  <th class="px-4 py-3 font-semibold">Statut</th>
                  <th class="px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-[#f3f4f6]">
                <?php if (count($formateurs) === 0): ?>
                  <tr><td colspan="8" class="px-4 py-10 text-center text-sm text-muted">Aucun formateur ne correspond à votre recherche.</td></tr>
                <?php else: ?>
                  <?php foreach ($formateurs as $f): ?>
                  <?php [$stLabel, $stClass] = statutInfo($f['statut']); ?>
                  <tr class="cursor-pointer transition-colors hover:bg-[#f9fafb]"
                      data-id="<?= $f['id_formateur'] ?>"
                      data-matricule="<?= htmlspecialchars($f['matricule_formateur']) ?>"
                      data-nom="<?= htmlspecialchars($f['nom']) ?>"
                      data-prenom="<?= htmlspecialchars($f['prenom']) ?>"
                      data-sexe="<?= htmlspecialchars($f['sexe']) ?>"
                      data-email="<?= htmlspecialchars($f['email'] ?? '') ?>"
                      data-tel="<?= htmlspecialchars($f['telephone'] ?? '') ?>"
                      data-spec="<?= htmlspecialchars($f['specialite'] ?? '') ?>"
                      data-photo="<?= htmlspecialchars($f['photo'] ?? '') ?>"
                      data-matieres="<?= htmlspecialchars($f['matieres_ids'] ?? '') ?>"
                      data-statut="<?= htmlspecialchars($f['statut']) ?>">
                    <td class="px-4 py-3 font-mono text-xs font-bold text-[#111827]"><?= htmlspecialchars($f['matricule_formateur']) ?></td>
                    <td class="px-4 py-3 font-bold text-[#1a1b22]"><?= htmlspecialchars($f['prenom'] . ' ' . $f['nom']) ?></td>
                    <td class="px-4 py-3 text-[#374151]"><?= htmlspecialchars($f['specialite'] ?: '—') ?></td>
                    <td class="px-4 py-3 text-[#374151]"><?= htmlspecialchars($f['email'] ?: '—') ?></td>
                    <td class="px-4 py-3 text-[#374151]"><?= htmlspecialchars($f['telephone'] ?: '—') ?></td>
                    <td class="px-4 py-3 text-[#374151]"><?= htmlspecialchars($f['matieres'] ?: '—') ?></td>
                    <td class="px-4 py-3"><span class="rounded-full border px-2 py-0.5 text-xs font-medium <?= $stClass ?>"><?= $stLabel ?></span></td>
                    <td class="px-4 py-3">
                      <div class="flex items-center justify-end gap-1.5">
                        <button type="button" class="js-edit rounded-lg p-1.5 text-primary hover:bg-primary hover:text-white" aria-label="Modifier"><svg class="h-4 w-4"><use href="#i-edit"/></svg></button>
                        <button type="button" class="js-del rounded-lg p-1.5 text-[#6b7280] hover:bg-primary hover:text-white" aria-label="Supprimer"><svg class="h-4 w-4"><use href="#i-trash"/></svg></button>
                      </div>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <p class="mt-4 text-xs text-muted"><?= count($formateurs) ?> formateur<?= count($formateurs) > 1 ? 's' : '' ?> affiché<?= count($formateurs) > 1 ? 's' : '' ?></p>
        </section>
      </div>
    </main>
  </div>

  <!-- ============ DRAWER : AJOUTER / MODIFIER FORMATEUR ============ -->
  <div id="drawerAdd" class="fixed inset-0 z-50 hidden">
    <div data-close class="absolute inset-0 bg-black/50"></div>
    <aside class="absolute inset-y-0 right-0 flex w-full max-w-md translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out">
      <div class="flex items-center justify-between border-b border-[#eef0f2] px-6 py-4">
        <div class="flex items-center gap-3">
          <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-5 w-5"><use href="#i-user-plus" /></svg></span>
          <div><h3 id="drawerTitle" class="text-lg font-bold text-[#1a1b22]"><?= ($_POST['id_formateur'] ?? '') !== '' ? 'Modifier le formateur' : 'Nouveau formateur' ?></h3><p class="text-xs text-muted">Renseignez les informations du formateur</p></div>
        </div>
        <button type="button" data-close class="rounded-lg p-1.5 text-muted hover:bg-page" aria-label="Fermer"><svg class="h-5 w-5"><use href="#i-x" /></svg></button>
      </div>

      <form id="formateurForm" method="post" enctype="multipart/form-data" class="flex flex-1 flex-col gap-4 overflow-y-auto px-6 py-5">

        <input type="hidden" name="id_formateur" id="f-id" value="<?= htmlspecialchars($_POST['id_formateur'] ?? '') ?>" />
        <input type="hidden" name="photo_actuelle" id="f-photo-actuelle" value="<?= htmlspecialchars($_POST['photo_actuelle'] ?? '') ?>" />

        <!-- Photo de profil -->
        <div class="flex flex-col items-center gap-3">
          <div class="relative">
            <img id="f-photo-preview" src="https://i.pravatar.cc/120?img=47" alt="Photo" class="h-20 w-20 rounded-full object-cover ring-2 ring-[#ffdad7]" />
            <label for="f-photo" class="absolute -bottom-1 -right-1 flex h-7 w-7 cursor-pointer items-center justify-center rounded-full bg-primary text-white shadow hover:bg-primarydark" title="Changer la photo"><svg class="h-3.5 w-3.5"><use href="#i-edit" /></svg></label>
            <input id="f-photo" type="file" name="photo" accept="image/*" class="sr-only" />
          </div>
          <p class="text-xs text-muted">Photo (jpg, png, webp — 2 Mo max)</p>
          <?php if ($erreurPhoto !== ""): ?>
            <p class="flex items-center gap-1.5 text-xs text-[#b8001a]"><svg class="h-3.5 w-3.5 shrink-0"><use href="#i-alert" /></svg> <span><?= htmlspecialchars($erreurPhoto) ?></span></p>
          <?php endif; ?>
        </div>

        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-matricule">Matricule</label>
          <input id="f-matricule" type="text" name="matricule" value="<?= htmlspecialchars($_POST['matricule'] ?? '') ?>" placeholder="ex: FORM-2024-001" class="w-full rounded-lg border px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= $erreurMatricule !== '' ? 'border-[#ffb3b3] focus:border-[#e11d2a]' : 'border-[#e5e7eb] focus:border-primary' ?>" />
          <?php if ($erreurMatricule !== ""): ?>
            <p class="mt-1.5 flex items-start gap-1.5 text-xs text-[#b8001a]"><svg class="mt-0.5 h-3.5 w-3.5 shrink-0"><use href="#i-alert" /></svg> <span><?= htmlspecialchars($erreurMatricule) ?></span></p>
          <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div>
            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-nom">Nom</label>
            <input id="f-nom" type="text" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" placeholder="Nom" class="w-full rounded-lg border px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= $erreurNom !== '' ? 'border-[#ffb3b3] focus:border-[#e11d2a]' : 'border-[#e5e7eb] focus:border-primary' ?>" />
            <?php if ($erreurNom !== ""): ?>
              <p class="mt-1.5 flex items-start gap-1.5 text-xs text-[#b8001a]"><svg class="mt-0.5 h-3.5 w-3.5 shrink-0"><use href="#i-alert" /></svg> <span><?= htmlspecialchars($erreurNom) ?></span></p>
            <?php endif; ?>
          </div>
          <div>
            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-prenom">Prénom</label>
            <input id="f-prenom" type="text" name="prenom" value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" placeholder="Prénom" class="w-full rounded-lg border px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= $erreurPrenom !== '' ? 'border-[#ffb3b3] focus:border-[#e11d2a]' : 'border-[#e5e7eb] focus:border-primary' ?>" />
            <?php if ($erreurPrenom !== ""): ?>
              <p class="mt-1.5 flex items-start gap-1.5 text-xs text-[#b8001a]"><svg class="mt-0.5 h-3.5 w-3.5 shrink-0"><use href="#i-alert" /></svg> <span><?= htmlspecialchars($erreurPrenom) ?></span></p>
            <?php endif; ?>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div>
            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted">Sexe</label>
            <div class="flex items-center gap-6 pt-2">
              <label class="flex cursor-pointer items-center gap-2 text-sm"><input type="radio" name="sexe" value="M" <?= ($_POST['sexe'] ?? 'M') !== 'F' ? 'checked' : '' ?> class="accent-primary" /> Masculin</label>
              <label class="flex cursor-pointer items-center gap-2 text-sm"><input type="radio" name="sexe" value="F" <?= ($_POST['sexe'] ?? '') === 'F' ? 'checked' : '' ?> class="accent-primary" /> Féminin</label>
            </div>
          </div>
          <div>
            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-statut">Statut</label>
            <select id="f-statut" name="statut" class="w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
              <option value="Actif"   <?= ($_POST['statut'] ?? 'Actif') === 'Actif'   ? 'selected' : '' ?>>Actif</option>
              <option value="Conge"   <?= ($_POST['statut'] ?? '') === 'Conge'   ? 'selected' : '' ?>>En congé</option>
              <option value="Inactif" <?= ($_POST['statut'] ?? '') === 'Inactif' ? 'selected' : '' ?>>Inactif</option>
            </select>
          </div>
        </div>

        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-spec">Spécialité</label>
          <input id="f-spec" type="text" name="specialite" value="<?= htmlspecialchars($_POST['specialite'] ?? '') ?>" placeholder="ex: Algorithmique & IA" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
        </div>

        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-email">Email</label>
          <input id="f-email" type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="ex: m.kane@ecole221.sn" class="w-full rounded-lg border px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= $erreurEmail !== '' ? 'border-[#ffb3b3] focus:border-[#e11d2a]' : 'border-[#e5e7eb] focus:border-primary' ?>" />
          <?php if ($erreurEmail !== ""): ?>
            <p class="mt-1.5 flex items-start gap-1.5 text-xs text-[#b8001a]"><svg class="mt-0.5 h-3.5 w-3.5 shrink-0"><use href="#i-alert" /></svg> <span><?= htmlspecialchars($erreurEmail) ?></span></p>
          <?php endif; ?>
        </div>

        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-tel">Téléphone</label>
          <input id="f-tel" type="tel" name="telephone" value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>" placeholder="ex: +221 77 123 45 67" class="w-full rounded-lg border px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= $erreurTel !== '' ? 'border-[#ffb3b3] focus:border-[#e11d2a]' : 'border-[#e5e7eb] focus:border-primary' ?>" />
          <?php if ($erreurTel !== ""): ?>
            <p class="mt-1.5 flex items-start gap-1.5 text-xs text-[#b8001a]"><svg class="mt-0.5 h-3.5 w-3.5 shrink-0"><use href="#i-alert" /></svg> <span><?= htmlspecialchars($erreurTel) ?></span></p>
          <?php endif; ?>
        </div>

        <div>
          <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-muted">Matières enseignées</label>
          <?php if (count($matieresListe) === 0): ?>
            <p class="text-xs text-muted">Aucune matière dans la base pour l'instant.</p>
          <?php else: ?>
            <div class="grid grid-cols-1 gap-2 rounded-lg border border-[#e5e7eb] p-3 sm:grid-cols-2">
              <?php foreach ($matieresListe as $ml): ?>
                <label class="flex cursor-pointer items-center gap-2 text-sm">
                  <input type="checkbox" name="matieres[]" value="<?= $ml['id_matiere'] ?>" <?= in_array($ml['id_matiere'], $_POST['matieres'] ?? []) ? 'checked' : '' ?> class="h-4 w-4 accent-primary" />
                  <?= htmlspecialchars($ml['nom_matiere']) ?>
                </label>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </form>

      <div class="grid grid-cols-2 gap-3 border-t border-[#eef0f2] px-6 py-4">
        <button type="button" data-close class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="submit" form="formateurForm" class="flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-check" /></svg> Enregistrer</button>
      </div>
    </aside>
  </div>

  <!-- ============ MODALE : CONFIRMER SUPPRESSION ============ -->
  <div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-xl">
      <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-7 w-7"><use href="#i-trash" /></svg></div>
      <h3 class="text-lg font-bold text-[#1a1b22]">Confirmer la suppression</h3>
      <p class="mt-2 text-sm text-muted">Voulez-vous vraiment supprimer le formateur <span id="delName" class="font-semibold text-[#1a1b22]"></span> ? Cette action est irréversible.</p>
      <div class="mt-6 flex items-center justify-center gap-3">
        <button type="button" data-cancel-delete class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="button" id="confirmDelete" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark">Supprimer</button>
      </div>
    </div>
  </div>

  <!-- Formulaire caché : suppression -->
  <form id="deleteForm" method="post" class="hidden">
    <input type="hidden" name="delete_id" id="delete_id" value="" />
  </form>

  <!-- Formulaire caché : restauration -->
  <form id="restoreForm" method="post" class="hidden">
    <input type="hidden" name="matricule" id="restore_matricule" value="" />
    <input type="hidden" name="nom" id="restore_nom" value="" />
    <input type="hidden" name="prenom" id="restore_prenom" value="" />
    <input type="hidden" name="sexe" id="restore_sexe" value="" />
    <input type="hidden" name="email" id="restore_email" value="" />
    <input type="hidden" name="telephone" id="restore_tel" value="" />
    <input type="hidden" name="specialite" id="restore_spec" value="" />
    <input type="hidden" name="statut" id="restore_statut" value="" />
  </form>

  <div id="toasts" class="fixed right-4 top-4 z-[60] flex w-[320px] max-w-[calc(100vw-2rem)] flex-col gap-2"></div>

  <script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleMenu = (open) => { sidebar.classList.toggle('-translate-x-full', !open); overlay.classList.toggle('hidden', !open); document.body.classList.toggle('overflow-hidden', open); };
    document.getElementById('openMenu').addEventListener('click', () => toggleMenu(true));
    document.getElementById('closeMenu').addEventListener('click', () => toggleMenu(false));
    overlay.addEventListener('click', () => toggleMenu(false));

    /* ----- Toasts ----- */
    function showToast(type, title, msg, action) {
      const map = { success: { b:'#16a34a', i:'#i-check', t:'#dcfce7', c:'#15803d' }, error: { b:'#e11d2a', i:'#i-alert', t:'#ffdad7', c:'#b8001a' }, info: { b:'#6b7280', i:'#i-alert', t:'#f3f4f6', c:'#374151' } };
      const m = map[type] || map.info;
      const el = document.createElement('div');
      el.className = 'rounded-xl border-l-4 bg-white p-3.5 shadow-md';
      el.style.borderLeftColor = m.b;
      el.innerHTML = '<div class="flex items-start gap-3"><span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full" style="background:' + m.t + ';color:' + m.c + '"><svg class="h-4 w-4"><use href="' + m.i + '"/></svg></span><div class="flex-1"><p class="text-sm font-semibold text-[#1a1b22]">' + title + '</p>' + (msg ? '<p class="text-xs text-muted">' + msg + '</p>' : '') + '</div><button class="js-close text-muted hover:text-ink" aria-label="Fermer"><svg class="h-4 w-4"><use href="#i-x"/></svg></button></div>' + (action ? '<div class="mt-2 flex justify-end"><button class="js-action rounded-lg border border-[#e5e7eb] px-3 py-1 text-xs font-medium text-primary hover:bg-page">' + action.label + '</button></div>' : '');
      el.querySelector('.js-close').addEventListener('click', () => el.remove());
      if (action) el.querySelector('.js-action').addEventListener('click', () => { action.onClick(); el.remove(); });
      document.getElementById('toasts').appendChild(el);
      setTimeout(() => el.remove(), action ? 6000 : 3500);
    }

    /* ----- Drawer ----- */
    const drawerAdd = document.getElementById('drawerAdd');
    const drawerPanel = drawerAdd.querySelector('aside');
    const drawerTitle = document.getElementById('drawerTitle');
    const form = document.getElementById('formateurForm');
    const photoPreview = document.getElementById('f-photo-preview');
    const PHOTO_DEFAUT = 'https://i.pravatar.cc/120?img=47';

    /* ----- Aperçu de la photo choisie ----- */
    document.getElementById('f-photo').addEventListener('change', (ev) => {
      const file = ev.target.files[0];
      if (file) photoPreview.src = URL.createObjectURL(file);
    });

    const openDrawerPanel = () => { drawerAdd.classList.remove('hidden'); requestAnimationFrame(() => drawerPanel.classList.remove('translate-x-full')); document.body.classList.add('overflow-hidden'); };
    const closeDrawer = () => { drawerPanel.classList.add('translate-x-full'); document.body.classList.remove('overflow-hidden'); setTimeout(() => drawerAdd.classList.add('hidden'), 300); };

    /* ----- Ajout ----- */
    document.getElementById('btnAdd').addEventListener('click', () => {
      form.reset();
      document.getElementById('f-id').value = '';
      document.getElementById('f-photo-actuelle').value = '';
      photoPreview.src = PHOTO_DEFAUT;
      drawerTitle.textContent = 'Nouveau formateur';
      openDrawerPanel();
    });
    drawerAdd.querySelectorAll('[data-close]').forEach((b) => b.addEventListener('click', closeDrawer));
    drawerAdd.addEventListener('click', (e) => { if (!drawerPanel.contains(e.target)) closeDrawer(); });

    /* ----- Modification ----- */
    function openDrawerEdit(d) {
      form.reset();
      document.getElementById('f-id').value        = d.id;
      document.getElementById('f-matricule').value = d.matricule;
      document.getElementById('f-nom').value       = d.nom;
      document.getElementById('f-prenom').value    = d.prenom;
      document.querySelectorAll('input[name="sexe"]').forEach((r) => { r.checked = (r.value === d.sexe); });
      document.getElementById('f-spec').value   = d.spec;
      document.getElementById('f-email').value  = d.email;
      document.getElementById('f-tel').value    = d.tel;
      document.getElementById('f-statut').value = d.statut;
      document.getElementById('f-photo-actuelle').value = d.photo || '';
      photoPreview.src = d.photo ? d.photo : PHOTO_DEFAUT;
      // Cocher les matières déjà enseignées
      document.querySelectorAll('input[name="matieres[]"]').forEach((cb) => { cb.checked = false; });
      if (d.matieres) {
        d.matieres.split(',').forEach((idm) => {
          const cb = document.querySelector('input[name="matieres[]"][value="' + idm + '"]');
          if (cb) cb.checked = true;
        });
      }
      drawerTitle.textContent = 'Modifier le formateur';
      openDrawerPanel();
    }

    /* ----- Clic sur une ligne : fiche détail. Boutons : modifier / supprimer ----- */
    document.querySelectorAll('tbody tr[data-id]').forEach((tr) => {
      tr.addEventListener('click', () => { window.location.href = 'detailsFormateurSecretaire.php?id=' + tr.dataset.id; });

      const editBtn = tr.querySelector('.js-edit');
      if (editBtn) editBtn.addEventListener('click', (ev) => { ev.stopPropagation(); openDrawerEdit(tr.dataset); });

      const delBtn = tr.querySelector('.js-del');
      if (delBtn) delBtn.addEventListener('click', (ev) => {
        ev.stopPropagation();
        idToDelete = tr.dataset.id;
        document.getElementById('delName').textContent = tr.dataset.prenom + ' ' + tr.dataset.nom;
        openModal(modalDelete);
      });
    });

    /* ----- Suppression ----- */
    const modalDelete = document.getElementById('modalDelete');
    const openModal = (m) => { m.classList.remove('hidden'); m.classList.add('flex'); };
    const closeModal = (m) => { m.classList.add('hidden'); m.classList.remove('flex'); };
    let idToDelete = null;

    document.querySelectorAll('[data-cancel-delete]').forEach((b) =>
      b.addEventListener('click', () => closeModal(modalDelete))
    );
    modalDelete.addEventListener('click', (e) => { if (e.target === modalDelete) closeModal(modalDelete); });
    document.getElementById('confirmDelete').addEventListener('click', () => {
      if (!idToDelete) { closeModal(modalDelete); return; }
      document.getElementById('delete_id').value = idToDelete;
      document.getElementById('deleteForm').submit();
    });

    /* ----- Échap ----- */
    window.addEventListener('keydown', (e) => { if (e.key !== 'Escape') return; closeDrawer(); closeModal(modalDelete); toggleMenu(false); });

    /* ----- Rouvrir le drawer en cas d'erreur ----- */
    <?php if ($erreurMatricule !== "" || $erreurNom !== "" || $erreurPrenom !== "" || $erreurEmail !== "" || $erreurTel !== "" || $erreurPhoto !== ""): ?>
    openDrawerPanel();
    <?php endif; ?>

    /* ----- Toasts après une action ----- */
    <?php if (isset($_GET['ok']) && $_GET['ok'] === 'ajout'): ?>
    showToast('success', 'Formateur ajouté', 'Le formateur a bien été enregistré.');
    <?php elseif (isset($_GET['ok']) && $_GET['ok'] === 'modification'): ?>
    showToast('success', 'Formateur modifié', 'Les informations ont bien été mises à jour.');
    <?php elseif (isset($_GET['ok']) && $_GET['ok'] === 'suppression'): ?>
    showToast('success', 'Formateur supprimé', 'Le formateur a bien été retiré.', {
      label: 'Annuler',
      onClick: () => {
        document.getElementById('restore_matricule').value = <?= json_encode($_GET['matricule'] ?? '') ?>;
        document.getElementById('restore_nom').value       = <?= json_encode($_GET['nom'] ?? '') ?>;
        document.getElementById('restore_prenom').value    = <?= json_encode($_GET['prenom'] ?? '') ?>;
        document.getElementById('restore_sexe').value      = <?= json_encode($_GET['sexe'] ?? 'M') ?>;
        document.getElementById('restore_email').value     = <?= json_encode($_GET['email'] ?? '') ?>;
        document.getElementById('restore_tel').value       = <?= json_encode($_GET['tel'] ?? '') ?>;
        document.getElementById('restore_spec').value      = <?= json_encode($_GET['spec'] ?? '') ?>;
        document.getElementById('restore_statut').value    = <?= json_encode($_GET['statut'] ?? 'Actif') ?>;
        document.getElementById('restoreForm').submit();
      }
    });
    <?php elseif (isset($_GET['err']) && $_GET['err'] === 'suppression'): ?>
    showToast('error', 'Suppression impossible', 'Ce formateur est rattaché à des séances ou des matières.');
    <?php endif; ?>

    /* ----- Nettoyer l'URL ----- */
    <?php if (isset($_GET['ok']) || isset($_GET['err'])): ?>
    history.replaceState({}, '', 'formateurSecretaire.php');
    <?php endif; ?>
  </script>
</body>
</html>