<?php

?>
<div id="chat-widget-button" onclick="toggleChat()" style="position: fixed; bottom: 20px; right: 20px; width: 55px; height: 55px; background-color: #0066CC; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 12px rgba(0,102,204,0.3); z-index: 1000;">
    <svg width="26" height="26" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
    </svg>
</div>

<div id="chat-window">
    <div style="background-image: url('<?= 'Images/logo.png' ?>'); background-size: cover; background-position: center; padding: 12px 15px; border-bottom: 1px solid rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: space-between; position: relative;">

        <div style="display: flex; align-items: center; gap: 12px; z-index: 2;">
            <img src="<?= 'Images/favicon_utexchange.png' ?>" alt="UT Logo" style="width: 38px; height: 38px; border-radius: 50%; border: 2px solid white; object-fit: cover; background: white;">
            <div style="text-shadow: 0 1px 2px rgba(0,0,0,0.3);">
                <h4 style="margin: 0; font-size: 0.95em; color: white; font-weight: 600;">Michele</h4>
                <div style="display: flex; align-items: center; gap: 4px;">
                    <span style="width: 7px; height: 7px; background: #4CAF50; border-radius: 50%; border: 1px solid white;"></span>
                    <span style="font-size: 0.7em; color: white; opacity: 0.9;">Répond rapidement</span>
                </div>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 5px; z-index: 2;">
            <button onclick="clearChat()" title="Effacer l'historique" style="background: none; border: none; cursor: pointer; padding: 5px; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
            </button>
            <button onclick="toggleChat()" style="background: none; border: none; font-size: 24px; color: white; cursor: pointer; padding: 0 5px; line-height: 1;">&times;</button>
        </div>

        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.15); z-index: 1;"></div>
    </div>

    <div id="messages">
        <div class="msg-common msg-michele">Hello Je suis Michele, comment puis-je t'aider sur UTexchange ?</div>
    </div>

    <div style="padding: 15px; border-top: 1px solid #EEEEEE;">
        <div style="display: flex; align-items: center; background: #F4F4F7; border-radius: 20px; padding: 5px 15px;">
            <input type="text" id="chat-input" placeholder="Écrire à Michele..." style="flex: 1; border: none; background: transparent; padding: 8px 0; outline: none; font-size: 0.9em; color: #1A1A1A;">
            <button onclick="sendMessage()" style="background: none; border: none; color: #0066CC; font-weight: bold; cursor: pointer; padding: 5px;">Envoyer</button>
        </div>
    </div>
</div>

<script>
    function toggleChat() {
        const win = document.getElementById('chat-window');
        win.style.display = (win.style.display === 'none' || win.style.display === '') ? 'flex' : 'none';
    }

    async function sendMessage() {
        const input = document.getElementById("chat-input");
        const container = document.getElementById("messages");
        const msg = input.value.trim();
        if (!msg) return;

        container.innerHTML += `<div class="msg-common msg-user">${msg}</div>`;
        input.value = "";
        container.scrollTop = container.scrollHeight;

        try {
            const response = await fetch("http://127.0.0.1:5000/chat", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ message: msg })
            });
            const data = await response.json();
            const micheleDiv = document.createElement("div");
            micheleDiv.className = "msg-common msg-michele";
            micheleDiv.innerHTML = data.response;
            container.appendChild(micheleDiv);
        } catch (e) {
            container.innerHTML += `<div style="font-size:0.75em; color:#999; text-align:center; margin:10px 0;">Le serveur est indisponible.</div>`;
        }
        container.scrollTop = container.scrollHeight;
    }

    document.getElementById("chat-input").addEventListener("keypress", (e) => {
        if (e.key === "Enter") sendMessage();
    });

    function clearChat() {
        if (confirm("Voulez-vous supprimer l'historique de cette discussion ?")) {
            const container = document.getElementById("messages");
            container.innerHTML = "";
            const welcomeDiv = document.createElement("div");
            welcomeDiv.className = "msg-common msg-michele";
            welcomeDiv.textContent = "Hello ! C'est encore Michele. On repart de zéro, comment puis-je t'aider ?";
            container.appendChild(welcomeDiv);
            document.getElementById("chat-input").value = "";
        }
    }
</script>