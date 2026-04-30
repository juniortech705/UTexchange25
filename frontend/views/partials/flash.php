<?php if (!empty($flashes)): ?>
    <div id="flash-container" style="position:fixed;top:20px;right:20px;z-index:99999;display:flex;flex-direction:column;gap:10px;max-width:360px;">
        <?php
        $styles = [
                'success' => ['bg' => '#dcfce7', 'border' => '#16a34a', 'text' => '#15803d', 'icon' => '✓'],
                'error'   => ['bg' => '#fee2e2', 'border' => '#dc2626', 'text' => '#b91c1c', 'icon' => '✕'],
                'warning' => ['bg' => '#fef9c3', 'border' => '#ca8a04', 'text' => '#92400e', 'icon' => '⚠'],
                'info'    => ['bg' => '#dbeafe', 'border' => '#2563eb', 'text' => '#1d4ed8', 'icon' => 'ℹ'],
        ];
        foreach ($flashes as $type => $messages):
            foreach ($messages as $message):
                $s = $styles[$type] ?? $styles['info'];
                ?>
                <div class="flash-item" style="
                        background: <?= $s['bg'] ?>;
                        border-left: 4px solid <?= $s['border'] ?>;
                        color: <?= $s['text'] ?>;
                        padding: 12px 16px;
                        border-radius: 8px;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        font-size: 14px;
                        font-weight: 500;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                        animation: slideIn 0.3s ease;
                        ">
                    <span style="font-size:16px"><?= $s['icon'] ?></span>
                    <span><?= htmlspecialchars($message) ?></span>
                </div>
            <?php endforeach; endforeach; ?>
    </div>

    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to   { transform: translateX(0);   opacity: 1; }
        }
    </style>

    <script>
        setTimeout(() => {
            document.querySelectorAll('.flash-item').forEach(el => {
                el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                el.style.opacity = '0';
                el.style.transform = 'translateX(20px)';
                setTimeout(() => el.remove(), 400);
            });
        }, 3500);
    </script>
<?php endif; ?>