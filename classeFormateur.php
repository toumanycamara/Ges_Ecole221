<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Classes (Formateur)</title>
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
      <symbol id="i-grid" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></symbol>
      <symbol id="i-door" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 21V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17M3 21h18M14 12v.01"/></symbol>
      <symbol id="i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/></symbol>
      <symbol id="i-note" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h4"/></symbol>
      <symbol id="i-clipboard" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="8" y="3" width="8" height="4" rx="1"/><path d="M8 5H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><path d="m9 14 2 2 4-4"/></symbol>
      <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
      <symbol id="i-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 21v-8M12 21V4M19 21v-12"/></symbol>
      <symbol id="i-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
      <symbol id="i-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></symbol>
    </defs>
  </svg>

  <div id="overlay" class="fixed inset-0 z-30 hidden bg-black/50 lg:hidden"></div>

  <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-[260px] -translate-x-full flex-col justify-between bg-primary shadow-[0_1px_4px_rgba(0,0,0,0.08)] transition-transform duration-300 lg:z-20 lg:translate-x-0">
    <div>
      <div class="flex h-16 items-center gap-3 px-6">
        <span class="flex h-11 w-10 shrink-0 items-center justify-center overflow-hidden rounded-md bg-white p-0.5 shadow-sm"><img src="assets/logo-ecole221.png" alt="Logo École 221" class="h-full w-full object-contain" /></span>
        <span class="text-xl font-semibold tracking-tight text-white">École 221</span>
        <button id="closeMenu" type="button" aria-label="Fermer le menu" class="ml-auto rounded-lg p-1.5 text-white transition hover:bg-[#B8141F] lg:hidden"><svg class="h-5 w-5"><use href="#i-x" /></svg></button>
      </div>
      <nav class="mt-1 flex flex-col gap-2 px-4 pt-4 text-sm font-medium text-white">
        <a href="accueilFormateur.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-grid" /></svg> Tableau de bord</a>
        <a href="classFormateur.php" class="flex items-center gap-3 rounded-lg bg-white px-5 py-2.5 text-primary shadow-sm"><svg class="h-4 w-4 shrink-0"><use href="#i-door" /></svg> Classes</a>
        <a href="etudiantFormateur.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-user" /></svg> Étudiants</a>
        <a href="noteFormateur.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-note" /></svg> Notes</a>
        <a href="presenceFormateur.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-clipboard" /></svg> Présences</a>
        <a href="edtFormateur.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-calendar" /></svg> Emploi du temps</a>
      </nav>
    </div>
    <div class="flex flex-col items-center gap-2.5 border-t-[0.5px] border-white/25 px-4 py-6">
      <div class="flex items-center gap-2 rounded-full bg-white/15 px-4 py-1 text-xs text-white"><svg class="h-3 w-3"><use href="#i-pin" /></svg> Dakar, Sénégal</div>
      <a href="#" class="flex w-full items-center gap-3 rounded-lg px-5 py-2.5 text-sm font-medium text-white transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-logout" /></svg> Se déconnecter</a>
    </div>
  </aside>

  <div class="min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <h2 class="text-sm font-semibold">Classes - Formateur</h2>
      </div>
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
          <img src="https://i.pravatar.cc/64?img=12" alt="Photo" class="h-8 w-8 rounded-full object-cover" />
          <div class="hidden sm:block"><p class="text-sm font-semibold leading-[17.5px]">Dr. Mamadou Kane</p><p class="text-xs leading-3 text-muted">m.kane@ecole221.sn</p></div>
        </div>
        <span class="hidden h-6 w-px bg-[#e3e1ec] sm:block"></span>
        <span class="whitespace-nowrap rounded-full bg-primary px-2 py-1 text-xs font-semibold text-white shadow-sm">Formateur</span>
      </div>
    </header>

    <main class="px-4 py-8 sm:px-6 lg:px-12">
      <div class="mx-auto flex max-w-[1180px] flex-col gap-6">

        <!-- En-tête -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <h1 class="text-xl font-bold text-[#1a1b22]">Liste des classes</h1>
            <p class="mt-1 text-xs text-muted">Classes que vous encadrez — consultation</p>
          </div>
          <div class="relative">
            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-search" /></svg></span>
            <input type="text" placeholder="Rechercher une classe" class="w-64 rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-9 pr-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
          </div>
        </div>

        <!-- Grille de cartes -->
        <section id="grid" class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

          <article class="rounded-2xl bg-white p-5 shadow-sm">
            <h3 class="font-bold text-[#1a1b22]">L3 Génie Logiciel</h3>
            <div class="mt-3 space-y-1 text-sm text-muted">
              <p><span class="text-[#374151]">Filière :</span> Génie Logiciel</p>
              <p><span class="text-[#374151]">Niveau :</span> Licence 3</p>
              <p><span class="text-[#374151]">Capacité :</span> 40 étudiants</p>
            </div>
            <div class="mt-4">
              <div class="mb-1.5 flex items-center justify-between text-xs"><span class="font-medium text-muted">Remplissage</span><span class="font-bold text-primary">28 / 40</span></div>
              <div class="h-2 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:70%"></div></div>
            </div>
          </article>

          <article class="rounded-2xl bg-white p-5 shadow-sm">
            <h3 class="font-bold text-[#1a1b22]">L1 Tronc Commun</h3>
            <div class="mt-3 space-y-1 text-sm text-muted">
              <p><span class="text-[#374151]">Filière :</span> Tronc Commun</p>
              <p><span class="text-[#374151]">Niveau :</span> Licence 1</p>
              <p><span class="text-[#374151]">Capacité :</span> 50 étudiants</p>
            </div>
            <div class="mt-4">
              <div class="mb-1.5 flex items-center justify-between text-xs"><span class="font-medium text-muted">Remplissage</span><span class="font-bold text-primary">45 / 50</span></div>
              <div class="h-2 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:90%"></div></div>
            </div>
          </article>

          <article class="rounded-2xl bg-white p-5 shadow-sm">
            <h3 class="font-bold text-[#1a1b22]">M1 Data Science</h3>
            <div class="mt-3 space-y-1 text-sm text-muted">
              <p><span class="text-[#374151]">Filière :</span> Data Science</p>
              <p><span class="text-[#374151]">Niveau :</span> Master 1</p>
              <p><span class="text-[#374151]">Capacité :</span> 40 étudiants</p>
            </div>
            <div class="mt-4">
              <div class="mb-1.5 flex items-center justify-between text-xs"><span class="font-medium text-muted">Remplissage</span><span class="font-bold text-primary">32 / 40</span></div>
              <div class="h-2 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:80%"></div></div>
            </div>
          </article>

          <article class="rounded-2xl bg-white p-5 shadow-sm">
            <h3 class="font-bold text-[#1a1b22]">L2 Informatique</h3>
            <div class="mt-3 space-y-1 text-sm text-muted">
              <p><span class="text-[#374151]">Filière :</span> Informatique</p>
              <p><span class="text-[#374151]">Niveau :</span> Licence 2</p>
              <p><span class="text-[#374151]">Capacité :</span> 45 étudiants</p>
            </div>
            <div class="mt-4">
              <div class="mb-1.5 flex items-center justify-between text-xs"><span class="font-medium text-muted">Remplissage</span><span class="font-bold text-primary">38 / 45</span></div>
              <div class="h-2 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:84%"></div></div>
            </div>
          </article>

          <article class="rounded-2xl bg-white p-5 shadow-sm">
            <h3 class="font-bold text-[#1a1b22]">M2 Cybersécurité</h3>
            <div class="mt-3 space-y-1 text-sm text-muted">
              <p><span class="text-[#374151]">Filière :</span> Cybersécurité</p>
              <p><span class="text-[#374151]">Niveau :</span> Master 2</p>
              <p><span class="text-[#374151]">Capacité :</span> 35 étudiants</p>
            </div>
            <div class="mt-4">
              <div class="mb-1.5 flex items-center justify-between text-xs"><span class="font-medium text-muted">Remplissage</span><span class="font-bold text-primary">25 / 35</span></div>
              <div class="h-2 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:71%"></div></div>
            </div>
          </article>

          <article class="rounded-2xl bg-white p-5 shadow-sm">
            <h3 class="font-bold text-[#1a1b22]">L1 Réseaux</h3>
            <div class="mt-3 space-y-1 text-sm text-muted">
              <p><span class="text-[#374151]">Filière :</span> Réseaux</p>
              <p><span class="text-[#374151]">Niveau :</span> Licence 1</p>
              <p><span class="text-[#374151]">Capacité :</span> 40 étudiants</p>
            </div>
            <div class="mt-4">
              <div class="mb-1.5 flex items-center justify-between text-xs"><span class="font-medium text-muted">Remplissage</span><span class="font-bold text-primary">20 / 40</span></div>
              <div class="h-2 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:50%"></div></div>
            </div>
          </article>

        </section>
      </div>
    </main>
  </div>

  <script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleMenu = (open) => { sidebar.classList.toggle('-translate-x-full', !open); overlay.classList.toggle('hidden', !open); document.body.classList.toggle('overflow-hidden', open); };
    document.getElementById('openMenu').addEventListener('click', () => toggleMenu(true));
    document.getElementById('closeMenu').addEventListener('click', () => toggleMenu(false));
    overlay.addEventListener('click', () => toggleMenu(false));

    /* ----- Clic sur une carte -> détail de la classe (formateur) ----- */
    document.querySelectorAll('#grid article').forEach((card) => {
      card.classList.add('cursor-pointer', 'transition', 'hover:shadow-md');
      card.addEventListener('click', () => { window.location.href = 'detailsClasseFormateur.php'; });
    });
  </script>
</body>
</html>