<?php
session_start();
if (!isset ($_SESSION['role'])||$_SESSION['role']!=='secretaire') {
  header('Location: loginEcole221.php');
  exit;
}

require 'config.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Niveaux</title>
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
      <symbol id="i-layers" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 2 10 5-10 5L2 7l10-5Z"/><path d="m2 17 10 5 10-5M2 12l10 5 10-5"/></symbol>
      <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
      <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></symbol>
      <symbol id="i-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
      <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
    </defs>
  </svg>

  <!-- ============ SIDEBAR ============ -->
  <?php $active = 'niveaux'; include 'sidebarSecretaire.php'; ?>

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
            <p class="text-sm font-semibold leading-[17.5px]">Moussa Diop</p>
            <p class="text-xs leading-3 text-muted">m.diop@ecole221.sn</p>
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
            <h1 class="text-2xl font-bold leading-8 tracking-[-0.6px] text-[#1a1b22]">Liste des niveaux</h1>
            <p id="countLabel" class="mt-1 text-xs text-muted">5 niveaux d'études homologués</p>
          </div>
          <button type="button" id="btnAdd" class="flex items-center justify-center gap-[7px] rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark">
            <svg class="h-3 w-3"><use href="#i-plus" /></svg> Ajouter un Niveau
          </button>
        </div>

        <!-- Grille de cartes -->
        <section id="grid" class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">

          <article class="group relative flex aspect-square flex-col items-center justify-center rounded-2xl bg-white shadow-sm transition hover:shadow-md">
            <button type="button" class="js-delete absolute right-2 top-2 rounded-lg p-1.5 text-[#9ca3af] opacity-0 transition hover:bg-primarydark hover:text-white group-hover:opacity-100" aria-label="Supprimer le niveau"><svg class="h-4 w-4"><use href="#i-trash" /></svg></button>
            <span class="js-code text-4xl font-bold text-[#1a1b22]">L1</span>
            <span class="mt-2 text-sm text-muted">Licence 1</span>
          </article>

          <article class="group relative flex aspect-square flex-col items-center justify-center rounded-2xl bg-white shadow-sm transition hover:shadow-md">
            <button type="button" class="js-delete absolute right-2 top-2 rounded-lg p-1.5 text-[#9ca3af] opacity-0 transition hover:bg-primarydark hover:text-white group-hover:opacity-100" aria-label="Supprimer le niveau"><svg class="h-4 w-4"><use href="#i-trash" /></svg></button>
            <span class="js-code text-4xl font-bold text-[#1a1b22]">L2</span>
            <span class="mt-2 text-sm text-muted">Licence 2</span>
          </article>

          <article class="group relative flex aspect-square flex-col items-center justify-center rounded-2xl bg-white shadow-sm transition hover:shadow-md">
            <button type="button" class="js-delete absolute right-2 top-2 rounded-lg p-1.5 text-[#9ca3af] opacity-0 transition hover:bg-primarydark hover:text-white group-hover:opacity-100" aria-label="Supprimer le niveau"><svg class="h-4 w-4"><use href="#i-trash" /></svg></button>
            <span class="js-code text-4xl font-bold text-[#1a1b22]">L3</span>
            <span class="mt-2 text-sm text-muted">Licence 3</span>
          </article>

          <article class="group relative flex aspect-square flex-col items-center justify-center rounded-2xl bg-white shadow-sm transition hover:shadow-md">
            <button type="button" class="js-delete absolute right-2 top-2 rounded-lg p-1.5 text-[#9ca3af] opacity-0 transition hover:bg-primarydark hover:text-white group-hover:opacity-100" aria-label="Supprimer le niveau"><svg class="h-4 w-4"><use href="#i-trash" /></svg></button>
            <span class="js-code text-4xl font-bold text-[#1a1b22]">M1</span>
            <span class="mt-2 text-sm text-muted">Master 1</span>
          </article>

          <article class="group relative flex aspect-square flex-col items-center justify-center rounded-2xl bg-white shadow-sm transition hover:shadow-md">
            <button type="button" class="js-delete absolute right-2 top-2 rounded-lg p-1.5 text-[#9ca3af] opacity-0 transition hover:bg-primarydark hover:text-white group-hover:opacity-100" aria-label="Supprimer le niveau"><svg class="h-4 w-4"><use href="#i-trash" /></svg></button>
            <span class="js-code text-4xl font-bold text-[#1a1b22]">M2</span>
            <span class="mt-2 text-sm text-muted">Master 2</span>
          </article>

        </section>
      </div>
    </main>
  </div>

  <!-- ============ DRAWER : NOUVEAU NIVEAU ============ -->
  <div id="drawerAdd" class="fixed inset-0 z-50 hidden">
    <div data-close class="absolute inset-0 bg-black/50"></div>
    <aside class="absolute inset-y-0 right-0 flex w-full max-w-md translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out">
      <div class="flex items-start justify-between gap-3 border-b border-[#eef0f2] px-6 py-4">
        <div class="flex items-center gap-3">
          <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#ffdad7] text-primary"><svg class="h-5 w-5"><use href="#i-layers" /></svg></span>
          <div>
            <h3 class="text-lg font-bold text-[#1a1b22]">Nouveau niveau</h3>
            <p class="text-xs text-muted">Renseignez les détails pour créer un nouveau niveau d'études.</p>
          </div>
        </div>
        <button type="button" data-close class="rounded-lg bg-page p-1.5 text-muted hover:bg-[#e8e7ec]" aria-label="Fermer"><svg class="h-5 w-5"><use href="#i-x" /></svg></button>
      </div>

      <form id="niveauForm" class="flex flex-1 flex-col gap-4 overflow-y-auto px-6 py-5">
        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="n-code">Code du niveau</label>
          <input id="n-code" type="text" placeholder="ex: L1, M2" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
        </div>
        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="n-libelle">Libellé</label>
          <input id="n-libelle" type="text" placeholder="ex: Licence 1" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
        </div>
      </form>

      <div class="grid grid-cols-2 gap-3 border-t border-[#eef0f2] px-6 py-4">
        <button type="button" data-close class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="submit" form="niveauForm" class="flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-check" /></svg> Enregistrer</button>
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
      <p class="mt-2 text-sm text-muted">Voulez-vous vraiment supprimer le niveau <span id="delName" class="font-semibold text-[#1a1b22]"></span> ? Cette action est irréversible.</p>
      <div class="mt-6 flex items-center justify-center gap-3">
        <button type="button" data-cancel-delete class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="button" id="confirmDelete" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark">Supprimer</button>
      </div>
    </div>
  </div>

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

    /* ----- Modales / drawer ----- */
    const modalDelete = document.getElementById('modalDelete');
    const openModal = (m) => { m.classList.remove('hidden'); m.classList.add('flex'); };
    const closeModal = (m) => { m.classList.add('hidden'); m.classList.remove('flex'); };

    const drawerAdd = document.getElementById('drawerAdd');
    const drawerPanel = drawerAdd.querySelector('aside');
    const openDrawer = () => {
      document.getElementById('niveauForm').reset();
      clearErrors();
      drawerAdd.classList.remove('hidden');
      requestAnimationFrame(() => drawerPanel.classList.remove('translate-x-full'));
      document.body.classList.add('overflow-hidden');
    };
    const closeDrawer = () => {
      drawerPanel.classList.add('translate-x-full');
      document.body.classList.remove('overflow-hidden');
      setTimeout(() => drawerAdd.classList.add('hidden'), 300);
    };

    /* ----- Compteur ----- */
    const grid = document.getElementById('grid');
    const countLabel = document.getElementById('countLabel');
    const updateCount = () => {
      const n = grid.querySelectorAll('article').length;
      countLabel.textContent = n + (n > 1 ? " niveaux d'études homologués" : " niveau d'études homologué");
    };

    /* ----- Ajout ----- */
    document.getElementById('btnAdd').addEventListener('click', openDrawer);
    drawerAdd.querySelectorAll('[data-close]').forEach((b) => b.addEventListener('click', closeDrawer));
    drawerAdd.addEventListener('click', (e) => { if (!drawerPanel.contains(e.target)) closeDrawer(); });

    const clearErrors = () => ['n-code', 'n-libelle'].forEach((id) => document.getElementById(id).classList.remove('border-primary', 'ring-2', 'ring-primary/30'));

    document.getElementById('niveauForm').addEventListener('submit', (e) => {
      e.preventDefault();
      clearErrors();
      const code = document.getElementById('n-code');
      const lib = document.getElementById('n-libelle');
      let ok = true;
      [code, lib].forEach((f) => { if (!f.value.trim()) { f.classList.add('border-primary', 'ring-2', 'ring-primary/30'); ok = false; } });
      if (!ok) { showToast('error', 'Champs obligatoires', 'Veuillez remplir le code et le libellé.'); return; }
      closeDrawer();
      showToast('success', 'Niveau ajouté', 'Le niveau a bien été enregistré.');
    });

    /* ----- Suppression ----- */
    let cardToDelete = null;
    document.querySelectorAll('.js-delete').forEach((btn) => {
      btn.addEventListener('click', () => {
        cardToDelete = btn.closest('article');
        document.getElementById('delName').textContent = '« ' + cardToDelete.querySelector('.js-code').textContent.trim() + ' »';
        openModal(modalDelete);
      });
    });
    document.querySelectorAll('[data-cancel-delete]').forEach((b) =>
      b.addEventListener('click', () => { closeModal(modalDelete); showToast('info', 'Suppression annulée'); })
    );
    modalDelete.addEventListener('click', (e) => { if (e.target === modalDelete) closeModal(modalDelete); });
    document.getElementById('confirmDelete').addEventListener('click', () => {
      if (!cardToDelete) { closeModal(modalDelete); return; }
      const card = cardToDelete;
      const parent = card.parentNode;
      const next = card.nextElementSibling;
      card.remove();
      cardToDelete = null;
      updateCount();
      closeModal(modalDelete);
      showToast('success', 'Niveau supprimé', 'Le niveau a bien été retiré de la liste.', {
        label: 'Annuler',
        onClick: () => {
          if (next) parent.insertBefore(card, next); else parent.appendChild(card);
          updateCount();
          showToast('info', 'Suppression annulée', 'Le niveau a été restauré.');
        }
      });
    });

    /* ----- Échap ferme tout ----- */
    window.addEventListener('keydown', (e) => {
      if (e.key !== 'Escape') return;
      closeDrawer(); closeModal(modalDelete); toggleMenu(false);
    });
  </script>
</body>
</html>