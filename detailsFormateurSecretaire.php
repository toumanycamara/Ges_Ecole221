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

// Id du formateur
$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM formateur WHERE id_formateur = :id");
$stmt->execute(['id' => $id]);
$fo = $stmt->fetch();

// Si le formateur n'existe pas, retour à la liste
if (!$fo) {
  header('Location: formateurSecretaire.php');
  exit;
}

// Matières enseignées
$stmt = $pdo->prepare("
  SELECT m.nom_matiere, m.code_matiere
  FROM enseigner en
  JOIN matiere m ON m.id_matiere = en.id_matiere
  WHERE en.id_formateur = :id
  ORDER BY m.nom_matiere
");
$stmt->execute(['id' => $id]);
$matieres = $stmt->fetchAll();

// Classes où il intervient (via ses séances)
$stmt = $pdo->prepare("
  SELECT DISTINCT c.nom_classe, c.salle
  FROM seance s
  JOIN classe c ON c.id_classe = s.id_classe
  WHERE s.id_formateur = :id
  ORDER BY c.nom_classe
");
$stmt->execute(['id' => $id]);
$classes = $stmt->fetchAll();

// Photo + statut
$photo = !empty($fo['photo']) ? $fo['photo'] : 'https://i.pravatar.cc/120?img=12';
$sexeLibelle = $fo['sexe'] === 'F' ? 'Féminin' : 'Masculin';

function statutInfo($s) {
  if ($s === 'Conge')   return ['En congé', 'border-[#fde68a] bg-[#fffbeb] text-[#b45309]'];
  if ($s === 'Inactif') return ['Inactif',  'border-[#e5e7eb] bg-[#f3f4f6] text-[#6b7280]'];
  return ['Actif', 'border-[#bbf7d0] bg-[#f0fdf4] text-[#15803d]'];
}
[$stLabel, $stClass] = statutInfo($fo['statut']);
?>
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
      <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
      <symbol id="i-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-chevron-right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></symbol>
      <symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></symbol>
    </defs>
  </svg>

  <!-- ============ SIDEBAR ============ -->
  <?php $active = 'formateurs'; include 'sidebarSecretaire.php'; ?>

  <div class="app-content min-h-screen lg:ml-[260px]">
    <header class="sticky top-0 z-10 flex h-[100px] items-center justify-between gap-4 bg-white px-4 shadow-sm sm:px-6 lg:px-12">
      <div class="flex items-center gap-3">
        <button id="openMenu" type="button" aria-label="Ouvrir le menu" class="rounded-lg p-2 text-ink hover:bg-primarydark hover:text-white lg:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
        <h2 class="text-sm font-semibold">Détail du formateur</h2>
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
            <a href="formateurSecretaire.php" class="hover:text-ink">Formateurs</a><svg class="h-3.5 w-3.5"><use href="#i-chevron-right" /></svg>
            <span class="font-semibold text-primary">Fiche formateur</span>
          </nav>
          <div class="flex items-center gap-3">
            <a href="formateurSecretaire.php" class="text-sm font-medium text-primary hover:underline">← Retour à la liste</a>
            <button type="button" id="btnDel" class="flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-trash" /></svg> Supprimer</button>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
          <!-- Profil + coordonnées -->
          <section class="rounded-2xl bg-white p-6 shadow-sm lg:col-span-2">
            <div class="flex items-center gap-4">
              <img src="<?= htmlspecialchars($photo) ?>" alt="" class="h-16 w-16 rounded-full object-cover ring-2 ring-primary/20" />
              <div>
                <h1 class="text-xl font-bold text-[#1a1b22]"><?= htmlspecialchars($fo['prenom'] . ' ' . $fo['nom']) ?></h1>
                <p class="text-xs text-muted"><?= htmlspecialchars($fo['specialite'] ?: 'Spécialité non renseignée') ?></p>
                <span class="mt-1 inline-block rounded-full border px-2 py-0.5 text-xs font-medium <?= $stClass ?>"><?= $stLabel ?></span>
              </div>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
              <div><p class="text-xs text-muted">Matricule</p><p class="font-mono font-medium text-[#1f2937]"><?= htmlspecialchars($fo['matricule_formateur']) ?></p></div>
              <div><p class="text-xs text-muted">Sexe</p><p class="font-medium text-[#1f2937]"><?= $sexeLibelle ?></p></div>
              <div><p class="text-xs text-muted">Spécialité</p><p class="font-medium text-[#1f2937]"><?= htmlspecialchars($fo['specialite'] ?: '—') ?></p></div>
              <div><p class="text-xs text-muted">Statut</p><p class="font-medium text-[#1f2937]"><?= $stLabel ?></p></div>
            </div>

            <div class="mt-5 rounded-xl bg-page p-4">
              <h4 class="mb-3 text-sm font-bold text-[#1a1b22]">Coordonnées</h4>
              <div class="space-y-3">
                <div><p class="mb-1 text-xs font-semibold uppercase tracking-wide text-muted">Email</p><div class="rounded-lg border border-[#e5e7eb] bg-white px-3 py-2 text-sm text-[#374151]"><?= htmlspecialchars($fo['email'] ?: '—') ?></div></div>
                <div><p class="mb-1 text-xs font-semibold uppercase tracking-wide text-muted">Téléphone</p><div class="rounded-lg border border-[#e5e7eb] bg-white px-3 py-2 text-sm text-[#374151]"><?= htmlspecialchars($fo['telephone'] ?: '—') ?></div></div>
              </div>
            </div>
          </section>

          <!-- Matières + classes -->
          <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h4 class="mb-3 text-sm font-bold text-[#1a1b22]">Matières enseignées</h4>
            <div class="space-y-3">
              <?php if (count($matieres) === 0): ?>
                <p class="text-sm text-muted">Aucune matière affectée pour l'instant.</p>
              <?php else: ?>
                <?php foreach ($matieres as $m): ?>
                <div class="flex items-center gap-3 rounded-xl border border-[#f3f4f6] p-3">
                  <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#ffdad7] text-primary"><svg class="h-4 w-4"><use href="#i-book" /></svg></span>
                  <div>
                    <p class="text-sm font-semibold text-[#1a1b22]"><?= htmlspecialchars($m['nom_matiere']) ?></p>
                    <?php if (!empty($m['code_matiere'])): ?><p class="text-xs text-muted"><?= htmlspecialchars($m['code_matiere']) ?></p><?php endif; ?>
                  </div>
                </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>

            <h4 class="mb-3 mt-6 text-sm font-bold text-[#1a1b22]">Classes d'intervention</h4>
            <div class="space-y-3">
              <?php if (count($classes) === 0): ?>
                <p class="text-sm text-muted">Aucune séance programmée pour l'instant.</p>
              <?php else: ?>
                <?php foreach ($classes as $c): ?>
                <div class="flex items-center gap-3 rounded-xl border border-[#f3f4f6] p-3">
                  <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#eff6ff] text-xs font-bold text-[#2563eb]">CL</span>
                  <div>
                    <p class="text-sm font-semibold text-[#1a1b22]"><?= htmlspecialchars($c['nom_classe']) ?></p>
                    <?php if (!empty($c['salle'])): ?><p class="text-xs text-muted">Salle <?= htmlspecialchars($c['salle']) ?></p><?php endif; ?>
                  </div>
                </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </section>
        </div>
      </div>
    </main>
  </div>

  <!-- ============ MODALE : CONFIRMER SUPPRESSION ============ -->
  <div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-xl">
      <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#ffdad7] text-primary"><svg class="h-7 w-7"><use href="#i-trash" /></svg></div>
      <h3 class="text-lg font-bold text-[#1a1b22]">Confirmer la suppression</h3>
      <p class="mt-2 text-sm text-muted">Voulez-vous vraiment supprimer le formateur <span class="font-semibold text-[#1a1b22]"><?= htmlspecialchars($fo['prenom'] . ' ' . $fo['nom']) ?></span> ? Cette action est irréversible.</p>
      <div class="mt-6 flex items-center justify-center gap-3">
        <button type="button" data-cancel-delete class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="button" id="confirmDelete" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark">Supprimer</button>
      </div>
    </div>
  </div>

  <!-- Suppression : envoyée à la page liste qui gère tout (toast + annulation) -->
  <form id="deleteForm" method="post" action="formateurSecretaire.php" class="hidden">
    <input type="hidden" name="delete_id" value="<?= $fo['id_formateur'] ?>" />
  </form>

  <script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleMenu = (open) => { sidebar.classList.toggle('-translate-x-full', !open); overlay.classList.toggle('hidden', !open); document.body.classList.toggle('overflow-hidden', open); };
    document.getElementById('openMenu').addEventListener('click', () => toggleMenu(true));
    document.getElementById('closeMenu').addEventListener('click', () => toggleMenu(false));
    overlay.addEventListener('click', () => toggleMenu(false));

    /* ----- Suppression ----- */
    const modalDelete = document.getElementById('modalDelete');
    const openModal = () => { modalDelete.classList.remove('hidden'); modalDelete.classList.add('flex'); };
    const closeModal = () => { modalDelete.classList.add('hidden'); modalDelete.classList.remove('flex'); };
    document.getElementById('btnDel').addEventListener('click', openModal);
    document.querySelectorAll('[data-cancel-delete]').forEach((b) => b.addEventListener('click', closeModal));
    modalDelete.addEventListener('click', (e) => { if (e.target === modalDelete) closeModal(); });
    document.getElementById('confirmDelete').addEventListener('click', () => { document.getElementById('deleteForm').submit(); });

    window.addEventListener('keydown', (e) => { if (e.key === 'Escape') { closeModal(); toggleMenu(false); } });
  </script>
</body>
</html>