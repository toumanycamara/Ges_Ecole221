<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Tableau de bord Formateur</title>
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
      <symbol id="i-tree" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="6" height="6"/><rect x="15" y="9" width="6" height="6"/><rect x="3" y="15" width="6" height="6"/><path d="M9 6h3v12H9M12 12h3"/></symbol>
      <symbol id="i-layers" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 2 10 5-10 5L2 7l10-5Z"/><path d="m2 17 10 5 10-5M2 12l10 5 10-5"/></symbol>
      <symbol id="i-door" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 21V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17M3 21h18M14 12v.01"/></symbol>
      <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M2 21v-1a6 6 0 0 1 12 0v1M16 4.5a3.5 3.5 0 0 1 0 7M22 21v-1a6 6 0 0 0-4-5.6"/></symbol>
      <symbol id="i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/></symbol>
      <symbol id="i-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 21v-8M12 21V4M19 21v-12"/></symbol>
      <symbol id="i-note" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h4"/></symbol>
      <symbol id="i-clipboard" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="8" y="3" width="8" height="4" rx="1"/><path d="M8 5H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><path d="m9 14 2 2 4-4"/></symbol>
      <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
      <symbol id="i-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
      <symbol id="i-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-trend" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17l6-6 4 4 7-7"/><path d="M17 8h4v4"/></symbol>
      <symbol id="i-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></symbol>
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
        <a href="accueilFormateur.php" class="flex items-center gap-3 rounded-lg bg-white px-5 py-2.5 text-primary shadow-sm"><svg class="h-4 w-4 shrink-0"><use href="#i-grid" /></svg> Tableau de bord</a>
        <a href="classeFormateur.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-door" /></svg> Classes</a>
        <a href="etudiantFormateur.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-user" /></svg> Étudiants</a>
        <a href="noteFormateur.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-note" /></svg> Notes</a>
        <a href="presenceFormateur.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-clipboard" /></svg> Présences</a>
        <a href="edtFormateur.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-calendar" /></svg> Emploi du temps</a>
      </nav>
    </div>
    <div class="flex flex-col items-center gap-2.5 border-t-[0.5px] border-white/25 px-4 py-6">
      <div class="flex items-center gap-2 rounded-full bg-white/15 px-4 py-1 text-xs text-white"><svg class="h-3 w-3"><use href="#i-pin" /></svg> Dakar, Sénégal</div>
      <a href="loginEcole221.php" class="flex w-full items-center gap-3 rounded-lg px-5 py-2.5 text-sm font-medium text-white transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-logout" /></svg> Se déconnecter</a>
    </div>
  </aside>

  <div class="min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <h2 class="text-sm font-semibold">Tableau de bord - Formateur</h2>
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

        <!-- Bandeau de bienvenue -->
        <section class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#e11d2a] via-[#b91c1c] to-[#991b1b] p-8 shadow-md">
          <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/5 blur-xl"></div>
          <div class="relative flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
            <div class="flex flex-col gap-1.5">
              <h1 class="text-3xl font-bold leading-[44px] tracking-[-0.9px] text-white sm:text-4xl">Bonjour Mamadou, bienvenue sur<br />École 221</h1>
              <p class="text-base text-[#f4f4f5]">Espace enseignant — vos classes et vos étudiants</p>
            </div>
            <div class="rounded-xl bg-black/10 px-6 py-3.5 text-right backdrop-blur-[2px]">
              <p class="text-xl font-bold leading-7 text-white">Samedi 27 Septembre 2026</p>
              <p class="text-sm font-medium tracking-[0.35px] text-white/85">Année 2025-2026</p>
            </div>
          </div>
        </section>

        <!-- 4 cartes (cadrées sur le formateur) -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
          <div class="rounded-xl bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between"><span class="text-[11px] font-semibold uppercase tracking-wide text-muted">Mes étudiants</span><span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-users" /></svg></span></div>
            <p class="mt-3 text-3xl font-bold text-[#1a1b22]">75</p><p class="text-xs text-muted">Dans mes classes</p>
          </div>
          <div class="rounded-xl bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between"><span class="text-[11px] font-semibold uppercase tracking-wide text-muted">Mes classes</span><span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-door" /></svg></span></div>
            <p class="mt-3 text-3xl font-bold text-[#1a1b22]">3</p><p class="text-xs text-muted">Groupes qui me sont assignés</p>
          </div>
          <div class="rounded-xl bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between"><span class="text-[11px] font-semibold uppercase tracking-wide text-muted">Mes filières</span><span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-tree" /></svg></span></div>
            <p class="mt-3 text-3xl font-bold text-[#1a1b22]">2</p><p class="text-xs text-muted">Génie Logiciel, Data Science</p>
          </div>
          <div class="rounded-xl bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between"><span class="text-[11px] font-semibold uppercase tracking-wide text-muted">Mes séances</span><span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-calendar" /></svg></span></div>
            <p class="mt-3 text-3xl font-bold text-[#1a1b22]">12</p><p class="text-xs text-muted">Cours cette semaine</p>
          </div>
        </div>

        <!-- Deux panneaux -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

          <!-- Mes classes -->
          <section class="flex flex-col gap-5 rounded-2xl bg-white p-6 shadow-sm">
            <div>
              <h3 class="text-base font-bold text-[#1a1b22]">Mes classes</h3>
              <p class="text-xs text-muted">Effectifs des groupes qui me sont assignés</p>
            </div>
            <div class="flex flex-col gap-4">
              <div><div class="mb-1.5 flex items-center justify-between text-sm"><span class="font-semibold text-[#1a1b22]">L3 Génie Logiciel</span><span class="text-sm font-bold text-primary">28 / 40</span></div><div class="h-2.5 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:70%"></div></div></div>
              <div><div class="mb-1.5 flex items-center justify-between text-sm"><span class="font-semibold text-[#1a1b22]">M1 Data Science</span><span class="text-sm font-bold text-primary">25 / 40</span></div><div class="h-2.5 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:62.5%"></div></div></div>
              <div><div class="mb-1.5 flex items-center justify-between text-sm"><span class="font-semibold text-[#1a1b22]">M2 Intelligence Artificielle</span><span class="text-sm font-bold text-primary">22 / 40</span></div><div class="h-2.5 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:55%"></div></div></div>
            </div>
            <div class="flex items-center gap-3 rounded-xl bg-page p-3.5 text-xs text-muted">
              <svg class="h-[17px] w-[17px] shrink-0"><use href="#i-info" /></svg>
              <p>Capacité maximale standard : 40 places par salle</p>
            </div>
          </section>

          <!-- Répartition de mes étudiants -->
          <section class="flex flex-col gap-5 rounded-2xl bg-white p-6 shadow-sm">
            <div>
              <h3 class="text-base font-bold text-[#1a1b22]">Répartition de mes étudiants</h3>
              <p class="text-xs text-muted">Par classe que j'encadre</p>
            </div>
            <div class="flex flex-col gap-4">
              <div><div class="mb-1.5 flex items-center justify-between text-sm"><span class="font-medium text-[#1f2937]">L3 Génie Logiciel</span><span class="font-bold text-[#1a1b22]">28</span></div><div class="h-2.5 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:100%"></div></div></div>
              <div><div class="mb-1.5 flex items-center justify-between text-sm"><span class="font-medium text-[#1f2937]">M1 Data Science</span><span class="font-bold text-[#1a1b22]">25</span></div><div class="h-2.5 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:89%"></div></div></div>
              <div><div class="mb-1.5 flex items-center justify-between text-sm"><span class="font-medium text-[#1f2937]">M2 Intelligence Artificielle</span><span class="font-bold text-[#1a1b22]">22</span></div><div class="h-2.5 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:78%"></div></div></div>
            </div>
            <div class="flex items-center gap-2 text-xs text-muted">
              <svg class="h-4 w-4 text-primary"><use href="#i-trend" /></svg>
              <p>Total : 75 étudiants — consultation en lecture seule</p>
            </div>
          </section>
        </div>
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
  </script>
</body>
</html>