<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil — <?= htmlspecialchars($user->getPrenom() . ' ' . $user->getNom()) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="stylesheet" href="/frontend/css/modals.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .profile-cover {
            height: 140px;
            background: linear-gradient(135deg, #0056b3 0%, #004a99 60%, #003d80 100%);
            border-radius: 16px 16px 0 0;
            position: relative;
        }
        .profile-card {
            background: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,.04);
            margin-bottom: 24px;
        }
        .profile-avatar {
            width: 80px; height: 80px; border-radius: 50%;
            background: linear-gradient(135deg, #0056b3, #004a99);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; font-weight: 700; color: white;
            border: 4px solid white;
            position: absolute; bottom: -40px; left: 28px;
            box-shadow: 0 4px 16px rgba(0,86,179,.3);
        }
        .profile-body { padding: 52px 28px 24px; }
        .profile-name {
            font-size: 1.3rem; font-weight: 700; color: #111; margin-bottom: 4px;
        }
        .profile-meta {
            font-size: 12px; color: #9ca3af;
            display: flex; flex-wrap: wrap; gap: 12px; margin-top: 6px;
        }
        .profile-meta span { display: flex; align-items: center; gap: 4px; }
        .profile-meta i { font-size: 10px; }
        .stat-box {
            background: #f8faff; border-radius: 12px; padding: 14px 20px;
            text-align: center; flex: 1;
        }
        .stat-box__val {
            font-size: 1.5rem; font-weight: 900; color: #0056b3;
            font-family: 'Poppins', sans-serif;
        }
        .stat-box__val--gold { color: #f59e0b; }
        .stat-box__label { font-size: 11px; color: #9ca3af; margin-top: 2px; }

        /* Cards annonces profil */
        .profile-listing {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 0; border-bottom: 1px solid #f3f4f6;
            text-decoration: none; color: inherit;
            transition: background .15s; border-radius: 8px;
        }
        .profile-listing:last-child { border-bottom: none; }
        .profile-listing:hover { background: #f8faff; padding-left: 8px; margin: 0 -8px; }
        .profile-listing__img {
            width: 60px; height: 60px; border-radius: 9px;
            background: #f4f6f8; overflow: hidden; flex-shrink: 0;
        }
        .profile-listing__img img { width:100%;height:100%;object-fit:cover; }
        .profile-listing__placeholder {
            width:100%;height:100%;display:flex;align-items:center;justify-content:center;
            color:#d1d5db;font-size:1.2rem;
        }
        .profile-listing__title {
            font-size: 13px; font-weight: 600; color: #111;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            margin-bottom: 3px;
        }
        .profile-listing__price {
            font-size: 14px; font-weight: 700; color: #0056b3;
        }
        .profile-listing__price--free { color: #059669; }
        .profile-listing__meta { font-size: 11px; color: #9ca3af; margin-top: 2px; }

        /* Boutons actions propre profil */
        .profile-action-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 9px; font-size: 12px; font-weight: 600;
            text-decoration: none; transition: background .15s, color .15s;
            border: 1.5px solid;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/flash.php'; ?>
<?php include __DIR__ . '/../partials/modals.php'; ?>

<main class="flex-1" style="max-width:900px;margin:0 auto;width:100%;padding:28px 20px 56px;">

    <div style="display:grid;grid-template-columns:320px 1fr;gap:20px;align-items:start;">

        <!-- Colonne gauche : infos profil -->
        <div>
            <!-- Carte profil -->
            <div class="profile-card">
                <div class="profile-cover" style="position:relative;">
                    <div class="profile-avatar">
                        <?= strtoupper(mb_substr($user->getPrenom(), 0, 1) . mb_substr($user->getNom(), 0, 1)) ?>
                    </div>
                </div>

                <div class="profile-body">
                    <p class="profile-name">
                        <?= htmlspecialchars($user->getPrenom() . ' ' . $user->getNom()) ?>
                    </p>

                    <div class="profile-meta">
                        <?php if ($user->getCampus()): ?>
                            <span>
                            <i class="fa-solid fa-location-dot"></i>
                            <?= htmlspecialchars($user->getCampus()) ?>
                        </span>
                        <?php endif; ?>
                        <span>
                            <i class="fa-regular fa-calendar"></i>
                            Membre depuis <?= date('M Y', strtotime($user->getDateIns())) ?>
                        </span>
                    </div>

                    <!-- Stats -->
                    <div style="display:flex;gap:8px;margin-top:20px;">
                        <div class="stat-box">
                            <p class="stat-box__val"><?= $stats['total'] ?? count($annonces) ?></p>
                            <p class="stat-box__label">Annonces</p>
                        </div>
                        <?php if ($user->getAverageRating()): ?>
                            <div class="stat-box">
                                <p class="stat-box__val stat-box__val--gold">
                                    <?= number_format($user->getAverageRating(), 1) ?>★
                                </p>
                                <p class="stat-box__label"><?= $user->getRatingsCount() ?> avis</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Actions si propre profil -->
                    <?php if (Session::get('user_id') == $user->getId()): ?>
                        <div style="display:flex;gap:8px;margin-top:16px;flex-wrap:wrap;">
                            <a href="/users/edit/<?= $user->getId() ?>"
                               class="profile-action-btn"
                               style="border-color:#0056b3;color:#0056b3;"
                               onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background='transparent'">
                                <i class="fa-solid fa-pen-to-square"></i> Modifier
                            </a>
                            <a href="/users/pass"
                               class="profile-action-btn"
                               style="border-color:#e5e7eb;color:#6b7280;"
                               onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                                <i class="fa-solid fa-lock"></i> Mot de passe
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Colonne droite : annonces -->
        <div>
            <div style="background:#fff;border:1px solid #f0f0f0;border-radius:16px;padding:22px;box-shadow:0 2px 12px rgba(0,0,0,.04);">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                    <h2 style="font-size:1rem;font-weight:700;color:#111;">
                        Annonces de <?= htmlspecialchars($user->getPrenom()) ?>
                    </h2>
                    <?php if (count($annonces) >= 5): ?>
                        <?php if (Session::get('user_id') == $user->getId()): ?>
                            <a href="/myAnnonces"
                               style="font-size:12px;font-weight:600;color:#0056b3;text-decoration:none;
                              display:flex;align-items:center;gap:4px;"
                               onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='1'">
                                Voir tout <i class="fa-solid fa-arrow-right" style="font-size:10px;"></i>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <?php if (!empty($annonces)): ?>
                    <?php foreach (array_slice($annonces, 0, 5) as $annonce): ?>
                        <a href="/annonce/<?= $annonce->getId() ?>" class="profile-listing">
                            <div class="profile-listing__img">
                                <?php $cover = $covers[$annonce->getId()] ?? null; ?>
                                <?php if ($cover): ?>
                                    <img src="/annonce/photo/<?= $annonce->getId() ?>/<?= urlencode($cover->getNomFichier()) ?>"
                                         alt="" loading="lazy">
                                <?php else: ?>
                                    <div class="profile-listing__placeholder">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <p class="profile-listing__title"><?= htmlspecialchars($annonce->getTitle()) ?></p>
                                <p class="profile-listing__price <?= $annonce->getType() === 'don' ? 'profile-listing__price--free' : '' ?>">
                                    <?= $annonce->getType() === 'don' ? 'Gratuit' : number_format((float)$annonce->getPrice(), 2, ',', ' ') . ' €' ?>
                                </p>
                                <p class="profile-listing__meta">
                                    <i class="fa-solid fa-location-dot" style="font-size:9px;"></i>
                                    <?= htmlspecialchars($annonce->getLocation()) ?>
                                    · <?= date('d/m/Y', strtotime($annonce->getCreatedAt())) ?>
                                </p>
                            </div>
                            <i class="fa-solid fa-chevron-right" style="font-size:10px;color:#d1d5db;flex-shrink:0;"></i>
                        </a>
                    <?php endforeach; ?>

                    <?php if (count($annonces) >= 5 && Session::get('user_id') == $user->getId()): ?>
                        <a href="/myAnnonces"
                           style="display:flex;align-items:center;justify-content:center;gap:6px;
                              margin-top:14px;padding:10px;border-radius:10px;
                              background:#f8faff;color:#0056b3;font-size:12px;
                              font-weight:600;text-decoration:none;transition:background .15s;"
                           onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background='#f8faff'">
                            Voir toutes mes annonces <i class="fa-solid fa-arrow-right" style="font-size:10px;"></i>
                        </a>
                    <?php endif; ?>

                <?php else: ?>
                    <div style="text-align:center;padding:40px 0;color:#9ca3af;">
                        <i class="fa-solid fa-box-open" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:.4;"></i>
                        <p style="font-size:13px;">Aucune annonce publiée.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="/frontend/js/script.js"></script>
</body>
</html>