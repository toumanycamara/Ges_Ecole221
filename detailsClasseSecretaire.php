<?php
session_start();
if (!isset ($_SESSION['role'])||$_SESSION['role']!=='secretaire') {
  header('Location: loginEcole221.php');
  exit;
}

require 'config.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Détails de la classe</title>
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
          colors: {
            primary: '#e11d2a',
            ink: '#141414',
            muted: '#5f5e5e',
            page: '#f4f4f5',
            primarydark: '#8f1119',
          },
        },
      },
    };
  </script>
</head>
<body class="font-sans bg-page text-ink">

  <!-- Icônes (sprite SVG) -->
  <svg class="hidden" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
      <symbol id="i-chevron-right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></symbol>
      <symbol id="i-male" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="14" r="6"/><path d="M19 5l-6 6M15 5h4v4"/></symbol>
      <symbol id="i-female" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M12 14v7M9 18h6"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
    </defs>
  </svg>

  <!-- ============ SIDEBAR ============ -->
  <?php $active = 'classes'; include 'sidebarSecretaire.php'; ?>

  <!-- ============ CONTENU ============ -->
  <div class="app-content min-h-screen lg:ml-[260px]">

    <!-- Navbar -->
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" aria-controls="sidebar" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden">
          <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h2 class="text-sm font-semibold">Détails de la Classe</h2>
      </div>
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
          <img src="https://i.pravatar.cc/64?img=47" alt="Photo de l'administrateur" class="h-8 w-8 rounded-full object-cover" />
          <div class="hidden sm:block">
            <p class="text-sm font-semibold leading-[17.5px]">Moussa Diop</p>
            <p class="text-xs leading-3 text-muted">m.diop@ecole221.sn</p>
          </div>
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
            <span>Tableau de bord</span>
            <svg class="h-3.5 w-3.5"><use href="#i-chevron-right" /></svg>
            <a href="classeSecretaire.php" class="hover:text-ink">Classes</a>
            <svg class="h-3.5 w-3.5"><use href="#i-chevron-right" /></svg>
            <span class="font-semibold text-primary">Fiche classe</span>
          </nav>
          <div class="flex items-center gap-4">
            <a href="classeSecretaire.php" class="text-sm font-medium text-primary hover:underline">Retour à la liste</a>
            <button type="button" id="btnEdit" class="flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-edit" /></svg> Modifier</button>
          </div>
        </div>

        <!-- Titre -->
        <div class="flex flex-wrap items-center gap-3">
          <h1 class="text-2xl font-bold tracking-[-0.6px] text-[#1a1b22]">L3 Génie Logiciel</h1>
          <span class="rounded-full bg-page px-2.5 py-1 text-xs font-semibold text-[#374151]">Salle A3</span>
          <span class="rounded-full bg-[#ffdad7] px-2.5 py-1 text-xs font-semibold text-primary">Année 2025-2026</span>
        </div>

        <!-- Deux cartes : infos + répartition -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

          <!-- Informations de la classe -->
          <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-bold text-[#1a1b22]">Informations de la classe</h2>
            <dl class="divide-y divide-[#f3f4f6] text-sm">
              <div class="flex justify-between py-2.5"><dt class="text-muted">Filière</dt><dd class="font-medium text-[#1f2937]">Génie Logiciel</dd></div>
              <div class="flex justify-between py-2.5"><dt class="text-muted">Niveau</dt><dd class="font-medium text-[#1f2937]">Licence 3</dd></div>
              <div class="flex justify-between py-2.5"><dt class="text-muted">Salle</dt><dd class="font-medium text-[#1f2937]">A3</dd></div>
              <div class="flex justify-between py-2.5"><dt class="text-muted">Capacité</dt><dd class="font-medium text-[#1f2937]">40 places</dd></div>
              <div class="flex justify-between py-2.5"><dt class="text-muted">Inscrits</dt><dd class="font-medium text-[#1f2937]">28 étudiants</dd></div>
              <div class="flex items-center justify-between py-2.5">
                <dt class="text-muted">Taux de remplissage</dt>
                <dd class="flex items-center gap-2">
                  <span class="h-2 w-24 overflow-hidden rounded-full bg-[#f3f4f6]"><span class="block h-full rounded-full bg-primary" style="width:70%"></span></span>
                  <span class="text-sm font-semibold text-primary">70%</span>
                </dd>
              </div>
              <div class="flex justify-between py-2.5"><dt class="text-muted">Formateur principal</dt><dd class="font-semibold text-[#1f2937]">Dr. Mamadou Kane</dd></div>
            </dl>
          </section>

          <!-- Répartition des étudiants -->
          <section class="flex flex-col rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-bold text-[#1a1b22]">Répartition des étudiants</h2>
            <div class="flex flex-1 items-center justify-center gap-12">
              <div class="flex flex-col items-center gap-2">
                <div class="flex h-20 w-20 items-center justify-center rounded-full border-[7px] border-[#2563eb] text-[#2563eb]"><svg class="h-8 w-8"><use href="#i-male" /></svg></div>
                <p class="text-sm text-muted">Garçons: <span class="font-semibold text-[#1f2937]">18</span></p>
              </div>
              <div class="flex flex-col items-center gap-2">
                <div class="flex h-20 w-20 items-center justify-center rounded-full border-[7px] border-[#db2777] text-[#db2777]"><svg class="h-8 w-8"><use href="#i-female" /></svg></div>
                <p class="text-sm text-muted">Filles: <span class="font-semibold text-[#1f2937]">10</span></p>
              </div>
            </div>
            <div class="mt-4 rounded-xl bg-[#ffdad7] px-4 py-3 text-center text-sm font-semibold text-primary">
              Moyenne générale de la classe : 13.2 / 20
            </div>
          </section>

        </div>

        <!-- Liste des étudiants de la classe -->
        <section class="rounded-2xl bg-white p-6 shadow-sm">
          <h2 class="mb-4 text-lg font-bold text-[#1a1b22]">Liste des étudiants de la classe</h2>
          <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
              <thead class="border-b border-[#e5e7eb] bg-[#f9fafb]">
                <tr class="text-xs font-semibold uppercase tracking-[0.5px] text-[#6b7280]">
                  <th class="px-4 py-3 font-semibold">N°</th>
                  <th class="px-4 py-3 font-semibold">Matricule</th>
                  <th class="px-4 py-3 font-semibold">Nom</th>
                  <th class="px-4 py-3 font-semibold">Prénom</th>
                  <th class="px-4 py-3 font-semibold">Sexe</th>
                  <th class="px-4 py-3 font-semibold">Moyenne</th>
                  <th class="px-4 py-3 font-semibold">Statut</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-[#f3f4f6]">
                <tr class="hover:bg-[#f9fafb]"><td class="px-4 py-3 text-muted">1</td><td class="px-4 py-3 font-mono text-xs font-bold text-[#111827]">221-SN-2024-0045</td><td class="px-4 py-3 font-bold">Camara</td><td class="px-4 py-3 text-[#374151]">Toumany</td><td class="px-4 py-3"><span class="rounded-full border border-[#bfdbfe] bg-[#eff6ff] px-2 py-0.5 text-xs font-medium text-[#1d4ed8]">Masculin</span></td><td class="px-4 py-3 font-medium">14.5 / 20</td><td class="px-4 py-3"><span class="rounded-full border border-[#bbf7d0] bg-[#f0fdf4] px-2 py-0.5 text-xs font-medium text-[#15803d]">Inscrit</span></td></tr>
                <tr class="hover:bg-[#f9fafb]"><td class="px-4 py-3 text-muted">2</td><td class="px-4 py-3 font-mono text-xs font-bold text-[#111827]">221-SN-2024-0046</td><td class="px-4 py-3 font-bold">Sy</td><td class="px-4 py-3 text-[#374151]">Fatou</td><td class="px-4 py-3"><span class="rounded-full border border-[#fbcfe8] bg-[#fdf2f8] px-2 py-0.5 text-xs font-medium text-[#be185d]">Féminin</span></td><td class="px-4 py-3 font-medium">16.2 / 20</td><td class="px-4 py-3"><span class="rounded-full border border-[#bbf7d0] bg-[#f0fdf4] px-2 py-0.5 text-xs font-medium text-[#15803d]">Inscrit</span></td></tr>
                <tr class="hover:bg-[#f9fafb]"><td class="px-4 py-3 text-muted">3</td><td class="px-4 py-3 font-mono text-xs font-bold text-[#111827]">221-SN-2024-0047</td><td class="px-4 py-3 font-bold">Diallo</td><td class="px-4 py-3 text-[#374151]">Abdoulaye</td><td class="px-4 py-3"><span class="rounded-full border border-[#bfdbfe] bg-[#eff6ff] px-2 py-0.5 text-xs font-medium text-[#1d4ed8]">Masculin</span></td><td class="px-4 py-3 font-medium">12.8 / 20</td><td class="px-4 py-3"><span class="rounded-full border border-[#bbf7d0] bg-[#f0fdf4] px-2 py-0.5 text-xs font-medium text-[#15803d]">Inscrit</span></td></tr>
                <tr class="hover:bg-[#f9fafb]"><td class="px-4 py-3 text-muted">4</td><td class="px-4 py-3 font-mono text-xs font-bold text-[#111827]">221-SN-2024-0048</td><td class="px-4 py-3 font-bold">Ndiaye</td><td class="px-4 py-3 text-[#374151]">Aminata</td><td class="px-4 py-3"><span class="rounded-full border border-[#fbcfe8] bg-[#fdf2f8] px-2 py-0.5 text-xs font-medium text-[#be185d]">Féminin</span></td><td class="px-4 py-3 font-medium">11.0 / 20</td><td class="px-4 py-3"><span class="rounded-full border border-[#fde68a] bg-[#fffbeb] px-2 py-0.5 text-xs font-medium text-[#b45309]">Redoublant</span></td></tr>
                <tr class="hover:bg-[#f9fafb]"><td class="px-4 py-3 text-muted">5</td><td class="px-4 py-3 font-mono text-xs font-bold text-[#111827]">221-SN-2024-0049</td><td class="px-4 py-3 font-bold">Ba</td><td class="px-4 py-3 text-[#374151]">Ibrahima</td><td class="px-4 py-3"><span class="rounded-full border border-[#bfdbfe] bg-[#eff6ff] px-2 py-0.5 text-xs font-medium text-[#1d4ed8]">Masculin</span></td><td class="px-4 py-3 font-medium">15.3 / 20</td><td class="px-4 py-3"><span class="rounded-full border border-[#bbf7d0] bg-[#f0fdf4] px-2 py-0.5 text-xs font-medium text-[#15803d]">Inscrit</span></td></tr>
                <tr class="hover:bg-[#f9fafb]"><td class="px-4 py-3 text-muted">6</td><td class="px-4 py-3 font-mono text-xs font-bold text-[#111827]">221-SN-2024-0050</td><td class="px-4 py-3 font-bold">Diop</td><td class="px-4 py-3 text-[#374151]">Mariama</td><td class="px-4 py-3"><span class="rounded-full border border-[#fbcfe8] bg-[#fdf2f8] px-2 py-0.5 text-xs font-medium text-[#be185d]">Féminin</span></td><td class="px-4 py-3 font-medium">13.7 / 20</td><td class="px-4 py-3"><span class="rounded-full border border-[#bbf7d0] bg-[#f0fdf4] px-2 py-0.5 text-xs font-medium text-[#15803d]">Inscrit</span></td></tr>
                <tr class="hover:bg-[#f9fafb]"><td class="px-4 py-3 text-muted">7</td><td class="px-4 py-3 font-mono text-xs font-bold text-[#111827]">221-SN-2024-0051</td><td class="px-4 py-3 font-bold">Fall</td><td class="px-4 py-3 text-[#374151]">Ousmane</td><td class="px-4 py-3"><span class="rounded-full border border-[#bfdbfe] bg-[#eff6ff] px-2 py-0.5 text-xs font-medium text-[#1d4ed8]">Masculin</span></td><td class="px-4 py-3 font-medium">9.5 / 20</td><td class="px-4 py-3"><span class="rounded-full border border-[#fde68a] bg-[#fffbeb] px-2 py-0.5 text-xs font-medium text-[#b45309]">Redoublant</span></td></tr>
                <tr class="hover:bg-[#f9fafb]"><td class="px-4 py-3 text-muted">8</td><td class="px-4 py-3 font-mono text-xs font-bold text-[#111827]">221-SN-2024-0052</td><td class="px-4 py-3 font-bold">Mbodji</td><td class="px-4 py-3 text-[#374151]">Anta</td><td class="px-4 py-3"><span class="rounded-full border border-[#fbcfe8] bg-[#fdf2f8] px-2 py-0.5 text-xs font-medium text-[#be185d]">Féminin</span></td><td class="px-4 py-3 font-medium">17.1 / 20</td><td class="px-4 py-3"><span class="rounded-full border border-[#bbf7d0] bg-[#f0fdf4] px-2 py-0.5 text-xs font-medium text-[#15803d]">Inscrit</span></td></tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
            <p class="text-xs text-muted">1 à 8 sur 28 étudiants</p>
            <div class="flex items-center gap-1.5">
              <button class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-sm font-medium text-white">1</button>
              <button class="flex h-8 w-8 items-center justify-center rounded-lg border border-[#e5e7eb] text-sm font-medium text-[#374151] hover:bg-page">2</button>
              <button class="flex h-8 w-8 items-center justify-center rounded-lg border border-[#e5e7eb] text-sm font-medium text-[#374151] hover:bg-page">3</button>
              <button class="flex h-8 w-8 items-center justify-center rounded-lg border border-[#e5e7eb] text-[#374151] hover:bg-page"><svg class="h-4 w-4"><use href="#i-chevron-right" /></svg></button>
            </div>
          </div>
        </section>

      </div>
    </main>
  </div>

  <!-- ============ DRAWER : MODIFIER LA CLASSE ============ -->
  <div id="drawerEdit" class="fixed inset-0 z-50 hidden">
    <div data-close class="absolute inset-0 bg-black/50"></div>
    <aside class="absolute inset-y-0 right-0 flex w-full max-w-md translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out">
      <div class="flex items-start justify-between gap-3 border-b border-[#eef0f2] px-6 py-4">
        <div class="flex items-center gap-3">
          <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#ffdad7] text-primary"><svg class="h-5 w-5"><use href="#i-edit" /></svg></span>
          <div>
            <h3 class="text-lg font-bold text-[#1a1b22]">Modifier la classe</h3>
            <p class="text-xs text-muted">Mettez à jour les informations de la classe.</p>
          </div>
        </div>
        <button type="button" data-close class="rounded-lg bg-page p-1.5 text-muted hover:bg-[#e8e7ec]" aria-label="Fermer"><svg class="h-5 w-5"><use href="#i-x" /></svg></button>
      </div>

      <form id="classeForm" class="flex flex-1 flex-col gap-4 overflow-y-auto px-6 py-5">
        <h4 class="text-xs font-semibold uppercase tracking-wide text-[#1a1b22]">Informations de la classe</h4>
        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="c-nom">Nom de la classe</label>
          <input id="c-nom" type="text" value="L3 Génie Logiciel" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
        </div>
        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="c-filiere">Filière</label>
          <select id="c-filiere" class="w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
            <option selected>Génie Logiciel</option>
            <option>Développement Web</option>
            <option>Référent Digital</option>
            <option>Cyber Sécurité</option>
            <option>Data Analytics</option>
          </select>
        </div>
        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="c-niveau">Niveau</label>
          <select id="c-niveau" class="w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
            <option>Licence 1</option><option>Licence 2</option><option selected>Licence 3</option><option>Master 1</option><option>Master 2</option>
          </select>
        </div>
        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="c-salle">Salle</label>
          <input id="c-salle" type="text" value="A3" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
        </div>
        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="c-capacite">Capacité</label>
          <input id="c-capacite" type="number" value="40" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
        </div>
      </form>

      <div class="grid grid-cols-2 gap-3 border-t border-[#eef0f2] px-6 py-4">
        <button type="button" data-close class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="submit" form="classeForm" class="flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-check" /></svg> Enregistrer</button>
      </div>
    </aside>
  </div>

  <!-- ============ TOASTS ============ -->
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

    /* ----- Toast ----- */
    function showToast(title, msg) {
      const el = document.createElement('div');
      el.className = 'flex items-start gap-3 rounded-xl border-l-4 bg-white p-3.5 shadow-md';
      el.style.borderLeftColor = '#16a34a';
      el.innerHTML =
        '<span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full" style="background:#dcfce7;color:#15803d"><svg class="h-4 w-4"><use href="#i-check"/></svg></span>' +
        '<div class="flex-1"><p class="text-sm font-semibold text-[#1a1b22]">' + title + '</p><p class="text-xs text-muted">' + msg + '</p></div>' +
        '<button class="text-muted hover:text-ink" aria-label="Fermer"><svg class="h-4 w-4"><use href="#i-x"/></svg></button>';
      el.querySelector('button').addEventListener('click', () => el.remove());
      document.getElementById('toasts').appendChild(el);
      setTimeout(() => el.remove(), 3500);
    }

    /* ----- Drawer Modifier ----- */
    const drawerEdit = document.getElementById('drawerEdit');
    const drawerPanel = drawerEdit.querySelector('aside');
    const openDrawer = () => {
      drawerEdit.classList.remove('hidden');
      requestAnimationFrame(() => drawerPanel.classList.remove('translate-x-full'));
      document.body.classList.add('overflow-hidden');
    };
    const closeDrawer = () => {
      drawerPanel.classList.add('translate-x-full');
      document.body.classList.remove('overflow-hidden');
      setTimeout(() => drawerEdit.classList.add('hidden'), 300);
    };
    document.getElementById('btnEdit').addEventListener('click', openDrawer);
    drawerEdit.querySelectorAll('[data-close]').forEach((b) => b.addEventListener('click', closeDrawer));
    drawerEdit.addEventListener('click', (e) => { if (!drawerPanel.contains(e.target)) closeDrawer(); });
    document.getElementById('classeForm').addEventListener('submit', (e) => {
      e.preventDefault();
      closeDrawer();
      showToast('Classe modifiée', 'Les informations ont bien été mises à jour.');
    });
    window.addEventListener('keydown', (e) => { if (e.key === 'Escape') { closeDrawer(); toggleMenu(false); } });
  </script>
</body>
</html>