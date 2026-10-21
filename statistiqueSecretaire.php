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
  <title>École 221 – Statistiques</title>
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
      <symbol id="i-tree" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="6" height="6"/><rect x="15" y="9" width="6" height="6"/><rect x="3" y="15" width="6" height="6"/><path d="M9 6h3v12H9M12 12h3"/></symbol>
      <symbol id="i-layers" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 2 10 5-10 5L2 7l10-5Z"/><path d="m2 17 10 5 10-5M2 12l10 5 10-5"/></symbol>
      <symbol id="i-door" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 21V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17M3 21h18M14 12v.01"/></symbol>
      <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M2 21v-1a6 6 0 0 1 12 0v1M16 4.5a3.5 3.5 0 0 1 0 7M22 21v-1a6 6 0 0 0-4-5.6"/></symbol>
      <symbol id="i-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 21v-8M12 21V4M19 21v-12"/></symbol>
      <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
      <symbol id="i-male" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="14" r="6"/><path d="M19 5l-6 6M15 5h4v4"/></symbol>
      <symbol id="i-female" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M12 14v7M9 18h6"/></symbol>
      <symbol id="i-award" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M8.2 13.5 7 22l5-3 5 3-1.2-8.5"/></symbol>
    </defs>
  </svg>

  <!-- ============ SIDEBAR ============ -->
  <?php $active = 'statistiques'; include 'sidebarSecretaire.php'; ?>

  <div class="app-content min-h-screen lg:ml-[260px]">
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
            <h1 class="flex items-center gap-2 text-xl font-bold text-[#1a1b22]"><span class="h-2 w-2 rounded-full bg-primary"></span> Indicateurs clés &amp; Statistiques académiques</h1>
            <p class="mt-1 text-xs text-muted">Supervision globale des effectifs d'apprentissage et de la structure institutionnelle</p>
          </div>
          <span class="flex items-center gap-2 rounded-full bg-page px-3 py-1.5 text-xs font-semibold text-[#374151]"><svg class="h-3.5 w-3.5 text-muted"><use href="#i-calendar" /></svg> Année académique 2024-2025</span>
        </div>

        <!-- 6 cartes indicateurs -->
        <div class="grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-6">
          <div class="rounded-xl bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between"><span class="text-[11px] font-semibold uppercase tracking-wide text-muted">Pôle</span><span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-tree" /></svg></span></div>
            <p class="mt-3 text-3xl font-bold text-[#1a1b22]">8</p><p class="text-xs text-muted">Filières</p>
          </div>
          <div class="rounded-xl bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between"><span class="text-[11px] font-semibold uppercase tracking-wide text-muted">Cycles</span><span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-layers" /></svg></span></div>
            <p class="mt-3 text-3xl font-bold text-[#1a1b22]">5</p><p class="text-xs text-muted">Niveaux</p>
          </div>
          <div class="rounded-xl bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between"><span class="text-[11px] font-semibold uppercase tracking-wide text-muted">Groupes</span><span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-door" /></svg></span></div>
            <p class="mt-3 text-3xl font-bold text-[#1a1b22]">24</p><p class="text-xs text-muted">Classes</p>
          </div>
          <div class="rounded-xl bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between"><span class="text-[11px] font-semibold uppercase tracking-wide text-muted">Total</span><span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-users" /></svg></span></div>
            <p class="mt-3 text-3xl font-bold text-[#1a1b22]">640</p><p class="text-xs text-muted">Étudiants</p>
          </div>
          <div class="rounded-xl bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between"><span class="text-[11px] font-semibold text-[#2563eb]">53,8%</span><span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#eff6ff] text-[#2563eb]"><svg class="h-4 w-4"><use href="#i-male" /></svg></span></div>
            <p class="mt-3 text-3xl font-bold text-[#1a1b22]">345</p><p class="text-xs text-muted">Garçons</p>
          </div>
          <div class="rounded-xl bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between"><span class="text-[11px] font-semibold text-[#db2777]">46,1%</span><span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#fdf2f8] text-[#db2777]"><svg class="h-4 w-4"><use href="#i-female" /></svg></span></div>
            <p class="mt-3 text-3xl font-bold text-[#1a1b22]">295</p><p class="text-xs text-muted">Filles</p>
          </div>
        </div>

        <!-- Deux graphiques -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

          <!-- Étudiants par filière (barres horizontales) -->
          <section class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between">
              <h3 class="flex items-center gap-2 text-base font-bold text-[#1a1b22]"><svg class="h-4 w-4 text-primary"><use href="#i-chart" /></svg> Étudiants par filière</h3>
              <span class="text-xs text-muted">Effectif cumulé : 640</span>
            </div>
            <div class="space-y-4">
              <div>
                <div class="mb-1.5 flex items-center justify-between text-sm"><span class="font-medium text-[#1f2937]">Génie Logiciel</span><span class="font-semibold text-[#1a1b22]">160 <span class="text-xs font-normal text-muted">étudiants</span></span></div>
                <div class="h-2.5 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:100%"></div></div>
              </div>
              <div>
                <div class="mb-1.5 flex items-center justify-between text-sm"><span class="font-medium text-[#1f2937]">Informatique &amp; Tech</span><span class="font-semibold text-[#1a1b22]">140 <span class="text-xs font-normal text-muted">étudiants</span></span></div>
                <div class="h-2.5 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:87.5%"></div></div>
              </div>
              <div>
                <div class="mb-1.5 flex items-center justify-between text-sm"><span class="font-medium text-[#1f2937]">Réseaux &amp; Télécoms</span><span class="font-semibold text-[#1a1b22]">115 <span class="text-xs font-normal text-muted">étudiants</span></span></div>
                <div class="h-2.5 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:72%"></div></div>
              </div>
              <div>
                <div class="mb-1.5 flex items-center justify-between text-sm"><span class="font-medium text-[#1f2937]">Data Analytics</span><span class="font-semibold text-[#1a1b22]">95 <span class="text-xs font-normal text-muted">étudiants</span></span></div>
                <div class="h-2.5 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:59%"></div></div>
              </div>
              <div>
                <div class="mb-1.5 flex items-center justify-between text-sm"><span class="font-medium text-[#1f2937]">Gestion &amp; Management</span><span class="font-semibold text-[#1a1b22]">75 <span class="text-xs font-normal text-muted">étudiants</span></span></div>
                <div class="h-2.5 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:47%"></div></div>
              </div>
              <div>
                <div class="mb-1.5 flex items-center justify-between text-sm"><span class="font-medium text-[#1f2937]">Finance &amp; Comptabilité</span><span class="font-semibold text-[#1a1b22]">55 <span class="text-xs font-normal text-muted">étudiants</span></span></div>
                <div class="h-2.5 overflow-hidden rounded-full bg-[#f3f4f6]"><div class="h-full rounded-full bg-primary" style="width:34%"></div></div>
              </div>
            </div>
            <p class="mt-5 text-xs text-muted">Source : Registre scolarité Dakar</p>
          </section>

          <!-- Étudiants par niveau (barres verticales) -->
          <section class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between">
              <h3 class="flex items-center gap-2 text-base font-bold text-[#1a1b22]"><svg class="h-4 w-4 text-primary"><use href="#i-chart" /></svg> Étudiants par niveau</h3>
              <span class="text-xs text-muted">Licence (70,5%) • Master (29,5%)</span>
            </div>
            <div class="flex h-56 items-end justify-between gap-3">
              <div class="flex h-full flex-1 flex-col items-center justify-end gap-2">
                <span class="text-sm font-bold text-[#1a1b22]">180</span>
                <div class="relative flex w-full flex-1 items-end"><div class="h-full w-full rounded-lg bg-[#f3f4f6]"></div><div class="absolute bottom-0 w-full rounded-lg bg-primary" style="height:100%"></div></div>
                <span class="text-xs font-medium text-[#1f2937]">L1</span><span class="text-[10px] text-muted">Licence 1</span>
              </div>
              <div class="flex h-full flex-1 flex-col items-center justify-end gap-2">
                <span class="text-sm font-bold text-[#1a1b22]">155</span>
                <div class="relative flex w-full flex-1 items-end"><div class="h-full w-full rounded-lg bg-[#f3f4f6]"></div><div class="absolute bottom-0 w-full rounded-lg bg-primary" style="height:86%"></div></div>
                <span class="text-xs font-medium text-[#1f2937]">L2</span><span class="text-[10px] text-muted">Licence 2</span>
              </div>
              <div class="flex h-full flex-1 flex-col items-center justify-end gap-2">
                <span class="text-sm font-bold text-[#1a1b22]">145</span>
                <div class="relative flex w-full flex-1 items-end"><div class="h-full w-full rounded-lg bg-[#f3f4f6]"></div><div class="absolute bottom-0 w-full rounded-lg bg-primary" style="height:81%"></div></div>
                <span class="text-xs font-medium text-[#1f2937]">L3</span><span class="text-[10px] text-muted">Licence 3</span>
              </div>
              <div class="flex h-full flex-1 flex-col items-center justify-end gap-2">
                <span class="text-sm font-bold text-[#1a1b22]">85</span>
                <div class="relative flex w-full flex-1 items-end"><div class="h-full w-full rounded-lg bg-[#f3f4f6]"></div><div class="absolute bottom-0 w-full rounded-lg bg-primary" style="height:47%"></div></div>
                <span class="text-xs font-medium text-[#1f2937]">M1</span><span class="text-[10px] text-muted">Master 1</span>
              </div>
              <div class="flex h-full flex-1 flex-col items-center justify-end gap-2">
                <span class="text-sm font-bold text-[#1a1b22]">65</span>
                <div class="relative flex w-full flex-1 items-end"><div class="h-full w-full rounded-lg bg-[#f3f4f6]"></div><div class="absolute bottom-0 w-full rounded-lg bg-primary" style="height:36%"></div></div>
                <span class="text-xs font-medium text-[#1f2937]">M2</span><span class="text-[10px] text-muted">Master 2</span>
              </div>
            </div>
            <div class="mt-5 flex items-center justify-between text-xs text-muted">
              <span>Cycle LMD conforme CAMES</span><span>Total : 640 inscrits</span>
            </div>
          </section>
        </div>

        <!-- Top effectif -->
        <section class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border-l-4 border-primary bg-white p-6 shadow-sm">
          <div class="flex items-center gap-4">
            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-6 w-6"><use href="#i-award" /></svg></span>
            <div>
              <span class="rounded-full bg-primary px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-white">Top effectif</span>
              <p class="mt-1 text-xs text-muted">Classe ayant le plus d'étudiants</p>
              <h3 class="text-xl font-bold text-[#1a1b22]">L3 Génie Logiciel</h3>
              <div class="mt-1 flex flex-wrap items-center gap-3 text-xs text-muted">
                <span>Filière rattachée : <span class="font-medium text-[#374151]">Génie Logiciel</span></span>
                <span>Promotion sortante Licence</span>
              </div>
            </div>
          </div>
          <div class="rounded-xl bg-page px-6 py-4 text-right">
            <p class="text-3xl font-bold text-primary">45 <span class="text-sm font-medium text-muted">étudiants</span></p>
            <p class="mt-1 text-xs text-muted">Taux de remplissage : <span class="font-semibold text-[#1f2937]">90%</span></p>
            <p class="text-xs text-muted">Capacité maximale de la salle : 50 places</p>
          </div>
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