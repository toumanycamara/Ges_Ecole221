<?php

session_start();
require 'config.php';

$erreur = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login      = trim($_POST['login'] ?? '');
  $motdepasse = ($_POST['mot_de_passe'] ?? '');

  $sql  = "SELECT id_utilisateur, login, mot_de_passe, role, id_etudiant, id_formateur
           FROM utilisateur WHERE login = :login AND actif = 1";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['login' => $login]);
  $u = $stmt->fetch();

 

  if ($u && password_verify($motdepasse, $u['mot_de_passe'])) {
    $_SESSION['id_utilisateur'] = $u['id_utilisateur'];
    $_SESSION['login']          = $u['login'];
    $_SESSION['role']           = $u['role'];
    $_SESSION['id_etudiant']    = $u['id_etudiant'];
    $_SESSION['id_formateur']   = $u['id_formateur'];

    // Redirection selon le rôle (les valeurs correspondent à la base)
    switch ($u['role']) {
      case 'secretaire':
        header('Location: AccueilSecretaire.php');
        exit;
      case 'formateur':
        header('Location: accueilFormateur.php');
        exit;
      case 'etudiant':
        header('Location: accueilEtudiant.php');
        exit;
      default:
        $erreur = "Rôle inconnu.";
    }
  } else {
    $erreur = "Identifiant ou mot de passe incorrect.";
  }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>École 221 – Connexion</title>
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
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
      <symbol id="i-shield" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></symbol>
      <symbol id="i-board" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h18M4 3v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3M9 21l3-4 3 4"/></symbol>
      <symbol id="i-cap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10 12 5 2 10l10 5 10-5Z"/><path d="M6 12v5c0 1 2.5 2.5 6 2.5s6-1.5 6-2.5v-5"/></symbol>
      <symbol id="i-mail" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6 10-6"/></symbol>
      <symbol id="i-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></symbol>
      <symbol id="i-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></symbol>
      <symbol id="i-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.9 4.24A9.1 9.1 0 0 1 12 4c6.5 0 10 7 10 7a13 13 0 0 1-1.67 2.68M6.6 6.6A13 13 0 0 0 2 11s3.5 7 10 7a9 9 0 0 0 4.2-1.02M3 3l18 18M9.9 9.9a3 3 0 0 0 4.2 4.2"/></symbol>
      <symbol id="i-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
      <symbol id="i-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></symbol>
      <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
    </defs>
  </svg>

  <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-primary to-primarydark p-4">
    <div class="grid w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-xl lg:grid-cols-2">

    <!-- ===================== PANNEAU GAUCHE ===================== -->
    <div class="relative hidden flex-col justify-between overflow-hidden bg-primary p-8 text-white lg:flex">
      <img src="https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=1200&q=70" alt="Campus École 221" onerror="this.remove()" class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-40" />
      <div class="absolute inset-0 bg-gradient-to-br from-primary/85 via-primary/75 to-primarydark/90"></div>

      <div class="relative z-10 flex items-center gap-3">
        <span class="flex h-11 w-10 items-center justify-center overflow-hidden rounded-lg bg-white p-1 shadow-sm"><img src="assets/logo-ecole221.png" alt="Logo École 221" class="h-full w-full object-contain" /></span>
        <span class="text-xl font-bold tracking-tight">École 221</span>
      </div>

      <div class="relative z-10 max-w-xs">
        <h1 class="text-2xl font-extrabold leading-tight">Gérer votre parcours académique simplement.</h1>
        <p class="mt-3 text-sm leading-relaxed text-white/85">Vos cours, votre emploi du temps, vos devoirs et vos résultats, en un seul endroit.</p>
        <div class="mt-6 flex gap-8">
          <div><p class="text-2xl font-extrabold">640+</p><p class="text-[11px] text-white/70">Étudiants</p></div>
          <div><p class="text-2xl font-extrabold">15+</p><p class="text-[11px] text-white/70">Formateurs</p></div>
        </div>
      </div>

      <div class="relative z-10 inline-flex items-center gap-2 text-xs text-white/70">
        <svg class="h-4 w-4"><use href="#i-pin" /></svg> Dieupeul Derkle · Dakar, Sénégal
      </div>
    </div>

    <!-- ===================== PANNEAU DROIT (FORMULAIRE) ===================== -->
    <div class="flex items-center justify-center px-6 py-8 sm:px-9">
      <div class="w-full max-w-sm">

        <!-- Logo (mobile) -->
        <div class="mb-8 flex items-center gap-3 lg:hidden">
          <span class="flex h-11 w-10 items-center justify-center overflow-hidden rounded-lg bg-primary p-1 shadow-sm"><img src="assets/logo-ecole221.png" alt="Logo École 221" class="h-full w-full object-contain" /></span>
          <span class="text-xl font-bold tracking-tight">École 221</span>
        </div>

        <h2 class="text-2xl font-extrabold text-[#1a1b22]">Connexion</h2>
        <p class="mt-1 text-sm text-muted">Sélectionnez votre profil puis connectez-vous.</p>

        <!-- Message d'erreur -->
        <?php if ($erreur !== ""): ?>
        <div class="mt-4 flex items-center gap-2 rounded-lg border border-[#ffd4d4] bg-[#fff1f1] px-3 py-2.5 text-sm text-[#b8001a]">
          <svg class="h-4 w-4 shrink-0"><use href="#i-alert" /></svg> <?= htmlspecialchars($erreur) ?>
        </div>
        <?php endif; ?>

        <!-- Sélecteur de rôle (visuel) -->
        <div class="mt-5">
          <label class="text-xs font-semibold uppercase tracking-wide text-muted">Vous êtes</label>
          <div class="mt-2 grid grid-cols-3 gap-2">
            <button type="button" data-role="admin" class="js-role flex flex-col items-center gap-1.5 rounded-xl border px-2 py-3 text-xs font-semibold transition">
              <svg class="h-5 w-5"><use href="#i-shield" /></svg> Secrétaire<span class="text-[10px] font-normal opacity-70">/ Admin</span>
            </button>
            <button type="button" data-role="formateur" class="js-role flex flex-col items-center gap-1.5 rounded-xl border px-2 py-3 text-xs font-semibold transition">
              <svg class="h-5 w-5"><use href="#i-board" /></svg> Formateur
            </button>
            <button type="button" data-role="etudiant" class="js-role flex flex-col items-center gap-1.5 rounded-xl border px-2 py-3 text-xs font-semibold transition">
              <svg class="h-5 w-5"><use href="#i-cap" /></svg> Étudiant
            </button>
          </div>
        </div>

        <!-- Formulaire : envoi en POST vers cette même page -->
        <form class="mt-6 flex flex-col gap-4" method="post">
          <label class="block">
            <span class="text-xs font-medium text-muted">Identifiant ou e-mail</span>
            <div class="relative mt-1">
              <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-mail" /></svg></span>
              <input type="text" name="login" required value="<?= htmlspecialchars($_POST['login'] ?? '') ?>" placeholder="prenom.nom@ecole221.sn" class="w-full rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-9 pr-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
            </div>
          </label>

          <label class="block">
            <span class="text-xs font-medium text-muted">Mot de passe</span>
            <div class="relative mt-1">
              <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted"><svg class="h-4 w-4"><use href="#i-lock" /></svg></span>
              <input id="pwd" name="mot_de_passe" type="password" required placeholder="••••••••" class="w-full rounded-lg border border-[#e5e7eb] bg-white py-2.5 pl-9 pr-10 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" />
              <button type="button" id="togglePwd" aria-label="Afficher le mot de passe" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted transition hover:text-ink"><svg class="h-4 w-4"><use href="#i-eye" /></svg></button>
            </div>
          </label>

          <div class="flex items-center justify-between text-sm">
            <label class="inline-flex items-center gap-2 text-muted"><input type="checkbox" class="h-4 w-4 rounded border-[#d1d5db] text-primary focus:ring-primary/30" /> Se souvenir de moi</label>
            <a href="#" class="font-medium text-primary hover:underline">Mot de passe oublié ?</a>
          </div>

          <button type="submit" class="mt-1 inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primarydark">
            Se connecter <svg class="h-4 w-4"><use href="#i-arrow" /></svg>
          </button>
        </form>

        <p class="mt-6 text-center text-xs text-muted">Problème de connexion ? Contactez le secrétariat · <span class="font-medium text-[#374151]">secretariat@ecole221.sn</span></p>
      </div>
    </div>

    </div>
  </div>

  <script>
    /* ----- Sélecteur de rôle (visuel seulement) ----- */
    let roleActif = 'admin';
    const boutonsRole = document.querySelectorAll('.js-role');
    function peindreRoles() {
      boutonsRole.forEach((b) => {
        const actif = b.dataset.role === roleActif;
        b.className = 'js-role flex flex-col items-center gap-1.5 rounded-xl border px-2 py-3 text-xs font-semibold transition ' +
          (actif ? 'border-primary bg-[#fdeaec] text-primary shadow-sm' : 'border-[#e5e7eb] bg-white text-muted hover:border-primary/40 hover:text-ink');
      });
    }
    boutonsRole.forEach((b) => b.addEventListener('click', () => { roleActif = b.dataset.role; peindreRoles(); }));
    peindreRoles();

    /* ----- Afficher / masquer le mot de passe ----- */
    document.getElementById('togglePwd').addEventListener('click', (e) => {
      const pwd = document.getElementById('pwd');
      const show = pwd.type === 'password';
      pwd.type = show ? 'text' : 'password';
      e.currentTarget.querySelector('use').setAttribute('href', show ? '#i-eye-off' : '#i-eye');
    });
  </script>
</body>
</html>