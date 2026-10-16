<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Emploi du temps (Formateur)</title>
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
      <symbol id="i-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></symbol>
      <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
    </defs>
  </svg>

  <div id="overlay" class="fixed inset-0 z-30 hidden bg-black/50 lg:hidden"></div>

  <!-- ===================== SIDEBAR ===================== -->
  <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-[260px] -translate-x-full flex-col justify-between bg-primary shadow-[0_1px_4px_rgba(0,0,0,0.08)] transition-transform duration-300 lg:z-20 lg:translate-x-0">
    <div>
      <div class="flex h-16 items-center gap-3 px-6">
        <span class="flex h-11 w-10 shrink-0 items-center justify-center overflow-hidden rounded-md bg-white p-0.5 shadow-sm"><img src="assets/logo-ecole221.png" alt="Logo École 221" class="h-full w-full object-contain" /></span>
        <span class="text-xl font-semibold tracking-tight text-white">École 221</span>
        <button id="closeMenu" type="button" aria-label="Fermer le menu" class="ml-auto rounded-lg p-1.5 text-white transition hover:bg-[#B8141F] lg:hidden"><svg class="h-5 w-5"><use href="#i-x" /></svg></button>
      </div>
      <nav class="mt-1 flex flex-col gap-2 px-4 pt-4 text-sm font-medium text-white">
        <a href="accueilFormateur.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-grid" /></svg> Tableau de bord</a>
        <a href="classeFormateur.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-door" /></svg> Classes</a>
        <a href="etudiantFormateur.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-user" /></svg> Étudiants</a>
        <a href="noteFormateur.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-note" /></svg> Notes</a>
        <a href="presenceFormateur.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-clipboard" /></svg> Présences</a>
        <a href="edtFormateur.php" class="flex items-center gap-3 rounded-lg bg-white px-5 py-2.5 text-primary shadow-sm"><svg class="h-4 w-4 shrink-0"><use href="#i-calendar" /></svg> Emploi du temps</a>
      </nav>
    </div>
    <div class="flex flex-col items-center gap-2.5 border-t-[0.5px] border-white/25 px-4 py-6">
      <div class="flex items-center gap-2 rounded-full bg-white/15 px-4 py-1 text-xs text-white"><svg class="h-3 w-3"><use href="#i-pin" /></svg> Dakar, Sénégal</div>
    </div>
  </aside>

  <!-- ===================== CONTENU ===================== -->
  <div class="min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <h1 class="text-xl font-bold">Emploi du temps</h1>
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

        <section class="overflow-hidden rounded-2xl bg-white shadow-sm">

          <!-- Barre d'outils -->
          <div class="flex flex-col gap-4 border-b border-[#ececef] bg-[#faf9fb] px-5 py-4 sm:flex-row sm:items-end sm:justify-between sm:px-6">
            <div class="flex flex-col gap-1">
              <label class="text-[11px] font-semibold uppercase tracking-wide text-muted">Période</label>
              <div class="relative">
                <select class="w-full appearance-none rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-3 pr-9 text-sm text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 sm:w-64">
                  <option>Semaine du 28 Sept — 2 Oct 2026</option>
                  <option>Semaine du 5 — 9 Oct 2026</option>
                  <option>Semaine du 12 — 16 Oct 2026</option>
                </select>
                <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-chevron" /></svg></span>
              </div>
            </div>
            <div class="inline-flex rounded-lg border border-[#e5e7eb] bg-white p-1 text-sm">
              <button type="button" class="rounded-md bg-primary px-4 py-1.5 font-semibold text-white shadow-sm">Semaine</button>
              <button type="button" class="rounded-md px-4 py-1.5 font-medium text-muted transition hover:text-ink">Jour</button>
            </div>
          </div>

          <!-- Grille emploi du temps -->
          <div class="overflow-x-auto">
            <table class="w-full min-w-[860px] table-fixed border-collapse text-left">
              <thead>
                <tr class="text-[11px] uppercase tracking-wide text-muted">
                  <th class="w-[92px] border-b border-r border-[#ececef] px-3 py-3 font-semibold">Heure</th>
                  <th class="border-b border-r border-[#ececef] px-3 py-3 text-center font-semibold">Lundi</th>
                  <th class="border-b border-r border-[#ececef] px-3 py-3 text-center font-semibold">Mardi</th>
                  <th class="border-b border-r border-[#ececef] px-3 py-3 text-center font-semibold">Mercredi</th>
                  <th class="border-b border-r border-[#ececef] px-3 py-3 text-center font-semibold">Jeudi</th>
                  <th class="border-b border-[#ececef] px-3 py-3 text-center font-semibold">Vendredi</th>
                </tr>
              </thead>
              <tbody class="align-top">

                <!-- 08:00 - 10:00 -->
                <tr>
                  <td class="border-b border-r border-[#ececef] px-3 py-3 text-xs font-medium text-muted">08:00 - 10:00</td>
                  <td class="h-[120px] border-b border-r border-[#ececef] p-2">
                    <div class="rounded-xl bg-primary p-3 text-white shadow-sm">
                      <p class="text-sm font-bold leading-tight">Algorithmique Avancée</p>
                      <p class="mt-1 text-xs text-white/90">L3 Génie Logiciel</p>
                      <p class="mt-0.5 text-xs italic text-white/70">Salle A12</p>
                    </div>
                  </td>
                  <td class="border-b border-r border-[#ececef] p-2"></td>
                  <td class="border-b border-r border-[#ececef] p-2">
                    <div class="rounded-xl bg-primary p-3 text-white shadow-sm">
                      <p class="text-sm font-bold leading-tight">Algorithmique Avancée</p>
                      <p class="mt-1 text-xs text-white/90">L3 Génie Logiciel</p>
                      <p class="mt-0.5 text-xs italic text-white/70">Salle A12</p>
                    </div>
                  </td>
                  <td class="border-b border-r border-[#ececef] p-2"></td>
                  <td class="border-b border-[#ececef] p-2"></td>
                </tr>

                <!-- 10:00 - 12:00 -->
                <tr>
                  <td class="border-b border-r border-[#ececef] px-3 py-3 text-xs font-medium text-muted">10:00 - 12:00</td>
                  <td class="h-[120px] border-b border-r border-[#ececef] p-2"></td>
                  <td class="border-b border-r border-[#ececef] p-2">
                    <div class="rounded-xl bg-[#fdeaec] p-3 ring-1 ring-primary/15">
                      <p class="text-sm font-bold leading-tight text-primary">Base de Données</p>
                      <p class="mt-1 text-xs text-[#374151]">M1 Data Science</p>
                      <p class="mt-0.5 text-xs italic text-muted">Salle C09</p>
                    </div>
                  </td>
                  <td class="border-b border-r border-[#ececef] p-2"></td>
                  <td class="border-b border-r border-[#ececef] p-2"></td>
                  <td class="border-b border-[#ececef] p-2">
                    <div class="rounded-xl bg-[#fdeaec] p-3 ring-1 ring-primary/15">
                      <p class="text-sm font-bold leading-tight text-primary">Réseaux Informatiques</p>
                      <p class="mt-1 text-xs text-[#374151]">L1 Réseaux</p>
                      <p class="mt-0.5 text-xs italic text-muted">Salle D03</p>
                    </div>
                  </td>
                </tr>

                <!-- 12:00 - 14:00 : Pause -->
                <tr>
                  <td class="border-b border-r border-[#ececef] px-3 py-3 text-xs font-medium text-muted">12:00 - 14:00</td>
                  <td colspan="5" class="border-b border-[#ececef] bg-[#fafafa] px-3 py-5 text-center text-xs font-semibold uppercase tracking-wide text-muted">Pause déjeuner</td>
                </tr>

                <!-- 14:00 - 16:00 -->
                <tr>
                  <td class="border-b border-r border-[#ececef] px-3 py-3 text-xs font-medium text-muted">14:00 - 16:00</td>
                  <td class="h-[120px] border-b border-r border-[#ececef] p-2">
                    <div class="rounded-xl bg-primary p-3 text-white shadow-sm">
                      <p class="text-sm font-bold leading-tight">Structures de Données</p>
                      <p class="mt-1 text-xs text-white/90">L2 Informatique</p>
                      <p class="mt-0.5 text-xs italic text-white/70">Salle B05</p>
                    </div>
                  </td>
                  <td class="border-b border-r border-[#ececef] p-2"></td>
                  <td class="border-b border-r border-[#ececef] p-2">
                    <div class="rounded-xl bg-primary p-3 text-white shadow-sm">
                      <p class="text-sm font-bold leading-tight">TP Programmation</p>
                      <p class="mt-1 text-xs text-white/90">L3 Génie Logiciel</p>
                      <p class="mt-0.5 text-xs italic text-white/70">Labo Info 2</p>
                    </div>
                  </td>
                  <td class="border-b border-r border-[#ececef] p-2"></td>
                  <td class="border-b border-[#ececef] p-2"></td>
                </tr>

                <!-- 16:00 - 17:00 -->
                <tr>
                  <td class="border-r border-[#ececef] px-3 py-3 text-xs font-medium text-muted">16:00 - 17:00</td>
                  <td class="h-[110px] border-r border-[#ececef] p-2"></td>
                  <td class="border-r border-[#ececef] p-2"></td>
                  <td class="border-r border-[#ececef] p-2">
                    <div class="rounded-xl bg-primary p-3 text-white shadow-sm">
                      <p class="text-sm font-bold leading-tight">TP Programmation</p>
                      <p class="mt-1 text-xs text-white/90">L3 Génie Logiciel</p>
                      <p class="mt-0.5 text-xs italic text-white/70">Labo Info 2</p>
                    </div>
                  </td>
                  <td class="border-r border-[#ececef] p-2"></td>
                  <td class="p-2"></td>
                </tr>

              </tbody>
            </table>
          </div>

          <!-- Barre de synthèse -->
          <div class="flex flex-wrap items-center justify-between gap-x-6 gap-y-1 border-t border-[#ececef] bg-[#fdf2f3] px-5 py-3.5 text-sm sm:px-6">
            <span class="inline-flex items-center gap-1.5 font-semibold text-[#1a1b22]"><svg class="h-4 w-4 text-primary"><use href="#i-clock" /></svg> Total : <span class="text-primary">16 heures / semaine</span></span>
            <span class="text-muted">Prochaine séance : <span class="font-semibold text-[#1a1b22]">Lundi 08:00 — Algorithmique Avancée</span></span>
          </div>

        </section>
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