// Empêcher les envois multiples si on clique trop vite
let isSending = false;

window.sendMessage = async function() {
    if (isSending) return;

    const input = document.getElementById("chat-input");
    const container = document.getElementById("messages");

    if (!input || !container) return;

    const message = input.value.trim();
    if (!message) return;

    isSending = true; // Bloquer le bouton pendant l'envoi

    // --- AFFICHER TON MESSAGE ---
    container.innerHTML += `
        <div class="user-msg" style="align-self: flex-end; background-color: #2D8CFF; color: white; padding: 10px 15px; border-radius: 15px 15px 2px 15px; margin-bottom: 10px; max-width: 80%; word-wrap: break-word;">
            ${message}
        </div>
    `;

    input.value = "";
    container.scrollTop = container.scrollHeight;

    try {
        const response = await fetch("http://127.0.0.1:5000/chatbot", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message: message })
        });

        const data = await response.json();

        // --- AFFICHER LA RÉPONSE DE MICHELE ---
        container.innerHTML += `
            <div class="bot-msg" style="align-self: flex-start; background-color: #333; color: white; padding: 10px 15px; border-radius: 15px 15px 15px 2px; margin-bottom: 10px; max-width: 80%; word-wrap: break-word;">
                ${data.response}
            </div>
        `;
        
    } catch (error) {
        console.error("Erreur d'affichage:", error);
    } finally {
        isSending = false; // Libérer le bouton
        container.scrollTop = container.scrollHeight;
    }
};