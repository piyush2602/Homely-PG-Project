<?php
require_once __DIR__ . '/includes/admin_header.php';
require_once __DIR__ . '/../includes/mongodb_connect.php';

$users = $db->users->find(['role' => ['$ne' => 'admin']])->toArray();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center gap-3">
        <span class="badge badge-pill badge-primary px-3 py-2 font-weight-bold" style="font-size: 0.875rem;">
            <i class="fas fa-users mr-1"></i> Total: <?= count($users) ?> Registered Users
        </span>
    </div>
    <a href="user_add.php" class="btn-admin-primary">
        <i class="fas fa-user-plus"></i> Add New User
    </a>
</div>

<div class="admin-table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User Details</th>
                <th>Email / Phone</th>
                <th>ID Card Document</th>
                <th>Status</th>
                <th style="min-width: 220px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No registered users found. Click "Add New User" above to create one.</td>
                </tr>
            <?php else: ?>

                <?php foreach ($users as $u): ?>
                    <?php
                    $uid = (int)$u['id'];
                    $unseen_count = $db->messages->countDocuments(['user_id' => $uid, 'sender' => 'user', 'is_seen' => false]);
                    $profile_img = !empty($u['profile_image']) ? '../' . $u['profile_image'] : null;
                    $id_card_file = !empty($u['id_card_path']) ? '../' . $u['id_card_path'] : null;
                    $is_verified = !empty($u['is_verified']);
                    ?>
                    <tr id="user-row-<?= $uid ?>">
                        <td><strong>#<?= htmlspecialchars($u['id'] ?? '') ?></strong></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php if ($profile_img): ?>
                                    <img src="<?= htmlspecialchars($profile_img) ?>" class="rounded-circle mr-2 border shadow-sm" style="width: 38px; height: 38px; object-fit: cover;" />
                                <?php else: ?>
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mr-2 border" style="width: 38px; height: 38px;">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <span class="font-weight-bold text-dark d-block"><?= htmlspecialchars($u['full_name'] ?? '') ?></span>
                                    <small class="text-muted"><?= htmlspecialchars($u['college_name'] ?? 'N/A') ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div><?= htmlspecialchars($u['email'] ?? '') ?></div>
                            <small class="text-muted"><i class="fas fa-phone-alt mr-1"></i><?= htmlspecialchars($u['phone'] ?? '') ?></small>
                        </td>
                        <td>
                            <?php if ($id_card_file && file_exists(__DIR__ . '/../' . $u['id_card_path'])): ?>
                                <a href="<?= htmlspecialchars($id_card_file) ?>" target="_blank" download class="btn btn-sm btn-outline-success font-weight-bold" style="border-radius: 8px;" title="View or Download ID Card">
                                    <i class="fas fa-id-card mr-1"></i> View / Download ID
                                </a>
                            <?php else: ?>
                                <span class="badge badge-light text-muted border px-2 py-1">No ID Uploaded</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($is_verified): ?>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-success px-2 py-1 mr-2" style="font-size: 0.825rem;"><i class="fas fa-check-circle mr-1"></i> Verified</span>
                                    <button onclick="toggleVerification(<?= $uid ?>, 0)" class="btn btn-sm btn-outline-danger py-0 px-2" style="border-radius: 6px;" title="Mark as Unverified">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            <?php else: ?>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-danger px-2 py-1 mr-2" style="font-size: 0.825rem;"><i class="fas fa-times-circle mr-1"></i> Unverified</span>
                                    <button onclick="toggleVerification(<?= $uid ?>, 1)" class="btn btn-sm btn-outline-success py-0 px-2 font-weight-bold" style="border-radius: 6px;" title="Mark as Verified">
                                        <i class="fas fa-check"></i> Verify
                                    </button>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="admin-action-btn-group">
                                <a href="chat.php?user_id=<?= $uid ?>" class="btn btn-sm <?= $unseen_count > 0 ? 'btn-danger font-weight-bold' : 'btn-outline-info' ?>" style="border-radius: 8px; padding: 6px 12px;" title="Live Chat">
                                    <i class="fas fa-comments mr-1"></i> Chat
                                    <?php if ($unseen_count > 0): ?>
                                        <span class="badge badge-light text-danger font-weight-bold ml-1">+<?= $unseen_count ?></span>
                                    <?php endif; ?>
                                </a>
                                <a href="user_edit.php?id=<?= $uid ?>" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; padding: 6px 12px; font-weight: 600;" title="Edit User">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <button onclick="deleteUser(<?= $uid ?>)" class="btn btn-sm btn-outline-danger" style="border-radius: 8px; padding: 6px 12px; font-weight: 600;" title="Delete User">
                                    <i class="fas fa-trash mr-1"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    function toggleVerification(userId, status) {
        if (!userId) return;
        $.ajax({
            url: 'api/toggle_verification.php',
            type: 'POST',
            data: { user_id: userId, is_verified: status },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    location.reload();
                } else {
                    alert(res.message);
                }
            },
            error: function() {
                alert('Error updating verification status.');
            }
        });
    }

    function deleteUser(id) {
        if (confirm('Are you sure you want to delete this user account? All their messages will also be removed.')) {
            $.ajax({
                url: 'api/user_delete.php',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        $('#user-row-' + id).fadeOut(300, function() { $(this).remove(); });
                    } else {
                        alert(res.message);
                    }
                }
            });
        }
    }
</script>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
</main>
</div>
</body>
</html>
