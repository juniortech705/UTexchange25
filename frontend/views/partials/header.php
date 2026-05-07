<?php
$isLoggedIn = Session::isLoggedIn();
$isAdmin    = $isLoggedIn && in_array(Session::userRole(), ['Administrateur', 'super-admin']);
$userId     = Session::get('user_id');
?>
<header class="main-header">
    <div class="header-wrapper">

        <div class="logo-area">
            <a href="/" class="logo-link">
                <h1 class="logo-text">UTexCHANGE</h1>
            </a>
        </div>

        <div class="header-right-side">

            <a href="/annonce/create" class="btn-deposer">
                <i class="fa-solid fa-square-plus"></i>
                <span>Déposer une annonce</span>
            </a>
            <form class="search-container" action="/annonces" method="GET">
                <input type="text" name="search" placeholder="Rechercher sur UTexchange"
                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>

            <nav class="user-nav">
                <?php if ($isLoggedIn): ?>

                    <a href="/favoris" class="nav-item">
                        <i class="fa-regular fa-heart"></i>
                        <span>Favoris</span>
                    </a>

                    <a href="/conversations" class="nav-item" style="position:relative;">
                        <i class="fa-regular fa-comment-dots"></i>
                        <span>Messages</span>
                        <span id="unread-badge" style="display:none;position:absolute;top:-4px;right:-4px;background:#ef4444;color:white;font-size:10px;border-radius:50%;width:16px;height:16px;align-items:center;justify-content:center;font-weight:700;">0</span>
                    </a>

                    <a href="/users/profil/<?= $userId ?>" class="nav-item">
                        <i class="fa-regular fa-user"></i>
                        <span><?= htmlspecialchars(Session::get('prenom') ?? 'Mon compte') ?></span>
                    </a>

                    <?php if ($isAdmin): ?>
                        <a href="/dashboard" class="nav-item nav-item--admin">
                            <i class="fa-solid fa-gauge-high"></i>
                            <span>Admin</span>
                        </a>
                    <?php endif; ?>

                    <a href="/logout" class="nav-item">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Déconnexion</span>
                    </a>

                <?php else: ?>

                    <button class="nav-item" onclick="openModal('loginModal')">
                        <i class="fa-regular fa-user"></i>
                        <span>Se connecter</span>
                    </button>

                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>

<style>
    .nav-item--admin i  { color: #f59e0b !important; }
    .nav-item--admin span { color: #f59e0b !important; font-weight: 700; }
    .nav-item { background: none; border: none; cursor: pointer; }
</style>