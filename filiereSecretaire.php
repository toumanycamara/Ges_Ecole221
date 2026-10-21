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
  <title>École 221 – Filières</title>
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
      <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></symbol>
      <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
      <symbol id="i-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
      <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
      <symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></symbol>
    </defs>
  </svg>

  <!-- ============ SIDEBAR ============ -->
  <?php $active = 'filieres'; include 'sidebarSecretaire.php'; ?>

  <!-- ============ CONTENU ============ -->
  <div class="app-content min-h-screen lg:ml-[260px]">

    <!-- Navbar -->
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" aria-controls="sidebar" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden">
          <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h2 class="text-sm font-semibold">Tableau de bord - Secrétaire</h2>
      </div>
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
          <label class="group relative h-8 w-8 shrink-0 cursor-pointer overflow-hidden rounded-full ring-2 ring-transparent transition hover:ring-primarydark focus-within:ring-primarydark" title="Changer la photo">
            <input id="avatarInput" type="file" accept="image/*" class="sr-only" />
            <img id="avatarPreview" src="https://i.pravatar.cc/64?img=47" alt="Photo de l'administrateur" class="h-full w-full object-cover" />
            <span class="absolute inset-0 flex items-center justify-center bg-primarydark/70 text-white opacity-0 transition group-hover:opacity-100"><svg class="h-3.5 w-3.5"><use href="#i-edit" /></svg></span>
          </label>
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

        <!-- En-tête de page -->
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <div class="flex items-center gap-3">
              <h1 class="text-2xl font-bold leading-8 tracking-[-0.6px] text-[#1a1b22]">Liste des filières</h1>
              <span id="countBadge" class="rounded-full bg-[#ffdad7] px-2.5 py-0.5 text-xs font-semibold text-primary">6 filières actives</span>
            </div>
            <p class="mt-1 text-xs text-muted">Programmes académiques et départements d'enseignement supérieur accrédités</p>
          </div>
          <button type="button" data-open="modalAdd" class="flex items-center justify-center gap-[7px] rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark">
            <svg class="h-3 w-3"><use href="#i-plus" /></svg> Ajouter une filière
          </button>
        </div>

        <!-- Grille de cartes -->
        <section id="grid" class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

          <article class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition hover:shadow-md">
            <div class="h-1.5 bg-primary"></div>
            <div class="flex flex-1 flex-col gap-2.5 p-5">
              <div class="flex items-start justify-between gap-3">
                <h3 class="font-bold leading-snug text-[#1a1b22]">Informatique &amp; Technologies Numériques</h3>
                <span class="shrink-0 rounded-full bg-[#ffdad7] px-2.5 py-1 text-xs font-semibold text-primary">ITN-221</span>
              </div>
              <p class="text-sm text-muted"><span class="font-medium text-[#374151]">Responsable :</span> Dr. Amadou Sall</p>
              <p class="text-sm leading-relaxed text-muted">Formation d'excellence en développement, infrastructures et systèmes d'information.</p>
              <div>
                <p class="mb-1.5 text-xs font-semibold uppercase tracking-wide text-muted">Niveaux associés</p>
                <div class="flex flex-wrap gap-1.5">
                  <span class="rounded-md bg-[#ffdad7] px-2 py-0.5 text-xs font-semibold text-primary">L1</span>
                  <span class="rounded-md bg-[#ffdad7] px-2 py-0.5 text-xs font-semibold text-primary">L2</span>
                  <span class="rounded-md bg-[#ffdad7] px-2 py-0.5 text-xs font-semibold text-primary">L3</span>
                </div>
              </div>
              <div class="mt-auto flex items-center justify-between border-t border-[#f3f4f6] pt-3">
                <span class="flex items-center gap-2 text-xs font-medium text-muted"><svg class="h-4 w-4 text-primary"><use href="#i-user" /></svg> 128 inscrits</span>
                <button type="button" class="js-delete rounded-lg p-1.5 text-[#9ca3af] transition hover:bg-primarydark hover:text-white" aria-label="Supprimer la filière"><svg class="h-4 w-4"><use href="#i-trash" /></svg></button>
              </div>
            </div>
          </article>

          <article class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition hover:shadow-md">
            <div class="h-1.5 bg-primary"></div>
            <div class="flex flex-1 flex-col gap-2.5 p-5">
              <div class="flex items-start justify-between gap-3">
                <h3 class="font-bold leading-snug text-[#1a1b22]">Génie Logiciel</h3>
                <span class="shrink-0 rounded-full bg-[#ffdad7] px-2.5 py-1 text-xs font-semibold text-primary">GL-221</span>
              </div>
              <p class="text-sm text-muted"><span class="font-medium text-[#374151]">Responsable :</span> Dr. Fatou Ndiaye</p>
              <p class="text-sm leading-relaxed text-muted">Conception logicielle avancée, DevOps et développement web &amp; mobile.</p>
              <div>
                <p class="mb-1.5 text-xs font-semibold uppercase tracking-wide text-muted">Niveaux associés</p>
                <div class="flex flex-wrap gap-1.5">
                  <span class="rounded-md bg-[#ffdad7] px-2 py-0.5 text-xs font-semibold text-primary">L3</span>
                  <span class="rounded-md bg-[#ffdad7] px-2 py-0.5 text-xs font-semibold text-primary">M1</span>
                  <span class="rounded-md bg-[#ffdad7] px-2 py-0.5 text-xs font-semibold text-primary">M2</span>
                </div>
              </div>
              <div class="mt-auto flex items-center justify-between border-t border-[#f3f4f6] pt-3">
                <span class="flex items-center gap-2 text-xs font-medium text-muted"><svg class="h-4 w-4 text-primary"><use href="#i-user" /></svg> 101 inscrits</span>
                <button type="button" class="js-delete rounded-lg p-1.5 text-[#9ca3af] transition hover:bg-primarydark hover:text-white" aria-label="Supprimer la filière"><svg class="h-4 w-4"><use href="#i-trash" /></svg></button>
              </div>
            </div>
          </article>

          <article class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition hover:shadow-md">
            <div class="h-1.5 bg-primary"></div>
            <div class="flex flex-1 flex-col gap-2.5 p-5">
              <div class="flex items-start justify-between gap-3">
                <h3 class="font-bold leading-snug text-[#1a1b22]">Data Analytics</h3>
                <span class="shrink-0 rounded-full bg-[#ffdad7] px-2.5 py-1 text-xs font-semibold text-primary">DA-221</span>
              </div>
              <p class="text-sm text-muted"><span class="font-medium text-[#374151]">Responsable :</span> M. Ibrahima Fall</p>
              <p class="text-sm leading-relaxed text-muted">Science des données, modélisation prédictive et valorisation de l'information.</p>
              <div>
                <p class="mb-1.5 text-xs font-semibold uppercase tracking-wide text-muted">Niveaux associés</p>
                <div class="flex flex-wrap gap-1.5">
                  <span class="rounded-md bg-[#ffdad7] px-2 py-0.5 text-xs font-semibold text-primary">L3</span>
                  <span class="rounded-md bg-[#ffdad7] px-2 py-0.5 text-xs font-semibold text-primary">M1</span>
                  <span class="rounded-md bg-[#ffdad7] px-2 py-0.5 text-xs font-semibold text-primary">M2</span>
                </div>
              </div>
              <div class="mt-auto flex items-center justify-between border-t border-[#f3f4f6] pt-3">
                <span class="flex items-center gap-2 text-xs font-medium text-muted"><svg class="h-4 w-4 text-primary"><use href="#i-user" /></svg> 86 inscrits</span>
                <button type="button" class="js-delete rounded-lg p-1.5 text-[#9ca3af] transition hover:bg-primarydark hover:text-white" aria-label="Supprimer la filière"><svg class="h-4 w-4"><use href="#i-trash" /></svg></button>
              </div>
            </div>
          </article>

          <article class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition hover:shadow-md">
            <div class="h-1.5 bg-primary"></div>
            <div class="flex flex-1 flex-col gap-2.5 p-5">
              <div class="flex items-start justify-between gap-3">
                <h3 class="font-bold leading-snug text-[#1a1b22]">Développement Web</h3>
                <span class="shrink-0 rounded-full bg-[#ffdad7] px-2.5 py-1 text-xs font-semibold text-primary">DW-221</span>
              </div>
              <p class="text-sm text-muted"><span class="font-medium text-[#374151]">Responsable :</span> Mme Aïssatou Ba</p>
              <p class="text-sm leading-relaxed text-muted">Intégration front-end, back-end et développement d'applications web modernes.</p>
              <div>
                <p class="mb-1.5 text-xs font-semibold uppercase tracking-wide text-muted">Niveaux associés</p>
                <div class="flex flex-wrap gap-1.5">
                  <span class="rounded-md bg-[#ffdad7] px-2 py-0.5 text-xs font-semibold text-primary">L1</span>
                  <span class="rounded-md bg-[#ffdad7] px-2 py-0.5 text-xs font-semibold text-primary">L2</span>
                </div>
              </div>
              <div class="mt-auto flex items-center justify-between border-t border-[#f3f4f6] pt-3">
                <span class="flex items-center gap-2 text-xs font-medium text-muted"><svg class="h-4 w-4 text-primary"><use href="#i-user" /></svg> 74 inscrits</span>
                <button type="button" class="js-delete rounded-lg p-1.5 text-[#9ca3af] transition hover:bg-primarydark hover:text-white" aria-label="Supprimer la filière"><svg class="h-4 w-4"><use href="#i-trash" /></svg></button>
              </div>
            </div>
          </article>

          <article class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition hover:shadow-md">
            <div class="h-1.5 bg-primary"></div>
            <div class="flex flex-1 flex-col gap-2.5 p-5">
              <div class="flex items-start justify-between gap-3">
                <h3 class="font-bold leading-snug text-[#1a1b22]">Référent Digital</h3>
                <span class="shrink-0 rounded-full bg-[#ffdad7] px-2.5 py-1 text-xs font-semibold text-primary">RD-221</span>
              </div>
              <p class="text-sm text-muted"><span class="font-medium text-[#374151]">Responsable :</span> M. Ousmane Diouf</p>
              <p class="text-sm leading-relaxed text-muted">Transformation digitale, marketing numérique et pilotage de projets digitaux.</p>
              <div>
                <p class="mb-1.5 text-xs font-semibold uppercase tracking-wide text-muted">Niveaux associés</p>
                <div class="flex flex-wrap gap-1.5">
                  <span class="rounded-md bg-[#ffdad7] px-2 py-0.5 text-xs font-semibold text-primary">L1</span>
                  <span class="rounded-md bg-[#ffdad7] px-2 py-0.5 text-xs font-semibold text-primary">L2</span>
                  <span class="rounded-md bg-[#ffdad7] px-2 py-0.5 text-xs font-semibold text-primary">L3</span>
                </div>
              </div>
              <div class="mt-auto flex items-center justify-between border-t border-[#f3f4f6] pt-3">
                <span class="flex items-center gap-2 text-xs font-medium text-muted"><svg class="h-4 w-4 text-primary"><use href="#i-user" /></svg> 59 inscrits</span>
                <button type="button" class="js-delete rounded-lg p-1.5 text-[#9ca3af] transition hover:bg-primarydark hover:text-white" aria-label="Supprimer la filière"><svg class="h-4 w-4"><use href="#i-trash" /></svg></button>
              </div>
            </div>
          </article>

          <article class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition hover:shadow-md">
            <div class="h-1.5 bg-primary"></div>
            <div class="flex flex-1 flex-col gap-2.5 p-5">
              <div class="flex items-start justify-between gap-3">
                <h3 class="font-bold leading-snug text-[#1a1b22]">Réseaux &amp; Télécommunications</h3>
                <span class="shrink-0 rounded-full bg-[#ffdad7] px-2.5 py-1 text-xs font-semibold text-primary">RT-221</span>
              </div>
              <p class="text-sm text-muted"><span class="font-medium text-[#374151]">Responsable :</span> Mme Mariama Sow</p>
              <p class="text-sm leading-relaxed text-muted">Administration des réseaux et des infrastructures de télécommunication.</p>
              <div>
                <p class="mb-1.5 text-xs font-semibold uppercase tracking-wide text-muted">Niveaux associés</p>
                <div class="flex flex-wrap gap-1.5">
                  <span class="rounded-md bg-[#ffdad7] px-2 py-0.5 text-xs font-semibold text-primary">L2</span>
                  <span class="rounded-md bg-[#ffdad7] px-2 py-0.5 text-xs font-semibold text-primary">L3</span>
                </div>
              </div>
              <div class="mt-auto flex items-center justify-between border-t border-[#f3f4f6] pt-3">
                <span class="flex items-center gap-2 text-xs font-medium text-muted"><svg class="h-4 w-4 text-primary"><use href="#i-user" /></svg> 92 inscrits</span>
                <button type="button" class="js-delete rounded-lg p-1.5 text-[#9ca3af] transition hover:bg-primarydark hover:text-white" aria-label="Supprimer la filière"><svg class="h-4 w-4"><use href="#i-trash" /></svg></button>
              </div>
            </div>
          </article>

        </section>
      </div>
    </main>
  </div>

  <!-- ============ DRAWER : NOUVELLE FILIÈRE ============ -->
  <div id="drawerAdd" class="fixed inset-0 z-50 hidden">
    <div data-close class="absolute inset-0 bg-black/50"></div>
    <aside class="absolute inset-y-0 right-0 flex w-full max-w-md translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out">
      <div class="flex items-start justify-between gap-3 border-b border-[#eef0f2] px-6 py-4">
        <div class="flex items-center gap-3">
          <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#ffdad7] text-primary"><svg class="h-5 w-5"><use href="#i-book" /></svg></span>
          <div>
            <h3 class="text-lg font-bold text-[#1a1b22]">Nouvelle filière</h3>
            <p class="text-xs text-muted">Renseignez les détails pour créer une nouvelle filière académique.</p>
          </div>
        </div>
        <button type="button" data-close class="rounded-lg bg-page p-1.5 text-muted hover:bg-[#e8e7ec]" aria-label="Fermer"><svg class="h-5 w-5"><use href="#i-x" /></svg></button>
      </div>

      <form id="filiereForm" class="flex flex-1 flex-col gap-5 overflow-y-auto px-6 py-5">

        <div class="flex flex-col gap-4">
          <h4 class="text-xs font-semibold uppercase tracking-wide text-[#1a1b22]">Informations générales</h4>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
              <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-code">Code de la filière</label>
              <input id="f-code" type="text" placeholder="ex: GL, ITN, DA" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
            </div>
            <div>
              <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-nom">Nom complet de la filière</label>
              <input id="f-nom" type="text" placeholder="ex: Génie Logiciel" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
            </div>
          </div>
          <div>
            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-resp">Responsable</label>
            <input id="f-resp" type="text" placeholder="ex: Dr. Abdoulaye Sylla" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
          </div>
        </div>

        <div>
          <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-muted">Niveaux associés</label>
          <div class="flex flex-wrap items-center gap-5">
            <label class="flex cursor-pointer items-center gap-2 text-sm"><input type="checkbox" name="f-niveaux" value="L1" checked class="h-4 w-4 accent-primary" /> L1</label>
            <label class="flex cursor-pointer items-center gap-2 text-sm"><input type="checkbox" name="f-niveaux" value="L2" checked class="h-4 w-4 accent-primary" /> L2</label>
            <label class="flex cursor-pointer items-center gap-2 text-sm"><input type="checkbox" name="f-niveaux" value="L3" checked class="h-4 w-4 accent-primary" /> L3</label>
            <label class="flex cursor-pointer items-center gap-2 text-sm"><input type="checkbox" name="f-niveaux" value="M1" class="h-4 w-4 accent-primary" /> M1</label>
            <label class="flex cursor-pointer items-center gap-2 text-sm"><input type="checkbox" name="f-niveaux" value="M2" class="h-4 w-4 accent-primary" /> M2</label>
          </div>
        </div>

        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="f-desc">Description</label>
          <textarea id="f-desc" rows="4" placeholder="Brève description des objectifs pédagogiques et débouchés…" class="w-full resize-none rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"></textarea>
        </div>
      </form>

      <div class="grid grid-cols-2 gap-3 border-t border-[#eef0f2] px-6 py-4">
        <button type="button" data-close class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="submit" form="filiereForm" class="flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-check" /></svg> Enregistrer</button>
      </div>
    </aside>
  </div>

  <!-- ============ MODALE : CONFIRMER SUPPRESSION ============ -->
  <div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-xl">
      <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#ffdad7] text-primary">
        <svg class="h-7 w-7"><use href="#i-trash" /></svg>
      </div>
      <h3 class="text-lg font-bold text-[#1a1b22]">Confirmer la suppression</h3>
      <p class="mt-2 text-sm text-muted">Voulez-vous vraiment supprimer la filière <span id="delName" class="font-semibold text-[#1a1b22]"></span> ? Cette action est irréversible.</p>
      <div class="mt-6 flex items-center justify-center gap-3">
        <button type="button" data-cancel-delete class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="button" id="confirmDelete" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark">Supprimer</button>
      </div>
    </div>
  </div>

  <!-- ============ TOASTS ============ -->
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

    /* ----- Photo de profil ----- */
    document.getElementById('avatarInput').addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) document.getElementById('avatarPreview').src = URL.createObjectURL(file);
    });

    /* ----- Toasts ----- */
    function showToast(type, title, msg) {
      const map = {
        success: { border: '#16a34a', icon: '#i-check', tint: '#dcfce7', text: '#15803d' },
        error:   { border: '#e11d2a', icon: '#i-alert', tint: '#ffdad7', text: '#b8001a' },
        info:    { border: '#6b7280', icon: '#i-alert', tint: '#f3f4f6', text: '#374151' },
      };
      const c = map[type] || map.info;
      const el = document.createElement('div');
      el.className = 'flex items-start gap-3 rounded-xl border-l-4 bg-white p-3.5 shadow-md';
      el.style.borderLeftColor = c.border;
      el.innerHTML =
        '<span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full" style="background:' + c.tint + ';color:' + c.text + '"><svg class="h-4 w-4"><use href="' + c.icon + '"/></svg></span>' +
        '<div class="flex-1"><p class="text-sm font-semibold text-[#1a1b22]">' + title + '</p>' + (msg ? '<p class="text-xs text-muted">' + msg + '</p>' : '') + '</div>' +
        '<button class="text-muted hover:text-ink" aria-label="Fermer"><svg class="h-4 w-4"><use href="#i-x"/></svg></button>';
      el.querySelector('button').addEventListener('click', () => el.remove());
      document.getElementById('toasts').appendChild(el);
      setTimeout(() => el.remove(), 3500);
    }

    /* ----- Utilitaires ----- */
    const modalDelete = document.getElementById('modalDelete');
    const openModal = (m) => { m.classList.remove('hidden'); m.classList.add('flex'); };
    const closeModal = (m) => { m.classList.add('hidden'); m.classList.remove('flex'); };

    /* ----- Drawer d'ajout ----- */
    const drawerAdd = document.getElementById('drawerAdd');
    const drawerPanel = drawerAdd.querySelector('aside');
    const openDrawer = () => {
      document.getElementById('filiereForm').reset();
      clearErrors();
      drawerAdd.classList.remove('hidden');
      requestAnimationFrame(() => drawerPanel.classList.remove('translate-x-full'));
      document.body.classList.add('overflow-hidden');
    };
    const closeDrawer = () => {
      drawerPanel.classList.add('translate-x-full');
      document.body.classList.remove('overflow-hidden');
      setTimeout(() => drawerAdd.classList.add('hidden'), 300);
    };

    /* ----- Compteur de filières ----- */
    const grid = document.getElementById('grid');
    const countBadge = document.getElementById('countBadge');
    function updateCount() {
      const n = grid.querySelectorAll('article').length;
      countBadge.textContent = n + (n > 1 ? ' filières actives' : ' filière active');
    }

    /* ----- Ajout ----- */
    document.querySelectorAll('[data-open="modalAdd"]').forEach((b) => b.addEventListener('click', openDrawer));
    drawerAdd.querySelectorAll('[data-close]').forEach((b) => b.addEventListener('click', closeDrawer));
    /* Clic en dehors du panneau (sur le fond sombre) -> ferme */
    drawerAdd.addEventListener('click', (e) => { if (!drawerPanel.contains(e.target)) closeDrawer(); });

    function clearErrors() {
      ['f-code', 'f-nom'].forEach((id) => document.getElementById(id).classList.remove('border-primary', 'ring-2', 'ring-primary/30'));
    }
    document.getElementById('filiereForm').addEventListener('submit', (e) => {
      e.preventDefault();
      clearErrors();
      const code = document.getElementById('f-code');
      const nom = document.getElementById('f-nom');
      let ok = true;
      [code, nom].forEach((f) => { if (!f.value.trim()) { f.classList.add('border-primary', 'ring-2', 'ring-primary/30'); ok = false; } });
      if (!ok) { showToast('error', 'Champs obligatoires', 'Veuillez remplir le code et le nom.'); return; }
      closeDrawer();
      showToast('success', 'Filière ajoutée', 'La filière a bien été enregistrée.');
    });

    /* ----- Suppression ----- */
    let cardToDelete = null;
    document.querySelectorAll('.js-delete').forEach((btn) => {
      btn.addEventListener('click', () => {
        cardToDelete = btn.closest('article');
        document.getElementById('delName').textContent = '« ' + cardToDelete.querySelector('h3').textContent.trim() + ' »';
        openModal(modalDelete);
      });
    });
    document.querySelectorAll('[data-cancel-delete]').forEach((b) =>
      b.addEventListener('click', () => { closeModal(modalDelete); showToast('info', 'Suppression annulée'); })
    );
    modalDelete.addEventListener('click', (e) => { if (e.target === modalDelete) closeModal(modalDelete); });
    document.getElementById('confirmDelete').addEventListener('click', () => {
      if (cardToDelete) { cardToDelete.remove(); cardToDelete = null; updateCount(); }
      closeModal(modalDelete);
      showToast('success', 'Filière supprimée', 'La filière a bien été retirée de la liste.');
    });

    /* ----- Échap ferme tout ----- */
    window.addEventListener('keydown', (e) => {
      if (e.key !== 'Escape') return;
      closeDrawer(); closeModal(modalDelete); toggleMenu(false);
    });
  </script>
</body>
</html>