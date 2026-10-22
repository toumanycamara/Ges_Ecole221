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

$jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];

// Liste des classes (pour le sélecteur)
$classesListe = $pdo->query("SELECT id_classe, nom_classe FROM classe ORDER BY nom_classe")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // ----- Suppression d'un cours -----
  if (isset($_POST['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM seance WHERE id_seance = :id");
    $stmt->execute(['id' => $_POST['delete_id']]);
    header('Location: edtSecretaire.php?classe=' . urlencode($_POST['id_classe'] ?? '') . '&ok=suppression');
    exit;
  }

  // ----- Ajout ou Modification -----
  $jour      = $_POST['jour'] ?? '';
  $hDebut    = $_POST['heure_debut'] ?? '';
  $hFin      = $_POST['heure_fin'] ?? '';
  $idMatiere = $_POST['id_matiere'] ?? '';
  $idForm    = $_POST['id_formateur'] ?? '';
  $salle     = trim($_POST['salle'] ?? '');
  $idClasse  = $_POST['id_classe'] ?? '';
  $idSeance  = $_POST['id_seance'] ?? '';

  // Vérifications : jour valide, heures au format HH:MM, début avant fin, champs remplis
  $heuresOk = preg_match('/^\d{2}:\d{2}$/', $hDebut) && preg_match('/^\d{2}:\d{2}$/', $hFin) && $hDebut < $hFin;
  $ok = in_array($jour, $jours) && $heuresOk && $idMatiere !== '' && $idForm !== '' && $salle !== '' && $idClasse !== '';

  if ($ok) {
    try {
      if ($idSeance === '') {
        $stmt = $pdo->prepare("INSERT INTO seance (jour, heure_debut, heure_fin, salle, id_matiere, id_formateur, id_classe)
                               VALUES (:jour, :hd, :hf, :salle, :idm, :idf, :idc)");
        $stmt->execute(['jour' => $jour, 'hd' => $hDebut, 'hf' => $hFin, 'salle' => $salle,
                        'idm' => $idMatiere, 'idf' => $idForm, 'idc' => $idClasse]);
        header('Location: edtSecretaire.php?classe=' . urlencode($idClasse) . '&ok=ajout');
        exit;
      } else {
        $stmt = $pdo->prepare("UPDATE seance SET jour = :jour, heure_debut = :hd, heure_fin = :hf, salle = :salle,
                               id_matiere = :idm, id_formateur = :idf WHERE id_seance = :id");
        $stmt->execute(['jour' => $jour, 'hd' => $hDebut, 'hf' => $hFin, 'salle' => $salle,
                        'idm' => $idMatiere, 'idf' => $idForm, 'id' => $idSeance]);
        header('Location: edtSecretaire.php?classe=' . urlencode($idClasse) . '&ok=modification');
        exit;
      }
    } catch (PDOException $e) {
      header('Location: edtSecretaire.php?classe=' . urlencode($idClasse) . '&err=1');
      exit;
    }
  } else {
    header('Location: edtSecretaire.php?classe=' . urlencode($idClasse) . '&err=champs');
    exit;
  }
}

// Classe affichée (celle de l'URL, sinon la première)
$classeId = $_GET['classe'] ?? ($classesListe[0]['id_classe'] ?? '');

// Séances de la classe affichée
$edt = []; // $edt[jour][label] = séance
if ($classeId !== '') {
  $stmt = $pdo->prepare("
    SELECT s.id_seance, s.jour, s.heure_debut, s.heure_fin, s.salle, s.id_matiere, s.id_formateur,
           m.nom_matiere, fo.nom AS fnom, fo.prenom AS fprenom
    FROM seance s
    JOIN matiere   m  ON m.id_matiere    = s.id_matiere
    JOIN formateur fo ON fo.id_formateur = s.id_formateur
    WHERE s.id_classe = :id
    ORDER BY s.heure_debut
  ");
  $stmt->execute(['id' => $classeId]);
  foreach ($stmt->fetchAll() as $s) {
    $label = substr($s['heure_debut'], 0, 5) . ' - ' . substr($s['heure_fin'], 0, 5);
    $edt[$s['jour']][$label] = $s;
  }
}

// Liste des créneaux existants pour cette classe (construite à partir des séances)
$creneauxList = []; // label => [debut HH:MM, fin HH:MM]
foreach ($edt as $slots) {
  foreach ($slots as $label => $s) {
    if (!isset($creneauxList[$label])) {
      $creneauxList[$label] = [substr($s['heure_debut'], 0, 5), substr($s['heure_fin'], 0, 5)];
    }
  }
}
uasort($creneauxList, fn($a, $b) => strcmp($a[0], $b[0]));

// Matières et formateurs pour le formulaire
$matieresListe   = $pdo->query("SELECT id_matiere, nom_matiere FROM matiere ORDER BY nom_matiere")->fetchAll();
$formateursListe = $pdo->query("SELECT id_formateur, nom, prenom FROM formateur ORDER BY nom, prenom")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Emploi du temps Secrétaire</title>
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
          colors: { primary: '#e11d2a', ink: '#141414', muted: '#5f5e5e', page: '#f4f2fd', primarydark: '#8f1119' },
        },
      },
    };
  </script>
</head>
<body class="font-sans bg-page text-ink">

  <!-- Icônes -->
  <svg class="hidden" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
      <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></symbol>
      <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
      <symbol id="i-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
      <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
      <symbol id="i-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></symbol>
    </defs>
  </svg>

  <!-- ============ SIDEBAR ============ -->
  <?php $active = 'edt'; include 'sidebarSecretaire.php'; ?>

  <div class="app-content min-h-screen lg:ml-[260px]">

    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" aria-controls="sidebar" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden">
          <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h2 class="text-sm font-semibold">Emploi du temps - Secrétaire</h2>
      </div>
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
          <img src="https://i.pravatar.cc/64?img=47" alt="Photo" class="h-8 w-8 rounded-full object-cover" />
          <div class="hidden sm:block"><p class="text-sm font-semibold leading-[17.5px]"><?= htmlspecialchars($nomAffiche) ?></p><p class="text-xs leading-3 text-muted"><?= htmlspecialchars($emailAffiche) ?></p></div>
        </div>
        <span class="hidden h-6 w-px bg-[#e3e1ec] sm:block"></span>
        <span class="whitespace-nowrap rounded-full bg-primary px-2 py-1 text-xs font-semibold text-white shadow-sm">Secrétaire / Admin</span>
      </div>
    </header>

    <main class="px-4 py-8 sm:px-6 lg:px-12">
      <div class="mx-auto flex max-w-[1180px] flex-col gap-6">

        <!-- En-tête -->
        <div class="flex flex-wrap items-end justify-between gap-4">
          <div>
            <h1 class="text-xl font-bold text-[#1a1b22]">Attribution des emplois du temps</h1>
            <p class="mt-1 text-xs text-muted">Affectez les cours (matière, formateur, salle) à chaque classe. Vous saisissez librement l'heure de début et de fin.</p>
          </div>
          <div class="flex flex-wrap items-end gap-3">
            <div class="flex flex-col gap-1">
              <label class="text-[11px] font-semibold uppercase tracking-wide text-muted">Classe</label>
              <div class="relative">
                <select id="selClasse" class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm font-medium text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 sm:w-56">
                  <?php foreach ($classesListe as $cl): ?>
                    <option value="<?= $cl['id_classe'] ?>" <?= $classeId == $cl['id_classe'] ? 'selected' : '' ?>><?= htmlspecialchars($cl['nom_classe']) ?></option>
                  <?php endforeach; ?>
                </select>
                <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
              </div>
            </div>
            <button id="btnAjouter" type="button" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primarydark">
              <svg class="h-4 w-4"><use href="#i-plus" /></svg> Affecter un cours
            </button>
          </div>
        </div>

        <!-- Grille emploi du temps -->
        <section class="overflow-hidden rounded-2xl bg-white shadow-sm">
          <?php if (count($creneauxList) === 0): ?>
            <div class="px-6 py-14 text-center">
              <p class="text-sm text-muted">Aucun cours pour cette classe pour l'instant.</p>
              <p class="mt-1 text-xs text-muted">Cliquez sur « Affecter un cours » pour créer le premier créneau.</p>
            </div>
          <?php else: ?>
          <div class="overflow-x-auto">
            <table class="w-full min-w-[920px] table-fixed border-collapse text-left">
              <thead>
                <tr class="text-[11px] uppercase tracking-wide text-muted">
                  <th class="w-[110px] border-b border-r border-[#ececef] px-3 py-3 font-semibold">Heure</th>
                  <?php foreach ($jours as $j): ?>
                    <th class="border-b border-r border-[#ececef] px-3 py-3 text-center font-semibold"><?= $j ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody class="align-top">
                <?php foreach ($creneauxList as $cr => $heures): ?>
                <tr>
                  <td class="border-b border-r border-[#ececef] px-3 py-3 text-xs font-medium text-muted"><?= $cr ?></td>
                  <?php foreach ($jours as $idx => $j): ?>
                    <?php
                      $c = $edt[$j][$cr] ?? null;
                      $borderR = $idx < count($jours) - 1 ? 'border-r' : '';
                    ?>
                    <td class="h-[104px] border-b <?= $borderR ?> border-[#ececef] p-2">
                      <?php if ($c): ?>
                        <div class="group relative h-full rounded-xl bg-primary p-3 text-white shadow-sm">
                          <p class="pr-12 text-sm font-bold leading-tight"><?= htmlspecialchars($c['nom_matiere']) ?></p>
                          <p class="mt-1 text-xs text-white/90"><?= htmlspecialchars($c['fprenom'] . ' ' . $c['fnom']) ?></p>
                          <p class="mt-0.5 text-xs italic text-white/70"><?= htmlspecialchars($c['salle']) ?></p>
                          <div class="absolute right-1.5 top-1.5 flex gap-1 opacity-0 transition group-hover:opacity-100">
                            <button type="button" class="js-edit flex h-6 w-6 items-center justify-center rounded-md bg-white/20 hover:bg-white/30"
                                    data-id="<?= $c['id_seance'] ?>" data-jour="<?= $j ?>" data-debut="<?= substr($c['heure_debut'],0,5) ?>" data-fin="<?= substr($c['heure_fin'],0,5) ?>"
                                    data-matiere="<?= $c['id_matiere'] ?>" data-formateur="<?= $c['id_formateur'] ?>" data-salle="<?= htmlspecialchars($c['salle']) ?>"
                                    title="Modifier"><svg class="h-3.5 w-3.5"><use href="#i-edit"/></svg></button>
                            <button type="button" class="js-del flex h-6 w-6 items-center justify-center rounded-md bg-white/20 hover:bg-white/30"
                                    data-id="<?= $c['id_seance'] ?>" title="Retirer"><svg class="h-3.5 w-3.5"><use href="#i-trash"/></svg></button>
                          </div>
                        </div>
                      <?php else: ?>
                        <button type="button" class="js-add flex h-full w-full items-center justify-center gap-1.5 rounded-xl border border-dashed border-[#e5e7eb] text-xs font-medium text-muted transition hover:border-primary hover:bg-[#fdeaec] hover:text-primary"
                                data-jour="<?= $j ?>" data-debut="<?= $heures[0] ?>" data-fin="<?= $heures[1] ?>">
                          <svg class="h-4 w-4"><use href="#i-plus"/></svg> Ajouter
                        </button>
                      <?php endif; ?>
                    </td>
                  <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <?php endif; ?>
        </section>

      </div>
    </main>
  </div>

  <!-- ============ DRAWER : Affecter un cours ============ -->
  <div id="drawerAdd" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40"></div>
    <aside class="absolute right-0 top-0 flex h-full w-full max-w-md translate-x-full flex-col bg-white shadow-xl transition-transform duration-300 sm:w-[440px]">
      <div class="flex items-center justify-between border-b border-[#ececef] px-6 py-4">
        <div class="flex items-center gap-3">
          <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#fdeaec] text-primary"><svg class="h-5 w-5"><use href="#i-calendar" /></svg></span>
          <h3 id="drawerTitre" class="text-base font-bold text-[#1a1b22]">Affecter un cours</h3>
        </div>
        <button type="button" data-close class="rounded-lg p-1.5 text-muted transition hover:bg-page hover:text-ink"><svg class="h-5 w-5"><use href="#i-x" /></svg></button>
      </div>

      <form id="formCours" method="post" class="flex-1 overflow-y-auto px-6 py-5">
        <input type="hidden" name="id_seance" id="fId" value="" />
        <input type="hidden" name="id_classe" id="fClasse" value="<?= htmlspecialchars($classeId) ?>" />

        <label class="block"><span class="text-xs font-medium text-muted">Jour</span>
          <div class="relative mt-1">
            <select name="jour" id="fJour" class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
              <?php foreach ($jours as $j): ?><option><?= $j ?></option><?php endforeach; ?>
            </select>
            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
          </div>
        </label>

        <div class="mt-4 grid grid-cols-2 gap-4">
          <label class="block"><span class="text-xs font-medium text-muted">Heure de début</span>
            <input type="time" name="heure_debut" id="fDebut" value="08:00" class="mt-1 w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
          </label>
          <label class="block"><span class="text-xs font-medium text-muted">Heure de fin</span>
            <input type="time" name="heure_fin" id="fFin" value="10:00" class="mt-1 w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
          </label>
        </div>

        <label class="mt-4 block"><span class="text-xs font-medium text-muted">Matière</span>
          <div class="relative mt-1">
            <select name="id_matiere" id="fMatiere" class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
              <option value="">Choisir une matière…</option>
              <?php foreach ($matieresListe as $ml): ?><option value="<?= $ml['id_matiere'] ?>"><?= htmlspecialchars($ml['nom_matiere']) ?></option><?php endforeach; ?>
            </select>
            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
          </div>
        </label>

        <label class="mt-4 block"><span class="text-xs font-medium text-muted">Formateur</span>
          <div class="relative mt-1">
            <select name="id_formateur" id="fFormateur" class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
              <option value="">Choisir un formateur…</option>
              <?php foreach ($formateursListe as $fl): ?><option value="<?= $fl['id_formateur'] ?>"><?= htmlspecialchars($fl['prenom'] . ' ' . $fl['nom']) ?></option><?php endforeach; ?>
            </select>
            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
          </div>
        </label>

        <label class="mt-4 block"><span class="text-xs font-medium text-muted">Salle</span>
          <input name="salle" id="fSalle" type="text" placeholder="Ex. Salle B12 / Lab 03" class="mt-1 w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
        </label>
      </form>

      <div class="flex items-center justify-end gap-2 border-t border-[#ececef] px-6 py-4">
        <button type="button" data-close class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="button" id="btnEnregistrer" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-check" /></svg> Enregistrer</button>
      </div>
    </aside>
  </div>

  <!-- ============ MODAL : confirmation suppression ============ -->
  <div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
      <div class="flex items-start gap-3">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#fdeaec] text-primary"><svg class="h-5 w-5"><use href="#i-trash" /></svg></span>
        <div>
          <h3 class="font-bold text-[#1a1b22]">Retirer ce cours ?</h3>
          <p class="mt-1 text-sm text-muted">Le créneau redeviendra libre pour cette classe.</p>
        </div>
      </div>
      <div class="mt-5 flex justify-end gap-2">
        <button type="button" data-cancel-delete class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="button" id="btnConfirmDelete" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primarydark">Retirer</button>
      </div>
    </div>
  </div>

  <!-- Formulaire caché : suppression -->
  <form id="deleteForm" method="post" class="hidden">
    <input type="hidden" name="delete_id" id="delete_id" value="" />
    <input type="hidden" name="id_classe" value="<?= htmlspecialchars($classeId) ?>" />
  </form>

  <!-- Toasts -->
  <div id="toasts" class="fixed right-4 top-4 z-[60] flex w-[320px] max-w-[calc(100vw-2rem)] flex-col gap-2"></div>

  <script>
    /* ----- Menu mobile ----- */
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleMenu = (open) => { sidebar.classList.toggle('-translate-x-full', !open); overlay.classList.toggle('hidden', !open); document.body.classList.toggle('overflow-hidden', open); };
    document.getElementById('openMenu').addEventListener('click', () => toggleMenu(true));
    document.getElementById('closeMenu').addEventListener('click', () => toggleMenu(false));
    overlay.addEventListener('click', () => toggleMenu(false));

    /* ----- Changement de classe : on recharge la page ----- */
    const selClasse = document.getElementById('selClasse');
    selClasse.addEventListener('change', () => { window.location.href = 'edtSecretaire.php?classe=' + selClasse.value; });

    /* ----- Drawer ----- */
    const drawerAdd = document.getElementById('drawerAdd');
    const drawerPanel = drawerAdd.querySelector('aside');
    const form = document.getElementById('formCours');

    function ouvrirDrawer() {
      drawerAdd.classList.remove('hidden');
      requestAnimationFrame(() => drawerPanel.classList.remove('translate-x-full'));
      document.body.classList.add('overflow-hidden');
    }
    function fermerDrawer() {
      drawerPanel.classList.add('translate-x-full');
      document.body.classList.remove('overflow-hidden');
      setTimeout(() => drawerAdd.classList.add('hidden'), 300);
    }

    // Nouveau cours (bouton en haut) : heures par défaut 08:00 - 10:00
    document.getElementById('btnAjouter').addEventListener('click', () => {
      form.reset();
      document.getElementById('fId').value = '';
      document.getElementById('fDebut').value = '08:00';
      document.getElementById('fFin').value = '10:00';
      document.getElementById('drawerTitre').textContent = 'Affecter un cours';
      ouvrirDrawer();
    });

    // Boutons "Ajouter" dans les cellules vides : on reprend jour + heures de la ligne
    document.querySelectorAll('.js-add').forEach((b) => b.addEventListener('click', () => {
      form.reset();
      document.getElementById('fId').value = '';
      document.getElementById('fJour').value = b.dataset.jour;
      document.getElementById('fDebut').value = b.dataset.debut;
      document.getElementById('fFin').value = b.dataset.fin;
      document.getElementById('drawerTitre').textContent = 'Affecter un cours';
      ouvrirDrawer();
    }));

    // Boutons "Modifier"
    document.querySelectorAll('.js-edit').forEach((b) => b.addEventListener('click', () => {
      form.reset();
      document.getElementById('fId').value = b.dataset.id;
      document.getElementById('fJour').value = b.dataset.jour;
      document.getElementById('fDebut').value = b.dataset.debut;
      document.getElementById('fFin').value = b.dataset.fin;
      document.getElementById('fMatiere').value = b.dataset.matiere;
      document.getElementById('fFormateur').value = b.dataset.formateur;
      document.getElementById('fSalle').value = b.dataset.salle;
      document.getElementById('drawerTitre').textContent = 'Modifier le cours';
      ouvrirDrawer();
    }));

    drawerAdd.querySelectorAll('[data-close]').forEach((b) => b.addEventListener('click', fermerDrawer));
    drawerAdd.addEventListener('click', (e) => { if (!drawerPanel.contains(e.target)) fermerDrawer(); });

    // Enregistrer : on vérifie puis on envoie le formulaire
    document.getElementById('btnEnregistrer').addEventListener('click', () => {
      const debut = document.getElementById('fDebut').value;
      const fin = document.getElementById('fFin').value;
      const mat = document.getElementById('fMatiere').value;
      const forma = document.getElementById('fFormateur').value;
      const salle = document.getElementById('fSalle').value.trim();
      if (!debut || !fin) { showToast('error', 'Heures manquantes', 'Indiquez l\'heure de début et de fin.'); return; }
      if (debut >= fin) { showToast('error', 'Heures incorrectes', 'L\'heure de fin doit être après le début.'); return; }
      if (!mat || !forma || !salle) { showToast('error', 'Champs manquants', 'Choisissez la matière, le formateur et la salle.'); return; }
      form.submit();
    });

    /* ----- Suppression ----- */
    const modalDelete = document.getElementById('modalDelete');
    let idASupprimer = null;
    document.querySelectorAll('.js-del').forEach((b) => b.addEventListener('click', () => {
      idASupprimer = b.dataset.id;
      modalDelete.classList.remove('hidden'); modalDelete.classList.add('flex');
      document.body.classList.add('overflow-hidden');
    }));
    function fermerModal() { modalDelete.classList.add('hidden'); modalDelete.classList.remove('flex'); document.body.classList.remove('overflow-hidden'); }
    modalDelete.querySelectorAll('[data-cancel-delete]').forEach((b) => b.addEventListener('click', fermerModal));
    modalDelete.addEventListener('click', (e) => { if (e.target === modalDelete) fermerModal(); });
    document.getElementById('btnConfirmDelete').addEventListener('click', () => {
      if (!idASupprimer) { fermerModal(); return; }
      document.getElementById('delete_id').value = idASupprimer;
      document.getElementById('deleteForm').submit();
    });

    /* ----- Toasts ----- */
    function showToast(type, title, msg) {
      const map = {
        success: { border: '#16a34a', icon: '#i-check', tint: '#dcfce7', text: '#15803d' },
        error:   { border: '#e11d2a', icon: '#i-alert', tint: '#ffdad7', text: '#b8001a' },
        info:    { border: '#6b7280', icon: '#i-alert', tint: '#f3f4f6', text: '#374151' },
      };
      const c = map[type] || map.info;
      const el = document.createElement('div');
      el.className = 'flex items-start gap-3 rounded-xl border-l-4 bg-white p-3.5 shadow-md';
      el.style.borderLeftColor = c.border;
      el.innerHTML =
        '<span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full" style="background:' + c.tint + ';color:' + c.text + '"><svg class="h-4 w-4"><use href="' + c.icon + '"/></svg></span>' +
        '<div class="flex-1"><p class="text-sm font-semibold text-[#1a1b22]">' + title + '</p>' + (msg ? '<p class="text-xs text-muted">' + msg + '</p>' : '') + '</div>' +
        '<button class="text-muted hover:text-ink" aria-label="Fermer"><svg class="h-4 w-4"><use href="#i-x"/></svg></button>';
      el.querySelector('button').addEventListener('click', () => el.remove());
      document.getElementById('toasts').appendChild(el);
      setTimeout(() => el.remove(), 3500);
    }

    /* ----- Toasts après une action serveur ----- */
    <?php if (isset($_GET['ok']) && $_GET['ok'] === 'ajout'): ?>
    showToast('success', 'Cours affecté', 'Le cours a bien été ajouté à l\'emploi du temps.');
    <?php elseif (isset($_GET['ok']) && $_GET['ok'] === 'modification'): ?>
    showToast('success', 'Cours modifié', 'Le créneau a bien été mis à jour.');
    <?php elseif (isset($_GET['ok']) && $_GET['ok'] === 'suppression'): ?>
    showToast('success', 'Cours retiré', 'Le créneau est de nouveau libre.');
    <?php elseif (isset($_GET['err']) && $_GET['err'] === 'champs'): ?>
    showToast('error', 'Saisie incomplète', 'Vérifiez les heures, la matière, le formateur et la salle.');
    <?php elseif (isset($_GET['err'])): ?>
    showToast('error', 'Erreur', 'Impossible d\'enregistrer ce cours.');
    <?php endif; ?>

    <?php if (isset($_GET['ok']) || isset($_GET['err'])): ?>
    history.replaceState({}, '', 'edtSecretaire.php?classe=<?= htmlspecialchars($classeId) ?>');
    <?php endif; ?>
  </script>
</body>
</html>