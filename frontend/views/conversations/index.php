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
    <link rel="stylesheet" href="/frontend/css/conversation.css">
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
    <span style="color:#374151;font-weight:500;">Conversations</span>
</div>

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