document.addEventListener('DOMContentLoaded', () => {

    // ── Système de modales ──────────────────────────────────

    window.openModal = function(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeModal = function(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    };

    window.switchModal = function(from, to) {
        closeModal(from);
        setTimeout(() => openModal(to), 150);
    };

    // Fermeture au clic sur le fond
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', e => {
            if (e.target === overlay) closeModal(overlay.id);
        });
    });

    // Fermeture avec Echap
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(m => closeModal(m.id));
        }
    });

    // ── Helpers mot de passe ────────────────────────────────

    /**
     * Indicateur de force du mot de passe
     * @param {string} inputId  - id du champ password
     * @param {string} barId    - id de la barre de progression
     */
    window.initPasswordStrength = function(inputId, barId) {
        const input = document.getElementById(inputId);
        const bar   = document.getElementById(barId);
        if (!input || !bar) return;

        input.addEventListener('input', () => {
            const val = input.value;
            let score = 0;
            if (val.length >= 8)              score++;
            if (/[A-Z]/.test(val))            score++;
            if (/[0-9]/.test(val))            score++;
            if (/[^A-Za-z0-9]/.test(val))     score++;

            const levels = [
                { w: '25%',  bg: '#ef4444' },
                { w: '50%',  bg: '#f97316' },
                { w: '75%',  bg: '#eab308' },
                { w: '100%', bg: '#16a34a' },
            ];
            const l = levels[Math.max(0, score - 1)] || levels[0];
            bar.style.width      = val.length ? l.w : '0%';
            bar.style.background = l.bg;
        });
    };

    /**
     * Feedback de correspondance des mots de passe
     */
    window.initPasswordMatch = function(pwdId, confirmId, msgId, submitId) {
        const pwd     = document.getElementById(pwdId);
        const confirm = document.getElementById(confirmId);
        const msg     = document.getElementById(msgId);
        const submit  = document.getElementById(submitId);
        if (!pwd || !confirm) return;

        const check = () => {
            if (!confirm.value) { msg.textContent = ''; return; }
            const match = pwd.value === confirm.value;
            msg.textContent = match ? '✓ Les mots de passe correspondent' : '✕ Les mots de passe ne correspondent pas';
            msg.style.color = match ? '#16a34a' : '#ef4444';
            confirm.style.borderColor = match ? '#16a34a' : '#ef4444';
            if (submit) submit.disabled = !match;
        };

        pwd.addEventListener('input', check);
        confirm.addEventListener('input', check);
    };

    // Init sur les formulaires présents dans la page
    initPasswordStrength('reg-pwd', 'pwd-strength-bar');
    initPasswordMatch('reg-pwd', 'reg-pwd-confirm', 'pwd-match-msg', 'reg-submit');
    initPasswordStrength('new-pwd', 'new-pwd-bar');
    initPasswordMatch('new-pwd', 'new-pwd-confirm', 'new-pwd-match', 'pass-submit');

    // ── Badge messages non lus ──────────────────────────────

    const badge = document.getElementById('unread-badge');
    if (badge) {
        const fetchUnread = () => {
            fetch('/messages/unread-count')
                .then(r => r.json())
                .then(data => {
                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                })
                .catch(() => {});
        };
        fetchUnread();
        setInterval(fetchUnread, 30000); // toutes les 30s
    }

    // ── Navigation logo ─────────────────────────────────────

    const logo = document.querySelector('.logo-text');
    if (logo) {
        logo.style.cursor = 'pointer';
        logo.addEventListener('click', () => window.location.href = '/');
    }

    // ── Effet fond souris ────────────────────────────────────

    document.addEventListener('mousemove', e => {
        const x = (e.clientX * -0.004);
        const y = (e.clientY * -0.004);
        document.body.style.backgroundPosition = `calc(50% + ${x}px) calc(50% + ${y}px)`;
    });

});

function getCsrfToken() {
    const input = document.querySelector('input[name="_csrf_token"]');
    return input ? input.value : '';
}