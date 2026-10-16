<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Notes & résultats (Étudiant)</title>
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
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
      <symbol id="i-grid" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></symbol>
      <symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></symbol>
      <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
      <symbol id="i-clipboard" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="8" y="3" width="8" height="4" rx="1"/><path d="M8 5H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><path d="m9 14 2 2 4-4"/></symbol>
      <symbol id="i-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 21v-8M12 21V4M19 21v-12"/></symbol>
      <symbol id="i-folder" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5l2 3h7a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2Z"/></symbol>
      <symbol id="i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/></symbol>
      <symbol id="i-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
      <symbol id="i-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-bell" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></symbol>
      <symbol id="i-award" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.47 12.89 17 22l-5-3-5 3 1.53-9.11"/></symbol>
      <symbol id="i-check-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></symbol>
      <symbol id="i-trophy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6M18 9h1.5a2.5 2.5 0 0 0 0-5H18M4 22h16M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22M18 2H6v7a6 6 0 0 0 12 0V2Z"/></symbol>
      <symbol id="i-download" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
      <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
    </defs>
  </svg>

  <div id="overlay" class="fixed inset-0 z-30 hidden bg-black/50 lg:hidden"></div>

  <!-- ===================== SIDEBAR (Étudiant) ===================== -->
  <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-[260px] -translate-x-full flex-col justify-between bg-primary shadow-[0_1px_4px_rgba(0,0,0,0.08)] transition-transform duration-300 lg:z-20 lg:translate-x-0">
    <div>
      <div class="flex h-16 items-center gap-3 px-6">
        <span class="flex h-11 w-10 shrink-0 items-center justify-center overflow-hidden rounded-md bg-white p-0.5 shadow-sm"><img src="assets/logo-ecole221.png" alt="Logo École 221" class="h-full w-full object-contain" /></span>
        <span class="text-xl font-semibold tracking-tight text-white">École 221</span>
        <button id="closeMenu" type="button" aria-label="Fermer le menu" class="ml-auto rounded-lg p-1.5 text-white transition hover:bg-[#B8141F] lg:hidden"><svg class="h-5 w-5"><use href="#i-x" /></svg></button>
      </div>
      <nav class="mt-1 flex flex-col gap-2 px-4 pt-4 text-sm font-medium text-white">
        <a href="accueilEtudiant.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-grid" /></svg> Tableau de bord</a>
        <a href="noteEtudiant.php" class="flex items-center gap-3 rounded-lg bg-white px-5 py-2.5 text-primary shadow-sm"><svg class="h-4 w-4 shrink-0"><use href="#i-chart" /></svg> Notes &amp; résultats</a>
        <a href="edtEtudiant.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-calendar" /></svg> Emploi du temps</a>
        <a href="profilEtudiant.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-user" /></svg> Mon profil</a>
      </nav>
    </div>
    <div class="flex flex-col items-center gap-2.5 border-t-[0.5px] border-white/25 px-4 py-6">
      <div class="flex items-center gap-2 rounded-full bg-white/15 px-4 py-1 text-xs text-white"><svg class="h-3 w-3"><use href="#i-pin" /></svg> Campus Point E · Dakar</div>
      <a href="loginEcole221.php" class="flex w-full items-center gap-3 rounded-lg border border-white/40 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-logout" /></svg> Se déconnecter</a>
    </div>
  </aside>

  <!-- ===================== CONTENU ===================== -->
  <div class="min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[88px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-10">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <div>
          <p class="text-[11px] font-semibold uppercase tracking-wide text-primary">Semestre 5 · Résultats provisoires</p>
          <h1 class="text-lg font-bold leading-tight">Notes &amp; résultats</h1>
        </div>
      </div>
      <div class="flex items-center gap-3 sm:gap-4">
        <div class="flex items-center gap-2">
          <span class="flex h-9 w-9 items-center justify-center rounded-full bg-primary text-xs font-bold text-white">AM</span>
          <div class="hidden sm:block"><p class="text-sm font-semibold leading-[17.5px]">Aminata Mbaye</p><p class="text-xs leading-3 text-muted">L3 Génie Logiciel</p></div>
        </div>
        <span class="whitespace-nowrap rounded-full bg-primary px-2.5 py-1 text-xs font-semibold text-white shadow-sm">Étudiante</span>
      </div>
    </header>

    <main class="px-4 py-7 sm:px-6 lg:px-10">
      <div class="mx-auto flex max-w-[1180px] flex-col gap-6">

        <!-- Cartes de synthèse -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-[1.9fr_1fr_1fr]">
          <!-- Moyenne générale (carte sombre) -->
          <div class="flex items-center justify-between gap-4 rounded-2xl bg-ink p-6 text-white shadow-sm">
            <div>
              <p class="text-[11px] font-semibold uppercase tracking-wide text-white/50">Moyenne générale</p>
              <p class="mt-2 text-5xl font-extrabold leading-none">14,8 <span class="text-2xl font-semibold text-white/50">/ 20</span></p>
              <p class="mt-3 inline-flex items-center gap-1.5 text-xs font-semibold text-[#4ade80]"><svg class="h-3.5 w-3.5"><use href="#i-chart" /></svg> +0,6 point depuis le S4</p>
            </div>
            <span class="flex h-20 w-20 shrink-0 items-center justify-center rounded-full bg-white text-primary shadow-md"><svg class="h-9 w-9"><use href="#i-award" /></svg></span>
          </div>
          <!-- Crédits ECTS -->
          <div class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#dcfce7] text-[#15803d]"><svg class="h-4 w-4"><use href="#i-check-circle" /></svg></span>
            <p class="mt-3 text-2xl font-extrabold text-[#1a1b22]">24 / 30</p>
            <p class="mt-1 text-xs text-muted">Crédits ECTS validés</p>
            <p class="mt-2 text-xs text-muted">6 crédits en attente</p>
          </div>
          <!-- Classement -->
          <div class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#fdeaec] text-primary"><svg class="h-4 w-4"><use href="#i-trophy" /></svg></span>
            <p class="mt-3 text-2xl font-extrabold text-[#1a1b22]">8e / 42</p>
            <p class="mt-1 text-xs text-muted">Classement de la promotion</p>
            <span class="mt-2 inline-block rounded-full bg-[#fdeaec] px-2.5 py-0.5 text-xs font-semibold text-primary">Top 20%</span>
          </div>
        </div>

        <!-- Filtres + téléchargement -->
        <div class="flex flex-wrap items-center justify-between gap-3">
          <div class="flex flex-wrap gap-3">
            <div class="relative">
              <select class="appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm font-medium text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                <option>Semestre 5</option><option>Semestre 4</option><option>Semestre 3</option>
              </select>
              <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></span>
            </div>
            <div class="relative">
              <select class="appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm font-medium text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                <option>Toutes les matières</option><option>Architecture logicielle</option><option>Bases de données avancées</option>
              </select>
              <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></span>
            </div>
          </div>
          <button id="btnTelecharger" type="button" class="inline-flex items-center gap-2 rounded-lg border border-primary px-4 py-2.5 text-sm font-semibold text-primary transition hover:bg-primary hover:text-white">
            <svg class="h-4 w-4"><use href="#i-download" /></svg> Télécharger le relevé
          </button>
        </div>

        <!-- Deux colonnes : détail + appréciation -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-[1.7fr_1fr]">

          <!-- Détail par matière -->
          <section class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-2">
              <h3 class="font-bold text-[#1a1b22]">Détail par matière</h3>
              <span class="text-xs text-muted">Contrôle continu 40% · Examen 60%</span>
            </div>
            <div class="mt-4 overflow-x-auto">
              <table class="w-full min-w-[620px] text-left text-sm">
                <thead>
                  <tr class="border-b border-[#ececef] text-[11px] uppercase tracking-wide text-muted">
                    <th class="px-2 py-3 font-semibold">Matière</th>
                    <th class="px-2 py-3 font-semibold">CC</th>
                    <th class="px-2 py-3 font-semibold">Examen</th>
                    <th class="px-2 py-3 font-semibold">Moyenne</th>
                    <th class="px-2 py-3 font-semibold">Coef.</th>
                    <th class="px-2 py-3 font-semibold">Mention</th>
                  </tr>
                </thead>
                <tbody id="tbody" class="divide-y divide-[#f1f1f3]"></tbody>
              </table>
            </div>
          </section>

          <!-- Colonne de droite -->
          <div class="flex flex-col gap-5">
            <!-- Appréciation générale -->
            <section class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm">
              <h3 class="font-bold text-[#1a1b22]">Appréciation générale</h3>
              <div class="mt-4 flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#fdeaec] text-xs font-bold text-primary">AD</span>
                <div><p class="text-sm font-semibold text-[#1a1b22]">Mme Astou Diop</p><p class="text-xs text-muted">Responsable pédagogique</p></div>
              </div>
              <p class="mt-4 text-sm leading-relaxed text-[#374151]">Très bon semestre. Aminata se distingue par sa rigueur et la qualité de ses projets. Poursuivre les efforts en DevOps.</p>
            </section>

            <!-- Repères de promotion -->
            <section class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm">
              <h3 class="font-bold text-[#1a1b22]">Repères de promotion</h3>
              <div class="mt-4 flex flex-col gap-3 text-sm">
                <div class="flex items-center justify-between">
                  <span class="inline-flex items-center gap-2 text-[#374151]"><span class="h-2.5 w-2.5 rounded-full bg-primary"></span> Votre moyenne</span>
                  <span class="font-bold text-[#1a1b22]">14,8</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="inline-flex items-center gap-2 text-[#374151]"><span class="h-2.5 w-2.5 rounded-full bg-[#2563eb]"></span> Moyenne de classe</span>
                  <span class="font-bold text-[#1a1b22]">13,2</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="inline-flex items-center gap-2 text-[#374151]"><span class="h-2.5 w-2.5 rounded-full bg-[#16a34a]"></span> Meilleure moyenne</span>
                  <span class="font-bold text-[#1a1b22]">17,6</span>
                </div>
              </div>
            </section>
          </div>
        </div>

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

    /* ----- Données ----- */
    const matieres = [
      { matiere: 'Architecture logicielle',   code: 'GL-301',  cc: '15,5', exam: '16,0', moy: '15,8', pct: 79, coef: 4, mention: 'Très bien',  couleur: '#16a34a' },
      { matiere: 'Bases de données avancées',  code: 'GL-303',  cc: '14,0', exam: '15,5', moy: '14,9', pct: 75, coef: 3, mention: 'Bien',       couleur: '#2563eb' },
      { matiere: 'Développement mobile',       code: 'GL-305',  cc: '16,5', exam: '14,0', moy: '15,0', pct: 75, coef: 4, mention: 'Bien',       couleur: '#2563eb' },
      { matiere: 'UX / UI Design',             code: 'GL-307',  cc: '17,5', exam: '16,0', moy: '16,6', pct: 83, coef: 3, mention: 'Très bien',  couleur: '#16a34a' },
      { matiere: 'DevOps & Cloud',             code: 'GL-309',  cc: '13,0', exam: '12,5', moy: '12,7', pct: 63, coef: 3, mention: 'Assez bien', couleur: '#ea580c' },
      { matiere: 'Anglais technique',          code: 'ANG-305', cc: '14,5', exam: '13,5', moy: '13,9', pct: 69, coef: 2, mention: 'Bien',       couleur: '#2563eb' },
    ];

    const mentionBadge = { 'Très bien': 'bg-[#dcfce7] text-[#15803d]', 'Bien': 'bg-[#dbeafe] text-[#2563eb]', 'Assez bien': 'bg-[#fff1e6] text-[#ea580c]' };

    document.getElementById('tbody').innerHTML = matieres.map((m) => `
      <tr class="hover:bg-[#faf9fb]">
        <td class="px-2 py-3.5"><p class="font-semibold text-[#1a1b22]">${m.matiere}</p><p class="text-xs text-muted">${m.code}</p></td>
        <td class="px-2 py-3.5 text-[#374151]">${m.cc}</td>
        <td class="px-2 py-3.5 text-[#374151]">${m.exam}</td>
        <td class="px-2 py-3.5">
          <p class="font-semibold text-[#1a1b22]">${m.moy}</p>
          <div class="mt-1 h-1.5 w-24 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full" style="width:${m.pct}%;background:${m.couleur}"></div></div>
        </td>
        <td class="px-2 py-3.5 text-[#374151]">${m.coef}</td>
        <td class="px-2 py-3.5"><span class="inline-block rounded-full ${mentionBadge[m.mention]} px-2.5 py-0.5 text-xs font-semibold">${m.mention}</span></td>
      </tr>`).join('');

    /* ----- Toast (style projet, bouton Annuler) ----- */
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

    document.getElementById('btnTelecharger').addEventListener('click', () => {
      showToast('success', 'Relevé téléchargé', 'Votre relevé de notes du semestre 5 a été généré.', () => showToast('info', 'Téléchargement annulé'));
    });
  </script>
</body>
</html>