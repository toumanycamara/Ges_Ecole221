<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'formateur') {
  header('Location: loginEcole221.php');
  exit;
}

require 'config.php';

$idFormateur = $_SESSION['id_formateur'] ?? 0;
$idClasse    = $_GET['id'] ?? 0;

// Sécurité : le formateur ne peut voir que les classes où il a une séance
$chk = $pdo->prepare("SELECT 1 FROM seance WHERE id_formateur = :f AND id_classe = :c LIMIT 1");
$chk->execute(['f' => $idFormateur, 'c' => $idClasse]);
if (!$chk->fetchColumn()) {
  header('Location: classeFormateur.php');
  exit;
}

// Infos du formateur connecté (en-tête)
$stmt = $pdo->prepare("SELECT nom, prenom, email, photo FROM formateur WHERE id_formateur = :id");
$stmt->execute(['id' => $idFormateur]);
$moi = $stmt->fetch();
$nomAffiche   = $moi ? trim($moi['prenom'] . ' ' . $moi['nom']) : ($_SESSION['login'] ?? 'Formateur');
$emailAffiche = $moi && !empty($moi['email']) ? $moi['email'] : ($_SESSION['login'] ?? '');
$photo        = $moi && !empty($moi['photo']) ? $moi['photo'] : 'https://i.pravatar.cc/64?img=12';

// Infos de la classe
$stmt = $pdo->prepare("
  SELECT c.*, f.nom_filiere, n.libelle_niveau
  FROM classe c
  JOIN filiere f ON f.id_filiere = c.id_filiere
  JOIN niveau  n ON n.id_niveau  = c.id_niveau
  WHERE c.id_classe = :id
");
$stmt->execute(['id' => $idClasse]);
$classe = $stmt->fetch();
if (!$classe) {
  header('Location: classeFormateur.php');
  exit;
}

// Étudiants de la classe
$stmt = $pdo->prepare("SELECT id_etudiant, matricule, nom, prenom, sexe FROM etudiant WHERE id_classe = :id ORDER BY nom, prenom");
$stmt->execute(['id' => $idClasse]);
$etudiants = $stmt->fetchAll();

// Calculs
$nbTotal = count($etudiants);
$nbG = 0; $nbF = 0;
foreach ($etudiants as $e) {
  if ($e['sexe'] === 'M') $nbG++;
  elseif ($e['sexe'] === 'F') $nbF++;
}
$cap  = (int)$classe['capacite'];
$taux = $cap > 0 ? round(100 * $nbTotal / $cap) : 0;

function sexeBadge($s) {
  if ($s === 'F') return '<span class="rounded-full border border-[#fbcfe8] bg-[#fdf2f8] px-2 py-0.5 text-xs font-medium text-[#be185d]">Féminin</span>';
  return '<span class="rounded-full border border-[#bfdbfe] bg-[#eff6ff] px-2 py-0.5 text-xs font-medium text-[#1d4ed8]">Masculin</span>';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Détail classe (Formateur)</title>
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
      <symbol id="i-chevron-right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></symbol>
      <symbol id="i-male" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="14" r="6"/><path d="M19 5l-6 6M15 5h4v4"/></symbol>
      <symbol id="i-female" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M12 14v7M9 18h6"/></symbol>
    </defs>
  </svg>

  <!-- ===================== SIDEBAR ===================== -->
  <?php $active = 'classes'; include 'sidebarFormateur.php'; ?>

  <div class="app-content min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <h2 class="text-sm font-semibold">Détail de la classe - Formateur</h2>
      </div>
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
          <img src="<?= htmlspecialchars($photo) ?>" alt="Photo" class="h-8 w-8 rounded-full object-cover" />
          <div class="hidden sm:block"><p class="text-sm font-semibold leading-[17.5px]"><?= htmlspecialchars($nomAffiche) ?></p><p class="text-xs leading-3 text-muted"><?= htmlspecialchars($emailAffiche) ?></p></div>
        </div>
        <span class="hidden h-6 w-px bg-[#e3e1ec] sm:block"></span>
        <span class="whitespace-nowrap rounded-full bg-primary px-2 py-1 text-xs font-semibold text-white shadow-sm">Formateur</span>
      </div>
    </header>

    <main class="px-4 py-8 sm:px-6 lg:px-12">
      <div class="mx-auto flex max-w-[1180px] flex-col gap-6">

        <!-- Fil d'ariane + retour -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <nav class="flex items-center gap-1.5 text-xs text-muted">
            <a href="classeFormateur.php" class="hover:text-ink">Classes</a><svg class="h-3.5 w-3.5"><use href="#i-chevron-right" /></svg>
            <span class="font-semibold text-primary">Détail de la classe</span>
          </nav>
          <a href="classeFormateur.php" class="text-sm font-medium text-primary hover:underline">← Retour à mes classes</a>
        </div>

        <!-- Titre -->
        <div class="flex flex-wrap items-center gap-3">
          <h1 class="text-2xl font-bold tracking-[-0.6px] text-[#1a1b22]"><?= htmlspecialchars($classe['nom_classe']) ?></h1>
          <?php if (!empty($classe['salle'])): ?><span class="rounded-full bg-page px-2.5 py-1 text-xs font-semibold text-[#374151]">Salle <?= htmlspecialchars($classe['salle']) ?></span><?php endif; ?>
          <?php if (!empty($classe['annee_academique'])): ?><span class="rounded-full bg-[#ffdad7] px-2.5 py-1 text-xs font-semibold text-primary">Année <?= htmlspecialchars($classe['annee_academique']) ?></span><?php endif; ?>
          <span class="rounded-full bg-page px-2.5 py-1 text-xs font-medium text-muted">Consultation</span>
        </div>

        <!-- Deux cartes -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-bold text-[#1a1b22]">Informations de la classe</h2>
            <dl class="divide-y divide-[#f3f4f6] text-sm">
              <div class="flex justify-between py-2.5"><dt class="text-muted">Filière</dt><dd class="font-medium text-[#1f2937]"><?= htmlspecialchars($classe['nom_filiere']) ?></dd></div>
              <div class="flex justify-between py-2.5"><dt class="text-muted">Niveau</dt><dd class="font-medium text-[#1f2937]"><?= htmlspecialchars($classe['libelle_niveau']) ?></dd></div>
              <div class="flex justify-between py-2.5"><dt class="text-muted">Salle</dt><dd class="font-medium text-[#1f2937]"><?= htmlspecialchars($classe['salle'] ?? '') ?></dd></div>
              <div class="flex justify-between py-2.5"><dt class="text-muted">Capacité</dt><dd class="font-medium text-[#1f2937]"><?= htmlspecialchars($classe['capacite']) ?> places</dd></div>
              <div class="flex justify-between py-2.5"><dt class="text-muted">Inscrits</dt><dd class="font-medium text-[#1f2937]"><?= $nbTotal ?> étudiant<?= $nbTotal > 1 ? 's' : '' ?></dd></div>
              <div class="flex items-center justify-between py-2.5">
                <dt class="text-muted">Taux de remplissage</dt>
                <dd class="flex items-center gap-2"><span class="h-2 w-24 overflow-hidden rounded-full bg-[#f3f4f6]"><span class="block h-full rounded-full bg-primary" style="width:<?= $taux ?>%"></span></span><span class="text-sm font-semibold text-primary"><?= $taux ?>%</span></dd>
              </div>
            </dl>
          </section>

          <section class="flex flex-col rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-bold text-[#1a1b22]">Répartition des étudiants</h2>
            <div class="flex flex-1 items-center justify-center gap-12">
              <div class="flex flex-col items-center gap-2">
                <div class="flex h-20 w-20 items-center justify-center rounded-full border-[7px] border-[#2563eb] text-[#2563eb]"><svg class="h-8 w-8"><use href="#i-male" /></svg></div>
                <p class="text-sm text-muted">Garçons : <span class="font-semibold text-[#1f2937]"><?= $nbG ?></span></p>
              </div>
              <div class="flex flex-col items-center gap-2">
                <div class="flex h-20 w-20 items-center justify-center rounded-full border-[7px] border-[#db2777] text-[#db2777]"><svg class="h-8 w-8"><use href="#i-female" /></svg></div>
                <p class="text-sm text-muted">Filles : <span class="font-semibold text-[#1f2937]"><?= $nbF ?></span></p>
              </div>
            </div>
            <div class="mt-4 rounded-xl bg-[#ffdad7] px-4 py-3 text-center text-sm font-semibold text-primary">Effectif total : <?= $nbTotal ?> étudiant<?= $nbTotal > 1 ? 's' : '' ?></div>
          </section>
        </div>

        <!-- Liste des étudiants (lecture seule) -->
        <section class="rounded-2xl bg-white p-6 shadow-sm">
          <h2 class="mb-4 text-lg font-bold text-[#1a1b22]">Étudiants de la classe</h2>
          <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-left text-sm">
              <thead class="border-b border-[#e5e7eb] bg-[#f9fafb]">
                <tr class="text-xs font-semibold uppercase tracking-[0.5px] text-[#6b7280]">
                  <th class="px-4 py-3 font-semibold">N°</th><th class="px-4 py-3 font-semibold">Matricule</th><th class="px-4 py-3 font-semibold">Nom</th><th class="px-4 py-3 font-semibold">Prénom</th><th class="px-4 py-3 font-semibold">Sexe</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-[#f3f4f6]">
                <?php if ($nbTotal === 0): ?>
                  <tr><td colspan="5" class="px-4 py-10 text-center text-sm text-muted">Aucun étudiant inscrit dans cette classe pour l'instant.</td></tr>
                <?php else: ?>
                  <?php foreach ($etudiants as $i => $e): ?>
                  <tr class="hover:bg-[#f9fafb]">
                    <td class="px-4 py-3 text-muted"><?= $i + 1 ?></td>
                    <td class="px-4 py-3 font-mono text-xs font-bold text-[#111827]"><?= htmlspecialchars($e['matricule']) ?></td>
                    <td class="px-4 py-3 font-bold"><?= htmlspecialchars($e['nom']) ?></td>
                    <td class="px-4 py-3 text-[#374151]"><?= htmlspecialchars($e['prenom']) ?></td>
                    <td class="px-4 py-3"><?= sexeBadge($e['sexe']) ?></td>
                  </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          <p class="mt-4 text-xs text-muted"><?= $nbTotal ?> étudiant<?= $nbTotal > 1 ? 's' : '' ?> — consultation en lecture seule</p>
        </section>
      </div>
    </main>
  </div>

  <script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleMenu = (open) => { sidebar.classList.toggle('-translate-x-full', !open); overlay.classList.toggle('hidden', !open); document.body.classList.toggle('overflow-hidden', open); };
    document.getElementById('openMenu').addEventListener('click', () => toggleMenu(true));
    document.getElementById('closeMenu').addEventListener('click', () => toggleMenu(false));
    overlay.addEventListener('click', () => toggleMenu(false));
  </script>
</body>
</html>