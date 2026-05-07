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
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .gallery-main { border-radius: 14px; overflow: hidden; height: 400px; background: #f4f6f8; position: relative; }
        .gallery-main img { width:100%;height:100%;object-fit:cover;transition:opacity .2s; }
        .gallery-thumb { width:72px;height:72px;border-radius:9px;overflow:hidden;flex-shrink:0;border:2px solid #e5e7eb;cursor:pointer;opacity:.7;transition:border-color .15s,opacity .15s; }
        .gallery-thumb.active, .gallery-thumb:hover { border-color:#0056b3;opacity:1; }
        .gallery-thumb img { width:100%;height:100%;object-fit:cover; }
        .price-big { font-size:2rem;font-weight:900;color:#0056b3;line-height:1; }
        .price-big--free { color:#059669; }
        .detail-pill { display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600; }
        .pill-vente    { background:#eff6ff;color:#1d4ed8; }
        .pill-don      { background:#d1fae5;color:#065f46; }
        .pill-location { background:#ede9fe;color:#4c1d95; }
        .pill-active   { background:#dcfce7;color:#16a34a; }
        .pill-draft    { background:#f3f4f6;color:#6b7280; }
        .pill-vendu    { background:#eff6ff;color:#1d4ed8; }
        .pill-expire   { background:#fef9c3;color:#92400e; }
        .pill-archive  { background:#fee2e2;color:#dc2626; }
        .sidebar-box { background:#fff;border:1px solid #f0f0f0;border-radius:14px;padding:20px;box-shadow:0 2px 12px rgba(0,0,0,.04); }
        .info-row { display:flex;align-items:center;gap:8px;padding:8px 0;font-size:13px;color:#6b7280;border-bottom:1px solid #f3f4f6; }
        .info-row:last-child { border-bottom:none; }
        .info-row i { width:15px;text-align:center;font-size:11px;color:#9ca3af; }
        .info-row strong { margin-left:auto;color:#111;font-weight:600;font-size:13px; }
        .btn-action { width:100%;padding:12px;border-radius:11px;border:none;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;transition:opacity .15s,transform .1s;font-family:'Poppins',sans-serif; }
        .btn-action:hover { opacity:.88; }
        .btn-action:active { transform:scale(.98); }
        .btn-action--primary { background:#0056b3;color:white; }
        .btn-action--outline { background:transparent;color:#0056b3;border:2px solid #0056b3 !important; }
        .btn-action--outline:hover { background:#eff6ff;opacity:1; }
        .btn-action--danger { background:transparent;color:#dc2626;border:2px solid #dc2626 !important; }
        .btn-action--danger:hover { background:#fef2f2;opacity:1; }
        .btn-action--ghost { background:#f9fafb;color:#374151;border:1.5px solid #e5e7eb !important; }
        .btn-action--ghost:hover { border-color:#0056b3 !important;color:#0056b3;background:#eff6ff;opacity:1; }
        .btn-action--disabled { background:#f3f4f6;color:#9ca3af;cursor:not-allowed;border:1.5px solid #e5e7eb !important; }
        .btn-action--disabled:hover { opacity:1;transform:none; }
        .seller-avatar { width:44px;height:44px;border-radius:50%;flex-shrink:0;background:linear-gradient(135deg,#0056b3,#004a99);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:white; }
        .owner-actions { display:flex;flex-direction:column;gap:8px; }
        .edit-pill-btn { display:inline-flex;align-items:center;gap:5px;cursor:pointer;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;border:none;transition:filter .15s; }
        .edit-pill-btn:hover { filter:brightness(.9); }

        /* ── Bandeau vendu ── */
        .sold-banner {
            background: #eff6ff;
            border: 1.5px solid #bfdbfe;
            border-radius: 12px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #1d4ed8;
            margin-bottom: 16px;
        }
        .sold-banner i { font-size: 1rem; }
    </style>
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