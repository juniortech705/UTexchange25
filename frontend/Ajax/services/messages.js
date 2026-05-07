const MessagesService = (() => {
    function post(url, body = {}) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: new URLSearchParams({ _csrf_token: getCsrfToken(), ...body }),
        }).then(async r => {
            const text = await r.text();

            try {
                return JSON.parse(text);
            } catch (e) {
                console.error("Réponse non JSON :", text);
                throw e;
            }
        });
    }

    function get(url) {
        return fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(r => r.json());
    }

    return {
        // Envoie un nouveau message
        send(convId, contenu) {
            return post(`/conversations/${convId}/messages/send`, { contenu });
        },

        // Récupère les nouveaux messages depuis lastId (polling)
        getNew(convId,  lastId) {
            return get(`/conversations/${convId}/messages?last_id=${lastId}`);
        },

        // Marque les messages comme lus
        markRead(convId) {
            return post(`/conversations/${convId}/read`);
        },

        // Modifie un message
        update(messageId, contenu) {
            return post(`/messages/update/${messageId}`, { contenu });
        },

        // Supprime un message
        delete(messageId) {
            return post(`/messages/delete/${messageId}`);
        },
    };
})();