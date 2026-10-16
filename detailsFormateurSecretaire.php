<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Fiche formateur</title>
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
      <symbol id="i-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
      <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
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
        <a href="etudiantSecretaire.php" class="flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]"><svg class="h-4 w-4 shrink-0"><use href="#i-user" /></svg> Étudiants</a>
        <a href="formateurSecretaire.php" class="flex items-center gap-3 rounded-lg bg-white px-5 py-2.5 text-primary shadow-sm"><svg class="h-4 w-4 shrink-0"><use href="#i-users" /></svg> Formateurs</a>
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
        <h2 class="text-sm font-semibold">Détail du formateur</h2>
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
            <a href="formateurs.html" class="hover:text-ink">Formateurs</a><svg class="h-3.5 w-3.5"><use href="#i-chevron-right" /></svg>
            <span class="font-semibold text-primary">Fiche formateur</span>
          </nav>
          <div class="flex items-center gap-3">
            <a href="formateurs.html" class="text-sm font-medium text-primary hover:underline">← Retour à la liste</a>
            <button type="button" id="btnEdit" class="flex items-center gap-2 rounded-lg border border-[#e5e7eb] bg-white px-4 py-2.5 text-sm font-medium text-[#374151] hover:bg-page"><svg class="h-4 w-4"><use href="#i-edit" /></svg> Modifier</button>
            <button type="button" id="btnDel" class="flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-trash" /></svg> Supprimer</button>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
          <!-- Profil + coordonnées -->
          <section class="rounded-2xl bg-white p-6 shadow-sm lg:col-span-2">
            <div class="flex items-center gap-4">
              <img src="https://i.pravatar.cc/120?img=12" alt="" class="h-16 w-16 rounded-full object-cover ring-2 ring-primary/20" />
              <div>
                <h1 class="text-xl font-bold text-[#1a1b22]">Dr. Mamadou Kane</h1>
                <p class="text-xs text-muted">Professeur titulaire • Algorithmique &amp; Intelligence Artificielle</p>
                <span class="mt-1 inline-block rounded-full border border-[#bbf7d0] bg-[#f0fdf4] px-2 py-0.5 text-xs font-medium text-[#15803d]">Actif</span>
              </div>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
              <div><p class="text-xs text-muted">Spécialité</p><p class="font-medium text-[#1f2937]">Algorithmique &amp; IA</p></div>
              <div><p class="text-xs text-muted">Email</p><p class="font-medium text-[#1f2937]">m.kane@ecole221.sn</p></div>
              <div><p class="text-xs text-muted">Téléphone</p><p class="font-medium text-[#1f2937]">+221 77 123 45 67</p></div>
              <div><p class="text-xs text-muted">Bureau</p><p class="font-medium text-[#1f2937]">Bâtiment principal • Salle 304</p></div>
            </div>

            <div class="mt-5 rounded-xl bg-page p-4">
              <h4 class="mb-3 text-sm font-bold text-[#1a1b22]">Coordonnées</h4>
              <div class="space-y-3">
                <div><p class="mb-1 text-xs font-semibold uppercase tracking-wide text-muted">Email principal</p><div class="rounded-lg border border-[#e5e7eb] bg-white px-3 py-2 text-sm text-[#374151]">m.kane@ecole221.sn</div></div>
                <div><p class="mb-1 text-xs font-semibold uppercase tracking-wide text-muted">Téléphone</p><div class="rounded-lg border border-[#e5e7eb] bg-white px-3 py-2 text-sm text-[#374151]">+221 77 123 45 67</div></div>
                <div><p class="mb-1 text-xs font-semibold uppercase tracking-wide text-muted">Bureau</p><div class="rounded-lg border border-[#e5e7eb] bg-white px-3 py-2 text-sm text-[#374151]">Bâtiment principal • Salle 304</div></div>
              </div>
            </div>
          </section>

          <!-- Affectations -->
          <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h4 class="mb-3 text-sm font-bold text-[#1a1b22]">Affectations</h4>
            <div class="space-y-3">
              <div class="flex items-center gap-3 rounded-xl border border-[#f3f4f6] p-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#ffdad7] text-xs font-bold text-primary">L3</span><div><p class="text-sm font-semibold text-[#1a1b22]">L3 Génie Logiciel</p><p class="text-xs text-muted">Salle A2 • 40 places</p></div></div>
              <div class="flex items-center gap-3 rounded-xl border border-[#f3f4f6] p-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#ffdad7] text-xs font-bold text-primary">M1</span><div><p class="text-sm font-semibold text-[#1a1b22]">M1 Data Science</p><p class="text-xs text-muted">Salle B1 • 25 places</p></div></div>
              <div class="flex items-center gap-3 rounded-xl border border-[#f3f4f6] p-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#ffdad7] text-xs font-bold text-primary">M2</span><div><p class="text-sm font-semibold text-[#1a1b22]">M2 Intelligence Artificielle</p><p class="text-xs text-muted">Salle B3 • 30 places</p></div></div>
            </div>
          </section>
        </div>
      </div>
    </main>
  </div>

  <!-- ============ DRAWER : MODIFIER LE FORMATEUR ============ -->
  <div id="drawerEdit" class="fixed inset-0 z-50 hidden">
    <div data-close class="absolute inset-0 bg-black/50"></div>
    <aside class="absolute inset-y-0 right-0 flex w-full max-w-md translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out">
      <div class="flex items-center justify-between border-b border-[#eef0f2] px-6 py-4">
        <div class="flex items-center gap-3">
          <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-5 w-5"><use href="#i-edit" /></svg></span>
          <div><h3 class="text-lg font-bold text-[#1a1b22]">Modifier le formateur</h3><p class="text-xs text-muted">Mettez à jour les informations</p></div>
        </div>
        <button type="button" data-close class="rounded-lg p-1.5 text-muted hover:bg-page" aria-label="Fermer"><svg class="h-5 w-5"><use href="#i-x" /></svg></button>
      </div>
      <form id="formateurForm" class="flex flex-1 flex-col gap-4 overflow-y-auto px-6 py-5">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-nom">Nom complet</label><input id="f-nom" type="text" value="Dr. Mamadou Kane" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
          <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-spec">Spécialité</label><input id="f-spec" type="text" value="Algorithmique & IA" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
        </div>
        <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-email">Email</label><input id="f-email" type="email" value="m.kane@ecole221.sn" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
        <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-tel">Téléphone</label><input id="f-tel" type="tel" value="+221 77 123 45 67" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
        <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-bureau">Bureau</label><input id="f-bureau" type="text" value="Bâtiment principal • Salle 304" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" /></div>
        <div><label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-statut">Statut</label>
          <select id="f-statut" class="w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"><option selected>Actif</option><option>En congé</option></select></div>
      </form>
      <div class="grid grid-cols-2 gap-3 border-t border-[#eef0f2] px-6 py-4">
        <button type="button" data-close class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="submit" form="formateurForm" class="flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-check" /></svg> Enregistrer</button>
      </div>
    </aside>
  </div>

  <!-- ============ MODALE : CONFIRMER SUPPRESSION ============ -->
  <div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-xl">
      <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-7 w-7"><use href="#i-trash" /></svg></div>
      <h3 class="text-lg font-bold text-[#1a1b22]">Confirmer la suppression</h3>
      <p class="mt-2 text-sm text-muted">Voulez-vous vraiment supprimer le formateur <span class="font-semibold text-[#1a1b22]">Dr. Mamadou Kane</span> ? Cette action est irréversible.</p>
      <div class="mt-6 flex items-center justify-center gap-3">
        <button type="button" data-cancel-delete class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="button" id="confirmDelete" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark">Supprimer</button>
      </div>
    </div>
  </div>

  <div id="toasts" class="fixed right-4 top-4 z-[60] flex w-[320px] max-w-[calc(100vw-2rem)] flex-col gap-2"></div>

  <script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleMenu = (open) => { sidebar.classList.toggle('-translate-x-full', !open); overlay.classList.toggle('hidden', !open); document.body.classList.toggle('overflow-hidden', open); };
    document.getElementById('openMenu').addEventListener('click', () => toggleMenu(true));
    document.getElementById('closeMenu').addEventListener('click', () => toggleMenu(false));
    overlay.addEventListener('click', () => toggleMenu(false));

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

    /* ----- Drawer Modifier ----- */
    const drawerEdit = document.getElementById('drawerEdit');
    const drawerPanel = drawerEdit.querySelector('aside');
    const openDrawer = () => { drawerEdit.classList.remove('hidden'); requestAnimationFrame(() => drawerPanel.classList.remove('translate-x-full')); document.body.classList.add('overflow-hidden'); };
    const closeDrawer = () => { drawerPanel.classList.add('translate-x-full'); document.body.classList.remove('overflow-hidden'); setTimeout(() => drawerEdit.classList.add('hidden'), 300); };
    document.getElementById('btnEdit').addEventListener('click', openDrawer);
    drawerEdit.querySelectorAll('[data-close]').forEach((b) => b.addEventListener('click', closeDrawer));
    drawerEdit.addEventListener('click', (e) => { if (!drawerPanel.contains(e.target)) closeDrawer(); });
    document.getElementById('formateurForm').addEventListener('submit', (e) => { e.preventDefault(); closeDrawer(); showToast('success', 'Formateur modifié', 'Les informations ont bien été mises à jour.'); });

    /* ----- Suppression ----- */
    const modalDelete = document.getElementById('modalDelete');
    const openModal = () => { modalDelete.classList.remove('hidden'); modalDelete.classList.add('flex'); };
    const closeModal = () => { modalDelete.classList.add('hidden'); modalDelete.classList.remove('flex'); };
    document.getElementById('btnDel').addEventListener('click', openModal);
    document.querySelectorAll('[data-cancel-delete]').forEach((b) => b.addEventListener('click', () => { closeModal(); showToast('info', 'Suppression annulée'); }));
    modalDelete.addEventListener('click', (e) => { if (e.target === modalDelete) closeModal(); });
    document.getElementById('confirmDelete').addEventListener('click', () => {
      closeModal();
      showToast('success', 'Formateur supprimé', 'Redirection vers la liste…', () => showToast('info', 'Suppression annulée', 'Le formateur a été conservé.'));
      setTimeout(() => { window.location.href = 'formateurs.html'; }, 1200);
    });

    window.addEventListener('keydown', (e) => { if (e.key === 'Escape') { closeDrawer(); closeModal(); toggleMenu(false); } });
  </script>
</body>
</html>
