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
  <title>École 221 – Emploi du temps Secrétaire</title>
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
            page: '#f4f2fd',
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
      <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
      <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></symbol>
      <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
      <symbol id="i-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6"/></symbol>
      <symbol id="i-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
      <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
      <symbol id="i-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></symbol>
    </defs>
  </svg>

  <!-- ============ SIDEBAR ============ -->
  <?php $active = 'edt'; include 'sidebarSecretaire.php'; ?>

  <!-- ============ CONTENU ============ -->
  <div class="app-content min-h-screen lg:ml-[260px]">

    <!-- Navbar -->
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" aria-controls="sidebar" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden">
          <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h2 class="text-sm font-semibold">Emploi du temps - Secrétaire</h2>
      </div>
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
          <img src="https://i.pravatar.cc/64?img=47" alt="Photo de l'administrateur" class="h-8 w-8 rounded-full object-cover" />
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

        <!-- En-tête -->
        <div class="flex flex-wrap items-end justify-between gap-4">
          <div>
            <h1 class="text-xl font-bold text-[#1a1b22]">Attribution des emplois du temps</h1>
            <p class="mt-1 text-xs text-muted">Affectez les cours (matière, formateur, salle) à chaque classe, par créneau.</p>
          </div>
          <div class="flex flex-wrap items-end gap-3">
            <div class="flex flex-col gap-1">
              <label class="text-[11px] font-semibold uppercase tracking-wide text-muted">Classe</label>
              <div class="relative">
                <select id="selClasse" class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm font-medium text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 sm:w-56">
                  <option>L3 Génie Logiciel</option>
                  <option>L1 Tronc Commun</option>
                  <option>M1 Data Science</option>
                  <option>M2 Cybersécurité</option>
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
          <div class="overflow-x-auto">
            <table class="w-full min-w-[920px] table-fixed border-collapse text-left">
              <thead>
                <tr class="text-[11px] uppercase tracking-wide text-muted">
                  <th class="w-[96px] border-b border-r border-[#ececef] px-3 py-3 font-semibold">Heure</th>
                  <th class="border-b border-r border-[#ececef] px-3 py-3 text-center font-semibold">Lundi</th>
                  <th class="border-b border-r border-[#ececef] px-3 py-3 text-center font-semibold">Mardi</th>
                  <th class="border-b border-r border-[#ececef] px-3 py-3 text-center font-semibold">Mercredi</th>
                  <th class="border-b border-r border-[#ececef] px-3 py-3 text-center font-semibold">Jeudi</th>
                  <th class="border-b border-[#ececef] px-3 py-3 text-center font-semibold">Vendredi</th>
                </tr>
              </thead>
              <tbody id="grille" class="align-top"></tbody>
            </table>
          </div>
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

      <form id="formCours" class="flex-1 overflow-y-auto px-6 py-5">
        <div class="grid grid-cols-2 gap-4">
          <label class="block"><span class="text-xs font-medium text-muted">Jour</span>
            <div class="relative mt-1">
              <select id="fJour" class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                <option>Lundi</option><option>Mardi</option><option>Mercredi</option><option>Jeudi</option><option>Vendredi</option>
              </select>
              <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
            </div>
          </label>
          <label class="block"><span class="text-xs font-medium text-muted">Créneau</span>
            <div class="relative mt-1">
              <select id="fCreneau" class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                <option>08:00 - 10:00</option><option>10:00 - 12:00</option><option>14:00 - 16:00</option><option>16:00 - 17:00</option>
              </select>
              <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
            </div>
          </label>
        </div>

        <label class="mt-4 block"><span class="text-xs font-medium text-muted">Matière</span>
          <div class="relative mt-1">
            <select id="fMatiere" class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
              <option value="">Choisir une matière…</option>
              <option>Architecture logicielle</option><option>Bases de données avancées</option><option>Développement mobile</option>
              <option>UX / UI Design</option><option>DevOps &amp; Cloud</option><option>Anglais technique</option>
            </select>
            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
          </div>
        </label>

        <label class="mt-4 block"><span class="text-xs font-medium text-muted">Formateur</span>
          <div class="relative mt-1">
            <select id="fFormateur" class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
              <option value="">Choisir un formateur…</option>
              <option>M. Ndiaye</option><option>Mme Sow</option><option>Mme Fall</option><option>Mme Diop</option><option>M. Ba</option><option>M. Wilson</option>
            </select>
            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
          </div>
        </label>

        <label class="mt-4 block"><span class="text-xs font-medium text-muted">Salle</span>
          <input id="fSalle" type="text" placeholder="Ex. Salle B12 / Lab 03" class="mt-1 w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
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

    /* ----- Données : emploi du temps par classe -----
       clé = "Jour|Créneau" -> { matiere, formateur, salle } */
    const CRENEAUX = ['08:00 - 10:00', '10:00 - 12:00', '14:00 - 16:00', '16:00 - 17:00'];
    const JOURS = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];

    const edt = {
      'L3 Génie Logiciel': {
        'Lundi|08:00 - 10:00':    { matiere: 'Architecture logicielle', formateur: 'M. Ndiaye', salle: 'Salle B12' },
        'Mardi|10:00 - 12:00':    { matiere: 'Bases de données avancées', formateur: 'Mme Sow', salle: 'Salle C09' },
        'Mercredi|14:00 - 16:00': { matiere: 'Développement mobile', formateur: 'Mme Fall', salle: 'Lab 03' },
      },
      'L1 Tronc Commun': {},
      'M1 Data Science': {},
      'M2 Cybersécurité': {},
    };

    const selClasse = document.getElementById('selClasse');
    const grille = document.getElementById('grille');

    function classeActive() { return selClasse.value; }

    function render() {
      const data = edt[classeActive()] || {};
      let html = '';
      CRENEAUX.forEach((cr) => {
        html += '<tr>';
        html += '<td class="border-b border-r border-[#ececef] px-3 py-3 text-xs font-medium text-muted">' + cr + '</td>';
        JOURS.forEach((j, idx) => {
          const key = j + '|' + cr;
          const c = data[key];
          const borderR = idx < JOURS.length - 1 ? 'border-r' : '';
          if (c) {
            html += '<td class="h-[104px] border-b ' + borderR + ' border-[#ececef] p-2">' +
              '<div class="group relative h-full rounded-xl bg-primary p-3 text-white shadow-sm">' +
                '<p class="pr-12 text-sm font-bold leading-tight">' + c.matiere + '</p>' +
                '<p class="mt-1 text-xs text-white/90">' + c.formateur + '</p>' +
                '<p class="mt-0.5 text-xs italic text-white/70">' + c.salle + '</p>' +
                '<div class="absolute right-1.5 top-1.5 flex gap-1 opacity-0 transition group-hover:opacity-100">' +
                  '<button type="button" data-edit="' + key + '" class="flex h-6 w-6 items-center justify-center rounded-md bg-white/20 hover:bg-white/30" title="Modifier"><svg class="h-3.5 w-3.5"><use href="#i-edit"/></svg></button>' +
                  '<button type="button" data-del="' + key + '" class="flex h-6 w-6 items-center justify-center rounded-md bg-white/20 hover:bg-white/30" title="Retirer"><svg class="h-3.5 w-3.5"><use href="#i-trash"/></svg></button>' +
                '</div>' +
              '</div></td>';
          } else {
            html += '<td class="h-[104px] border-b ' + borderR + ' border-[#ececef] p-2">' +
              '<button type="button" data-add="' + key + '" class="flex h-full w-full items-center justify-center gap-1.5 rounded-xl border border-dashed border-[#e5e7eb] text-xs font-medium text-muted transition hover:border-primary hover:bg-[#fdeaec] hover:text-primary">' +
                '<svg class="h-4 w-4"><use href="#i-plus"/></svg> Ajouter</button></td>';
          }
        });
        html += '</tr>';
        if (cr === '10:00 - 12:00') {
          html += '<tr><td class="border-b border-r border-[#ececef] px-3 py-3 text-xs font-medium text-muted">12:00 - 14:00</td>' +
            '<td colspan="5" class="border-b border-[#ececef] bg-[#faf9fb] px-3 py-4 text-center text-xs font-semibold uppercase tracking-wide text-muted">Pause déjeuner</td></tr>';
        }
      });
      grille.innerHTML = html;
      bindGrille();
    }

    function bindGrille() {
      grille.querySelectorAll('[data-add]').forEach((b) => b.addEventListener('click', () => ouvrirDrawer(null, b.dataset.add)));
      grille.querySelectorAll('[data-edit]').forEach((b) => b.addEventListener('click', () => ouvrirDrawer(b.dataset.edit)));
      grille.querySelectorAll('[data-del]').forEach((b) => b.addEventListener('click', () => demanderSuppression(b.dataset.del)));
    }

    selClasse.addEventListener('change', render);

    /* ----- Drawer ----- */
    const drawerAdd = document.getElementById('drawerAdd');
    const drawerPanel = drawerAdd.querySelector('aside');
    const fJour = document.getElementById('fJour'), fCreneau = document.getElementById('fCreneau');
    const fMatiere = document.getElementById('fMatiere'), fFormateur = document.getElementById('fFormateur'), fSalle = document.getElementById('fSalle');
    let cleEnCours = null;

    function ouvrirDrawer(key, prefill) {
      cleEnCours = key;
      document.getElementById('drawerTitre').textContent = key ? 'Modifier le cours' : 'Affecter un cours';
      const base = key || prefill || (JOURS[0] + '|' + CRENEAUX[0]);
      const [j, cr] = base.split('|');
      fJour.value = j; fCreneau.value = cr;
      const c = key ? edt[classeActive()][key] : null;
      fMatiere.value = c ? c.matiere : '';
      fFormateur.value = c ? c.formateur : '';
      fSalle.value = c ? c.salle : '';
      drawerAdd.classList.remove('hidden');
      requestAnimationFrame(() => drawerPanel.classList.remove('translate-x-full'));
      document.body.classList.add('overflow-hidden');
    }
    function fermerDrawer() {
      drawerPanel.classList.add('translate-x-full');
      document.body.classList.remove('overflow-hidden');
      setTimeout(() => drawerAdd.classList.add('hidden'), 300);
    }
    document.getElementById('btnAjouter').addEventListener('click', () => ouvrirDrawer(null, null));
    drawerAdd.querySelectorAll('[data-close]').forEach((b) => b.addEventListener('click', fermerDrawer));
    drawerAdd.addEventListener('click', (e) => { if (!drawerPanel.contains(e.target)) fermerDrawer(); });

    document.getElementById('btnEnregistrer').addEventListener('click', () => {
      if (!fMatiere.value || !fFormateur.value || !fSalle.value.trim()) {
        showToast('error', 'Champs manquants', 'Choisissez la matière, le formateur et la salle.');
        return;
      }
      const data = edt[classeActive()];
      const nouvelleCle = fJour.value + '|' + fCreneau.value;
      if (cleEnCours && cleEnCours !== nouvelleCle) delete data[cleEnCours];
      const etait = cleEnCours ? 'modifié' : 'ajouté';
      data[nouvelleCle] = { matiere: fMatiere.value, formateur: fFormateur.value, salle: fSalle.value.trim() };
      fermerDrawer();
      render();
      showToast('success', 'Cours ' + etait, fJour.value + ' · ' + fCreneau.value + ' — ' + fMatiere.value,
        () => { delete data[nouvelleCle]; render(); showToast('info', 'Action annulée'); });
    });

    /* ----- Suppression ----- */
    const modalDelete = document.getElementById('modalDelete');
    let cleASupprimer = null;
    function demanderSuppression(key) {
      cleASupprimer = key;
      modalDelete.classList.remove('hidden'); modalDelete.classList.add('flex');
      document.body.classList.add('overflow-hidden');
    }
    function fermerModal() {
      modalDelete.classList.add('hidden'); modalDelete.classList.remove('flex');
      document.body.classList.remove('overflow-hidden');
    }
    modalDelete.querySelectorAll('[data-cancel-delete]').forEach((b) => b.addEventListener('click', fermerModal));
    modalDelete.addEventListener('click', (e) => { if (e.target === modalDelete) fermerModal(); });
    document.getElementById('btnConfirmDelete').addEventListener('click', () => {
      const data = edt[classeActive()];
      const sauvegarde = data[cleASupprimer];
      const cle = cleASupprimer;
      delete data[cle];
      fermerModal(); render();
      showToast('success', 'Cours retiré', cle.replace('|', ' · '),
        () => { data[cle] = sauvegarde; render(); showToast('info', 'Suppression annulée'); });
    });

    /* ----- Toasts (toujours un bouton Annuler) ----- */
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
        '<div class="mt-2 flex justify-end"><button class="js-action rounded-lg border border-[#e5e7eb] px-3 py-1 text-xs font-medium text-primary hover:bg-page">Annuler</button></div>';
      const remove = () => el.remove();
      el.querySelector('.js-close').addEventListener('click', remove);
      el.querySelector('.js-action').addEventListener('click', () => { if (action) action(); remove(); });
      document.getElementById('toasts').appendChild(el);
      setTimeout(remove, action ? 6000 : 4000);
    }

    render();
  </script>
</body>
</html>