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
  <title>École 221 – Formateurs</title>
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
      <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M2 21v-1a6 6 0 0 1 12 0v1M16 4.5a3.5 3.5 0 0 1 0 7M22 21v-1a6 6 0 0 0-4-5.6"/></symbol>
      <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></symbol>
      <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
      <symbol id="i-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
      <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
      <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></symbol>
      <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
      <symbol id="i-user-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="4"/><path d="M3 21v-1a6 6 0 0 1 12 0v1"/><path d="M17 9v6M20 12h-6"/></symbol>
    </defs>
  </svg>

  <!-- ============ SIDEBAR ============ -->
  <?php $active = 'formateurs'; include 'sidebarSecretaire.php'; ?>

  <div class="app-content min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <h2 class="text-sm font-semibold">Tableau de bord - Secrétaire</h2>
      </div>
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
          <img src="https://i.pravatar.cc/64?img=47" alt="Photo" class="h-8 w-8 rounded-full object-cover" />
          <div class="hidden sm:block"><p class="text-sm font-semibold leading-[17.5px]">Moussa Diop</p><p class="text-xs leading-3 text-muted">m.diop@ecole221.sn</p></div>
        </div>
        <span class="hidden h-6 w-px bg-[#e3e1ec] sm:block"></span>
        <span class="whitespace-nowrap rounded-full bg-primary px-2 py-1 text-xs font-semibold text-white shadow-sm">Secrétaire / Admin</span>
      </div>
    </header>

    <main class="px-4 py-8 sm:px-6 lg:px-12">
      <div class="mx-auto flex max-w-[1180px] flex-col gap-6">

        <!-- Cartes stats -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
          <div class="flex items-center justify-between rounded-xl bg-white p-5 shadow-sm">
            <div><p class="text-xs text-muted">Total formateurs</p><p class="mt-1 text-3xl font-bold text-[#1a1b22]">15</p></div>
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-5 w-5"><use href="#i-users" /></svg></span>
          </div>
          <div class="flex items-center justify-between rounded-xl bg-white p-5 shadow-sm">
            <div><p class="text-xs text-muted">Actifs</p><p class="mt-1 text-3xl font-bold text-[#1a1b22]">13</p></div>
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#f0fdf4] text-[#15803d]"><svg class="h-5 w-5"><use href="#i-check" /></svg></span>
          </div>
          <div class="flex items-center justify-between rounded-xl bg-white p-5 shadow-sm">
            <div><p class="text-xs text-muted">En congé</p><p class="mt-1 text-3xl font-bold text-[#1a1b22]">2</p></div>
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#fffbeb] text-[#b45309]"><svg class="h-5 w-5"><use href="#i-clock" /></svg></span>
          </div>
        </div>

        <!-- Gestion des formateurs -->
        <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">
          <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
              <h1 class="text-xl font-bold text-[#1a1b22]">Gestion des formateurs</h1>
              <p class="mt-1 text-xs text-muted">Liste, coordonnées et affectations du corps enseignant</p>
            </div>
            <button type="button" id="btnAdd" class="flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark"><svg class="h-3.5 w-3.5"><use href="#i-plus" /></svg> Ajouter un formateur</button>
          </div>

          <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
            <div class="relative">
              <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-search" /></svg></span>
              <input type="text" placeholder="Rechercher un formateur…" class="w-64 rounded-lg border border-[#e5e7eb] py-2.5 pl-9 pr-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
            </div>
            <select class="rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
              <option>Spécialité : Toutes</option><option>Algorithmique &amp; IA</option><option>Base de Données</option><option>Réseaux &amp; Télécoms</option>
            </select>
          </div>

          <div class="mt-5 overflow-x-auto">
            <table class="w-full min-w-[920px] text-left text-sm">
              <thead class="border-b border-[#e5e7eb]">
                <tr class="text-xs font-semibold uppercase tracking-[0.5px] text-[#6b7280]">
                  <th class="px-4 py-3 font-semibold">Photo</th>
                  <th class="px-4 py-3 font-semibold">Nom complet</th>
                  <th class="px-4 py-3 font-semibold">Spécialité</th>
                  <th class="px-4 py-3 font-semibold">Email</th>
                  <th class="px-4 py-3 font-semibold">Téléphone</th>
                  <th class="px-4 py-3 font-semibold">Classes assignées</th>
                  <th class="px-4 py-3 font-semibold">Statut</th>
                  <th class="px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
              </thead>
              <tbody id="tbody" class="divide-y divide-[#f3f4f6]"></tbody>
            </table>
          </div>

          <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
            <p class="text-xs text-muted">Affichage de 1 à 6 sur 15 formateurs</p>
            <div class="flex items-center gap-1.5">
              <button class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-sm font-medium text-white">1</button>
              <button class="flex h-8 w-8 items-center justify-center rounded-lg border border-[#e5e7eb] text-sm font-medium text-[#374151] hover:bg-page">2</button>
              <button class="flex h-8 items-center justify-center rounded-lg border border-[#e5e7eb] px-2 text-sm text-[#374151] hover:bg-page">›</button>
            </div>
          </div>
        </section>
      </div>
    </main>
  </div>

  <!-- ============ DRAWER : AJOUTER / MODIFIER FORMATEUR ============ -->
  <div id="drawerAdd" class="fixed inset-0 z-50 hidden">
    <div data-close class="absolute inset-0 bg-black/50"></div>
    <aside class="absolute inset-y-0 right-0 flex w-full max-w-md translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out">
      <div class="flex items-center justify-between border-b border-[#eef0f2] px-6 py-4">
        <div class="flex items-center gap-3">
          <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-5 w-5"><use href="#i-user-plus" /></svg></span>
          <div><h3 id="drawerTitle" class="text-lg font-bold text-[#1a1b22]">Nouveau formateur</h3><p class="text-xs text-muted">Renseignez les informations du formateur</p></div>
        </div>
        <button type="button" data-close class="rounded-lg p-1.5 text-muted hover:bg-page" aria-label="Fermer"><svg class="h-5 w-5"><use href="#i-x" /></svg></button>
      </div>
      <form id="formateurForm" class="flex flex-1 flex-col gap-4 overflow-y-auto px-6 py-5">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-nom">Nom complet</label><input id="f-nom" type="text" placeholder="ex: Dr. Mamadou Kane" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
          <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-spec">Spécialité</label><input id="f-spec" type="text" placeholder="ex: Algorithmique & IA" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
        </div>
        <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-email">Email</label><input id="f-email" type="email" placeholder="ex: m.kane@ecole221.sn" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
        <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-tel">Téléphone</label><input id="f-tel" type="tel" placeholder="ex: +221 77 123 45 67" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
        <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-bureau">Bureau</label><input id="f-bureau" type="text" placeholder="ex: Bâtiment principal • Salle 304" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
        <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-statut">Statut</label>
          <select id="f-statut" class="w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"><option>Actif</option><option>En congé</option></select></div>
      </form>
      <div class="grid grid-cols-2 gap-3 border-t border-[#eef0f2] px-6 py-4">
        <button type="button" data-close class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="submit" form="formateurForm" class="flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-check" /></svg> Enregistrer</button>
      </div>
    </aside>
  </div>

  <!-- ============ MODALE : CONFIRMER SUPPRESSION ============ -->
  <div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-xl">
      <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-7 w-7"><use href="#i-trash" /></svg></div>
      <h3 class="text-lg font-bold text-[#1a1b22]">Confirmer la suppression</h3>
      <p class="mt-2 text-sm text-muted">Voulez-vous vraiment supprimer le formateur <span id="delName" class="font-semibold text-[#1a1b22]"></span> ? Cette action est irréversible.</p>
      <div class="mt-6 flex items-center justify-center gap-3">
        <button type="button" data-cancel-delete class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="button" id="confirmDelete" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark">Supprimer</button>
      </div>
    </div>
  </div>

  <div id="toasts" class="fixed right-4 top-4 z-[60] flex w-[320px] max-w-[calc(100vw-2rem)] flex-col gap-2"></div>

  <script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleMenu = (open) => { sidebar.classList.toggle('-translate-x-full', !open); overlay.classList.toggle('hidden', !open); document.body.classList.toggle('overflow-hidden', open); };
    document.getElementById('openMenu').addEventListener('click', () => toggleMenu(true));
    document.getElementById('closeMenu').addEventListener('click', () => toggleMenu(false));
    overlay.addEventListener('click', () => toggleMenu(false));

    const formateurs = [
      { img: 12, nom: 'Dr. Mamadou Kane', spec: 'Algorithmique & IA', email: 'm.kane@ecole221.sn', tel: '+221 77 123 45 67', classes: 'L3 GL, M1 DS', statut: 'Actif' },
      { img: 32, nom: 'Pr. Fatou Sow', spec: 'Base de Données', email: 'f.sow@ecole221.sn', tel: '+221 76 234 56 78', classes: 'L2 Info, M2 Cyber', statut: 'Actif' },
      { img: 15, nom: 'M. Ibrahima Diallo', spec: 'Réseaux & Télécoms', email: 'i.diallo@ecole221.sn', tel: '+221 78 345 67 89', classes: 'L1 Réseaux, L2 RT', statut: 'Actif' },
      { img: 45, nom: 'Mme. Aminata Ndiaye', spec: 'Mathématiques', email: 'a.ndiaye@ecole221.sn', tel: '+221 77 456 78 90', classes: 'L1 TC, L2 Info', statut: 'Actif' },
      { img: 60, nom: 'Dr. Ousmane Fall', spec: 'Cybersécurité', email: 'o.fall@ecole221.sn', tel: '+221 76 567 89 01', classes: 'M1 Cyber, M2 Cyber', statut: 'En congé' },
      { img: 68, nom: 'M. Cheikh Mbaye', spec: 'Développement Web', email: 'c.mbaye@ecole221.sn', tel: '+221 78 678 90 12', classes: 'L1 DW, L2 DW, L3 DW', statut: 'Actif' },
    ];
    const badgeStatut = (s) => s === 'Actif'
      ? '<span class="rounded-full border border-[#bbf7d0] bg-[#f0fdf4] px-2 py-0.5 text-xs font-medium text-[#15803d]">Actif</span>'
      : '<span class="rounded-full border border-[#fde68a] bg-[#fffbeb] px-2 py-0.5 text-xs font-medium text-[#b45309]">En congé</span>';

    const tbody = document.getElementById('tbody');
    let editIndex = null;
    function renderTable() {
      tbody.innerHTML = '';
      formateurs.forEach((f, i) => {
        const tr = document.createElement('tr');
        tr.className = 'cursor-pointer transition-colors hover:bg-[#f9fafb]';
        tr.innerHTML =
          '<td class="px-4 py-3"><img src="https://i.pravatar.cc/48?img=' + f.img + '" class="h-9 w-9 rounded-full object-cover" alt=""></td>' +
          '<td class="px-4 py-3 font-bold text-[#1a1b22]">' + f.nom + '</td>' +
          '<td class="px-4 py-3 text-[#374151]">' + f.spec + '</td>' +
          '<td class="px-4 py-3 text-[#374151]">' + f.email + '</td>' +
          '<td class="px-4 py-3 text-[#374151]">' + f.tel + '</td>' +
          '<td class="px-4 py-3 text-[#374151]">' + f.classes + '</td>' +
          '<td class="px-4 py-3">' + badgeStatut(f.statut) + '</td>' +
          '<td class="px-4 py-3"><div class="flex items-center justify-end gap-1.5">' +
            '<button class="js-edit rounded-lg p-1.5 text-primary hover:bg-primary hover:text-white" aria-label="Modifier"><svg class="h-4 w-4"><use href="#i-edit"/></svg></button>' +
            '<button class="js-del rounded-lg p-1.5 text-[#6b7280] hover:bg-primary hover:text-white" aria-label="Supprimer"><svg class="h-4 w-4"><use href="#i-trash"/></svg></button>' +
          '</div></td>';
        tr.addEventListener('click', () => { window.location.href = 'detailsFormateurSecretaire.php'; });
        tr.querySelector('.js-edit').addEventListener('click', (ev) => { ev.stopPropagation(); openEditDrawer(i); });
        tr.querySelector('.js-del').addEventListener('click', (ev) => { ev.stopPropagation(); askDelete(i); });
        tbody.appendChild(tr);
      });
    }

    function showToast(type, title, msg, action) {
      const map = { success: { b:'#16a34a', i:'#i-check', t:'#dcfce7', c:'#15803d' }, error: { b:'#e11d2a', i:'#i-alert', t:'#ffdad7', c:'#b8001a' }, info: { b:'#6b7280', i:'#i-alert', t:'#f3f4f6', c:'#374151' } };
      const m = map[type] || map.info;
      const el = document.createElement('div');
      el.className = 'rounded-xl border-l-4 bg-white p-3.5 shadow-md';
      el.style.borderLeftColor = m.b;
      el.innerHTML = '<div class="flex items-start gap-3"><span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full" style="background:' + m.t + ';color:' + m.c + '"><svg class="h-4 w-4"><use href="' + m.i + '"/></svg></span><div class="flex-1"><p class="text-sm font-semibold text-[#1a1b22]">' + title + '</p>' + (msg ? '<p class="text-xs text-muted">' + msg + '</p>' : '') + '</div><button class="js-close text-muted hover:text-ink" aria-label="Fermer"><svg class="h-4 w-4"><use href="#i-x"/></svg></button></div><div class="mt-2 flex justify-end"><button class="js-action rounded-lg border border-[#e5e7eb] px-3 py-1 text-xs font-medium text-primary hover:bg-page">Annuler</button></div>';
      const remove = () => el.remove();
      el.querySelector('.js-close').addEventListener('click', remove);
      el.querySelector('.js-action').addEventListener('click', () => { if (action) action(); remove(); });
      document.getElementById('toasts').appendChild(el);
      setTimeout(remove, action ? 6000 : 4000);
    }

    const drawerAdd = document.getElementById('drawerAdd');
    const drawerPanel = drawerAdd.querySelector('aside');
    const drawerTitle = document.getElementById('drawerTitle');
    const form = document.getElementById('formateurForm');
    const openDrawer = () => { drawerAdd.classList.remove('hidden'); requestAnimationFrame(() => drawerPanel.classList.remove('translate-x-full')); document.body.classList.add('overflow-hidden'); };
    const closeDrawer = () => { drawerPanel.classList.add('translate-x-full'); document.body.classList.remove('overflow-hidden'); setTimeout(() => drawerAdd.classList.add('hidden'), 300); };
    function openEditDrawer(i) {
      editIndex = i;
      const f = formateurs[i];
      drawerTitle.textContent = 'Modifier le formateur';
      document.getElementById('f-nom').value = f.nom;
      document.getElementById('f-spec').value = f.spec;
      document.getElementById('f-email').value = f.email;
      document.getElementById('f-tel').value = f.tel;
      document.getElementById('f-bureau').value = '';
      document.getElementById('f-statut').value = f.statut;
      openDrawer();
    }
    document.getElementById('btnAdd').addEventListener('click', () => { editIndex = null; drawerTitle.textContent = 'Nouveau formateur'; form.reset(); openDrawer(); });
    drawerAdd.querySelectorAll('[data-close]').forEach((b) => b.addEventListener('click', closeDrawer));
    drawerAdd.addEventListener('click', (e) => { if (!drawerPanel.contains(e.target)) closeDrawer(); });
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const nom = document.getElementById('f-nom');
      if (!nom.value.trim()) { nom.classList.add('border-primary','ring-2','ring-primary/30'); showToast('error', 'Champ obligatoire', 'Le nom complet est requis.'); return; }
      nom.classList.remove('border-primary','ring-2','ring-primary/30');
      closeDrawer();
      showToast('success', editIndex !== null ? 'Formateur modifié' : 'Formateur ajouté', editIndex !== null ? 'Les informations ont bien été mises à jour.' : 'Le formateur a bien été enregistré.');
    });

    const modalDelete = document.getElementById('modalDelete');
    const openModal = () => { modalDelete.classList.remove('hidden'); modalDelete.classList.add('flex'); };
    const closeModal = () => { modalDelete.classList.add('hidden'); modalDelete.classList.remove('flex'); };
    let idxToDelete = null;
    function askDelete(i) { idxToDelete = i; document.getElementById('delName').textContent = formateurs[i].nom; openModal(); }
    document.querySelectorAll('[data-cancel-delete]').forEach((b) => b.addEventListener('click', () => { closeModal(); showToast('info', 'Suppression annulée'); }));
    modalDelete.addEventListener('click', (e) => { if (e.target === modalDelete) closeModal(); });
    document.getElementById('confirmDelete').addEventListener('click', () => {
      if (idxToDelete === null) { closeModal(); return; }
      const i = idxToDelete; const saved = formateurs[i];
      formateurs.splice(i, 1); idxToDelete = null; closeModal(); renderTable();
      showToast('success', 'Formateur supprimé', 'Le formateur a bien été retiré.', () => {
        formateurs.splice(i, 0, saved); renderTable();
        showToast('info', 'Suppression annulée', 'Le formateur a été restauré.');
      });
    });

    window.addEventListener('keydown', (e) => { if (e.key === 'Escape') { closeDrawer(); closeModal(); toggleMenu(false); } });
    renderTable();
  </script>
</body>
</html>