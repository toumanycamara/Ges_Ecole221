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

$erreurCode = "";
$erreurNom  = "";
$erreurCoef = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // ----- Suppression -----
  if (isset($_POST['delete_id'])) {
    $q = $pdo->prepare("SELECT code_matiere, nom_matiere, coefficient, id_filiere, id_niveau FROM matiere WHERE id_matiere = :id");
    $q->execute(['id' => $_POST['delete_id']]);
    $old = $q->fetch();

    try {
      $stmt = $pdo->prepare("DELETE FROM matiere WHERE id_matiere = :id");
      $stmt->execute(['id' => $_POST['delete_id']]);
    } catch (PDOException $e) {
      header('Location: matiereSecretaire.php?err=suppression');
      exit;
    }

    $url = 'matiereSecretaire.php?ok=suppression';
    if ($old) {
      $url .= '&code='    . urlencode($old['code_matiere'])
            . '&nom='     . urlencode($old['nom_matiere'])
            . '&coef='    . urlencode($old['coefficient'] ?? '1')
            . '&filiere=' . urlencode($old['id_filiere'] ?? '')
            . '&niveau='  . urlencode($old['id_niveau'] ?? '');
    }
    header('Location: ' . $url);
    exit;
  }

  // ----- Ajout ou Modification -----
  $code    = strtoupper(trim($_POST['code'] ?? ''));
  $nom     = trim($_POST['nom'] ?? '');
  $coef    = trim($_POST['coefficient'] ?? '');
  $filiere = $_POST['id_filiere'] ?? '';
  $niveau  = $_POST['id_niveau'] ?? '';
  $id      = $_POST['id_matiere'] ?? '';

  if ($coef === '') { $coef = '1'; }
  $filiere = $filiere === '' ? null : $filiere;
  $niveau  = $niveau  === '' ? null : $niveau;

  // Validation (avec regex)
  if (!preg_match('/^[A-Za-z0-9\-]{2,20}$/', $code)) {
    $erreurCode = "Le code doit faire 2 à 20 caractères (lettres, chiffres, tirets).";
  }
  if (!preg_match('/^[A-Za-zÀ-ÿ0-9 \'&-]{2,100}$/u', $nom)) {
    $erreurNom = "Le nom doit faire 2 à 100 caractères.";
  }
  if (!ctype_digit($coef) || (int)$coef < 1) {
    $erreurCoef = "Le coefficient doit être un nombre supérieur à 0.";
  }

  if ($erreurCode === "" && $erreurNom === "" && $erreurCoef === "") {
    try {
      if ($id === '') {
        $stmt = $pdo->prepare("INSERT INTO matiere (code_matiere, nom_matiere, coefficient, id_filiere, id_niveau)
                               VALUES (:code, :nom, :coef, :filiere, :niveau)");
        $stmt->execute(['code' => $code, 'nom' => $nom, 'coef' => $coef, 'filiere' => $filiere, 'niveau' => $niveau]);
        header('Location: matiereSecretaire.php?ok=ajout');
        exit;
      } else {
        $stmt = $pdo->prepare("UPDATE matiere SET code_matiere = :code, nom_matiere = :nom, coefficient = :coef,
                               id_filiere = :filiere, id_niveau = :niveau WHERE id_matiere = :id");
        $stmt->execute(['code' => $code, 'nom' => $nom, 'coef' => $coef, 'filiere' => $filiere, 'niveau' => $niveau, 'id' => $id]);
        header('Location: matiereSecretaire.php?ok=modification');
        exit;
      }
    } catch (PDOException $e) {
      $erreurCode = "Ce code de matière existe déjà.";
    }
  }
}

// Recherche
$q = trim($_GET['q'] ?? '');

$stmt = $pdo->prepare("
  SELECT m.id_matiere, m.code_matiere, m.nom_matiere, m.coefficient, m.id_filiere, m.id_niveau,
         f.nom_filiere, n.libelle_niveau
  FROM matiere m
  LEFT JOIN filiere f ON f.id_filiere = m.id_filiere
  LEFT JOIN niveau  n ON n.id_niveau  = m.id_niveau
  WHERE (:q1 = '' OR m.nom_matiere LIKE CONCAT('%', :q2, '%') OR m.code_matiere LIKE CONCAT('%', :q3, '%'))
  ORDER BY m.nom_matiere
");
$stmt->execute(['q1' => $q, 'q2' => $q, 'q3' => $q]);
$matieres = $stmt->fetchAll();

// Listes déroulantes
$filieresListe = $pdo->query("SELECT id_filiere, nom_filiere FROM filiere ORDER BY nom_filiere")->fetchAll();
$niveauxListe  = $pdo->query("SELECT id_niveau, libelle_niveau FROM niveau ORDER BY id_niveau")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Matières</title>
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
      <symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></symbol>
      <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></symbol>
      <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
      <symbol id="i-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
      <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
      <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></symbol>
    </defs>
  </svg>

  <!-- ============ SIDEBAR ============ -->
  <?php $active = 'matieres'; include 'sidebarSecretaire.php'; ?>

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

        <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">
          <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
              <div class="flex items-center gap-3">
                <h1 class="text-xl font-bold text-[#1a1b22]">Liste des matières</h1>
                <span class="rounded-full bg-primary px-2.5 py-0.5 text-xs font-semibold text-white"><?= count($matieres) ?> matière<?= count($matieres) > 1 ? 's' : '' ?></span>
              </div>
              <p class="mt-1 text-xs text-muted">Matières d'enseignement, coefficients et rattachements</p>
            </div>
            <button type="button" id="btnAdd" class="flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark"><svg class="h-3.5 w-3.5"><use href="#i-plus" /></svg> Ajouter une matière</button>
          </div>

          <form method="get" class="mt-5 flex items-center gap-2">
            <div class="relative">
              <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-search" /></svg></span>
              <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Rechercher par nom ou code…" class="w-64 rounded-lg border border-[#e5e7eb] py-2.5 pl-9 pr-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
            </div>
            <button type="submit" class="rounded-lg border border-[#e5e7eb] p-2.5 text-muted hover:bg-page" aria-label="Rechercher"><svg class="h-4 w-4"><use href="#i-search" /></svg></button>
          </form>

          <div class="mt-5 overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
              <thead class="border-b border-[#e5e7eb]">
                <tr class="text-xs font-semibold uppercase tracking-[0.5px] text-[#6b7280]">
                  <th class="px-4 py-3 font-semibold">Code</th>
                  <th class="px-4 py-3 font-semibold">Nom de la matière</th>
                  <th class="px-4 py-3 font-semibold">Coefficient</th>
                  <th class="px-4 py-3 font-semibold">Filière</th>
                  <th class="px-4 py-3 font-semibold">Niveau</th>
                  <th class="px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-[#f3f4f6]">
                <?php if (count($matieres) === 0): ?>
                  <tr><td colspan="6" class="px-4 py-10 text-center text-sm text-muted">Aucune matière ne correspond à votre recherche.</td></tr>
                <?php else: ?>
                  <?php foreach ($matieres as $m): ?>
                  <tr class="transition-colors hover:bg-[#f9fafb]"
                      data-id="<?= $m['id_matiere'] ?>"
                      data-code="<?= htmlspecialchars($m['code_matiere']) ?>"
                      data-nom="<?= htmlspecialchars($m['nom_matiere']) ?>"
                      data-coef="<?= htmlspecialchars($m['coefficient'] ?? '1') ?>"
                      data-filiere="<?= htmlspecialchars($m['id_filiere'] ?? '') ?>"
                      data-niveau="<?= htmlspecialchars($m['id_niveau'] ?? '') ?>">
                    <td class="px-4 py-3 font-mono text-xs font-bold text-primary"><?= htmlspecialchars($m['code_matiere']) ?></td>
                    <td class="px-4 py-3 font-bold text-[#1a1b22]"><?= htmlspecialchars($m['nom_matiere']) ?></td>
                    <td class="px-4 py-3 text-[#374151]"><?= htmlspecialchars($m['coefficient'] ?? '1') ?></td>
                    <td class="px-4 py-3 text-[#374151]"><?= htmlspecialchars($m['nom_filiere'] ?: '—') ?></td>
                    <td class="px-4 py-3 text-[#374151]"><?= htmlspecialchars($m['libelle_niveau'] ?: '—') ?></td>
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

          <p class="mt-4 text-xs text-muted"><?= count($matieres) ?> matière<?= count($matieres) > 1 ? 's' : '' ?> affichée<?= count($matieres) > 1 ? 's' : '' ?></p>
        </section>
      </div>
    </main>
  </div>

  <!-- ============ DRAWER : AJOUT / MODIFICATION MATIÈRE ============ -->
  <div id="drawerAdd" class="fixed inset-0 z-50 hidden">
    <div data-close class="absolute inset-0 bg-black/50"></div>
    <aside class="absolute inset-y-0 right-0 flex w-full max-w-md translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out">
      <div class="flex items-center justify-between border-b border-[#eef0f2] px-6 py-4">
        <div class="flex items-center gap-3">
          <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-5 w-5"><use href="#i-book" /></svg></span>
          <div><h3 id="drawerTitle" class="text-lg font-bold text-[#1a1b22]"><?= ($_POST['id_matiere'] ?? '') !== '' ? 'Modifier la matière' : 'Nouvelle matière' ?></h3><p class="text-xs text-muted">Renseignez les détails de la matière</p></div>
        </div>
        <button type="button" data-close class="rounded-lg p-1.5 text-muted hover:bg-page" aria-label="Fermer"><svg class="h-5 w-5"><use href="#i-x" /></svg></button>
      </div>

      <form id="matiereForm" method="post" class="flex flex-1 flex-col gap-4 overflow-y-auto px-6 py-5">

        <input type="hidden" name="id_matiere" id="m-id" value="<?= htmlspecialchars($_POST['id_matiere'] ?? '') ?>" />

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div>
            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="m-code">Code</label>
            <input id="m-code" type="text" name="code" value="<?= htmlspecialchars($_POST['code'] ?? '') ?>" placeholder="ex: ALGO1" class="w-full rounded-lg border px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= $erreurCode !== '' ? 'border-[#ffb3b3] focus:border-[#e11d2a]' : 'border-[#e5e7eb] focus:border-primary' ?>" />
            <?php if ($erreurCode !== ""): ?>
              <p class="mt-1.5 flex items-start gap-1.5 text-xs text-[#b8001a]"><svg class="mt-0.5 h-3.5 w-3.5 shrink-0"><use href="#i-alert" /></svg> <span><?= htmlspecialchars($erreurCode) ?></span></p>
            <?php endif; ?>
          </div>
          <div>
            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="m-coef">Coefficient</label>
            <input id="m-coef" type="number" name="coefficient" value="<?= htmlspecialchars($_POST['coefficient'] ?? '1') ?>" placeholder="ex: 2" class="w-full rounded-lg border px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= $erreurCoef !== '' ? 'border-[#ffb3b3] focus:border-[#e11d2a]' : 'border-[#e5e7eb] focus:border-primary' ?>" />
            <?php if ($erreurCoef !== ""): ?>
              <p class="mt-1.5 flex items-start gap-1.5 text-xs text-[#b8001a]"><svg class="mt-0.5 h-3.5 w-3.5 shrink-0"><use href="#i-alert" /></svg> <span><?= htmlspecialchars($erreurCoef) ?></span></p>
            <?php endif; ?>
          </div>
        </div>

        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="m-nom">Nom de la matière</label>
          <input id="m-nom" type="text" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" placeholder="ex: Algorithmique" class="w-full rounded-lg border px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= $erreurNom !== '' ? 'border-[#ffb3b3] focus:border-[#e11d2a]' : 'border-[#e5e7eb] focus:border-primary' ?>" />
          <?php if ($erreurNom !== ""): ?>
            <p class="mt-1.5 flex items-start gap-1.5 text-xs text-[#b8001a]"><svg class="mt-0.5 h-3.5 w-3.5 shrink-0"><use href="#i-alert" /></svg> <span><?= htmlspecialchars($erreurNom) ?></span></p>
          <?php endif; ?>
        </div>

        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="m-filiere">Filière</label>
          <select id="m-filiere" name="id_filiere" class="w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
            <option value="">Aucune filière</option>
            <?php foreach ($filieresListe as $fl): ?>
              <option value="<?= $fl['id_filiere'] ?>" <?= ($_POST['id_filiere'] ?? '') == $fl['id_filiere'] ? 'selected' : '' ?>><?= htmlspecialchars($fl['nom_filiere']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="m-niveau">Niveau</label>
          <select id="m-niveau" name="id_niveau" class="w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
            <option value="">Aucun niveau</option>
            <?php foreach ($niveauxListe as $nl): ?>
              <option value="<?= $nl['id_niveau'] ?>" <?= ($_POST['id_niveau'] ?? '') == $nl['id_niveau'] ? 'selected' : '' ?>><?= htmlspecialchars($nl['libelle_niveau']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </form>

      <div class="grid grid-cols-2 gap-3 border-t border-[#eef0f2] px-6 py-4">
        <button type="button" data-close class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="submit" form="matiereForm" class="flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-check" /></svg> Enregistrer</button>
      </div>
    </aside>
  </div>

  <!-- ============ MODALE : CONFIRMER SUPPRESSION ============ -->
  <div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-xl">
      <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-7 w-7"><use href="#i-trash" /></svg></div>
      <h3 class="text-lg font-bold text-[#1a1b22]">Confirmer la suppression</h3>
      <p class="mt-2 text-sm text-muted">Voulez-vous vraiment supprimer la matière <span id="delName" class="font-semibold text-[#1a1b22]"></span> ? Cette action est irréversible.</p>
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
    <input type="hidden" name="code" id="restore_code" value="" />
    <input type="hidden" name="nom" id="restore_nom" value="" />
    <input type="hidden" name="coefficient" id="restore_coef" value="" />
    <input type="hidden" name="id_filiere" id="restore_filiere" value="" />
    <input type="hidden" name="id_niveau" id="restore_niveau" value="" />
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
    const form = document.getElementById('matiereForm');

    const openDrawerPanel = () => { drawerAdd.classList.remove('hidden'); requestAnimationFrame(() => drawerPanel.classList.remove('translate-x-full')); document.body.classList.add('overflow-hidden'); };
    const closeDrawer = () => { drawerPanel.classList.add('translate-x-full'); document.body.classList.remove('overflow-hidden'); setTimeout(() => drawerAdd.classList.add('hidden'), 300); };

    document.getElementById('btnAdd').addEventListener('click', () => {
      form.reset();
      document.getElementById('m-id').value = '';
      document.getElementById('m-coef').value = '1';
      drawerTitle.textContent = 'Nouvelle matière';
      openDrawerPanel();
    });
    drawerAdd.querySelectorAll('[data-close]').forEach((b) => b.addEventListener('click', closeDrawer));
    drawerAdd.addEventListener('click', (e) => { if (!drawerPanel.contains(e.target)) closeDrawer(); });

    function openDrawerEdit(d) {
      form.reset();
      document.getElementById('m-id').value      = d.id;
      document.getElementById('m-code').value    = d.code;
      document.getElementById('m-nom').value     = d.nom;
      document.getElementById('m-coef').value    = d.coef;
      document.getElementById('m-filiere').value = d.filiere;
      document.getElementById('m-niveau').value  = d.niveau;
      drawerTitle.textContent = 'Modifier la matière';
      openDrawerPanel();
    }

    /* ----- Boutons modifier / supprimer ----- */
    document.querySelectorAll('tbody tr[data-id]').forEach((tr) => {
      const editBtn = tr.querySelector('.js-edit');
      if (editBtn) editBtn.addEventListener('click', () => openDrawerEdit(tr.dataset));

      const delBtn = tr.querySelector('.js-del');
      if (delBtn) delBtn.addEventListener('click', () => {
        idToDelete = tr.dataset.id;
        document.getElementById('delName').textContent = '« ' + tr.dataset.nom + ' »';
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

    window.addEventListener('keydown', (e) => { if (e.key !== 'Escape') return; closeDrawer(); closeModal(modalDelete); toggleMenu(false); });

    /* ----- Rouvrir le drawer en cas d'erreur ----- */
    <?php if ($erreurCode !== "" || $erreurNom !== "" || $erreurCoef !== ""): ?>
    openDrawerPanel();
    <?php endif; ?>

    /* ----- Toasts après une action ----- */
    <?php if (isset($_GET['ok']) && $_GET['ok'] === 'ajout'): ?>
    showToast('success', 'Matière ajoutée', 'La matière a bien été enregistrée.');
    <?php elseif (isset($_GET['ok']) && $_GET['ok'] === 'modification'): ?>
    showToast('success', 'Matière modifiée', 'Les modifications ont bien été enregistrées.');
    <?php elseif (isset($_GET['ok']) && $_GET['ok'] === 'suppression'): ?>
    showToast('success', 'Matière supprimée', 'La matière a bien été retirée.', {
      label: 'Annuler',
      onClick: () => {
        document.getElementById('restore_code').value    = <?= json_encode($_GET['code'] ?? '') ?>;
        document.getElementById('restore_nom').value     = <?= json_encode($_GET['nom'] ?? '') ?>;
        document.getElementById('restore_coef').value    = <?= json_encode($_GET['coef'] ?? '1') ?>;
        document.getElementById('restore_filiere').value = <?= json_encode($_GET['filiere'] ?? '') ?>;
        document.getElementById('restore_niveau').value  = <?= json_encode($_GET['niveau'] ?? '') ?>;
        document.getElementById('restoreForm').submit();
      }
    });
    <?php elseif (isset($_GET['err']) && $_GET['err'] === 'suppression'): ?>
    showToast('error', 'Suppression impossible', 'Cette matière est utilisée (notes, séances ou formateurs).');
    <?php endif; ?>

    /* ----- Nettoyer l'URL ----- */
    <?php if (isset($_GET['ok']) || isset($_GET['err'])): ?>
    history.replaceState({}, '', 'matiereSecretaire.php');
    <?php endif; ?>
  </script>
</body>
</html>