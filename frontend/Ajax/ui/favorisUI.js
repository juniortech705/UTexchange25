//Mise à jour du DOM après exécution de AJAX
function toggleFavori(annonceId, btn) {
    // Feedback immédiat — évite le double clic
    btn.disabled = true;

    apiFavoriToggle(annonceId)
        .then(data => {
            if (!data.success) {
                console.error('Erreur favori:', data.message);
                btn.disabled = false;
                return;
            }

            const added = data.action === 'added';
            updateFavoriButton(btn, added);

            // Sur la page favoris : retire la carte du DOM si retrait
            if (!added) {
                const card = btn.closest('.annonce-card');
                if (card) {
                    card.style.transition = 'opacity .3s, transform .3s';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => card.remove(), 300);
                }
            }

            btn.disabled = false;
        })
        .catch(() => { btn.disabled = false; });
}


function checkFavoriStatus(annonceId) {
    apiFavoriCheck(annonceId).then(data => {
        const btn = document.getElementById('favori-btn');
        if (btn) updateFavoriButton(btn, data.favori);
    });
}

//Met à jour l'apparence du bouton favori selon l'état
function updateFavoriButton(btn, isFavori) {
    const icon = btn.querySelector('.favori-icon');
    const label = btn.querySelector('#favori-label');

    if (isFavori) {
        if (icon)  { icon.className = 'fa-solid fa-heart'; icon.style.color = '#ef4444'; }
        if (label) label.textContent = 'Sauvegardé';
        btn.style.borderColor = '#ef4444';
        btn.style.color = '#ef4444';
    } else {
        if (icon)  { icon.className = 'fa-regular fa-heart'; icon.style.color = '#0056b3'; }
        if (label) label.textContent = 'Sauvegarder';
        btn.style.borderColor = '#0056b3';
        btn.style.color = '#0056b3';
    }

    // Bouton wishlist sur les cartes (index.php, favoris.php)
    const cardIcon = btn.querySelector('i');
    if (!label && cardIcon) {
        cardIcon.className = isFavori ? 'fa-solid fa-heart' : 'fa-regular fa-heart';
        cardIcon.style.color = isFavori ? '#ef4444' : '#0056b3';
    }
}