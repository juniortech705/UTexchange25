<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon profil</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="icon" type="image/png" href="/Images/favicon_utexchange.png">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/flash.php'; ?>

<main class="flex-1 flex items-center justify-center px-4 py-12">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 w-full max-w-lg">

        <div class="flex items-center gap-4 mb-8">
            <div class="w-14 h-14 rounded-full flex items-center justify-center text-xl font-bold text-white"
                 style="background:linear-gradient(135deg,#0056b3,#004a99);">
                <?= strtoupper(mb_substr($user->getPrenom(),0,1) . mb_substr($user->getNom(),0,1)) ?>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-800" style="font-family:'Poppins',sans-serif;">Modifier mon profil</h1>
                <p class="text-sm text-gray-400">Mettez à jour vos informations</p>
            </div>
        </div>

        <form method="POST" action="/users/edit">
            <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
            <input type="hidden" name="id" value="<?= $user->getId() ?>">

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Nom</label>
                    <input type="text" name="nom" required
                           value="<?= htmlspecialchars($user->getNom()) ?>"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Prénom</label>
                    <input type="text" name="prenom" required
                           value="<?= htmlspecialchars($user->getPrenom()) ?>"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Adresse email</label>
                <input type="email" name="email" required
                       value="<?= htmlspecialchars($user->getEmail()) ?>"
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Campus</label>
                <select name="campus"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                    <?php foreach (['Belfort','Compiègne','Montbéliard','Sevenans','Tarbes','Troyes'] as $campus): ?>
                        <option value="<?= $campus ?>" <?= $user->getCampus() === $campus ? 'selected' : '' ?>>
                            <?= $campus ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex gap-3">
                <a href="/users/profil/<?= $user->getId() ?>"
                   class="flex-1 py-2.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-500 text-center hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit"
                        class="flex-1 py-2.5 rounded-lg text-sm font-bold text-white"
                        style="background:#0056b3;">
                    <i class="fa-solid fa-check mr-1"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="/frontend/js/script.js"></script>
</body>
</html>