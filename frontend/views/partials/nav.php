<?php
$categories = $GLOBALS['nav_categories'] ?? [];
?>

<nav class="categories-nav" style="position:relative;z-index:100;">
    <ul style="list-style:none;display:flex;justify-content:center;gap:0;margin:0;padding:0;flex-wrap:wrap;">

        <!-- Toutes les annonces -->
        <li style="position:relative;">
            <a href="/annonces"
               style="display:flex;align-items:center;gap:5px;padding:10px 14px;
                      font-size:13px;font-weight:600;color:#374151;text-decoration:none;
                      transition:color .15s;white-space:nowrap;"
               onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#374151'">
                <i class="fa-solid fa-border-all" style="font-size:11px;"></i> Tout
            </a>
        </li>

        <!-- Don rapide -->
        <li style="position:relative;">
            <a href="/annonces?type=don"
               style="display:flex;align-items:center;gap:5px;padding:10px 14px;
                      font-size:13px;font-weight:600;color:#059669;text-decoration:none;
                      transition:opacity .15s;white-space:nowrap;"
               onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='1'">
                <i class="fa-solid fa-gift" style="font-size:11px;"></i> Dons
            </a>
        </li>

        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $cat): ?>
                <?php $hasChildren = !empty($cat['enfants']); ?>
                <li class="nav-cat-item" style="position:relative;">
                    <a href="/annonces?categorie_id=<?= $cat['id'] ?>"
                       style="display:flex;align-items:center;gap:5px;padding:10px 14px;
                          font-size:13px;font-weight:500;color:#374151;text-decoration:none;
                          transition:color .15s;white-space:nowrap;"
                       onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#374151'">
                        <?= htmlspecialchars($cat['nom']) ?>
                        <?php if ($hasChildren): ?>
                            <i class="fa-solid fa-chevron-down" style="font-size:8px;color:#9ca3af;margin-left:2px;"></i>
                        <?php endif; ?>
                    </a>

                    <!-- Sous-menu dropdown -->
                    <?php if ($hasChildren): ?>
                        <div class="nav-dropdown"
                             style="display:none;position:absolute;top:100%;left:0;
                            background:#fff;border:1px solid #e5e7eb;border-radius:12px;
                            padding:8px;min-width:180px;box-shadow:0 8px 24px rgba(0,0,0,.1);
                            z-index:200;">
                            <?php foreach ($cat['enfants'] as $enfant): ?>
                                <a href="/annonces?categorie_id=<?= $enfant['id'] ?>"
                                   style="display:flex;align-items:center;gap:8px;padding:8px 12px;
                              border-radius:8px;text-decoration:none;color:#374151;
                              font-size:12px;font-weight:500;transition:background .15s;"
                                   onmouseover="this.style.background='#f0f6ff';this.style.color='#0056b3'"
                                   onmouseout="this.style.background='transparent';this.style.color='#374151'">
                                        <span style="width:14px;height:14px;border-radius:50%;
                                         background:#e5e7eb;display:inline-block;flex-shrink:0;"></span>
                                    <?= htmlspecialchars($enfant['nom']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>

    </ul>
</nav>

<style>
    .nav-cat-item:hover .nav-dropdown { display:block !important; animation:dropIn .15s ease; }
    @keyframes dropIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
</style>

