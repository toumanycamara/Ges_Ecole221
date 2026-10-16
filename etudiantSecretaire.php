<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Étudiants</title>
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
      <symbol id="i-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
      <symbol id="i-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></symbol>
      <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></symbol>
      <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
      <symbol id="i-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
      <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
      <symbol id="i-filter" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M7 12h10M10 18h4"/></symbol>
      <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></symbol>
      <symbol id="i-download" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12M7 10l5 5 5-5M5 21h14"/></symbol>
      <symbol id="i-male" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="14" r="6"/><path d="M19 5l-6 6M15 5h4v4"/></symbol>
      <symbol id="i-female" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M12 14v7M9 18h6"/></symbol>
      <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
      <symbol id="i-user-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="4"/><path d="M3 21v-1a6 6 0 0 1 12 0v1"/><path d="M17 9v6M20 12h-6"/></symbol>
      <symbol id="i-chevron-right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></symbol>
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
        <a href="AccueilSecretaire.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-grid" /></svg> Tableau de bord</a>
        <a href="filiereSecretaire.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-tree" /></svg> Filières</a>
        <a href="niveauSecretaire.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-layers" /></svg> Niveaux</a>
        <a href="classeSecretaire.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-door" /></svg> Classes</a>
        <a href="etudiantSecretaire.php" class="flex items-center gap-3 rounded-lg bg-white px-5 py-2.5 text-primary shadow-sm"><svg class="h-4 w-4 shrink-0"><use href="#i-user" /></svg> Étudiants</a>
        <a href="formateurSecretaire.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-users" /></svg> Formateurs</a>
        <a href="edtSecretaire.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F] hover:shadow-[6px_0_12px_-2px_rgba(0,0,0,0.35)]"><svg class="h-4 w-4 shrink-0"><use href="#i-calendar" /></svg> Emploi du temps</a>
        <a href="statistiqueSecretaire.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-chart" /></svg> Statistiques</a>
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
        <h2 class="text-sm font-semibold">Tableau de bord - Secrétaire</h2>
      </div>
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
          <img src="https://i.pravatar.cc/64?img=47" alt="Photo" class="h-8 w-8 rounded-full object-cover" />
          <div class="hidden sm:block"><p class="text-sm font-semibold leading-[17.5px]">Moussa Diop</p><p class="text-xs leading-3 text-muted">m.diop@ecole221.sn</p></div>
        </div>
        <span class="hidden h-6 w-px bg-[#e3e1ec] sm:block"></span>
        <span class="whitespace-nowrap rounded-full bg-primary px-2 py-1 text-xs font-semibold text-white shadow-sm">Secrétaire / Admin</span>
      </div>
    </header>

    <main class="px-4 py-8 sm:px-6 lg:px-12">
      <div class="mx-auto flex max-w-[1180px] flex-col gap-6">

        <!-- En-tête -->
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <nav class="flex items-center gap-1.5 text-xs text-muted">
              <span>Scolarité</span><svg class="h-3.5 w-3.5"><use href="#i-chevron-right" /></svg>
              <span class="font-semibold text-primary">Étudiants</span>
            </nav>
            <h1 class="mt-1 text-2xl font-bold leading-8 tracking-[-0.6px] text-[#1a1b22]">Liste des étudiants</h1>
            <p class="mt-1 text-xs text-muted">Annuaire officiel des apprenants inscrits à l'École 221</p>
          </div>
          <div class="flex items-center gap-3">
            <button type="button" class="flex items-center gap-2 rounded-lg border border-[#e5e7eb] bg-white px-4 py-2.5 text-sm font-medium text-[#374151] hover:bg-page"><svg class="h-4 w-4"><use href="#i-download" /></svg> Exporter</button>
            <button type="button" id="btnAdd" class="flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark"><svg class="h-3.5 w-3.5"><use href="#i-plus" /></svg> Ajouter un Étudiant</button>
          </div>
        </div>

        <!-- Cartes stats -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
          <div class="flex items-center justify-between rounded-xl bg-white p-5 shadow-sm">
            <div><p class="text-xs text-muted">Total Inscrits</p><p class="mt-1 text-3xl font-bold text-[#1a1b22]">640</p></div>
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-5 w-5"><use href="#i-users" /></svg></span>
          </div>
          <div class="flex items-center justify-between rounded-xl bg-white p-5 shadow-sm">
            <div><p class="text-xs text-muted">Féminin</p><p class="mt-1 text-3xl font-bold text-[#1a1b22]">294</p></div>
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#fdf2f8] text-[#db2777]"><svg class="h-5 w-5"><use href="#i-female" /></svg></span>
          </div>
          <div class="flex items-center justify-between rounded-xl bg-white p-5 shadow-sm">
            <div><p class="text-xs text-muted">Masculin</p><p class="mt-1 text-3xl font-bold text-[#1a1b22]">346</p></div>
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#eff6ff] text-[#2563eb]"><svg class="h-5 w-5"><use href="#i-male" /></svg></span>
          </div>
          <div class="flex items-center justify-between rounded-xl bg-white p-5 shadow-sm">
            <div><p class="text-xs text-muted">Année académique</p><p class="mt-1 text-2xl font-bold text-[#1a1b22]">2025-2026</p></div>
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-5 w-5"><use href="#i-calendar" /></svg></span>
          </div>
        </div>

        <!-- Panneau tableau -->
        <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">
          <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
              <h2 class="font-bold text-[#1a1b22]">Effectif répertorié</h2>
              <span id="countBadge" class="rounded-full bg-primary px-2.5 py-0.5 text-xs font-semibold text-white">640 étudiants enregistrés</span>
            </div>
            <div class="flex items-center gap-2">
              <div class="relative">
                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-search" /></svg></span>
                <input type="text" placeholder="Rechercher par nom, matricule…" class="w-56 rounded-lg border border-[#e5e7eb] py-2.5 pl-9 pr-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
              </div>
              <select class="rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm text-[#374151] outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                <option>Toutes les filières</option>
                <option>Génie Logiciel</option>
                <option>Développement Web</option>
                <option>Data Analytics</option>
              </select>
              <button type="button" class="rounded-lg border border-[#e5e7eb] p-2.5 text-muted hover:bg-page" aria-label="Filtres"><svg class="h-4 w-4"><use href="#i-filter" /></svg></button>
            </div>
          </div>

          <div class="mt-5 overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
              <thead class="border-b border-[#e5e7eb]">
                <tr class="text-xs font-semibold uppercase tracking-[0.5px] text-[#6b7280]">
                  <th class="px-4 py-3 font-semibold">Matricule</th>
                  <th class="px-4 py-3 font-semibold">Nom</th>
                  <th class="px-4 py-3 font-semibold">Prénom</th>
                  <th class="px-4 py-3 font-semibold">Sexe</th>
                  <th class="px-4 py-3 font-semibold">Classe</th>
                  <th class="px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
              </thead>
              <tbody id="tbody" class="divide-y divide-[#f3f4f6]"></tbody>
            </table>
          </div>

          <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
            <p class="text-xs text-muted">Affichage de 1 à 8 sur 640 apprenants</p>
            <div class="flex items-center gap-1.5">
              <button class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-sm font-medium text-white">1</button>
              <button class="flex h-8 w-8 items-center justify-center rounded-lg border border-[#e5e7eb] text-sm font-medium text-[#374151] hover:bg-page">2</button>
              <span class="px-1 text-muted">…</span>
              <button class="flex h-8 w-8 items-center justify-center rounded-lg border border-[#e5e7eb] text-sm font-medium text-[#374151] hover:bg-page">3</button>
              <button class="flex h-8 w-8 items-center justify-center rounded-lg border border-[#e5e7eb] text-[#374151] hover:bg-page"><svg class="h-4 w-4"><use href="#i-chevron-right" /></svg></button>
            </div>
          </div>
        </section>
      </div>
    </main>
  </div>

  <!-- ============ DRAWER : AJOUTER / MODIFIER UN ÉTUDIANT ============ -->
  <div id="drawerAdd" class="fixed inset-0 z-50 hidden">
    <div data-close class="absolute inset-0 bg-black/50"></div>
    <aside class="absolute inset-y-0 right-0 flex w-full max-w-lg translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out">
      <div class="flex items-center justify-between border-b border-[#eef0f2] px-6 py-4">
        <div class="flex items-center gap-3">
          <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-5 w-5"><use href="#i-user-plus" /></svg></span>
          <div>
            <h3 id="drawerTitle" class="text-lg font-bold text-[#1a1b22]">Ajouter un étudiant</h3>
            <p class="text-xs text-muted">Remplissez les informations académiques et personnelles</p>
          </div>
        </div>
        <button type="button" data-close class="rounded-lg p-1.5 text-muted hover:bg-page" aria-label="Fermer"><svg class="h-5 w-5"><use href="#i-x" /></svg></button>
      </div>

      <form id="etudiantForm" class="flex flex-1 flex-col gap-5 overflow-y-auto px-6 py-5">
        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-matricule">Matricule</label>
          <input id="e-matricule" type="text" value="221-SN-2024-0520" disabled class="w-full cursor-not-allowed rounded-lg border border-[#e5e7eb] bg-page px-3 py-2.5 text-sm text-[#374151] outline-none" />
        </div>
        <div class="flex flex-col gap-4">
          <h4 class="text-xs font-semibold uppercase tracking-wide text-[#1a1b22]">Informations personnelles</h4>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-nom">Nom</label><input id="e-nom" type="text" placeholder="Nom de famille" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
            <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-prenom">Prénom</label><input id="e-prenom" type="text" placeholder="Prénom(s)" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
          </div>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
              <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted">Sexe</label>
              <div class="flex items-center gap-6 pt-2">
                <label class="flex cursor-pointer items-center gap-2 text-sm"><input type="radio" name="e-sexe" value="Masculin" checked class="accent-primary" /> Masculin</label>
                <label class="flex cursor-pointer items-center gap-2 text-sm"><input type="radio" name="e-sexe" value="Féminin" class="accent-primary" /> Féminin</label>
              </div>
            </div>
            <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-adresse">Adresse</label><input id="e-adresse" type="text" placeholder="Scat Urbam villa E/55" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
          </div>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-naissance">Date de naissance</label><input id="e-naissance" type="text" placeholder="JJ/MM/AAAA" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
            <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-lieu">Lieu de naissance</label><input id="e-lieu" type="text" placeholder="Ville de naissance" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
          </div>
        </div>
        <div class="flex flex-col gap-4">
          <h4 class="text-xs font-semibold uppercase tracking-wide text-[#1a1b22]">Informations académiques</h4>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-filiere">Filière</label>
              <select id="e-filiere" class="w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm text-muted outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"><option value="">Sélectionner une filière</option><option>Génie Logiciel</option><option>Développement Web</option><option>Data Analytics</option></select></div>
            <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-niveau">Niveau</label>
              <select id="e-niveau" class="w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm text-muted outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"><option value="">Sélectionner un niveau</option><option>L1</option><option>L2</option><option>L3</option><option>M1</option><option>M2</option></select></div>
          </div>
          <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-classe">Classe</label>
            <select id="e-classe" class="w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm text-muted outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"><option value="">Sélectionner une classe</option><option>L3 Génie Logiciel</option><option>L2 Informatique</option><option>M1 Data Science</option></select></div>
        </div>
        <div class="flex flex-col gap-4">
          <h4 class="text-xs font-semibold uppercase tracking-wide text-[#1a1b22]">Informations de contact</h4>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-tel">Téléphone</label><input id="e-tel" type="tel" placeholder="+221771234567" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
            <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-email">Adresse e-mail</label><input id="e-email" type="email" placeholder="toumanycamara@ecole221.sn" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
          </div>
        </div>
      </form>

      <div class="grid grid-cols-2 gap-3 border-t border-[#eef0f2] px-6 py-4">
        <button type="button" data-close class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="submit" form="etudiantForm" class="flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-check" /></svg> Enregistrer</button>
      </div>
    </aside>
  </div>

  <!-- ============ MODALE : CONFIRMER SUPPRESSION ============ -->
  <div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-xl">
      <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-7 w-7"><use href="#i-trash" /></svg></div>
      <h3 class="text-lg font-bold text-[#1a1b22]">Confirmer la suppression</h3>
      <p class="mt-2 text-sm text-muted">Voulez-vous vraiment supprimer l'étudiant <span id="delName" class="font-semibold text-[#1a1b22]"></span> ? Cette action est irréversible.</p>
      <div class="mt-6 flex items-center justify-center gap-3">
        <button type="button" data-cancel-delete class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="button" id="confirmDelete" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark">Supprimer</button>
      </div>
    </div>
  </div>

  <div id="toasts" class="fixed right-4 top-4 z-[60] flex w-[320px] max-w-[calc(100vw-2rem)] flex-col gap-2"></div>

  <script>
    /* ----- Menu mobile ----- */
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleMenu = (open) => {
      sidebar.classList.toggle('-translate-x-full', !open);
      overlay.classList.toggle('hidden', !open);
      document.body.classList.toggle('overflow-hidden', open);
    };
    document.getElementById('openMenu').addEventListener('click', () => toggleMenu(true));
    document.getElementById('closeMenu').addEventListener('click', () => toggleMenu(false));
    overlay.addEventListener('click', () => toggleMenu(false));

    /* ----- Données d'exemple ----- */
    const etudiants = [
      ['221-SN-2024-0045', 'Camara', 'Toumany', 'Masculin', 'L3 Génie Logiciel'],
      ['221-SN-2024-0046', 'Sy', 'Fatou', 'Féminin', 'L3 Génie Logiciel'],
      ['221-SN-2024-0047', 'Camara', 'Toumany', 'Masculin', 'L3 Génie Logiciel'],
      ['221-SN-2024-0048', 'Mbodji', 'Anta', 'Féminin', 'L3 Génie Logiciel'],
      ['221-SN-2024-0049', 'Camara', 'Toumany', 'Masculin', 'L3 Génie Logiciel'],
      ['221-SN-2024-0050', 'Mbodji', 'Anta', 'Féminin', 'L3 Génie Logiciel'],
      ['221-SN-2024-0051', 'Camara', 'Toumany', 'Masculin', 'L3 Génie Logiciel'],
      ['221-SN-2024-0052', 'Mbodji', 'Anta', 'Féminin', 'L3 Génie Logiciel'],
    ];
    const badgeSexe = (s) => s === 'Masculin'
      ? '<span class="rounded-full border border-[#bfdbfe] bg-[#eff6ff] px-2 py-0.5 text-xs font-medium text-[#1d4ed8]">Masculin</span>'
      : '<span class="rounded-full border border-[#fbcfe8] bg-[#fdf2f8] px-2 py-0.5 text-xs font-medium text-[#be185d]">Féminin</span>';

    const tbody = document.getElementById('tbody');
    etudiants.forEach((e) => {
      const tr = document.createElement('tr');
      tr.className = 'cursor-pointer transition-colors hover:bg-[#f9fafb]';
      tr.innerHTML =
        '<td class="px-4 py-4 font-mono text-xs font-bold text-[#111827]">' + e[0] + '</td>' +
        '<td class="px-4 py-4 font-bold">' + e[1] + '</td>' +
        '<td class="px-4 py-4 text-[#374151]">' + e[2] + '</td>' +
        '<td class="px-4 py-4">' + badgeSexe(e[3]) + '</td>' +
        '<td class="px-4 py-4 font-medium text-[#1f2937]">' + e[4] + '</td>' +
        '<td class="px-4 py-3"><div class="flex items-center justify-end gap-1.5">' +
          '<button class="js-edit rounded-lg p-1.5 text-primary hover:bg-primary hover:text-white" aria-label="Modifier"><svg class="h-4 w-4"><use href="#i-edit"/></svg></button>' +
          '<button class="js-del rounded-lg p-1.5 text-[#6b7280] hover:bg-primary hover:text-white" aria-label="Supprimer"><svg class="h-4 w-4"><use href="#i-trash"/></svg></button>' +
        '</div></td>';
      tr.addEventListener('click', () => { window.location.href = 'detailsEtudiantSecretaire.php'; });
      tbody.appendChild(tr);
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

    /* ----- Drawer ajout / modification ----- */
    const drawerAdd = document.getElementById('drawerAdd');
    const drawerPanel = drawerAdd.querySelector('aside');
    const drawerTitle = document.getElementById('drawerTitle');
    const etudiantForm = document.getElementById('etudiantForm');
    const requis = ['e-nom', 'e-prenom'];
    let modeDrawer = 'add';
    const clearErrors = () => requis.forEach((id) => document.getElementById(id).classList.remove('border-primary', 'ring-2', 'ring-primary/30'));
    const openDrawer = () => { drawerAdd.classList.remove('hidden'); requestAnimationFrame(() => drawerPanel.classList.remove('translate-x-full')); document.body.classList.add('overflow-hidden'); };
    const closeDrawer = () => { drawerPanel.classList.add('translate-x-full'); document.body.classList.remove('overflow-hidden'); setTimeout(() => drawerAdd.classList.add('hidden'), 300); };

    document.getElementById('btnAdd').addEventListener('click', () => {
      modeDrawer = 'add';
      drawerTitle.textContent = 'Ajouter un étudiant';
      etudiantForm.reset();
      document.getElementById('e-matricule').value = '221-SN-2024-0520';
      clearErrors();
      openDrawer();
    });
    drawerAdd.querySelectorAll('[data-close]').forEach((b) => b.addEventListener('click', closeDrawer));
    drawerAdd.addEventListener('click', (e) => { if (!drawerPanel.contains(e.target)) closeDrawer(); });

    etudiantForm.addEventListener('submit', (e) => {
      e.preventDefault();
      clearErrors();
      let ok = true;
      requis.forEach((id) => { const f = document.getElementById(id); if (!f.value.trim()) { f.classList.add('border-primary', 'ring-2', 'ring-primary/30'); ok = false; } });
      if (!ok) { showToast('error', 'Champs obligatoires', 'Le nom et le prénom sont requis.'); return; }
      closeDrawer();
      showToast('success', modeDrawer === 'edit' ? 'Étudiant modifié' : 'Étudiant ajouté', modeDrawer === 'edit' ? 'Les informations ont bien été mises à jour.' : 'L\'étudiant a bien été enregistré.');
    });

    /* ----- Modifier depuis une ligne ----- */
    tbody.querySelectorAll('.js-edit').forEach((btn) => {
      btn.addEventListener('click', (ev) => {
        ev.stopPropagation();
        const cells = btn.closest('tr').children;
        modeDrawer = 'edit';
        drawerTitle.textContent = 'Modifier l\'étudiant';
        etudiantForm.reset();
        clearErrors();
        document.getElementById('e-matricule').value = cells[0].textContent.trim();
        document.getElementById('e-nom').value = cells[1].textContent.trim();
        document.getElementById('e-prenom').value = cells[2].textContent.trim();
        const sexe = cells[3].textContent.trim();
        document.querySelectorAll('input[name="e-sexe"]').forEach((r) => { r.checked = (r.value === sexe); });
        document.getElementById('e-classe').value = cells[4].textContent.trim();
        openDrawer();
      });
    });

    /* ----- Suppression ----- */
    const modalDelete = document.getElementById('modalDelete');
    const openModal = () => { modalDelete.classList.remove('hidden'); modalDelete.classList.add('flex'); };
    const closeModal = () => { modalDelete.classList.add('hidden'); modalDelete.classList.remove('flex'); };
    let rowToDelete = null;
    tbody.querySelectorAll('.js-del').forEach((btn) => {
      btn.addEventListener('click', (ev) => {
        ev.stopPropagation();
        rowToDelete = btn.closest('tr');
        const c = rowToDelete.children;
        document.getElementById('delName').textContent = c[2].textContent.trim() + ' ' + c[1].textContent.trim();
        openModal();
      });
    });
    document.querySelectorAll('[data-cancel-delete]').forEach((b) => b.addEventListener('click', () => { closeModal(); showToast('info', 'Suppression annulée'); }));
    modalDelete.addEventListener('click', (e) => { if (e.target === modalDelete) closeModal(); });
    document.getElementById('confirmDelete').addEventListener('click', () => {
      if (!rowToDelete) { closeModal(); return; }
      const row = rowToDelete; const parent = row.parentNode; const next = row.nextElementSibling;
      row.remove(); rowToDelete = null; closeModal();
      showToast('success', 'Étudiant supprimé', 'L\'étudiant a bien été retiré de la liste.', () => {
        if (next) parent.insertBefore(row, next); else parent.appendChild(row);
        showToast('info', 'Suppression annulée', 'L\'étudiant a été restauré.');
      });
    });

    window.addEventListener('keydown', (e) => { if (e.key === 'Escape') { closeDrawer(); closeModal(); toggleMenu(false); } });
  </script>
</body>
</html>