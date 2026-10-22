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

// Mes classes (celles où j'ai des séances)
$stmt = $pdo->prepare("
  SELECT c.id_classe, c.nom_classe, c.salle, c.capacite,
         f.nom_filiere, n.libelle_niveau,
         COUNT(DISTINCT e.id_etudiant) AS eff
  FROM seance s
  JOIN classe  c ON c.id_classe  = s.id_classe
  JOIN filiere f ON f.id_filiere = c.id_filiere
  JOIN niveau  n ON n.id_niveau  = c.id_niveau
  LEFT JOIN etudiant e ON e.id_classe = c.id_classe
  WHERE s.id_formateur = :id
  GROUP BY c.id_classe, c.nom_classe, c.salle, c.capacite, f.nom_filiere, n.libelle_niveau
  ORDER BY c.nom_classe
");
$stmt->execute(['id' => $idFormateur]);
$mesClasses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Mes classes (Formateur)</title>
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
      <symbol id="i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/></symbol>
      <symbol id="i-chevron-right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></symbol>
    </defs>
  </svg>

  <!-- ===================== SIDEBAR ===================== -->
  <?php $active = 'classes'; include 'sidebarFormateur.php'; ?>

  <div class="app-content min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <h2 class="text-sm font-semibold">Mes classes - Formateur</h2>
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

        <!-- En-tête -->
        <div>
          <h1 class="text-2xl font-bold leading-8 tracking-[-0.6px] text-[#1a1b22]">Mes classes</h1>
          <p class="mt-1 text-xs text-muted">Les groupes où vous avez des cours — cliquez pour voir le détail</p>
        </div>

        <!-- Grille des classes -->
        <section class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
          <?php if (count($mesClasses) === 0): ?>
            <div class="col-span-full rounded-2xl bg-white p-10 text-center shadow-sm">
              <p class="text-sm text-muted">Aucune classe ne vous est assignée pour l'instant.</p>
              <p class="mt-1 text-xs text-muted">Une classe apparaît ici dès que vous avez un cours programmé dans l'emploi du temps.</p>
            </div>
          <?php else: ?>
            <?php foreach ($mesClasses as $c): ?>
              <?php $cap = (int)$c['capacite']; $taux = $cap > 0 ? round((int)$c['eff'] / $cap * 100) : 0; ?>
              <a href="detailsClasseFormateur.php?id=<?= $c['id_classe'] ?>" class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition hover:shadow-md">
                <div class="h-1.5 bg-primary"></div>
                <div class="flex flex-1 flex-col gap-3 p-5">
                  <div class="flex items-start justify-between gap-3">
                    <h3 class="font-bold text-[#1a1b22] group-hover:text-primary"><?= htmlspecialchars($c['nom_classe']) ?></h3>
                    <?php if (!empty($c['salle'])): ?><span class="shrink-0 rounded-md border border-[#e5e7eb] px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-muted"><?= htmlspecialchars($c['salle']) ?></span><?php endif; ?>
                  </div>
                  <div class="space-y-1 text-sm text-muted">
                    <p><span class="text-[#374151]">Filière :</span> <?= htmlspecialchars($c['nom_filiere']) ?></p>
                    <p><span class="text-[#374151]">Niveau :</span> <?= htmlspecialchars($c['libelle_niveau']) ?></p>
                  </div>
                  <div class="mt-1">
                    <div class="mb-1 flex items-center justify-between text-xs"><span class="font-medium text-muted">Effectif</span><span class="font-bold text-primary"><?= (int)$c['eff'] ?> / <?= $cap ?></span></div>
                    <div class="h-2 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:<?= $taux ?>%"></div></div>
                  </div>
                  <div class="mt-auto flex items-center justify-between border-t border-[#f3f4f6] pt-3">
                    <span class="flex items-center gap-2 text-xs font-medium text-muted"><svg class="h-4 w-4 text-primary"><use href="#i-user" /></svg> <?= (int)$c['eff'] ?> étudiant<?= (int)$c['eff'] > 1 ? 's' : '' ?></span>
                    <span class="flex items-center gap-1 text-xs font-medium text-primary">Voir <svg class="h-3.5 w-3.5"><use href="#i-chevron-right" /></svg></span>
                  </div>
                </div>
              </a>
            <?php endforeach; ?>
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