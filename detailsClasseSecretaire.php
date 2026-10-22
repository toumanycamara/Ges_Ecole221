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

// Id de la classe à afficher
$id = $_GET['id'] ?? 0;

// Infos de la classe (+ filière et niveau)
$stmt = $pdo->prepare("
  SELECT c.*, f.nom_filiere, n.libelle_niveau
  FROM classe c
  JOIN filiere f ON f.id_filiere = c.id_filiere
  JOIN niveau  n ON n.id_niveau  = c.id_niveau
  WHERE c.id_classe = :id
");
$stmt->execute(['id' => $id]);
$classe = $stmt->fetch();

// Si la classe n'existe pas, on retourne à la liste
if (!$classe) {
  header('Location: classeSecretaire.php');
  exit;
}

// Étudiants de la classe
$stmt = $pdo->prepare("
  SELECT id_etudiant, matricule, nom, prenom, sexe
  FROM etudiant
  WHERE id_classe = :id
  ORDER BY nom, prenom
");
$stmt->execute(['id' => $id]);
$etudiants = $stmt->fetchAll();

// Calculs
$nbTotal  = count($etudiants);
$nbG = 0;
$nbF = 0;
foreach ($etudiants as $e) {
  if ($e['sexe'] === 'M') { $nbG++; }
  elseif ($e['sexe'] === 'F') { $nbF++; }
}
$capacite = (int)$classe['capacite'];
$taux = $capacite > 0 ? round(100 * $nbTotal / $capacite) : 0;

// Petit badge sexe
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
  <title>École 221 – Détails de la classe</title>
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
          colors: {
            primary: '#e11d2a',
            ink: '#141414',
            muted: '#5f5e5e',
            page: '#f4f4f5',
            primarydark: '#8f1119',
          },
        },
      },
    };
  </script>
</head>
<body class="font-sans bg-page text-ink">

  <!-- Icônes (sprite SVG) -->
  <svg class="hidden" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
      <symbol id="i-chevron-right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></symbol>
      <symbol id="i-male" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="14" r="6"/><path d="M19 5l-6 6M15 5h4v4"/></symbol>
      <symbol id="i-female" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M12 14v7M9 18h6"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
    </defs>
  </svg>

  <!-- ============ SIDEBAR ============ -->
  <?php $active = 'classes'; include 'sidebarSecretaire.php'; ?>

  <!-- ============ CONTENU ============ -->
  <div class="app-content min-h-screen lg:ml-[260px]">

    <!-- Navbar -->
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" aria-controls="sidebar" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden">
          <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h2 class="text-sm font-semibold">Détails de la Classe</h2>
      </div>
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
          <img src="https://i.pravatar.cc/64?img=47" alt="Photo de l'administrateur" class="h-8 w-8 rounded-full object-cover" />
          <div class="hidden sm:block">
            <p class="text-sm font-semibold leading-[17.5px]"><?= htmlspecialchars($nomAffiche) ?></p>
            <p class="text-xs leading-3 text-muted"><?= htmlspecialchars($emailAffiche) ?></p>
          </div>
        </div>
        <span class="hidden h-6 w-px bg-[#e3e1ec] sm:block"></span>
        <span class="whitespace-nowrap rounded-full bg-primary px-2 py-1 text-xs font-semibold text-white shadow-sm">Secrétaire / Admin</span>
      </div>
    </header>

    <main class="px-4 py-8 sm:px-6 lg:px-12">
      <div class="mx-auto flex max-w-[1180px] flex-col gap-6">

        <!-- Fil d'ariane + actions -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <nav class="flex items-center gap-1.5 text-xs text-muted">
            <span>Tableau de bord</span>
            <svg class="h-3.5 w-3.5"><use href="#i-chevron-right" /></svg>
            <a href="classeSecretaire.php" class="hover:text-ink">Classes</a>
            <svg class="h-3.5 w-3.5"><use href="#i-chevron-right" /></svg>
            <span class="font-semibold text-primary">Fiche classe</span>
          </nav>
          <div class="flex items-center gap-4">
            <a href="classeSecretaire.php" class="text-sm font-medium text-primary hover:underline">Retour à la liste</a>
          </div>
        </div>

        <!-- Titre -->
        <div class="flex flex-wrap items-center gap-3">
          <h1 class="text-2xl font-bold tracking-[-0.6px] text-[#1a1b22]"><?= htmlspecialchars($classe['nom_classe']) ?></h1>
          <?php if (!empty($classe['salle'])): ?>
            <span class="rounded-full bg-page px-2.5 py-1 text-xs font-semibold text-[#374151]">Salle <?= htmlspecialchars($classe['salle']) ?></span>
          <?php endif; ?>
          <?php if (!empty($classe['annee_academique'])): ?>
            <span class="rounded-full bg-[#ffdad7] px-2.5 py-1 text-xs font-semibold text-primary">Année <?= htmlspecialchars($classe['annee_academique']) ?></span>
          <?php endif; ?>
        </div>

        <!-- Deux cartes : infos + répartition -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

          <!-- Informations de la classe -->
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
                <dd class="flex items-center gap-2">
                  <span class="h-2 w-24 overflow-hidden rounded-full bg-[#f3f4f6]"><span class="block h-full rounded-full bg-primary" style="width:<?= $taux ?>%"></span></span>
                  <span class="text-sm font-semibold text-primary"><?= $taux ?>%</span>
                </dd>
              </div>
            </dl>
          </section>

          <!-- Répartition des étudiants -->
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
            <div class="mt-4 rounded-xl bg-[#ffdad7] px-4 py-3 text-center text-sm font-semibold text-primary">
              Effectif total : <?= $nbTotal ?> étudiant<?= $nbTotal > 1 ? 's' : '' ?>
            </div>
          </section>

        </div>

        <!-- Liste des étudiants de la classe -->
        <section class="rounded-2xl bg-white p-6 shadow-sm">
          <h2 class="mb-4 text-lg font-bold text-[#1a1b22]">Liste des étudiants de la classe</h2>
          <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-left text-sm">
              <thead class="border-b border-[#e5e7eb] bg-[#f9fafb]">
                <tr class="text-xs font-semibold uppercase tracking-[0.5px] text-[#6b7280]">
                  <th class="px-4 py-3 font-semibold">N°</th>
                  <th class="px-4 py-3 font-semibold">Matricule</th>
                  <th class="px-4 py-3 font-semibold">Nom</th>
                  <th class="px-4 py-3 font-semibold">Prénom</th>
                  <th class="px-4 py-3 font-semibold">Sexe</th>
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
          <p class="mt-4 text-xs text-muted"><?= $nbTotal ?> étudiant<?= $nbTotal > 1 ? 's' : '' ?> au total</p>
        </section>

      </div>
    </main>
  </div>

  <script>
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
    window.addEventListener('keydown', (e) => { if (e.key === 'Escape') toggleMenu(false); });
  </script>
</body>
</html>