<header class="main-header">
    <div class="header-wrapper">
        <div class="logo-area">
            <a href="index.php" class="logo-link">
                <h1 class="logo-text">UTexCHANGE</h1>
            </a>
        </div>

        <div class="header-right-side">
            <a href="deposer.php" class="btn-deposer">
                <i class="fa-solid fa-square-plus"></i> 
                <span>Déposer une annonce</span>
            </a>

            <div class="search-container">
                <input type="text" placeholder="Rechercher sur UTexchange">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>

            <nav class="user-nav">
                <a href="front-end/views/users/mes-recherches.php" class="nav-item">
                    <i class="fa-regular fa-bell"></i>
                    <span>Mes recherches</span>
                </a>
                <a href="front-end/views/users/favoris.php" class="nav-item">
                    <i class="fa-regular fa-heart"></i>
                    <span>Favoris</span>
                </a>
                <a href="front-end/views/users/messages.php" class="nav-item">
                    <i class="fa-regular fa-comment-dots"></i>
                    <span>Messages</span>
                </a>
                
                <div class="nav-item" id="openLogin" style="cursor: pointer;">
                    <i class="fa-regular fa-user"></i>
                    <span>Se connecter</span>
                </div>
            </nav>
        </div>
    </div>
</header>

<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        
        <div id="loginForm">
            <h2>Connexion</h2>
            <form action="backend/auth.php" method="POST">
                <div class="input-group">
                    <label>Adresse UT</label>
                    <input type="email" name="email" placeholder="prenom.nom@utbm.fr" required>
                </div>
                <div class="input-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" name="submit_login" class="btn-auth">Se connecter</button>
            </form>
            <p class="switch-text">Pas encore de compte ? <a href="#" id="showSignup">Créer un compte</a></p>
        </div>

        <div id="signupForm" style="display: none;">
            <h2>Inscription Étudiant</h2>
            <form action="backend/register.php" method="POST">
                <div style="display: flex; gap: 10px;">
                    <div class="input-group" style="flex: 1;">
                        <label>Nom</label>
                        <input type="text" name="nom" placeholder="Nom" required>
                    </div>
                    <div class="input-group" style="flex: 1;">
                        <label>Prénom</label>
                        <input type="text" name="prenom" placeholder="Prénom" required>
                    </div>
                </div>

                <div class="input-group">
                    <label>Campus</label>
                    <select name="campus" required>
                        <option value="" disabled selected>Choisir un campus</option>
                        <option value="Belfort">Belfort</option>
                        <option value="Compiègne">Compiègne</option>
                        <option value="Montbéliard">Montbéliard</option>
                        <option value="Sevenans">Sevenans</option>
                        <option value="Tarbes">Tarbes</option>
                        <option value="Troyes">Troyes</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Adresse UT</label>
                    <input type="email" name="email" placeholder="votre.nom@utbm.fr" required>
                </div>

                <div class="input-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" name="submit_register" class="btn-auth">Créer mon compte</button>
            </form>
            <p class="switch-text">Déjà inscrit ? <a href="#" id="showLogin">Se connecter</a></p>
        </div>
    </div>
</div>