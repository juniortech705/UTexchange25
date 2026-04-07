<?php if (!empty($flashes)): ?>

    <div id="flash-container" class="fixed top-5 right-5 space-y-3 z-50">

        <?php foreach ($flashes as $type => $messages): ?>
            <?php foreach ($messages as $message): ?>

                <?php
                $colors = [
                    'success' => 'bg-green-500',
                    'error' => 'bg-red-500',
                    'info' => 'bg-blue-500',
                    'warning' => 'bg-yellow-500'
                ];

                $icons = [
                    'success' => '✔️',
                    'error' => '❌',
                    'info' => 'ℹ️',
                    'warning' => '⚠️'
                ];

                $bg = $colors[$type] ?? 'bg-gray-500';
                $icon = $icons[$type] ?? 'ℹ️';
                ?>

                <div class="flash-message <?= $bg ?> text-white px-4 py-3 rounded shadow flex items-center gap-2 animate-fade-in">
                    <span><?= $icon ?></span>
                    <span><?= htmlspecialchars($message) ?></span>
                </div>

            <?php endforeach; ?>
        <?php endforeach; ?>

    </div>

    <script>
        setTimeout(() => {
            const flashes = document.querySelectorAll('.flash-message');
            flashes.forEach(el => {
                el.style.transition = "opacity 0.5s ease";
                el.style.opacity = "0";
                setTimeout(() => el.remove(), 500);
            });
        }, 3000);
    </script>

<?php endif; ?>