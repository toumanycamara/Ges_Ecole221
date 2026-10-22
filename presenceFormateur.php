<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'formateur') {
  header('Location: loginEcole221.php');
  exit;
}

require 'config.php';

$idFormateur = $_SESSION['id_formateur'] ?? 0;
$statutsDB   = ['Present', 'Retard', 'Absent'];
$statutLabel = ['Present' => 'Présent', 'Retard' => 'Retard', 'Absent' => 'Absent'];

// Infos du formateur (en-tête)
$stmt = $pdo->prepare("SELECT nom, prenom, email, photo FROM formateur WHERE id_formateur = :id");
$stmt->execute(['id' => $idFormateur]);
$moi = $stmt->fetch();
$nomAffiche   = $moi ? trim($moi['prenom'] . ' ' . $moi['nom']) : ($_SESSION['login'] ?? 'Formateur');
$emailAffiche = $moi && !empty($moi['email']) ? $moi['email'] : ($_SESSION['login'] ?? '');
$photo        = $moi && !empty($moi['photo']) ? $moi['photo'] : 'https://i.pravatar.cc/64?img=12';

// Séances du formateur (classe + matière + jour + horaire)
$stmt = $pdo->prepare("
  SELECT s.id_seance, s.jour, s.heure_debut, s.heure_fin, s.id_classe,
         c.nom_classe, m.nom_matiere
  FROM seance s
  JOIN classe  c ON c.id_classe  = s.id_classe
  JOIN matiere m ON m.id_matiere = s.id_matiere
  WHERE s.id_formateur = :f
  ORDER BY c.nom_classe, s.jour, s.heure_debut
");
$stmt->execute(['f' => $idFormateur]);
$mesSeances = $stmt->fetchAll();
$idsSeances = array_column($mesSeances, 'id_seance');

// Libellé lisible d'une séance
function libelleSeance($s) {
  $h = substr($s['heure_debut'], 0, 5) . 'h-' . substr($s['heure_fin'], 0, 5) . 'h';
  return $s['nom_classe'] . ' - ' . $s['nom_matiere'] . ' - ' . $s['jour'] . ' ' . $h;
}

// ----- Enregistrement de l'appel -----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
  $seance = $_POST['seance'] ?? '';
  $date   = $_POST['date'] ?? '';

  $ok = in_array($seance, $idsSeances) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date);
  if (!$ok) {
    header('Location: presenceFormateur.php?err=1');
    exit;
  }

  $statuts = $_POST['statut'] ?? [];
  $heures  = $_POST['heure'] ?? [];
  $obs     = $_POST['obs'] ?? [];

  $sel = $pdo->prepare("SELECT id_presence FROM presence WHERE id_etudiant = :e AND id_seance = :s AND date_presence = :d");
  $ins = $pdo->prepare("INSERT INTO presence (date_presence, statut, heure_arrivee, observation, id_etudiant, id_seance)
                        VALUES (:d, :st, :h, :o, :e, :s)");
  $upd = $pdo->prepare("UPDATE presence SET statut = :st, heure_arrivee = :h, observation = :o WHERE id_presence = :id");

  foreach ($statuts as $idEtu => $st) {
    if (!in_array($st, $statutsDB)) continue; // statut inattendu : on ignore

    $h = trim($heures[$idEtu] ?? '');
    if ($st === 'Absent') $h = '';          // pas d'heure d'arrivée si absent
    $h = ($h === '') ? null : $h;

    $o = trim($obs[$idEtu] ?? '');
    $o = ($o === '') ? null : mb_substr($o, 0, 150);

    $sel->execute(['e' => $idEtu, 's' => $seance, 'd' => $date]);
    $existe = $sel->fetchColumn();
    if ($existe) {
      $upd->execute(['st' => $st, 'h' => $h, 'o' => $o, 'id' => $existe]);
    } else {
      $ins->execute(['d' => $date, 'st' => $st, 'h' => $h, 'o' => $o, 'e' => $idEtu, 's' => $seance]);
    }
  }
  header('Location: presenceFormateur.php?seance=' . urlencode($seance) . '&date=' . urlencode($date) . '&ok=1');
  exit;
}

// ----- Sélection courante -----
$seanceSel = $_GET['seance'] ?? ($mesSeances[0]['id_seance'] ?? '');
if (!in_array($seanceSel, $idsSeances)) $seanceSel = $mesSeances[0]['id_seance'] ?? '';
$dateSel = $_GET['date'] ?? date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateSel)) $dateSel = date('Y-m-d');

// Classe de la séance sélectionnée
$classeSel = '';
foreach ($mesSeances as $s) { if ($s['id_seance'] == $seanceSel) $classeSel = $s['id_classe']; }

// Étudiants de la classe + présence déjà enregistrée pour (séance, date)
$etudiants = [];
if ($seanceSel !== '' && $classeSel !== '') {
  $stmt = $pdo->prepare("
    SELECT e.id_etudiant, e.matricule, e.nom, e.prenom,
           p.statut, p.heure_arrivee, p.observation
    FROM etudiant e
    LEFT JOIN presence p ON p.id_etudiant = e.id_etudiant AND p.id_seance = :s AND p.date_presence = :d
    WHERE e.id_classe = :c
    ORDER BY e.nom, e.prenom
  ");
  $stmt->execute(['s' => $seanceSel, 'd' => $dateSel, 'c' => $classeSel]);
  $etudiants = $stmt->fetchAll();
}

// Synthèse initiale
$nbP = $nbR = $nbA = 0;
foreach ($etudiants as $e) {
  $st = $e['statut'] ?? 'Present';
  if ($st === 'Present') $nbP++;
  elseif ($st === 'Retard') $nbR++;
  else $nbA++;
}
$total = count($etudiants);
$taux  = $total ? round(($nbP + $nbR) / $total * 100) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Gestion des présences (Formateur)</title>
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
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
      <symbol id="i-check-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></symbol>
      <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
    </defs>
  </svg>

  <!-- ===================== SIDEBAR ===================== -->
  <?php $active = 'presences'; include 'sidebarFormateur.php'; ?>

  <div class="app-content min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <h1 class="text-xl font-bold">Gestion des présences</h1>
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

        <?php if (count($mesSeances) === 0): ?>
          <section class="rounded-2xl bg-white p-10 text-center shadow-sm">
            <p class="text-sm text-muted">Aucune séance ne vous est assignée pour l'instant.</p>
            <p class="mt-1 text-xs text-muted">Une séance apparaît ici dès que le secrétaire vous en programme une dans l'emploi du temps.</p>
          </section>
        <?php else: ?>

        <!-- Panneau -->
        <section class="overflow-hidden rounded-2xl bg-white shadow-sm">

          <!-- Barre de sélection -->
          <div class="flex flex-col gap-4 border-b border-[#ececef] bg-[#faf9fb] px-5 py-4 sm:px-6 lg:flex-row lg:items-end lg:justify-between">
            <form method="get" class="flex flex-col gap-4 sm:flex-row sm:flex-wrap">
              <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold uppercase tracking-wide text-muted">Séance</label>
                <div class="relative">
                  <select name="seance" onchange="this.form.submit()" class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 sm:w-[22rem]">
                    <?php foreach ($mesSeances as $s): ?>
                      <option value="<?= $s['id_seance'] ?>" <?= $seanceSel == $s['id_seance'] ? 'selected' : '' ?>><?= htmlspecialchars(libelleSeance($s)) ?></option>
                    <?php endforeach; ?>
                  </select>
                  <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
                </div>
              </div>
              <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold uppercase tracking-wide text-muted">Date</label>
                <input type="date" name="date" value="<?= htmlspecialchars($dateSel) ?>" onchange="this.form.submit()" class="rounded-lg border border-[#e5e7eb] bg-white py-2.5 px-3 text-sm text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
              </div>
            </form>
            <button type="submit" form="appelForm" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primarydark">
              <svg class="h-4 w-4"><use href="#i-check-circle" /></svg> Valider l'appel
            </button>
          </div>

          <!-- Tableau d'appel -->
          <form id="appelForm" method="post">
            <input type="hidden" name="save" value="1" />
            <input type="hidden" name="seance" value="<?= htmlspecialchars($seanceSel) ?>" />
            <input type="hidden" name="date" value="<?= htmlspecialchars($dateSel) ?>" />

            <div class="overflow-x-auto px-2 sm:px-4">
              <table class="w-full min-w-[820px] text-left text-sm">
                <thead>
                  <tr class="border-b border-[#ececef] text-[11px] uppercase tracking-wide text-muted">
                    <th class="px-3 py-3 font-semibold">N°</th>
                    <th class="px-3 py-3 font-semibold">Matricule</th>
                    <th class="px-3 py-3 font-semibold">Nom complet</th>
                    <th class="px-3 py-3 font-semibold">Statut</th>
                    <th class="px-3 py-3 font-semibold">Heure d'arrivée</th>
                    <th class="px-3 py-3 font-semibold">Observation</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-[#f1f1f3]">
                  <?php if (count($etudiants) === 0): ?>
                    <tr><td colspan="6" class="px-3 py-10 text-center text-sm text-muted">Aucun étudiant dans cette classe.</td></tr>
                  <?php else: ?>
                    <?php foreach ($etudiants as $i => $e):
                      $idE   = $e['id_etudiant'];
                      $st    = $e['statut'] ?? 'Present';          // Présent par défaut
                      $heure = $e['heure_arrivee'] ? substr($e['heure_arrivee'], 0, 5) : '';
                      $styles = [
                        'Present' => 'bg-[#dcfce7] text-[#15803d]',
                        'Retard'  => 'bg-[#fef9c3] text-[#a16207]',
                        'Absent'  => 'bg-[#fee2e2] text-[#b91c1c]',
                      ];
                    ?>
                    <tr class="hover:bg-[#faf9fb]">
                      <td class="px-3 py-3.5 text-muted"><?= $i + 1 ?></td>
                      <td class="px-3 py-3.5 font-mono text-xs text-[#374151]"><?= htmlspecialchars($e['matricule']) ?></td>
                      <td class="px-3 py-3.5 font-semibold text-[#1a1b22]"><?= htmlspecialchars($e['nom'] . ' ' . $e['prenom']) ?></td>
                      <td class="px-3 py-3.5">
                        <div class="relative inline-block">
                          <select name="statut[<?= $idE ?>]" data-i="<?= $i ?>" class="js-statut <?= $styles[$st] ?> cursor-pointer appearance-none rounded-full py-1 pl-3 pr-7 text-xs font-semibold outline-none focus:ring-2 focus:ring-primary/30">
                            <?php foreach ($statutsDB as $v): ?>
                              <option value="<?= $v ?>" <?= $st === $v ? 'selected' : '' ?>><?= $statutLabel[$v] ?></option>
                            <?php endforeach; ?>
                          </select>
                          <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2"><svg class="h-3.5 w-3.5 opacity-60"><use href="#i-chevron" /></svg></span>
                        </div>
                      </td>
                      <td class="px-3 py-3.5">
                        <input type="time" name="heure[<?= $idE ?>]" data-i="<?= $i ?>" value="<?= htmlspecialchars($heure) ?>" <?= $st === 'Absent' ? 'disabled' : '' ?>
                          class="js-heure w-28 rounded-lg border border-[#e5e7eb] bg-white px-2.5 py-1.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 disabled:bg-[#f3f4f6] disabled:text-muted" />
                      </td>
                      <td class="px-3 py-3.5">
                        <input type="text" name="obs[<?= $idE ?>]" maxlength="150" value="<?= htmlspecialchars($e['observation'] ?? '') ?>" placeholder="—"
                          class="w-48 rounded-lg border border-transparent bg-transparent px-2 py-1.5 text-sm text-[#374151] outline-none hover:border-[#e5e7eb] focus:border-primary focus:ring-2 focus:ring-primary/20" />
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </form>

          <!-- Barre de synthèse -->
          <div class="flex flex-wrap items-center gap-x-5 gap-y-2 border-t border-[#ececef] bg-[#fafafa] px-5 py-3.5 text-sm sm:px-6">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#dcfce7] px-3 py-1 text-xs font-semibold text-[#15803d]">Présents : <span id="nbP"><?= $nbP ?></span></span>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#fef9c3] px-3 py-1 text-xs font-semibold text-[#a16207]">Retards : <span id="nbR"><?= $nbR ?></span></span>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#fee2e2] px-3 py-1 text-xs font-semibold text-[#b91c1c]">Absents : <span id="nbA"><?= $nbA ?></span></span>
            <span class="font-semibold text-[#1a1b22]">Taux de présence : <span id="taux" class="text-primary"><?= $taux ?> %</span></span>
          </div>

        </section>
        <?php endif; ?>
      </div>
    </main>
  </div>

  <div id="toasts" class="fixed right-4 top-4 z-[60] flex w-[320px] max-w-[calc(100vw-2rem)] flex-col gap-2"></div>

  <script>
    /* ----- Menu mobile ----- */
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleMenu = (open) => { sidebar.classList.toggle('-translate-x-full', !open); overlay.classList.toggle('hidden', !open); document.body.classList.toggle('overflow-hidden', open); };
    document.getElementById('openMenu').addEventListener('click', () => toggleMenu(true));
    document.getElementById('closeMenu').addEventListener('click', () => toggleMenu(false));
    overlay.addEventListener('click', () => toggleMenu(false));

    /* ----- Statut : couleur + blocage de l'heure si absent ----- */
    const styleStatut = {
      'Present': 'bg-[#dcfce7] text-[#15803d]',
      'Retard':  'bg-[#fef9c3] text-[#a16207]',
      'Absent':  'bg-[#fee2e2] text-[#b91c1c]',
    };
    document.querySelectorAll('.js-statut').forEach((sel) => {
      sel.addEventListener('change', () => {
        const i = sel.dataset.i;
        sel.className = 'js-statut ' + styleStatut[sel.value] + ' cursor-pointer appearance-none rounded-full py-1 pl-3 pr-7 text-xs font-semibold outline-none focus:ring-2 focus:ring-primary/30';
        const h = document.querySelector('.js-heure[data-i="' + i + '"]');
        if (h) {
          if (sel.value === 'Absent') { h.value = ''; h.disabled = true; }
          else { h.disabled = false; }
        }
        synthese();
      });
    });

    /* ----- Synthèse en direct ----- */
    function synthese() {
      const statuts = [...document.querySelectorAll('.js-statut')].map((s) => s.value);
      const total = statuts.length;
      const nbP = statuts.filter((s) => s === 'Present').length;
      const nbR = statuts.filter((s) => s === 'Retard').length;
      const nbA = statuts.filter((s) => s === 'Absent').length;
      const p = document.getElementById('nbP'); if (!p) return;
      p.textContent = nbP;
      document.getElementById('nbR').textContent = nbR;
      document.getElementById('nbA').textContent = nbA;
      document.getElementById('taux').textContent = (total ? Math.round(((nbP + nbR) / total) * 100) : 0) + ' %';
    }

    /* ----- Toast ----- */
    function showToast(type, title, msg) {
      const map = { success: { b:'#16a34a', i:'#i-check', t:'#dcfce7', c:'#15803d' }, error: { b:'#e11d2a', i:'#i-alert', t:'#ffdad7', c:'#b8001a' } };
      const m = map[type] || map.success;
      const el = document.createElement('div');
      el.className = 'flex items-start gap-3 rounded-xl border-l-4 bg-white p-3.5 shadow-md';
      el.style.borderLeftColor = m.b;
      el.innerHTML = '<span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full" style="background:' + m.t + ';color:' + m.c + '"><svg class="h-4 w-4"><use href="' + m.i + '"/></svg></span><div class="flex-1"><p class="text-sm font-semibold text-[#1a1b22]">' + title + '</p>' + (msg ? '<p class="text-xs text-muted">' + msg + '</p>' : '') + '</div><button class="text-muted hover:text-ink"><svg class="h-4 w-4"><use href="#i-x"/></svg></button>';
      el.querySelector('button').addEventListener('click', () => el.remove());
      document.getElementById('toasts').appendChild(el);
      setTimeout(() => el.remove(), 3500);
    }
    <?php if (isset($_GET['ok'])): ?>
    showToast('success', 'Appel validé', 'Les présences ont bien été enregistrées.');
    history.replaceState({}, '', 'presenceFormateur.php?seance=<?= htmlspecialchars($seanceSel) ?>&date=<?= htmlspecialchars($dateSel) ?>');
    <?php elseif (isset($_GET['err'])): ?>
    showToast('error', 'Erreur', 'Séance ou date invalide, appel non enregistré.');
    <?php endif; ?>
  </script>
</body>
</html>