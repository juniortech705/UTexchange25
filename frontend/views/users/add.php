<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un utilisateur</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="icon" type="image/png" href="/Images/favicon_utexchange.png">
</head>

<body class="bg-gray-50">

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/flash.php'; ?>

<div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#9ca3af;margin-bottom:20px;">
    <a href="/dashboard" style="color:inherit;text-decoration:none;"
       onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#9ca3af'">Admin</a>
    <i class="fa-solid fa-chevron-right" style="font-size:8px;"></i>
    <a href="/users" style="color:inherit;text-decoration:none;"
       onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#9ca3af'">Utilisateurs</a>
    <i class="fa-solid fa-chevron-right" style="font-size:8px;"></i>
    <span style="color:#374151;font-weight:500;">Ajouter</span>
</div>

<main class="max-w-3xl mx-auto px-4 py-10">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2"
            style="font-family:'Poppins',sans-serif;">
            <i class="fa-solid fa-user-plus" style="color:#0056b3;"></i>
            Ajouter un utilisateur
        </h1>
        <p class="text-sm text-gray-400 mt-1">
            Créer un nouveau compte manuellement
        </p>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <form method="POST" action="/users/add" class="space-y-5">

            <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">

            <!-- Nom + Prénom -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Nom</label>
                    <input type="text" name="nom" required
                           class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:outline-none">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Prénom</label>
                    <input type="text" name="prenom" required
                           class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:outline-none">
                </div>
            </div>

            <!-- Email -->
            <div>
                <label class="text-sm font-medium text-gray-600">Email</label>
                <input type="email" name="email" required
                       class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:outline-none">
            </div>

            <!-- Campus -->
            <div>
                <label class="text-sm font-medium text-gray-600">Campus</label>
                <select name="ville"
                        class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-blue-200 focus:outline-none">
                    <?php foreach (['Belfort','Compiègne','Montbéliard','Sevenans','Tarbes','Troyes'] as $campus): ?>
                        <option value="<?= $campus ?>"><?= $campus ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Role -->
            <div>
                <label class="text-sm font-medium text-gray-600">Rôle</label>
                <select name="role_id"
                        class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-blue-200 focus:outline-none">
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role->getId() ?>">
                            <?= htmlspecialchars($role->getNom()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Password -->
            <div>
                <label class="text-sm font-medium text-gray-600">Mot de passe</label>
                <input type="password" name="password" id="add-pwd" required minlength="8"
                       class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:outline-none">

                <div class="h-1 mt-2 rounded bg-gray-200 overflow-hidden">
                    <div id="add-pwd-bar" class="h-full w-0 transition-all duration-300"></div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center pt-4">

                <a href="/users"
                   class="text-sm text-gray-500 hover:text-gray-700">
                    ← Retour à la liste
                </a>

                <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-white font-semibold text-sm flex items-center gap-2"
                        style="background:#0056b3;">
                    <i class="fa-solid fa-check"></i>
                    Créer l'utilisateur
                </button>
            </div>

        </form>

    </div>

</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>

<script src="/frontend/js/script.js"></script>
<script>
    initPasswordStrength('add-pwd', 'add-pwd-bar');
</script>

</body>
</html>