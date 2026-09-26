<?php
// includes/chat_widget.php
if (empty($_SESSION['user_id'])) return;
?>

<!-- Floating Chat Trigger Button -->
<div id="floatingChatBtn" class="floating-chat-btn" onclick="toggleChatWidget()" title="Live Support Chat">
    <i class="fas fa-comments"></i>
    <span id="chatUnseenBadge" class="unseen-badge badge badge-danger" style="display: none;"></span>
</div>

<!-- Pop-up Chatbot Drawer Container -->
<div id="popupChatWidget" class="popup-chat-widget">
    <div class="popup-chat-header">
        <div class="d-flex align-items-center" style="gap: 10px;">
            <div class="chat-header-avatar">
                <i class="fas fa-headset"></i>
            </div>
            <div>
                <div class="chat-header-title">Homely Live Support</div>
                <div class="chat-header-status">
                    <span class="status-dot"></span> Online • Admin Support
                </div>
            </div>
        </div>
        <button type="button" class="chat-close-btn" onclick="toggleChatWidget()" title="Close Chat">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="popup-chat-messages" id="popupMessagesFeed">
        <div class="text-center text-muted small my-auto">Loading messages...</div>
    </div>

    <div id="popupFilePreview" class="popup-file-preview"></div>

    <form id="popupChatForm" class="popup-chat-composer" enctype="multipart/form-data">
        <input type="text" id="popupInputMsg" name="text" placeholder="Type a message..." autocomplete="off" />
        <label for="popupFileInput" class="popup-file-label" title="Attach file or photo">
            <i class="fas fa-paperclip"></i>
        </label>
        <input type="file" id="popupFileInput" name="image" accept="image/*,.pdf" style="display: none;">
        <button type="submit" class="popup-send-btn" title="Send message">
            <i class="fas fa-paper-plane"></i>
        </button>
    </form>
</div>

<script>
    function escapeHtml(s) {
        return (s || '').toString().replace(/[&<>"']/g, function(c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": "&#39;" }[c];
        });
    }

    function toggleChatWidget() {
        const widget = document.getElementById('popupChatWidget');
        if (!widget) return;
        widget.classList.toggle('active');
        if (widget.classList.contains('active')) {
            fetchPopupMessages();
            const input = document.getElementById('popupInputMsg');
            if (input) input.focus();
        }
    }

    async function fetchPopupMessages() {
        try {
            const fd = new FormData();
            fd.append('action', 'fetch_messages');
            const res = await fetch('chat.php', { method: 'POST', body: fd });
            const data = await res.json();
            if (data.messages) {
                renderPopupMessages(data.messages);
            }
        } catch (e) {
            console.error('Fetch messages error:', e);
        }
    }

    function renderPopupMessages(messages) {
        const feed = document.getElementById('popupMessagesFeed');
        if (!feed) return;
        feed.innerHTML = '';
        if (!messages || messages.length === 0) {
            feed.innerHTML = '<div class="text-center text-muted small my-auto" style="padding: 20px; background: rgba(255,255,255,0.7); border-radius: 12px;">👋 Hello! Send a message below to start chatting with our Homely PG admin support team.</div>';
            return;
        }
        messages.forEach(m => {
            const isUser = (m.sender === 'user');
            const bubble = document.createElement('div');
            bubble.className = 'popup-msg-bubble ' + (isUser ? 'popup-msg-user' : 'popup-msg-admin');
            
            let html = '';
            if (m.text) {
                html += '<div>' + escapeHtml(m.text) + '</div>';
            }
            if (m.image_path) {
                const lower = m.image_path.toLowerCase();
                if (lower.endsWith('.pdf')) {
                    html += '<iframe src="' + escapeHtml(m.image_path) + '" width="200" height="120" style="border:0;border-radius:6px;margin-top:6px;"></iframe>';
                } else {
                    html += '<img src="' + escapeHtml(m.image_path) + '" style="max-width: 200px; max-height: 200px; border-radius: 8px; margin-top: 6px; display: block;" />';
                }
            }
            html += '<div class="popup-msg-time">' + (isUser ? 'You' : '👑 Admin') + ' • ' + escapeHtml(m.created_at || '') + '</div>';
            bubble.innerHTML = html;
            feed.appendChild(bubble);
        });
        feed.scrollTop = feed.scrollHeight;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const popupFileInput = document.getElementById('popupFileInput');
        const popupFilePreview = document.getElementById('popupFilePreview');
        const popupChatForm = document.getElementById('popupChatForm');

        if (popupFileInput) {
            popupFileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) {
                    popupFilePreview.style.display = 'none';
                    popupFilePreview.innerHTML = '';
                    return;
                }
                popupFilePreview.style.display = 'block';
                popupFilePreview.innerHTML = `
                    <div class="d-flex align-items-center justify-content-between p-2 rounded bg-white border">
                        <span class="small text-truncate font-weight-bold" style="max-width: 220px;">📎 ${escapeHtml(file.name)}</span>
                        <button type="button" class="btn btn-sm text-danger p-0 ml-2" onclick="document.getElementById('popupFileInput').value=''; document.getElementById('popupFilePreview').style.display='none';">&times;</button>
                    </div>
                `;
            });
        }

        if (popupChatForm) {
            popupChatForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const textInput = document.getElementById('popupInputMsg');
                const text = textInput.value.trim();
                const file = popupFileInput.files[0];

                if (!text && !file) return;

                const fd = new FormData(popupChatForm);
                fd.append('action', 'send_message');

                try {
                    const res = await fetch('chat.php', { method: 'POST', body: fd });
                    const data = await res.json();
                    if (data.status === 'ok') {
                        textInput.value = '';
                        popupFileInput.value = '';
                        popupFilePreview.style.display = 'none';
                        popupFilePreview.innerHTML = '';
                        fetchPopupMessages();
                    } else {
                        alert(data.message || 'Failed to send message');
                    }
                } catch (e) {
                    console.error(e);
                }
            });
        }

        // Poll for new messages every 3 seconds
        setInterval(function() {
            const widget = document.getElementById('popupChatWidget');
            if (widget && widget.classList.contains('active')) {
                fetchPopupMessages();
            }
        }, 3000);
    });
</script>
