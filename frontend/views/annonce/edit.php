<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'annonce ? UTexCHANGE</title>

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

    <!-- HEADER -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800" style="font-family:'Poppins',sans-serif;">
            <i class="fa-solid fa-pen mr-2" style="color:#0056b3;"></i>
            Modifier l'annonce
        </h1>
        <p class="text-sm text-gray-400 mt-1">Mettez à jour les informations de votre annonce</p>
    </div>

    <!-- FORM -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">

        <form method="POST" action="/annonce/edit/<?= $annonce->getId() ?>" enctype="multipart/form-data">
            <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">

            <!-- TITLE -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Titre</label>
                <input type="text" name="title" required maxlength="150"
                       value="<?= htmlspecialchars($annonce->getTitle()) ?>"
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>

            <!-- CAT + TYPE + STATUS -->
            <div class="grid grid-cols-2 gap-4 mb-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Catégorie</label>
                    <select name="categorie_id" required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">

                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat->getId() ?>"
                                    <?= $cat->getId() == $annonce->getCategorieId() ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat->getNom()) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Type</label>
                    <select name="type" id="type-select" required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">

                        <?php foreach (['vente','don','location','troc'] as $type): ?>
                            <option value="<?= $type ?>"
                                    <?= $annonce->getType() === $type ? 'selected' : '' ?>>
                                <?= ucfirst($type) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

            </div>

            <!-- PRICE -->
            <div class="mb-5" id="price-block"
                 style="<?= $annonce->getType() === 'don' ? 'display:none' : '' ?>">

                <label class="block text-sm font-semibold text-gray-600 mb-1">Prix</label>
                <input type="number" name="price" min="0" step="0.01"
                       value="<?= $annonce->getPrice() ?>"
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">

            </div>

            <!-- STATUS -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Statut</label>
                <select name="status"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">

                    <?php foreach (['draft'=>'Brouillon','active'=>'Actif','vendu'=>'Vendu','archive'=>'Archivé'] as $k=>$v): ?>
                        <option value="<?= $k ?>" <?= $annonce->getStatus()===$k?'selected':'' ?>>
                            <?= $v ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <!-- DESCRIPTION -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Description</label>
                <textarea name="description" required rows="5"
                          class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm resize-none focus:outline-none focus:border-blue-500"><?= htmlspecialchars($annonce->getDescription()) ?></textarea>
            </div>

            <!-- LOCATION -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Localisation</label>
                <input type="text" name="location" required
                       value="<?= htmlspecialchars($annonce->getLocation()) ?>"
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>

            <!-- ========================= -->
            <!-- EXISTING PHOTOS (AJAX) -->
            <!-- ========================= -->
            <?php if (!empty($photos)): ?>
                <div class="mb-6">

                    <label class="block text-sm font-semibold text-gray-600 mb-3">
                        Photos actuelles (<?= count($photos) ?>/8)
                    </label>

                    <div id="existing-photos" class="flex flex-wrap gap-3">

                        <?php foreach ($photos as $photo): ?>
                            <div id="photo-<?= $photo->getId() ?>"
                                 class="relative"
                                 style="width:90px;height:90px;border-radius:10px;overflow:hidden;border:2px solid <?= $photo->getIsCover() ? '#0056b3' : '#e5e7eb' ?>;">

                                <img src="/annonce/photo/<?= $annonce->getId() ?>/<?= urlencode($photo->getNomFichier()) ?>"
                                     style="width:100%;height:100%;object-fit:cover;">

                                <!-- COVER BADGE -->
                                <?php if ($photo->getIsCover()): ?>
                                    <span class="cover-badge"
                                          style="position:absolute;bottom:0;left:0;right:0;background:#0056b3;color:white;font-size:9px;text-align:center;">
                                    Cover
                                </span>
                                <?php endif; ?>

                                <!-- DELETE -->
                                <button type="button"
                                        onclick="deletePhoto(<?= $photo->getId() ?>)"
                                        style="position:absolute;top:4px;right:4px;background:rgba(239,68,68,.9);color:white;border:none;border-radius:50%;width:20px;height:20px;">
                                    ✕
                                </button>

                                <!-- SET COVER -->
                                <button type="button"
                                        onclick="setCover(<?= $photo->getId() ?>)"
                                        class="cover-btn"
                                        style="position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,.6);color:white;font-size:9px;border:none;opacity:0;transition:.2s;">
                                    Cover
                                </button>

                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            <?php endif; ?>

            <!-- ========================= -->
            <!-- ADD NEW PHOTOS (CREATE STYLE) -->
            <!-- ========================= -->
            <div class="mb-7">

                <label class="block text-sm font-semibold text-gray-600 mb-2">
                    Ajouter des photos
                </label>

                <div id="drop-zone"
                     class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition"
                     onclick="document.getElementById('file-input').click()">

                    <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-400 mb-2"></i>

                    <p class="text-sm text-gray-500">
                        Glissez ou cliquez pour ajouter des images
                    </p>

                    <input type="file"
                           name="photos[]"
                           id="file-input"
                           multiple
                           accept="image/*"
                           class="hidden"
                           onchange="handleFiles(this.files)">
                </div>

                <div id="photo-preview" class="flex flex-wrap gap-3 mt-4"></div>

            </div>

            <!-- ACTIONS -->
            <div class="flex gap-3">

                <a href="/annonce/<?= $annonce->getId() ?>"
                   class="flex-1 py-2.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-500 text-center hover:bg-gray-50">
                    Annuler
                </a>

                <button type="submit"
                        class="flex-1 py-2.5 rounded-xl text-white font-bold text-sm"
                        style="background:#0056b3;">
                    Enregistrer
                </button>

            </div>

        </form>
    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>

<!-- JS -->
<script src="/frontend/js/script.js"></script>
<script src="/frontend/Ajax/services/photos.js"></script>
<script src="/frontend/Ajax/ui/photoUI.js"></script>
<script src="/frontend/js/scriptPhoto.js"></script>

<script>
    document.getElementById('type-select').addEventListener('change', function () {
        document.getElementById('price-block').style.display =
            this.value === 'don' || this.value === 'troc' ? 'none' : 'block';
    });
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