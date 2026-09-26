<?php
require_once __DIR__ . '/includes/admin_header.php';
require_once __DIR__ . '/../includes/mongodb_connect.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name    = trim($_POST['full_name'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $phone        = trim($_POST['phone'] ?? '');
    $password     = trim($_POST['password'] ?? '');
    $gender       = trim($_POST['gender'] ?? 'male');
    $college_name = trim($_POST['college_name'] ?? '');

    if (empty($full_name) || empty($email) || empty($phone) || empty($password)) {
        $error = 'Full name, email, phone, and password are required.';
    } else {
        // Check if email already exists
        $existing = $db->users->findOne(['email' => $email]);
        if ($existing) {
            $error = 'User with this email already exists.';
        } else {
            // Process optional profile image upload
            $profile_image = null;
            if (!empty($_FILES['profile_image']['name'])) {
                $target_dir = __DIR__ . '/../uploads/profile/';
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
                $file_name = time() . '_' . rand(1000, 9999) . '.' . $ext;
                $target_file = $target_dir . $file_name;
                
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                    $profile_image = 'uploads/profile/' . $file_name;
                }
            }

            // Calculate next auto increment ID
            $max_doc = $db->users->findOne([], ['sort' => ['id' => -1]]);
            $new_id = ($max_doc && isset($max_doc['id'])) ? (int)$max_doc['id'] + 1 : 1;

            $insert_data = [
                'id'           => $new_id,
                'email'        => $email,
                'password'     => sha1($password),
                'full_name'    => $full_name,
                'phone'        => $phone,
                'gender'       => strtolower($gender),
                'college_name' => $college_name
            ];

            if ($profile_image) {
                $insert_data['profile_image'] = $profile_image;
            }

            $db->users->insertOne($insert_data);
            $success = 'User account created successfully!';
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="font-weight-bold mb-0 text-dark">Add New User Account</h3>
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
    <form method="POST" action="user_add.php" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group col-md-6 mb-3">
                <label class="font-weight-bold text-dark">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control" placeholder="e.g. Rahul Sharma" required />
            </div>
            <div class="form-group col-md-6 mb-3">
                <label class="font-weight-bold text-dark">Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" placeholder="rahul@gmail.com" required />
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6 mb-3">
                <label class="font-weight-bold text-dark">Phone Number <span class="text-danger">*</span></label>
                <input type="text" name="phone" class="form-control" placeholder="9876543210" required />
            </div>
            <div class="form-group col-md-6 mb-3">
                <label class="font-weight-bold text-dark">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required />
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6 mb-3">
                <label class="font-weight-bold text-dark">Gender</label>
                <select name="gender" class="form-control">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group col-md-6 mb-3">
                <label class="font-weight-bold text-dark">College / Institute Name</label>
                <input type="text" name="college_name" class="form-control" placeholder="e.g. Delhi University" />
            </div>
        </div>

        <div class="form-group mb-4">
            <label class="font-weight-bold text-dark">Profile Image <span class="text-muted font-weight-normal">(Optional)</span></label>
            <input type="file" name="profile_image" class="form-control-file p-2 rounded border" accept="image/*" />
            <small class="form-text text-muted">Allowed formats: JPG, PNG, WEBP.</small>
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-admin-primary">
                <i class="fas fa-user-plus mr-1"></i> Create User Account
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

