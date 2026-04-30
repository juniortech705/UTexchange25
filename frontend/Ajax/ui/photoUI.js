function deletePhoto(photoId) {
    if (!confirm('Supprimer cette photo ?')) return;

    apiPhotoDelete(photoId)
        .then(res => {
            if (!res.success) {
                alert(res.message || 'Erreur suppression');
                return;
            }

            const el = document.getElementById(`photo-${photoId}`);
            if (el) el.remove();
        })
        .catch(() => alert('Erreur réseau'));
}

function setCover(photoId) {
    apiPhotoSetCover(photoId)
        .then(res => {
            if (!res.success) return;

            document.querySelectorAll('#existing-photos > div').forEach(div => {
                div.style.borderColor = '#e5e7eb';

                const badge = div.querySelector('.cover-badge');
                if (badge) badge.remove();
            });

            const el = document.getElementById(`photo-${photoId}`);
            if (!el) return;

            el.style.borderColor = '#0056b3';

            const badge = document.createElement('span');
            badge.className = 'cover-badge';
            badge.innerText = 'Cover';
            badge.style.cssText = `
                position:absolute;
                bottom:0;
                left:0;
                right:0;
                background:#0056b3;
                color:white;
                font-size:9px;
                text-align:center;
            `;

            el.appendChild(badge);
        })
        .catch(() => alert('Erreur réseau'));
}