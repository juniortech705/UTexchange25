function apiPhotoDelete(photoId) {
    return fetch(`/photos/delete/${photoId}`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': getCsrfToken(),
        },
    }).then(r => r.json());
}

function apiPhotoSetCover(photoId) {
    return fetch(`/photos/cover/${photoId}`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': getCsrfToken(),
        },
    }).then(r => r.json());
}