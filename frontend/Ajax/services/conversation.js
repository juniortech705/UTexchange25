const ConversationsService = (() => {

    return {
        // Nombre total de messages non lus (badge navbar)
        unreadCount() {
            return fetch('/messages/unread-count', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(r => r.json());
        },
    };
})();