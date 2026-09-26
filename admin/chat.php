<?php
require_once __DIR__ . '/includes/admin_header.php';
require_once __DIR__ . '/../includes/mongodb_connect.php';

// Fetch all registered users
$all_users = $db->users->find(['role' => ['$ne' => 'admin']])->toArray();
$users_by_id = [];
foreach ($all_users as $u) {
    $users_by_id[$u['id']] = $u;
}

// Find unique user_ids from messages
$unique_user_ids = $db->messages->distinct('user_id');

// Default selected user_id
$selected_user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : (!empty($unique_user_ids) ? (int)$unique_user_ids[0] : 1);

// Fetch messages for selected user & mark user messages as seen
$messages = [];
if ($selected_user_id > 0) {
    $db->messages->updateMany(
        ['user_id' => $selected_user_id, 'sender' => 'user', 'is_seen' => false],
        ['$set' => ['is_seen' => true]]
    );
    $messages = $db->messages->find(['user_id' => $selected_user_id], ['sort' => ['_id' => 1]])->toArray();
}
?>

<div class="admin-chat-layout">
    <!-- User List Panel -->
    <div class="chat-user-list">
        <div class="p-3 border-bottom font-weight-bold small text-muted text-uppercase" style="border-color: #e0f2fe !important;">
            <i class="fas fa-users mr-1"></i> User Chat Threads
        </div>
        <?php if (empty($all_users)): ?>
            <div class="p-3 text-muted small">No users found.</div>
        <?php else: ?>
            <?php foreach ($all_users as $u): ?>
                <?php 
                $uid = (int)$u['id']; 
                $isSelected = ($uid === $selected_user_id);
                $unseen_count = $db->messages->countDocuments(['user_id' => $uid, 'sender' => 'user', 'is_seen' => false]);
                ?>
                <a href="chat.php?user_id=<?= $uid ?>" class="chat-user-item <?= $isSelected ? 'active' : '' ?>">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="chat-user-name"><?= htmlspecialchars($u['full_name'] ?? 'User #'.$uid) ?></span>
                        <?php if ($unseen_count > 0): ?>
                            <span class="badge badge-danger font-weight-bold">+<?= $unseen_count ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="chat-user-email"><?= htmlspecialchars($u['email'] ?? '') ?></div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Active User Chat Window -->
    <div class="chat-window">
        <?php 
        $active_user = $users_by_id[$selected_user_id] ?? null; 
        ?>
        <div class="chat-header">
            <div>
                <span class="chat-header-name">
                    <i class="fas fa-user-circle text-primary mr-2"></i> 
                    <?= htmlspecialchars($active_user['full_name'] ?? 'User #'.$selected_user_id) ?>
                </span>
                <span class="chat-header-email ml-2">(<?= htmlspecialchars($active_user['email'] ?? '') ?>)</span>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge badge-success mr-2">Active Thread</span>
                <button type="button" onclick="clearUserChat(<?= $selected_user_id ?>)" class="btn btn-sm btn-outline-danger font-weight-bold" style="border-radius: 8px;" title="Clear Chat History from Database">
                    <i class="fas fa-trash-alt mr-1"></i> Clear Chat
                </button>
            </div>
        </div>

        <div class="chat-messages-area" id="admin-chat-feed">
            <?php if (empty($messages)): ?>
                <div class="text-center text-muted font-weight-bold my-auto">No messages exchanged with this user yet.</div>
            <?php else: ?>
                <?php foreach ($messages as $m): ?>
                    <?php 
                    $sender = $m['sender'] ?? 'user'; 
                    $text = $m['text'] ?? '';
                    $img = $m['image_path'] ?? null;
                    $time = $m['created_at'] ?? '';
                    ?>
                    <div class="msg-bubble <?= $sender === 'admin' ? 'msg-admin' : 'msg-user' ?>">
                        <div class="small msg-meta font-weight-bold mb-1">
                            <?= $sender === 'admin' ? '👑 Admin (You)' : '👤 ' . htmlspecialchars($active_user['full_name'] ?? 'User') ?> • <?= htmlspecialchars($time) ?>
                        </div>
                        <div><?= htmlspecialchars($text) ?></div>
                        <?php if ($img): ?>
                            <img src="../<?= htmlspecialchars($img) ?>" class="mt-2 rounded" style="max-width: 200px; max-height: 200px;" />
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <form id="admin-reply-form" class="chat-input-bar">
            <input type="hidden" id="target-user-id" value="<?= $selected_user_id ?>" />
            <input type="text" id="admin-msg-text" placeholder="Type message to reply to <?= htmlspecialchars($active_user['full_name'] ?? 'user') ?>..." required autocomplete="off" />
            <button type="submit" class="btn-admin-primary">
                <i class="fas fa-paper-plane"></i> Send Reply
            </button>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    function scrollToBottom() {
        const feed = document.getElementById('admin-chat-feed');
        if (feed) feed.scrollTop = feed.scrollHeight;
    }
    scrollToBottom();

    function clearUserChat(userId) {
        if (!userId) return;
        if (confirm('Are you sure you want to delete all old chat messages for this user from the database?')) {
            $.ajax({
                url: 'api/clear_chat.php',
                type: 'POST',
                data: { user_id: userId },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        location.reload();
                    } else {
                        alert(res.message);
                    }
                },
                error: function() {
                    alert('Failed to clear chat history. Please try again.');
                }
            });
        }
    }

    $('#admin-reply-form').on('submit', function(e) {
        e.preventDefault();
        const text = $('#admin-msg-text').val().trim();
        const userId = $('#target-user-id').val();
        if (!text || !userId) return;

        $.ajax({
            url: 'api/send_admin_reply.php',
            type: 'POST',
            data: { user_id: userId, text: text },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    $('#admin-msg-text').val('');
                    location.reload();
                } else {
                    alert(res.message);
                }
            }
        });
    });
</script>

</main>
</div>
</body>
</html>
