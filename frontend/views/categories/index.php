<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des catégories — UTexCHANGE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="icon" type="image/png" href="/Images/favicon_utexchange.png">
</head>

<body class="bg-gray-50">

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/flash.php'; ?>

<main class="max-w-6xl mx-auto px-4 py-10">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800" style="font-family:'Poppins',sans-serif;">
                <i class="fa-solid fa-layer-group mr-2" style="color:#0056b3;"></i>
                Gestion des catégories
            </h1>
            <p class="text-sm text-gray-400 mt-1">
                <?= count($categories) ?> catégories enregistrées
            </p>
        </div>
    </div>

    <!-- Add button -->
    <div class="mb-6">
        <a href="/categories/add"
           class="px-5 py-2.5 rounded-xl text-white font-semibold text-sm flex items-center gap-2"
           style="background:#0056b3; width:fit-content;">
            <i class="fa-solid fa-plus"></i> Ajouter une catégorie
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">

            <thead style="background:#f8faff;">
            <tr>
                <th class="text-left px-5 py-4 font-semibold text-gray-500">Catégorie</th>
                <th class="text-left px-5 py-4 font-semibold text-gray-500">Parent</th>
                <th class="text-left px-5 py-4 font-semibold text-gray-500">Statut</th>
                <th class="text-right px-5 py-4 font-semibold text-gray-500">Actions</th>
            </tr>
            </thead>

            <tbody class="divide-y divide-gray-50">
            <?php foreach ($categories as $cat): ?>
                <tr class="hover:bg-gray-50 transition">

                    <!-- Nom -->
                    <td class="px-5 py-4">
                        <p class="font-semibold text-gray-800">
                            <?= htmlspecialchars($cat->getNom()) ?>
                        </p>
                    </td>

                    <!-- Parent -->
                    <td class="px-5 py-4 text-gray-500">
                        <?= $cat->getParentId() && isset($parentsMap[$cat->getParentId()])
                                ? htmlspecialchars($parentsMap[$cat->getParentId()]["nom"])
                                : '-' ?>
                    </td>

                    <!-- Statut -->
                    <td class="px-5 py-4">
                        <?php if ($cat->getIsActive()): ?>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold"
                                  style="background:#dcfce7;color:#16a34a;">
                                Actif
                            </span>
                        <?php else: ?>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold"
                                  style="background:#fee2e2;color:#dc2626;">
                                Inactif
                            </span>
                        <?php endif; ?>
                    </td>

                    <!-- Actions -->
                    <td class="px-5 py-4">
                        <div class="flex justify-end gap-2">

                            <!-- Edit -->
                            <a href="/categories/edit/<?= $cat->getId() ?>"
                               class="p-2 rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition"
                               title="Modifier">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>

                            <!-- Activate / Deactivate -->
                            <?php if ($cat->getIsActive()): ?>
                                <form method="POST" action="/categories/deactivate/<?= $cat->getId() ?>" class="inline">
                                    <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
                                    <button type="submit"
                                            class="p-2 rounded-lg text-gray-400 hover:text-orange-600 hover:bg-orange-50 transition"
                                            title="Désactiver">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="/categories/activate/<?= $cat->getId() ?>" class="inline">
                                    <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
                                    <button type="submit"
                                            class="p-2 rounded-lg text-gray-400 hover:text-green-600 hover:bg-green-50 transition"
                                            title="Activer">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </button>
                                </form>
                            <?php endif; ?>

                            <!-- Delete -->
                            <form method="POST" action="/categories/delete/<?= $cat->getId() ?>" class="inline"
                                  onsubmit="return confirm('Supprimer cette catégorie ?')">
                                <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
                                <button type="submit"
                                        class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition"
                                        title="Supprimer">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
    </div>

</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>

<script src="/frontend/js/script.js"></script>

</body>
</html>