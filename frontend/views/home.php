<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTexCHANGE — Accueil</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="stylesheet" href="/frontend/css/modals.css">
    <link rel="stylesheet" href="/frontend/css/home.css">
    <link rel="icon" type="image/png" href="/Images/favicon_utexchange.png">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>
<?php include __DIR__ . '/partials/flash.php'; ?>
<?php include __DIR__ . '/partials/modals.php'; ?>

<main class="flex-1" style="max-width:1260px;margin:0 auto;width:100%;padding:28px 20px 56px;">

    <!-- Hero banner (si non connecté) -->
    <?php if (!Session::isLoggedIn()): ?>
        <div class="hero-banner">
            <h1 style="font-family:'Poppins',sans-serif;font-size:1.8rem;font-weight:900;margin-bottom:8px;">
                La marketplace des étudiants UT
            </h1>
            <p style="opacity:.85;font-size:14px;margin-bottom:20px;max-width:1000px;text-align:'justify';">
                Achetez, vendez et donnez entre étudiants des Universités de Technologie. Simple, rapide, entre pairs.
            </p>
                <a href="/annonce/create">
                <button
                        style="background:rgba(255,255,255,.15);color:white;padding:10px 22px;
                               border:1.5px solid rgba(255,255,255,.4);border-radius:10px;
                               font-weight:600;font-size:13px;cursor:pointer;transition:background .15s;"
                        onmouseover="this.style.background='rgba(255,255,255,.25)'"
                        onmouseout="this.style.background='rgba(255,255,255,.15)'">
                    Déposer une annonce
                </button>
                </a>
            </div>
    <?php endif; ?>

    <!-- Sections par catégorie -->
    <?php if (!empty($annonces_by_cat)): ?>
        <?php foreach ($annonces_by_cat as $cat): ?>
            <?php if (empty($cat['annonces'])) continue; ?>
            <section style="margin-bottom:40px;">
                <div class="section-header">
                    <h2>
                        <?php if (!empty($cat['icone'])): ?>
                            <i class="fa-solid <?= htmlspecialchars($cat['icone']) ?>"></i>
                        <?php endif; ?>
                        <?= htmlspecialchars($cat['nom']) ?>
                    </h2>
                    <a href="/annonces?categorie_id=<?= $cat['id'] ?>" class="see-more">
                        Voir plus <i class="fa-solid fa-arrow-right" style="font-size:10px;"></i>
                    </a>
                </div>

                <div class="section-scroll">
                    <?php foreach ($cat['annonces'] as $annonce): ?>
                        <a href="/annonce/<?= $annonce->getId() ?>" class="annonce-card-h">
                            <div class="annonce-card-h__img">
                                <?php $cover = $covers[$annonce->getId()] ?? null; ?>
                                <?php if ($cover): ?>
                                    <img src="/annonce/photo/<?= $annonce->getId() ?>/<?= urlencode($cover->getNomFichier()) ?>"
                                         alt="<?= htmlspecialchars($annonce->getTitle()) ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="annonce-card-h__placeholder">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                <?php endif; ?>
                                <?php if ($annonce->getType() === 'don'): ?>
                                    <span class="badge-gratuit">Gratuit</span>
                                <?php elseif ($annonce->getType() === 'troc'): ?>
                                    <span class="badge-troc">Troc</span>
                                <?php endif; ?>
                            </div>
                            <div class="annonce-card-h__body">
                                <p class="annonce-card-h__title"><?= htmlspecialchars($annonce->getTitle()) ?></p>
                                <p class="annonce-card-h__loc">
                                    <i class="fa-solid fa-location-dot" style="font-size:9px;"></i>
                                    <?= htmlspecialchars($annonce->getLocation()) ?>
                                </p>
                                <?php if ($annonce->getType() === 'don'): ?>
                                    <p class="annonce-card-h__price annonce-card-h__price--free">Gratuit</p>
                                <?php elseif ($annonce->getType() === 'troc'): ?>
                                    <p class="annonce-card-h__price annonce-card-h__price--troc">Troc</p>
                                <?php else: ?>
                                    <p class="annonce-card-h__price">
                                        <?= number_format((float)$annonce->getPrice(), 2, ',', ' ') ?> €
                                    </p>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>

                    <!-- Card "voir plus" -->
                    <a href="/annonces?cat_id=<?= $cat['id'] ?>"
                       style="width:140px;flex-shrink:0;border-radius:14px;border:2px dashed #e5e7eb;
                          display:flex;flex-direction:column;align-items:center;justify-content:center;
                          gap:8px;color:#9ca3af;text-decoration:none;transition:border-color .15s,color .15s;
                          font-size:12px;font-weight:600;"
                       onmouseover="this.style.borderColor='#0056b3';this.style.color='#0056b3'"
                       onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#9ca3af'">
                        <i class="fa-solid fa-arrow-right" style="font-size:1.4rem;"></i>
                        Voir tout
                    </a>
                </div>
            </section>
        <?php endforeach; ?>

    <?php else: ?>
        <div style="text-align:center;padding:80px 0;color:#9ca3af;">
            <i class="fa-solid fa-box-open" style="font-size:3rem;display:block;margin-bottom:16px;opacity:.4;"></i>
            <p style="font-size:1.1rem;font-weight:600;color:#374151;">Aucune annonce pour le moment</p>
            <p style="font-size:13px;margin-top:4px;">Soyez le premier à publier !</p>
        </div>
    <?php endif; ?>


</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="/frontend/js/script.js"></script>
</body>
</html>