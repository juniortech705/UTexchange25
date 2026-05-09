<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes annonces — UTexCHANGE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="stylesheet" href="/frontend/css/annonces.css">
    <link rel="stylesheet" href="/frontend/css/modals.css">
    <link rel="icon" type="image/png" href="/Images/favicon_utexchange.png">
    
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/flash.php'; ?>
<?php include __DIR__ . '/../partials/modals.php'; ?>

<div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#9ca3af;margin-bottom:20px;">
    <a href="/" style="color:inherit;text-decoration:none;"
       onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#9ca3af'">Accueil</a>
    <i class="fa-solid fa-chevron-right" style="font-size:8px;"></i>
    <a href="/users/profil/<?= Session::userId() ?>" style="color:inherit;text-decoration:none;"
       onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#9ca3af'">Profil</a>
    <i class="fa-solid fa-chevron-right" style="font-size:8px;"></i>
    <span style="color:#374151;font-weight:500;">Annonce de <?= htmlspecialchars($name) ?></span>
</div>

<main class="flex-1" style="max-width:860px;margin:0 auto;width:100%;padding:28px 20px 56px;">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div>
            <h1 style="font-size:1.4rem;font-weight:700;color:#111;display:flex;align-items:center;gap:8px;">
                <i class="fa-solid fa-list" style="color:#0056b3;font-size:1.1rem;"></i>
                Annonce de <?= htmlspecialchars($name) ?>
            </h1>
            <p style="font-size:13px;color:#9ca3af;margin-top:2px;">
                <?= count($annonces) ?> annonce<?= count($annonces) > 1 ? 's' : '' ?> publiée<?= count($annonces) > 1 ? 's' : '' ?>
            </p>
        </div>
        <a href="/annonce/create"
           style="display:inline-flex;align-items:center;gap:7px;padding:10px 20px;
                  background:#0056b3;color:white;border-radius:11px;font-size:13px;
                  font-weight:700;text-decoration:none;transition:background .15s;"
           onmouseover="this.style.background='#004a99'" onmouseout="this.style.background='#0056b3'">
            <i class="fa-solid fa-plus"></i> Nouvelle annonce
        </a>
    </div>

    <?php
    $statusMap = [
            'active'  => ['label' => 'Active',    'class' => 's-active'],
            'draft'   => ['label' => 'Brouillon', 'class' => 's-draft'],
            'vendu'   => ['label' => 'Vendu',     'class' => 's-vendu'],
            'expire'  => ['label' => 'Expirée',   'class' => 's-expire'],
            'signale' => ['label' => 'Signalée',  'class' => 's-archive'],
    ];
    ?>

    <?php if (!empty($annonces)): ?>
        <div style="display:flex;flex-direction:column;gap:10px;">
            <?php foreach ($annonces as $annonce): ?>
                <?php $s = $statusMap[$annonce->getStatus()] ?? $statusMap['draft']; ?>
                <div class="my-card">

                    <!-- Image -->
                    <a href="/annonce/<?= $annonce->getId() ?>" class="my-card__img" style="text-decoration:none;">
                        <?php $cover = $covers[$annonce->getId()] ?? null; ?>
                        <?php if ($cover): ?>
                            <img src="/annonce/photo/<?= $annonce->getId() ?>/<?= urlencode($cover->getNomFichier()) ?>"
                                 alt="<?= htmlspecialchars($annonce->getTitle()) ?>" loading="lazy">
                        <?php else: ?>
                            <div class="my-card__img-placeholder"><i class="fa-regular fa-image"></i></div>
                        <?php endif; ?>
                    </a>

                    <!-- Infos -->
                    <a href="/annonce/<?= $annonce->getId() ?>" class="my-card__body" style="text-decoration:none;">
                        <div>
                            <p class="my-card__title"><?= htmlspecialchars($annonce->getTitle()) ?></p>
                            <p class="my-card__price
                                <?= $annonce->getType() === 'don' ? 'my-card__price--free' : ($annonce->getType() === 'troc' ? 'my-card__price--troc' : '') ?>">
                                <?php if ($annonce->getType() === 'don'): ?>Gratuit
                                <?php elseif ($annonce->getType() === 'troc'): ?>Échange
                                <?php else: ?>
                                    <?= number_format((float)$annonce->getPrice(), 2, ',', ' ') . ' €' ?>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="my-card__meta">
                            <span><i class="fa-regular fa-clock"></i> <?= date('d/m/Y', strtotime($annonce->getCreatedAt())) ?></span>
                            <span><i class="fa-regular fa-eye"></i> <?= $annonce->getViewCount() ?> vue<?= $annonce->getViewCount() > 1 ? 's' : '' ?></span>
                            <span><i class="fa-solid fa-tag"></i> <?= ucfirst($annonce->getType()) ?></span>
                        </div>
                    </a>

                    <!-- Actions -->
                    <div class="my-card__actions">
                        <span class="status-badge <?= $s['class'] ?>"><?= $s['label'] ?></span>
                        <div style="display:flex;gap:4px;">
                            <a href="/annonce/<?= $annonce->getId() ?>" class="icon-btn icon-btn--view" title="Voir">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                            <a href="/annonce/edit/<?= $annonce->getId() ?>" class="icon-btn icon-btn--edit" title="Modifier">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form method="POST" action="/annonce/delete/<?= $annonce->getId() ?>"
                                  onsubmit="return confirm('Supprimer cette annonce ?')">
                                <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
                                <button type="submit" class="icon-btn icon-btn--delete" title="Supprimer">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <div style="text-align:center;padding:80px 0;color:#9ca3af;">
            <i class="fa-solid fa-box-open" style="font-size:3rem;display:block;margin-bottom:16px;opacity:.4;"></i>
            <p style="font-size:1.1rem;font-weight:600;color:#374151;">Aucune annonce publiée</p>
            <p style="font-size:13px;margin-top:4px;">Déposez votre première annonce !</p>
            <a href="/annonce/create"
               style="margin-top:20px;display:inline-flex;align-items:center;gap:8px;
                  padding:10px 22px;background:#0056b3;color:white;border-radius:10px;
                  font-weight:700;font-size:13px;text-decoration:none;"
               onmouseover="this.style.background='#004a99'" onmouseout="this.style.background='#0056b3'">
                <i class="fa-solid fa-plus"></i> Déposer une annonce
            </a>
        </div>
    <?php endif; ?>

</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="/frontend/js/script.js"></script>
</body>
</html>