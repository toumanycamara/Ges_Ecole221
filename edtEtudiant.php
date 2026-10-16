<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Emploi du temps (Étudiant)</title>
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
      <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
      <symbol id="i-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 21v-8M12 21V4M19 21v-12"/></symbol>
      <symbol id="i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/></symbol>
      <symbol id="i-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
      <symbol id="i-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-chevron-l" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></symbol>
      <symbol id="i-chevron-r" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></symbol>
      <symbol id="i-download" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></symbol>
      <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
      <symbol id="i-pin-s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
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
        <a href="noteEtudiant.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-chart" /></svg> Notes &amp; résultats</a>
        <a href="edtEtudiant.php" class="flex items-center gap-3 rounded-lg bg-white px-5 py-2.5 text-primary shadow-sm"><svg class="h-4 w-4 shrink-0"><use href="#i-calendar" /></svg> Emploi du temps</a>
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
          <p class="text-[11px] font-semibold uppercase tracking-wide text-primary">Semaine en cours</p>
          <h1 class="text-lg font-bold leading-tight">Emploi du temps</h1>
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
      <div class="mx-auto flex max-w-[1240px] flex-col gap-6">

        <!-- Barre d'outils -->
        <div class="flex flex-wrap items-center justify-between gap-3">
          <div class="flex items-center gap-2">
            <button type="button" aria-label="Semaine précédente" class="flex h-9 w-9 items-center justify-center rounded-lg border border-[#e5e7eb] bg-white text-muted transition hover:text-ink"><svg class="h-4 w-4"><use href="#i-chevron-l" /></svg></button>
            <div class="rounded-lg border border-[#e5e7eb] bg-white px-4 py-2 text-sm font-semibold text-[#1a1b22]">29 septembre — 5 octobre 2026</div>
            <button type="button" aria-label="Semaine suivante" class="flex h-9 w-9 items-center justify-center rounded-lg border border-[#e5e7eb] bg-white text-muted transition hover:text-ink"><svg class="h-4 w-4"><use href="#i-chevron-r" /></svg></button>
            <button type="button" class="ml-1 rounded-lg border border-[#e5e7eb] bg-white px-3 py-2 text-sm font-medium text-[#374151] transition hover:bg-page">Aujourd'hui</button>
          </div>
          <div class="flex items-center gap-2">
            <div class="inline-flex rounded-lg border border-[#e5e7eb] bg-white p-1 text-sm">
              <button type="button" class="rounded-md bg-primary px-3 py-1.5 font-semibold text-white shadow-sm">Semaine</button>
              <button type="button" class="rounded-md px-3 py-1.5 font-medium text-muted transition hover:text-ink">Jour</button>
            </div>
            <button id="btnTelecharger" type="button" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-download" /></svg> Télécharger</button>
          </div>
        </div>

        <!-- Grille par jour -->
        <div id="grille" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6"></div>

        <!-- Synthèse -->
        <div class="flex flex-wrap items-center justify-between gap-2 rounded-2xl border border-[#ececef] bg-white px-5 py-3.5 text-sm shadow-sm">
          <span class="inline-flex items-center gap-1.5 font-semibold text-[#1a1b22]"><svg class="h-4 w-4 text-primary"><use href="#i-clock" /></svg> Total : <span class="text-primary">22 heures / semaine</span></span>
          <span class="text-muted">Prochain cours : <span class="font-semibold text-[#1a1b22]">Lundi 08:00 — Architecture logicielle</span></span>
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

    /* ----- Données de la semaine ----- */
    const jours = [
      { nom: 'Lundi', date: '29 sept', cours: [
        { h: '08:00 - 10:00', titre: 'Architecture logicielle',  prof: 'M. Ndiaye', salle: 'Salle B12', c: '#e11d2a' },
        { h: '10:15 - 12:15', titre: 'Bases de données avancées', prof: 'Mme Sow',   salle: 'Salle C09', c: '#2563eb' },
      ] },
      { nom: 'Mardi', date: '30 sept', cours: [
        { h: '09:00 - 11:00', titre: 'Développement mobile', prof: 'Mme Fall', salle: 'Lab 03', c: '#7c3aed' },
        { h: '14:00 - 16:00', titre: 'DevOps & Cloud',       prof: 'M. Ba',    salle: 'Lab 01', c: '#ea580c' },
      ] },
      { nom: 'Mercredi', date: '1 oct', cours: [
        { h: '08:00 - 10:00', titre: 'UX / UI Design',          prof: 'Mme Diop',  salle: 'Salle A08', c: '#16a34a' },
        { h: '10:15 - 12:15', titre: 'Architecture logicielle', prof: 'M. Ndiaye', salle: 'Salle B12', c: '#e11d2a' },
      ] },
      { nom: 'Jeudi', date: '2 oct', cours: [
        { h: '11:00 - 13:00', titre: 'Anglais technique', prof: 'M. Wilson', salle: 'Salle A08', c: '#2563eb' },
        { h: '14:00 - 17:00', titre: 'Projet tutoré',     prof: 'M. Ndiaye', salle: 'Lab 02',    c: '#e11d2a' },
      ] },
      { nom: 'Vendredi', date: '3 oct', cours: [
        { h: '08:00 - 10:00', titre: 'Bases de données avancées', prof: 'Mme Sow',  salle: 'Salle C09', c: '#2563eb' },
        { h: '10:15 - 12:15', titre: 'Développement mobile',      prof: 'Mme Fall', salle: 'Lab 03',    c: '#7c3aed' },
      ] },
      { nom: 'Samedi', date: '4 oct', cours: [
        { h: '09:00 - 11:00', titre: 'DevOps & Cloud', prof: 'M. Ba', salle: 'Lab 01', c: '#ea580c' },
      ] },
    ];

    document.getElementById('grille').innerHTML = jours.map((j) => `
      <section class="flex flex-col rounded-2xl border border-[#ececef] bg-white p-3 shadow-sm">
        <div class="flex items-center justify-between border-b border-[#f1f1f3] pb-2.5">
          <div><p class="text-sm font-bold text-[#1a1b22]">${j.nom}</p><p class="text-xs text-muted">${j.date}</p></div>
          <span class="flex h-6 w-6 items-center justify-center rounded-full bg-ink text-[11px] font-bold text-white">${j.cours.length}</span>
        </div>
        <div class="mt-3 flex flex-col gap-2.5">
          ${j.cours.length ? j.cours.map((c) => `
            <article class="rounded-xl border-l-4 bg-[#faf9fb] p-3" style="border-left-color:${c.c}">
              <p class="text-[11px] font-semibold" style="color:${c.c}">${c.h}</p>
              <p class="mt-1 text-sm font-bold leading-tight text-[#1a1b22]">${c.titre}</p>
              <p class="mt-0.5 text-xs text-muted">${c.prof}</p>
              <p class="mt-1.5 inline-flex items-center gap-1 text-xs text-muted"><svg class="h-3 w-3"><use href="#i-pin-s" /></svg> ${c.salle}</p>
            </article>`).join('') : `<p class="py-6 text-center text-xs text-muted">Aucun cours</p>`}
        </div>
      </section>`).join('');

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
      showToast('success', 'Emploi du temps téléchargé', 'Votre planning de la semaine a été généré.', () => showToast('info', 'Téléchargement annulé'));
    });
  </script>
</body>
</html>