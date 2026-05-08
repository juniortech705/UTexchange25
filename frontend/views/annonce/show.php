<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($annonce->getTitle()) ?> — UTexCHANGE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="stylesheet" href="/frontend/css/modals.css">
    <link rel="icon" type="image/png" href="/Images/favicon_utexchange.png">
    
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/flash.php'; ?>
<?php include __DIR__ . '/../partials/modals.php'; ?>

<?php $isOwner  = Session::isLoggedIn() && Session::userId() == $annonce->getUtilisateurId(); ?>
<?php $isVendu  = $annonce->getStatus() === 'vendu'; ?>
<?php $isSignale  = $annonce->getStatus() === 'signale'; ?>
<?php $readOnly = $isVendu || $isSignale; // lecture seule si vendu ou signale ?>

<!-- Modales owner : changer type / statut — désactivées si vendu -->
<?php if ($isOwner && !$readOnly): ?>
    <div class="modal-overlay" id="modalType">
        <div class="modal-box" style="max-width:380px;">
            <button class="modal-close" onclick="closeModal('modalType')">&times;</button>
            <p class="modal-title" style="font-size:1.1rem;">Changer le type</p>
            <form method="POST" action="/annonces/type/<?= $annonce->getId() ?>">
                <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
                <div class="modal-field">
                    <label>Type d'annonce</label>
                    <select name="type">
                        <?php foreach (['vente' => 'Vente', 'don' => 'Don (gratuit)', 'location' => 'Location', 'troc' => 'Troc (échange)'] as $v => $l): ?>
                            <option value="<?= $v ?>" <?= $annonce->getType() === $v ? 'selected' : '' ?>><?= $l ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="modal-btn">Enregistrer</button>
            </form>
        </div>
    </div>
    <div class="modal-overlay" id="modalStatus">
        <div class="modal-box" style="max-width:380px;">
            <button class="modal-close" onclick="closeModal('modalStatus')">&times;</button>
            <p class="modal-title" style="font-size:1.1rem;">Changer le statut</p>
            <form method="POST" action="/annonces/status/<?= $annonce->getId() ?>">
                <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
                <div class="modal-field">
                    <label>Statut de l'annonce</label>
                    <select name="status">
                        <?php foreach (['draft' => 'Brouillon', 'active' => 'Active', 'vendu' => 'Vendu', 'archive' => 'Archivée'] as $v => $l): ?>
                            <option value="<?= $v ?>" <?= $annonce->getStatus() === $v ? 'selected' : '' ?>><?= $l ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="modal-btn">Enregistrer</button>
            </form>
        </div>
    </div>
<?php endif; ?>

<main class="flex-1" style="max-width:1100px;margin:0 auto;width:100%;padding:24px 20px 56px;">

    <!-- Fil d'Ariane -->
    <nav style="font-size:12px;color:#9ca3af;margin-bottom:20px;display:flex;align-items:center;gap:5px;">
        <a href="/" style="color:inherit;text-decoration:none;" onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#9ca3af'">Accueil</a>
        <i class="fa-solid fa-chevron-right" style="font-size:8px;"></i>
        <a href="/annonces" style="color:inherit;text-decoration:none;" onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#9ca3af'">Annonces</a>
        <i class="fa-solid fa-chevron-right" style="font-size:8px;"></i>
        <span style="color:#374151;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
            <?= htmlspecialchars($annonce->getTitle()) ?>
        </span>
    </nav>

    <!-- ── Bandeau vendu (si applicable) ── AJOUT -->
    <?php if ($readOnly): ?>
        <div class="sold-banner">
            <i class="fa-solid fa-circle-check"></i>
            Cette annonce est marquée comme <strong>Vendu</strong> — elle est en lecture seule.
        </div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 310px;gap:24px;align-items:start;">

        <!-- Colonne gauche -->
        <div>
            <!-- Galerie -->
            <div style="margin-bottom:16px;">
                <div class="gallery-main">
                    <?php
                    $sortedPhotos = array_filter($photos, fn($p) => $p->getIsCover());
                    $sortedPhotos = array_merge(
                            array_values($sortedPhotos),
                            array_values(array_filter($photos, fn($p) => !$p->getIsCover()))
                    );
                    $firstPhoto = $sortedPhotos[0] ?? null;
                    ?>
                    <?php if ($firstPhoto): ?>
                        <img id="gallery-main-img"
                             src="/annonce/photo/<?= $annonce->getId() ?>/<?= urlencode($firstPhoto->getNomFichier()) ?>"
                             alt="<?= htmlspecialchars($annonce->getTitle()) ?>">
                    <?php else: ?>
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#d1d5db;font-size:4rem;">
                            <i class="fa-regular fa-image"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (count($sortedPhotos) > 1): ?>
                    <div style="display:flex;gap:8px;margin-top:8px;overflow-x:auto;padding-bottom:2px;">
                        <?php foreach ($sortedPhotos as $i => $photo): ?>
                            <div class="gallery-thumb <?= $i === 0 ? 'active' : '' ?>"
                                 onclick="selectPhoto(this, '/annonce/photo/<?= $annonce->getId() ?>/<?= urlencode($photo->getNomFichier()) ?>')">
                                <img src="/annonce/photo/<?= $annonce->getId() ?>/<?= urlencode($photo->getNomFichier()) ?>"
                                     alt="" loading="lazy">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Titre + métadonnées -->
            <div class="sidebar-box" style="margin-bottom:14px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:10px;">
                    <h1 style="font-size:1.3rem;font-weight:700;color:#111;line-height:1.3;">
                        <?= htmlspecialchars($annonce->getTitle()) ?>
                    </h1>
                    <div style="display:flex;gap:6px;flex-shrink:0;flex-wrap:wrap;justify-content:flex-end;">
                        <?php if ($isOwner && !$readOnly): ?>
                            <!-- Badges cliquables si owner ET non vendu -->
                            <button onclick="openModal('modalType')"
                                    class="edit-pill-btn detail-pill pill-<?= $annonce->getType() ?>"
                                    title="Modifier le type">
                                <?= ucfirst($annonce->getType()) ?>
                                <i class="fa-solid fa-pen" style="font-size:9px;"></i>
                            </button>
                            <button onclick="openModal('modalStatus')"
                                    class="edit-pill-btn detail-pill pill-<?= $annonce->getStatus() ?>"
                                    title="Modifier le statut">
                                <?= ucfirst($annonce->getStatus()) ?>
                                <i class="fa-solid fa-pen" style="font-size:9px;"></i>
                            </button>
                        <?php else: ?>
                            <!-- Badges non cliquables -->
                            <span class="detail-pill pill-<?= $annonce->getType() ?>"><?= ucfirst($annonce->getType()) ?></span>
                            <span class="detail-pill pill-<?= $annonce->getStatus() ?>"><?= ucfirst($annonce->getStatus()) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <p class="price-big <?= $annonce->getType() === 'don' ? 'price-big--free' : '' ?>" style="margin-bottom:14px;">
                    <?= $annonce->getType() === 'don' ? 'Gratuit' : number_format((float)$annonce->getPrice(), 2, ',', ' ') . ' €' ?>
                </p>
                <div class="info-row"><i class="fa-solid fa-location-dot"></i> Localisation <strong><?= htmlspecialchars($annonce->getLocation()) ?></strong></div>
                <div class="info-row"><i class="fa-regular fa-calendar"></i> Publiée le <strong><?= date('d/m/Y', strtotime($annonce->getCreatedAt())) ?></strong></div>
                <div class="info-row"><i class="fa-regular fa-eye"></i> Vues <strong><?= number_format($annonce->getViewCount()) ?></strong></div>
            </div>

            <!-- Description -->
            <div class="sidebar-box">
                <h2 style="font-size:.95rem;font-weight:700;color:#111;margin-bottom:12px;">Description</h2>
                <p style="font-size:14px;color:#4b5563;line-height:1.75;white-space:pre-line;">
                    <?= htmlspecialchars($annonce->getDescription()) ?>
                </p>
            </div>
        </div>

        <!-- Sidebar -->
        <div style="position:sticky;top:80px;display:flex;flex-direction:column;gap:12px;">

            <!-- Prix + actions -->
            <div class="sidebar-box">
                <p class="price-big <?= $annonce->getType() === 'don' ? 'price-big--free' : '' ?>" style="margin-bottom:4px;">
                    <?= $annonce->getType() === 'don' ? 'Gratuit' : number_format((float)$annonce->getPrice(), 2, ',', ' ') . ' €' ?>
                </p>
                <p style="font-size:12px;color:#9ca3af;margin-bottom:16px;">
                    <i class="fa-solid fa-location-dot" style="margin-right:3px;"></i>
                    <?= htmlspecialchars($annonce->getLocation()) ?>
                </p>

                <?php if (!$isOwner): ?>

                    <?php if ($readOnly): ?>
                        <!-- ── Lecture seule : boutons désactivés ── AJOUT -->
                        <button class="btn-action btn-action--disabled" disabled style="margin-bottom:8px;" title="Cette annonce est vendue">
                            <i class="fa-solid fa-ban"></i> Article vendu
                        </button>
                        <button class="btn-action btn-action--disabled" disabled title="Cette annonce est vendue">
                            <i class="fa-regular fa-heart"></i> Sauvegarder
                        </button>
                    <?php else: ?>
                        <form method="POST" action="/conversations/start" style="margin-bottom:8px;">
                            <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
                            <input type="hidden" name="annonce_id" value="<?= $annonce->getId() ?>">
                            <button type="submit" class="btn-action btn-action--primary">
                                <i class="fa-regular fa-comment-dots"></i> Contacter le vendeur
                            </button>
                        </form>
                        <button id="favori-btn" data-id="<?= $annonce->getId() ?>"
                                class="btn-action btn-action--ghost"
                                onclick="toggleFavori(<?= $annonce->getId() ?>, this)">
                            <i class="fa-regular fa-heart favori-icon"></i>
                            <span class="favori-label">Sauvegarder</span>
                        </button>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="owner-actions">
                        <?php if ($readOnly): ?>
                            <!-- ── Owner + vendu : modifier désactivé, peut changer statut ── AJOUT -->
                            <button class="btn-action btn-action--disabled" disabled title="Annonce vendue">
                                <i class="fa-solid fa-pen-to-square"></i> Modifier l'annonce
                            </button>
                            <!-- Peut quand même changer le statut pour rouvrir -->
                            <button onclick="openModal('modalStatusOwner')"
                                    class="btn-action btn-action--outline">
                                <i class="fa-solid fa-rotate"></i> Changer le statut
                            </button>
                        <?php else: ?>
                            <a href="/annonce/edit/<?= $annonce->getId() ?>" class="btn-action btn-action--outline">
                                <i class="fa-solid fa-pen-to-square"></i> Modifier l'annonce
                            </a>
                        <?php endif; ?>
                        <form method="POST" action="/annonce/delete/<?= $annonce->getId() ?>"
                              onsubmit="return confirm('Supprimer définitivement ?')">
                            <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
                            <button type="submit" class="btn-action btn-action--danger" style="width:100%;">
                                <i class="fa-solid fa-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Vendeur -->
            <div class="sidebar-box">
                <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:12px;">Vendeur</p>
                <a href="/users/profil/<?= $annonce->getUtilisateurId() ?>"
                   style="display:flex;align-items:center;gap:11px;text-decoration:none;"
                   onmouseover="this.style.opacity='.75'" onmouseout="this.style.opacity='1'">
                    <div class="seller-avatar">
                        <?= strtoupper(mb_substr($seller->getPrenom() ?? '?', 0, 1) . mb_substr($seller->getNom() ?? '', 0, 1)) ?>
                    </div>
                    <div>
                        <p style="font-weight:600;color:#111;font-size:13px;">
                            <?= htmlspecialchars(($seller->getPrenom() ?? '') . ' ' . ($seller->getNom() ?? '')) ?>
                        </p>
                        <?php if ($seller && $seller->getAverageRating()): ?>
                            <p style="font-size:11px;color:#9ca3af;margin-top:2px;">
                                <span style="color:#f59e0b;">★</span>
                                <?= number_format($seller->getAverageRating(), 1) ?>
                                (<?= $seller->getRatingsCount() ?> avis)
                            </p>
                        <?php else: ?>
                            <p style="font-size:11px;color:#9ca3af;margin-top:2px;">Nouveau membre</p>
                        <?php endif; ?>
                    </div>
                    <i class="fa-solid fa-chevron-right" style="font-size:10px;color:#d1d5db;margin-left:auto;"></i>
                </a>
            </div>
        </div>
    </div>
</main>

<!-- Modale changement statut pour owner même si vendu -->
<?php if ($isOwner && $readOnly): ?>
    <div class="modal-overlay" id="modalStatusOwner">
        <div class="modal-box" style="max-width:380px;">
            <button class="modal-close" onclick="closeModal('modalStatusOwner')">&times;</button>
            <p class="modal-title" style="font-size:1.1rem;">Changer le statut</p>
            <form method="POST" action="/annonces/status/<?= $annonce->getId() ?>">
                <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
                <div class="modal-field">
                    <label>Statut de l'annonce</label>
                    <select name="status">
                        <?php foreach (['draft' => 'Brouillon', 'active' => 'Active', 'vendu' => 'Vendu', 'archive' => 'Archivée'] as $v => $l): ?>
                            <option value="<?= $v ?>" <?= $annonce->getStatus() === $v ? 'selected' : '' ?>><?= $l ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="modal-btn">Enregistrer</button>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="/frontend/js/script.js"></script>
<script src="/frontend/js/show-annonce.js"></script>
<script src="/frontend/Ajax/services/favoris.js"></script>
<script src="/frontend/Ajax/ui/favorisUI.js"></script>
<script>
    const favBtn = document.getElementById('favori-btn');
    if (favBtn) {
        apiFavoriCheck(favBtn.dataset.id).then(d => updateFavoriButton(favBtn, d.favori));
    }
</script>
</body>
</html>