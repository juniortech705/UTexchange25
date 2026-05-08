<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversation — UTexCHANGE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="stylesheet" href="/frontend/css/modals.css">
    <link rel="icon" type="image/png" href="/Images/favicon_utexchange.png">
    <style>
        body { font-family: 'Poppins', sans-serif; }

        /* Layout chat */
        .chat-wrap {
            display: flex; flex-direction: column;
            height: calc(100vh - 130px);
            background: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,.04);
        }

        /* Header conversation */
        .chat-header {
            padding: 14px 18px;
            border-bottom: 1px solid #f3f4f6;
            display: flex; align-items: center; gap: 12px;
            flex-shrink: 0;
        }
        .chat-header__avatar {
            width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, #0056b3, #004a99);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: white;
        }
        .chat-header__name { font-size: 14px; font-weight: 700; color: #111; }
        .chat-header__sub  { font-size: 11px; color: #9ca3af; }

        /* Feed messages */
        #messages-feed {
            flex: 1; overflow-y: auto;
            padding: 20px 18px; display: flex;
            flex-direction: column; gap: 10px;
        }
        #messages-feed::-webkit-scrollbar { width: 4px; }
        #messages-feed::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }

        /* Bulles */
        .msg-bubble { display: flex; }
        .msg-mine   { justify-content: flex-end; }
        .msg-theirs { justify-content: flex-start; }

        .msg-inner {
            max-width: 68%; display: flex;
            flex-direction: column; position: relative;
        }
        .msg-mine .msg-inner { align-items: flex-end; }
        .msg-theirs .msg-inner { align-items: flex-start; }

        .msg-text {
            padding: 10px 14px; border-radius: 14px;
            font-size: 13.5px; line-height: 1.55; word-break: break-word;
        }
        .msg-mine .msg-text {
            background: #0056b3; color: white;
            border-bottom-right-radius: 4px;
        }
        .msg-theirs .msg-text {
            background: #f3f4f6; color: #111;
            border-bottom-left-radius: 4px;
        }

        .msg-time { font-size: 10px; color: #9ca3af; margin-top: 3px; padding: 0 2px; }

        /* Actions sur message (visible au hover) */
        .msg-actions {
            display: none; gap: 4px; margin-bottom: 4px;
        }
        .msg-bubble:hover .msg-actions { display: flex; }
        .msg-actions button {
            width: 24px; height: 24px; border-radius: 6px; border: none;
            background: #f3f4f6; color: #9ca3af; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: background .15s, color .15s;
        }
        .msg-actions button:hover { background: #e5e7eb; color: #374151; }

        /* Zone de saisie */
        .chat-input-bar {
            border-top: 1px solid #f3f4f6;
            padding: 12px 16px;
            display: flex; align-items: flex-end; gap: 10px;
            flex-shrink: 0;
        }
        #message-input {
            flex: 1; resize: none; border: 1.5px solid #e5e7eb;
            border-radius: 12px; padding: 10px 14px;
            font-size: 13.5px; font-family: 'Poppins', sans-serif;
            outline: none; max-height: 120px; min-height: 42px;
            transition: border-color .2s;
            line-height: 1.5;
        }
        #message-input:focus { border-color: #0056b3; }

        #send-btn {
            width: 42px; height: 42px; border-radius: 12px; flex-shrink: 0;
            background: #0056b3; color: white; border: none;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: background .15s, transform .1s;
        }
        #send-btn:hover { background: #004a99; }
        #send-btn:active { transform: scale(.95); }
        #send-btn:disabled { background: #9ca3af; cursor: not-allowed; }

        /* Bandeau conversation terminée */
        .terminated-banner {
            background: #fef9c3; border-top: 1px solid #fde68a;
            padding: 10px 18px; font-size: 12px; color: #92400e;
            text-align: center; flex-shrink: 0;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/flash.php'; ?>
<?php include __DIR__ . '/../partials/modals.php'; ?>

<?php $isTerminee = $conversation->getStatus() === 'terminee'; ?>
<?php $isAcheteur = $userId == $conversation->getAcheteurId(); ?>

<!-- Modale terminer conversation -->
<?php if (!$isTerminee): ?>
    <div class="modal-overlay" id="modalTerminate">
        <div class="modal-box" style="max-width:380px;">
            <button class="modal-close" onclick="closeModal('modalTerminate')">&times;</button>
            <p class="modal-title" style="font-size:1.1rem;">Terminer la conversation ?</p>
            <p class="modal-subtitle">La transaction sera marquée comme conclue. Vous pourrez ensuite laisser un avis.</p>
            <form method="POST" action="/conversations/terminate/<?= $conversation->getId() ?>">
                <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
                <button type="submit" class="modal-btn">Confirmer</button>
            </form>
        </div>
    </div>
<?php endif; ?>

<!-- Modale avis -->
<?php if ($isTerminee && $isAcheteur && !$conversation->getAvisLaisse()): ?>
    <div class="modal-overlay" id="modalAvis">
        <div class="modal-box" style="max-width:400px;">
            <button class="modal-close" onclick="closeModal('modalAvis')">&times;</button>
            <p class="modal-title" style="font-size:1.1rem;">Laisser un avis</p>
            <form method="POST" action="/conversations/<?= $conversation->getId() ?>/avis">
                <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
                <div class="modal-field">
                    <label>Note</label>
                    <div id="star-rating" style="display:flex;gap:6px;font-size:1.6rem;cursor:pointer;margin:4px 0;">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span data-val="<?= $i ?>" onclick="setNote(<?= $i ?>)"
                                  style="color:#e5e7eb;transition:color .15s;"
                                  onmouseover="hoverNote(<?= $i ?>)" onmouseout="resetNote()">★</span>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="note" id="note-input" value="0">
                </div>
                <div class="modal-field">
                    <label>Commentaire (optionnel)</label>
                    <textarea name="commentaire" rows="3" placeholder="Décrivez votre expérience…"></textarea>
                </div>
                <button type="submit" class="modal-btn">Envoyer l'avis</button>
            </form>
        </div>
    </div>
<?php endif; ?>

<div class="modal-overlay" id="modalShowAvis">
    <div class="modal-box" style="max-width:400px;">
        <button class="modal-close" onclick="closeModal('modalShowAvis')">&times;</button>

        <p class="modal-title" style="font-size:1.1rem;">Avis laissé</p>

        <?php if ($avis): ?>
            <div style="margin-top:12px;">

                <!-- Séparateur -->
                <div style="border-top:1px solid #e5e7eb;margin-bottom:12px;"></div>

                <!-- NOTE -->
                <div class="modal-field" style="margin-bottom:14px;">
                    <label style="font-weight:600;color:#374151;">Note</label>

                    <div style="display:flex;align-items:center;gap:6px;margin-top:6px;">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span style="
                                    font-size:1.5rem;
                                    color: <?= $i <= $avis->getNote() ? '#facc15' : '#e5e7eb' ?>;
                                    ">★</span>
                        <?php endfor; ?>

                        <span style="font-size:13px;color:#6b7280;margin-left:6px;">
                    (<?= $avis->getNote() ?>/5)
                </span>
                    </div>
                </div>

                <!-- COMMENTAIRE -->
                <?php if ($avis->getCommentaire()): ?>
                    <div class="modal-field">
                        <label style="font-weight:600;color:#374151;">Commentaire</label>

                        <div style="
                    margin-top:6px;
                    padding:10px 12px;
                    background:#f9fafb;
                    border:1px solid #e5e7eb;
                    border-radius:8px;
                    font-size:13px;
                    color:#374151;
                    line-height:1.4;
                ">
                            <?= nl2br(htmlspecialchars($avis->getCommentaire())) ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        <?php else: ?>
            <div style="
        margin-top:12px;
        padding:10px;
        background:#f9fafb;
        border:1px dashed #e5e7eb;
        border-radius:8px;
        font-size:13px;
        color:#6b7280;
        text-align:center;
    ">
                Aucun avis disponible.
            </div>
        <?php endif; ?>
    </div>
</div>

<main class="flex-1" style="max-width:760px;margin:0 auto;width:100%;padding:20px 20px 28px;">

    <!-- Retour -->
    <a href="/conversations"
       style="display:inline-flex;align-items:center;gap:6px;font-size:12px;
              color:#9ca3af;text-decoration:none;margin-bottom:14px;transition:color .15s;"
       onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#9ca3af'">
        <i class="fa-solid fa-arrow-left" style="font-size:10px;"></i> Conversations
    </a>

    <div class="chat-wrap">

        <!-- Header -->
        <div class="chat-header">

            <div class="chat-header__avatar">
                <?= strtoupper(mb_substr($other->getPrenom() ?? '?', 0, 1) . mb_substr($other->getNom() ?? '', 0, 1)) ?>
            </div>
            <div style="flex:1;">
                <p class="chat-header__name">
                    <?= htmlspecialchars(($other->getPrenom() ?? '') . ' ' . ($other->getNom() ?? '')) ?>
                </p>
                <p class="chat-header__sub">
                    <?php
                    echo 'Re: ' . htmlspecialchars($annonce->getTitle());
                    ?>
                </p>
            </div>

            <!-- Actions header -->
            <div style="display:flex;gap:8px;">
                <?php if (!$isTerminee): ?>
                    <button onclick="openModal('modalTerminate')"
                            style="padding:6px 12px;border-radius:9px;border:1.5px solid #e5e7eb;
                               background:#fff;font-size:11px;font-weight:600;color:#6b7280;
                               cursor:pointer;transition:border-color .15s;"
                            onmouseover="this.style.borderColor='#0056b3';this.style.color='#0056b3'"
                            onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#6b7280'">
                        <i class="fa-solid fa-check" style="font-size:10px;margin-right:3px;"></i>
                        Terminer
                    </button>
                <?php endif; ?>

                <?php if ($isTerminee && $isAcheteur && !$conversation->getAvisLaisse()): ?>
                    <button onclick="openModal('modalAvis')"
                            style="padding:6px 12px;border-radius:9px;background:#0056b3;color:white;
                               border:none;font-size:11px;font-weight:600;cursor:pointer;">
                        <i class="fa-solid fa-star" style="font-size:10px;margin-right:3px;"></i>
                        Laisser un avis
                    </button>
                <?php endif; ?>

                <?php if ($isTerminee && $isAcheteur && $conversation->getAvisLaisse()): ?>
                    <form method="POST" action="/conversations/<?= $conversation->getId() ?>/avis/delete"
                          onsubmit="return confirm('Supprimer votre avis ?')">
                        <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
                        <button type="submit"
                                style="padding:6px 12px;border-radius:9px;border:1.5px solid #fee2e2;
                                   background:#fff;font-size:11px;font-weight:600;color:#dc2626;cursor:pointer;">
                            <i class="fa-solid fa-trash" style="font-size:10px;margin-right:3px;"></i>
                            Supprimer l'avis
                        </button>
                    </form>
                <?php endif; ?>

                <?php if ($isTerminee && !$isAcheteur): ?>
                    <button onclick="openModal('modalShowAvis')"
                            style="padding:6px 12px;border-radius:9px;border:1.5px solid #fee2e2;
                   background:#fff;font-size:11px;font-weight:600;color:#dc2626;cursor:pointer;">
                        <i class="fa-solid fa-eye" style="font-size:10px;margin-right:3px;"></i>
                        Voir l'avis
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bandeau si terminée -->
        <?php if ($isTerminee): ?>
            <div class="terminated-banner">
                <i class="fa-solid fa-lock" style="margin-right:5px;"></i>
                Cette conversation est terminée — aucun nouveau message ne peut être envoyé.
            </div>
        <?php endif; ?>

        <!-- Feed messages -->
        <div id="messages-feed">
            <?php foreach ($messages as $msg): ?>
                <?php if (!is_object($msg)) continue; ?>
                <?php $isMine = $msg->getExpediteurId() == $userId; ?>
                <div class="msg-bubble <?= $isMine ? 'msg-mine' : 'msg-theirs' ?>"
                     id="msg-<?= $msg->getId() ?>"
                     data-original="<?= htmlspecialchars($msg->getContenu()) ?>">
                    <div class="msg-inner">
                        <?php if ($isMine): ?>
                            <div class="msg-actions">
                                <button onclick="MessagesUI.startEdit(<?= $msg->getId() ?>)" title="Modifier">
                                    <i class="fa-solid fa-pen" style="font-size:10px;"></i>
                                </button>
                                <button onclick="MessagesUI.deleteMsg(<?= $msg->getId() ?>)" title="Supprimer">
                                    <i class="fa-solid fa-trash" style="font-size:10px;"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                        <div class="msg-text" id="msg-text-<?= $msg->getId() ?>">
                            <?= htmlspecialchars($msg->getContenu()) ?>
                        </div>
                        <span class="msg-time">
                        <?= date('H:i', strtotime($msg->getCreatedAt())) ?>
                    </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Zone saisie (masquée si conversation terminée) -->
        <?php if (!$isTerminee): ?>
            <div class="chat-input-bar">
                <textarea id="message-input" placeholder="Votre message…" rows="1"></textarea>
                <button id="send-btn">
                    <i class="fa-solid fa-paper-plane" style="font-size:14px;"></i>
                </button>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="/frontend/js/script.js"></script>
<script src="/frontend/Ajax/services/messages.js"></script>
<script src="/frontend/Ajax/services/conversation.js"></script>
<script>
    // Variables injectées pour messagesUI.js
    window.CONV_ID     = <?= $conversation->getId() ?>;
    window.USER_ID     = <?= $userId ?>;
    window.LAST_MSG_ID = <?= $lastMessageId ?>;
    window.IS_TERMINATED = <?= $isTerminee ? 'true' : 'false' ?>;

    // ── Notation étoiles ─────────────────────────────────
    let currentNote = 0;
    function setNote(n) {
        currentNote = n;
        document.getElementById('note-input').value = n;
        updateStars(n, true);
    }
    function hoverNote(n)  { updateStars(n, false); }
    function resetNote()   { updateStars(currentNote, true); }
    function updateStars(n, lock) {
        document.querySelectorAll('#star-rating span').forEach((s, i) => {
            s.style.color = i < n ? '#f59e0b' : '#e5e7eb';
        });
    }

    // Auto-resize textarea
    const ta = document.getElementById('message-input');
    if (ta) {
        ta.addEventListener('input', () => {
            ta.style.height = 'auto';
            ta.style.height = Math.min(ta.scrollHeight, 120) + 'px';
        });
    }
</script>
<script src="/frontend/Ajax/ui/messagesUI.js"></script>
</body>
</html>