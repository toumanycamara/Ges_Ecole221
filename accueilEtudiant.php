<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Tableau de bord (Étudiant)</title>
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
      <symbol id="i-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 21v-8M12 21V4M19 21v-12"/></symbol>
      <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
      <symbol id="i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/></symbol>
      <symbol id="i-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
      <symbol id="i-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-bell" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></symbol>
      <symbol id="i-trend" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 17 6-6 4 4 8-8"/><path d="M17 7h4v4"/></symbol>
      <symbol id="i-award" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.47 12.89 17 22l-5-3-5 3 1.53-9.11"/></symbol>
      <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
      <symbol id="i-mega" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 13v-2Z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></symbol>
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
        <a href="AccueilEtudiant.php" class="flex items-center gap-3 rounded-lg bg-white px-5 py-2.5 text-primary shadow-sm"><svg class="h-4 w-4 shrink-0"><use href="#i-grid" /></svg> Tableau de bord</a>
        <a href="noteEtudiant.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-chart" /></svg> Notes &amp; résultats</a>
        <a href="edtEtudiant.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-calendar" /></svg> Emploi du temps</a>
        <a href="profilEtudiant.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-user" /></svg> Mon profil</a>
      </nav>
    </div>
    <div class="flex flex-col items-center gap-2.5 border-t-[0.5px] border-white/25 px-4 py-6">
      <div class="flex items-center gap-2 rounded-full bg-white/15 px-4 py-1 text-xs text-white"><svg class="h-3 w-3"><use href="#i-pin" /></svg> Campus Point E · Dakar</div>
      <a href="#" class="flex w-full items-center gap-3 rounded-lg border border-white/40 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-logout" /></svg> Se déconnecter</a>
    </div>
  </aside>

  <!-- ===================== CONTENU ===================== -->
  <div class="min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[88px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-10">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <div>
          <p class="text-xs font-medium text-primary">Vendredi 2 octobre 2026</p>
          <h1 class="text-lg font-bold leading-tight">Tableau de bord</h1>
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

        <!-- Bonjour + action (bande rouge) -->
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-primary px-6 py-6 text-white shadow-sm">
          <div>
            <h2 class="text-2xl font-extrabold">Bonjour Aminata 👋</h2>
            <p class="mt-1 text-sm text-white/85">Voici l'essentiel de votre journée et votre progression ce semestre.</p>
          </div>
          <a href="edtEtudiant.php" class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-primary shadow-sm transition hover:bg-white/90">
            <svg class="h-4 w-4"><use href="#i-calendar" /></svg> Voir mon planning
          </a>
        </div>

        <!-- Cartes de synthèse -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
          <div class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
              <p class="text-sm text-muted">Progression globale</p>
              <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#fdeaec] text-primary"><svg class="h-4 w-4"><use href="#i-trend" /></svg></span>
            </div>
            <div class="mt-3 flex items-end justify-between">
              <p class="text-3xl font-extrabold text-[#1a1b22]">72%</p>
              <span class="text-xs font-semibold text-primary">+8% ce mois</span>
            </div>
          </div>
          <div class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
              <p class="text-sm text-muted">Moyenne générale</p>
              <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#fdeaec] text-primary"><svg class="h-4 w-4"><use href="#i-award" /></svg></span>
            </div>
            <div class="mt-3 flex items-end justify-between">
              <p class="text-3xl font-extrabold text-[#1a1b22]">14,8</p>
              <span class="text-xs font-semibold text-primary">+0,6 ce semestre</span>
            </div>
          </div>
        </div>

        <!-- Prochains cours -->
        <div>
          <section class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-center justify-between">
              <h3 class="font-bold text-[#1a1b22]">Prochains cours</h3>
              <a href="edtEtudiant.php" class="text-xs font-semibold text-primary hover:underline">Emploi du temps complet</a>
            </div>
            <p class="mt-2 inline-flex items-center gap-1.5 text-xs font-semibold text-primary"><svg class="h-3.5 w-3.5"><use href="#i-calendar" /></svg> Aujourd'hui · 3 cours</p>

            <div class="mt-4 flex flex-col divide-y divide-[#f1f1f3]">
              <div class="flex items-center gap-4 py-3.5">
                <div class="w-14 shrink-0 text-sm font-medium"><p class="text-[#1a1b22]">09:00</p><p class="text-xs text-muted">11:00</p></div>
                <div class="flex-1 border-l-2 border-primary pl-3"><p class="font-semibold text-[#1a1b22]">Architecture logicielle</p><p class="text-xs text-muted">M. Ndiaye</p></div>
                <span class="rounded-full bg-[#fdeaec] px-2.5 py-1 text-xs font-semibold text-primary">Salle B12</span>
              </div>
              <div class="flex items-center gap-4 py-3.5">
                <div class="w-14 shrink-0 text-sm font-medium"><p class="text-[#1a1b22]">11:30</p><p class="text-xs text-muted">13:00</p></div>
                <div class="flex-1 border-l-2 border-[#2563eb] pl-3"><p class="font-semibold text-[#1a1b22]">Développement mobile</p><p class="text-xs text-muted">Mme Fall</p></div>
                <span class="rounded-full bg-[#dbeafe] px-2.5 py-1 text-xs font-semibold text-[#2563eb]">Lab 03</span>
              </div>
              <div class="flex items-center gap-4 py-3.5">
                <div class="w-14 shrink-0 text-sm font-medium"><p class="text-[#1a1b22]">15:00</p><p class="text-xs text-muted">17:00</p></div>
                <div class="flex-1 border-l-2 border-[#7c3aed] pl-3"><p class="font-semibold text-[#1a1b22]">Anglais technique</p><p class="text-xs text-muted">M. Wilson</p></div>
                <span class="rounded-full bg-[#ede9fe] px-2.5 py-1 text-xs font-semibold text-[#7c3aed]">Salle A08</span>
              </div>
            </div>
          </section>
        </div>

      </div>
    </main>
  </div>

  <script>
    /* ----- Menu mobile ----- */
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleMenu = (open) => { sidebar.classList.toggle('-translate-x-full', !open); overlay.classList.toggle('hidden', !open); document.body.classList.toggle('overflow-hidden', open); };
    document.getElementById('openMenu').addEventListener('click', () => toggleMenu(true));
    document.getElementById('closeMenu').addEventListener('click', () => toggleMenu(false));
    overlay.addEventListener('click', () => toggleMenu(false));
  </script>
</body>
</html>