function apiFavoriToggle(annonceId) {
    return fetch(`/annonce/${annonceId}/favori`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': getCsrfToken(),
        },
    }).then(r => r.json())
}
//
function apiFavoriCheck(annonceId) {
    return fetch(`/annonce/${annonceId}/is-favori`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(r => r.json());
}
