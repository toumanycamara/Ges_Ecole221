<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'formateur') {
  header('Location: loginEcole221.php');
  exit;
}

require 'config.php';

$idFormateur = $_SESSION['id_formateur'] ?? 0;

// Infos du formateur connecté (en-tête)
$stmt = $pdo->prepare("SELECT nom, prenom, email, photo FROM formateur WHERE id_formateur = :id");
$stmt->execute(['id' => $idFormateur]);
$moi = $stmt->fetch();
$nomAffiche   = $moi ? trim($moi['prenom'] . ' ' . $moi['nom']) : ($_SESSION['login'] ?? 'Formateur');
$emailAffiche = $moi && !empty($moi['email']) ? $moi['email'] : ($_SESSION['login'] ?? '');
$photo        = $moi && !empty($moi['photo']) ? $moi['photo'] : 'https://i.pravatar.cc/64?img=12';

// Recherche + filtres
$q         = trim($_GET['q'] ?? '');
$filFil    = $_GET['filiere'] ?? '';
$filClasse = $_GET['classe'] ?? '';

// Étudiants des classes où le formateur a des séances
$stmt = $pdo->prepare("
  SELECT e.id_etudiant, e.matricule, e.nom, e.prenom, e.sexe,
         c.nom_classe, f.nom_filiere, c.id_classe, f.id_filiere
  FROM etudiant e
  JOIN classe  c ON c.id_classe  = e.id_classe
  JOIN filiere f ON f.id_filiere = c.id_filiere
  WHERE c.id_classe IN (SELECT DISTINCT id_classe FROM seance WHERE id_formateur = :f)
    AND (:q1 = '' OR e.nom LIKE CONCAT('%', :q2, '%') OR e.prenom LIKE CONCAT('%', :q3, '%') OR e.matricule LIKE CONCAT('%', :q4, '%'))
    AND (:fil1 = '' OR f.id_filiere = :fil2)
    AND (:cl1  = '' OR c.id_classe  = :cl2)
  ORDER BY e.nom, e.prenom
");
$stmt->execute([
  'f' => $idFormateur,
  'q1' => $q, 'q2' => $q, 'q3' => $q, 'q4' => $q,
  'fil1' => $filFil, 'fil2' => $filFil,
  'cl1' => $filClasse, 'cl2' => $filClasse,
]);
$etudiants = $stmt->fetchAll();

// Listes pour les filtres (classes et filières du formateur)
$mesClassesListe = $pdo->prepare("
  SELECT DISTINCT c.id_classe, c.nom_classe
  FROM seance s JOIN classe c ON c.id_classe = s.id_classe
  WHERE s.id_formateur = :f ORDER BY c.nom_classe
");
$mesClassesListe->execute(['f' => $idFormateur]);
$mesClassesListe = $mesClassesListe->fetchAll();

$mesFilieresListe = $pdo->prepare("
  SELECT DISTINCT f.id_filiere, f.nom_filiere
  FROM seance s
  JOIN classe  c ON c.id_classe  = s.id_classe
  JOIN filiere f ON f.id_filiere = c.id_filiere
  WHERE s.id_formateur = :f ORDER BY f.nom_filiere
");
$mesFilieresListe->execute(['f' => $idFormateur]);
$mesFilieresListe = $mesFilieresListe->fetchAll();

function sexeBadge($s) {
  if ($s === 'F') return '<span class="inline-block rounded-full bg-[#fce7f3] px-2.5 py-0.5 text-xs font-semibold text-[#db2777]">Féminin</span>';
  return '<span class="inline-block rounded-full bg-[#dbeafe] px-2.5 py-0.5 text-xs font-semibold text-[#2563eb]">Masculin</span>';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Étudiants (Formateur)</title>
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
      <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></symbol>
      <symbol id="i-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></symbol>
      <symbol id="i-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></symbol>
    </defs>
  </svg>

  <!-- ===================== SIDEBAR ===================== -->
  <?php $active = 'etudiants'; include 'sidebarFormateur.php'; ?>

  <div class="app-content min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <h2 class="text-sm font-semibold">Étudiants - Formateur</h2>
      </div>
      <div class="flex items-center gap-3 sm:gap-4">
        <div class="flex items-center gap-2">
          <img src="<?= htmlspecialchars($photo) ?>" alt="Photo" class="h-8 w-8 rounded-full object-cover" />
          <div class="hidden sm:block"><p class="text-sm font-semibold leading-[17.5px]"><?= htmlspecialchars($nomAffiche) ?></p><p class="text-xs leading-3 text-muted"><?= htmlspecialchars($emailAffiche) ?></p></div>
        </div>
        <span class="hidden h-6 w-px bg-[#e3e1ec] sm:block"></span>
        <span class="whitespace-nowrap rounded-full bg-primary px-2.5 py-1 text-xs font-semibold text-white shadow-sm">Formateur</span>
      </div>
    </header>

    <main class="px-4 py-8 sm:px-6 lg:px-12">
      <div class="mx-auto flex max-w-[1180px] flex-col gap-6">

        <!-- En-tête -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <h1 class="text-xl font-bold text-[#1a1b22]">Liste des étudiants</h1>
            <p class="mt-1 text-xs text-muted">Étudiants des classes que vous encadrez — consultation</p>
          </div>
          <span class="inline-flex items-center gap-1.5 rounded-full bg-[#eef2ff] px-3 py-1 text-xs font-semibold text-[#4f46e5]"><svg class="h-3.5 w-3.5"><use href="#i-eye" /></svg> Lecture seule</span>
        </div>

        <!-- Panneau liste -->
        <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">

          <!-- Barre de recherche + filtres -->
          <form method="get" class="flex flex-col gap-3 md:flex-row md:items-center">
            <div class="relative flex-1">
              <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-search" /></svg></span>
              <input id="search" name="q" value="<?= htmlspecialchars($q) ?>" type="text" placeholder="Rechercher un étudiant" class="w-full rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-9 pr-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
            </div>
            <div class="relative">
              <select name="filiere" onchange="this.form.submit()" class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 md:w-48">
                <option value="">Filière : Toutes</option>
                <?php foreach ($mesFilieresListe as $fl): ?>
                  <option value="<?= $fl['id_filiere'] ?>" <?= $filFil == $fl['id_filiere'] ? 'selected' : '' ?>><?= htmlspecialchars($fl['nom_filiere']) ?></option>
                <?php endforeach; ?>
              </select>
              <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
            </div>
            <div class="relative">
              <select name="classe" onchange="this.form.submit()" class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 md:w-48">
                <option value="">Classe : Toutes</option>
                <?php foreach ($mesClassesListe as $cl): ?>
                  <option value="<?= $cl['id_classe'] ?>" <?= $filClasse == $cl['id_classe'] ? 'selected' : '' ?>><?= htmlspecialchars($cl['nom_classe']) ?></option>
                <?php endforeach; ?>
              </select>
              <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
            </div>
            <button type="submit" class="rounded-lg border border-[#e5e7eb] p-2.5 text-muted hover:bg-page" aria-label="Rechercher"><svg class="h-4 w-4"><use href="#i-search" /></svg></button>
          </form>

          <!-- Tableau -->
          <div class="mt-5 overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
              <thead>
                <tr class="border-b border-[#ececef] text-[11px] uppercase tracking-wide text-muted">
                  <th class="px-3 py-3 font-semibold">Matricule</th>
                  <th class="px-3 py-3 font-semibold">Nom</th>
                  <th class="px-3 py-3 font-semibold">Prénom</th>
                  <th class="px-3 py-3 font-semibold">Sexe</th>
                  <th class="px-3 py-3 font-semibold">Classe</th>
                  <th class="px-3 py-3 font-semibold">Filière</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-[#f1f1f3]">
                <?php if (count($etudiants) === 0): ?>
                  <tr><td colspan="6" class="px-3 py-10 text-center text-sm text-muted">Aucun étudiant ne correspond à votre recherche.</td></tr>
                <?php else: ?>
                  <?php foreach ($etudiants as $e): ?>
                  <tr class="transition hover:bg-[#faf9fb]">
                    <td class="px-3 py-3.5 font-mono text-xs text-[#374151]"><?= htmlspecialchars($e['matricule']) ?></td>
                    <td class="px-3 py-3.5 font-semibold text-[#1a1b22]"><?= htmlspecialchars($e['nom']) ?></td>
                    <td class="px-3 py-3.5 text-[#374151]"><?= htmlspecialchars($e['prenom']) ?></td>
                    <td class="px-3 py-3.5"><?= sexeBadge($e['sexe']) ?></td>
                    <td class="px-3 py-3.5 text-[#374151]"><?= htmlspecialchars($e['nom_classe']) ?></td>
                    <td class="px-3 py-3.5 text-[#374151]"><?= htmlspecialchars($e['nom_filiere']) ?></td>
                  </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <!-- Pied -->
          <div class="mt-4 flex flex-wrap items-center justify-between gap-2 text-xs text-muted">
            <p><?= count($etudiants) ?> étudiant<?= count($etudiants) > 1 ? 's' : '' ?> affiché<?= count($etudiants) > 1 ? 's' : '' ?></p>
            <p>Consultation en lecture seule</p>
          </div>

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