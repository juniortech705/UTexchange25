const MessagesUI = (() => {

    let lastId       = window.LAST_MSG_ID || 0;
    let pollingTimer = null;
    let editingId    = null; // id du message en cours d'édition
    let isEditing = false;

    const feed     = () => document.getElementById('messages-feed');
    const input    = () => document.getElementById('message-input');
    const sendBtn  = () => document.getElementById('send-btn');

    function init() {
        scrollToBottom(false);
        sendBtn()?.addEventListener('click', handleSend);
        // Entrée = envoyer, Shift+Entrée = saut de ligne
        input()?.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                handleSend();
            }
        });

        syncMessages().then(() => {
            startPolling();
        });

        // Marque comme lus dès que la page est visible
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) MessagesService.markRead(window.CONV_ID);
        });

        setInterval(syncMessages, 10000);
    }

    //Envoi d'un message
    function handleSend() {
        const el = input();
        if (!el) return;
        const contenu = el.value.trim();
        if (!contenu) return;

        el.disabled = true;
        sendBtn().disabled = true;

        MessagesService.send(window.CONV_ID, contenu)
            .then(data => {
                if (data.success) {
                    el.value = '';
                    el.style.height = 'auto';
                    appendMessage(data.message);
                    lastId = Math.max(lastId, data.message.id);
                    scrollToBottom(true);
                }
            })
            .finally(() => {
                el.disabled = false;
                sendBtn().disabled = false;
                el.focus();
            });
    }

    //Polling
    function startPolling() {
        pollingTimer = setInterval(() => {

            MessagesService.getNew(window.CONV_ID, lastId)
                .then(data => {
                    if (!data.success || !data.messages.length) return;

                    data.messages.forEach(msg => {
                        if (!document.getElementById(`msg-${msg.id}`)) {
                            appendMessage(msg);
                        }
                    });

                    const maxId = Math.max(...data.messages.map(m => m.id));
                    lastId = Math.max(lastId, maxId);

                    scrollToBottom(true);
                    MessagesService.markRead(window.CONV_ID);
                })
                .catch(console.error);

        }, 3000);
    }

    function stopPolling() {
        clearInterval(pollingTimer);
    }

    //Rendu d'une bulle de message
    function appendMessage(msg) {
        const isMine = msg.expediteur_id == window.USER_ID;
        const div    = document.createElement('div');

        div.id        = `msg-${msg.id}`;
        div.className = `msg-bubble ${isMine ? 'msg-mine' : 'msg-theirs'}`;
        div.dataset.original = msg.contenu;
        div.innerHTML = buildBubble(msg, isMine);

        feed().appendChild(div);
    }

    function buildBubble(msg, isMine) {
        const time    = formatTime(msg.created_at);
        const actions = isMine ? `
            <div class="msg-actions">
                <button onclick="MessagesUI.startEdit(${msg.id})" title="Modifier">
                    <i class="fa-solid fa-pen" style="font-size:10px;"></i>
                </button>
                <button onclick="MessagesUI.deleteMsg(${msg.id})" title="Supprimer">
                    <i class="fa-solid fa-trash" style="font-size:10px;"></i>
                </button>
            </div>` : '';

        return `
            <div class="msg-inner">
                ${actions}
                <div class="msg-text" id="msg-text-${msg.id}">
                    ${escapeHtml(msg.contenu)}
                </div>
                <div class="msg-meta">
                    <span class="msg-time">${time}</span>
                    ${isMine ? `<span class="msg-read-receipt msg-read-receipt--${msg.is_read ? 'read' : 'sent'}">
                        <i class="fa-solid ${msg.is_read ? 'fa-check-double' : 'fa-check'}"></i>
                    </span>` : ''}
                </div>
            </div>`;
    }

    //Édition inline du message
    function startEdit(msgId) {
        if (window.IS_TERMINATED) return;
        isEditing = true;

        if (editingId) cancelEdit(editingId);
        editingId = msgId;

        const textEl = document.getElementById(`msg-text-${msgId}`);
        if (!textEl) return;

        const original = document.getElementById(`msg-${msgId}`)?.dataset.original ?? '';

        textEl.innerHTML = `
            <textarea id="edit-input-${msgId}"
                      style="width:100%;border:1.5px solid #0056b3;border-radius:8px;
                             padding:6px 10px;font-size:13px;resize:none;outline:none;
                             font-family:inherit;background:#fff;color:#111;"
                      rows="2">${escapeHtml(original)}</textarea>
            <div style="display:flex;gap:6px;margin-top:5px;justify-content:flex-end;">
                <button onclick="MessagesUI.cancelEdit(${msgId})"
                        style="font-size:11px;padding:4px 10px;border-radius:6px;
                               border:1px solid #e5e7eb;background:#fff;cursor:pointer;
                               font-family:inherit;color:#6b7280;">
                    Annuler
                </button>
                <button onclick="MessagesUI.confirmEdit(${msgId})"
                        style="font-size:11px;padding:4px 10px;border-radius:6px;
                               background:#0056b3;color:white;border:none;cursor:pointer;
                               font-weight:600;font-family:inherit;">
                    Enregistrer
                </button>
            </div>`;

        document.getElementById(`edit-input-${msgId}`)?.focus();
    }

    function cancelEdit(msgId) {
        editingId = null;
        const bubble  = document.getElementById(`msg-${msgId}`);
        const textEl  = document.getElementById(`msg-text-${msgId}`);
        const original = bubble?.dataset.original ?? '';
        if (textEl) textEl.innerHTML = escapeHtml(original);

        isEditing = false;
        setTimeout(syncMessages, 10);
    }

    function confirmEdit(msgId) {

        const ta = document.getElementById(`edit-input-${msgId}`);
        if (!ta) return;
        const contenu = ta.value.trim();
        if (!contenu) return;

        MessagesService.update(msgId, contenu)
            .then(data => {
                if (!data.success) return;

                const bubble = document.getElementById(`msg-${msgId}`);
                const textEl = document.getElementById(`msg-text-${msgId}`);

                if (bubble) bubble.dataset.original = data.message.contenu;
                if (textEl) textEl.textContent = data.message.contenu;

                // Indicateur "modifié" sous le message
                const timeEl = document.querySelector(`#msg-${msgId} .msg-time`);
                if (timeEl && !timeEl.querySelector('.edited-tag')) {
                    timeEl.insertAdjacentHTML('beforeend',
                        ' <span class="edited-tag" style="font-size:10px;opacity:.6;">(modifié)</span>');
                }

                editingId = null;
                isEditing = false;
                setTimeout(syncMessages, 10);
            });
    }

    //Suppression
    function deleteMsg(msgId) {
        if (window.IS_TERMINATED) return;
        if (!confirm('Supprimer ce message ?')) return;

        MessagesService.delete(msgId)
            .then(data => {
                if (!data.success) return;

                const el = document.getElementById(`msg-${msgId}`);

                if (el) el.remove();

                recalculateLastId();

                // force sync pour autre user
                syncMessages();
            });
    }

    //Scroll
    function scrollToBottom(smooth) {
        const f = feed();
        if (!f) return;
        f.scrollTo({ top: f.scrollHeight, behavior: smooth ? 'smooth' : 'auto' });
    }

    //Helpers
    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function formatTime(datetime) {
        if (!datetime) return '';
        const d = new Date(datetime.replace(' ', 'T'));
        return d.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    }

    function recalculateLastId() {
        const messages = document.querySelectorAll('[id^="msg-"]');

        if (!messages.length) {
            lastId = 0;
            return;
        }

        let maxId = 0;

        messages.forEach(el => {
            const id = parseInt(el.id.replace('msg-', ''));
            if (id > maxId) maxId = id;
        });

        lastId = maxId;

        console.log("Nouveau lastId après suppression:", lastId);
    }
    function syncMessages() {
        if (isEditing) return;
        return fetch(`/conversations/${window.CONV_ID}/messages/sync`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(r => r.json())
            .then(data => {
                if (!data.success || !Array.isArray(data.messages)) return;
                if (data.status === 'terminee') {
                    disableChatUI();
                }

                const serverIds = new Set(data.messages.map(m => m.id));

                // 1. suppression SAFE
                document.querySelectorAll('[id^="msg-"]').forEach(el => {
                    const id = parseInt(el.id.replace('msg-', ''), 10);

                    if (!serverIds.has(id)) {
                        el.remove();
                    }
                });

                // 2. reconstruction SAFE (important)
                data.messages.forEach(msg => {

                    let el = document.getElementById(`msg-${msg.id}`);
                    if (!el) {
                        appendMessage(msg);
                        return;
                    }

                    const textEl = el.querySelector('.msg-text');
                    if (!textEl) {
                        // 🔥 réparation DOM cassé
                        el.innerHTML = buildBubble(msg, msg.expediteur_id == window.USER_ID);
                        el.dataset.original = msg.contenu;
                        return;
                    }

                    if (el.dataset.original !== msg.contenu) {
                        el.dataset.original = msg.contenu;
                        textEl.textContent = msg.contenu;
                    }
                });

                // 3. lastId fiable
                const ids = data.messages.map(m => m.id);
                lastId = ids.length ? Math.max(...ids) : 0;
            })
            .catch(err => console.error("Erreur sync:", err));
    }
    function disableChatUI() {
        const input = document.getElementById('message-input');
        const btn   = document.getElementById('send-btn');
        const header = document.querySelector('.chat-header');

        if (input) input.disabled = true;
        if (btn) btn.disabled = true;

        // éviter doublon
        if (document.querySelector('.terminated-banner')) return;

        const banner = document.createElement('div');
        banner.className = 'terminated-banner';
        banner.innerHTML = `
        <i class="fa-solid fa-lock" style="margin-right:5px;"></i>
        Cette conversation est terminée — aucun nouveau message ne peut être envoyé.
         `;

        header.appendChild(banner);
    }

    //API publique (appelée depuis les boutons inline)
    return { init, startEdit, cancelEdit, confirmEdit, deleteMsg, stopPolling };
})();

document.addEventListener('DOMContentLoaded', () => MessagesUI.init());