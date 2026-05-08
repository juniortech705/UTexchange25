<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes conversations — UTexCHANGE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="stylesheet" href="/frontend/css/modals.css">
    <link rel="icon" type="image/png" href="/Images/favicon_utexchange.png">
    <style>
        body { font-family: 'Poppins', sans-serif; }

        .conv-item {
            display: flex; align-items: center; gap: 14px;
            padding: 14px 16px; border-radius: 12px;
            text-decoration: none; color: inherit;
            transition: background .15s;
            border-bottom: 1px solid #f3f4f6;
        }
        .conv-item:last-child { border-bottom: none; }
        .conv-item:hover  { background: #f8faff; }
        .conv-item.unread { background: #f0f6ff; }

        .conv-avatar {
            width: 46px; height: 46px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, #0056b3, #004a99);
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 700; color: white;
        }

        .conv-title { font-size: 14px; font-weight: 600; color: #111; }
        .conv-last  {
            font-size: 12px; color: #9ca3af;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            max-width: 280px;
        }
        .conv-last.unread-text { color: #374151; font-weight: 600; }
        .conv-time { font-size: 11px; color: #9ca3af; flex-shrink: 0; }

        .conv-badge {
            min-width: 18px; height: 18px; border-radius: 20px;
            background: #0056b3; color: white;
            font-size: 10px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            padding: 0 5px; flex-shrink: 0;
        }

        .status-dot {
            width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0;
            margin-top: 2px;
        }
        .status-dot--active   { background: #16a34a; }
        .status-dot--terminee { background: #9ca3af; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/flash.php'; ?>
<?php include __DIR__ . '/../partials/modals.php'; ?>

<main class="flex-1" style="max-width:680px;margin:0 auto;width:100%;padding:28px 20px 56px;">

    <?php if (!empty($items)): ?>
        <div style="background:#fff;border:1px solid #f0f0f0;border-radius:16px;
                box-shadow:0 2px 12px rgba(0,0,0,.04);overflow:hidden;">

            <?php foreach ($items as $item): ?>
                <?php
                $conv   = $item['conversation'];
                $other  = $item['otherUser'];
                $unread = $item['unread'];

                $initiales = strtoupper(
                    mb_substr($other->getPrenom() ?? '?', 0, 1) .
                    mb_substr($other->getNom() ?? '', 0, 1)
                );
                ?>
                <a href="/conversations/<?= $conv->getId() ?>"
                   class="conv-item <?= $unread > 0 ? 'unread' : '' ?>">

                    <div class="conv-avatar"><?= $initiales ?></div>

                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;align-items:center;gap:6px;margin-bottom:3px;">
                    <span class="conv-title">
                        <?= htmlspecialchars(($other->getPrenom() ?? '') . ' ' . ($other->getNom() ?? '')) ?>
                    </span>
                            <span class="status-dot status-dot--<?= $conv->getStatus() ?>"></span>
                        </div>
                        <p class="conv-last <?= $unread > 0 ? 'unread-text' : '' ?>">
                            <?= htmlspecialchars($conv->getDernierMessage() ?? 'Démarrer la conversation…') ?>
                        </p>
                    </div>

                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:5px;flex-shrink:0;">
                <span class="conv-time">
                    <?php
                    $ts = $conv->getLastMessageAt();
                    echo $ts ? date('d/m H:i', strtotime($ts)) : '';
                    ?>
                </span>
                        <?php if ($unread > 0): ?>
                            <span class="conv-badge"><?= $unread ?></span>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <div style="text-align:center;padding:80px 0;color:#9ca3af;">
            <i class="fa-regular fa-comment-dots"
               style="font-size:3rem;display:block;margin-bottom:16px;opacity:.4;"></i>
            <p style="font-size:1rem;font-weight:600;color:#374151;">Aucune conversation</p>
            <p style="font-size:13px;margin-top:4px;">Contactez un vendeur depuis une annonce.</p>
        </div>
    <?php endif; ?>

</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="/frontend/js/script.js"></script>
<script src="/frontend/Ajax/services/conversation.js"></script>
</body>
</html>