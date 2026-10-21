<?php
session_start();
if (!isset ($_SESSION['role'])||$_SESSION['role']!=='secretaire') {
  header('Location: loginEcole221.php');
  exit;
}

require 'config.php';

?>

<?php
$sql = "SELECT c.id_classe, c.nom_classe, c.salle, c.capacite,
               f.nom_filiere AS filiere,
               n.libelle_niveau AS niveau,
               COUNT(e.id_etudiant) AS effectif
        FROM classe c
        JOIN filiere f ON c.id_filiere = f.id_filiere
        JOIN niveau  n ON c.id_niveau  = n.id_niveau
        LEFT JOIN etudiant e ON e.id_classe = c.id_classe
        GROUP BY c.id_classe, c.nom_classe, c.salle, c.capacite, f.nom_filiere, n.libelle_niveau
        ORDER BY c.nom_classe";

$stmt = $pdo->query($sql);
$classe = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Classes</title>
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
      <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></symbol>
      <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
      <symbol id="i-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6"/></symbol>
      <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
      <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
      <symbol id="i-filter" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M7 12h10M10 18h4"/></symbol>
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
            <p class="text-xs font-semibold uppercase tracking-wide text-muted">Administration académique • Année 2024-2025</p>
            <h1 class="mt-1 text-2xl font-bold leading-8 tracking-[-0.6px] text-[#1a1b22]">Liste des classes</h1>
            <p class="mt-1 text-xs text-muted">Gestion des promotions et suivi des effectifs par classe</p>
          </div>
          <div class="flex items-center gap-3">
            <span id="countBadge" class="flex items-center gap-1.5 rounded-full bg-page px-3 py-1.5 text-xs font-semibold text-[#374151]"><svg class="h-3.5 w-3.5 text-muted"><use href="#i-filter" /></svg> 6 classes actives</span>
            <button type="button" id="btnAdd" class="flex items-center justify-center gap-[7px] rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primarydark">
              <svg class="h-3 w-3"><use href="#i-plus" /></svg> Ajouter une classe
            </button>
          </div>
        </div>

        <!-- Grille de cartes -->
        <section id="grid" class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

           <?php foreach ($classe as $c): ?>

          <article class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition hover:shadow-md">
            <div class="h-1.5 bg-primary"></div>
            <div class="flex flex-1 flex-col gap-3 p-5">
              <div class="flex items-start justify-between gap-3">
                <h3 class="font-bold text-[#1a1b22]"><?= htmlspecialchars($c['nom_classe']) ?></h3>
                <span class="shrink-0 rounded-md border border-[#e5e7eb] px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-muted"><?= htmlspecialchars($c['salle']) ?></span>
              </div>
              <div class="space-y-1 text-sm text-muted">
                <p><span class="text-[#374151]">Filière :</span> <?= htmlspecialchars($c['filiere']) ?></p>
                <p><span class="text-[#374151]">Niveau :</span> <?= htmlspecialchars($c['niveau']) ?></p>
                <p><span class="text-[#374151]">Capacité :</span> <?= htmlspecialchars($c['capacite']) ?> places</p>
              </div>
            </div>
          </article>

           <?php endforeach;  ?>

        </section>
      </div>
    </main>
  </div>

  <!-- ============ DRAWER : NOUVELLE CLASSE ============ -->
  <div id="drawerAdd" class="fixed inset-0 z-50 hidden">
    <div data-close class="absolute inset-0 bg-black/50"></div>
    <aside class="absolute inset-y-0 right-0 flex w-full max-w-md translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out">
      <div class="flex items-start justify-between gap-3 border-b border-[#eef0f2] px-6 py-4">
        <div class="flex items-center gap-3">
          <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#ffdad7] text-primary"><svg class="h-5 w-5"><use href="#i-plus" /></svg></span>
          <div>
            <h3 class="text-lg font-bold text-[#1a1b22]">Nouvelle classe</h3>
            <p class="text-xs text-muted">Créer une nouvelle classe et l'attacher à une filière.</p>
          </div>
        </div>
        <button type="button" data-close class="rounded-lg bg-page p-1.5 text-muted hover:bg-[#e8e7ec]" aria-label="Fermer"><svg class="h-5 w-5"><use href="#i-x" /></svg></button>
      </div>

      <form id="classeForm" class="flex flex-1 flex-col gap-4 overflow-y-auto px-6 py-5">
        <h4 class="text-xs font-semibold uppercase tracking-wide text-[#1a1b22]">Informations de la classe</h4>
        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="c-nom">Nom de la classe</label>
          <input id="c-nom" type="text" placeholder="ex: L3 Génie Logiciel" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
        </div>
        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="c-filiere">Filière</label>
          <select id="c-filiere" class="w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm text-muted outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
            <option value="">Sélectionner une filière</option>
            <option>Génie Logiciel</option>
            <option>Développement Web</option>
            <option>Référent Digital</option>
            <option>Cyber Sécurité</option>
            <option>Data Analytics</option>
          </select>
        </div>
        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="c-niveau">Niveau</label>
          <select id="c-niveau" class="w-full rounded-lg border border-[#e5e7eb] bg-white px-3 py-2.5 text-sm text-muted outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
            <option value="">Sélectionner un niveau</option>
            <option>Licence 1</option>
            <option>Licence 2</option>
            <option>Licence 3</option>
            <option>Master 1</option>
            <option>Master 2</option>
          </select>
        </div>
        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="c-salle">Salle</label>
          <input id="c-salle" type="text" placeholder="ex: A2, B1" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
        </div>
        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted" for="c-capacite">Capacité</label>
          <input id="c-capacite" type="number" placeholder="ex: 40" class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2.5 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
        </div>
      </form>

      <div class="grid grid-cols-2 gap-3 border-t border-[#eef0f2] px-6 py-4">
        <button type="button" data-close class="rounded-lg border border-[#e5e7eb] px-5 py-2.5 text-sm font-medium text-[#374151] hover:bg-page">Annuler</button>
        <button type="submit" form="classeForm" class="flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primarydark"><svg class="h-4 w-4"><use href="#i-plus" /></svg> Enregistrer</button>
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
      <p class="mt-2 text-sm text-muted">Voulez-vous vraiment supprimer la classe <span id="delName" class="font-semibold text-[#1a1b22]"></span> ? Cette action est irréversible.</p>
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

    /* ----- Compteur réel ----- */
    (function () {
      const n = document.querySelectorAll('#grid article').length;
      document.getElementById('countBadge').lastChild.textContent = ' ' + n + (n > 1 ? ' classes actives' : ' classe active');
    })();

    /* ----- Modale de suppression ----- */
    const modalDelete = document.getElementById('modalDelete');
    const openModal = () => { modalDelete.classList.remove('hidden'); modalDelete.classList.add('flex'); };
    const closeModal = () => { modalDelete.classList.add('hidden'); modalDelete.classList.remove('flex'); };
    let cardToDelete = null;

    const updateCount = () => {
      const n = document.querySelectorAll('#grid article').length;
      document.getElementById('countBadge').lastChild.textContent = ' ' + n + (n > 1 ? ' classes actives' : ' classe active');
    };

    /* ----- Clic sur une carte -> fiche ; corbeille -> suppression ----- */
    document.querySelectorAll('#grid article').forEach((card) => {
      card.classList.add('relative', 'cursor-pointer');
      card.addEventListener('click', () => { window.location.href = 'detailsClasseSecretaire.php'; });

      const btn = document.createElement('button');
      btn.type = 'button';
      btn.setAttribute('aria-label', 'Supprimer la classe');
      btn.className = 'absolute right-3 top-3 z-10 rounded-lg bg-white/90 p-1.5 text-[#9ca3af] shadow-sm transition hover:bg-primary hover:text-white';
      btn.innerHTML = '<svg class="h-4 w-4"><use href="#i-trash"/></svg>';
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        cardToDelete = card;
        document.getElementById('delName').textContent = '« ' + card.querySelector('h3').textContent.trim() + ' »';
        openModal();
      });
      card.appendChild(btn);
    });

    document.querySelectorAll('[data-cancel-delete]').forEach((b) =>
      b.addEventListener('click', () => { closeModal(); showToast('info', 'Suppression annulée'); })
    );
    modalDelete.addEventListener('click', (e) => { if (e.target === modalDelete) closeModal(); });
    document.getElementById('confirmDelete').addEventListener('click', () => {
      if (!cardToDelete) { closeModal(); return; }
      const card = cardToDelete;
      const parent = card.parentNode;
      const next = card.nextElementSibling;
      card.remove();
      cardToDelete = null;
      updateCount();
      closeModal();
      showToast('success', 'Classe supprimée', 'La classe a bien été retirée de la liste.', () => {
        if (next) parent.insertBefore(card, next); else parent.appendChild(card);
        updateCount();
        showToast('info', 'Suppression annulée', 'La classe a été restaurée.');
      });
    });

    /* ----- Toasts (toujours un bouton Annuler) ----- */
    function showToast(type, title, msg, action) {
      const map = {
        success: { border: '#16a34a', icon: '#i-check', tint: '#dcfce7', text: '#15803d' },
        error:   { border: '#e11d2a', icon: '#i-alert', tint: '#ffdad7', text: '#b8001a' },
        info:    { border: '#6b7280', icon: '#i-alert', tint: '#f3f4f6', text: '#374151' },
      };
      const c = map[type] || map.info;
      const el = document.createElement('div');
      el.className = 'rounded-xl border-l-4 bg-white p-3.5 shadow-md';
      el.style.borderLeftColor = c.border;
      el.innerHTML =
        '<div class="flex items-start gap-3">' +
          '<span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full" style="background:' + c.tint + ';color:' + c.text + '"><svg class="h-4 w-4"><use href="' + c.icon + '"/></svg></span>' +
          '<div class="flex-1"><p class="text-sm font-semibold text-[#1a1b22]">' + title + '</p>' + (msg ? '<p class="text-xs text-muted">' + msg + '</p>' : '') + '</div>' +
          '<button class="js-close text-muted hover:text-ink" aria-label="Fermer"><svg class="h-4 w-4"><use href="#i-x"/></svg></button>' +
        '</div>' +
        '<div class="mt-2 flex justify-end"><button class="js-action rounded-lg border border-[#e5e7eb] px-3 py-1 text-xs font-medium text-primary hover:bg-page">Annuler</button></div>';
      const remove = () => el.remove();
      el.querySelector('.js-close').addEventListener('click', remove);
      el.querySelector('.js-action').addEventListener('click', () => { if (action) action(); remove(); });
      document.getElementById('toasts').appendChild(el);
      setTimeout(remove, action ? 6000 : 4000);
    }

    /* ----- Drawer d'ajout ----- */
    const drawerAdd = document.getElementById('drawerAdd');
    const drawerPanel = drawerAdd.querySelector('aside');
    const openDrawer = () => {
      document.getElementById('classeForm').reset();
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
    document.getElementById('btnAdd').addEventListener('click', openDrawer);
    drawerAdd.querySelectorAll('[data-close]').forEach((b) => b.addEventListener('click', closeDrawer));
    drawerAdd.addEventListener('click', (e) => { if (!drawerPanel.contains(e.target)) closeDrawer(); });

    const clearErrors = () => document.getElementById('c-nom').classList.remove('border-primary', 'ring-2', 'ring-primary/30');

    document.getElementById('classeForm').addEventListener('submit', (e) => {
      e.preventDefault();
      clearErrors();
      const nom = document.getElementById('c-nom');
      if (!nom.value.trim()) { nom.classList.add('border-primary', 'ring-2', 'ring-primary/30'); showToast('error', 'Champ obligatoire', 'Veuillez saisir le nom de la classe.'); return; }
      closeDrawer();
      showToast('success', 'Classe ajoutée', 'La classe a bien été enregistrée.');
    });

    /* ----- Échap ferme tout ----- */
    window.addEventListener('keydown', (e) => {
      if (e.key !== 'Escape') return;
      closeDrawer(); closeModal(); toggleMenu(false);
    });
  </script>
</body>
</html>