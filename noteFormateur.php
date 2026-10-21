<?php
session_start();
if (!isset ($_SESSION['role'])||$_SESSION['role']!=='formateur') {
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
          <img src="https://i.pravatar.cc/64?img=12" alt="Photo" class="h-8 w-8 rounded-full object-cover" />
          <div class="hidden sm:block"><p class="text-sm font-semibold leading-[17.5px]">Dr. Mamadou Kane</p><p class="text-xs leading-3 text-muted">m.kane@ecole221.sn</p></div>
        </div>
        <span class="hidden h-6 w-px bg-[#e3e1ec] sm:block"></span>
        <span class="whitespace-nowrap rounded-full bg-primary px-2.5 py-1 text-xs font-semibold text-white shadow-sm">Formateur</span>
        <a href="deconnexion.php" class="hidden items-center gap-1.5 whitespace-nowrap rounded-full border border-primary px-3 py-1 text-xs font-semibold text-primary transition hover:bg-primary hover:text-white sm:inline-flex"><svg class="h-3.5 w-3.5"><use href="#i-logout" /></svg> Déconnexion</a>
      </div>
    </header>

    <main class="px-4 py-8 sm:px-6 lg:px-12">
      <div class="mx-auto flex max-w-[1180px] flex-col gap-6">

        <!-- Panneau -->
        <section class="overflow-hidden rounded-2xl bg-white shadow-sm">

          <!-- Barre de sélection -->
          <div class="flex flex-col gap-4 border-b border-[#ececef] bg-[#faf9fb] px-5 py-4 sm:px-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="flex flex-col gap-4 sm:flex-row sm:flex-wrap">
              <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold uppercase tracking-wide text-muted">Classe</label>
                <div class="relative">
                  <select class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 sm:w-52">
                    <option>L3 Génie Logiciel</option>
                    <option>M1 Data Science</option>
                    <option>M2 Intelligence Artificielle</option>
                  </select>
                  <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
                </div>
              </div>
              <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold uppercase tracking-wide text-muted">Matière</label>
                <div class="relative">
                  <select class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 sm:w-56">
                    <option>Algorithmique Avancée</option>
                    <option>Base de données</option>
                    <option>Programmation Web</option>
                  </select>
                  <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
                </div>
              </div>
              <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold uppercase tracking-wide text-muted">Type</label>
                <div class="relative">
                  <select class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 sm:w-40">
                    <option>Examen</option>
                    <option>Devoir</option>
                    <option>TP</option>
                  </select>
                  <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
                </div>
              </div>
            </div>
            <button id="btnSave" type="button" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primarydark">
              <svg class="h-4 w-4"><use href="#i-save" /></svg> Enregistrer les notes
            </button>
          </div>

          <!-- Tableau de saisie -->
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
              <tbody id="tbody" class="divide-y divide-[#f1f1f3]"></tbody>
            </table>
          </div>

          <!-- Barre de synthèse -->
          <div class="flex flex-wrap items-center gap-x-6 gap-y-1 border-t border-[#ececef] bg-[#fdf2f3] px-5 py-3.5 text-sm sm:px-6">
            <span class="inline-flex items-center gap-1.5 font-semibold text-[#1a1b22]"><svg class="h-4 w-4 text-primary"><use href="#i-trend" /></svg> Moyenne de la classe : <span id="moy" class="text-primary">—</span> / 20</span>
            <span class="text-muted">Note la plus haute : <span id="hi" class="font-semibold text-[#1a1b22]">—</span></span>
            <span class="text-muted">Note la plus basse : <span id="lo" class="font-semibold text-[#1a1b22]">—</span></span>
          </div>

        </section>
      </div>
    </main>
  </div>

  <!-- Conteneur des toasts (style projet : cartes blanches en haut à droite) -->
  <div id="toasts" class="fixed right-4 top-4 z-[60] flex w-[320px] max-w-[calc(100vw-2rem)] flex-col gap-2"></div>

  <script>
    /* ----- Menu mobile ----- */
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleMenu = (open) => { sidebar.classList.toggle('-translate-x-full', !open); overlay.classList.toggle('hidden', !open); document.body.classList.toggle('overflow-hidden', open); };
    document.getElementById('openMenu').addEventListener('click', () => toggleMenu(true));
    document.getElementById('closeMenu').addEventListener('click', () => toggleMenu(false));
    overlay.addEventListener('click', () => toggleMenu(false));

    /* ----- Données ----- */
    const etudiants = [
      { matricule: '221-SN-2024-0045', nom: 'Camara', prenom: 'Toumany',   note: 15 },
      { matricule: '221-SN-2024-0046', nom: 'Sy',     prenom: 'Fatou',     note: 18 },
      { matricule: '221-SN-2024-0047', nom: 'Diallo', prenom: 'Abdoulaye', note: 12 },
      { matricule: '221-SN-2024-0048', nom: 'Ndiaye', prenom: 'Aminata',   note: 8  },
      { matricule: '221-SN-2024-0049', nom: 'Ba',     prenom: 'Ibrahima',  note: 14 },
      { matricule: '221-SN-2024-0050', nom: 'Diop',   prenom: 'Mariama',   note: 16 },
      { matricule: '221-SN-2024-0051', nom: 'Fall',   prenom: 'Ousmane',   note: 10 },
      { matricule: '221-SN-2024-0052', nom: 'Mbodji', prenom: 'Anta',      note: 17 },
    ];

    const APPRECIATIONS = ['Très bien', 'Bien', 'Assez bien', 'Passable', 'Insuffisant'];
    const appreciationPour = (n) => {
      if (n === '' || n === null || isNaN(n)) return '';
      if (n >= 17) return 'Très bien';
      if (n >= 14) return 'Bien';
      if (n >= 12) return 'Assez bien';
      if (n >= 10) return 'Passable';
      return 'Insuffisant';
    };

    const tbody = document.getElementById('tbody');
    const moyEl = document.getElementById('moy');
    const hiEl = document.getElementById('hi');
    const loEl = document.getElementById('lo');

    function optionsAppreciation(selected) {
      return APPRECIATIONS.map((a) => `<option value="${a}" ${a === selected ? 'selected' : ''}>${a}</option>`).join('');
    }

    function render() {
      tbody.innerHTML = etudiants.map((e, i) => `
        <tr class="hover:bg-[#faf9fb]">
          <td class="px-3 py-3 text-muted">${i + 1}</td>
          <td class="px-3 py-3 font-mono text-xs text-[#374151]">${e.matricule}</td>
          <td class="px-3 py-3 font-semibold text-[#1a1b22]">${e.nom}</td>
          <td class="px-3 py-3 text-[#374151]">${e.prenom}</td>
          <td class="px-3 py-3">
            <input type="number" min="0" max="20" step="0.25" value="${e.note}" data-i="${i}"
              class="js-note w-20 rounded-lg border border-[#e5e7eb] bg-white px-2.5 py-1.5 text-center text-sm font-semibold outline-none focus:border-primary focus:ring-2 focus:ring-primary/30" />
          </td>
          <td class="px-3 py-3">
            <div class="relative w-36">
              <select data-i="${i}" class="js-appr w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-1.5 pl-3 pr-8 text-sm text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                ${optionsAppreciation(appreciationPour(e.note))}
              </select>
              <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
            </div>
          </td>
        </tr>`).join('');
      bindRows();
      synthese();
    }

    function bindRows() {
      document.querySelectorAll('.js-note').forEach((inp) => {
        inp.addEventListener('input', () => {
          const i = +inp.dataset.i;
          let v = inp.value === '' ? '' : Math.min(20, Math.max(0, parseFloat(inp.value)));
          etudiants[i].note = v;
          const appr = document.querySelector(`.js-appr[data-i="${i}"]`);
          if (appr) appr.value = appreciationPour(v);
          synthese();
        });
      });
      document.querySelectorAll('.js-appr').forEach((sel) => {
        sel.addEventListener('change', () => { etudiants[+sel.dataset.i].appreciation = sel.value; });
      });
    }

    function synthese() {
      const notes = etudiants.map((e) => parseFloat(e.note)).filter((n) => !isNaN(n));
      if (!notes.length) { moyEl.textContent = '—'; hiEl.textContent = '—'; loEl.textContent = '—'; return; }
      const moy = notes.reduce((a, b) => a + b, 0) / notes.length;
      moyEl.textContent = moy.toFixed(2).replace(/\.00$/, '');
      hiEl.textContent = Math.max(...notes);
      loEl.textContent = Math.min(...notes);
    }

    /* ----- Toast (style projet : carte blanche, barre colorée, bouton Annuler) ----- */
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

    /* ----- Enregistrer ----- */
    document.getElementById('btnSave').addEventListener('click', () => {
      showToast('success', 'Notes enregistrées', 'Les notes de la classe ont bien été enregistrées.', () => showToast('info', 'Enregistrement annulé', 'Les notes n\'ont pas été enregistrées.'));
    });

    render();
  </script>
</body>
</html>