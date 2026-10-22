<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'secretaire') {
  header('Location: loginEcole221.php');
  exit;
}

require 'config.php';

// Nom et email de la personne connectée (affichage en-tête)
$nomAffiche   = (isset($_SESSION['nom_complet']) && trim($_SESSION['nom_complet']) !== '') ? $_SESSION['nom_complet'] : ($_SESSION['login'] ?? 'Utilisateur');
$emailAffiche = $_SESSION['email'] ?? ($_SESSION['login'] ?? '');

// Id de l'étudiant à afficher
$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("
  SELECT e.*, c.nom_classe, c.salle, c.annee_academique,
         f.nom_filiere, n.libelle_niveau
  FROM etudiant e
  LEFT JOIN classe  c ON c.id_classe  = e.id_classe
  LEFT JOIN filiere f ON f.id_filiere = c.id_filiere
  LEFT JOIN niveau  n ON n.id_niveau  = c.id_niveau
  WHERE e.id_etudiant = :id
");
$stmt->execute(['id' => $id]);
$etu = $stmt->fetch();

// Si l'étudiant n'existe pas, retour à la liste
if (!$etu) {
  header('Location: etudiantSecretaire.php');
  exit;
}

// Photo (sinon avatar par défaut)
$photo = !empty($etu['photo']) ? $etu['photo'] : 'https://i.pravatar.cc/120?img=12';

// Date de naissance au format jour/mois/année
$dateNaissance = !empty($etu['date_naissance']) ? date('d/m/Y', strtotime($etu['date_naissance'])) : '—';

// Badge sexe
$sexeLibelle = $etu['sexe'] === 'F' ? 'Féminin' : 'Masculin';
$sexeClasse  = $etu['sexe'] === 'F'
  ? 'border-[#fbcfe8] bg-[#fdf2f8] text-[#be185d]'
  : 'border-[#bfdbfe] bg-[#eff6ff] text-[#1d4ed8]';
?>
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
      <symbol id="i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/></symbol>
      <symbol id="i-tree" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="6" height="6"/><rect x="15" y="9" width="6" height="6"/><rect x="3" y="15" width="6" height="6"/><path d="M9 6h3v12H9M12 12h3"/></symbol>
      <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
      <symbol id="i-chevron-right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></symbol>
    </defs>
  </svg>

  <!-- ============ SIDEBAR ============ -->
  <?php $active = 'etudiants'; include 'sidebarSecretaire.php'; ?>

  <div class="app-content min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <h2 class="text-sm font-semibold">Tableau de bord - Secrétaire</h2>
      </div>
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
          <img src="https://i.pravatar.cc/64?img=47" alt="Photo" class="h-8 w-8 rounded-full object-cover" />
          <div class="hidden sm:block"><p class="text-sm font-semibold leading-[17.5px]"><?= htmlspecialchars($nomAffiche) ?></p><p class="text-xs leading-3 text-muted"><?= htmlspecialchars($emailAffiche) ?></p></div>
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
          <a href="etudiantSecretaire.php" class="text-sm font-medium text-primary hover:underline">← Retour à la liste</a>
        </div>

        <div>
          <h1 class="text-2xl font-bold tracking-[-0.6px] text-[#1a1b22]">Fiche de l'étudiant</h1>
          <p class="mt-1 text-xs text-muted">Détails du profil académique et personnel</p>
        </div>

        <!-- Carte profil -->
        <section class="rounded-2xl bg-white p-6 shadow-sm">
          <div class="flex items-center gap-4">
            <img src="<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($etu['prenom'] . ' ' . $etu['nom']) ?>" class="h-16 w-16 rounded-full object-cover ring-2 ring-primary/20" />
            <div>
              <h2 class="text-xl font-bold text-[#1a1b22]"><?= htmlspecialchars($etu['prenom'] . ' ' . $etu['nom']) ?></h2>
              <div class="mt-1 flex flex-wrap items-center gap-2">
                <span class="font-mono text-xs font-bold text-primary"><?= htmlspecialchars($etu['matricule']) ?></span>
                <span class="rounded-full border px-2 py-0.5 text-xs font-medium <?= $sexeClasse ?>"><?= $sexeLibelle ?></span>
              </div>
              <p class="mt-2 text-xs text-muted">Date de naissance <span class="font-semibold text-[#1f2937]"><?= htmlspecialchars($dateNaissance) ?></span></p>
            </div>
          </div>
        </section>

        <!-- Deux colonnes d'informations -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 flex items-center gap-2 text-base font-bold text-[#1a1b22]"><svg class="h-4 w-4 text-primary"><use href="#i-user" /></svg> Informations personnelles</h3>
            <dl class="divide-y divide-[#f3f4f6] text-sm">
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Date de naissance</dt><dd class="text-right font-semibold text-[#1f2937]"><?= htmlspecialchars($dateNaissance) ?></dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Sexe</dt><dd class="text-right font-semibold text-[#1f2937]"><?= $sexeLibelle ?></dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Téléphone</dt><dd class="text-right font-semibold text-[#1f2937]"><?= htmlspecialchars($etu['telephone'] ?: '—') ?></dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Email</dt><dd class="text-right font-semibold text-[#1f2937]"><?= htmlspecialchars($etu['email'] ?: '—') ?></dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Adresse</dt><dd class="text-right font-semibold text-[#1f2937]"><?= htmlspecialchars($etu['adresse'] ?: '—') ?></dd></div>
            </dl>
          </section>

          <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 flex items-center gap-2 text-base font-bold text-[#1a1b22]"><svg class="h-4 w-4 text-primary"><use href="#i-tree" /></svg> Informations académiques</h3>
            <dl class="divide-y divide-[#f3f4f6] text-sm">
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Filière</dt><dd class="text-right font-semibold text-[#1f2937]"><?= htmlspecialchars($etu['nom_filiere'] ?: '—') ?></dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Niveau</dt><dd class="text-right font-semibold text-[#1f2937]"><?= htmlspecialchars($etu['libelle_niveau'] ?: '—') ?></dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Classe</dt><dd class="text-right font-semibold text-[#1f2937]"><?= htmlspecialchars($etu['nom_classe'] ?: 'Non affecté') ?></dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Salle</dt><dd class="text-right font-semibold text-[#1f2937]"><?= htmlspecialchars($etu['salle'] ?: '—') ?></dd></div>
              <div class="flex justify-between gap-4 py-2.5"><dt class="text-muted">Année académique</dt><dd class="text-right font-semibold text-[#1f2937]"><?= htmlspecialchars($etu['annee_academique'] ?: '—') ?></dd></div>
            </dl>
          </section>
        </div>

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