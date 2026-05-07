<?php
$categories  = $GLOBALS['nav_categories'] ?? [];

// Lecture directe de $_GET pour détecter le filtre actif
// Sans dépendre des variables du controller
$activeCatId = isset($_GET['cat_id'])  ? (int) $_GET['cat_id']  : null;
$activeType  = isset($_GET['type'])    ? $_GET['type']           : null;
$isAllActive = !$activeCatId && !$activeType && basename($_SERVER['REQUEST_URI']) !== '/';

// Style de base et style actif
$baseStyle   = "display:flex;align-items:center;gap:5px;padding:10px 14px;font-size:13px;font-weight:500;text-decoration:none;transition:color .15s,background .15s;white-space:nowrap;border-radius:8px;";
$activeStyle = "color:#0056b3;background:#eff6ff;font-weight:700;";
$inactiveStyle = "color:#374151;";
?>

<nav class="categories-nav" style="position:relative;z-index:100;">
    <ul style="list-style:none;display:flex;justify-content:center;gap:2px;margin:0;padding:0 8px;flex-wrap:wrap;">

        <!-- Tout -->
        <li style="position:relative;">
            <a href="/annonces"
               style="<?= $baseStyle . ($isAllActive ? $activeStyle : $inactiveStyle) ?>"
               onmouseover="this.style.color='#0056b3';this.style.background='#f0f6ff'"
               onmouseout="this.style.color='<?= $isAllActive ? '#0056b3' : '#374151' ?>'; this.style.background='<?= $isAllActive ? '#eff6ff' : 'transparent' ?>'">
                <i class="fa-solid fa-border-all" style="font-size:11px;"></i> Tout
            </a>
        </li>

        <!-- Dons -->
        <?php $donActive = $activeType === 'don'; ?>
        <li style="position:relative;">
            <a href="/annonces?type=don"
               style="<?= $baseStyle ?> color:<?= $donActive ? '#059669' : '#374151' ?>;<?= $donActive ? 'background:#d1fae5;font-weight:700;' : '' ?>"
               onmouseover="this.style.color='#059669';this.style.background='#ecfdf5'"
               onmouseout="this.style.color='<?= $donActive ? '#059669' : '#374151' ?>'; this.style.background='<?= $donActive ? '#d1fae5' : 'transparent' ?>'">
                <i class="fa-solid fa-gift" style="font-size:11px;"></i> Dons
            </a>
        </li>

        <?php foreach ($categories as $cat): ?>
            <?php
            $hasChildren  = !empty($cat['enfants']);
            $isCatActive  = $activeCatId === (int) $cat['id'];

            // Vérifie si un enfant est actif (pour surligner le parent aussi)
            $isChildActive = false;
            if ($hasChildren && !$isCatActive) {
                foreach ($cat['enfants'] as $enfant) {
                    if ($activeCatId === (int) $enfant['id']) {
                        $isChildActive = true;
                        break;
                    }
                }
            }
            $isActive = $isCatActive || $isChildActive;
            ?>
            <li class="nav-cat-item" style="position:relative;">
                <a href="/annonces?cat_id=<?= $cat['id'] ?>"
                   style="<?= $baseStyle . ($isActive ? $activeStyle : $inactiveStyle) ?>"
                   onmouseover="this.style.color='#0056b3';this.style.background='#f0f6ff'"
                   onmouseout="this.style.color='<?= $isActive ? '#0056b3' : '#374151' ?>'; this.style.background='<?= $isActive ? '#eff6ff' : 'transparent' ?>'">
                    <?= htmlspecialchars($cat['nom']) ?>
                    <?php if ($hasChildren): ?>
                        <i class="fa-solid fa-chevron-down"
                           style="font-size:8px;color:<?= $isActive ? '#0056b3' : '#9ca3af' ?>;margin-left:2px;"></i>
                    <?php endif; ?>
                </a>

                <?php if ($hasChildren): ?>
                    <div class="nav-dropdown"
                         style="display:none;position:absolute;top:100%;left:0;
                            background:#fff;border:1px solid #e5e7eb;border-radius:12px;
                            padding:8px;min-width:180px;box-shadow:0 8px 24px rgba(0,0,0,.1);
                            z-index:200;">
                        <?php foreach ($cat['enfants'] as $enfant): ?>
                            <?php $isEnfantActive = $activeCatId === (int) $enfant['id']; ?>
                            <a href="/annonces?cat_id=<?= $enfant['id'] ?>"
                               style="display:flex;align-items:center;gap:8px;padding:8px 12px;
                                  border-radius:8px;text-decoration:none;font-size:12px;font-weight:500;
                                  transition:background .15s;
                                  <?= $isEnfantActive ? 'background:#eff6ff;color:#0056b3;font-weight:700;' : 'color:#374151;' ?>"
                               onmouseover="this.style.background='#f0f6ff';this.style.color='#0056b3'"
                               onmouseout="this.style.background='<?= $isEnfantActive ? '#eff6ff' : 'transparent' ?>'; this.style.color='<?= $isEnfantActive ? '#0056b3' : '#374151' ?>'">
                            <span style="width:6px;height:6px;border-radius:50%;flex-shrink:0;
                                    background:<?= $isEnfantActive ? '#0056b3' : '#d1d5db' ?>;"></span>
                                <?= htmlspecialchars($enfant['nom']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<style>
    .nav-cat-item:hover .nav-dropdown { display:block !important; animation:dropIn .15s ease; }
    @keyframes dropIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
</style>