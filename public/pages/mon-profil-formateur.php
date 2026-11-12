<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'formateur') {
  header('Location: loginEcole221.php');
  exit;
}

require __DIR__ . '/../../config/database.php';

$idFormateur = $_SESSION['id_formateur'] ?? 0;

$errInfo = [];      // erreurs du formulaire d'infos
$errMdp  = [];      // erreurs du formulaire mot de passe
$infoPost = null;   // valeurs saisies à réafficher en cas d'erreur
$ongletActif = 'perso';

// ===================== TRAITEMENTS POST =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  // ---------- 1) Informations personnelles ----------
  if ($action === 'infos') {
    $infoPost = $_POST;
    $prenom = trim($_POST['prenom'] ?? '');
    $nom    = trim($_POST['nom'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $tel    = trim($_POST['telephone'] ?? '');
    $spec   = trim($_POST['specialite'] ?? '');
    $sexe   = $_POST['sexe'] ?? '';

    if (!preg_match("/^[\p{L}][\p{L} '\-]{1,59}$/u", $prenom)) $errInfo['prenom'] = 'Prénom invalide (2 à 60 lettres).';
    if (!preg_match("/^[\p{L}][\p{L} '\-]{1,59}$/u", $nom))    $errInfo['nom']    = 'Nom invalide (2 à 60 lettres).';
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errInfo['email'] = 'Adresse e-mail invalide.';
    if ($tel !== '' && !preg_match('/^[0-9+\s]{6,30}$/', $tel)) $errInfo['telephone'] = 'Téléphone invalide.';
    if ($sexe !== 'M' && $sexe !== 'F') $errInfo['sexe'] = 'Veuillez choisir le sexe.';

    if (empty($errInfo)) {
      try {
        $stmt = $pdo->prepare("UPDATE formateur SET prenom = :p, nom = :n, email = :e, telephone = :t,
                               specialite = :sp, sexe = :s WHERE id_formateur = :id");
        $stmt->execute([
          'p' => $prenom, 'n' => $nom,
          'e' => ($email === '' ? null : $email),
          't' => ($tel === '' ? null : $tel),
          'sp' => ($spec === '' ? null : $spec),
          's' => $sexe,
          'id' => $idFormateur,
        ]);
        header('Location: mon-profil-formateur.php?ok=infos');
        exit;
      } catch (PDOException $e) {
        $errInfo['email'] = 'Cet e-mail est déjà utilisé par un autre formateur.';
      }
    }
    $ongletActif = 'perso';
  }

  // ---------- 2) Changement de photo ----------
  elseif ($action === 'photo') {
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
      $infos = getimagesize($_FILES['photo']['tmp_name']);
      $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
      $extsOk = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
      if ($infos === false || !in_array($ext, $extsOk)) {
        header('Location: mon-profil-formateur.php?err=photo');
        exit;
      }
      if ($_FILES['photo']['size'] > 2 * 1024 * 1024) { // 2 Mo max
        header('Location: mon-profil-formateur.php?err=taille');
        exit;
      }
      $dossier = __DIR__ . '/../uploads/formateurs/';
      if (!is_dir($dossier)) mkdir($dossier, 0777, true);
      $nomFichier = 'form_' . $idFormateur . '_' . time() . '.' . $ext;
      if (move_uploaded_file($_FILES['photo']['tmp_name'], $dossier . $nomFichier)) {
        $cheminPhoto = '../uploads/formateurs/' . $nomFichier;
        $stmt = $pdo->prepare("UPDATE formateur SET photo = :photo WHERE id_formateur = :id");
        $stmt->execute(['photo' => $cheminPhoto, 'id' => $idFormateur]);
        header('Location: mon-profil-formateur.php?ok=photo');
        exit;
      }
    }
    header('Location: mon-profil-formateur.php?err=photo');
    exit;
  }

  // ---------- 3) Changement de mot de passe ----------
  elseif ($action === 'motdepasse') {
    $actuel  = $_POST['actuel'] ?? '';
    $nouveau = $_POST['nouveau'] ?? '';
    $confirm = $_POST['confirmer'] ?? '';

    $stmt = $pdo->prepare("SELECT id_utilisateur, mot_de_passe FROM utilisateur WHERE id_formateur = :id AND role = 'formateur' LIMIT 1");
    $stmt->execute(['id' => $idFormateur]);
    $u = $stmt->fetch();

    if (!$u || !password_verify($actuel, $u['mot_de_passe'])) $errMdp['actuel'] = 'Mot de passe actuel incorrect.';
    if (strlen($nouveau) < 6) $errMdp['nouveau'] = '6 caractères minimum.';
    if ($nouveau !== $confirm) $errMdp['confirmer'] = 'La confirmation ne correspond pas.';

    if (empty($errMdp)) {
      $hash = password_hash($nouveau, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("UPDATE utilisateur SET mot_de_passe = :mp WHERE id_utilisateur = :id");
      $stmt->execute(['mp' => $hash, 'id' => $u['id_utilisateur']]);
      header('Location: mon-profil-formateur.php?ok=motdepasse');
      exit;
    }
    $ongletActif = 'compte';
  }
}

// ===================== DONNÉES D'AFFICHAGE =====================
$stmt = $pdo->prepare("SELECT * FROM formateur WHERE id_formateur = :id");
$stmt->execute(['id' => $idFormateur]);
$fo = $stmt->fetch();

function val($champ, $infoPost, $fo) {
  if ($infoPost !== null) return $infoPost[$champ] ?? '';
  return $fo[$champ] ?? '';
}

$nomComplet = trim(($fo['prenom'] ?? '') . ' ' . ($fo['nom'] ?? ''));
if ($nomComplet === '') $nomComplet = $_SESSION['login'] ?? 'Formateur';
$photo     = !empty($fo['photo']) ? $fo['photo'] : '';
$initiales = strtoupper(mb_substr($fo['prenom'] ?? '', 0, 1) . mb_substr($fo['nom'] ?? '', 0, 1));
$statut    = $fo['statut'] ?? 'Actif';

// Matières enseignées
$stmt = $pdo->prepare("SELECT m.nom_matiere FROM enseigner en JOIN matiere m ON m.id_matiere = en.id_matiere WHERE en.id_formateur = :id ORDER BY m.nom_matiere");
$stmt->execute(['id' => $idFormateur]);
$matieres = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Nombre de classes et de séances
$stmt = $pdo->prepare("SELECT COUNT(DISTINCT id_classe) FROM seance WHERE id_formateur = :id");
$stmt->execute(['id' => $idFormateur]);
$nbClasses = (int)$stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM seance WHERE id_formateur = :id");
$stmt->execute(['id' => $idFormateur]);
$nbSeances = (int)$stmt->fetchColumn();

function ongletBtn($cle, $actif) {
  return $cle === $actif
    ? '-mb-px border-b-2 border-primary px-1 pb-3 font-semibold text-primary'
    : '-mb-px border-b-2 border-transparent px-1 pb-3 text-muted transition hover:text-ink';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Mon profil (Formateur)</title>
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
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
      <symbol id="i-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-camera" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2Z"/><circle cx="12" cy="13" r="4"/></symbol>
      <symbol id="i-board" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h18M4 3v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3M9 21l3-4 3 4"/></symbol>
      <symbol id="i-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
      <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
    </defs>
  </svg>

  <!-- ===================== SIDEBAR ===================== -->
  <?php $active = 'profil'; include __DIR__ . '/../../includes/sidebarFormateur.php'; ?>

  <!-- ===================== CONTENU ===================== -->
  <div class="app-content min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[88px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-10">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <div>
          <p class="text-[11px] font-semibold uppercase tracking-wide text-primary">Compte formateur</p>
          <h1 class="text-lg font-bold leading-tight">Mon profil</h1>
        </div>
      </div>
      <div class="flex items-center gap-3 sm:gap-4">
        <div class="flex items-center gap-2">
          <?php if ($photo): ?>
            <img src="<?= htmlspecialchars($photo) ?>" alt="Photo" class="h-9 w-9 rounded-full object-cover" />
          <?php else: ?>
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#fdeaec] text-xs font-bold text-primary"><?= htmlspecialchars($initiales) ?></span>
          <?php endif; ?>
          <div class="hidden sm:block"><p class="text-sm font-semibold leading-[17.5px]"><?= htmlspecialchars($nomComplet) ?></p><p class="text-xs leading-3 text-muted"><?= htmlspecialchars($fo['specialite'] ?? 'Formateur') ?></p></div>
        </div>
        <span class="whitespace-nowrap rounded-full bg-primary px-2.5 py-1 text-xs font-semibold text-white shadow-sm">Formateur</span>
      </div>
    </header>

    <main class="px-4 py-7 sm:px-6 lg:px-10">
      <div class="mx-auto flex max-w-[1180px] flex-col gap-6">

        <!-- Carte profil -->
        <section class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm sm:p-6">
          <div class="flex flex-col items-center gap-4 sm:flex-row sm:items-center">
            <div class="relative shrink-0">
              <?php if ($photo): ?>
                <img src="<?= htmlspecialchars($photo) ?>" alt="Photo" class="h-20 w-20 rounded-full object-cover ring-2 ring-[#fdeaec]" />
              <?php else: ?>
                <span class="flex h-20 w-20 items-center justify-center rounded-full bg-[#fdeaec] text-2xl font-bold text-primary ring-2 ring-[#fdeaec]"><?= htmlspecialchars($initiales) ?></span>
              <?php endif; ?>
              <form id="formPhoto" method="post" enctype="multipart/form-data" class="hidden">
                <input type="hidden" name="action" value="photo" />
                <input id="photoInput" type="file" name="photo" accept="image/*" onchange="document.getElementById('formPhoto').submit()" />
              </form>
            </div>
            <div class="flex-1 text-center sm:text-left">
              <div class="flex flex-wrap items-center justify-center gap-2 sm:justify-start">
                <h2 class="text-xl font-extrabold text-[#1a1b22]"><?= htmlspecialchars($nomComplet) ?></h2>
                <span class="rounded-full bg-[#dcfce7] px-2.5 py-0.5 text-xs font-semibold text-[#15803d]"><?= htmlspecialchars($statut) ?></span>
              </div>
              <p class="mt-1 text-sm text-muted"><?= htmlspecialchars($fo['email'] ?? 'E-mail non renseigné') ?><?= !empty($fo['telephone']) ? ' · ' . htmlspecialchars($fo['telephone']) : '' ?></p>
              <p class="mt-0.5 text-sm"><span class="font-semibold text-primary">Matricule <?= htmlspecialchars($fo['matricule_formateur'] ?? '—') ?></span><?= !empty($fo['specialite']) ? ' <span class="text-muted">· ' . htmlspecialchars($fo['specialite']) . '</span>' : '' ?></p>
            </div>
            <button type="button" onclick="document.getElementById('photoInput').click()" class="inline-flex items-center gap-2 rounded-lg border border-primary px-4 py-2.5 text-sm font-semibold text-primary transition hover:bg-primary hover:text-white">
              <svg class="h-4 w-4"><use href="#i-camera" /></svg> Modifier la photo
            </button>
          </div>
        </section>

        <!-- Onglets -->
        <div class="flex gap-6 border-b border-[#ececef] text-sm font-medium">
          <button data-tab="perso" class="js-tab <?= ongletBtn('perso', $ongletActif) ?>">Informations personnelles</button>
          <button data-tab="activite" class="js-tab <?= ongletBtn('activite', $ongletActif) ?>">Mon activité</button>
          <button data-tab="compte" class="js-tab <?= ongletBtn('compte', $ongletActif) ?>">Mot de passe</button>
        </div>

        <!-- Grille principale -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-[1.7fr_1fr]">

          <!-- Colonne gauche : contenu des onglets -->
          <div>
            <!-- Onglet : Informations personnelles -->
            <section data-panel="perso" class="<?= $ongletActif === 'perso' ? '' : 'hidden' ?> rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm sm:p-6">
              <h3 class="font-bold text-[#1a1b22]">Informations personnelles</h3>
              <p class="mt-0.5 text-xs text-muted">Ces informations apparaissent sur votre fiche formateur.</p>

              <form method="post" class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <input type="hidden" name="action" value="infos" />
                <label class="block"><span class="text-xs font-medium text-muted">Prénom</span>
                  <input type="text" name="prenom" value="<?= htmlspecialchars(val('prenom', $infoPost, $fo)) ?>" class="mt-1 w-full rounded-lg border bg-white px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= isset($errInfo['prenom']) ? 'border-primary' : 'border-[#e5e7eb] focus:border-primary' ?>" />
                  <?php if (isset($errInfo['prenom'])): ?><span class="mt-1 block text-xs text-primary"><?= $errInfo['prenom'] ?></span><?php endif; ?></label>
                <label class="block"><span class="text-xs font-medium text-muted">Nom</span>
                  <input type="text" name="nom" value="<?= htmlspecialchars(val('nom', $infoPost, $fo)) ?>" class="mt-1 w-full rounded-lg border bg-white px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= isset($errInfo['nom']) ? 'border-primary' : 'border-[#e5e7eb] focus:border-primary' ?>" />
                  <?php if (isset($errInfo['nom'])): ?><span class="mt-1 block text-xs text-primary"><?= $errInfo['nom'] ?></span><?php endif; ?></label>
                <label class="block"><span class="text-xs font-medium text-muted">Adresse e-mail</span>
                  <input type="email" name="email" value="<?= htmlspecialchars(val('email', $infoPost, $fo)) ?>" class="mt-1 w-full rounded-lg border bg-white px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= isset($errInfo['email']) ? 'border-primary' : 'border-[#e5e7eb] focus:border-primary' ?>" />
                  <?php if (isset($errInfo['email'])): ?><span class="mt-1 block text-xs text-primary"><?= $errInfo['email'] ?></span><?php endif; ?></label>
                <label class="block"><span class="text-xs font-medium text-muted">Téléphone</span>
                  <input type="tel" name="telephone" value="<?= htmlspecialchars(val('telephone', $infoPost, $fo)) ?>" class="mt-1 w-full rounded-lg border bg-white px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= isset($errInfo['telephone']) ? 'border-primary' : 'border-[#e5e7eb] focus:border-primary' ?>" />
                  <?php if (isset($errInfo['telephone'])): ?><span class="mt-1 block text-xs text-primary"><?= $errInfo['telephone'] ?></span><?php endif; ?></label>
                <label class="block"><span class="text-xs font-medium text-muted">Spécialité</span>
                  <input type="text" name="specialite" maxlength="100" value="<?= htmlspecialchars(val('specialite', $infoPost, $fo)) ?>" class="mt-1 w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></label>
                <label class="block"><span class="text-xs font-medium text-muted">Sexe</span>
                  <?php $sx = val('sexe', $infoPost, $fo); ?>
                  <select name="sexe" class="mt-1 w-full rounded-lg border bg-white px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= isset($errInfo['sexe']) ? 'border-primary' : 'border-[#e5e7eb] focus:border-primary' ?>">
                    <option value="">Choisir…</option>
                    <option value="M" <?= $sx === 'M' ? 'selected' : '' ?>>Masculin</option>
                    <option value="F" <?= $sx === 'F' ? 'selected' : '' ?>>Féminin</option>
                  </select>
                  <?php if (isset($errInfo['sexe'])): ?><span class="mt-1 block text-xs text-primary"><?= $errInfo['sexe'] ?></span><?php endif; ?></label>

                <div class="sm:col-span-2 mt-1 flex flex-wrap items-center justify-end gap-2 border-t border-[#ececef] pt-4">
                  <a href="mon-profil-formateur.php" class="inline-flex items-center gap-1.5 rounded-lg border border-[#e5e7eb] px-4 py-2 text-sm font-medium text-[#374151] transition hover:bg-page"><svg class="h-4 w-4"><use href="#i-x" /></svg> Annuler</a>
                  <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-check" /></svg> Enregistrer</button>
                </div>
              </form>
            </section>

            <!-- Onglet : Mon activité -->
            <section data-panel="activite" class="<?= $ongletActif === 'activite' ? '' : 'hidden' ?> rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm sm:p-6">
              <h3 class="font-bold text-[#1a1b22]">Mon activité</h3>
              <dl class="mt-4 flex flex-col divide-y divide-[#f1f1f3] text-sm">
                <div class="flex items-center justify-between gap-4 py-3"><dt class="text-muted">Spécialité</dt><dd class="font-medium text-[#1a1b22]"><?= htmlspecialchars($fo['specialite'] ?? '—') ?></dd></div>
                <div class="flex items-center justify-between gap-4 py-3"><dt class="text-muted">Statut</dt><dd class="font-medium text-[#1a1b22]"><?= htmlspecialchars($statut) ?></dd></div>
                <div class="flex items-center justify-between gap-4 py-3"><dt class="text-muted">Classes</dt><dd class="font-medium text-[#1a1b22]"><?= $nbClasses ?></dd></div>
                <div class="flex items-center justify-between gap-4 py-3"><dt class="text-muted">Séances</dt><dd class="font-medium text-[#1a1b22]"><?= $nbSeances ?></dd></div>
              </dl>
              <h4 class="mt-5 text-xs font-semibold uppercase tracking-wide text-muted">Matières enseignées</h4>
              <div class="mt-2 flex flex-wrap gap-2">
                <?php if (count($matieres) === 0): ?>
                  <span class="text-sm text-muted">Aucune matière assignée.</span>
                <?php else: foreach ($matieres as $m): ?>
                  <span class="rounded-full bg-[#fdeaec] px-3 py-1 text-xs font-semibold text-primary"><?= htmlspecialchars($m) ?></span>
                <?php endforeach; endif; ?>
              </div>
            </section>

            <!-- Onglet : Mot de passe -->
            <section data-panel="compte" class="<?= $ongletActif === 'compte' ? '' : 'hidden' ?> rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm sm:p-6">
              <h3 class="font-bold text-[#1a1b22]">Changer le mot de passe</h3>
              <form method="post" class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <input type="hidden" name="action" value="motdepasse" />
                <label class="block sm:col-span-2"><span class="text-xs font-medium text-muted">Mot de passe actuel</span>
                  <input type="password" name="actuel" class="mt-1 w-full rounded-lg border bg-white px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= isset($errMdp['actuel']) ? 'border-primary' : 'border-[#e5e7eb] focus:border-primary' ?>" />
                  <?php if (isset($errMdp['actuel'])): ?><span class="mt-1 block text-xs text-primary"><?= $errMdp['actuel'] ?></span><?php endif; ?></label>
                <label class="block"><span class="text-xs font-medium text-muted">Nouveau mot de passe</span>
                  <input type="password" name="nouveau" placeholder="••••••••" class="mt-1 w-full rounded-lg border bg-white px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= isset($errMdp['nouveau']) ? 'border-primary' : 'border-[#e5e7eb] focus:border-primary' ?>" />
                  <?php if (isset($errMdp['nouveau'])): ?><span class="mt-1 block text-xs text-primary"><?= $errMdp['nouveau'] ?></span><?php endif; ?></label>
                <label class="block"><span class="text-xs font-medium text-muted">Confirmer le mot de passe</span>
                  <input type="password" name="confirmer" placeholder="••••••••" class="mt-1 w-full rounded-lg border bg-white px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= isset($errMdp['confirmer']) ? 'border-primary' : 'border-[#e5e7eb] focus:border-primary' ?>" />
                  <?php if (isset($errMdp['confirmer'])): ?><span class="mt-1 block text-xs text-primary"><?= $errMdp['confirmer'] ?></span><?php endif; ?></label>
                <div class="sm:col-span-2 flex justify-end border-t border-[#ececef] pt-4">
                  <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primarydark">Mettre à jour</button>
                </div>
              </form>
            </section>
          </div>

          <!-- Colonne droite -->
          <div class="flex flex-col gap-5">
            <!-- Résumé -->
            <section class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm">
              <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#fdeaec] text-primary"><svg class="h-5 w-5"><use href="#i-board" /></svg></span>
                <div><p class="font-bold text-[#1a1b22]"><?= htmlspecialchars($fo['specialite'] ?? 'Formateur') ?></p><p class="text-xs text-muted">Statut : <?= htmlspecialchars($statut) ?></p></div>
              </div>
              <div class="mt-4 grid grid-cols-2 gap-2 text-center">
                <div class="rounded-lg bg-page py-3"><p class="text-lg font-extrabold text-[#1a1b22]"><?= $nbClasses ?></p><p class="text-[11px] text-muted">classes</p></div>
                <div class="rounded-lg bg-page py-3"><p class="text-lg font-extrabold text-[#1a1b22]"><?= $nbSeances ?></p><p class="text-[11px] text-muted">séances</p></div>
              </div>
            </section>

            <!-- Sécurité du compte -->
            <section class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm">
              <h3 class="font-bold text-[#1a1b22]">Sécurité du compte</h3>
              <div class="mt-4 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                  <span class="flex h-9 w-9 items-center justify-center rounded-full bg-page text-muted"><svg class="h-4 w-4"><use href="#i-lock" /></svg></span>
                  <div><p class="text-sm font-medium text-[#1a1b22]">Mot de passe</p><p class="text-xs text-muted">Protégé par chiffrement</p></div>
                </div>
                <button type="button" class="text-sm font-semibold text-primary hover:underline" onclick="document.querySelector('[data-tab=compte]').click()">Modifier</button>
              </div>
              <a href="deconnexion.php" class="mt-4 flex items-center justify-center gap-2 rounded-lg border border-primary px-4 py-2.5 text-sm font-semibold text-primary transition hover:bg-primary hover:text-white"><svg class="h-4 w-4"><use href="#i-logout" /></svg> Se déconnecter</a>
            </section>
          </div>
        </div>

      </div>
    </main>
  </div>

  <div id="toasts" class="fixed right-4 top-4 z-[60] flex w-[320px] max-w-[calc(100vw-2rem)] flex-col gap-2"></div>

  <script>
    /* ----- Menu mobile ----- */
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleMenu = (open) => { sidebar.classList.toggle('-translate-x-full', !open); overlay.classList.toggle('hidden', !open); document.body.classList.toggle('overflow-hidden', open); };
    document.getElementById('openMenu').addEventListener('click', () => toggleMenu(true));
    document.getElementById('closeMenu').addEventListener('click', () => toggleMenu(false));
    overlay.addEventListener('click', () => toggleMenu(false));

    /* ----- Onglets ----- */
    const tabs = document.querySelectorAll('.js-tab');
    const panels = document.querySelectorAll('[data-panel]');
    tabs.forEach((tab) => tab.addEventListener('click', () => {
      tabs.forEach((t) => { t.classList.remove('border-primary', 'text-primary', 'font-semibold'); t.classList.add('border-transparent', 'text-muted'); });
      tab.classList.add('border-primary', 'text-primary', 'font-semibold'); tab.classList.remove('border-transparent', 'text-muted');
      panels.forEach((p) => p.classList.toggle('hidden', p.dataset.panel !== tab.dataset.tab));
    }));

    /* ----- Toast ----- */
    function showToast(type, title, msg) {
      const map = { success: { b:'#16a34a', i:'#i-check', t:'#dcfce7', c:'#15803d' }, error: { b:'#e11d2a', i:'#i-alert', t:'#ffdad7', c:'#b8001a' } };
      const m = map[type] || map.success;
      const el = document.createElement('div');
      el.className = 'flex items-start gap-3 rounded-xl border-l-4 bg-white p-3.5 shadow-md';
      el.style.borderLeftColor = m.b;
      el.innerHTML = '<span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full" style="background:' + m.t + ';color:' + m.c + '"><svg class="h-4 w-4"><use href="' + m.i + '"/></svg></span><div class="flex-1"><p class="text-sm font-semibold text-[#1a1b22]">' + title + '</p>' + (msg ? '<p class="text-xs text-muted">' + msg + '</p>' : '') + '</div><button class="text-muted hover:text-ink"><svg class="h-4 w-4"><use href="#i-x"/></svg></button>';
      el.querySelector('button').addEventListener('click', () => el.remove());
      document.getElementById('toasts').appendChild(el);
      setTimeout(() => el.remove(), 3500);
    }
    <?php if (isset($_GET['ok']) && $_GET['ok'] === 'infos'): ?>
    showToast('success', 'Profil mis à jour', 'Vos informations ont été enregistrées.');
    history.replaceState({}, '', 'mon-profil-formateur.php');
    <?php elseif (isset($_GET['ok']) && $_GET['ok'] === 'photo'): ?>
    showToast('success', 'Photo mise à jour', 'Votre photo de profil a été changée.');
    history.replaceState({}, '', 'mon-profil-formateur.php');
    <?php elseif (isset($_GET['ok']) && $_GET['ok'] === 'motdepasse'): ?>
    showToast('success', 'Mot de passe changé', 'Votre mot de passe a bien été mis à jour.');
    history.replaceState({}, '', 'mon-profil-formateur.php');
    <?php elseif (isset($_GET['err']) && $_GET['err'] === 'photo'): ?>
    showToast('error', 'Fichier invalide', 'Veuillez choisir une image (jpg, png, webp, gif).');
    <?php elseif (isset($_GET['err']) && $_GET['err'] === 'taille'): ?>
    showToast('error', 'Image trop lourde', 'La photo ne doit pas dépasser 2 Mo.');
    <?php endif; ?>
  </script>
</body>
</html>