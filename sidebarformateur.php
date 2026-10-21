<?php
/*
  SIDEBAR FORMATEUR (repliable) - École 221
  --------------------------------------------
  À utiliser avec :  <?php $active = 'notes'; include 'sidebarFormateur.php'; ?>
  Valeurs possibles pour $active :
    dashboard | etudiants | notes | presences | edt

  ATTENTION : vérifie que les noms de fichiers (href) correspondent bien
  aux tiens. Corrige-les ici si besoin, une seule fois.
*/
$active = $active ?? '';
$base = 'flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]';
$on   = 'flex items-center gap-3 rounded-lg bg-white px-5 py-2.5 text-primary shadow-sm';
function lienF($cle, $active, $base, $on) { return $active === $cle ? $on : $base; }
?>

<svg class="hidden" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <symbol id="sb-grid" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></symbol>
    <symbol id="sb-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/></symbol>
    <symbol id="sb-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
    <symbol id="sb-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
    <symbol id="sb-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
    <symbol id="sb-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
    <symbol id="sb-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></symbol>
    <symbol id="sb-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
  </defs>
</svg>

<style>
  #sidebar { transition: width .3s ease; }
  @media (min-width:1024px){
    #sidebar.is-collapsed { width: 76px; }
    #sidebar.is-collapsed .label,
    #sidebar.is-collapsed .logo-text,
    #sidebar.is-collapsed .hide-collapsed { display: none; }
    #sidebar.is-collapsed nav a,
    #sidebar.is-collapsed .foot-link { justify-content: center; padding-left: 0; padding-right: 0; }
    #sidebar.is-collapsed ~ .app-content { margin-left: 76px; }
  }
</style>

<div id="overlay" class="fixed inset-0 z-30 hidden bg-black/50 lg:hidden"></div>

<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-[260px] -translate-x-full flex-col justify-between bg-primary shadow-[0_1px_4px_rgba(0,0,0,0.08)] transition-transform duration-300 lg:z-20 lg:translate-x-0">
  <div>
    <div class="flex h-16 items-center gap-3 px-6">
      <button id="toggleSidebar" type="button" class="flex items-center gap-3" aria-label="Réduire ou ouvrir le menu">
        <span class="flex h-11 w-10 shrink-0 items-center justify-center overflow-hidden rounded-md bg-white p-0.5 shadow-sm"><img src="assets/logo-ecole221.png" alt="Logo École 221" class="h-full w-full object-contain" /></span>
        <span class="logo-text text-xl font-semibold tracking-tight text-white">École 221</span>
      </button>
      <button id="closeMenu" type="button" aria-label="Fermer le menu" class="ml-auto rounded-lg p-1.5 text-white transition hover:bg-[#B8141F] lg:hidden">
        <svg class="h-5 w-5"><use href="#sb-x" /></svg>
      </button>
    </div>

    <nav class="mt-1 flex flex-col gap-2 px-4 pt-4 text-sm font-medium text-white">
      <a href="accueilFormateur.php" class="<?= lienF('dashboard', $active, $base, $on) ?>"><svg class="h-4 w-4 shrink-0"><use href="#sb-grid" /></svg> <span class="label">Tableau de bord</span></a>
      <a href="etudiants-formateur.php" class="<?= lienF('etudiants', $active, $base, $on) ?>"><svg class="h-4 w-4 shrink-0"><use href="#sb-user" /></svg> <span class="label">Étudiants</span></a>
      <a href="notes-formateur.php" class="<?= lienF('notes', $active, $base, $on) ?>"><svg class="h-4 w-4 shrink-0"><use href="#sb-edit" /></svg> <span class="label">Notes</span></a>
      <a href="presences-formateur.php" class="<?= lienF('presences', $active, $base, $on) ?>"><svg class="h-4 w-4 shrink-0"><use href="#sb-check" /></svg> <span class="label">Présences</span></a>
      <a href="emploi-du-temps-formateur.php" class="<?= lienF('edt', $active, $base, $on) ?>"><svg class="h-4 w-4 shrink-0"><use href="#sb-calendar" /></svg> <span class="label">Emploi du temps</span></a>
    </nav>
  </div>

  <div class="flex flex-col items-center gap-2.5 border-t-[0.5px] border-white/25 px-4 py-6">
    <div class="hide-collapsed flex items-center gap-2 rounded-full bg-white/15 px-4 py-1 text-xs text-white">
      <svg class="h-3 w-3"><use href="#sb-pin" /></svg> Dakar, Sénégal
    </div>
    <a href="deconnexion.php" class="foot-link flex w-full items-center gap-3 rounded-lg px-5 py-2.5 text-sm font-medium text-white transition hover:bg-[#B8141F]">
      <svg class="h-4 w-4 shrink-0"><use href="#sb-logout" /></svg> <span class="label">Se déconnecter</span>
    </a>
  </div>
</aside>

<script>
  (function () {
    const sidebarEl = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleSidebar');
    if (localStorage.getItem('sidebarCollapsed') === '1') sidebarEl.classList.add('is-collapsed');
    toggleBtn.addEventListener('click', () => {
      sidebarEl.classList.toggle('is-collapsed');
      localStorage.setItem('sidebarCollapsed', sidebarEl.classList.contains('is-collapsed') ? '1' : '0');
    });
  })();
</script>