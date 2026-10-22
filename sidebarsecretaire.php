<?php
/*
  SIDEBAR SECRÉTAIRE (repliable) - École 221
  --------------------------------------------
  À utiliser avec :  <?php $active = 'classes'; include 'sidebarSecretaire.php'; ?>
  Valeurs possibles pour $active :
    dashboard | filieres | niveaux | classes | etudiants | formateurs | matieres | edt | statistiques
*/
$active = $active ?? '';
// Nom et email de la personne connectée (définis ici, utilisables dans l'en-tête des pages)
$nomAffiche    = (isset($_SESSION['nom_complet']) && trim($_SESSION['nom_complet']) !== '') ? $_SESSION['nom_complet'] : ($_SESSION['login'] ?? 'Utilisateur');
$emailAffiche  = $_SESSION['email'] ?? ($_SESSION['login'] ?? '');
$prenomAffiche = explode(' ', trim($nomAffiche))[0];
// classes communes
$base = 'flex items-center gap-3 rounded-lg px-5 py-2.5 transition hover:bg-[#B8141F]';
$on   = 'flex items-center gap-3 rounded-lg bg-white px-5 py-2.5 text-primary shadow-sm';
// petite fonction pour choisir la bonne classe
function lien($cle, $active, $base, $on) { return $active === $cle ? $on : $base; }
?>

<!-- Icônes du sidebar (préfixe sb- pour ne pas gêner les icônes de la page) -->
<svg class="hidden" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <symbol id="sb-grid" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></symbol>
    <symbol id="sb-tree" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="6" height="6"/><rect x="15" y="9" width="6" height="6"/><rect x="3" y="15" width="6" height="6"/><path d="M9 6h3v12H9M12 12h3"/></symbol>
    <symbol id="sb-layers" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 2 10 5-10 5L2 7l10-5Z"/><path d="m2 17 10 5 10-5M2 12l10 5 10-5"/></symbol>
    <symbol id="sb-door" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 21V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17M3 21h18M14 12v.01"/></symbol>
    <symbol id="sb-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/></symbol>
    <symbol id="sb-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M2 21v-1a6 6 0 0 1 12 0v1M16 4.5a3.5 3.5 0 0 1 0 7M22 21v-1a6 6 0 0 0-4-5.6"/></symbol>
    <symbol id="sb-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
    <symbol id="sb-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 21v-8M12 21V4M19 21v-12"/></symbol>
    <symbol id="sb-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></symbol>
    <symbol id="sb-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
    <symbol id="sb-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></symbol>
    <symbol id="sb-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
  </defs>
</svg>

<!-- Style du mode réduit (uniquement sur grand écran) -->
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

<!-- Fond sombre (mobile) -->
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
      <a href="AccueilSecretaire.php" class="<?= lien('dashboard', $active, $base, $on) ?>"><svg class="h-4 w-4 shrink-0"><use href="#sb-grid" /></svg> <span class="label">Tableau de bord</span></a>
      <a href="filiereSecretaire.php" class="<?= lien('filieres', $active, $base, $on) ?>"><svg class="h-4 w-4 shrink-0"><use href="#sb-tree" /></svg> <span class="label">Filières</span></a>
      <a href="niveauSecretaire.php" class="<?= lien('niveaux', $active, $base, $on) ?>"><svg class="h-4 w-4 shrink-0"><use href="#sb-layers" /></svg> <span class="label">Niveaux</span></a>
      <a href="classeSecretaire.php" class="<?= lien('classes', $active, $base, $on) ?>"><svg class="h-4 w-4 shrink-0"><use href="#sb-door" /></svg> <span class="label">Classes</span></a>
      <a href="etudiantSecretaire.php" class="<?= lien('etudiants', $active, $base, $on) ?>"><svg class="h-4 w-4 shrink-0"><use href="#sb-user" /></svg> <span class="label">Étudiants</span></a>
      <a href="formateurSecretaire.php" class="<?= lien('formateurs', $active, $base, $on) ?>"><svg class="h-4 w-4 shrink-0"><use href="#sb-users" /></svg> <span class="label">Formateurs</span></a>
      <a href="matiereSecretaire.php" class="<?= lien('matieres', $active, $base, $on) ?>"><svg class="h-4 w-4 shrink-0"><use href="#sb-book" /></svg> <span class="label">Matières</span></a>
      <a href="edtSecretaire.php" class="<?= lien('edt', $active, $base, $on) ?>"><svg class="h-4 w-4 shrink-0"><use href="#sb-calendar" /></svg> <span class="label">Emploi du temps</span></a>
      <a href="statistiqueSecretaire.php" class="<?= lien('statistiques', $active, $base, $on) ?>"><svg class="h-4 w-4 shrink-0"><use href="#sb-chart" /></svg> <span class="label">Statistiques</span></a>
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
  /* Sidebar repliable : clic sur le logo */
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