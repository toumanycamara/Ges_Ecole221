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
    $q = $pdo->prepare("SELECT matricule, nom, prenom, sexe, date_naissance, email, telephone, adresse, id_classe FROM etudiant WHERE id_etudiant = :id");
    $q->execute(['id' => $_POST['delete_id']]);
    $old = $q->fetch();

    $stmt = $pdo->prepare("DELETE FROM etudiant WHERE id_etudiant = :id");
    $stmt->execute(['id' => $_POST['delete_id']]);

    $url = 'etudiantSecretaire.php?ok=suppression';
    if ($old) {
      $url .= '&matricule=' . urlencode($old['matricule'])
            . '&nom='       . urlencode($old['nom'])
            . '&prenom='    . urlencode($old['prenom'])
            . '&sexe='      . urlencode($old['sexe'])
            . '&daten='     . urlencode($old['date_naissance'] ?? '')
            . '&email='     . urlencode($old['email'] ?? '')
            . '&tel='       . urlencode($old['telephone'] ?? '')
            . '&adresse='   . urlencode($old['adresse'] ?? '')
            . '&classe='    . urlencode($old['id_classe'] ?? '');
    }
    header('Location: ' . $url);
    exit;
  }

  // ----- Ajout ou Modification -----
  $matricule = trim($_POST['matricule'] ?? '');
  $nom       = trim($_POST['nom'] ?? '');
  $prenom    = trim($_POST['prenom'] ?? '');
  $sexe      = ($_POST['sexe'] ?? 'M') === 'F' ? 'F' : 'M';
  $dateN     = trim($_POST['date_naissance'] ?? '');
  $email     = trim($_POST['email'] ?? '');
  $tel       = trim($_POST['telephone'] ?? '');
  $adresse   = trim($_POST['adresse'] ?? '');
  $idClasse  = $_POST['id_classe'] ?? '';
  $id        = $_POST['id_etudiant'] ?? '';

  // ----- Gestion de la photo -----
  // Par défaut on garde l'ancienne photo (champ caché), sauf si une nouvelle est envoyée
  $photoChemin = trim($_POST['photo_actuelle'] ?? '');
  if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    $extAutorisees = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array($ext, $extAutorisees)) {
      $erreurPhoto = "Format d'image non supporté (jpg, png ou webp).";
    } elseif ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
      $erreurPhoto = "L'image ne doit pas dépasser 2 Mo.";
    } else {
      if (!is_dir('uploads/etudiants')) { mkdir('uploads/etudiants', 0777, true); }
      $nomFichier = 'uploads/etudiants/' . uniqid('etu_') . '.' . $ext;
      if (move_uploaded_file($_FILES['photo']['tmp_name'], $nomFichier)) {
        $photoChemin = $nomFichier;
      } else {
        $erreurPhoto = "Échec de l'enregistrement de l'image.";
      }
    }
  }

  // Les champs vides deviennent NULL en base
  $dateN    = $dateN       === '' ? null : $dateN;
  $email    = $email       === '' ? null : $email;
  $tel      = $tel         === '' ? null : $tel;
  $adresse  = $adresse     === '' ? null : $adresse;
  $idClasse = $idClasse    === '' ? null : $idClasse;
  $photo    = $photoChemin === '' ? null : $photoChemin;

  // Validation (avec regex)
  if (!preg_match('/^[A-Za-z0-9\-\/]{4,30}$/', $matricule)) {
    $erreurMatricule = "Matricule invalide (lettres, chiffres et tirets, ex: 221-SN-2024-0045).";
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
        $stmt = $pdo->prepare("INSERT INTO etudiant (matricule, nom, prenom, sexe, date_naissance, email, telephone, adresse, photo, id_classe)
                               VALUES (:matricule, :nom, :prenom, :sexe, :dateN, :email, :tel, :adresse, :photo, :idClasse)");
        $stmt->execute([
          'matricule' => $matricule, 'nom' => $nom, 'prenom' => $prenom, 'sexe' => $sexe,
          'dateN' => $dateN, 'email' => $email, 'tel' => $tel, 'adresse' => $adresse, 'photo' => $photo, 'idClasse' => $idClasse,
        ]);
        header('Location: etudiantSecretaire.php?ok=ajout');
        exit;
      } else {
        // Modification
        $stmt = $pdo->prepare("UPDATE etudiant SET matricule = :matricule, nom = :nom, prenom = :prenom, sexe = :sexe,
                               date_naissance = :dateN, email = :email, telephone = :tel, adresse = :adresse, photo = :photo, id_classe = :idClasse
                               WHERE id_etudiant = :id");
        $stmt->execute([
          'matricule' => $matricule, 'nom' => $nom, 'prenom' => $prenom, 'sexe' => $sexe,
          'dateN' => $dateN, 'email' => $email, 'tel' => $tel, 'adresse' => $adresse, 'photo' => $photo, 'idClasse' => $idClasse, 'id' => $id,
        ]);
        header('Location: etudiantSecretaire.php?ok=modification');
        exit;
      }
    } catch (PDOException $e) {
      $erreurMatricule = "Ce matricule ou cet e-mail existe déjà.";
    }
  }
}

// Recherche + filtre filière
$q          = trim($_GET['q'] ?? '');
$filFiliere = $_GET['filiere'] ?? '';

// Pagination : page courante et nombre par page
$parPage = 10;
$page = max(1, (int)($_GET['page'] ?? 1));

// On compte d'abord le total (avec les mêmes filtres)
$countStmt = $pdo->prepare("
  SELECT COUNT(*)
  FROM etudiant e
  LEFT JOIN classe  c ON c.id_classe  = e.id_classe
  LEFT JOIN filiere f ON f.id_filiere = c.id_filiere
  WHERE (:q1 = '' OR e.nom LIKE CONCAT('%', :q2, '%') OR e.prenom LIKE CONCAT('%', :q3, '%') OR e.matricule LIKE CONCAT('%', :q4, '%'))
    AND (:fil1 = '' OR f.id_filiere = :fil2)
");
$countStmt->execute([
  'q1' => $q, 'q2' => $q, 'q3' => $q, 'q4' => $q,
  'fil1' => $filFiliere, 'fil2' => $filFiliere,
]);
$totalEtudiants = (int)$countStmt->fetchColumn();
$totalPages = max(1, (int)ceil($totalEtudiants / $parPage));
if ($page > $totalPages) { $page = $totalPages; }
$offset = ($page - 1) * $parPage;

// On récupère seulement les étudiants de la page (LIMIT / OFFSET)
$stmt = $pdo->prepare("
  SELECT e.id_etudiant, e.matricule, e.nom, e.prenom, e.sexe,
         e.date_naissance, e.email, e.telephone, e.adresse, e.photo, e.id_classe,
         c.nom_classe, f.nom_filiere, f.id_filiere
  FROM etudiant e
  LEFT JOIN classe  c ON c.id_classe  = e.id_classe
  LEFT JOIN filiere f ON f.id_filiere = c.id_filiere
  WHERE (:q1 = '' OR e.nom LIKE CONCAT('%', :q2, '%') OR e.prenom LIKE CONCAT('%', :q3, '%') OR e.matricule LIKE CONCAT('%', :q4, '%'))
    AND (:fil1 = '' OR f.id_filiere = :fil2)
  ORDER BY e.nom, e.prenom
  LIMIT $parPage OFFSET $offset
");
$stmt->execute([
  'q1' => $q, 'q2' => $q, 'q3' => $q, 'q4' => $q,
  'fil1' => $filFiliere, 'fil2' => $filFiliere,
]);
$etudiants = $stmt->fetchAll();

// Petite fonction pour construire un lien de page en gardant la recherche/filtre
function lienPage($p, $q, $fil) {
  $params = ['page' => $p];
  if ($q !== '')   { $params['q'] = $q; }
  if ($fil !== '') { $params['filiere'] = $fil; }
  return 'etudiantSecretaire.php?' . http_build_query($params);
}

// Statistiques globales
$stats = $pdo->query("SELECT SUM(sexe='M') AS g, SUM(sexe='F') AS f, COUNT(*) AS total FROM etudiant")->fetch();
$nbClasses = $pdo->query("SELECT COUNT(*) FROM classe")->fetchColumn();

// Listes déroulantes
$classesListe  = $pdo->query("SELECT id_classe, nom_classe FROM classe ORDER BY nom_classe")->fetchAll();
$filieresListe = $pdo->query("SELECT id_filiere, nom_filiere FROM filiere ORDER BY nom_filiere")->fetchAll();

// Badge sexe
function sexeBadge($s) {
  if ($s === 'F') {
    return '<span class="rounded-full border border-[#fbcfe8] bg-[#fdf2f8] px-2 py-0.5 text-xs font-medium text-[#be185d]">Féminin</span>';
  }
  return '<span class="rounded-full border border-[#bfdbfe] bg-[#eff6ff] px-2 py-0.5 text-xs font-medium text-[#1d4ed8]">Masculin</span>';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Étudiants</title>
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
      <symbol id="i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/></symbol>
      <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></symbol>
      <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
      <symbol id="i-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
      <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
      <symbol id="i-filter" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M7 12h10M10 18h4"/></symbol>
      <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></symbol>
      <symbol id="i-male" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="14" r="6"/><path d="M19 5l-6 6M15 5h4v4"/></symbol>
      <symbol id="i-female" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M12 14v7M9 18h6"/></symbol>
      <symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></symbol>
      <symbol id="i-user-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="4"/><path d="M3 21v-1a6 6 0 0 1 12 0v1"/><path d="M17 9v6M20 12h-6"/></symbol>
      <symbol id="i-camera" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></symbol>
      <symbol id="i-chevron-right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></symbol>
    </defs>
  </svg>

  <!-- ============ SIDEBAR ============ -->
  <?php $active = 'etudiants'; include 'sidebarSecretaire.php'; ?>

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

        <!-- En-tête -->
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <nav class="flex items-center gap-1.5 text-xs text-muted">
              <span>Scolarité</span><svg class="h-3.5 w-3.5"><use href="#i-chevron-right" /></svg>
              <span class="font-semibold text-primary">Étudiants</span>
            </nav>
            <h1 class="mt-1 text-2xl font-bold leading-8 tracking-[-0.6px] text-[#1a1b22]">Liste des étudiants</h1>
            <p class="mt-1 text-xs text-muted">Annuaire officiel des apprenants inscrits à l'École 221</p>
          </div>
          <div class="flex items-center gap-3">
            <button type="button" id="btnAdd" class="flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark"><svg class="h-3.5 w-3.5"><use href="#i-plus" /></svg> Ajouter un Étudiant</button>
          </div>
        </div>

        <!-- Cartes stats -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
          <div class="flex items-center justify-between rounded-xl bg-white p-5 shadow-sm">
            <div><p class="text-xs text-muted">Total Inscrits</p><p class="mt-1 text-3xl font-bold text-[#1a1b22]"><?= (int)$stats['total'] ?></p></div>
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-5 w-5"><use href="#i-users" /></svg></span>
          </div>
          <div class="flex items-center justify-between rounded-xl bg-white p-5 shadow-sm">
            <div><p class="text-xs text-muted">Féminin</p><p class="mt-1 text-3xl font-bold text-[#1a1b22]"><?= (int)$stats['f'] ?></p></div>
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#fdf2f8] text-[#db2777]"><svg class="h-5 w-5"><use href="#i-female" /></svg></span>
          </div>
          <div class="flex items-center justify-between rounded-xl bg-white p-5 shadow-sm">
            <div><p class="text-xs text-muted">Masculin</p><p class="mt-1 text-3xl font-bold text-[#1a1b22]"><?= (int)$stats['g'] ?></p></div>
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#eff6ff] text-[#2563eb]"><svg class="h-5 w-5"><use href="#i-male" /></svg></span>
          </div>
          <div class="flex items-center justify-between rounded-xl bg-white p-5 shadow-sm">
            <div><p class="text-xs text-muted">Classes</p><p class="mt-1 text-3xl font-bold text-[#1a1b22]"><?= (int)$nbClasses ?></p></div>
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-5 w-5"><use href="#i-book" /></svg></span>
          </div>
        </div>

        <!-- Panneau tableau -->
        <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">
          <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
              <h2 class="font-bold text-[#1a1b22]">Effectif répertorié</h2>
              <span class="rounded-full bg-primary px-2.5 py-0.5 text-xs font-semibold text-white"><?= $totalEtudiants ?> étudiant<?= $totalEtudiants > 1 ? 's' : '' ?></span>
            </div>
            <form method="get" class="flex items-center gap-2">
              <div class="relative">
                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-search" /></svg></span>
                <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Rechercher par nom, matricule…" class="w-56 rounded-lg border border-[#e5e7eb] py-2.5 pl-9 pr-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
              </div>
              <select name="filiere" onchange="this.form.submit()" class="rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                <option value="">Toutes les filières</option>
                <?php foreach ($filieresListe as $fl): ?>
                  <option value="<?= $fl['id_filiere'] ?>" <?= $filFiliere == $fl['id_filiere'] ? 'selected' : '' ?>><?= htmlspecialchars($fl['nom_filiere']) ?></option>
                <?php endforeach; ?>
              </select>
              <button type="submit" class="rounded-lg border border-[#e5e7eb] p-2.5 text-muted hover:bg-page" aria-label="Rechercher"><svg class="h-4 w-4"><use href="#i-search" /></svg></button>
            </form>
          </div>

          <div class="mt-5 overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
              <thead class="border-b border-[#e5e7eb]">
                <tr class="text-xs font-semibold uppercase tracking-[0.5px] text-[#6b7280]">
                  <th class="px-4 py-3 font-semibold">Matricule</th>
                  <th class="px-4 py-3 font-semibold">Nom</th>
                  <th class="px-4 py-3 font-semibold">Prénom</th>
                  <th class="px-4 py-3 font-semibold">Sexe</th>
                  <th class="px-4 py-3 font-semibold">Classe</th>
                  <th class="px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-[#f3f4f6]">
                <?php if (count($etudiants) === 0): ?>
                  <tr><td colspan="6" class="px-4 py-10 text-center text-sm text-muted">Aucun étudiant ne correspond à votre recherche.</td></tr>
                <?php else: ?>
                  <?php foreach ($etudiants as $e): ?>
                  <tr class="cursor-pointer transition-colors hover:bg-[#f9fafb]"
                      data-id="<?= $e['id_etudiant'] ?>"
                      data-matricule="<?= htmlspecialchars($e['matricule']) ?>"
                      data-nom="<?= htmlspecialchars($e['nom']) ?>"
                      data-prenom="<?= htmlspecialchars($e['prenom']) ?>"
                      data-sexe="<?= htmlspecialchars($e['sexe']) ?>"
                      data-datenaissance="<?= htmlspecialchars($e['date_naissance'] ?? '') ?>"
                      data-email="<?= htmlspecialchars($e['email'] ?? '') ?>"
                      data-tel="<?= htmlspecialchars($e['telephone'] ?? '') ?>"
                      data-adresse="<?= htmlspecialchars($e['adresse'] ?? '') ?>"
                      data-photo="<?= htmlspecialchars($e['photo'] ?? '') ?>"
                      data-classe="<?= htmlspecialchars($e['id_classe'] ?? '') ?>">
                    <td class="px-4 py-4 font-mono text-xs font-bold text-[#111827]"><?= htmlspecialchars($e['matricule']) ?></td>
                    <td class="px-4 py-4 font-bold"><?= htmlspecialchars($e['nom']) ?></td>
                    <td class="px-4 py-4 text-[#374151]"><?= htmlspecialchars($e['prenom']) ?></td>
                    <td class="px-4 py-4"><?= sexeBadge($e['sexe']) ?></td>
                    <td class="px-4 py-4 font-medium text-[#1f2937]"><?= htmlspecialchars($e['nom_classe'] ?? 'Non affecté') ?></td>
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

          <!-- Pagination -->
          <?php
            $debut = $totalEtudiants === 0 ? 0 : $offset + 1;
            $fin   = min($offset + $parPage, $totalEtudiants);
          ?>
          <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
            <p class="text-xs text-muted">Affichage de <?= $debut ?> à <?= $fin ?> sur <?= $totalEtudiants ?> étudiant<?= $totalEtudiants > 1 ? 's' : '' ?></p>
            <?php if ($totalPages > 1): ?>
            <div class="flex items-center gap-1.5">
              <?php if ($page > 1): ?>
                <a href="<?= lienPage($page - 1, $q, $filFiliere) ?>" class="flex h-8 items-center justify-center rounded-lg border border-[#e5e7eb] px-3 text-sm font-medium text-[#374151] hover:bg-page">Précédent</a>
              <?php endif; ?>
              <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <?php if ($p == $page): ?>
                  <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-sm font-medium text-white"><?= $p ?></span>
                <?php else: ?>
                  <a href="<?= lienPage($p, $q, $filFiliere) ?>" class="flex h-8 w-8 items-center justify-center rounded-lg border border-[#e5e7eb] text-sm font-medium text-[#374151] hover:bg-page"><?= $p ?></a>
                <?php endif; ?>
              <?php endfor; ?>
              <?php if ($page < $totalPages): ?>
                <a href="<?= lienPage($page + 1, $q, $filFiliere) ?>" class="flex h-8 items-center justify-center rounded-lg border border-[#e5e7eb] px-3 text-sm font-medium text-[#374151] hover:bg-page">Suivant</a>
              <?php endif; ?>
            </div>
            <?php endif; ?>
          </div>
        </section>
      </div>
    </main>
  </div>

  <!-- ============ DRAWER : AJOUTER / MODIFIER UN ÉTUDIANT ============ -->
  <div id="drawerAdd" class="fixed inset-0 z-50 hidden">
    <div data-close class="absolute inset-0 bg-black/50"></div>
    <aside class="absolute inset-y-0 right-0 flex w-full max-w-lg translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out">
      <div class="flex items-center justify-between border-b border-[#eef0f2] px-6 py-4">
        <div class="flex items-center gap-3">
          <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-5 w-5"><use href="#i-user-plus" /></svg></span>
          <div>
            <h3 id="drawerTitle" class="text-lg font-bold text-[#1a1b22]"><?= ($_POST['id_etudiant'] ?? '') !== '' ? "Modifier l'étudiant" : 'Ajouter un étudiant' ?></h3>
            <p class="text-xs text-muted">Remplissez les informations de l'étudiant</p>
          </div>
        </div>
        <button type="button" data-close class="rounded-lg p-1.5 text-muted hover:bg-page" aria-label="Fermer"><svg class="h-5 w-5"><use href="#i-x" /></svg></button>
      </div>

      <form id="etudiantForm" method="post" enctype="multipart/form-data" class="flex flex-1 flex-col gap-5 overflow-y-auto px-6 py-5">

        <input type="hidden" name="id_etudiant" id="e-id" value="<?= htmlspecialchars($_POST['id_etudiant'] ?? '') ?>" />
        <input type="hidden" name="photo_actuelle" id="e-photo-actuelle" value="<?= htmlspecialchars($_POST['photo_actuelle'] ?? '') ?>" />

        <!-- Photo de profil -->
        <div class="flex flex-col items-center gap-3">
          <div class="relative">
            <img id="e-photo-preview" src="https://i.pravatar.cc/120?img=12" alt="Photo" class="h-20 w-20 rounded-full object-cover ring-2 ring-[#ffdad7]" />
            <label for="e-photo" class="absolute -bottom-1 -right-1 flex h-7 w-7 cursor-pointer items-center justify-center rounded-full bg-primary text-white shadow hover:bg-primarydark" title="Changer la photo"><svg class="h-3.5 w-3.5"><use href="#i-camera" /></svg></label>
            <input id="e-photo" type="file" name="photo" accept="image/*" class="sr-only" />
          </div>
          <p class="text-xs text-muted">Photo de profil (jpg, png, webp — 2 Mo max)</p>
          <?php if ($erreurPhoto !== ""): ?>
            <p class="flex items-center gap-1.5 text-xs text-[#b8001a]"><svg class="h-3.5 w-3.5 shrink-0"><use href="#i-alert" /></svg> <span><?= htmlspecialchars($erreurPhoto) ?></span></p>
          <?php endif; ?>
        </div>

        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-matricule">Matricule</label>
          <input id="e-matricule" type="text" name="matricule" value="<?= htmlspecialchars($_POST['matricule'] ?? '') ?>" placeholder="ex: 221-SN-2024-0045" class="w-full rounded-lg border px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= $erreurMatricule !== '' ? 'border-[#ffb3b3] focus:border-[#e11d2a]' : 'border-[#e5e7eb] focus:border-primary' ?>" />
          <?php if ($erreurMatricule !== ""): ?>
            <p class="mt-1.5 flex items-start gap-1.5 text-xs text-[#b8001a]"><svg class="mt-0.5 h-3.5 w-3.5 shrink-0"><use href="#i-alert" /></svg> <span><?= htmlspecialchars($erreurMatricule) ?></span></p>
          <?php endif; ?>
        </div>

        <div class="flex flex-col gap-4">
          <h4 class="text-xs font-semibold uppercase tracking-wide text-[#1a1b22]">Informations personnelles</h4>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
              <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-nom">Nom</label>
              <input id="e-nom" type="text" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" placeholder="Nom de famille" class="w-full rounded-lg border px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= $erreurNom !== '' ? 'border-[#ffb3b3] focus:border-[#e11d2a]' : 'border-[#e5e7eb] focus:border-primary' ?>" />
              <?php if ($erreurNom !== ""): ?>
                <p class="mt-1.5 flex items-start gap-1.5 text-xs text-[#b8001a]"><svg class="mt-0.5 h-3.5 w-3.5 shrink-0"><use href="#i-alert" /></svg> <span><?= htmlspecialchars($erreurNom) ?></span></p>
              <?php endif; ?>
            </div>
            <div>
              <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-prenom">Prénom</label>
              <input id="e-prenom" type="text" name="prenom" value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" placeholder="Prénom(s)" class="w-full rounded-lg border px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= $erreurPrenom !== '' ? 'border-[#ffb3b3] focus:border-[#e11d2a]' : 'border-[#e5e7eb] focus:border-primary' ?>" />
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
              <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-naissance">Date de naissance</label>
              <input id="e-naissance" type="date" name="date_naissance" value="<?= htmlspecialchars($_POST['date_naissance'] ?? '') ?>" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
            </div>
          </div>
          <div>
            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-adresse">Adresse</label>
            <input id="e-adresse" type="text" name="adresse" value="<?= htmlspecialchars($_POST['adresse'] ?? '') ?>" placeholder="ex: Scat Urbam villa E/55" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
          </div>
        </div>

        <div class="flex flex-col gap-4">
          <h4 class="text-xs font-semibold uppercase tracking-wide text-[#1a1b22]">Informations académiques</h4>
          <div>
            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-classe">Classe</label>
            <select id="e-classe" name="id_classe" class="w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
              <option value="">Non affecté</option>
              <?php foreach ($classesListe as $cl): ?>
                <option value="<?= $cl['id_classe'] ?>" <?= ($_POST['id_classe'] ?? '') == $cl['id_classe'] ? 'selected' : '' ?>><?= htmlspecialchars($cl['nom_classe']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="flex flex-col gap-4">
          <h4 class="text-xs font-semibold uppercase tracking-wide text-[#1a1b22]">Informations de contact</h4>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
              <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-tel">Téléphone</label>
              <input id="e-tel" type="tel" name="telephone" value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>" placeholder="+221 77 123 45 67" class="w-full rounded-lg border px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= $erreurTel !== '' ? 'border-[#ffb3b3] focus:border-[#e11d2a]' : 'border-[#e5e7eb] focus:border-primary' ?>" />
              <?php if ($erreurTel !== ""): ?>
                <p class="mt-1.5 flex items-start gap-1.5 text-xs text-[#b8001a]"><svg class="mt-0.5 h-3.5 w-3.5 shrink-0"><use href="#i-alert" /></svg> <span><?= htmlspecialchars($erreurTel) ?></span></p>
              <?php endif; ?>
            </div>
            <div>
              <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-email">Adresse e-mail</label>
              <input id="e-email" type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="prenom.nom@ecole221.sn" class="w-full rounded-lg border px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= $erreurEmail !== '' ? 'border-[#ffb3b3] focus:border-[#e11d2a]' : 'border-[#e5e7eb] focus:border-primary' ?>" />
              <?php if ($erreurEmail !== ""): ?>
                <p class="mt-1.5 flex items-start gap-1.5 text-xs text-[#b8001a]"><svg class="mt-0.5 h-3.5 w-3.5 shrink-0"><use href="#i-alert" /></svg> <span><?= htmlspecialchars($erreurEmail) ?></span></p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </form>

      <div class="grid grid-cols-2 gap-3 border-t border-[#eef0f2] px-6 py-4">
        <button type="button" data-close class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="submit" form="etudiantForm" class="flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-check" /></svg> Enregistrer</button>
      </div>
    </aside>
  </div>

  <!-- ============ MODALE : CONFIRMER SUPPRESSION ============ -->
  <div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-xl">
      <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-7 w-7"><use href="#i-trash" /></svg></div>
      <h3 class="text-lg font-bold text-[#1a1b22]">Confirmer la suppression</h3>
      <p class="mt-2 text-sm text-muted">Voulez-vous vraiment supprimer l'étudiant <span id="delName" class="font-semibold text-[#1a1b22]"></span> ? Cette action est irréversible.</p>
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
    <input type="hidden" name="date_naissance" id="restore_datenaissance" value="" />
    <input type="hidden" name="email" id="restore_email" value="" />
    <input type="hidden" name="telephone" id="restore_tel" value="" />
    <input type="hidden" name="adresse" id="restore_adresse" value="" />
    <input type="hidden" name="id_classe" id="restore_classe" value="" />
  </form>

  <div id="toasts" class="fixed right-4 top-4 z-[60] flex w-[320px] max-w-[calc(100vw-2rem)] flex-col gap-2"></div>

  <script>
    /* ----- Menu mobile ----- */
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleMenu = (open) => {
      sidebar.classList.toggle('-translate-x-full', !open);
      overlay.classList.toggle('hidden', !open);
      document.body.classList.toggle('overflow-hidden', open);
    };
    document.getElementById('openMenu').addEventListener('click', () => toggleMenu(true));
    document.getElementById('closeMenu').addEventListener('click', () => toggleMenu(false));
    overlay.addEventListener('click', () => toggleMenu(false));

    const PHOTO_DEFAUT = 'https://i.pravatar.cc/120?img=12';

    /* ----- Toasts ----- */
    function showToast(type, title, msg, action) {
      const map = {
        success: { border: '#16a34a', icon: '#i-check', tint: '#dcfce7', text: '#15803d' },
        error:   { border: '#e11d2a', icon: '#i-alert', tint: '#ffdad7', text: '#b8001a' },
        info:    { border: '#6b7280', icon: '#i-alert', tint: '#f3f4f6', text: '#374151' },
      };
      const c = map[type] || map.info;
      const el = document.createElement('div');
      el.className = 'rounded-xl border-l-4 bg-white p-3.5 shadow-md';
      el.style.borderLeftColor = c.border;
      el.innerHTML =
        '<div class="flex items-start gap-3">' +
          '<span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full" style="background:' + c.tint + ';color:' + c.text + '"><svg class="h-4 w-4"><use href="' + c.icon + '"/></svg></span>' +
          '<div class="flex-1"><p class="text-sm font-semibold text-[#1a1b22]">' + title + '</p>' + (msg ? '<p class="text-xs text-muted">' + msg + '</p>' : '') + '</div>' +
          '<button class="js-close text-muted hover:text-ink" aria-label="Fermer"><svg class="h-4 w-4"><use href="#i-x"/></svg></button>' +
        '</div>' +
        (action ? '<div class="mt-2 flex justify-end"><button class="js-action rounded-lg border border-[#e5e7eb] px-3 py-1 text-xs font-medium text-primary hover:bg-page">' + action.label + '</button></div>' : '');
      el.querySelector('.js-close').addEventListener('click', () => el.remove());
      if (action) el.querySelector('.js-action').addEventListener('click', () => { action.onClick(); el.remove(); });
      document.getElementById('toasts').appendChild(el);
      setTimeout(() => el.remove(), action ? 6000 : 3500);
    }

    /* ----- Drawer ----- */
    const drawerAdd = document.getElementById('drawerAdd');
    const drawerPanel = drawerAdd.querySelector('aside');
    const drawerTitle = document.getElementById('drawerTitle');
    const etudiantForm = document.getElementById('etudiantForm');
    const photoPreview = document.getElementById('e-photo-preview');

    const openDrawerPanel = () => {
      drawerAdd.classList.remove('hidden');
      requestAnimationFrame(() => drawerPanel.classList.remove('translate-x-full'));
      document.body.classList.add('overflow-hidden');
    };
    const closeDrawer = () => {
      drawerPanel.classList.add('translate-x-full');
      document.body.classList.remove('overflow-hidden');
      setTimeout(() => drawerAdd.classList.add('hidden'), 300);
    };

    /* ----- Aperçu de la photo choisie ----- */
    document.getElementById('e-photo').addEventListener('change', (ev) => {
      const file = ev.target.files[0];
      if (file) photoPreview.src = URL.createObjectURL(file);
    });

    /* ----- Ajout ----- */
    document.getElementById('btnAdd').addEventListener('click', () => {
      etudiantForm.reset();
      document.getElementById('e-id').value = '';
      document.getElementById('e-photo-actuelle').value = '';
      photoPreview.src = PHOTO_DEFAUT;
      drawerTitle.textContent = 'Ajouter un étudiant';
      openDrawerPanel();
    });
    drawerAdd.querySelectorAll('[data-close]').forEach((b) => b.addEventListener('click', closeDrawer));
    drawerAdd.addEventListener('click', (e) => { if (!drawerPanel.contains(e.target)) closeDrawer(); });

    /* ----- Modification ----- */
    function openDrawerEdit(d) {
      etudiantForm.reset();
      document.getElementById('e-id').value        = d.id;
      document.getElementById('e-matricule').value = d.matricule;
      document.getElementById('e-nom').value       = d.nom;
      document.getElementById('e-prenom').value    = d.prenom;
      document.querySelectorAll('input[name="sexe"]').forEach((r) => { r.checked = (r.value === d.sexe); });
      document.getElementById('e-naissance').value = d.datenaissance;
      document.getElementById('e-adresse').value   = d.adresse;
      document.getElementById('e-classe').value    = d.classe;
      document.getElementById('e-tel').value        = d.tel;
      document.getElementById('e-email').value      = d.email;
      document.getElementById('e-photo-actuelle').value = d.photo || '';
      photoPreview.src = d.photo ? d.photo : PHOTO_DEFAUT;
      drawerTitle.textContent = "Modifier l'étudiant";
      openDrawerPanel();
    }

    /* ----- Clic sur une ligne : fiche détail. Boutons : modifier / supprimer ----- */
    document.querySelectorAll('tbody tr[data-id]').forEach((tr) => {
      tr.addEventListener('click', () => { window.location.href = 'detailsEtudiantSecretaire.php?id=' + tr.dataset.id; });

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

    /* ----- Échap ferme tout ----- */
    window.addEventListener('keydown', (e) => {
      if (e.key !== 'Escape') return;
      closeDrawer(); closeModal(modalDelete); toggleMenu(false);
    });

    /* ----- Rouvrir le drawer s'il y a une erreur de validation ----- */
    <?php if ($erreurMatricule !== "" || $erreurNom !== "" || $erreurPrenom !== "" || $erreurEmail !== "" || $erreurTel !== "" || $erreurPhoto !== ""): ?>
    openDrawerPanel();
    <?php endif; ?>

    /* ----- Toasts après une action ----- */
    <?php if (isset($_GET['ok']) && $_GET['ok'] === 'ajout'): ?>
    showToast('success', 'Étudiant ajouté', "L'étudiant a bien été enregistré.");
    <?php elseif (isset($_GET['ok']) && $_GET['ok'] === 'modification'): ?>
    showToast('success', 'Étudiant modifié', 'Les informations ont bien été mises à jour.');
    <?php elseif (isset($_GET['ok']) && $_GET['ok'] === 'suppression'): ?>
    showToast('success', 'Étudiant supprimé', "L'étudiant a bien été retiré de la liste.", {
      label: 'Annuler',
      onClick: () => {
        document.getElementById('restore_matricule').value     = <?= json_encode($_GET['matricule'] ?? '') ?>;
        document.getElementById('restore_nom').value           = <?= json_encode($_GET['nom'] ?? '') ?>;
        document.getElementById('restore_prenom').value        = <?= json_encode($_GET['prenom'] ?? '') ?>;
        document.getElementById('restore_sexe').value          = <?= json_encode($_GET['sexe'] ?? 'M') ?>;
        document.getElementById('restore_datenaissance').value = <?= json_encode($_GET['daten'] ?? '') ?>;
        document.getElementById('restore_email').value         = <?= json_encode($_GET['email'] ?? '') ?>;
        document.getElementById('restore_tel').value           = <?= json_encode($_GET['tel'] ?? '') ?>;
        document.getElementById('restore_adresse').value       = <?= json_encode($_GET['adresse'] ?? '') ?>;
        document.getElementById('restore_classe').value        = <?= json_encode($_GET['classe'] ?? '') ?>;
        document.getElementById('restoreForm').submit();
      }
    });
    <?php endif; ?>

    /* ----- Nettoyer l'URL après une action ----- */
    <?php if (isset($_GET['ok'])): ?>
    history.replaceState({}, '', 'etudiantSecretaire.php');
    <?php endif; ?>
  </script>
</body>
</html>