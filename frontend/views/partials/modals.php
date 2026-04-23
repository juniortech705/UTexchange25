<!-- ============================================================
     Système de modales — inclus une seule fois dans le layout
     Toutes les modales partagent le même overlay de fond
     ============================================================ -->

<link rel="stylesheet" href="/frontend/css/modals.css">

<!-- ── MODALE LOGIN ───────────────────────────────────────── -->
<div class="modal-overlay" id="loginModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModalAndRedirect()">&times;</button>
        <p class="modal-title">Bon retour !</p>
        <p class="modal-subtitle">Connectez-vous à votre espace UTexCHANGE</p>

        <form method="POST" action="/login">
            <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">

            <div class="modal-field">
                <label>Adresse email UT</label>
                <input type="email" name="email" placeholder="prenom.nom@utbm.fr" required>
            </div>

            <div class="modal-field">
                <label>Mot de passe</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="modal-btn">Se connecter</button>
        </form>

        <p class="modal-switch">
            Pas encore de compte ?
            <a onclick="switchModal('loginModal','registerModal')">Créer un compte</a>
        </p>
    </div>
</div>

<!-- ── MODALE REGISTER ───────────────────────────────────── -->
<div class="modal-overlay" id="registerModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModalAndRedirect()">&times;</button>
        <p class="modal-title">Rejoignez la communauté</p>

        <form method="POST" action="/register" id="registerForm">
            <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">

            <div class="modal-field-row">
                <div class="modal-field">
                    <label>Nom</label>
                    <input type="text" name="nom" placeholder="Dupont" required>
                </div>
                <div class="modal-field">
                    <label>Prénom</label>
                    <input type="text" name="prenom" placeholder="Jean" required>
                </div>
            </div>

            <div class="modal-field">
                <label>Campus</label>
                <select name="campus">
                    <option value="" disabled>Choisir un campus</option>
                    <?php foreach (['Belfort','Compiègne','Montbéliard','Sevenans','Tarbes','Troyes'] as $campus): ?>
                        <option value="<?= $campus ?>" > <?= $campus ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="modal-field">
                <label>Adresse email UT</label>
                <input type="email" name="email" placeholder="prenom.nom@utbm.fr" required>
            </div>

            <div class="modal-field">
                <label>Mot de passe</label>
                <input type="password" name="password" id="reg-pwd" placeholder="8 caractères minimum" required minlength="8">
                <div class="pwd-strength" id="pwd-strength-bar"></div>
            </div>

            <div class="modal-field">
                <label>Confirmer le mot de passe</label>
                <input type="password" name="password_confirm" id="reg-pwd-confirm" placeholder="••••••••" required>
                <p class="pwd-match-msg" id="pwd-match-msg"></p>
            </div>

            <button type="submit" class="modal-btn" id="reg-submit">Créer mon compte</button>
        </form>

        <p class="modal-switch">
            Déjà inscrit ?
            <a onclick="switchModal('registerModal','loginModal')">Se connecter</a>
        </p>
    </div>
</div>

<script>
    function closeModalAndRedirect() {
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = "/";
        }
    }
</script>