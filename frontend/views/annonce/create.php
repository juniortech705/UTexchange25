<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déposer une annonce — UTexCHANGE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/flash.php'; ?>
<?php include __DIR__ . '/../partials/modals.php'; ?>

<main class="flex-1 max-w-2xl mx-auto w-full px-4 py-10">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800" style="font-family:'Poppins',sans-serif;">
            <i class="fa-solid fa-square-plus mr-2" style="color:#0056b3;"></i>
            Déposer une annonce
        </h1>
        <p class="text-sm text-gray-400 mt-1">Remplissez les informations de votre annonce</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <form method="POST" action="/annonce/create" enctype="multipart/form-data" id="createForm">
            <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">

            <!-- Titre -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Titre de l'annonce</label>
                <input type="text" name="title" required maxlength="150"
                       placeholder="Ex: Vélo de ville en bon état"
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>

            <!-- Catégorie + Type + Status -->
            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Catégorie</label>
                    <select name="categorie_id" required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                        <option value="" disabled selected>Choisir...</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat->getId() ?>"><?= htmlspecialchars($cat->getNom()) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Type</label>
                    <select name="type" id="type-select" required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                        <option value="" disabled selected>Choisir...</option>
                        <option value="vente">Vente</option>
                        <option value="don">Don (gratuit)</option>
                        <option value="location">Location</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Status</label>
                    <select name="status" id="type-select" required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                        <option value="" disabled selected>Choisir...</option>
                        <option value="draft">Brouillon</option>
                        <option value="active">Actif</option>
                    </select>
                </div>
            </div>

            <!-- Prix (masqué si don) -->
            <div class="mb-5" id="price-block">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Prix (€)</label>
                <input type="number" name="price" min="0" step="0.01" value="0"
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>

            <!-- Description -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Description</label>
                <textarea name="description" required rows="5"
                          placeholder="Décrivez votre article : état, caractéristiques, raison de la vente..."
                          class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 resize-none"></textarea>
            </div>

            <!-- Localisation -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Localisation</label>
                <input type="text" name="location" required placeholder="Ex: Troyes"
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>

            <!-- Photos -->
            <div class="mb-7">
                <label class="block text-sm font-semibold text-gray-600 mb-2">
                    Photos (max 8)
                </label>

                <!-- Drop zone -->
                <div id="drop-zone"
                     class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition"
                     onclick="document.getElementById('file-input').click()">

                    <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-400 mb-2"></i>

                    <p class="text-sm text-gray-500">
                        Glissez vos images ici ou cliquez pour sélectionner
                    </p>

                    <input type="file"
                           name="photos[]"
                           id="file-input"
                           multiple
                           accept="image/*"
                           class="hidden"
                           onchange="handleFiles(this.files)">
                </div>

                <!-- Preview -->
                <div id="photo-preview" class="flex flex-wrap gap-3 mt-4"></div>
            </div>

            <button type="submit"
                    class="w-full py-3 rounded-xl text-white font-bold text-sm flex items-center justify-center gap-2 transition hover:opacity-90"
                    style="background:#0056b3;">
                <i class="fa-solid fa-paper-plane"></i>
                Publier l'annonce
            </button>
        </form>
    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="/frontend/js/script.js"></script>
<script src="/frontend/js/scriptPhoto.js"></script>
<script>
    const dropZone = document.getElementById('drop-zone');

    dropZone.addEventListener('dragover', e => {
        e.preventDefault();
        dropZone.classList.add('border-blue-500');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-blue-500');
    });

    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-500');

        handleFiles(e.dataTransfer.files);
    });
</script>
</body>
</html>