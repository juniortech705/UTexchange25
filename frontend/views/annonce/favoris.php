<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes favoris — UTexCHANGE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="icon" type="image/png" href="/Images/favicon_utexchange.png">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/flash.php'; ?>
<?php include __DIR__ . '/../partials/modals.php'; ?>

<div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#9ca3af;margin-bottom:18px;">
    <a href="/" style="color:inherit;text-decoration:none;"
       onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#9ca3af'">Accueil</a>
    <i class="fa-solid fa-chevron-right" style="font-size:8px;"></i>
    <span style="color:#374151;font-weight:500;">Favoris</span>
</div>

<main class="flex-1" style="max-width:1200px;margin:0 auto;width:100%;padding:30px 20px;">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800" style="font-family:'Poppins',sans-serif;">
                <i class="fa-regular fa-heart mr-2" style="color:#0056b3;"></i> Mes favoris
            </h1>
            <p class="text-sm text-gray-400 mt-1"><?= count($annonces) ?> annonce<?= count($annonces) > 1 ? 's' : '' ?> sauvegardée<?= count($annonces) > 1 ? 's' : '' ?></p>
        </div>
    </div>

    <?php if (!empty($annonces)): ?>
        <div class="annonces-container">
            <?php foreach ($annonces as $annonce): ?>
                <div class="annonce-card" onclick="window.location='/annonce/<?= $annonce->getId() ?>'">
                    <div class="annonce-image" style="position:relative;">
                        <?php $cover = $covers[$annonce->getId()] ?? null; ?>
                        <?php if ($cover): ?>
                            <img src="/annonce/photo/<?= $annonce->getId() ?>/<?= urlencode($cover->getNomFichier()) ?>"
                                 alt="<?= htmlspecialchars($annonce->getTitle()) ?>" loading="lazy">
                        <?php else: ?>
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f3f4f6;">
                                <i class="fa-regular fa-image" style="font-size:2.5rem;color:#d1d5db;"></i>
                            </div>
                        <?php endif; ?>

                        <button class="wishlist-btn favori-btn" data-id="<?= $annonce->getId() ?>"
                                onclick="event.stopPropagation(); toggleFavori(<?= $annonce->getId() ?>, this)"
                                title="Retirer des favoris">
                            <i class="fa-solid fa-heart" style="color:#ef4444;"></i>
                        </button>
                    </div>
                    <div class="annonce-details">
                        <h3 style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            <?= htmlspecialchars($annonce->getTitle()) ?>
                        </h3>
                        <p style="font-size:12px;color:#9ca3af;margin-bottom:6px;">
                            <i class="fa-solid fa-location-dot" style="margin-right:3px;"></i>
                            <?= htmlspecialchars($annonce->getLocation()) ?>
                        </p>
                        <?php if ($annonce->getType() === 'don'): ?>
                            <p class="price" style="color:#16a34a;">Gratuit</p>
                        <?php else: ?>
                            <p class="price"><?= number_format($annonce->getPrice(), 2, ',', ' ') ?> €</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div style="text-align:center;padding:80px 20px;color:#9ca3af;">
            <i class="fa-regular fa-heart" style="font-size:3rem;display:block;margin-bottom:16px;"></i>
            <p style="font-size:1.1rem;font-weight:600;">Aucun favori pour le moment</p>
            <a href="/" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-semibold text-sm"
               style="background:#0056b3;">
                Parcourir les annonces
            </a>
        </div>
    <?php endif; ?>

</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="/frontend/js/script.js"></script>
<script src="/frontend/Ajax/services/favoris.js"></script>
<script src="/frontend/Ajax/ui/favorisUI.js"></script>
</body>
</html>