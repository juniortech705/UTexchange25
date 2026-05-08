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
    <link rel="stylesheet" href="/frontend/css/modals.css">
    <link rel="icon" type="image/png" href="/Images/favicon_utexchange.png">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .my-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #f0f0f0;
            transition: box-shadow .2s, border-color .2s;
            display: flex;
            align-items: stretch;
        }
        .my-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.08); border-color: #e0eaf5; }
        .my-card__img {
            width: 120px;
            flex-shrink: 0;
            background: #f4f6f8;
            position: relative;
            overflow: hidden;
        }
        .my-card__img img { width:100%;height:100%;object-fit:cover;transition:transform .3s; }
        .my-card:hover .my-card__img img { transform: scale(1.05); }
        .my-card__img-placeholder {
            width:100%;height:100%;display:flex;align-items:center;justify-content:center;
            color:#d1d5db;font-size:1.8rem;
        }
        .my-card__body {
            flex: 1;
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
        }
        .my-card__title {
            font-size: 14px; font-weight: 700; color: #111;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            margin-bottom: 4px;
        }
        .my-card__price {
            font-size: 16px; font-weight: 900; color: #0056b3;
        }
        .my-card__price--free { color: #059669; }
        .my-card__meta { font-size: 11px; color: #9ca3af; display:flex;gap:10px;flex-wrap:wrap;margin-top:4px; }
        .my-card__meta i { font-size: 10px; }
        .my-card__actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: space-between;
            padding: 14px 14px 14px 8px;
            gap: 8px;
            flex-shrink: 0;
        }

        /* Badge statut */
        .status-badge {
            font-size: 10px; font-weight: 700; padding: 3px 9px;
            border-radius: 20px; white-space: nowrap;
        }
        .s-active  { background:#dcfce7;color:#16a34a; }
        .s-draft   { background:#f3f4f6;color:#6b7280; }
        .s-vendu   { background:#eff6ff;color:#1d4ed8; }
        .s-expire  { background:#fef9c3;color:#92400e; }
        .s-archive { background:#fee2e2;color:#dc2626; }

        .icon-btn {
            width:32px;height:32px;border-radius:8px;border:none;cursor:pointer;
            display:flex;align-items:center;justify-content:center;font-size:13px;
            transition:background .15s,color .15s;background:transparent;
        }
        .icon-btn--view   { color:#9ca3af; } .icon-btn--view:hover   { background:#eff6ff;color:#0056b3; }
        .icon-btn--edit   { color:#9ca3af; } .icon-btn--edit:hover   { background:#fffbeb;color:#d97706; }
        .icon-btn--delete { color:#9ca3af; } .icon-btn--delete:hover { background:#fef2f2;color:#dc2626; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/flash.php'; ?>
<?php include __DIR__ . '/../partials/modals.php'; ?>

<main class="flex-1" style="max-width:860px;margin:0 auto;width:100%;padding:28px 20px 56px;">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div>
            <h1 style="font-size:1.4rem;font-weight:700;color:#111;display:flex;align-items:center;gap:8px;">
                <i class="fa-solid fa-list" style="color:#0056b3;font-size:1.1rem;"></i>
                Mes annonces
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
            'archive' => ['label' => 'Archivée',  'class' => 's-archive'],
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
                            <p class="my-card__price <?= $annonce->getType() === 'don' ? 'my-card__price--free' : '' ?>">
                                <?= $annonce->getType() === 'don' ? 'Gratuit' : number_format((float)$annonce->getPrice(), 2, ',', ' ') . ' €' ?>
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