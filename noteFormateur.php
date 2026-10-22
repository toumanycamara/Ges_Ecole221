<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'formateur') {
  header('Location: loginEcole221.php');
  exit;
}

require 'config.php';

$idFormateur = $_SESSION['id_formateur'] ?? 0;
$typesValides = ['CC', 'Examen', 'TP'];

// Infos du formateur (en-tête)
$stmt = $pdo->prepare("SELECT nom, prenom, email, photo FROM formateur WHERE id_formateur = :id");
$stmt->execute(['id' => $idFormateur]);
$moi = $stmt->fetch();
$nomAffiche   = $moi ? trim($moi['prenom'] . ' ' . $moi['nom']) : ($_SESSION['login'] ?? 'Formateur');
$emailAffiche = $moi && !empty($moi['email']) ? $moi['email'] : ($_SESSION['login'] ?? '');
$photo        = $moi && !empty($moi['photo']) ? $moi['photo'] : 'https://i.pravatar.cc/64?img=12';

// Classes du formateur
$mesClasses = $pdo->prepare("SELECT DISTINCT c.id_classe, c.nom_classe FROM seance s JOIN classe c ON c.id_classe = s.id_classe WHERE s.id_formateur = :f ORDER BY c.nom_classe");
$mesClasses->execute(['f' => $idFormateur]);
$mesClasses = $mesClasses->fetchAll();

// Matières du formateur
$mesMatieres = $pdo->prepare("SELECT m.id_matiere, m.nom_matiere FROM enseigner en JOIN matiere m ON m.id_matiere = en.id_matiere WHERE en.id_formateur = :f ORDER BY m.nom_matiere");
$mesMatieres->execute(['f' => $idFormateur]);
$mesMatieres = $mesMatieres->fetchAll();

// Petites fonctions de sécurité : la classe/matière appartient-elle au formateur ?
$idsClasses  = array_column($mesClasses, 'id_classe');
$idsMatieres = array_column($mesMatieres, 'id_matiere');

// Regex de validation d'une note : un nombre de 0 à 20 (virgule ou point, 2 décimales max)
$regexNote = '/^(?:20(?:[.,]0{1,2})?|1?\d(?:[.,]\d{1,2})?)$/';

$erreurs    = [];    // id_etudiant => message d'erreur
$valeurs    = [];    // id_etudiant => valeur saisie (réaffichée en cas d'erreur)
$postError  = false; // true si le formulaire est renvoyé avec des erreurs

// ----- Enregistrement des notes -----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
  $classe  = $_POST['classe'] ?? '';
  $matiere = $_POST['matiere'] ?? '';
  $type    = $_POST['type'] ?? '';

  $ok = in_array($classe, $idsClasses) && in_array($matiere, $idsMatieres) && in_array($type, $typesValides);
  if (!$ok) {
    header('Location: noteFormateur.php?err=1');
    exit;
  }

  $notes = $_POST['note'] ?? [];

  // 1) On vérifie toutes les notes avant d'enregistrer quoi que ce soit
  foreach ($notes as $idEtu => $val) {
    $val = trim($val);
    $valeurs[$idEtu] = $val;
    if ($val === '') continue; // champ vide : autorisé, on ne crée rien
    if (!preg_match($regexNote, $val)) {
      $erreurs[$idEtu] = 'Note invalide. Saisissez un nombre entre 0 et 20.';
    }
  }

  // 2) Si tout est bon, on enregistre puis on redirige
  if (empty($erreurs)) {
    $sel = $pdo->prepare("SELECT id_note FROM note WHERE id_etudiant = :e AND id_matiere = :m AND type_eval = :t");
    $ins = $pdo->prepare("INSERT INTO note (type_eval, valeur, date_eval, publie, id_etudiant, id_matiere, id_formateur)
                          VALUES (:t, :v, CURDATE(), 1, :e, :m, :f)");
    $upd = $pdo->prepare("UPDATE note SET valeur = :v, date_eval = CURDATE(), publie = 1, id_formateur = :f WHERE id_note = :id");

    foreach ($notes as $idEtu => $val) {
      $val = trim($val);
      if ($val === '') continue;
      $val = (float)str_replace(',', '.', $val);

      $sel->execute(['e' => $idEtu, 'm' => $matiere, 't' => $type]);
      $existe = $sel->fetchColumn();
      if ($existe) {
        $upd->execute(['v' => $val, 'f' => $idFormateur, 'id' => $existe]);
      } else {
        $ins->execute(['t' => $type, 'v' => $val, 'e' => $idEtu, 'm' => $matiere, 'f' => $idFormateur]);
      }
    }
    header('Location: noteFormateur.php?classe=' . urlencode($classe) . '&matiere=' . urlencode($matiere) . '&type=' . urlencode($type) . '&ok=1');
    exit;
  }

  // 3) Sinon on réaffiche la page avec les erreurs et les valeurs saisies
  $postError  = true;
  $classeSel  = $classe;
  $matiereSel = $matiere;
  $typeSel    = $type;
}

// ----- Sélection courante -----
if (!$postError) {
  $classeSel  = $_GET['classe']  ?? ($mesClasses[0]['id_classe']  ?? '');
  $matiereSel = $_GET['matiere'] ?? ($mesMatieres[0]['id_matiere'] ?? '');
  $typeSel    = $_GET['type']    ?? 'Examen';
}
if (!in_array($typeSel, $typesValides)) $typeSel = 'Examen';
// Sécurité : si la sélection n'est pas valide, on repasse sur la première valeur
if (!in_array($classeSel, $idsClasses))   $classeSel = $mesClasses[0]['id_classe'] ?? '';
if (!in_array($matiereSel, $idsMatieres)) $matiereSel = $mesMatieres[0]['id_matiere'] ?? '';

// Étudiants de la classe + note existante pour (matière, type)
$etudiants = [];
if ($classeSel !== '' && $matiereSel !== '') {
  $stmt = $pdo->prepare("
    SELECT e.id_etudiant, e.matricule, e.nom, e.prenom, n.valeur
    FROM etudiant e
    LEFT JOIN note n ON n.id_etudiant = e.id_etudiant AND n.id_matiere = :m AND n.type_eval = :t
    WHERE e.id_classe = :c
    ORDER BY e.nom, e.prenom
  ");
  $stmt->execute(['m' => $matiereSel, 't' => $typeSel, 'c' => $classeSel]);
  $etudiants = $stmt->fetchAll();
}

// Synthèse initiale
$notesNum = [];
foreach ($etudiants as $e) { if ($e['valeur'] !== null) $notesNum[] = (float)$e['valeur']; }
$moy = count($notesNum) ? round(array_sum($notesNum) / count($notesNum), 2) : null;
$hi  = count($notesNum) ? max($notesNum) : null;
$lo  = count($notesNum) ? min($notesNum) : null;

function appreciation($n) {
  if ($n === null || $n === '') return '';
  $n = (float)$n;
  if ($n >= 17) return 'Très bien';
  if ($n >= 14) return 'Bien';
  if ($n >= 12) return 'Assez bien';
  if ($n >= 10) return 'Passable';
  return 'Insuffisant';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Saisie des notes (Formateur)</title>
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
      <symbol id="i-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></symbol>
      <symbol id="i-save" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/><path d="M17 21v-8H7v8M7 3v5h8"/></symbol>
      <symbol id="i-trend" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 17 6-6 4 4 8-8"/><path d="M17 7h4v4"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
      <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
    </defs>
  </svg>

  <!-- ===================== SIDEBAR ===================== -->
  <?php $active = 'notes'; include 'sidebarFormateur.php'; ?>

  <div class="app-content min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <h1 class="text-xl font-bold">Saisie des notes</h1>
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

        <?php if (count($mesClasses) === 0 || count($mesMatieres) === 0): ?>
          <section class="rounded-2xl bg-white p-10 text-center shadow-sm">
            <p class="text-sm text-muted">Vous devez avoir au moins une classe (via l'emploi du temps) et une matière assignée pour saisir des notes.</p>
          </section>
        <?php else: ?>

        <!-- Panneau -->
        <section class="overflow-hidden rounded-2xl bg-white shadow-sm">

          <!-- Barre de sélection -->
          <div class="flex flex-col gap-4 border-b border-[#ececef] bg-[#faf9fb] px-5 py-4 sm:px-6 lg:flex-row lg:items-end lg:justify-between">
            <form method="get" class="flex flex-col gap-4 sm:flex-row sm:flex-wrap">
              <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold uppercase tracking-wide text-muted">Classe</label>
                <div class="relative">
                  <select name="classe" onchange="this.form.submit()" class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 sm:w-52">
                    <?php foreach ($mesClasses as $c): ?>
                      <option value="<?= $c['id_classe'] ?>" <?= $classeSel == $c['id_classe'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nom_classe']) ?></option>
                    <?php endforeach; ?>
                  </select>
                  <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
                </div>
              </div>
              <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold uppercase tracking-wide text-muted">Matière</label>
                <div class="relative">
                  <select name="matiere" onchange="this.form.submit()" class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 sm:w-56">
                    <?php foreach ($mesMatieres as $m): ?>
                      <option value="<?= $m['id_matiere'] ?>" <?= $matiereSel == $m['id_matiere'] ? 'selected' : '' ?>><?= htmlspecialchars($m['nom_matiere']) ?></option>
                    <?php endforeach; ?>
                  </select>
                  <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
                </div>
              </div>
              <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold uppercase tracking-wide text-muted">Type</label>
                <div class="relative">
                  <select name="type" onchange="this.form.submit()" class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 sm:w-40">
                    <option value="CC"     <?= $typeSel === 'CC' ? 'selected' : '' ?>>CC</option>
                    <option value="Examen" <?= $typeSel === 'Examen' ? 'selected' : '' ?>>Examen</option>
                    <option value="TP"     <?= $typeSel === 'TP' ? 'selected' : '' ?>>TP</option>
                  </select>
                  <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
                </div>
              </div>
            </form>
            <button type="submit" form="noteForm" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primarydark">
              <svg class="h-4 w-4"><use href="#i-save" /></svg> Enregistrer les notes
            </button>
          </div>

          <!-- Tableau de saisie -->
          <form id="noteForm" method="post">
            <input type="hidden" name="save" value="1" />
            <input type="hidden" name="classe" value="<?= htmlspecialchars($classeSel) ?>" />
            <input type="hidden" name="matiere" value="<?= htmlspecialchars($matiereSel) ?>" />
            <input type="hidden" name="type" value="<?= htmlspecialchars($typeSel) ?>" />

            <div class="overflow-x-auto px-2 sm:px-4">
              <table class="w-full min-w-[720px] text-left text-sm">
                <thead>
                  <tr class="border-b border-[#ececef] text-[11px] uppercase tracking-wide text-muted">
                    <th class="px-3 py-3 font-semibold">N°</th>
                    <th class="px-3 py-3 font-semibold">Matricule</th>
                    <th class="px-3 py-3 font-semibold">Nom</th>
                    <th class="px-3 py-3 font-semibold">Prénom</th>
                    <th class="px-3 py-3 font-semibold">Note / 20</th>
                    <th class="px-3 py-3 font-semibold">Appréciation</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-[#f1f1f3]">
                  <?php if (count($etudiants) === 0): ?>
                    <tr><td colspan="6" class="px-3 py-10 text-center text-sm text-muted">Aucun étudiant dans cette classe.</td></tr>
                  <?php else: ?>
                    <?php foreach ($etudiants as $i => $e):
                      $idE = $e['id_etudiant'];
                      // Valeur à afficher : celle saisie si erreur, sinon celle de la base
                      if ($postError) {
                        $valAff = $valeurs[$idE] ?? '';
                      } else {
                        $valAff = $e['valeur'] !== null ? rtrim(rtrim($e['valeur'], '0'), '.') : '';
                      }
                      $err = $erreurs[$idE] ?? '';
                    ?>
                    <tr class="hover:bg-[#faf9fb]">
                      <td class="px-3 py-3 text-muted align-top pt-4"><?= $i + 1 ?></td>
                      <td class="px-3 py-3 font-mono text-xs text-[#374151] align-top pt-4"><?= htmlspecialchars($e['matricule']) ?></td>
                      <td class="px-3 py-3 font-semibold text-[#1a1b22] align-top pt-4"><?= htmlspecialchars($e['nom']) ?></td>
                      <td class="px-3 py-3 text-[#374151] align-top pt-4"><?= htmlspecialchars($e['prenom']) ?></td>
                      <td class="px-3 py-3 align-top">
                        <input type="text" inputmode="decimal" name="note[<?= $idE ?>]" value="<?= htmlspecialchars($valAff) ?>" data-i="<?= $i ?>"
                          class="js-note w-20 rounded-lg border bg-white px-2.5 py-1.5 text-center text-sm font-semibold outline-none focus:ring-2 focus:ring-primary/30 <?= $err ? 'border-primary' : 'border-[#e5e7eb] focus:border-primary' ?>" />
                        <p class="js-err mt-1 text-xs text-primary <?= $err ? '' : 'hidden' ?>"><?= htmlspecialchars($err) ?></p>
                      </td>
                      <td class="px-3 py-3 align-top pt-4">
                        <span class="js-appr text-sm text-[#374151]"><?= appreciation($postError ? ($err ? null : str_replace(',', '.', $valAff)) : $e['valeur']) ?></span>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </form>

          <!-- Barre de synthèse -->
          <div class="flex flex-wrap items-center gap-x-6 gap-y-1 border-t border-[#ececef] bg-[#fdf2f3] px-5 py-3.5 text-sm sm:px-6">
            <span class="inline-flex items-center gap-1.5 font-semibold text-[#1a1b22]"><svg class="h-4 w-4 text-primary"><use href="#i-trend" /></svg> Moyenne de la classe : <span id="moy" class="text-primary"><?= $moy !== null ? $moy : '—' ?></span> / 20</span>
            <span class="text-muted">Note la plus haute : <span id="hi" class="font-semibold text-[#1a1b22]"><?= $hi !== null ? $hi : '—' ?></span></span>
            <span class="text-muted">Note la plus basse : <span id="lo" class="font-semibold text-[#1a1b22]"><?= $lo !== null ? $lo : '—' ?></span></span>
          </div>

        </section>
        <?php endif; ?>
      </div>
    </main>
  </div>

  <div id="toasts" class="fixed right-4 top-4 z-[60] flex w-[320px] max-w-[calc(100vw-2rem)] flex-col gap-2"></div>

  <script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleMenu = (open) => { sidebar.classList.toggle('-translate-x-full', !open); overlay.classList.toggle('hidden', !open); document.body.classList.toggle('overflow-hidden', open); };
    document.getElementById('openMenu').addEventListener('click', () => toggleMenu(true));
    document.getElementById('closeMenu').addEventListener('click', () => toggleMenu(false));
    overlay.addEventListener('click', () => toggleMenu(false));

    /* ----- Appréciation + synthèse en direct ----- */
    // Même règle que côté PHP : un nombre de 0 à 20 (virgule ou point, 2 décimales max)
    const noteRegex = /^(?:20(?:[.,]0{1,2})?|1?\d(?:[.,]\d{1,2})?)$/;

    const apprPour = (n) => {
      if (n === '' || isNaN(n)) return '';
      if (n >= 17) return 'Très bien';
      if (n >= 14) return 'Bien';
      if (n >= 12) return 'Assez bien';
      if (n >= 10) return 'Passable';
      return 'Insuffisant';
    };

    const parseNote = (s) => {
      s = (s || '').trim();
      if (s === '' || !noteRegex.test(s)) return NaN;
      return parseFloat(s.replace(',', '.'));
    };

    // Vérifie une case : affiche ou cache le message d'erreur, met à jour l'appréciation
    function validerCase(inp) {
      const raw   = inp.value.trim();
      const errEl = inp.parentElement.querySelector('.js-err');
      const appr  = inp.closest('tr').querySelector('.js-appr');

      if (raw === '') { // case vide : autorisée
        inp.classList.remove('border-primary'); inp.classList.add('border-[#e5e7eb]');
        if (errEl) errEl.classList.add('hidden');
        if (appr) appr.textContent = '';
        return true;
      }
      if (!noteRegex.test(raw)) {
        inp.classList.add('border-primary'); inp.classList.remove('border-[#e5e7eb]');
        if (errEl) { errEl.textContent = 'Note invalide. Saisissez un nombre entre 0 et 20.'; errEl.classList.remove('hidden'); }
        if (appr) appr.textContent = '';
        return false;
      }
      inp.classList.remove('border-primary'); inp.classList.add('border-[#e5e7eb]');
      if (errEl) errEl.classList.add('hidden');
      if (appr) appr.textContent = apprPour(parseFloat(raw.replace(',', '.')));
      return true;
    }

    function synthese() {
      const notes = [...document.querySelectorAll('.js-note')].map((i) => parseNote(i.value)).filter((n) => !isNaN(n));
      const moyEl = document.getElementById('moy'), hiEl = document.getElementById('hi'), loEl = document.getElementById('lo');
      if (!moyEl) return;
      if (!notes.length) { moyEl.textContent = '—'; hiEl.textContent = '—'; loEl.textContent = '—'; return; }
      const moy = notes.reduce((a, b) => a + b, 0) / notes.length;
      moyEl.textContent = (Math.round(moy * 100) / 100);
      hiEl.textContent = Math.max(...notes);
      loEl.textContent = Math.min(...notes);
    }

    document.querySelectorAll('.js-note').forEach((inp) => {
      inp.addEventListener('input', () => { validerCase(inp); synthese(); });
    });

    // On bloque l'enregistrement si une note est invalide
    document.getElementById('noteForm')?.addEventListener('submit', (e) => {
      let okAll = true;
      document.querySelectorAll('.js-note').forEach((inp) => { if (!validerCase(inp)) okAll = false; });
      if (!okAll) {
        e.preventDefault();
        showToast('error', 'Vérifiez les notes', 'Certaines notes sont invalides. Elles doivent être comprises entre 0 et 20.');
      }
    });

    /* ----- Toast après enregistrement ----- */
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
    showToast('success', 'Notes enregistrées', 'Les notes ont bien été enregistrées et publiées.');
    history.replaceState({}, '', 'noteFormateur.php?classe=<?= htmlspecialchars($classeSel) ?>&matiere=<?= htmlspecialchars($matiereSel) ?>&type=<?= htmlspecialchars($typeSel) ?>');
    <?php elseif (isset($_GET['err'])): ?>
    showToast('error', 'Erreur', 'Sélection invalide, notes non enregistrées.');
    <?php endif; ?>
  </script>
</body>
</html>