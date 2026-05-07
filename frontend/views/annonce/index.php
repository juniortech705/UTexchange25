<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
        if (!empty($filters['search']))       echo htmlspecialchars($filters['search']) . ' — ';
        elseif (!empty($currentCat['nom']))   echo htmlspecialchars($currentCat['nom']) . ' — ';
        ?>
        Annonces — UTexCHANGE
    </title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="stylesheet" href="/frontend/css/modals.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }

        /* ── Grille responsive ───────────────────────────── */
        .listing-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }
        .listing-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #f0f0f0;
            cursor: pointer;
            transition: transform .22s ease, box-shadow .22s ease;
            text-decoration: none;
            color: inherit;
            display: block;
            width: calc(20% - 13px); /* 5 par ligne */
            min-width: 180px;
        }
        @media (max-width: 1200px) { .listing-card { width: calc(25% - 12px); } }
        @media (max-width: 900px)  { .listing-card { width: calc(33.33% - 11px); } }
        @media (max-width: 600px)  { .listing-card { width: calc(50% - 8px); } }

        .listing-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0,0,0,.1);
        }
        .listing-card__img {
            height: 160px;
            background: #f4f6f8;
            overflow: hidden;
            position: relative;
        }
        .listing-card__img img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform .3s ease;
        }
        .listing-card:hover .listing-card__img img { transform: scale(1.04); }
        .listing-card__placeholder {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            color: #d1d5db; font-size: 2rem;
        }
        .listing-card__badge {
            position: absolute; top: 8px; left: 8px;
            font-size: 9px; font-weight: 700; padding: 2px 8px;
            border-radius: 20px; text-transform: uppercase; letter-spacing: .04em;
        }
        .badge-don      { background: #d1fae5; color: #065f46; }
        .badge-location { background: #ede9fe; color: #4c1d95; }

        .listing-card__fav {
            position: absolute; top: 8px; right: 8px;
            width: 30px; height: 30px;
            background: rgba(255,255,255,.9);
            border: none; border-radius: 50%; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,.1);
            transition: background .15s, transform .15s;
        }
        .listing-card__fav:hover { background: #fff; transform: scale(1.1); }
        .listing-card__fav i { font-size: 12px; color: #0056b3; }

        .listing-card__body { padding: 11px 13px 13px; }
        .listing-card__title {
            font-size: 13px; font-weight: 600; color: #111;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            margin-bottom: 3px;
        }
        .listing-card__loc {
            font-size: 11px; color: #9ca3af;
            display: flex; align-items: center; gap: 3px;
            margin-bottom: 6px;
        }
        .listing-card__price {
            font-size: 15px; font-weight: 700; color: #0056b3;
        }
        .listing-card__price--free { color: #059669; }

        /* ── Filtres ─────────────────────────────────────── */
        .filter-bar {
            background: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 14px;
            padding: 14px 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,.03);
            display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end;
        }
        .filter-group { display: flex; flex-direction: column; gap: 4px; }
        .filter-label {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .06em; color: #9ca3af;
        }
        .filter-input {
            padding: 8px 12px; border: 1.5px solid #ebebeb; border-radius: 9px;
            font-size: 13px; font-family: 'Poppins', sans-serif; outline: none;
            background: #fafafa; transition: border-color .2s;
        }
        .filter-input:focus { border-color: #0056b3; background: #fff; }
        .filter-input--active { border-color: #0056b3 !important; background: #eff6ff !important; }

        /* ── Fil d'Ariane ────────────────────────────────── */
        .breadcrumb {
            display: flex; align-items: center; gap: 6px;
            font-size: 12px; color: #9ca3af; margin-bottom: 18px;
        }
        .breadcrumb a {
            color: inherit; text-decoration: none; transition: color .15s;
        }
        .breadcrumb a:hover { color: #0056b3; }
        .breadcrumb i { font-size: 8px; }
        .breadcrumb span { color: #374151; font-weight: 500; }

        /* ── Tags filtres actifs ──────────────────────────── */
        .active-filters { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 14px; }
        .filter-tag {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 20px;
            background: #eff6ff; color: #0056b3;
            font-size: 11px; font-weight: 600;
            text-decoration: none;
        }
        .filter-tag__remove {
            cursor: pointer; opacity: .7; background: none; border: none;
            color: inherit; padding: 0; font-size: 12px; line-height: 1;
        }
        .filter-tag__remove:hover { opacity: 1; }

        /* ── Empty state ──────────────────────────────────── */
        .empty-banner {
            background: linear-gradient(135deg, #0056b3 0%, #004a99 100%);
            border-radius: 16px;
            padding: 40px;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .empty-banner::before {
            content: '';
            position: absolute; top: -40px; right: -40px;
            width: 180px; height: 180px;
            background: rgba(255,255,255,.06);
            border-radius: 50%;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>
<?php include __DIR__ . '/../partials/flash.php'; ?>
<?php include __DIR__ . '/../partials/modals.php'; ?>

<main class="flex-1" style="max-width:1260px;margin:0 auto;width:100%;padding:24px 20px 56px;">

    <!-- Fil d'Ariane -->
    <div class="breadcrumb">
        <a href="/">Accueil</a>
        <i class="fa-solid fa-chevron-right"></i>
        <a href="/annonces">Annonces</a>
        <?php if (!empty($currentCat)): ?>
            <?php if (!empty($currentCat['parent'])): ?>
                <i class="fa-solid fa-chevron-right"></i>
                <a href="/annonces?cat_id=<?= $currentCat['parent']['id'] ?>">
                    <?= htmlspecialchars($currentCat['parent']['nom']) ?>
                </a>
            <?php endif; ?>
            <i class="fa-solid fa-chevron-right"></i>
            <span><?= htmlspecialchars($currentCat['nom']) ?></span>
        <?php elseif (!empty($filters['search'])): ?>
            <i class="fa-solid fa-chevron-right"></i>
            <span>Résultats pour "<?= htmlspecialchars($filters['search']) ?>"</span>
        <?php elseif (!empty($filters['type'])): ?>
            <i class="fa-solid fa-chevron-right"></i>
            <span><?= ucfirst(htmlspecialchars($filters['type'])) ?>s</span>
        <?php endif; ?>
    </div>

    <!-- Barre de filtres -->
    <form method="GET" action="/annonces" class="filter-bar" style="margin-bottom:16px;">

        <!-- Recherche -->
        <div class="filter-group" style="flex:2;min-width:200px;">
            <span class="filter-label">Recherche</span>
            <div style="display:flex;background:#fafafa;border:1.5px solid #ebebeb;
                        border-radius:9px;overflow:hidden;transition:border-color .2s;"
                 onfocusin="this.style.borderColor='#0056b3'" onfocusout="this.style.borderColor='#ebebeb'">
                <input type="text" name="search"
                       value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
                       placeholder="Titre, description…"
                       style="flex:1;padding:8px 12px;border:none;background:transparent;
                              font-size:13px;font-family:'Poppins',sans-serif;outline:none;">
                <button type="submit"
                        style="padding:0 13px;background:none;border:none;cursor:pointer;color:#9ca3af;">
                    <i class="fa-solid fa-magnifying-glass" style="font-size:12px;"></i>
                </button>
            </div>
        </div>

        <!-- Type -->
        <div class="filter-group">
            <span class="filter-label">Type</span>
            <select name="type"
                    class="filter-input <?= !empty($filters['type']) ? 'filter-input--active' : '' ?>"
                    onchange="this.form.submit()">
                <option value="">Tous</option>
                <option value="vente"    <?= ($filters['type'] ?? '') === 'vente'    ? 'selected' : '' ?>>Vente</option>
                <option value="don"      <?= ($filters['type'] ?? '') === 'don'      ? 'selected' : '' ?>>Don gratuit</option>
                <option value="location" <?= ($filters['type'] ?? '') === 'location' ? 'selected' : '' ?>>Location</option>
            </select>
        </div>

        <!-- Prix min -->
        <div class="filter-group">
            <span class="filter-label">Prix min (€)</span>
            <input type="number" name="min_price" min="0" placeholder="0"
                   value="<?= htmlspecialchars($filters['min_price'] ?? '') ?>"
                   class="filter-input <?= !empty($filters['min_price']) ? 'filter-input--active' : '' ?>"
                   style="width:90px;">
        </div>

        <!-- Prix max -->
        <div class="filter-group">
            <span class="filter-label">Prix max (€)</span>
            <input type="number" name="max_price" min="0" placeholder="—"
                   value="<?= htmlspecialchars($filters['max_price'] ?? '') ?>"
                   class="filter-input <?= !empty($filters['max_price']) ? 'filter-input--active' : '' ?>"
                   style="width:90px;">
        </div>

        <!-- Catégorie (hidden si déjà dans l'URL) -->
        <?php if (!empty($filters['cat_id'])): ?>
            <input type="hidden" name="cat_id" value="<?= (int)$filters['cat_id'] ?>">
        <?php endif; ?>

        <!-- Bouton filtrer -->
        <button type="submit"
                style="padding:9px 18px;background:#0056b3;color:white;border:none;
                       border-radius:9px;font-size:13px;font-weight:700;cursor:pointer;
                       font-family:'Poppins',sans-serif;transition:background .15s;align-self:flex-end;"
                onmouseover="this.style.background='#004a99'"
                onmouseout="this.style.background='#0056b3'">
            Filtrer
        </button>

        <?php
        $hasFilters = array_filter(array_diff_key($filters, ['cat_id' => 1]));
        if ($hasFilters):
            ?>
            <a href="/annonces<?= !empty($filters['cat_id']) ? '?cat_id=' . (int)$filters['cat_id'] : '' ?>"
               style="font-size:12px;color:#9ca3af;text-decoration:none;align-self:flex-end;
                  padding:9px 0;display:flex;align-items:center;gap:4px;transition:color .15s;"
               onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#9ca3af'">
                <i class="fa-solid fa-xmark"></i> Réinitialiser
            </a>
        <?php endif; ?>

    </form>

    <!-- Tags filtres actifs (résumé visuel) -->
    <?php
    $activeTags = [];
    if (!empty($filters['search']))   $activeTags[] = ['label' => '🔍 ' . $filters['search'],      'remove' => 'search'];
    if (!empty($filters['type']))     $activeTags[] = ['label' => ucfirst($filters['type']),         'remove' => 'type'];
    if (!empty($filters['min_price'])) $activeTags[] = ['label' => 'Min ' . $filters['min_price'] . '€', 'remove' => 'min_price'];
    if (!empty($filters['max_price'])) $activeTags[] = ['label' => 'Max ' . $filters['max_price'] . '€', 'remove' => 'max_price'];
    if (!empty($currentCat))          $activeTags[] = ['label' => $currentCat['nom'],                'remove' => 'cat_id'];
    ?>
    <?php if (!empty($activeTags)): ?>
        <div class="active-filters">
            <?php foreach ($activeTags as $tag): ?>
                <?php
                // Construit l'URL sans ce filtre
                $newFilters = $filters;
                unset($newFilters[$tag['remove']]);
                $qs = http_build_query(array_filter($newFilters));
                ?>
                <span class="filter-tag">
            <?= htmlspecialchars($tag['label']) ?>
            <a href="/annonces<?= $qs ? '?' . $qs : '' ?>"
               class="filter-tag__remove" title="Retirer ce filtre">
                <i class="fa-solid fa-xmark"></i>
            </a>
        </span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Compteur résultats -->
    <div style="display:flex;align-items:center;justify-content:space-between;
                margin-bottom:18px;flex-wrap:wrap;gap:8px;">
        <p style="font-size:13px;color:#6b7280;font-weight:500;">
            <strong style="color:#111;"><?= count($annonces) ?></strong>
            annonce<?= count($annonces) > 1 ? 's' : '' ?> trouvée<?= count($annonces) > 1 ? 's' : '' ?>
            <?php if (!empty($currentCat)): ?>
                dans <strong style="color:#0056b3;"><?= htmlspecialchars($currentCat['nom']) ?></strong>
            <?php endif; ?>
        </p>
    </div>

    <!-- Grille ou empty state -->
    <?php if (!empty($annonces)): ?>
        <div class="listing-grid">
            <?php foreach ($annonces as $annonce): ?>
                <a href="/annonce/<?= $annonce->getId() ?>" class="listing-card">

                    <div class="listing-card__img">
                        <?php $cover = $covers[$annonce->getId()] ?? null; ?>
                        <?php if ($cover): ?>
                            <img src="/annonce/photo/<?= $annonce->getId() ?>/<?= urlencode($cover->getNomFichier()) ?>"
                                 alt="<?= htmlspecialchars($annonce->getTitle()) ?>" loading="lazy">
                        <?php else: ?>
                            <div class="listing-card__placeholder">
                                <i class="fa-regular fa-image"></i>
                            </div>
                        <?php endif; ?>

                        <?php if ($annonce->getType() === 'don'): ?>
                            <span class="listing-card__badge badge-don">Gratuit</span>
                        <?php elseif ($annonce->getType() === 'location'): ?>
                            <span class="listing-card__badge badge-location">Location</span>
                        <?php endif; ?>

                        <?php if (Session::isLoggedIn()): ?>
                            <button class="listing-card__fav"
                                    onclick="event.preventDefault(); toggleFavori(<?= $annonce->getId() ?>, this)"
                                    title="Sauvegarder">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="listing-card__body">
                        <p class="listing-card__title"><?= htmlspecialchars($annonce->getTitle()) ?></p>
                        <p class="listing-card__loc">
                            <i class="fa-solid fa-location-dot" style="font-size:9px;"></i>
                            <?= htmlspecialchars($annonce->getLocation()) ?>
                        </p>
                        <?php if ($annonce->getType() === 'don'): ?>
                            <p class="listing-card__price listing-card__price--free">Gratuit</p>
                        <?php else: ?>
                            <p class="listing-card__price">
                                <?= number_format((float)$annonce->getPrice(), 2, ',', ' ') ?> €
                            </p>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    <?php else: ?>

        <!-- Empty state style home -->
        <div class="empty-banner">
            <div style="position:relative;z-index:1;">
                <i class="fa-solid fa-box-open"
                   style="font-size:3rem;display:block;margin-bottom:16px;opacity:.7;"></i>
                <h2 style="font-family:'Poppins',sans-serif;font-size:1.4rem;font-weight:700;margin-bottom:8px;">
                    <?php if (!empty($filters['search'])): ?>
                        Aucun résultat pour "<?= htmlspecialchars($filters['search']) ?>"
                    <?php else: ?>
                        Aucune annonce trouvée
                    <?php endif; ?>
                </h2>
                <p style="opacity:.8;font-size:14px;margin-bottom:22px;max-width:440px;margin-left:auto;margin-right:auto;">
                    <?php if ($hasFilters ?? false): ?>
                        Essayez d'élargir vos critères de recherche.
                    <?php else: ?>
                        Soyez le premier à publier dans cette catégorie !
                    <?php endif; ?>
                </p>
                <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
                    <?php if ($hasFilters ?? false): ?>
                        <a href="/annonces<?= !empty($filters['cat_id']) ? '?cat_id=' . (int)$filters['cat_id'] : '' ?>"
                           style="background:rgba(255,255,255,.2);color:white;padding:10px 20px;
                          border:1.5px solid rgba(255,255,255,.4);border-radius:10px;
                          font-weight:600;font-size:13px;text-decoration:none;transition:background .15s;"
                           onmouseover="this.style.background='rgba(255,255,255,.3)'"
                           onmouseout="this.style.background='rgba(255,255,255,.2)'">
                            <i class="fa-solid fa-xmark" style="margin-right:5px;"></i>
                            Réinitialiser les filtres
                        </a>
                    <?php endif; ?>
                    <a href="/annonce/create"
                       style="background:white;color:#0056b3;padding:10px 20px;
                          border:none;border-radius:10px;font-weight:700;
                          font-size:13px;text-decoration:none;transition:opacity .15s;"
                       onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                        <i class="fa-solid fa-plus" style="margin-right:5px;"></i>
                        Déposer une annonce
                    </a>
                </div>
            </div>
        </div>

    <?php endif; ?>

</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="/frontend/js/script.js"></script>
<script src="/frontend/Ajax/services/favoris.js"></script>
<script src="/frontend/Ajax/ui/favorisUI.js"></script>
</body>
</html>