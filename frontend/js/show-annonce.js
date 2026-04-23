function selectPhoto(thumbEl, src) {
    // Met à jour les thumbnails
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    thumbEl.classList.add('active');

    // Transition sur la photo principale
    const img = document.getElementById('gallery-main-img');
    if (!img) return;
    img.style.opacity = '0';
    setTimeout(() => {
        img.src = src;
        img.style.opacity = '1';
    }, 150);
}