document.addEventListener('DOMContentLoaded', () => {
    console.log("Script UTEXCHANGE chargé avec succès !");

    // --- NAVIGATION TITRE ---
    const titre = document.querySelector('.logo-text');
    if (titre) {
        titre.style.cursor = "pointer";
        titre.addEventListener('click', () => {
            window.location.href = "index.php";
        });
    }

    // --- GESTION DE LA MODALE ---
    const modal = document.getElementById("loginModal");
    const openBtn = document.getElementById("openLogin");
    const closeBtn = document.querySelector(".close-modal");
    const toSignup = document.getElementById("showSignup");
    const toLogin = document.getElementById("showLogin");
    const loginForm = document.getElementById("loginForm");
    const signupForm = document.getElementById("signupForm");

    if (openBtn) {
        openBtn.addEventListener('click', () => {
            console.log("Ouverture de la modale...");
            modal.style.display = "block";
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            modal.style.display = "none";
        });
    }

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });

    if (toSignup) {
        toSignup.addEventListener('click', (e) => {
            e.preventDefault();
            loginForm.style.display = "none";
            signupForm.style.display = "block";
        });
    }

    if (toLogin) {
        toLogin.addEventListener('click', (e) => {
            e.preventDefault();
            signupForm.style.display = "none";
            loginForm.style.display = "block";
        });
    }

    // --- EFFET DE FOND ---
    document.addEventListener('mousemove', (e) => {
        const moveX = (e.clientX * -0.005);
        const moveY = (e.clientY * -0.005);
        document.body.style.backgroundPosition = `calc(50% + ${moveX}px) calc(50% + ${moveY}px)`;
    });
});