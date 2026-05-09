<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer mon mot de passe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="icon" type="image/png" href="/Images/favicon_utexchange.png">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/flash.php'; ?>

<div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#9ca3af;margin-bottom:20px;">
    <a href="/" style="color:inherit;text-decoration:none;"
       onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#9ca3af'">Accueil</a>
    <i class="fa-solid fa-chevron-right" style="font-size:8px;"></i>
    <a href="/users/profil/<?= Session::userId() ?>" style="color:inherit;text-decoration:none;"
       onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#9ca3af'">Profil</a>
    <i class="fa-solid fa-chevron-right" style="font-size:8px;"></i>
    <span style="color:#374151;font-weight:500;">Mot de passe</span>
</div>

<main class="flex-1 flex items-center justify-center px-4 py-12">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 w-full max-w-md">

        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#eff6ff;">
                <i class="fa-solid fa-lock text-lg" style="color:#0056b3;"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-800" style="font-family:'Poppins',sans-serif;">Mot de passe</h1>
                <p class="text-sm text-gray-400">Choisissez un mot de passe sécurisé</p>
            </div>
        </div>

        <form method="POST" action="/users/pass" id="passForm">
            <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Mot de passe actuel</label>
                <input type="password" name="old" placeholder="••••••••" required
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Nouveau mot de passe</label>
                <input type="password" name="new" id="new-pwd"
                       placeholder="8 caractères minimum" required minlength="8"
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                <div class="pwd-strength" id="new-pwd-bar" style="height:3px;border-radius:2px;margin-top:5px;width:0%;transition:width .3s,background .3s;"></div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Confirmer le nouveau mot de passe</label>
                <input type="password" name="new_password_confirm" id="new-pwd-confirm"
                       placeholder="••••••••" required
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                <p class="pwd-match-msg text-xs mt-1 font-medium" id="new-pwd-match"></p>
            </div>

            <div class="flex gap-3">
                <a href="javascript:history.back()"
                   class="flex-1 py-2.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-500 text-center hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" id="pass-submit"
                        class="flex-1 py-2.5 rounded-lg text-sm font-bold text-white"
                        style="background:#0056b3;">
                    <i class="fa-solid fa-lock mr-1"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="/frontend/js/script.js"></script>
<script>
    initPasswordStrength('new-pwd', 'new-pwd-bar');
    initPasswordMatch('new-pwd', 'new-pwd-confirm', 'new-pwd-match', 'pass-submit');
</script>
</body>
</html>