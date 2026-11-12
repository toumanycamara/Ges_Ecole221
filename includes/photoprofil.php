<?php
/*
  PHOTO DE PROFIL (fichier unique) - École 221
  --------------------------------------------------------------
  Gère tout : réception de l'image, enregistrement, affichage de l'avatar
  cliquable et message de confirmation. Fonctionne sur n'importe quelle page,
  sans modifier la base de données.

  Dans la page (après session_start() et le contrôle du rôle, AVANT tout HTML) :
      require_once __DIR__ . '/../../includes/photoProfil.php';

  Dans l'en-tête, à la place de l'ancienne <img> :
      <?= avatarProfil($prenomAffiche) ?>

  Juste après <main ...> pour le message :
      <?= messagePhoto() ?>
*/
if (session_status() === PHP_SESSION_NONE) session_start();

const PHOTO_MAX_OCTETS = 2 * 1024 * 1024; // 2 Mo

function photoDossier(): string { return __DIR__ . '/../public/uploads/profils'; }
function photoBase(): string    { return 'sec_' . substr(hash('sha256', $_SESSION['login'] ?? ''), 0, 24); }

/* URL de la photo (relative à public/pages/) ou null */
function photoUrl(): ?string {
  if (empty($_SESSION['login'])) return null;
  $fichiers = glob(photoDossier() . '/' . photoBase() . '.*');
  return $fichiers ? '../uploads/profils/' . basename($fichiers[0]) . '?v=' . filemtime($fichiers[0]) : null;
}

if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));

/* ---------- Réception de l'image (POST) ---------- */
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action_photo'])) {
  $retour = basename($_SERVER['SCRIPT_NAME']);   // on revient sur la même page

  $fin = function (string $type, string $message) use ($retour): void {
    $_SESSION['flash_photo'] = ['type' => $type, 'message' => $message];
    header('Location: ' . $retour);
    exit;
  };

  if (($_SESSION['role'] ?? '') !== 'secretaire')                          $fin('erreur', 'Accès refusé.');
  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? ''))               $fin('erreur', 'Session expirée, réessayez.');

  $f = $_FILES['photo'] ?? null;
  if (!$f || $f['error'] !== UPLOAD_ERR_OK) {
    $trop = $f && in_array($f['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true);
    $fin('erreur', $trop ? 'Image trop lourde (2 Mo maximum).' : 'Aucune image reçue.');
  }
  if ($f['size'] > PHOTO_MAX_OCTETS)  $fin('erreur', 'Image trop lourde (2 Mo maximum).');
  if (!is_uploaded_file($f['tmp_name'])) $fin('erreur', 'Fichier invalide.');

  // Vrai type de l'image (on ne se fie pas à l'extension)
  $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
  $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
  if (!isset($extensions[$mime]) || @getimagesize($f['tmp_name']) === false) {
    $fin('erreur', 'Format non accepté (JPG, PNG ou WebP).');
  }

  // Dossier + interdiction d'exécuter des scripts dedans
  $dossier = photoDossier();
  if (!is_dir($dossier) && !mkdir($dossier, 0755, true)) $fin('erreur', 'Impossible de créer le dossier des photos.');
  $ht = $dossier . '/.htaccess';
  if (!file_exists($ht)) file_put_contents($ht, "<FilesMatch \"\\.(php|phtml|phar)$\">\n  Require all denied\n</FilesMatch>\n");

  // Remplacer l'ancienne photo
  foreach (glob($dossier . '/' . photoBase() . '.*') ?: [] as $ancien) @unlink($ancien);
  if (!move_uploaded_file($f['tmp_name'], $dossier . '/' . photoBase() . '.' . $extensions[$mime])) {
    $fin('erreur', "Impossible d'enregistrer l'image.");
  }
  $fin('ok', 'Photo de profil mise à jour.');
}

/* ---------- Affichage ---------- */

/* Avatar cliquable : un clic ouvre le choix d'image, l'envoi est automatique */
function avatarProfil(string $prenom): string {
  $url   = photoUrl();
  $csrf  = htmlspecialchars($_SESSION['csrf']);
  $initi = htmlspecialchars(mb_strtoupper(mb_substr(trim($prenom), 0, 1)));
  $image = $url
    ? '<img src="' . htmlspecialchars($url) . '" alt="Photo de profil" class="h-full w-full object-cover" />'
    : '<span class="flex h-full w-full items-center justify-center bg-[#ffdad7] text-sm font-semibold text-primary">' . $initi . '</span>';

  return <<<HTML
<form method="post" enctype="multipart/form-data" class="shrink-0">
  <input type="hidden" name="action_photo" value="1">
  <input type="hidden" name="csrf" value="{$csrf}">
  <label class="group relative block h-9 w-9 cursor-pointer overflow-hidden rounded-full ring-2 ring-primary/20 sm:h-8 sm:w-8" title="Changer la photo de profil">
    {$image}
    <span class="absolute inset-0 flex items-center justify-center bg-black/50 text-white opacity-0 transition group-hover:opacity-100 group-focus-within:opacity-100">
      <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2Z"/><circle cx="12" cy="13" r="4"/></svg>
    </span>
    <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="sr-only" aria-label="Changer la photo de profil"
           onchange="if(this.files[0]){ if(this.files[0].size>2*1024*1024){alert('Image trop lourde (2 Mo maximum).');this.value='';return;} this.form.submit(); }">
  </label>
</form>
HTML;
}

/* Message de succès ou d'erreur (affiché une seule fois) */
function messagePhoto(): string {
  if (empty($_SESSION['flash_photo'])) return '';
  $m = $_SESSION['flash_photo'];
  unset($_SESSION['flash_photo']);
  $classe = $m['type'] === 'ok' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800';
  return '<div class="mx-auto mb-6 max-w-[1180px] rounded-lg px-4 py-3 text-sm font-medium ' . $classe . '" role="status">'
       . htmlspecialchars($m['message']) . '</div>';
}