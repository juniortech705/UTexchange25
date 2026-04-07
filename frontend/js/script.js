console.log("Script UTEXCHANGE chargé avec succès !");

const titre = document.querySelector('h1');
if (titre) {
    titre.addEventListener('click', function() {
        window.location.href = "home.php";
    });
}

const liensCategories = document.querySelectorAll('.categories-nav a');

liensCategories.forEach(lien => {
    lien.addEventListener('click', function(e) {
        const nomCategorie = this.textContent;
        console.log("Navigation vers la catégorie : " + nomCategorie);
        
        liensCategories.forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        
    });
});

const btn = document.querySelector('.btn-deposer');
if (btn) {
    btn.addEventListener('click', function(e) {
        console.log("L'utilisateur veut déposer une annonce.");
    });
}

// --- Effet de mouvement léger sur le fond UT ---
document.addEventListener('mousemove', (e) => {
    const moveX = (e.clientX * -0.01);
    const moveY = (e.clientY * -0.01);
    document.body.style.backgroundPosition = `calc(50% + ${moveX}px) calc(50% + ${moveY}px)`;
});