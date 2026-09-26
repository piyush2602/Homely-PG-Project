<?php
require_once __DIR__ . '/includes/admin_header.php';
require_once __DIR__ . '/../includes/mongodb_connect.php';

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user = $db->users->findOne(['id' => $user_id, 'role' => ['$ne' => 'admin']]);

if (!$user) {
    echo '<div class="alert alert-danger">User not found. <a href="users.php">Back to users list</a></div>';
    echo '</main></div></body></html>';
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name    = trim($_POST['full_name'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $phone        = trim($_POST['phone'] ?? '');
    $password     = trim($_POST['password'] ?? '');
    $gender       = trim($_POST['gender'] ?? 'male');
    $college_name = trim($_POST['college_name'] ?? '');

    if (empty($full_name) || empty($email) || empty($phone)) {
        $error = 'Full name, email, and phone are required.';
    } else {
        // Check if email belongs to another user
        $email_conflict = $db->users->findOne([
            'email' => $email,
            'id' => ['$ne' => $user_id]
        ]);

        if ($email_conflict) {
            $error = 'Another user is already using this email address.';
        } else {
            $update_data = [
                'full_name'    => $full_name,
                'email'        => $email,
                'phone'        => $phone,
                'gender'       => strtolower($gender),
                'college_name' => $college_name
            ];

            // If password is updated
            if (!empty($password)) {
                $update_data['password'] = sha1($password);
            }

            // Process optional profile image update
            if (!empty($_FILES['profile_image']['name'])) {
                $target_dir = __DIR__ . '/../uploads/profile/';
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
                $file_name = time() . '_' . rand(1000, 9999) . '.' . $ext;
                $target_file = $target_dir . $file_name;
                
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                    $update_data['profile_image'] = 'uploads/profile/' . $file_name;
                }
            }

            $db->users->updateOne(['id' => $user_id], ['$set' => $update_data]);
            $success = 'User details updated successfully!';
            
            // Refresh user document
            $user = $db->users->findOne(['id' => $user_id]);
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="font-weight-bold mb-0 text-dark">Edit User Account #<?= $user['id'] ?></h3>
    <a href="users.php" class="btn btn-outline-secondary px-3 py-2" style="border-radius: 10px;">
        <i class="fas fa-arrow-left mr-1"></i> Back to Users List
    </a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger rounded-lg mb-4"><i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success rounded-lg mb-4"><i class="fas fa-check-circle mr-2"></i><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="admin-card max-w-700">
    <form method="POST" action="user_edit.php?id=<?= $user['id'] ?>" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group col-md-6 mb-3">
                <label class="font-weight-bold text-dark">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" required />
            </div>
            <div class="form-group col-md-6 mb-3">
                <label class="font-weight-bold text-dark">Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required />
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6 mb-3">
                <label class="font-weight-bold text-dark">Phone Number <span class="text-danger">*</span></label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required />
            </div>
            <div class="form-group col-md-6 mb-3">
                <label class="font-weight-bold text-dark">Password <span class="text-muted font-weight-normal">(Leave blank to keep unchanged)</span></label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" />
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6 mb-3">
                <label class="font-weight-bold text-dark">Gender</label>
                <select name="gender" class="form-control">
                    <option value="male" <?= ($user['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
                    <option value="female" <?= ($user['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
                    <option value="other" <?= ($user['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            <div class="form-group col-md-6 mb-3">
                <label class="font-weight-bold text-dark">College / Institute Name</label>
                <input type="text" name="college_name" class="form-control" value="<?= htmlspecialchars($user['college_name'] ?? '') ?>" />
            </div>
        </div>

        <div class="form-group mb-4">
            <label class="font-weight-bold text-dark">Profile Image <span class="text-muted font-weight-normal">(Optional upload to replace current)</span></label>
            <?php if (!empty($user['profile_image'])): ?>
                <div class="mb-2 d-flex align-items-center gap-2">
                    <img src="../<?= htmlspecialchars($user['profile_image']) ?>" class="rounded-circle border" style="width: 50px; height: 50px; object-fit: cover;" />
                    <span class="small text-muted">Current Profile Image</span>
                </div>
            <?php endif; ?>
            <input type="file" name="profile_image" class="form-control-file p-2 rounded border" accept="image/*" />
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-admin-primary">
                <i class="fas fa-save mr-1"></i> Save Changes
            </button>
            <a href="users.php" class="btn btn-light ml-2">Cancel</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
</main>
</div>
</body>
</html>

