<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'secretaire') {
  header('Location: loginEcole221.php');
  exit;
}

require 'config.php';

// Nom et email de la personne connectée (affichage en-tête)
$nomAffiche    = (isset($_SESSION['nom_complet']) && trim($_SESSION['nom_complet']) !== '') ? $_SESSION['nom_complet'] : ($_SESSION['login'] ?? 'Utilisateur');
$emailAffiche  = $_SESSION['email'] ?? ($_SESSION['login'] ?? '');
$prenomAffiche = explode(' ', trim($nomAffiche))[0];

// Compteurs généraux
$compteurs = $pdo->query("SELECT
  (SELECT COUNT(*) FROM filiere)   AS nb_filieres,
  (SELECT COUNT(*) FROM niveau)    AS nb_niveaux,
  (SELECT COUNT(*) FROM classe)    AS nb_classes,
  (SELECT COUNT(*) FROM etudiant)  AS nb_etudiants
")->fetch();

// Répartition garçons / filles
$sexe = $pdo->query("SELECT SUM(sexe='M') AS g, SUM(sexe='F') AS f FROM etudiant")->fetch();
$nbG = (int)$sexe['g'];
$nbF = (int)$sexe['f'];

// 5 derniers étudiants enregistrés
$derniers = $pdo->query("
  SELECT e.id_etudiant, e.matricule, e.nom, e.prenom, e.sexe, c.nom_classe
  FROM etudiant e
  LEFT JOIN classe c ON c.id_classe = e.id_classe
  ORDER BY e.id_etudiant DESC
  LIMIT 5
")->fetchAll();

// Classes les plus remplies (top 5)
$topClasses = $pdo->query("
  SELECT c.nom_classe, c.capacite, COUNT(e.id_etudiant) AS eff
  FROM classe c
  LEFT JOIN etudiant e ON e.id_classe = c.id_classe
  GROUP BY c.id_classe, c.nom_classe, c.capacite
  ORDER BY eff DESC
  LIMIT 5
")->fetchAll();
$maxEff = 1;
foreach ($topClasses as $r) { if ((int)$r['eff'] > $maxEff) $maxEff = (int)$r['eff']; }

// Année académique (la plus récente trouvée dans les classes)
$annee = $pdo->query("SELECT annee_academique FROM classe WHERE annee_academique IS NOT NULL ORDER BY annee_academique DESC LIMIT 1")->fetchColumn();

// Date du jour en français
$joursFr = ['Sunday' => 'Dimanche', 'Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi', 'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi'];
$moisFr  = ['January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars', 'April' => 'Avril', 'May' => 'Mai', 'June' => 'Juin', 'July' => 'Juillet', 'August' => 'Août', 'September' => 'Septembre', 'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'];
$dateJour = $joursFr[date('l')] . ' ' . date('j') . ' ' . $moisFr[date('F')] . ' ' . date('Y');

// Badge sexe pour le tableau
function sexeBadge($s) {
  if ($s === 'F') {
    return '<span class="rounded-full border border-[#fbcfe8] bg-[#fdf2f8] px-[9px] py-[3px] text-xs font-medium text-[#be185d]">Féminin</span>';
  }
  return '<span class="rounded-full border border-[#bfdbfe] bg-[#eff6ff] px-[9px] py-[3px] text-xs font-medium text-[#1d4ed8]">Masculin</span>';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Tableau de bord Secrétaire</title>
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
          colors: { primary: '#e11d2a', ink: '#141414', muted: '#5f5e5e', page: '#f4f2fd', primarydark: '#8f1119' },
        },
      },
    };
  </script>
</head>
<body class="font-sans bg-page text-ink">

  <!-- Icônes -->
  <svg class="hidden" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <symbol id="i-tree" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="6" height="6"/><rect x="15" y="9" width="6" height="6"/><rect x="3" y="15" width="6" height="6"/><path d="M9 6h3v12H9M12 12h3"/></symbol>
      <symbol id="i-layers" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 2 10 5-10 5L2 7l10-5Z"/><path d="m2 17 10 5 10-5M2 12l10 5 10-5"/></symbol>
      <symbol id="i-door" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 21V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17M3 21h18M14 12v.01"/></symbol>
      <symbol id="i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/></symbol>
      <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
      <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></symbol>
      <symbol id="i-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></symbol>
      <symbol id="i-male" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="14" r="6"/><path d="M19 5l-6 6M15 5h4v4"/></symbol>
      <symbol id="i-female" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M12 14v7M9 18h6"/></symbol>
      <symbol id="i-chevron-right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></symbol>
    </defs>
  </svg>

  <!-- ============ SIDEBAR ============ -->
  <?php $active = 'dashboard'; include 'sidebarSecretaire.php'; ?>

  <div class="app-content min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" aria-controls="sidebar" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden">
          <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h2 class="text-sm font-semibold">Tableau de bord - Secrétaire</h2>
      </div>
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
          <img src="https://i.pravatar.cc/64?img=47" alt="Photo" class="h-8 w-8 rounded-full object-cover" />
          <div class="hidden sm:block">
            <p class="text-sm font-semibold leading-[17.5px]"><?= htmlspecialchars($nomAffiche) ?></p>
            <p class="text-xs leading-3 text-muted"><?= htmlspecialchars($emailAffiche) ?></p>
          </div>
        </div>
        <span class="hidden h-6 w-px bg-[#e3e1ec] sm:block"></span>
        <span class="whitespace-nowrap rounded-full bg-primary px-2 py-1 text-xs font-semibold text-white shadow-sm">Secrétaire / Admin</span>
      </div>
    </header>

    <main class="px-4 py-8 sm:px-6 lg:px-12">
      <div class="mx-auto flex max-w-[1180px] flex-col gap-9 rounded-2xl p-0 sm:p-6">

        <!-- Bannière de bienvenue -->
        <section class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#e11d2a] via-[#b91c1c] to-[#991b1b] p-8 shadow-md">
          <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/5 blur-xl"></div>
          <div class="relative flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
            <div class="flex flex-col gap-1.5">
              <h1 class="text-3xl font-bold leading-[44px] tracking-[-0.9px] text-white sm:text-4xl">
                Bonjour <?= htmlspecialchars($prenomAffiche) ?>, bienvenue sur<br />École 221
              </h1>
              <p class="text-base text-[#f4f4f5]">Portail d'administration académique et de gestion de l'établissement</p>
            </div>
            <div class="rounded-xl bg-black/10 px-6 py-3.5 text-right backdrop-blur-[2px]">
              <p class="text-xl font-bold leading-7 text-white"><?= $dateJour ?></p>
              <?php if (!empty($annee)): ?><p class="text-sm font-medium tracking-[0.35px] text-white/85">Année <?= htmlspecialchars($annee) ?></p><?php endif; ?>
            </div>
          </div>
        </section>

        <!-- Cartes statistiques -->
        <section class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-6 xl:gap-6">
          <div class="flex flex-col items-center rounded-xl bg-white p-6 shadow-sm transition duration-200 hover:shadow-[8px_0_16px_-4px_rgba(0,0,0,0.2)]">
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-tree" /></svg></span>
            <p class="mt-4 text-3xl font-semibold leading-9 tracking-[-0.75px]"><?= (int)$compteurs['nb_filieres'] ?></p>
            <p class="mt-1 text-xs font-medium text-muted">Filières</p>
          </div>
          <div class="flex flex-col items-center rounded-xl bg-white p-6 shadow-sm transition duration-200 hover:shadow-[8px_0_16px_-4px_rgba(0,0,0,0.2)]">
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-layers" /></svg></span>
            <p class="mt-4 text-3xl font-semibold leading-9 tracking-[-0.75px]"><?= (int)$compteurs['nb_niveaux'] ?></p>
            <p class="mt-1 text-xs font-medium text-muted">Niveaux</p>
          </div>
          <div class="flex flex-col items-center rounded-xl bg-white p-6 shadow-sm transition duration-200 hover:shadow-[8px_0_16px_-4px_rgba(0,0,0,0.2)]">
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-door" /></svg></span>
            <p class="mt-4 text-3xl font-semibold leading-9 tracking-[-0.75px]"><?= (int)$compteurs['nb_classes'] ?></p>
            <p class="mt-1 text-xs font-medium text-muted">Classes</p>
          </div>
          <div class="flex flex-col items-center rounded-xl bg-white p-6 shadow-sm transition duration-200 hover:shadow-[8px_0_16px_-4px_rgba(0,0,0,0.2)]">
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-user" /></svg></span>
            <p class="mt-4 text-3xl font-semibold leading-9 tracking-[-0.75px]"><?= (int)$compteurs['nb_etudiants'] ?></p>
            <p class="mt-1 text-xs font-medium text-muted">Étudiants</p>
          </div>
          <div class="flex flex-col items-center rounded-xl bg-white p-6 shadow-sm transition duration-200 hover:shadow-[8px_0_16px_-4px_rgba(0,0,0,0.2)]">
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-male" /></svg></span>
            <p class="mt-4 text-3xl font-semibold leading-9 tracking-[-0.75px]"><?= $nbG ?></p>
            <p class="mt-1 text-xs font-medium text-muted">Garçons</p>
          </div>
          <div class="flex flex-col items-center rounded-xl bg-white p-6 shadow-sm transition duration-200 hover:shadow-[8px_0_16px_-4px_rgba(0,0,0,0.2)]">
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-female" /></svg></span>
            <p class="mt-4 text-3xl font-semibold leading-9 tracking-[-0.75px]"><?= $nbF ?></p>
            <p class="mt-1 text-xs font-medium text-muted">Filles</p>
          </div>
        </section>

        <!-- Derniers étudiants -->
        <section class="flex flex-col gap-6">
          <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
              <h2 class="text-2xl font-bold leading-8 tracking-[-0.6px] text-[#1a1b22]">Derniers étudiants enregistrés</h2>
              <p class="text-xs text-muted">Inscriptions académiques récentes</p>
            </div>
            <a href="etudiantSecretaire.php" class="flex items-center justify-center gap-[7px] rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark">
              <svg class="h-3 w-3"><use href="#i-plus" /></svg> Ajouter
            </a>
          </div>

          <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
            <table class="w-full min-w-[820px] text-left">
              <thead class="border-b border-[#e5e7eb] bg-[#f9fafb]">
                <tr class="text-xs font-semibold uppercase tracking-[0.6px] text-[#6b7280]">
                  <th class="px-4 py-3.5 font-semibold">Matricule</th>
                  <th class="px-4 py-3.5 font-semibold">Nom</th>
                  <th class="px-4 py-3.5 font-semibold">Prénom</th>
                  <th class="px-4 py-3.5 font-semibold">Sexe</th>
                  <th class="px-4 py-3.5 font-semibold">Classe</th>
                  <th class="px-4 py-3.5 text-right font-semibold">Fiche</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-[#f3f4f6] text-sm">
                <?php if (count($derniers) === 0): ?>
                  <tr><td colspan="6" class="px-4 py-10 text-center text-sm text-muted">Aucun étudiant enregistré pour l'instant.</td></tr>
                <?php else: ?>
                  <?php foreach ($derniers as $e): ?>
                  <tr class="cursor-pointer transition-colors hover:bg-[#F3F4F6]" onclick="window.location.href='detailsEtudiantSecretaire.php?id=<?= $e['id_etudiant'] ?>'">
                    <td class="px-4 py-5 font-mono text-xs font-bold text-[#111827]"><?= htmlspecialchars($e['matricule']) ?></td>
                    <td class="px-4 py-4 font-bold"><?= htmlspecialchars($e['nom']) ?></td>
                    <td class="px-4 py-4 text-[#374151]"><?= htmlspecialchars($e['prenom']) ?></td>
                    <td class="px-4 py-4"><?= sexeBadge($e['sexe']) ?></td>
                    <td class="px-4 py-4 font-medium text-[#1f2937]"><?= htmlspecialchars($e['nom_classe'] ?? 'Non affecté') ?></td>
                    <td class="px-4 py-4 text-right"><span class="inline-flex items-center justify-center text-primary"><svg class="h-4 w-4"><use href="#i-chevron-right" /></svg></span></td>
                  </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </section>

        <!-- Classes les plus remplies -->
        <section class="flex flex-col gap-6 rounded-2xl bg-white p-6 shadow-sm">
          <div>
            <h2 class="text-2xl font-bold leading-8 tracking-[-0.6px] text-[#1a1b22]">Classes les plus remplies</h2>
            <p class="text-xs text-muted">Répartition par effectifs décroissants</p>
          </div>

          <div class="flex flex-col gap-5 pb-2">
            <?php if (count($topClasses) === 0): ?>
              <p class="text-sm text-muted">Aucune classe pour l'instant.</p>
            <?php else: ?>
              <?php foreach ($topClasses as $r): ?>
                <?php $w = round((int)$r['eff'] / $maxEff * 100); ?>
                <div class="flex flex-col gap-2">
                  <div class="flex items-center justify-between"><span class="font-semibold text-[#1a1b22]"><?= htmlspecialchars($r['nom_classe']) ?></span><span class="text-sm font-bold text-[#b8001a]"><?= (int)$r['eff'] ?> étudiant<?= (int)$r['eff'] > 1 ? 's' : '' ?></span></div>
                  <div class="h-3 overflow-hidden rounded-full bg-[#e8e7f1]"><div class="h-full rounded-full bg-primary" style="width:<?= $w ?>%"></div></div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>

          <div class="flex items-center gap-3 rounded-xl bg-page p-3.5 text-xs text-muted">
            <svg class="h-[17px] w-[17px] shrink-0"><use href="#i-info" /></svg>
            <p>La longueur de la barre est proportionnelle à l'effectif de la classe.</p>
          </div>
        </section>

      </div>
    </main>
  </div>

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
    window.addEventListener('keydown', (e) => { if (e.key === 'Escape') toggleMenu(false); });
  </script>
</body>
</html>