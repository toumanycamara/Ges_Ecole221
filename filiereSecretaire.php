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

$erreurCode = "";
$erreurNom  = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // ----- Suppression -----
  if (isset($_POST['delete_id'])) {
    $q = $pdo->prepare("SELECT code_filiere, nom_filiere, responsable, description FROM filiere WHERE id_filiere = :id");
    $q->execute(['id' => $_POST['delete_id']]);
    $old = $q->fetch();

    $stmt = $pdo->prepare("DELETE FROM filiere WHERE id_filiere = :id");
    $stmt->execute(['id' => $_POST['delete_id']]);

    $url = 'filiereSecretaire.php?ok=suppression';
    if ($old) {
      $url .= '&code=' . urlencode($old['code_filiere'])
            . '&nom=' . urlencode($old['nom_filiere'])
            . '&resp=' . urlencode($old['responsable'] ?? '')
            . '&desc=' . urlencode($old['description'] ?? '');
    }
    header('Location: ' . $url);
    exit;
  }

  // ----- Ajout ou Modification -----
  $code = strtoupper(trim($_POST['code'] ?? ''));
  $nom  = trim($_POST['nom'] ?? '');
  $resp = trim($_POST['responsable'] ?? '');
  $desc = trim($_POST['description'] ?? '');
  $id   = $_POST['id_filiere'] ?? '';

  // Validation : un message par champ obligatoire
  if (!preg_match('/^[A-Z]{2,5}$/', $code)) {
    $erreurCode = "Le code doit être 2 à 5 lettres majuscules (ex: GL, ITN).";
  }
  if (!preg_match('/^[A-Za-zÀ-ÿ0-9 &\'-]{2,60}$/u', $nom)) {
    $erreurNom = "Le nom doit faire 2 à 60 caractères.";
  }

  if ($erreurCode === "" && $erreurNom === "") {
    try {
      if ($id === '') {
        // Ajout
        $stmt = $pdo->prepare("INSERT INTO filiere (code_filiere, nom_filiere, responsable, description) VALUES (:code, :nom, :resp, :desc)");
        $stmt->execute(['code' => $code, 'nom' => $nom, 'resp' => $resp, 'desc' => $desc]);
        header('Location: filiereSecretaire.php?ok=ajout');
        exit;
      } else {
        // Modification
        $stmt = $pdo->prepare("UPDATE filiere SET code_filiere = :code, nom_filiere = :nom, responsable = :resp, description = :desc WHERE id_filiere = :id");
        $stmt->execute(['code' => $code, 'nom' => $nom, 'resp' => $resp, 'desc' => $desc, 'id' => $id]);
        header('Location: filiereSecretaire.php?ok=modification');
        exit;
      }
    } catch (PDOException $e) {
      $erreurCode = "Ce code de filière existe déjà.";
    }
  }
}

$filieres = $pdo->query("
  SELECT f.id_filiere, f.code_filiere, f.nom_filiere, f.responsable, f.description,
         COUNT(e.id_etudiant) AS nb_inscrits
  FROM filiere f
  LEFT JOIN classe   c ON c.id_filiere = f.id_filiere
  LEFT JOIN etudiant e ON e.id_classe  = c.id_classe
  GROUP BY f.id_filiere, f.code_filiere, f.nom_filiere, f.responsable, f.description
  ORDER BY f.id_filiere
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Filières</title>
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
          colors: {
            primary: '#e11d2a',
            ink: '#141414',
            muted: '#5f5e5e',
            page: '#f4f4f5',
            primarydark: '#8f1119',
          },
        },
      },
    };
  </script>
</head>
<body class="font-sans bg-page text-ink">

  <!-- Icônes (sprite SVG) -->
  <svg class="hidden" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <symbol id="i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/></symbol>
      <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></symbol>
      <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
      <symbol id="i-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
      <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
      <symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></symbol>
    </defs>
  </svg>

  <!-- ============ SIDEBAR ============ -->
  <?php $active = 'filieres'; include 'sidebarSecretaire.php'; ?>

  <!-- ============ CONTENU ============ -->
  <div class="app-content min-h-screen lg:ml-[260px]">

    <!-- Navbar -->
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" aria-controls="sidebar" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden">
          <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h2 class="text-sm font-semibold">Tableau de bord - Secrétaire</h2>
      </div>
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
          <label class="group relative h-8 w-8 shrink-0 cursor-pointer overflow-hidden rounded-full ring-2 ring-transparent transition hover:ring-primarydark focus-within:ring-primarydark" title="Changer la photo">
            <input id="avatarInput" type="file" accept="image/*" class="sr-only" />
            <img id="avatarPreview" src="https://i.pravatar.cc/64?img=47" alt="Photo de l'administrateur" class="h-full w-full object-cover" />
            <span class="absolute inset-0 flex items-center justify-center bg-primarydark/70 text-white opacity-0 transition group-hover:opacity-100"><svg class="h-3.5 w-3.5"><use href="#i-edit" /></svg></span>
          </label>
          <div class="hidden sm:block">
            <p class="text-sm font-semibold leading-[17.5px]"><?= htmlspecialchars($nomAffiche) ?></p>
            <p class="text-xs leading-3 text-muted"><?= htmlspecialchars($emailAffiche) ?></p>
          </div>
        </div>
        <span class="hidden h-6 w-px bg-[#e3e1ec] sm:block"></span>
        <span class="whitespace-nowrap rounded-full bg-primary px-2 py-1 text-xs font-semibold text-white shadow-sm">Secrétaire / Admin</span>
      </div>
    </header>

    <main class="px-4 py-8 sm:px-6 lg:px-12">
      <div class="mx-auto flex max-w-[1180px] flex-col gap-6">

        <!-- En-tête de page -->
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <div class="flex items-center gap-3">
              <h1 class="text-2xl font-bold leading-8 tracking-[-0.6px] text-[#1a1b22]">Liste des filières</h1>
              <span id="countBadge" class="rounded-full bg-[#ffdad7] px-2.5 py-0.5 text-xs font-semibold text-primary"><?= count($filieres) ?> filière<?= count($filieres) > 1 ? 's' : '' ?> active<?= count($filieres) > 1 ? 's' : '' ?></span>
            </div>
            <p class="mt-1 text-xs text-muted">Programmes académiques et départements d'enseignement supérieur accrédités</p>
          </div>
          <button type="button" id="btnAdd" class="flex items-center justify-center gap-[7px] rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark">
            <svg class="h-3 w-3"><use href="#i-plus" /></svg> Ajouter une filière
          </button>
        </div>

        <!-- Grille de cartes -->
        <section id="grid" class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

          <?php foreach ($filieres as $f): ?>
          <article class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition hover:shadow-md">
            <div class="h-1.5 bg-primary"></div>
            <div class="flex flex-1 flex-col gap-2.5 p-5">
              <div class="flex items-start justify-between gap-3">
                <h3 class="js-nom font-bold leading-snug text-[#1a1b22]"><?= htmlspecialchars($f['nom_filiere']) ?></h3>
                <span class="shrink-0 rounded-full bg-[#ffdad7] px-2.5 py-1 text-xs font-semibold text-primary"><?= htmlspecialchars($f['code_filiere']) ?></span>
              </div>
              <p class="text-sm text-muted"><span class="font-medium text-[#374151]">Responsable :</span> <?= htmlspecialchars($f['responsable'] ?? '') ?></p>
              <p class="text-sm leading-relaxed text-muted"><?= htmlspecialchars($f['description'] ?? '') ?></p>
              <div class="mt-auto flex items-center justify-between border-t border-[#f3f4f6] pt-3">
                <span class="flex items-center gap-2 text-xs font-medium text-muted"><svg class="h-4 w-4 text-primary"><use href="#i-user" /></svg> <?= $f['nb_inscrits'] ?> inscrit<?= $f['nb_inscrits'] > 1 ? 's' : '' ?></span>
                <div class="flex items-center gap-1">
                  <button type="button" class="js-edit rounded-lg p-1.5 text-[#9ca3af] transition hover:bg-primarydark hover:text-white" data-id="<?= $f['id_filiere'] ?>" data-code="<?= htmlspecialchars($f['code_filiere']) ?>" data-nom="<?= htmlspecialchars($f['nom_filiere']) ?>" data-resp="<?= htmlspecialchars($f['responsable'] ?? '') ?>" data-desc="<?= htmlspecialchars($f['description'] ?? '') ?>" aria-label="Modifier la filière"><svg class="h-4 w-4"><use href="#i-edit" /></svg></button>
                  <button type="button" class="js-delete rounded-lg p-1.5 text-[#9ca3af] transition hover:bg-primarydark hover:text-white" data-id="<?= $f['id_filiere'] ?>" aria-label="Supprimer la filière"><svg class="h-4 w-4"><use href="#i-trash" /></svg></button>
                </div>
              </div>
            </div>
          </article>
          <?php endforeach; ?>

        </section>
      </div>
    </main>
  </div>

  <!-- ============ DRAWER : AJOUT / MODIFICATION FILIÈRE ============ -->
  <div id="drawerAdd" class="fixed inset-0 z-50 hidden">
    <div data-close class="absolute inset-0 bg-black/50"></div>
    <aside class="absolute inset-y-0 right-0 flex w-full max-w-md translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out">
      <div class="flex items-start justify-between gap-3 border-b border-[#eef0f2] px-6 py-4">
        <div class="flex items-center gap-3">
          <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#ffdad7] text-primary"><svg class="h-5 w-5"><use href="#i-book" /></svg></span>
          <div>
            <h3 class="text-lg font-bold text-[#1a1b22]"><?= ($_POST['id_filiere'] ?? '') !== '' ? 'Modifier la filière' : 'Nouvelle filière' ?></h3>
            <p class="text-xs text-muted">Renseignez les détails de la filière académique.</p>
          </div>
        </div>
        <button type="button" data-close class="rounded-lg bg-page p-1.5 text-muted hover:bg-[#e8e7ec]" aria-label="Fermer"><svg class="h-5 w-5"><use href="#i-x" /></svg></button>
      </div>

      <form id="filiereForm" method="post" class="flex flex-1 flex-col gap-5 overflow-y-auto px-6 py-5">

        <input type="hidden" name="id_filiere" id="f-id" value="<?= htmlspecialchars($_POST['id_filiere'] ?? '') ?>" />

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div>
            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-code">Code de la filière</label>
            <input id="f-code" type="text" name="code" value="<?= htmlspecialchars($_POST['code'] ?? '') ?>" placeholder="ex: GL, ITN" class="w-full rounded-lg border px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= $erreurCode !== '' ? 'border-[#ffb3b3] focus:border-[#e11d2a]' : 'border-[#e5e7eb] focus:border-primary' ?>" />
            <?php if ($erreurCode !== ""): ?>
              <p class="mt-1.5 flex items-start gap-1.5 text-xs text-[#b8001a]"><svg class="mt-0.5 h-3.5 w-3.5 shrink-0"><use href="#i-alert" /></svg> <span><?= htmlspecialchars($erreurCode) ?></span></p>
            <?php endif; ?>
          </div>
          <div>
            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-nom">Nom complet</label>
            <input id="f-nom" type="text" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" placeholder="ex: Génie Logiciel" class="w-full rounded-lg border px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 <?= $erreurNom !== '' ? 'border-[#ffb3b3] focus:border-[#e11d2a]' : 'border-[#e5e7eb] focus:border-primary' ?>" />
            <?php if ($erreurNom !== ""): ?>
              <p class="mt-1.5 flex items-start gap-1.5 text-xs text-[#b8001a]"><svg class="mt-0.5 h-3.5 w-3.5 shrink-0"><use href="#i-alert" /></svg> <span><?= htmlspecialchars($erreurNom) ?></span></p>
            <?php endif; ?>
          </div>
        </div>

        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-resp">Responsable</label>
          <input id="f-resp" type="text" name="responsable" value="<?= htmlspecialchars($_POST['responsable'] ?? '') ?>" placeholder="ex: Dr. Abdoulaye Sylla" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
        </div>

        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-desc">Description</label>
          <textarea id="f-desc" name="description" rows="4" placeholder="Brève description des objectifs pédagogiques et débouchés…" class="w-full resize-none rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>
      </form>

      <div class="grid grid-cols-2 gap-3 border-t border-[#eef0f2] px-6 py-4">
        <button type="button" data-close class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="submit" form="filiereForm" class="flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-check" /></svg> Enregistrer</button>
      </div>
    </aside>
  </div>

  <!-- ============ MODALE : CONFIRMER SUPPRESSION ============ -->
  <div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-xl">
      <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#ffdad7] text-primary">
        <svg class="h-7 w-7"><use href="#i-trash" /></svg>
      </div>
      <h3 class="text-lg font-bold text-[#1a1b22]">Confirmer la suppression</h3>
      <p class="mt-2 text-sm text-muted">Voulez-vous vraiment supprimer la filière <span id="delName" class="font-semibold text-[#1a1b22]"></span> ? Cette action est irréversible.</p>
      <div class="mt-6 flex items-center justify-center gap-3">
        <button type="button" data-cancel-delete class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="button" id="confirmDelete" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark">Supprimer</button>
      </div>
    </div>
  </div>

  <!-- Formulaire caché : suppression -->
  <form id="deleteForm" method="post" class="hidden">
    <input type="hidden" name="delete_id" id="delete_id" value="" />
  </form>

  <!-- Formulaire caché : restauration (annuler la suppression) -->
  <form id="restoreForm" method="post" class="hidden">
    <input type="hidden" name="code" id="restore_code" value="" />
    <input type="hidden" name="nom" id="restore_nom" value="" />
    <input type="hidden" name="responsable" id="restore_resp" value="" />
    <input type="hidden" name="description" id="restore_desc" value="" />
  </form>

  <!-- ============ TOASTS ============ -->
  <div id="toasts" class="fixed right-4 top-4 z-[60] flex w-[320px] max-w-[calc(100vw-2rem)] flex-col gap-2"></div>

  <script>
    /* ----- Menu mobile ----- */
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleMenu = (open) => {
      sidebar.classList.toggle('-translate-x-full', !open);
      overlay.classList.toggle('hidden', !open);
      document.body.classList.toggle('overflow-hidden', open);
    };
    document.getElementById('openMenu').addEventListener('click', () => toggleMenu(true));
    document.getElementById('closeMenu').addEventListener('click', () => toggleMenu(false));
    overlay.addEventListener('click', () => toggleMenu(false));

    /* ----- Photo de profil ----- */
    document.getElementById('avatarInput').addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) document.getElementById('avatarPreview').src = URL.createObjectURL(file);
    });

    /* ----- Toasts ----- */
    function showToast(type, title, msg, action) {
      const map = {
        success: { border: '#16a34a', icon: '#i-check', tint: '#dcfce7', text: '#15803d' },
        error:   { border: '#e11d2a', icon: '#i-alert', tint: '#ffdad7', text: '#b8001a' },
        info:    { border: '#6b7280', icon: '#i-alert', tint: '#f3f4f6', text: '#374151' },
      };
      const c = map[type] || map.info;
      const el = document.createElement('div');
      el.className = 'rounded-xl border-l-4 bg-white p-3.5 shadow-md';
      el.style.borderLeftColor = c.border;
      el.innerHTML =
        '<div class="flex items-start gap-3">' +
          '<span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full" style="background:' + c.tint + ';color:' + c.text + '"><svg class="h-4 w-4"><use href="' + c.icon + '"/></svg></span>' +
          '<div class="flex-1"><p class="text-sm font-semibold text-[#1a1b22]">' + title + '</p>' + (msg ? '<p class="text-xs text-muted">' + msg + '</p>' : '') + '</div>' +
          '<button class="js-close text-muted hover:text-ink" aria-label="Fermer"><svg class="h-4 w-4"><use href="#i-x"/></svg></button>' +
        '</div>' +
        (action ? '<div class="mt-2 flex justify-end"><button class="js-action rounded-lg border border-[#e5e7eb] px-3 py-1 text-xs font-medium text-primary hover:bg-page">' + action.label + '</button></div>' : '');
      el.querySelector('.js-close').addEventListener('click', () => el.remove());
      if (action) el.querySelector('.js-action').addEventListener('click', () => { action.onClick(); el.remove(); });
      document.getElementById('toasts').appendChild(el);
      setTimeout(() => el.remove(), action ? 6000 : 3500);
    }

    /* ----- Modale / drawer ----- */
    const modalDelete = document.getElementById('modalDelete');
    const openModal = (m) => { m.classList.remove('hidden'); m.classList.add('flex'); };
    const closeModal = (m) => { m.classList.add('hidden'); m.classList.remove('flex'); };

    const drawerAdd = document.getElementById('drawerAdd');
    const drawerPanel = drawerAdd.querySelector('aside');
    const drawerTitle = drawerAdd.querySelector('h3');

    const openDrawer = () => {
      document.getElementById('filiereForm').reset();
      document.getElementById('f-id').value = '';
      drawerTitle.textContent = 'Nouvelle filière';
      drawerAdd.classList.remove('hidden');
      requestAnimationFrame(() => drawerPanel.classList.remove('translate-x-full'));
      document.body.classList.add('overflow-hidden');
    };
    const closeDrawer = () => {
      drawerPanel.classList.add('translate-x-full');
      document.body.classList.remove('overflow-hidden');
      setTimeout(() => drawerAdd.classList.add('hidden'), 300);
    };

    /* ----- Ajout ----- */
    document.getElementById('btnAdd').addEventListener('click', openDrawer);
    drawerAdd.querySelectorAll('[data-close]').forEach((b) => b.addEventListener('click', closeDrawer));
    drawerAdd.addEventListener('click', (e) => { if (!drawerPanel.contains(e.target)) closeDrawer(); });

    /* ----- Modification ----- */
    function openDrawerEdit(id, code, nom, resp, desc) {
      document.getElementById('filiereForm').reset();
      document.getElementById('f-id').value = id;
      document.getElementById('f-code').value = code;
      document.getElementById('f-nom').value = nom;
      document.getElementById('f-resp').value = resp;
      document.getElementById('f-desc').value = desc;
      drawerTitle.textContent = 'Modifier la filière';
      drawerAdd.classList.remove('hidden');
      requestAnimationFrame(() => drawerPanel.classList.remove('translate-x-full'));
      document.body.classList.add('overflow-hidden');
    }
    document.querySelectorAll('.js-edit').forEach((btn) => {
      btn.addEventListener('click', () => openDrawerEdit(btn.dataset.id, btn.dataset.code, btn.dataset.nom, btn.dataset.resp, btn.dataset.desc));
    });

    /* ----- Suppression ----- */
    let idToDelete = null;
    document.querySelectorAll('.js-delete').forEach((btn) => {
      btn.addEventListener('click', () => {
        idToDelete = btn.dataset.id;
        const nom = btn.closest('article').querySelector('.js-nom').textContent.trim();
        document.getElementById('delName').textContent = '« ' + nom + ' »';
        openModal(modalDelete);
      });
    });
    document.querySelectorAll('[data-cancel-delete]').forEach((b) =>
      b.addEventListener('click', () => closeModal(modalDelete))
    );
    modalDelete.addEventListener('click', (e) => { if (e.target === modalDelete) closeModal(modalDelete); });
    document.getElementById('confirmDelete').addEventListener('click', () => {
      if (!idToDelete) { closeModal(modalDelete); return; }
      document.getElementById('delete_id').value = idToDelete;
      document.getElementById('deleteForm').submit();
    });

    /* ----- Échap ferme tout ----- */
    window.addEventListener('keydown', (e) => {
      if (e.key !== 'Escape') return;
      closeDrawer(); closeModal(modalDelete); toggleMenu(false);
    });

    /* ----- Rouvrir le drawer s'il y a une erreur de validation ----- */
    <?php if ($erreurCode !== "" || $erreurNom !== ""): ?>
    drawerAdd.classList.remove('hidden');
    requestAnimationFrame(() => drawerPanel.classList.remove('translate-x-full'));
    document.body.classList.add('overflow-hidden');
    <?php endif; ?>

    /* ----- Toasts après une action (ajout / modification / suppression) ----- */
    <?php if (isset($_GET['ok']) && $_GET['ok'] === 'ajout'): ?>
    showToast('success', 'Filière ajoutée', 'La filière a bien été enregistrée.');
    <?php elseif (isset($_GET['ok']) && $_GET['ok'] === 'modification'): ?>
    showToast('success', 'Filière modifiée', 'Les modifications ont bien été enregistrées.');
    <?php elseif (isset($_GET['ok']) && $_GET['ok'] === 'suppression'): ?>
    showToast('success', 'Filière supprimée', 'La filière a bien été retirée de la liste.', {
      label: 'Annuler',
      onClick: () => {
        document.getElementById('restore_code').value = <?= json_encode($_GET['code'] ?? '') ?>;
        document.getElementById('restore_nom').value  = <?= json_encode($_GET['nom'] ?? '') ?>;
        document.getElementById('restore_resp').value = <?= json_encode($_GET['resp'] ?? '') ?>;
        document.getElementById('restore_desc').value = <?= json_encode($_GET['desc'] ?? '') ?>;
        document.getElementById('restoreForm').submit();
      }
    });
    <?php endif; ?>

    /* ----- Nettoyer l'URL pour que le toast ne revienne pas au rechargement ----- */
    <?php if (isset($_GET['ok'])): ?>
    history.replaceState({}, '', 'filiereSecretaire.php');
    <?php endif; ?>
  </script>
</body>
</html>