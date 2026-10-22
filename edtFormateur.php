<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'formateur') {
  header('Location: loginEcole221.php');
  exit;
}

require 'config.php';

$idFormateur = $_SESSION['id_formateur'] ?? 0;
$jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];

// Infos du formateur (en-tête)
$stmt = $pdo->prepare("SELECT nom, prenom, email, photo FROM formateur WHERE id_formateur = :id");
$stmt->execute(['id' => $idFormateur]);
$moi = $stmt->fetch();
$nomAffiche   = $moi ? trim($moi['prenom'] . ' ' . $moi['nom']) : ($_SESSION['login'] ?? 'Formateur');
$emailAffiche = $moi && !empty($moi['email']) ? $moi['email'] : ($_SESSION['login'] ?? '');
$photo        = $moi && !empty($moi['photo']) ? $moi['photo'] : 'https://i.pravatar.cc/64?img=12';

// Mes séances (toutes classes confondues)
$stmt = $pdo->prepare("
  SELECT s.id_seance, s.jour, s.heure_debut, s.heure_fin, s.salle,
         m.nom_matiere, c.nom_classe
  FROM seance s
  JOIN matiere m ON m.id_matiere = s.id_matiere
  JOIN classe  c ON c.id_classe  = s.id_classe
  WHERE s.id_formateur = :f
  ORDER BY s.heure_debut
");
$stmt->execute(['f' => $idFormateur]);
$seances = $stmt->fetchAll();

// Rangement par jour + créneau, et total d'heures
$edt = [];          // $edt[jour][label] = séance
$creneaux = [];     // label => heure de début (pour le tri)
$totalMinutes = 0;
foreach ($seances as $s) {
  $debut = substr($s['heure_debut'], 0, 5);
  $fin   = substr($s['heure_fin'], 0, 5);
  $label = $debut . ' - ' . $fin;
  $edt[$s['jour']][$label] = $s;
  if (!isset($creneaux[$label])) $creneaux[$label] = $debut;
  $totalMinutes += (strtotime("1970-01-01 $fin") - strtotime("1970-01-01 $debut")) / 60;
}
asort($creneaux); // tri des créneaux par heure de début

$totalHeures = $totalMinutes / 60;
$totalAffiche = rtrim(rtrim(number_format($totalHeures, 1, ',', ''), '0'), ',');
$nbSeances = count($seances);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Emploi du temps (Formateur)</title>
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
      <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
    </defs>
  </svg>

  <!-- ===================== SIDEBAR ===================== -->
  <?php $active = 'edt'; include 'sidebarFormateur.php'; ?>

  <!-- ===================== CONTENU ===================== -->
  <div class="app-content min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <h1 class="text-xl font-bold">Emploi du temps</h1>
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

        <div>
          <h2 class="text-2xl font-bold leading-8 tracking-[-0.6px] text-[#1a1b22]">Mon emploi du temps</h2>
          <p class="mt-1 text-xs text-muted">Toutes vos séances de la semaine, telles que programmées par le secrétaire.</p>
        </div>

        <section class="overflow-hidden rounded-2xl bg-white shadow-sm">

          <?php if ($nbSeances === 0): ?>
            <div class="p-10 text-center">
              <p class="text-sm text-muted">Aucune séance programmée pour l'instant.</p>
              <p class="mt-1 text-xs text-muted">Vos cours apparaîtront ici dès que le secrétaire les aura ajoutés à l'emploi du temps.</p>
            </div>
          <?php else: ?>

          <!-- Grille emploi du temps -->
          <div class="overflow-x-auto">
            <table class="w-full min-w-[860px] table-fixed border-collapse text-left">
              <thead>
                <tr class="text-[11px] uppercase tracking-wide text-muted">
                  <th class="w-[110px] border-b border-r border-[#ececef] px-3 py-3 font-semibold">Heure</th>
                  <?php foreach ($jours as $j): ?>
                    <th class="border-b border-r border-[#ececef] px-3 py-3 text-center font-semibold last:border-r-0"><?= $j ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody class="align-top">
                <?php foreach ($creneaux as $label => $debut): ?>
                  <tr>
                    <td class="border-b border-r border-[#ececef] px-3 py-3 text-xs font-medium text-muted"><?= htmlspecialchars($label) ?></td>
                    <?php foreach ($jours as $j): ?>
                      <td class="h-[120px] border-b border-r border-[#ececef] p-2 last:border-r-0">
                        <?php if (isset($edt[$j][$label])): $s = $edt[$j][$label]; ?>
                          <div class="rounded-xl bg-primary p-3 text-white shadow-sm">
                            <p class="text-sm font-bold leading-tight"><?= htmlspecialchars($s['nom_matiere']) ?></p>
                            <p class="mt-1 text-xs text-white/90"><?= htmlspecialchars($s['nom_classe']) ?></p>
                            <?php if (!empty($s['salle'])): ?><p class="mt-0.5 text-xs italic text-white/70">Salle <?= htmlspecialchars($s['salle']) ?></p><?php endif; ?>
                          </div>
                        <?php endif; ?>
                      </td>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- Barre de synthèse -->
          <div class="flex flex-wrap items-center justify-between gap-x-6 gap-y-1 border-t border-[#ececef] bg-[#fdf2f3] px-5 py-3.5 text-sm sm:px-6">
            <span class="inline-flex items-center gap-1.5 font-semibold text-[#1a1b22]"><svg class="h-4 w-4 text-primary"><use href="#i-clock" /></svg> Total : <span class="text-primary"><?= $totalAffiche ?> heures / semaine</span></span>
            <span class="text-muted">Nombre de séances : <span class="font-semibold text-[#1a1b22]"><?= $nbSeances ?></span></span>
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