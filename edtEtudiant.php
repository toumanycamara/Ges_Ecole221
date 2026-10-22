<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'etudiant') {
  header('Location: loginEcole221.php');
  exit;
}

require 'config.php';

$idEtudiant = $_SESSION['id_etudiant'] ?? 0;
$jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];

// Infos de l'étudiant + sa classe
$stmt = $pdo->prepare("
  SELECT e.nom, e.prenom, e.photo, e.id_classe, c.nom_classe
  FROM etudiant e
  LEFT JOIN classe c ON c.id_classe = e.id_classe
  WHERE e.id_etudiant = :id
");
$stmt->execute(['id' => $idEtudiant]);
$etu = $stmt->fetch();
$nomComplet = trim(($etu['prenom'] ?? '') . ' ' . ($etu['nom'] ?? ''));
if ($nomComplet === '') $nomComplet = $_SESSION['login'] ?? 'Étudiant';
$classeLib = $etu['nom_classe'] ?? '—';
$photo     = !empty($etu['photo']) ? $etu['photo'] : '';
$initiales = strtoupper(mb_substr($etu['prenom'] ?? '', 0, 1) . mb_substr($etu['nom'] ?? '', 0, 1));

// Séances de la classe de l'étudiant
$seances = [];
$totalMinutes = 0;
if (!empty($etu['id_classe'])) {
  $stmt = $pdo->prepare("
    SELECT s.jour, s.heure_debut, s.heure_fin, s.salle, m.nom_matiere,
           fo.nom AS fnom, fo.prenom AS fprenom
    FROM seance s
    JOIN matiere   m  ON m.id_matiere    = s.id_matiere
    JOIN formateur fo ON fo.id_formateur = s.id_formateur
    WHERE s.id_classe = :c
    ORDER BY s.heure_debut
  ");
  $stmt->execute(['c' => $etu['id_classe']]);
  $seances = $stmt->fetchAll();
}

// Rangement par jour + couleur par matière
$palette = ['#e11d2a', '#2563eb', '#7c3aed', '#ea580c', '#16a34a', '#0891b2', '#a16207'];
$couleurMatiere = [];
$edt = [];
foreach ($seances as $s) {
  $nom = $s['nom_matiere'];
  if (!isset($couleurMatiere[$nom])) {
    $couleurMatiere[$nom] = $palette[count($couleurMatiere) % count($palette)];
  }
  $debut = substr($s['heure_debut'], 0, 5);
  $fin   = substr($s['heure_fin'], 0, 5);
  $edt[$s['jour']][] = [
    'h'     => $debut . ' - ' . $fin,
    'titre' => $nom,
    'prof'  => trim($s['fprenom'] . ' ' . $s['fnom']),
    'salle' => $s['salle'],
    'c'     => $couleurMatiere[$nom],
  ];
  $totalMinutes += (strtotime("1970-01-01 $fin") - strtotime("1970-01-01 $debut")) / 60;
}
$nbSeances = count($seances);
$totalHeures = $totalMinutes / 60;
$totalAffiche = rtrim(rtrim(number_format($totalHeures, 1, ',', ''), '0'), ',');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Emploi du temps (Étudiant)</title>
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
  <style>
    @media print {
      #sidebar, header, #overlay, .no-print { display: none !important; }
      .app-content { margin-left: 0 !important; }
      body { background: #fff; }
    }
  </style>
</head>
<body class="font-sans bg-page text-ink">

  <svg class="hidden" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <symbol id="i-download" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></symbol>
      <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
      <symbol id="i-pin-s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
    </defs>
  </svg>

  <!-- ===================== SIDEBAR ===================== -->
  <?php $active = 'edt'; include 'sidebarEtudiant.php'; ?>

  <!-- ===================== CONTENU ===================== -->
  <div class="app-content min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[88px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-10">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <div>
          <p class="text-[11px] font-semibold uppercase tracking-wide text-primary">Semaine type</p>
          <h1 class="text-lg font-bold leading-tight">Emploi du temps</h1>
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
      <div class="mx-auto flex max-w-[1240px] flex-col gap-6">

        <div class="no-print flex flex-wrap items-center justify-between gap-3">
          <div>
            <h2 class="text-xl font-bold leading-7 tracking-[-0.4px] text-[#1a1b22]">Mon planning</h2>
            <p class="mt-0.5 text-xs text-muted">Vos cours de la semaine pour la classe <?= htmlspecialchars($classeLib) ?></p>
          </div>
          <button id="btnTelecharger" type="button" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-download" /></svg> Télécharger</button>
        </div>

        <?php if ($nbSeances === 0): ?>
          <div class="rounded-2xl border border-[#ececef] bg-white p-10 text-center shadow-sm">
            <p class="text-sm text-muted">Aucun cours programmé pour votre classe.</p>
            <p class="mt-1 text-xs text-muted">Votre emploi du temps apparaîtra ici dès que le secrétaire l'aura rempli.</p>
          </div>
        <?php else: ?>

        <!-- Grille par jour -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
          <?php foreach ($jours as $j): $cours = $edt[$j] ?? []; ?>
            <section class="flex flex-col rounded-2xl border border-[#ececef] bg-white p-3 shadow-sm">
              <div class="flex items-center justify-between border-b border-[#f1f1f3] pb-2.5">
                <p class="text-sm font-bold text-[#1a1b22]"><?= $j ?></p>
                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-ink text-[11px] font-bold text-white"><?= count($cours) ?></span>
              </div>
              <div class="mt-3 flex flex-col gap-2.5">
                <?php if (count($cours) === 0): ?>
                  <p class="py-6 text-center text-xs text-muted">Aucun cours</p>
                <?php else: foreach ($cours as $c): ?>
                  <article class="rounded-xl border-l-4 bg-[#faf9fb] p-3" style="border-left-color: <?= $c['c'] ?>">
                    <p class="text-[11px] font-semibold" style="color: <?= $c['c'] ?>"><?= htmlspecialchars($c['h']) ?></p>
                    <p class="mt-1 text-sm font-bold leading-tight text-[#1a1b22]"><?= htmlspecialchars($c['titre']) ?></p>
                    <?php if (!empty($c['prof'])): ?><p class="mt-0.5 text-xs text-muted"><?= htmlspecialchars($c['prof']) ?></p><?php endif; ?>
                    <?php if (!empty($c['salle'])): ?><p class="mt-1.5 inline-flex items-center gap-1 text-xs text-muted"><svg class="h-3 w-3"><use href="#i-pin-s" /></svg> Salle <?= htmlspecialchars($c['salle']) ?></p><?php endif; ?>
                  </article>
                <?php endforeach; endif; ?>
              </div>
            </section>
          <?php endforeach; ?>
        </div>

        <!-- Synthèse -->
        <div class="flex flex-wrap items-center justify-between gap-2 rounded-2xl border border-[#ececef] bg-white px-5 py-3.5 text-sm shadow-sm">
          <span class="inline-flex items-center gap-1.5 font-semibold text-[#1a1b22]"><svg class="h-4 w-4 text-primary"><use href="#i-clock" /></svg> Total : <span class="text-primary"><?= $totalAffiche ?> heures / semaine</span></span>
          <span class="text-muted">Nombre de séances : <span class="font-semibold text-[#1a1b22]"><?= $nbSeances ?></span></span>
        </div>

        <?php endif; ?>
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

    const btn = document.getElementById('btnTelecharger');
    if (btn) btn.addEventListener('click', () => window.print());
  </script>
</body>
</html>