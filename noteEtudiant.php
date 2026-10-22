<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'etudiant') {
  header('Location: loginEcole221.php');
  exit;
}

require 'config.php';

$idEtudiant = $_SESSION['id_etudiant'] ?? 0;

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

// Mentions
function mention($n) {
  if ($n === null) return '';
  $n = (float)$n;
  if ($n >= 16) return 'Très bien';
  if ($n >= 14) return 'Bien';
  if ($n >= 12) return 'Assez bien';
  if ($n >= 10) return 'Passable';
  return 'Insuffisant';
}
$mentionBadge = [
  'Très bien'   => 'bg-[#dcfce7] text-[#15803d]',
  'Bien'        => 'bg-[#dbeafe] text-[#2563eb]',
  'Assez bien'  => 'bg-[#fff1e6] text-[#ea580c]',
  'Passable'    => 'bg-[#fef9c3] text-[#a16207]',
  'Insuffisant' => 'bg-[#fee2e2] text-[#b91c1c]',
];
$mentionCouleur = [
  'Très bien'   => '#16a34a',
  'Bien'        => '#2563eb',
  'Assez bien'  => '#ea580c',
  'Passable'    => '#a16207',
  'Insuffisant' => '#e11d2a',
];

// Notes de l'étudiant, par matière (CC / Examen / TP)
$stmt = $pdo->prepare("
  SELECT m.id_matiere, m.nom_matiere, m.code_matiere, COALESCE(m.coefficient, 1) AS coef,
         n.type_eval, n.valeur
  FROM note n
  JOIN matiere m ON m.id_matiere = n.id_matiere
  WHERE n.id_etudiant = :id AND n.publie = 1
  ORDER BY m.nom_matiere
");
$stmt->execute(['id' => $idEtudiant]);

$matieres = [];
foreach ($stmt->fetchAll() as $r) {
  $idm = $r['id_matiere'];
  if (!isset($matieres[$idm])) {
    $matieres[$idm] = [
      'nom' => $r['nom_matiere'], 'code' => $r['code_matiere'], 'coef' => (int)$r['coef'],
      'CC' => null, 'Examen' => null, 'TP' => null, 'valeurs' => [],
    ];
  }
  $matieres[$idm][$r['type_eval']] = (float)$r['valeur'];
  $matieres[$idm]['valeurs'][] = (float)$r['valeur'];
}
// Moyenne de chaque matière + indicateur TP
$hasTP = false;
foreach ($matieres as $idm => $m) {
  $matieres[$idm]['moy'] = count($m['valeurs']) ? array_sum($m['valeurs']) / count($m['valeurs']) : null;
  if ($m['TP'] !== null) $hasTP = true;
}

// Moyenne générale de l'étudiant (pondérée par le coefficient)
function moyenneGenerale($mats) {
  $sn = 0; $sc = 0;
  foreach ($mats as $m) {
    if (!isset($m['moy']) || $m['moy'] === null) continue;
    $sn += $m['moy'] * $m['coef'];
    $sc += $m['coef'];
  }
  return $sc > 0 ? $sn / $sc : null;
}

// Moyennes de toute la classe (pour le classement et les repères)
$classeMoyennes = [];
if (!empty($etu['id_classe'])) {
  $stmt = $pdo->prepare("
    SELECT n.id_etudiant, n.id_matiere, n.valeur, COALESCE(m.coefficient, 1) AS coef
    FROM note n
    JOIN etudiant e ON e.id_etudiant = n.id_etudiant
    JOIN matiere  m ON m.id_matiere  = n.id_matiere
    WHERE e.id_classe = :c AND n.publie = 1
  ");
  $stmt->execute(['c' => $etu['id_classe']]);

  $parEtu = [];
  foreach ($stmt->fetchAll() as $r) {
    $parEtu[$r['id_etudiant']][$r['id_matiere']]['coef'] = (int)$r['coef'];
    $parEtu[$r['id_etudiant']][$r['id_matiere']]['valeurs'][] = (float)$r['valeur'];
  }
  foreach ($parEtu as $idE => $mats) {
    $m2 = [];
    foreach ($mats as $mat) {
      $m2[] = ['coef' => $mat['coef'], 'moy' => array_sum($mat['valeurs']) / count($mat['valeurs'])];
    }
    $moy = moyenneGenerale($m2);
    if ($moy !== null) $classeMoyennes[$idE] = $moy;
  }
}

$maMoyenne = $classeMoyennes[$idEtudiant] ?? null;
$moyAffiche = $maMoyenne !== null ? number_format($maMoyenne, 2, ',', '') : '—';

// Classement
$rang = null; $totalClasse = count($classeMoyennes);
if ($maMoyenne !== null) {
  $rang = 1;
  foreach ($classeMoyennes as $m) { if ($m > $maMoyenne) $rang++; }
}

// Repères de promotion
$moyClasse = $totalClasse ? array_sum($classeMoyennes) / $totalClasse : null;
$meilleure = $totalClasse ? max($classeMoyennes) : null;
$nbMatieres = count($matieres);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Notes & résultats (Étudiant)</title>
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
      <symbol id="i-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 21v-8M12 21V4M19 21v-12"/></symbol>
      <symbol id="i-award" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.47 12.89 17 22l-5-3-5 3 1.53-9.11"/></symbol>
      <symbol id="i-check-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></symbol>
      <symbol id="i-trophy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6M18 9h1.5a2.5 2.5 0 0 0 0-5H18M4 22h16M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22M18 2H6v7a6 6 0 0 0 12 0V2Z"/></symbol>
      <symbol id="i-download" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></symbol>
    </defs>
  </svg>

  <!-- ===================== SIDEBAR ===================== -->
  <?php $active = 'notes'; include 'sidebarEtudiant.php'; ?>

  <!-- ===================== CONTENU ===================== -->
  <div class="app-content min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[88px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-10">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <div>
          <p class="text-[11px] font-semibold uppercase tracking-wide text-primary">Résultats publiés</p>
          <h1 class="text-lg font-bold leading-tight">Notes &amp; résultats</h1>
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

        <?php if ($nbMatieres === 0): ?>
          <section class="rounded-2xl border border-[#ececef] bg-white p-10 text-center shadow-sm">
            <p class="text-sm text-muted">Aucune note publiée pour le moment.</p>
            <p class="mt-1 text-xs text-muted">Vos résultats apparaîtront ici dès que vos formateurs les auront saisis et publiés.</p>
          </section>
        <?php else: ?>

        <!-- Cartes de synthèse -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-[1.9fr_1fr_1fr]">
          <!-- Moyenne générale -->
          <div class="flex items-center justify-between gap-4 rounded-2xl bg-ink p-6 text-white shadow-sm">
            <div>
              <p class="text-[11px] font-semibold uppercase tracking-wide text-white/50">Moyenne générale</p>
              <p class="mt-2 text-5xl font-extrabold leading-none"><?= $moyAffiche ?> <span class="text-2xl font-semibold text-white/50">/ 20</span></p>
              <p class="mt-3 inline-flex items-center gap-1.5 text-xs font-semibold text-[#4ade80]"><svg class="h-3.5 w-3.5"><use href="#i-award" /></svg> <?= mention($maMoyenne) ?></p>
            </div>
            <span class="flex h-20 w-20 shrink-0 items-center justify-center rounded-full bg-white text-primary shadow-md"><svg class="h-9 w-9"><use href="#i-award" /></svg></span>
          </div>
          <!-- Matières notées -->
          <div class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#dcfce7] text-[#15803d]"><svg class="h-4 w-4"><use href="#i-check-circle" /></svg></span>
            <p class="mt-3 text-2xl font-extrabold text-[#1a1b22]"><?= $nbMatieres ?></p>
            <p class="mt-1 text-xs text-muted">Matière<?= $nbMatieres > 1 ? 's' : '' ?> notée<?= $nbMatieres > 1 ? 's' : '' ?></p>
          </div>
          <!-- Classement -->
          <div class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#fdeaec] text-primary"><svg class="h-4 w-4"><use href="#i-trophy" /></svg></span>
            <p class="mt-3 text-2xl font-extrabold text-[#1a1b22]"><?= $rang !== null ? $rang . 'e / ' . $totalClasse : '—' ?></p>
            <p class="mt-1 text-xs text-muted">Classement dans la classe</p>
          </div>
        </div>

        <!-- Filtre + téléchargement -->
        <div class="no-print flex flex-wrap items-center justify-between gap-3">
          <div class="relative">
            <select id="filtreMatiere" class="appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm font-medium text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
              <option value="">Toutes les matières</option>
              <?php foreach ($matieres as $idm => $m): ?>
                <option value="<?= $idm ?>"><?= htmlspecialchars($m['nom']) ?></option>
              <?php endforeach; ?>
            </select>
            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></span>
          </div>
          <button id="btnTelecharger" type="button" class="inline-flex items-center gap-2 rounded-lg border border-primary px-4 py-2.5 text-sm font-semibold text-primary transition hover:bg-primary hover:text-white">
            <svg class="h-4 w-4"><use href="#i-download" /></svg> Télécharger le relevé
          </button>
        </div>

        <!-- Détail + repères -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-[1.7fr_1fr]">

          <!-- Détail par matière -->
          <section class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm sm:p-6">
            <h3 class="font-bold text-[#1a1b22]">Détail par matière</h3>
            <div class="mt-4 overflow-x-auto">
              <table class="w-full min-w-[620px] text-left text-sm">
                <thead>
                  <tr class="border-b border-[#ececef] text-[11px] uppercase tracking-wide text-muted">
                    <th class="px-2 py-3 font-semibold">Matière</th>
                    <th class="px-2 py-3 font-semibold">CC</th>
                    <th class="px-2 py-3 font-semibold">Examen</th>
                    <?php if ($hasTP): ?><th class="px-2 py-3 font-semibold">TP</th><?php endif; ?>
                    <th class="px-2 py-3 font-semibold">Moyenne</th>
                    <th class="px-2 py-3 font-semibold">Coef.</th>
                    <th class="px-2 py-3 font-semibold">Mention</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-[#f1f1f3]">
                  <?php foreach ($matieres as $idm => $m):
                    $men = mention($m['moy']);
                    $pct = $m['moy'] !== null ? round($m['moy'] / 20 * 100) : 0;
                    $col = $mentionCouleur[$men] ?? '#9ca3af';
                    $fmt = fn($v) => $v !== null ? number_format($v, 2, ',', '') : '—';
                  ?>
                  <tr class="js-ligne hover:bg-[#faf9fb]" data-matiere="<?= $idm ?>">
                    <td class="px-2 py-3.5"><p class="font-semibold text-[#1a1b22]"><?= htmlspecialchars($m['nom']) ?></p><?php if (!empty($m['code'])): ?><p class="text-xs text-muted"><?= htmlspecialchars($m['code']) ?></p><?php endif; ?></td>
                    <td class="px-2 py-3.5 text-[#374151]"><?= $fmt($m['CC']) ?></td>
                    <td class="px-2 py-3.5 text-[#374151]"><?= $fmt($m['Examen']) ?></td>
                    <?php if ($hasTP): ?><td class="px-2 py-3.5 text-[#374151]"><?= $fmt($m['TP']) ?></td><?php endif; ?>
                    <td class="px-2 py-3.5">
                      <p class="font-semibold text-[#1a1b22]"><?= $fmt($m['moy']) ?></p>
                      <div class="mt-1 h-1.5 w-24 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full" style="width:<?= $pct ?>%;background:<?= $col ?>"></div></div>
                    </td>
                    <td class="px-2 py-3.5 text-[#374151]"><?= $m['coef'] ?></td>
                    <td class="px-2 py-3.5"><?php if ($men): ?><span class="inline-block rounded-full <?= $mentionBadge[$men] ?> px-2.5 py-0.5 text-xs font-semibold"><?= $men ?></span><?php endif; ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </section>

          <!-- Repères de promotion -->
          <section class="h-fit rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm">
            <h3 class="font-bold text-[#1a1b22]">Repères de la classe</h3>
            <div class="mt-4 flex flex-col gap-3 text-sm">
              <div class="flex items-center justify-between">
                <span class="inline-flex items-center gap-2 text-[#374151]"><span class="h-2.5 w-2.5 rounded-full bg-primary"></span> Votre moyenne</span>
                <span class="font-bold text-[#1a1b22]"><?= $moyAffiche ?></span>
              </div>
              <div class="flex items-center justify-between">
                <span class="inline-flex items-center gap-2 text-[#374151]"><span class="h-2.5 w-2.5 rounded-full bg-[#2563eb]"></span> Moyenne de la classe</span>
                <span class="font-bold text-[#1a1b22]"><?= $moyClasse !== null ? number_format($moyClasse, 2, ',', '') : '—' ?></span>
              </div>
              <div class="flex items-center justify-between">
                <span class="inline-flex items-center gap-2 text-[#374151]"><span class="h-2.5 w-2.5 rounded-full bg-[#16a34a]"></span> Meilleure moyenne</span>
                <span class="font-bold text-[#1a1b22]"><?= $meilleure !== null ? number_format($meilleure, 2, ',', '') : '—' ?></span>
              </div>
            </div>
            <p class="mt-4 border-t border-[#f1f1f3] pt-3 text-xs text-muted">Moyenne générale pondérée par les coefficients, calculée sur les notes publiées.</p>
          </section>
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

    /* ----- Filtre par matière ----- */
    const filtre = document.getElementById('filtreMatiere');
    if (filtre) {
      filtre.addEventListener('change', () => {
        const val = filtre.value;
        document.querySelectorAll('.js-ligne').forEach((tr) => {
          tr.style.display = (val === '' || tr.dataset.matiere === val) ? '' : 'none';
        });
      });
    }

    /* ----- Télécharger le relevé (impression navigateur) ----- */
    const btn = document.getElementById('btnTelecharger');
    if (btn) btn.addEventListener('click', () => window.print());
  </script>
</body>
</html>