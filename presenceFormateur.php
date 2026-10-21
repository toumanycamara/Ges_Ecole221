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
          <img src="https://i.pravatar.cc/64?img=12" alt="Photo" class="h-8 w-8 rounded-full object-cover" />
          <div class="hidden sm:block"><p class="text-sm font-semibold leading-[17.5px]">Dr. Mamadou Kane</p><p class="text-xs leading-3 text-muted">m.kane@ecole221.sn</p></div>
        </div>
        <span class="hidden h-6 w-px bg-[#e3e1ec] sm:block"></span>
        <span class="whitespace-nowrap rounded-full bg-primary px-2.5 py-1 text-xs font-semibold text-white shadow-sm">Formateur</span>
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
                <label class="text-[11px] font-semibold uppercase tracking-wide text-muted">Date</label>
                <div class="relative">
                  <select class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 sm:w-56">
                    <option>Lundi 28 Septembre 2026</option>
                    <option>Mardi 29 Septembre 2026</option>
                    <option>Mercredi 30 Septembre 2026</option>
                  </select>
                  <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
                </div>
              </div>
            </div>
            <button id="btnValider" type="button" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primarydark">
              <svg class="h-4 w-4"><use href="#i-check-circle" /></svg> Valider l'appel
            </button>
          </div>

          <!-- Tableau d'appel -->
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
              <tbody id="tbody" class="divide-y divide-[#f1f1f3]"></tbody>
            </table>
          </div>

          <!-- Barre de synthèse -->
          <div class="flex flex-wrap items-center gap-x-5 gap-y-2 border-t border-[#ececef] bg-[#fafafa] px-5 py-3.5 text-sm sm:px-6">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#dcfce7] px-3 py-1 text-xs font-semibold text-[#15803d]">Présents : <span id="nbP">0</span></span>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#fef9c3] px-3 py-1 text-xs font-semibold text-[#a16207]">Retards : <span id="nbR">0</span></span>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#fee2e2] px-3 py-1 text-xs font-semibold text-[#b91c1c]">Absents : <span id="nbA">0</span></span>
            <span class="font-semibold text-[#1a1b22]">Taux de présence : <span id="taux" class="text-primary">0 %</span></span>
          </div>

        </section>
      </div>
    </main>
  </div>

  <!-- Conteneur des toasts (style projet) -->
  <div id="toasts" class="fixed right-4 top-4 z-[60] flex w-[320px] max-w-[calc(100vw-2rem)] flex-col gap-2"></div>

  <script>
    /* ----- Menu mobile ----- */
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleMenu = (open) => { sidebar.classList.toggle('-translate-x-full', !open); overlay.classList.toggle('hidden', !open); document.body.classList.toggle('overflow-hidden', open); };
    document.getElementById('openMenu').addEventListener('click', () => toggleMenu(true));
    document.getElementById('closeMenu').addEventListener('click', () => toggleMenu(false));
    overlay.addEventListener('click', () => toggleMenu(false));

    /* ----- Données de l'appel ----- */
    const eleves = [
      { matricule: '221-SN-2024-0045', nom: 'Camara Toumany',   statut: 'Présent', heure: '08:00', obs: '' },
      { matricule: '221-SN-2024-0046', nom: 'Sy Fatou',         statut: 'Présent', heure: '08:02', obs: '' },
      { matricule: '221-SN-2024-0047', nom: 'Diallo Abdoulaye', statut: 'Retard',  heure: '08:25', obs: 'Retard de 25 min' },
      { matricule: '221-SN-2024-0048', nom: 'Ndiaye Aminata',   statut: 'Absent',  heure: '',      obs: 'Non justifié' },
      { matricule: '221-SN-2024-0049', nom: 'Ba Ibrahima',      statut: 'Présent', heure: '07:55', obs: '' },
      { matricule: '221-SN-2024-0050', nom: 'Diop Mariama',     statut: 'Présent', heure: '08:00', obs: '' },
      { matricule: '221-SN-2024-0051', nom: 'Fall Ousmane',     statut: 'Absent',  heure: '',      obs: 'Certificat médical' },
      { matricule: '221-SN-2024-0052', nom: 'Mbodji Anta',      statut: 'Retard',  heure: '08:15', obs: 'Retard de 15 min' },
    ];

    const STATUTS = ['Présent', 'Retard', 'Absent'];
    const statutStyle = {
      'Présent': 'bg-[#dcfce7] text-[#15803d]',
      'Retard':  'bg-[#fef9c3] text-[#a16207]',
      'Absent':  'bg-[#fee2e2] text-[#b91c1c]',
    };

    const tbody = document.getElementById('tbody');

    function render() {
      tbody.innerHTML = eleves.map((e, i) => `
        <tr class="hover:bg-[#faf9fb]">
          <td class="px-3 py-3.5 text-muted">${i + 1}</td>
          <td class="px-3 py-3.5 font-mono text-xs text-[#374151]">${e.matricule}</td>
          <td class="px-3 py-3.5 font-semibold text-[#1a1b22]">${e.nom}</td>
          <td class="px-3 py-3.5">
            <div class="relative inline-block">
              <select data-i="${i}" class="js-statut ${statutStyle[e.statut]} cursor-pointer appearance-none rounded-full py-1 pl-3 pr-7 text-xs font-semibold outline-none focus:ring-2 focus:ring-primary/30">
                ${STATUTS.map((s) => `<option value="${s}" ${s === e.statut ? 'selected' : ''}>${s}</option>`).join('')}
              </select>
              <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2"><svg class="h-3.5 w-3.5 opacity-60"><use href="#i-chevron" /></svg></span>
            </div>
          </td>
          <td class="px-3 py-3.5">
            <input type="time" data-i="${i}" value="${e.heure}" ${e.statut === 'Absent' ? 'disabled' : ''}
              class="js-heure w-28 rounded-lg border border-[#e5e7eb] bg-white px-2.5 py-1.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 disabled:bg-[#f3f4f6] disabled:text-muted" />
          </td>
          <td class="px-3 py-3.5">
            <input type="text" data-i="${i}" value="${e.obs}" placeholder="—"
              class="js-obs w-48 rounded-lg border border-transparent bg-transparent px-2 py-1.5 text-sm text-[#374151] outline-none hover:border-[#e5e7eb] focus:border-primary focus:ring-2 focus:ring-primary/20" />
          </td>
        </tr>`).join('');
      bindRows();
      synthese();
    }

    function bindRows() {
      document.querySelectorAll('.js-statut').forEach((sel) => {
        sel.addEventListener('change', () => {
          const i = +sel.dataset.i;
          eleves[i].statut = sel.value;
          sel.className = `js-statut ${statutStyle[sel.value]} cursor-pointer appearance-none rounded-full py-1 pl-3 pr-7 text-xs font-semibold outline-none focus:ring-2 focus:ring-primary/30`;
          const h = document.querySelector(`.js-heure[data-i="${i}"]`);
          if (h) {
            if (sel.value === 'Absent') { h.value = ''; h.disabled = true; eleves[i].heure = ''; }
            else { h.disabled = false; }
          }
          synthese();
        });
      });
      document.querySelectorAll('.js-heure').forEach((inp) => inp.addEventListener('input', () => { eleves[+inp.dataset.i].heure = inp.value; }));
      document.querySelectorAll('.js-obs').forEach((inp) => inp.addEventListener('input', () => { eleves[+inp.dataset.i].obs = inp.value; }));
    }

    function synthese() {
      const total = eleves.length;
      const nbP = eleves.filter((e) => e.statut === 'Présent').length;
      const nbR = eleves.filter((e) => e.statut === 'Retard').length;
      const nbA = eleves.filter((e) => e.statut === 'Absent').length;
      document.getElementById('nbP').textContent = nbP;
      document.getElementById('nbR').textContent = nbR;
      document.getElementById('nbA').textContent = nbA;
      const taux = total ? Math.round(((nbP + nbR) / total) * 100) : 0;
      document.getElementById('taux').textContent = taux + ' %';
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

    /* ----- Valider l'appel ----- */
    document.getElementById('btnValider').addEventListener('click', () => {
      showToast('success', 'Appel validé', 'Les présences de la classe ont bien été enregistrées.', () => showToast('info', 'Validation annulée', 'L\'appel n\'a pas été enregistré.'));
    });

    render();
  </script>
</body>
</html>