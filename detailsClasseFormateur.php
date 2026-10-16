<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Détail classe (Formateur)</title>
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
      <symbol id="i-chevron-right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></symbol>
      <symbol id="i-male" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="14" r="6"/><path d="M19 5l-6 6M15 5h4v4"/></symbol>
      <symbol id="i-female" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M12 14v7M9 18h6"/></symbol>
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
        <a href="AccueilFormateur.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-grid" /></svg> Tableau de bord</a>
        <a href="classeFormateur.php" class="flex items-center gap-3 rounded-lg bg-white px-5 py-2.5 text-primary shadow-sm"><svg class="h-4 w-4 shrink-0"><use href="#i-door" /></svg> Classes</a>
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
        <h2 class="text-sm font-semibold">Détail de la classe - Formateur</h2>
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

        <!-- Fil d'ariane + retour -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <nav class="flex items-center gap-1.5 text-xs text-muted">
            <a href="classeFormateur.php" class="hover:text-ink">Classes</a><svg class="h-3.5 w-3.5"><use href="#i-chevron-right" /></svg>
            <span class="font-semibold text-primary">Détail de la classe</span>
          </nav>
          <a href="classeFormateur.php" class="text-sm font-medium text-primary hover:underline">← Retour à mes classes</a>
        </div>

        <!-- Titre -->
        <div class="flex flex-wrap items-center gap-3">
          <h1 class="text-2xl font-bold tracking-[-0.6px] text-[#1a1b22]">L3 Génie Logiciel</h1>
          <span class="rounded-full bg-page px-2.5 py-1 text-xs font-semibold text-[#374151]">Salle A2</span>
          <span class="rounded-full bg-[#ffdad7] px-2.5 py-1 text-xs font-semibold text-primary">Année 2025-2026</span>
          <span class="rounded-full bg-page px-2.5 py-1 text-xs font-medium text-muted">Consultation</span>
        </div>

        <!-- Deux cartes -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-bold text-[#1a1b22]">Informations de la classe</h2>
            <dl class="divide-y divide-[#f3f4f6] text-sm">
              <div class="flex justify-between py-2.5"><dt class="text-muted">Filière</dt><dd class="font-medium text-[#1f2937]">Génie Logiciel</dd></div>
              <div class="flex justify-between py-2.5"><dt class="text-muted">Niveau</dt><dd class="font-medium text-[#1f2937]">Licence 3</dd></div>
              <div class="flex justify-between py-2.5"><dt class="text-muted">Salle</dt><dd class="font-medium text-[#1f2937]">A2</dd></div>
              <div class="flex justify-between py-2.5"><dt class="text-muted">Capacité</dt><dd class="font-medium text-[#1f2937]">40 places</dd></div>
              <div class="flex justify-between py-2.5"><dt class="text-muted">Inscrits</dt><dd class="font-medium text-[#1f2937]">28 étudiants</dd></div>
              <div class="flex items-center justify-between py-2.5">
                <dt class="text-muted">Taux de remplissage</dt>
                <dd class="flex items-center gap-2"><span class="h-2 w-24 overflow-hidden rounded-full bg-[#f3f4f6]"><span class="block h-full rounded-full bg-primary" style="width:70%"></span></span><span class="text-sm font-semibold text-primary">70%</span></dd>
              </div>
              <div class="flex justify-between py-2.5"><dt class="text-muted">Formateur principal</dt><dd class="font-semibold text-[#1f2937]">Dr. Mamadou Kane</dd></div>
            </dl>
          </section>

          <section class="flex flex-col rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-bold text-[#1a1b22]">Répartition des étudiants</h2>
            <div class="flex flex-1 items-center justify-center gap-12">
              <div class="flex flex-col items-center gap-2">
                <div class="flex h-20 w-20 items-center justify-center rounded-full border-[7px] border-[#2563eb] text-[#2563eb]"><svg class="h-8 w-8"><use href="#i-male" /></svg></div>
                <p class="text-sm text-muted">Garçons : <span class="font-semibold text-[#1f2937]">18</span></p>
              </div>
              <div class="flex flex-col items-center gap-2">
                <div class="flex h-20 w-20 items-center justify-center rounded-full border-[7px] border-[#db2777] text-[#db2777]"><svg class="h-8 w-8"><use href="#i-female" /></svg></div>
                <p class="text-sm text-muted">Filles : <span class="font-semibold text-[#1f2937]">10</span></p>
              </div>
            </div>
            <div class="mt-4 rounded-xl bg-[#ffdad7] px-4 py-3 text-center text-sm font-semibold text-primary">Moyenne générale de la classe : 13.2 / 20</div>
          </section>
        </div>

        <!-- Liste des étudiants (lecture seule) -->
        <section class="rounded-2xl bg-white p-6 shadow-sm">
          <h2 class="mb-4 text-lg font-bold text-[#1a1b22]">Étudiants de la classe</h2>
          <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-left text-sm">
              <thead class="border-b border-[#e5e7eb] bg-[#f9fafb]">
                <tr class="text-xs font-semibold uppercase tracking-[0.5px] text-[#6b7280]">
                  <th class="px-4 py-3 font-semibold">N°</th><th class="px-4 py-3 font-semibold">Matricule</th><th class="px-4 py-3 font-semibold">Nom</th><th class="px-4 py-3 font-semibold">Prénom</th><th class="px-4 py-3 font-semibold">Sexe</th><th class="px-4 py-3 font-semibold">Moyenne</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-[#f3f4f6]">
                <tr class="hover:bg-[#f9fafb]"><td class="px-4 py-3 text-muted">1</td><td class="px-4 py-3 font-mono text-xs font-bold text-[#111827]">221-SN-2024-0045</td><td class="px-4 py-3 font-bold">Camara</td><td class="px-4 py-3 text-[#374151]">Toumany</td><td class="px-4 py-3"><span class="rounded-full border border-[#bfdbfe] bg-[#eff6ff] px-2 py-0.5 text-xs font-medium text-[#1d4ed8]">Masculin</span></td><td class="px-4 py-3 font-medium">14.5 / 20</td></tr>
                <tr class="hover:bg-[#f9fafb]"><td class="px-4 py-3 text-muted">2</td><td class="px-4 py-3 font-mono text-xs font-bold text-[#111827]">221-SN-2024-0046</td><td class="px-4 py-3 font-bold">Sy</td><td class="px-4 py-3 text-[#374151]">Fatou</td><td class="px-4 py-3"><span class="rounded-full border border-[#fbcfe8] bg-[#fdf2f8] px-2 py-0.5 text-xs font-medium text-[#be185d]">Féminin</span></td><td class="px-4 py-3 font-medium">16.2 / 20</td></tr>
                <tr class="hover:bg-[#f9fafb]"><td class="px-4 py-3 text-muted">3</td><td class="px-4 py-3 font-mono text-xs font-bold text-[#111827]">221-SN-2024-0047</td><td class="px-4 py-3 font-bold">Diallo</td><td class="px-4 py-3 text-[#374151]">Abdoulaye</td><td class="px-4 py-3"><span class="rounded-full border border-[#bfdbfe] bg-[#eff6ff] px-2 py-0.5 text-xs font-medium text-[#1d4ed8]">Masculin</span></td><td class="px-4 py-3 font-medium">12.8 / 20</td></tr>
                <tr class="hover:bg-[#f9fafb]"><td class="px-4 py-3 text-muted">4</td><td class="px-4 py-3 font-mono text-xs font-bold text-[#111827]">221-SN-2024-0048</td><td class="px-4 py-3 font-bold">Ndiaye</td><td class="px-4 py-3 text-[#374151]">Aminata</td><td class="px-4 py-3"><span class="rounded-full border border-[#fbcfe8] bg-[#fdf2f8] px-2 py-0.5 text-xs font-medium text-[#be185d]">Féminin</span></td><td class="px-4 py-3 font-medium">11.0 / 20</td></tr>
                <tr class="hover:bg-[#f9fafb]"><td class="px-4 py-3 text-muted">5</td><td class="px-4 py-3 font-mono text-xs font-bold text-[#111827]">221-SN-2024-0049</td><td class="px-4 py-3 font-bold">Ba</td><td class="px-4 py-3 text-[#374151]">Ibrahima</td><td class="px-4 py-3"><span class="rounded-full border border-[#bfdbfe] bg-[#eff6ff] px-2 py-0.5 text-xs font-medium text-[#1d4ed8]">Masculin</span></td><td class="px-4 py-3 font-medium">15.3 / 20</td></tr>
              </tbody>
            </table>
          </div>
          <p class="mt-4 text-xs text-muted">Affichage de 1 à 5 sur 28 étudiants — consultation en lecture seule</p>
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
  </script>
</body>
</html>