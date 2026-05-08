<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques — UTexCHANGE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="icon" type="image/png" href="/Images/favicon_utexchange.png">
    <style>
        body { font-family: 'Poppins', sans-serif; }

        /* ── KPI cards ── */
        .kpi-card {
            background: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 16px;
            padding: 22px 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,.04);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .kpi-icon {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
        }
        .kpi-value {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem; font-weight: 900; color: #111; line-height: 1;
        }
        .kpi-label { font-size: 12px; color: #9ca3af; font-weight: 500; }
        .kpi-sub { font-size: 11px; color: #6b7280; display: flex; align-items: center; gap: 4px; }

        /* ── Chart cards ── */
        .chart-card {
            background: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 16px;
            padding: 22px 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,.04);
        }
        .chart-title {
            font-size: .95rem; font-weight: 700; color: #111; margin-bottom: 18px;
            display: flex; align-items: center; gap: 8px;
        }
        .chart-title i { color: #0056b3; font-size: .9rem; }

        /* ── Top vendeurs ── */
        .top-row {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 0; border-bottom: 1px solid #f3f4f6;
        }
        .top-row:last-child { border-bottom: none; }
        .top-avatar {
            width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, #0056b3, #004a99);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: white;
        }
        .rank-badge {
            width: 22px; height: 22px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: 700; flex-shrink: 0;
        }

        /* ── Dernières annonces ── */
        .latest-row {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 0; border-bottom: 1px solid #f3f4f6;
            text-decoration: none; color: inherit;
            transition: background .15s; border-radius: 6px;
        }
        .latest-row:last-child { border-bottom: none; }
        .latest-row:hover { background: #f8faff; padding-left: 6px; }
        .latest-thumb {
            width: 44px; height: 44px; border-radius: 8px;
            background: #f4f6f8; overflow: hidden; flex-shrink: 0;
        }
        .latest-thumb img { width:100%;height:100%;object-fit:cover; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/flash.php'; ?>
<?php include __DIR__ . '/../partials/modals.php'; ?>

<main class="flex-1" style="max-width:1280px;margin:0 auto;width:100%;padding:28px 20px 56px;">

    <!-- Page header -->
    <div style="margin-bottom:28px;">
        <h1 style="font-size:1.5rem;font-weight:700;color:#111;display:flex;align-items:center;gap:10px;">
            <i class="fa-solid fa-chart-line" style="color:#0056b3;font-size:1.2rem;"></i>
            Statistiques
        </h1>
        <p style="font-size:13px;color:#9ca3af;margin-top:4px;">Vue d'ensemble de la plateforme UTexCHANGE</p>
    </div>

    <!-- ── Ligne 1 : KPI cards ── -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">

        <!-- Total annonces -->
        <div class="kpi-card">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div class="kpi-icon" style="background:#eff6ff;">
                    <i class="fa-solid fa-tag" style="color:#0056b3;"></i>
                </div>
            </div>
            <div>
                <p class="kpi-value"><?= number_format($annonceStats['actives'] + ($annonceStats['vendues'] ?? 0)) ?></p>
                <p class="kpi-label">Annonces totales</p>
            </div>
            <div class="kpi-sub">
                <span style="color:#16a34a;font-weight:600;"><?= $annonceStats['actives'] ?></span> actives
                &nbsp;·&nbsp;
                <span style="color:#1d4ed8;font-weight:600;"><?= $annonceStats['vendues'] ?></span> vendues
            </div>
        </div>

        <!-- Utilisateurs -->
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#ede9fe;">
                <i class="fa-solid fa-users" style="color:#7c3aed;"></i>
            </div>
            <div>
                <p class="kpi-value"><?= number_format($nbUsers) ?></p>
                <p class="kpi-label">Utilisateurs inscrits</p>
            </div>
            <div class="kpi-sub">
                <i class="fa-solid fa-user-plus" style="font-size:9px;"></i>
                Membres de la communauté UT
            </div>
        </div>

        <!-- Conversations -->
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#d1fae5;">
                <i class="fa-regular fa-comment-dots" style="color:#059669;"></i>
            </div>
            <div>
                <p class="kpi-value"><?= number_format($nbConversations) ?></p>
                <p class="kpi-label">Conversations</p>
            </div>
            <div class="kpi-sub">
                Échanges entre membres
            </div>
        </div>

        <!-- Avis -->
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#fef9c3;">
                <i class="fa-solid fa-star" style="color:#f59e0b;"></i>
            </div>
            <div>
                <p class="kpi-value"><?= number_format($nbAvis) ?></p>
                <p class="kpi-label">Avis publiés</p>
            </div>
            <div class="kpi-sub">
                <span style="color:#16a34a;font-weight:600;"><?= $avisStats['actifs'] ?></span> actifs
                &nbsp;·&nbsp;
                <span style="color:#dc2626;font-weight:600;"><?= $avisStats['signales'] ?></span> désactivés
            </div>
        </div>

    </div>

    <!-- ── Ligne 2 : Graphiques principaux ── -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">

        <!-- Répartition des annonces par type — Donut -->
        <div class="chart-card">
            <p class="chart-title">
                <i class="fa-solid fa-chart-pie"></i>
                Répartition par type d'annonce
            </p>
            <div style="max-width:300px;margin:0 auto;">
                <canvas id="chartType"></canvas>
            </div>
        </div>

        <!-- Statuts des annonces — Bar horizontal -->
        <div class="chart-card">
            <p class="chart-title">
                <i class="fa-solid fa-chart-bar"></i>
                Annonces par statut
            </p>
            <canvas id="chartStatus" style="max-height:240px;"></canvas>
        </div>

    </div>

    <!-- ── Ligne 3 : Top catégories + Top vendeurs ── -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">

        <!-- Top catégories — Bar -->
        <div class="chart-card">
            <p class="chart-title">
                <i class="fa-solid fa-layer-group"></i>
                Top catégories
            </p>
            <canvas id="chartCategories" style="max-height:240px;"></canvas>
        </div>

        <!-- Top vendeurs -->
        <div class="chart-card">
            <p class="chart-title">
                <i class="fa-solid fa-trophy"></i>
                Top vendeurs (par note)
            </p>
            <div>
                <?php foreach ($topUsers as $rank => $user): ?>
                    <?php
                    $rankColors = ['#f59e0b','#9ca3af','#cd7f32','#0056b3','#0056b3'];
                    $initiales  = strtoupper(mb_substr($user['prenom'] ?? '?', 0, 1) . mb_substr($user['nom'] ?? '', 0, 1));
                    ?>
                    <div class="top-row">
                    <span class="rank-badge"
                          style="background:<?= $rankColors[$rank] ?? '#e5e7eb' ?>;color:white;">
                        <?= $rank + 1 ?>
                    </span>
                        <div class="top-avatar"><?= $initiales ?></div>
                        <div style="flex:1;">
                            <p style="font-size:13px;font-weight:600;color:#111;">
                                <?= htmlspecialchars(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) ?>
                            </p>
                            <p style="font-size:11px;color:#9ca3af;">
                                <?= $user['nb_avis'] ?> avis
                            </p>
                        </div>
                        <div style="text-align:right;">
                            <p style="font-size:14px;font-weight:700;color:#f59e0b;">
                                <?= number_format($user['note_moyenne'], 1) ?> ★
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($topUsers)): ?>
                    <p style="text-align:center;color:#9ca3af;font-size:13px;padding:30px 0;">Aucune donnée disponible.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- ── Ligne 4 : Avis stats + Dernières annonces ── -->
    <div style="display:grid;grid-template-columns:360px 1fr;gap:16px;">

        <!-- Avis actifs/signalés — Gauge visuel -->
        <div class="chart-card">
            <p class="chart-title">
                <i class="fa-solid fa-star-half-stroke"></i>
                Santé des avis
            </p>
            <?php
            $totalAvis  = ($avisStats['actifs'] + $avisStats['signales']) ?: 1;
            $pctActifs  = round($avisStats['actifs'] / $totalAvis * 100);
            $pctDesact  = 100 - $pctActifs;
            ?>
            <div style="max-width:240px;margin:0 auto;">
                <canvas id="chartAvis"></canvas>
            </div>
            <div style="display:flex;justify-content:center;gap:20px;margin-top:14px;font-size:12px;">
                <span style="display:flex;align-items:center;gap:5px;">
                    <span style="width:10px;height:10px;border-radius:50%;background:#16a34a;display:inline-block;"></span>
                    Actifs (<?= $pctActifs ?>%)
                </span>
                <span style="display:flex;align-items:center;gap:5px;">
                    <span style="width:10px;height:10px;border-radius:50%;background:#dc2626;display:inline-block;"></span>
                    Désactivés (<?= $pctDesact ?>%)
                </span>
            </div>
        </div>

        <!-- Dernières annonces -->
        <div class="chart-card">
            <p class="chart-title">
                <i class="fa-solid fa-clock-rotate-left"></i>
                Dernières annonces publiées
            </p>
            <?php foreach ($lastestAnnonces as $annonce): ?>
                <a href="/annonce/<?= $annonce->getId() ?>" target="_blank" class="latest-row">
                    <div class="latest-thumb">
                        <?php $cover = $covers[$annonce->getId()] ?? null; ?>
                        <?php if ($cover): ?>
                            <img src="/annonce/photo/<?= $annonce->getId() ?>/<?= urlencode($cover->getNomFichier()) ?>"
                                 alt="">
                        <?php else: ?>
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#d1d5db;">
                                <i class="fa-regular fa-image"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="font-size:13px;font-weight:600;color:#111;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            <?= htmlspecialchars($annonce->getTitle()) ?>
                        </p>
                        <p style="font-size:11px;color:#9ca3af;margin-top:1px;">
                            <?= date('d/m/Y à H:i', strtotime($annonce->getCreatedAt())) ?>
                            &nbsp;·&nbsp;
                            <?= $annonce->getType() !== 'don' ? number_format((float)$annonce->getPrice(), 2, ',', ' ') . ' €' : 'Gratuit' ?>
                        </p>
                    </div>
                    <?php
                    $statusColors = ['active'=>'#16a34a','draft'=>'#6b7280','vendu'=>'#1d4ed8','expire'=>'#92400e','archive'=>'#dc2626'];
                    $statusBg     = ['active'=>'#dcfce7','draft'=>'#f3f4f6','vendu'=>'#eff6ff','expire'=>'#fef9c3','archive'=>'#fee2e2'];
                    $sc = $statusColors[$annonce->getStatus()] ?? '#6b7280';
                    $sb = $statusBg[$annonce->getStatus()]     ?? '#f3f4f6';
                    ?>
                    <span style="font-size:9px;font-weight:700;padding:2px 7px;border-radius:20px;
                        background:<?= $sb ?>;color:<?= $sc ?>;white-space:nowrap;flex-shrink:0;">
                    <?= ucfirst($annonce->getStatus()) ?>
                </span>
                    <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:9px;color:#d1d5db;margin-left:6px;"></i>
                </a>
            <?php endforeach; ?>
        </div>

    </div>

</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="/frontend/js/script.js"></script>
<script>
    // ── Données PHP → JS ──────────────────────────────────────
    const annonceStats = {
        dons: <?= (int)($annonceStats['dons']    ?? 0) ?>,
        trocs:  <?= (int)($annonceStats['trocs']   ?? 0) ?>,
        locations: <?= (int)($annonceStats['locations'] ?? 0) ?>,
        ventes: <?= (int)($annonceStats['ventes'] ?? 0) ?>,

        signales: <?= (int)($annonceStats['signales'] ?? 0) ?>,
        vendues: <?= (int)($annonceStats['vendues'] ?? 0) ?>,
        actives: <?= (int)($annonceStats['actives'] ?? 0) ?>,

    };

    const topCategories = <?= json_encode(array_map(fn($c) => [
        'nom'   => $c['nom'],
        'total' => (int)$c['total_annonces'],
    ], $topCategories)) ?>;

    const avisStats = {
        actifs:   <?= (int)($avisStats['actifs']   ?? 0) ?>,
        signales: <?= (int)($avisStats['signales'] ?? 0) ?>,
    };

    // ── Palette ───────────────────────────────────────────────
    const BLUE    = '#0056b3';
    const PALETTE = ['#0056b3','#16a34a','#f59e0b','#7c3aed','#059669','#dc2626'];

    // ── 1. Donut — Répartition par type ──────────────────────
    new Chart(document.getElementById('chartType'), {
        type: 'doughnut',
        data: {
            labels: ['Ventes', 'Dons', 'Trocs', 'Locations'],
            datasets: [{
                data: [
                    annonceStats.ventes,
                    annonceStats.dons,
                    annonceStats.trocs,
                    annonceStats.locations,
                ],
                backgroundColor: ['#0056b3','#16a34a','#f59e0b','#7c3aed'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            plugins: {
                legend: { position: 'bottom', labels: { font: { family: 'Poppins', size: 11 }, padding: 16 } }
            },
            cutout: '68%',
        }
    });

    // ── 2. Bar horizontal — Statuts ───────────────────────────
    new Chart(document.getElementById('chartStatus'), {
        type: 'bar',
        data: {
            labels: ['Actives', 'Vendues', 'Signalées'],
            datasets: [{
                label: 'Annonces',
                data: [annonceStats.actives, annonceStats.vendues, annonceStats.signales],
                backgroundColor: ['#0056b3','#16a34a','#f59e0b'],
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: '#f3f4f6' }, ticks: { font: { family: 'Poppins', size: 11 }, stepSize: 1 } },
                y: { grid: { display: false },   ticks: { font: { family: 'Poppins', size: 11 } } },
            }
        }
    });

    // ── 3. Bar — Top catégories ───────────────────────────────
    new Chart(document.getElementById('chartCategories'), {
        type: 'bar',
        data: {
            labels: topCategories.map(c => c.nom),
            datasets: [{
                label: 'Annonces',
                data: topCategories.map(c => c.total),
                backgroundColor: PALETTE,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { family: 'Poppins', size: 11 } } },
                y: { grid: { color: '#f3f4f6' }, ticks: { font: { family: 'Poppins', size: 11 }, stepSize: 1 } },
            }
        }
    });

    // ── 4. Donut — Santé des avis ─────────────────────────────
    new Chart(document.getElementById('chartAvis'), {
        type: 'doughnut',
        data: {
            labels: ['Actifs', 'Désactivés'],
            datasets: [{
                data: [avisStats.actifs, avisStats.signales],
                backgroundColor: ['#16a34a', '#dc2626'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            cutout: '72%',
        }
    });
</script>
</body>
</html>