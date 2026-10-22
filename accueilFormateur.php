<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'formateur') {
  header('Location: loginEcole221.php');
  exit;
}

require 'config.php';

$idFormateur = $_SESSION['id_formateur'] ?? 0;

// Infos du formateur connecté (nom, email, photo)
$stmt = $pdo->prepare("SELECT nom, prenom, email, photo FROM formateur WHERE id_formateur = :id");
$stmt->execute(['id' => $idFormateur]);
$moi = $stmt->fetch();

$nomAffiche   = $moi ? trim($moi['prenom'] . ' ' . $moi['nom']) : ($_SESSION['login'] ?? 'Formateur');
$prenomAffiche = $moi ? $moi['prenom'] : explode(' ', $nomAffiche)[0];
$emailAffiche = $moi && !empty($moi['email']) ? $moi['email'] : ($_SESSION['login'] ?? '');
$photo        = $moi && !empty($moi['photo']) ? $moi['photo'] : 'https://i.pravatar.cc/64?img=12';

// Mes classes (celles où j'ai des séances) + effectif réel + filière
$stmt = $pdo->prepare("
  SELECT c.id_classe, c.nom_classe, c.capacite, f.nom_filiere,
         COUNT(DISTINCT e.id_etudiant) AS eff
  FROM seance s
  JOIN classe  c ON c.id_classe  = s.id_classe
  JOIN filiere f ON f.id_filiere = c.id_filiere
  LEFT JOIN etudiant e ON e.id_classe = c.id_classe
  WHERE s.id_formateur = :id
  GROUP BY c.id_classe, c.nom_classe, c.capacite, f.nom_filiere
  ORDER BY c.nom_classe
");
$stmt->execute(['id' => $idFormateur]);
$mesClasses = $stmt->fetchAll();

// Calculs
$nbClasses = count($mesClasses);
$totalEtudiants = 0;
$filieresSet = [];
$maxEff = 1;
foreach ($mesClasses as $c) {
  $totalEtudiants += (int)$c['eff'];
  $filieresSet[$c['nom_filiere']] = true;
  if ((int)$c['eff'] > $maxEff) $maxEff = (int)$c['eff'];
}
$nbFilieres = count($filieresSet);
$listeFilieres = implode(', ', array_keys($filieresSet));

// Nombre de séances
$stmt = $pdo->prepare("SELECT COUNT(*) FROM seance WHERE id_formateur = :id");
$stmt->execute(['id' => $idFormateur]);
$nbSeances = (int)$stmt->fetchColumn();

// Date du jour en français
$joursFr = ['Sunday' => 'Dimanche', 'Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi', 'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi'];
$moisFr  = ['January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars', 'April' => 'Avril', 'May' => 'Mai', 'June' => 'Juin', 'July' => 'Juillet', 'August' => 'Août', 'September' => 'Septembre', 'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'];
$dateJour = $joursFr[date('l')] . ' ' . date('j') . ' ' . $moisFr[date('F')] . ' ' . date('Y');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Tableau de bord Formateur</title>
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
      <symbol id="i-tree" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="6" height="6"/><rect x="15" y="9" width="6" height="6"/><rect x="3" y="15" width="6" height="6"/><path d="M9 6h3v12H9M12 12h3"/></symbol>
      <symbol id="i-door" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 21V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17M3 21h18M14 12v.01"/></symbol>
      <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M2 21v-1a6 6 0 0 1 12 0v1M16 4.5a3.5 3.5 0 0 1 0 7M22 21v-1a6 6 0 0 0-4-5.6"/></symbol>
      <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
      <symbol id="i-trend" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17l6-6 4 4 7-7"/><path d="M17 8h4v4"/></symbol>
      <symbol id="i-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></symbol>
    </defs>
  </svg>

  <!-- ===================== SIDEBAR ===================== -->
  <?php $active = 'dashboard'; include 'sidebarFormateur.php'; ?>

  <div class="app-content min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <h2 class="text-sm font-semibold">Tableau de bord - Formateur</h2>
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

        <!-- Bandeau de bienvenue -->
        <section class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#e11d2a] via-[#b91c1c] to-[#991b1b] p-8 shadow-md">
          <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/5 blur-xl"></div>
          <div class="relative flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
            <div class="flex flex-col gap-1.5">
              <h1 class="text-3xl font-bold leading-[44px] tracking-[-0.9px] text-white sm:text-4xl">Bonjour <?= htmlspecialchars($prenomAffiche) ?>, bienvenue sur<br />École 221</h1>
              <p class="text-base text-[#f4f4f5]">Espace enseignant — vos classes et vos étudiants</p>
            </div>
            <div class="rounded-xl bg-black/10 px-6 py-3.5 text-right backdrop-blur-[2px]">
              <p class="text-xl font-bold leading-7 text-white"><?= $dateJour ?></p>
            </div>
          </div>
        </section>

        <!-- 4 cartes -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
          <div class="rounded-xl bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between"><span class="text-[11px] font-semibold uppercase tracking-wide text-muted">Mes étudiants</span><span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-users" /></svg></span></div>
            <p class="mt-3 text-3xl font-bold text-[#1a1b22]"><?= $totalEtudiants ?></p><p class="text-xs text-muted">Dans mes classes</p>
          </div>
          <div class="rounded-xl bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between"><span class="text-[11px] font-semibold uppercase tracking-wide text-muted">Mes classes</span><span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-door" /></svg></span></div>
            <p class="mt-3 text-3xl font-bold text-[#1a1b22]"><?= $nbClasses ?></p><p class="text-xs text-muted">Groupes qui me sont assignés</p>
          </div>
          <div class="rounded-xl bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between"><span class="text-[11px] font-semibold uppercase tracking-wide text-muted">Mes filières</span><span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-tree" /></svg></span></div>
            <p class="mt-3 text-3xl font-bold text-[#1a1b22]"><?= $nbFilieres ?></p><p class="truncate text-xs text-muted"><?= htmlspecialchars($listeFilieres ?: '—') ?></p>
          </div>
          <div class="rounded-xl bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between"><span class="text-[11px] font-semibold uppercase tracking-wide text-muted">Mes séances</span><span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-calendar" /></svg></span></div>
            <p class="mt-3 text-3xl font-bold text-[#1a1b22]"><?= $nbSeances ?></p><p class="text-xs text-muted">Cours programmés</p>
          </div>
        </div>

        <!-- Deux panneaux -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

          <!-- Mes classes (taux de remplissage) -->
          <section class="flex flex-col gap-5 rounded-2xl bg-white p-6 shadow-sm">
            <div>
              <h3 class="text-base font-bold text-[#1a1b22]">Mes classes</h3>
              <p class="text-xs text-muted">Effectifs des groupes qui me sont assignés</p>
            </div>
            <div class="flex flex-col gap-4">
              <?php if ($nbClasses === 0): ?>
                <p class="text-sm text-muted">Aucune classe ne vous est assignée pour l'instant.</p>
              <?php else: ?>
                <?php foreach ($mesClasses as $c): ?>
                  <?php $cap = (int)$c['capacite']; $taux = $cap > 0 ? round((int)$c['eff'] / $cap * 100) : 0; ?>
                  <div>
                    <div class="mb-1.5 flex items-center justify-between text-sm"><span class="font-semibold text-[#1a1b22]"><?= htmlspecialchars($c['nom_classe']) ?></span><span class="text-sm font-bold text-primary"><?= (int)$c['eff'] ?> / <?= $cap ?></span></div>
                    <div class="h-2.5 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:<?= $taux ?>%"></div></div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </section>

          <!-- Répartition de mes étudiants -->
          <section class="flex flex-col gap-5 rounded-2xl bg-white p-6 shadow-sm">
            <div>
              <h3 class="text-base font-bold text-[#1a1b22]">Répartition de mes étudiants</h3>
              <p class="text-xs text-muted">Par classe que j'encadre</p>
            </div>
            <div class="flex flex-col gap-4">
              <?php if ($nbClasses === 0): ?>
                <p class="text-sm text-muted">Aucun étudiant à afficher.</p>
              <?php else: ?>
                <?php foreach ($mesClasses as $c): ?>
                  <?php $w = round((int)$c['eff'] / $maxEff * 100); ?>
                  <div>
                    <div class="mb-1.5 flex items-center justify-between text-sm"><span class="font-medium text-[#1f2937]"><?= htmlspecialchars($c['nom_classe']) ?></span><span class="font-bold text-[#1a1b22]"><?= (int)$c['eff'] ?></span></div>
                    <div class="h-2.5 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:<?= $w ?>%"></div></div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
            <div class="flex items-center gap-2 text-xs text-muted">
              <svg class="h-4 w-4 text-primary"><use href="#i-trend" /></svg>
              <p>Total : <?= $totalEtudiants ?> étudiant<?= $totalEtudiants > 1 ? 's' : '' ?> — consultation en lecture seule</p>
            </div>
          </section>
        </div>
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