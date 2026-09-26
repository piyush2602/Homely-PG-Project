<?php
require_once __DIR__ . '/includes/admin_header.php';
require_once __DIR__ . '/../includes/mongodb_connect.php';

$users_count = $db->users->countDocuments(['role' => ['$ne' => 'admin']]);
$properties_count = $db->properties->countDocuments();
$cities_count = $db->cities->countDocuments();
$messages_count = $db->messages->countDocuments();
?>

<div class="row mb-4">
    <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-lg-0">
        <div class="admin-card">
            <div class="card-stat-val text-primary"><?= $users_count ?></div>
            <div class="card-stat-lbl"><i class="fas fa-users mr-1"></i> Registered Users</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-lg-0">
        <div class="admin-card">
            <div class="card-stat-val text-success"><?= $properties_count ?></div>
            <div class="card-stat-lbl"><i class="fas fa-hotel mr-1"></i> Listed PGs / Hotels</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-lg-0">
        <div class="admin-card">
            <div class="card-stat-val text-info"><?= $cities_count ?></div>
            <div class="card-stat-lbl"><i class="fas fa-city mr-1"></i> Cities Covered</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-lg-0">
        <div class="admin-card">
            <div class="card-stat-val text-warning"><?= $messages_count ?></div>
            <div class="card-stat-lbl"><i class="fas fa-comments mr-1"></i> Support Messages</div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-lg-8 mb-4">
        <div class="admin-card">
            <h4 class="mb-3 font-weight-bold">Quick Actions</h4>
            <div class="admin-btn-group">
                <a href="property_add.php" class="btn-admin-primary">
                    <i class="fas fa-plus-circle"></i> Add New PG / Hotel
                </a>
                <a href="users.php" class="btn btn-outline-primary px-4 py-2" style="border-radius: 10px;">
                    <i class="fas fa-users mr-1"></i> View All Users
                </a>
                <?php
                $total_unseen = $db->messages->countDocuments(['sender' => 'user', 'is_seen' => false]);
                ?>
                <a href="chat.php" class="btn <?= $total_unseen > 0 ? 'btn-danger font-weight-bold' : 'btn-outline-info' ?> px-4 py-2" style="border-radius: 10px;">
                    <i class="fas fa-comments mr-1"></i> Open Live Support Chat
                    <?php if ($total_unseen > 0): ?>
                        <span class="badge badge-light text-danger font-weight-bold ml-1">+<?= $total_unseen ?></span>
                    <?php endif; ?>
                </a>
            </div>

        </div>
    </div>

    <div class="col-md-4">
        <div class="admin-card">
            <h4 class="mb-3 font-weight-bold text-dark">System Status</h4>
            <div class="small font-weight-bold mb-2" style="color: #334155; font-size: 0.95rem;"><i class="fas fa-database text-success mr-2"></i> MongoDB Atlas Connected</div>
            <div class="small font-weight-bold mb-2" style="color: #334155; font-size: 0.95rem;"><i class="fas fa-server text-success mr-2"></i> PHP Web Server Running</div>
            <div class="small font-weight-bold" style="color: #334155; font-size: 0.95rem;"><i class="fas fa-shield-alt text-info mr-2"></i> Admin Guard Active</div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
</main>
</div>
</body>
</html>
