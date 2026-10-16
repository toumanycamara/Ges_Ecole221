<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Mon profil (Étudiant)</title>
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
      <symbol id="i-camera" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2Z"/><circle cx="12" cy="13" r="4"/></symbol>
      <symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></symbol>
      <symbol id="i-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></symbol>
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
        <a href="edtEtudiant.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-calendar" /></svg> Emploi du temps</a>
        <a href="profilEtudiant.php" class="flex items-center gap-3 rounded-lg bg-white px-5 py-2.5 text-primary shadow-sm"><svg class="h-4 w-4 shrink-0"><use href="#i-user" /></svg> Mon profil</a>
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
          <p class="text-[11px] font-semibold uppercase tracking-wide text-primary">Compte étudiant</p>
          <h1 class="text-lg font-bold leading-tight">Mon profil</h1>
        </div>
      </div>
      <div class="flex items-center gap-3 sm:gap-4">
        <div class="flex items-center gap-2">
          <span id="avatarNav" class="flex h-9 w-9 items-center justify-center overflow-hidden rounded-full bg-[#fdeaec] text-xs font-bold text-primary">AM</span>
          <div class="hidden sm:block"><p class="text-sm font-semibold leading-[17.5px]">Aminata Mbaye</p><p class="text-xs leading-3 text-muted">L3 Génie Logiciel</p></div>
        </div>
        <span class="whitespace-nowrap rounded-full bg-primary px-2.5 py-1 text-xs font-semibold text-white shadow-sm">Étudiante</span>
      </div>
    </header>

    <main class="px-4 py-7 sm:px-6 lg:px-10">
      <div class="mx-auto flex max-w-[1180px] flex-col gap-6">

        <!-- Carte profil -->
        <section class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm sm:p-6">
          <div class="flex flex-col items-center gap-4 sm:flex-row sm:items-center">
            <div class="relative shrink-0">
              <span id="avatarMain" class="flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-[#fdeaec] text-2xl font-bold text-primary ring-2 ring-[#fdeaec]">AM</span>
              <input id="photoInput" type="file" accept="image/*" class="hidden" />
            </div>
            <div class="flex-1 text-center sm:text-left">
              <div class="flex flex-wrap items-center justify-center gap-2 sm:justify-start">
                <h2 class="text-xl font-extrabold text-[#1a1b22]">Aminata Mbaye</h2>
                <span class="rounded-full bg-[#dcfce7] px-2.5 py-0.5 text-xs font-semibold text-[#15803d]">Compte actif</span>
              </div>
              <p class="mt-1 text-sm text-muted">aminata.mbaye@etudiant.ecole221.sn · +221 77 458 20 14</p>
              <p class="mt-0.5 text-sm"><span class="font-semibold text-primary">Matricule 221-SN-2024-0048</span> <span class="text-muted">· Campus Point E - Dakar</span></p>
            </div>
            <button id="btnChangePhoto" type="button" class="inline-flex items-center gap-2 rounded-lg border border-primary px-4 py-2.5 text-sm font-semibold text-primary transition hover:bg-primary hover:text-white">
              <svg class="h-4 w-4"><use href="#i-camera" /></svg> Modifier la photo
            </button>
          </div>
        </section>

        <!-- Onglets -->
        <div class="flex gap-6 border-b border-[#ececef] text-sm font-medium">
          <button data-tab="perso" class="js-tab -mb-px border-b-2 border-primary px-1 pb-3 font-semibold text-primary">Informations personnelles</button>
          <button data-tab="parcours" class="js-tab -mb-px border-b-2 border-transparent px-1 pb-3 text-muted transition hover:text-ink">Parcours académique</button>
          <button data-tab="compte" class="js-tab -mb-px border-b-2 border-transparent px-1 pb-3 text-muted transition hover:text-ink">Paramètres du compte</button>
        </div>

        <!-- Grille principale -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-[1.7fr_1fr]">

          <!-- Colonne gauche : contenu des onglets -->
          <div>
            <!-- Onglet : Informations personnelles -->
            <section data-panel="perso" class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm sm:p-6">
              <div class="flex flex-wrap items-start justify-between gap-2">
                <div>
                  <h3 class="font-bold text-[#1a1b22]">Informations personnelles</h3>
                  <p class="mt-0.5 text-xs text-muted">Ces informations figurent sur vos documents académiques.</p>
                </div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#dcfce7] px-2.5 py-1 text-xs font-semibold text-[#15803d]"><svg class="h-3.5 w-3.5"><use href="#i-check" /></svg> Vérifiées</span>
              </div>

              <form class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <label class="block"><span class="text-xs font-medium text-muted">Prénom</span>
                  <input type="text" value="Aminata" class="mt-1 w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></label>
                <label class="block"><span class="text-xs font-medium text-muted">Nom</span>
                  <input type="text" value="Mbaye" class="mt-1 w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></label>
                <label class="block"><span class="text-xs font-medium text-muted">Adresse e-mail</span>
                  <input type="email" value="aminata.mbaye@etudiant.ecole221.sn" class="mt-1 w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></label>
                <label class="block"><span class="text-xs font-medium text-muted">Téléphone</span>
                  <input type="tel" value="+221 77 458 20 14" class="mt-1 w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></label>
                <label class="block"><span class="text-xs font-medium text-muted">Date de naissance</span>
                  <input type="text" value="14 mars 2003" class="mt-1 w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></label>
                <label class="block"><span class="text-xs font-medium text-muted">Ville de résidence</span>
                  <input type="text" value="Dakar, Sénégal" class="mt-1 w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></label>
                <label class="block"><span class="text-xs font-medium text-muted">Contact d'urgence</span>
                  <input type="text" value="Fatou Mbaye · +221 76 122 90 11" class="mt-1 w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></label>
                <label class="block"><span class="text-xs font-medium text-muted">Adresse</span>
                  <input type="text" value="Mermoz, Dakar" class="mt-1 w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></label>
              </form>

              <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-[#ececef] pt-4">
                <p class="text-xs text-muted">Dernière mise à jour · 12 septembre 2026</p>
                <div class="flex gap-2">
                  <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-[#e5e7eb] px-4 py-2 text-sm font-medium text-[#374151] transition hover:bg-page"><svg class="h-4 w-4"><use href="#i-x" /></svg> Annuler</button>
                  <button id="btnSave" type="button" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-check" /></svg> Enregistrer</button>
                </div>
              </div>
            </section>

            <!-- Onglet : Parcours académique -->
            <section data-panel="parcours" class="hidden rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm sm:p-6">
              <h3 class="font-bold text-[#1a1b22]">Parcours académique</h3>
              <dl class="mt-4 flex flex-col divide-y divide-[#f1f1f3] text-sm">
                <div class="flex items-center justify-between gap-4 py-3"><dt class="text-muted">Filière</dt><dd class="font-medium text-[#1a1b22]">Génie Logiciel</dd></div>
                <div class="flex items-center justify-between gap-4 py-3"><dt class="text-muted">Niveau</dt><dd class="font-medium text-[#1a1b22]">Licence 3 · Semestre 5</dd></div>
                <div class="flex items-center justify-between gap-4 py-3"><dt class="text-muted">Classe</dt><dd class="font-medium text-[#1a1b22]">L3 Génie Logiciel</dd></div>
                <div class="flex items-center justify-between gap-4 py-3"><dt class="text-muted">Année d'admission</dt><dd class="font-medium text-[#1a1b22]">2024</dd></div>
                <div class="flex items-center justify-between gap-4 py-3"><dt class="text-muted">Moyenne générale</dt><dd class="font-medium text-[#1a1b22]">14,8 / 20</dd></div>
                <div class="flex items-center justify-between gap-4 py-3"><dt class="text-muted">Crédits ECTS</dt><dd class="font-medium text-[#1a1b22]">114</dd></div>
              </dl>
            </section>

            <!-- Onglet : Paramètres du compte -->
            <section data-panel="compte" class="hidden rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm sm:p-6">
              <h3 class="font-bold text-[#1a1b22]">Paramètres du compte</h3>
              <form class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <label class="block sm:col-span-2"><span class="text-xs font-medium text-muted">Mot de passe actuel</span>
                  <input type="password" value="········" class="mt-1 w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></label>
                <label class="block"><span class="text-xs font-medium text-muted">Nouveau mot de passe</span>
                  <input type="password" placeholder="••••••••" class="mt-1 w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></label>
                <label class="block"><span class="text-xs font-medium text-muted">Confirmer le mot de passe</span>
                  <input type="password" placeholder="••••••••" class="mt-1 w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></label>
                <label class="block sm:col-span-2"><span class="text-xs font-medium text-muted">Langue de l'interface</span>
                  <select class="mt-1 w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"><option>Français</option><option>English</option></select></label>
              </form>
              <div class="mt-5 flex justify-end border-t border-[#ececef] pt-4">
                <button type="button" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primarydark" onclick="showToast('success','Paramètres enregistrés','Vos préférences de compte ont été mises à jour.',()=>showToast('info','Modification annulée'))">Enregistrer</button>
              </div>
            </section>
          </div>

          <!-- Colonne droite -->
          <div class="flex flex-col gap-5">
            <!-- Parcours académique (résumé) -->
            <section class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm">
              <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#fdeaec] text-primary"><svg class="h-5 w-5"><use href="#i-book" /></svg></span>
                <div><p class="font-bold text-[#1a1b22]">Licence Génie Logiciel</p><p class="text-xs text-muted">3e année · Semestre 5</p></div>
              </div>
              <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                <div class="rounded-lg bg-page py-3"><p class="text-lg font-extrabold text-[#1a1b22]">2024</p><p class="text-[11px] text-muted">admission</p></div>
                <div class="rounded-lg bg-page py-3"><p class="text-lg font-extrabold text-[#1a1b22]">14,8</p><p class="text-[11px] text-muted">moyenne</p></div>
                <div class="rounded-lg bg-page py-3"><p class="text-lg font-extrabold text-[#1a1b22]">114</p><p class="text-[11px] text-muted">crédits ECTS</p></div>
              </div>
            </section>

            <!-- Préférences -->
            <section class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm">
              <h3 class="font-bold text-[#1a1b22]">Préférences</h3>
              <div class="mt-4 flex flex-col gap-4 text-sm">
                <div class="flex items-start justify-between gap-3">
                  <div><p class="font-medium text-[#1a1b22]">Notifications par e-mail</p><p class="text-xs text-muted">Rappels de cours et de devoirs</p></div>
                  <button type="button" class="js-toggle" data-on="true" aria-label="Activer"></button>
                </div>
                <div class="flex items-start justify-between gap-3">
                  <div><p class="font-medium text-[#1a1b22]">Alertes de notes</p><p class="text-xs text-muted">Prévenir à chaque nouvelle note publiée</p></div>
                  <button type="button" class="js-toggle" data-on="true" aria-label="Activer"></button>
                </div>
                <div class="flex items-start justify-between gap-3">
                  <div><p class="font-medium text-[#1a1b22]">Newsletter École 221</p><p class="text-xs text-muted">Actualités et événements du campus</p></div>
                  <button type="button" class="js-toggle" data-on="false" aria-label="Activer"></button>
                </div>
              </div>
            </section>

            <!-- Sécurité du compte -->
            <section class="rounded-2xl border border-[#ececef] bg-white p-5 shadow-sm">
              <h3 class="font-bold text-[#1a1b22]">Sécurité du compte</h3>
              <div class="mt-4 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                  <span class="flex h-9 w-9 items-center justify-center rounded-full bg-page text-muted"><svg class="h-4 w-4"><use href="#i-lock" /></svg></span>
                  <div><p class="text-sm font-medium text-[#1a1b22]">Mot de passe</p><p class="text-xs text-muted">Modifié il y a 42 jours</p></div>
                </div>
                <button type="button" class="text-sm font-semibold text-primary hover:underline" onclick="document.querySelector('[data-tab=compte]').click()">Modifier</button>
              </div>
              <a href="loginEcole221.php" class="mt-4 flex items-center justify-center gap-2 rounded-lg border border-primary px-4 py-2.5 text-sm font-semibold text-primary transition hover:bg-primary hover:text-white"><svg class="h-4 w-4"><use href="#i-logout" /></svg> Se déconnecter</a>
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

    /* ----- Onglets ----- */
    const tabs = document.querySelectorAll('.js-tab');
    const panels = document.querySelectorAll('[data-panel]');
    tabs.forEach((tab) => tab.addEventListener('click', () => {
      tabs.forEach((t) => { t.classList.remove('border-primary', 'text-primary', 'font-semibold'); t.classList.add('border-transparent', 'text-muted'); });
      tab.classList.add('border-primary', 'text-primary', 'font-semibold'); tab.classList.remove('border-transparent', 'text-muted');
      panels.forEach((p) => p.classList.toggle('hidden', p.dataset.panel !== tab.dataset.tab));
    }));

    /* ----- Interrupteurs (toggles) ----- */
    function peindreToggle(btn) {
      const on = btn.dataset.on === 'true';
      btn.className = 'js-toggle relative h-6 w-11 shrink-0 rounded-full transition ' + (on ? 'bg-primary' : 'bg-[#d1d5db]');
      btn.innerHTML = '<span class="absolute top-0.5 h-5 w-5 rounded-full bg-white shadow transition-all ' + (on ? 'left-[22px]' : 'left-0.5') + '"></span>';
    }
    document.querySelectorAll('.js-toggle').forEach((btn) => {
      peindreToggle(btn);
      btn.addEventListener('click', () => { btn.dataset.on = btn.dataset.on === 'true' ? 'false' : 'true'; peindreToggle(btn); });
    });

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
    document.getElementById('btnSave').addEventListener('click', () => {
      showToast('success', 'Profil mis à jour', 'Vos informations personnelles ont été enregistrées.', () => showToast('info', 'Modification annulée'));
    });

    /* ----- Changer la photo ----- */
    const photoInput = document.getElementById('photoInput');
    const avatarMain = document.getElementById('avatarMain');
    const avatarNav = document.getElementById('avatarNav');
    document.getElementById('btnChangePhoto').addEventListener('click', () => photoInput.click());
    photoInput.addEventListener('change', () => {
      const file = photoInput.files[0];
      if (!file) return;
      if (!file.type.startsWith('image/')) { showToast('error', 'Fichier invalide', 'Veuillez choisir une image.'); photoInput.value = ''; return; }
      const url = URL.createObjectURL(file);
      const ancienMain = avatarMain.innerHTML, ancienNav = avatarNav.innerHTML;
      const img = '<img src="' + url + '" alt="Photo" class="h-full w-full object-cover" />';
      avatarMain.innerHTML = img; avatarNav.innerHTML = img;
      photoInput.value = '';
      showToast('success', 'Photo mise à jour', 'Votre photo de profil a été changée.', () => { avatarMain.innerHTML = ancienMain; avatarNav.innerHTML = ancienNav; });
    });
  </script>
</body>
</html>