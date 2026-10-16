<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Fiche étudiant</title>
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
      <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
      <symbol id="i-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
      <symbol id="i-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></symbol>
      <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
      <symbol id="i-download" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12M7 10l5 5 5-5M5 21h14"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
      <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
      <symbol id="i-chevron-right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></symbol>
      <symbol id="i-user-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="4"/><path d="M3 21v-1a6 6 0 0 1 12 0v1"/><path d="M17 9v6M20 12h-6"/></symbol>
      <symbol id="i-history" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><path d="M12 7v5l3 2"/></symbol>
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

        <!-- Fil d'ariane + actions -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <nav class="flex items-center gap-1.5 text-xs text-muted">
            <span>Scolarité</span><svg class="h-3.5 w-3.5"><use href="#i-chevron-right" /></svg>
            <a href="etudiantSecretaire.php" class="hover:text-ink">Étudiants</a><svg class="h-3.5 w-3.5"><use href="#i-chevron-right" /></svg>
            <span class="font-semibold text-primary">Fiche étudiant</span>
          </nav>
          <div class="flex items-center gap-3">
            <a href="etudiantSecretaire.php" class="text-sm font-medium text-primary hover:underline">← Retour à la liste</a>
            <button type="button" class="flex items-center gap-2 rounded-lg border border-[#e5e7eb] bg-white px-4 py-2.5 text-sm font-medium text-[#374151] hover:bg-page"><svg class="h-4 w-4"><use href="#i-download" /></svg> Exporter PDF</button>
            <button type="button" id="btnEdit" class="flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-edit" /></svg> Modifier</button>
          </div>
        </div>

        <div>
          <h1 class="text-2xl font-bold tracking-[-0.6px] text-[#1a1b22]">Fiche de l'étudiant</h1>
          <p class="mt-1 text-xs text-muted">Détails complets du profil académique et personnel</p>
        </div>

        <!-- Carte profil -->
        <section class="rounded-2xl bg-white p-6 shadow-sm">
          <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="flex items-center gap-4">
              <img src="https://i.pravatar.cc/120?img=12" alt="Toumany Camara" class="h-16 w-16 rounded-full object-cover ring-2 ring-primary/20" />
              <div>
                <h2 class="text-xl font-bold text-[#1a1b22]">Toumany Camara</h2>
                <div class="mt-1 flex flex-wrap items-center gap-2">
                  <span class="font-mono text-xs font-bold text-primary">221-SN-2024-0045</span>
                  <span class="rounded-full border border-[#bbf7d0] bg-[#f0fdf4] px-2 py-0.5 text-xs font-medium text-[#15803d]">Inscrit</span>
                  <span class="rounded-full border border-[#bfdbfe] bg-[#eff6ff] px-2 py-0.5 text-xs font-medium text-[#1d4ed8]">Masculin</span>
                </div>
                <p class="mt-2 text-xs text-muted">Date de naissance <span class="font-semibold text-[#1f2937]">12 Mars 2001</span></p>
              </div>
            </div>
            <div class="text-right text-sm">
              <p class="text-xs text-muted">Date d'inscription</p>
              <p class="font-semibold text-[#1f2937]">15 Septembre 2024</p>
            </div>
          </div>
        </section>

        <!-- Deux colonnes d'informations -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 flex items-center gap-2 text-base font-bold text-[#1a1b22]"><svg class="h-4 w-4 text-primary"><use href="#i-user" /></svg> Informations personnelles</h3>
            <dl class="divide-y divide-[#f3f4f6] text-sm">
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Date de naissance</dt><dd class="text-right font-semibold text-[#1f2937]">12 Mars 2001</dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Lieu de naissance</dt><dd class="text-right font-semibold text-[#1f2937]">Dakar, Sénégal</dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Nationalité</dt><dd class="text-right font-semibold text-[#1f2937]">Sénégalaise</dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Téléphone</dt><dd class="text-right font-semibold text-[#1f2937]">+221 77 123 45 67</dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Email</dt><dd class="text-right font-semibold text-[#1f2937]">t.camara@ecole221.sn</dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Adresse</dt><dd class="text-right font-semibold text-[#1f2937]">25 Rue Félix Faure, Dakar</dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Personne à contacter</dt><dd class="text-right font-semibold text-[#1f2937]">Amadou Camara (Père) — +221 76 987 65 43</dd></div>
            </dl>
          </section>

          <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 flex items-center gap-2 text-base font-bold text-[#1a1b22]"><svg class="h-4 w-4 text-primary"><use href="#i-tree" /></svg> Informations académiques</h3>
            <dl class="divide-y divide-[#f3f4f6] text-sm">
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Filière</dt><dd class="text-right font-semibold text-[#1f2937]">Génie Logiciel</dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Niveau</dt><dd class="text-right font-semibold text-[#1f2937]">Licence 3</dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Classe</dt><dd class="text-right font-semibold text-[#1f2937]">L3 Génie Logiciel</dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Salle</dt><dd class="text-right font-semibold text-[#1f2937]">Salle A2</dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Année académique</dt><dd class="text-right font-semibold text-[#1f2937]">2025-2026</dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Statut d'inscription</dt><dd class="text-right font-semibold text-[#1f2937]">Confirmé</dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Moyenne générale</dt><dd class="text-right font-bold text-primary">14.5/20</dd></div>
            </dl>
          </section>
        </div>

        <!-- Historique -->
        <section class="rounded-2xl bg-white p-6 shadow-sm">
          <h3 class="mb-4 flex items-center gap-2 text-base font-bold text-[#1a1b22]"><svg class="h-4 w-4 text-primary"><use href="#i-history" /></svg> Historique des inscriptions</h3>
          <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-left text-sm">
              <thead class="border-b border-[#e5e7eb] bg-[#f9fafb]">
                <tr class="text-xs font-semibold uppercase tracking-[0.5px] text-[#6b7280]">
                  <th class="px-4 py-3 font-semibold">Année</th><th class="px-4 py-3 font-semibold">Filière</th><th class="px-4 py-3 font-semibold">Niveau</th><th class="px-4 py-3 font-semibold">Classe</th><th class="px-4 py-3 font-semibold">Statut</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-[#f3f4f6]">
                <tr><td class="px-4 py-3 font-medium">2025-2026</td><td class="px-4 py-3 text-[#374151]">Génie Logiciel</td><td class="px-4 py-3 text-[#374151]">Licence 3</td><td class="px-4 py-3 text-[#374151]">L3 Génie Logiciel</td><td class="px-4 py-3"><span class="rounded-full border border-[#bfdbfe] bg-[#eff6ff] px-2 py-0.5 text-xs font-medium text-[#1d4ed8]">Confirmé</span></td></tr>
                <tr><td class="px-4 py-3 font-medium">2024-2025</td><td class="px-4 py-3 text-[#374151]">Génie Logiciel</td><td class="px-4 py-3 text-[#374151]">Licence 2</td><td class="px-4 py-3 text-[#374151]">L2 Génie Logiciel</td><td class="px-4 py-3"><span class="rounded-full border border-[#bbf7d0] bg-[#f0fdf4] px-2 py-0.5 text-xs font-medium text-[#15803d]">Validé</span></td></tr>
                <tr><td class="px-4 py-3 font-medium">2023-2024</td><td class="px-4 py-3 text-[#374151]">Génie Logiciel</td><td class="px-4 py-3 text-[#374151]">Licence 1</td><td class="px-4 py-3 text-[#374151]">L1 Tronc Commun</td><td class="px-4 py-3"><span class="rounded-full border border-[#bbf7d0] bg-[#f0fdf4] px-2 py-0.5 text-xs font-medium text-[#15803d]">Validé</span></td></tr>
              </tbody>
            </table>
          </div>
        </section>
      </div>
    </main>
  </div>

  <!-- ============ DRAWER : MODIFIER L'ÉTUDIANT ============ -->
  <div id="drawerEdit" class="fixed inset-0 z-50 hidden">
    <div data-close class="absolute inset-0 bg-black/50"></div>
    <aside class="absolute inset-y-0 right-0 flex w-full max-w-lg translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out">
      <div class="flex items-center justify-between border-b border-[#eef0f2] px-6 py-4">
        <div class="flex items-center gap-3">
          <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-5 w-5"><use href="#i-edit" /></svg></span>
          <div>
            <h3 class="text-lg font-bold text-[#1a1b22]">Modifier l'étudiant</h3>
            <p class="text-xs text-muted">Mettez à jour les informations de l'étudiant</p>
          </div>
        </div>
        <button type="button" data-close class="rounded-lg p-1.5 text-muted hover:bg-page" aria-label="Fermer"><svg class="h-5 w-5"><use href="#i-x" /></svg></button>
      </div>

      <form id="etudiantForm" class="flex flex-1 flex-col gap-5 overflow-y-auto px-6 py-5">
        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-matricule">Matricule</label>
          <input id="e-matricule" type="text" value="221-SN-2024-0045" disabled class="w-full cursor-not-allowed rounded-lg border border-[#e5e7eb] bg-page px-3 py-2.5 text-sm text-[#374151] outline-none" />
        </div>
        <div class="flex flex-col gap-4">
          <h4 class="text-xs font-semibold uppercase tracking-wide text-[#1a1b22]">Informations personnelles</h4>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-nom">Nom</label><input id="e-nom" type="text" value="Camara" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
            <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-prenom">Prénom</label><input id="e-prenom" type="text" value="Toumany" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
          </div>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
              <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted">Sexe</label>
              <div class="flex items-center gap-6 pt-2">
                <label class="flex cursor-pointer items-center gap-2 text-sm"><input type="radio" name="e-sexe" value="Masculin" checked class="accent-primary" /> Masculin</label>
                <label class="flex cursor-pointer items-center gap-2 text-sm"><input type="radio" name="e-sexe" value="Féminin" class="accent-primary" /> Féminin</label>
              </div>
            </div>
            <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-adresse">Adresse</label><input id="e-adresse" type="text" value="25 Rue Félix Faure, Dakar" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
          </div>
        </div>
        <div class="flex flex-col gap-4">
          <h4 class="text-xs font-semibold uppercase tracking-wide text-[#1a1b22]">Informations académiques</h4>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-filiere">Filière</label>
              <select id="e-filiere" class="w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"><option selected>Génie Logiciel</option><option>Développement Web</option><option>Data Analytics</option></select></div>
            <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-classe">Classe</label>
              <select id="e-classe" class="w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"><option selected>L3 Génie Logiciel</option><option>L2 Informatique</option><option>M1 Data Science</option></select></div>
          </div>
        </div>
        <div class="flex flex-col gap-4">
          <h4 class="text-xs font-semibold uppercase tracking-wide text-[#1a1b22]">Informations de contact</h4>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-tel">Téléphone</label><input id="e-tel" type="tel" value="+221 77 123 45 67" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
            <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="e-email">Adresse e-mail</label><input id="e-email" type="email" value="t.camara@ecole221.sn" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
          </div>
        </div>
      </form>

      <div class="grid grid-cols-2 gap-3 border-t border-[#eef0f2] px-6 py-4">
        <button type="button" data-close class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="submit" form="etudiantForm" class="flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-check" /></svg> Enregistrer</button>
      </div>
    </aside>
  </div>

  <div id="toasts" class="fixed right-4 top-4 z-[60] flex w-[320px] max-w-[calc(100vw-2rem)] flex-col gap-2"></div>

  <script>
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

    /* ----- Drawer Modifier ----- */
    const drawerEdit = document.getElementById('drawerEdit');
    const drawerPanel = drawerEdit.querySelector('aside');
    const openDrawer = () => { drawerEdit.classList.remove('hidden'); requestAnimationFrame(() => drawerPanel.classList.remove('translate-x-full')); document.body.classList.add('overflow-hidden'); };
    const closeDrawer = () => { drawerPanel.classList.add('translate-x-full'); document.body.classList.remove('overflow-hidden'); setTimeout(() => drawerEdit.classList.add('hidden'), 300); };
    document.getElementById('btnEdit').addEventListener('click', openDrawer);
    drawerEdit.querySelectorAll('[data-close]').forEach((b) => b.addEventListener('click', closeDrawer));
    drawerEdit.addEventListener('click', (e) => { if (!drawerPanel.contains(e.target)) closeDrawer(); });
    document.getElementById('etudiantForm').addEventListener('submit', (e) => {
      e.preventDefault();
      closeDrawer();
      showToast('success', 'Étudiant modifié', 'Les informations ont bien été mises à jour.');
    });

    window.addEventListener('keydown', (e) => { if (e.key === 'Escape') { closeDrawer(); toggleMenu(false); } });
  </script>
</body>
</html>