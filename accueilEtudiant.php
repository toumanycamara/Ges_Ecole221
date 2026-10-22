<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'etudiant') {
  header('Location: loginEcole221.php');
  exit;
}

require 'config.php';

$idEtudiant = $_SESSION['id_etudiant'] ?? 0;

// Dates en français
$joursFr = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
$moisFr  = ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
$jourToday  = $joursFr[date('N') - 1];
$dateAffiche = $jourToday . ' ' . date('j') . ' ' . $moisFr[(int)date('n')] . ' ' . date('Y');

// Infos de l'étudiant + sa classe
$stmt = $pdo->prepare("
  SELECT e.nom, e.prenom, e.matricule, e.photo, e.id_classe,
         c.nom_classe, f.nom_filiere, n.libelle_niveau
  FROM etudiant e
  LEFT JOIN classe  c ON c.id_classe  = e.id_classe
  LEFT JOIN filiere f ON f.id_filiere = c.id_filiere
  LEFT JOIN niveau  n ON n.id_niveau  = c.id_niveau
  WHERE e.id_etudiant = :id
");
$stmt->execute(['id' => $idEtudiant]);
$etu = $stmt->fetch();

$prenom    = $etu['prenom'] ?? '';
$nomComplet = trim(($etu['prenom'] ?? '') . ' ' . ($etu['nom'] ?? ''));
if ($nomComplet === '') $nomComplet = $_SESSION['login'] ?? 'Étudiant';
$classeLib = $etu['nom_classe'] ?? '—';
$photo     = !empty($etu['photo']) ? $etu['photo'] : '';
$initiales = strtoupper(mb_substr($etu['prenom'] ?? '', 0, 1) . mb_substr($etu['nom'] ?? '', 0, 1));

// Moyenne générale (notes publiées)
$stmt = $pdo->prepare("SELECT AVG(valeur) FROM note WHERE id_etudiant = :id AND publie = 1");
$stmt->execute(['id' => $idEtudiant]);
$moyenne = $stmt->fetchColumn();
$moyAffiche = $moyenne !== null ? number_format((float)$moyenne, 2, ',', '') : '—';

function mention($n) {
  if ($n === null) return 'Pas encore de note';
  $n = (float)$n;
  if ($n >= 16) return 'Très bien';
  if ($n >= 14) return 'Bien';
  if ($n >= 12) return 'Assez bien';
  if ($n >= 10) return 'Passable';
  return 'À améliorer';
}

// Nombre de matières notées
$stmt = $pdo->prepare("SELECT COUNT(DISTINCT id_matiere) FROM note WHERE id_etudiant = :id AND publie = 1");
$stmt->execute(['id' => $idEtudiant]);
$nbMatieres = (int)$stmt->fetchColumn();

// Cours d'aujourd'hui (séances de sa classe, pour le jour courant)
$coursJour = [];
if (!empty($etu['id_classe'])) {
  $stmt = $pdo->prepare("
    SELECT s.heure_debut, s.heure_fin, s.salle, m.nom_matiere,
           fo.nom AS fnom, fo.prenom AS fprenom
    FROM seance s
    JOIN matiere   m  ON m.id_matiere    = s.id_matiere
    JOIN formateur fo ON fo.id_formateur = s.id_formateur
    WHERE s.id_classe = :c AND s.jour = :j
    ORDER BY s.heure_debut
  ");
  $stmt->execute(['c' => $etu['id_classe'], 'j' => $jourToday]);
  $coursJour = $stmt->fetchAll();
}
$nbCours = count($coursJour);
$accents = ['#e11d2a', '#2563eb', '#7c3aed', '#15803d']; // couleurs des cours
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Tableau de bord (Étudiant)</title>
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
      <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
      <symbol id="i-trend" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 17 6-6 4 4 8-8"/><path d="M17 7h4v4"/></symbol>
      <symbol id="i-award" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.47 12.89 17 22l-5-3-5 3 1.53-9.11"/></symbol>
    </defs>
  </svg>

  <!-- ===================== SIDEBAR ===================== -->
  <?php $active = 'dashboard'; include 'sidebarEtudiant.php'; ?>

  <!-- ===================== CONTENU ===================== -->
  <div class="app-content min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[88px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-10">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <div>
          <p class="text-xs font-medium text-primary"><?= htmlspecialchars($dateAffiche) ?></p>
          <h1 class="text-lg font-bold leading-tight">Tableau de bord</h1>
        </div>
      </div>
      <div class="flex items-center gap-3 sm:gap-4">
        <div class="flex items-center gap-2">
          <?php if ($photo): ?>
            <img src="<?= htmlspecialchars($photo) ?>" alt="Photo" class="h-9 w-9 rounded-full object-cover" />
          <?php else: ?>
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-primary text-xs font-bold text-white"><?= htmlspecialchars($initiales) ?></span>
          <?php endif; ?>
          <div class="hidden sm:block"><p class="text-sm font-semibold leading-[17.5px]"><?= htmlspecialchars($nomComplet) ?></p><p class="text-xs leading-3 text-muted"><?= htmlspecialchars($classeLib) ?></p></div>
        </div>
        <span class="whitespace-nowrap rounded-full bg-primary px-2.5 py-1 text-xs font-semibold text-white shadow-sm">Étudiant</span>
      </div>
    </header>

    <main class="px-4 py-7 sm:px-6 lg:px-10">
      <div class="mx-auto flex max-w-[1180px] flex-col gap-6">

        <!-- Bonjour + action -->
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-primary px-6 py-6 text-white shadow-sm">
          <div>
            <h2 class="text-2xl font-extrabold">Bonjour <?= htmlspecialchars($prenom) ?> 👋</h2>
            <p class="mt-1 text-sm text-white/85">Voici l'essentiel de votre journée et votre progression ce semestre.</p>
          </div>
          <a href="emploi-du-temps-etudiant.php" class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-primary shadow-sm transition hover:bg-white/90">
            <svg class="h-4 w-4"><use href="#i-calendar" /></svg> Voir mon planning
          </a>
        </div>

        <!-- Cartes de synthèse -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
          <div class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
              <p class="text-sm text-muted">Moyenne générale</p>
              <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#fdeaec] text-primary"><svg class="h-4 w-4"><use href="#i-award" /></svg></span>
            </div>
            <div class="mt-3 flex items-end justify-between">
              <p class="text-3xl font-extrabold text-[#1a1b22]"><?= $moyAffiche ?><?= $moyenne !== null ? ' <span class="text-base font-semibold text-muted">/ 20</span>' : '' ?></p>
              <span class="text-xs font-semibold text-primary"><?= mention($moyenne) ?></span>
            </div>
          </div>
          <div class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
              <p class="text-sm text-muted">Matières notées</p>
              <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#fdeaec] text-primary"><svg class="h-4 w-4"><use href="#i-trend" /></svg></span>
            </div>
            <div class="mt-3 flex items-end justify-between">
              <p class="text-3xl font-extrabold text-[#1a1b22]"><?= $nbMatieres ?></p>
              <span class="text-xs font-semibold text-muted">matière<?= $nbMatieres > 1 ? 's' : '' ?> évaluée<?= $nbMatieres > 1 ? 's' : '' ?></span>
            </div>
          </div>
        </div>

        <!-- Cours d'aujourd'hui -->
        <section class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm sm:p-6">
          <div class="flex items-center justify-between">
            <h3 class="font-bold text-[#1a1b22]">Cours d'aujourd'hui</h3>
            <a href="emploi-du-temps-etudiant.php" class="text-xs font-semibold text-primary hover:underline">Emploi du temps complet</a>
          </div>
          <p class="mt-2 inline-flex items-center gap-1.5 text-xs font-semibold text-primary"><svg class="h-3.5 w-3.5"><use href="#i-calendar" /></svg> <?= $jourToday ?> · <?= $nbCours ?> cours</p>

          <?php if ($nbCours === 0): ?>
            <p class="mt-4 rounded-xl bg-[#fafafa] px-4 py-6 text-center text-sm text-muted">Aucun cours programmé aujourd'hui.</p>
          <?php else: ?>
            <div class="mt-4 flex flex-col divide-y divide-[#f1f1f3]">
              <?php foreach ($coursJour as $i => $c): $col = $accents[$i % count($accents)]; ?>
                <div class="flex items-center gap-4 py-3.5">
                  <div class="w-14 shrink-0 text-sm font-medium"><p class="text-[#1a1b22]"><?= substr($c['heure_debut'], 0, 5) ?></p><p class="text-xs text-muted"><?= substr($c['heure_fin'], 0, 5) ?></p></div>
                  <div class="flex-1 border-l-2 pl-3" style="border-color: <?= $col ?>"><p class="font-semibold text-[#1a1b22]"><?= htmlspecialchars($c['nom_matiere']) ?></p><p class="text-xs text-muted"><?= htmlspecialchars(trim($c['fprenom'] . ' ' . $c['fnom'])) ?></p></div>
                  <?php if (!empty($c['salle'])): ?><span class="rounded-full bg-[#fdeaec] px-2.5 py-1 text-xs font-semibold text-primary">Salle <?= htmlspecialchars($c['salle']) ?></span><?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
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