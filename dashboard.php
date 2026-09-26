<?php
session_start();
require "includes/mongodb_connect.php";

if (!isset($_SESSION["user_id"])) {
    header("location: index.php");
    die();
}
$user_id = (int) $_SESSION['user_id'];

// Fetch user
$user = $db->users->findOne(['id' => $user_id]);
if (!$user) {
    echo "Something went wrong!";
    return;
}

// Fetch interested properties
$iup_docs = $db->interested_users_properties->find(['user_id' => $user_id])->toArray();
$property_ids = array_map(fn($doc) => (int)$doc['property_id'], (array)$iup_docs);

$interested_properties = [];
if (!empty($property_ids)) {
    $props = $db->properties->find(['id' => ['$in' => $property_ids]])->toArray();
    $props_by_id = [];
    foreach ($props as $p) {
        $props_by_id[$p['id']] = (array)$p;
    }
    foreach ($iup_docs as $iup) {
        $pid = (int)$iup['property_id'];
        if (isset($props_by_id[$pid])) {
            $interested_properties[] = array_merge((array)$iup, $props_by_id[$pid]);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | PG Life</title>

    <?php include "includes/head_links.php"; ?>
    <link href="css/dashboard.css" rel="stylesheet" />
    <style>
        /* Quick inline fixes (move to CSS file if you prefer) */
        .profile-img-container {
            padding: 15px;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 0 auto;
        }

        .profile-placeholder {
            font-size: 80px;
            color: #777;
            display: block;
            margin: 0 auto;
        }

        .upload-error {
            color: #a94442;
            background: #f2dede;
            padding: 8px 12px;
            border-radius: 4px;
            margin-top: 8px;
        }

        .image-warning {
            color: #856404;
            background: #fff3cd;
            padding: 8px 12px;
            border-radius: 4px;
            margin-top: 8px;
        }

        .property-card img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .chat-btn {
            display: inline-block;
            background-color: #3EC6B5;
            color: white;
            padding: 15px 40px;
            font-size: 22px;
            font-weight: bold;
            border-radius: 20px;
            text-decoration: none;
            cursor: pointer;

            border: none !important;
            /* Remove border */
            outline: none !important;
            /* Remove outline */
            box-shadow: none !important;
            /* Remove any outer shadow */
        }

        .chat-btn:focus,
        .chat-btn:active {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
        }
    </style>
</head>

<body>
    <?php include "includes/header.php"; ?>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb py-2">
            <li class="breadcrumb-item">
                <a href="index.php">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Dashboard
            </li>
        </ol>
    </nav>

    <div class="profile-section-container">
        <div class="profile-card-wrapper">
            <div class="profile-card-header">
                <h2>
                    <div class="header-icon"><i class="fas fa-id-card"></i></div>
                    My Profile
                </h2>
                <?php $is_verified = !empty($user['is_verified']); ?>
                <?php if ($is_verified): ?>
                    <span class="profile-verified-badge badge-verified">
                        <i class="fas fa-check-circle"></i> Verified Account
                    </span>
                <?php else: ?>
                    <span class="profile-verified-badge badge-unverified">
                        <i class="fas fa-times-circle"></i> Unverified Account
                    </span>
                <?php endif; ?>
            </div>

            <div class="row align-items-center">
                <!-- Profile Avatar & Upload -->
                <div class="col-md-4 col-lg-3 profile-avatar-box mb-4 mb-md-0">
                    <div class="profile-avatar-ring">
                        <?php if (!empty($user['profile_image']) && file_exists($user['profile_image'])) { ?>
                            <img src="<?= htmlspecialchars($user['profile_image']) ?>" alt="Profile Image">
                        <?php } else { ?>
                            <div class="profile-avatar-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php } ?>
                    </div>

                    <?php if (!empty($_SESSION['error'])) { ?>
                        <div class="upload-error mb-2" id="server-upload-error">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                        </div>
                    <?php unset($_SESSION['error']); } ?>

                    <form id="profileUploadForm" action="upload_profile.php" method="POST" enctype="multipart/form-data" class="text-center w-100">
                        <label for="profileImageInput" class="btn-choose-photo mb-2">
                            <i class="fas fa-camera"></i> Change Photo
                        </label>
                        <input id="profileImageInput" type="file" name="profile_image" accept="image/*" required style="display: none;">
                        <div id="fileNameDisplay" class="small text-truncate text-muted mb-2 font-weight-bold" style="max-width: 200px; display: none; margin: 0 auto;"></div>
                        <div id="clientImageWarning" class="image-warning small mb-2" style="display:none;"></div>
                        <div>
                            <button id="uploadBtn" type="submit" class="btn-emerald-upload">
                                <i class="fas fa-upload mr-1"></i> Upload Image
                            </button>
                        </div>
                    </form>

                    <small class="text-muted d-block mt-2" style="font-size: 0.75rem;">Min dimensions: 563×688 px</small>
                </div>

                <!-- User Information Grid -->
                <div class="col-md-8 col-lg-9">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                        <div>
                            <h1 class="profile-user-name mb-1"><?= htmlspecialchars($user['full_name']) ?></h1>
                            <span class="text-muted small font-weight-bold"><i class="fas fa-user-graduate text-success mr-1"></i> Registered Homely PG Resident</span>
                        </div>
                        <a href="#" onclick="toggleChatWidget(); return false;" class="btn-emerald-chat mt-3 mt-md-0">
                            <i class="fas fa-comments"></i> Chat With Us
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="info-item-row">
                                <div class="info-icon-badge"><i class="fas fa-envelope"></i></div>
                                <div>
                                    <div class="info-label">Email Address</div>
                                    <div class="info-val text-truncate" style="max-width: 170px;" title="<?= htmlspecialchars($user['email']) ?>"><?= htmlspecialchars($user['email']) ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="info-item-row">
                                <div class="info-icon-badge"><i class="fas fa-phone-alt"></i></div>
                                <div>
                                    <div class="info-label">Phone Number</div>
                                    <div class="info-val"><?= htmlspecialchars($user['phone']) ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="info-item-row">
                                <div class="info-icon-badge"><i class="fas fa-graduation-cap"></i></div>
                                <div>
                                    <div class="info-label">College / Institute</div>
                                    <div class="info-val"><?= htmlspecialchars($user['college_name'] ?? 'N/A') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Identity Document Upload & Verification Status Row -->
                    <div class="mt-3 pt-3 border-top" style="border-color: #ecfdf5 !important;">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1"><i class="fas fa-id-card text-success mr-2"></i> Identity Card Document</h6>
                                <p class="small text-muted mb-2">Upload your Aadhar Card, Student ID, or Passport for admin account verification.</p>
                            </div>
                            
                            <?php if (!empty($user['id_card_path']) && file_exists($user['id_card_path'])): ?>
                                <div class="d-flex align-items-center mb-2 mb-md-0">
                                    <a href="<?= htmlspecialchars($user['id_card_path']) ?>" target="_blank" download class="btn btn-sm btn-outline-success font-weight-bold mr-2" style="border-radius: 8px;">
                                        <i class="fas fa-download mr-1"></i> View / Download ID
                                    </a>
                                    <button type="button" class="btn btn-sm btn-light border text-muted" onclick="$('#idCardUploadBox').toggle();" style="border-radius: 8px;">
                                        <i class="fas fa-sync-alt mr-1"></i> Replace
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($_SESSION['id_error'])): ?>
                            <div class="alert alert-danger small p-2 rounded mb-2"><i class="fas fa-exclamation-circle mr-1"></i><?= htmlspecialchars($_SESSION['id_error']) ?></div>
                        <?php unset($_SESSION['id_error']); endif; ?>

                        <?php if (!empty($_SESSION['id_success'])): ?>
                            <div class="alert alert-success small p-2 rounded mb-2"><i class="fas fa-check-circle mr-1"></i><?= htmlspecialchars($_SESSION['id_success']) ?></div>
                        <?php unset($_SESSION['id_success']); endif; ?>

                        <form id="idCardUploadBox" action="upload_id_card.php" method="POST" enctype="multipart/form-data" class="mt-2 <?= (!empty($user['id_card_path']) && file_exists($user['id_card_path'])) ? 'style="display:none;"' : '' ?>">
                            <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                                <input type="file" name="id_card" accept="image/*,.pdf" class="form-control-file p-2 rounded border bg-light small" style="max-width: 320px;" required />
                                <button type="submit" class="btn-emerald-upload">
                                    <i class="fas fa-file-upload mr-1"></i> Upload ID Card
                                </button>
                            </div>
                            <small class="text-muted d-block mt-1">Accepted formats: JPG, PNG, WEBP, PDF (Max 5MB).</small>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php if (count($interested_properties) > 0) { ?>
        <div class="my-interested-properties">
            <div class="page-container">
                <h1>My Interested Properties</h1>

                <?php foreach ($interested_properties as $property) {
                    // find property images, use default if none
                    $glob_path = "img/properties/" . (int)$property['id'] . "/*";
                    $property_images = glob($glob_path);
                    $thumb = (!empty($property_images) && file_exists($property_images[0])) ? $property_images[0] : 'img/property_default.jpg';
                ?>

                    <div class="property-card property-id-<?= (int)$property['id'] ?> row mb-3">
                        <div class="image-container col-md-4">
                            <img src="<?= htmlspecialchars($thumb) ?>" alt="<?= htmlspecialchars($property['name'] ?? 'Property') ?>">
                        </div>
                        <div class="content-container col-md-8">
                            <div class="row no-gutters justify-content-between">
                                <?php
                                // calculate rating safely (ensure numeric)
                                $r_clean = is_numeric($property['rating_clean']) ? (float)$property['rating_clean'] : 0;
                                $r_food  = is_numeric($property['rating_food']) ? (float)$property['rating_food'] : 0;
                                $r_safe  = is_numeric($property['rating_safety']) ? (float)$property['rating_safety'] : 0;
                                $total_rating = ($r_clean + $r_food + $r_safe) / 3;
                                $total_rating = round($total_rating, 1);
                                ?>
                                <div class="star-container" title="<?= $total_rating ?>">
                                    <?php
                                    $rating = $total_rating;
                                    for ($i = 0; $i < 5; $i++) {
                                        if ($rating >= $i + 0.8) { ?>
                                            <i class="fas fa-star"></i>
                                        <?php } elseif ($rating >= $i + 0.3) { ?>
                                            <i class="fas fa-star-half-alt"></i>
                                        <?php } else { ?>
                                            <i class="far fa-star"></i>
                                    <?php }
                                    } ?>
                                </div>

                                <div class="interested-container">
                                    <i class="is-interested-image fas fa-heart" property_id="<?= (int)$property['id'] ?>"></i>
                                </div>
                            </div>

                            <div class="detail-container">
                                <div class="property-name"><?= htmlspecialchars($property['name'] ?? '') ?></div>
                                <div class="property-address"><?= htmlspecialchars($property['address'] ?? '') ?></div>
                                <div class="property-gender">
                                    <?php if (($property['gender'] ?? '') === "male") { ?>
                                        <img src="img/male.png" alt="male">
                                    <?php } elseif (($property['gender'] ?? '') === "female") { ?>
                                        <img src="img/female.png" alt="female">
                                    <?php } else { ?>
                                        <img src="img/unisex.png" alt="unisex">
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row no-gutters mt-2">
                                <div class="rent-container col-6">
                                    <div class="rent">₹ <?= number_format((float)$property['rent']) ?>/-</div>
                                    <div class="rent-unit">per month</div>
                                </div>
                                <div class="button-container col-6 text-right">
                                    <a href="property_detail.php?property_id=<?= (int)$property['id'] ?>" class="btn btn-primary">View</a>
                                </div>
                            </div>

                        </div>
                    </div>

                <?php } ?>
            </div>
        </div>
    <?php } ?>
    <?php include "includes/chat_widget.php"; ?>
    <?php include "includes/footer.php"; ?>
    <script type="text/javascript" src="js/dashboard.js"></script>
    <script>
        // Client-side image dimension check (min: 563x688)
        (function() {
            const input = document.getElementById('profileImageInput');
            const warning = document.getElementById('clientImageWarning');
            const uploadBtn = document.getElementById('uploadBtn');
            const MIN_W = 563;
            const MIN_H = 688;

            if (!input) return;

            input.addEventListener('change', function(e) {
                warning.style.display = 'none';
                warning.textContent = '';
                uploadBtn.disabled = false;

                const file = this.files[0];
                const nameDisplay = document.getElementById('fileNameDisplay');
                if (file) {
                    if (nameDisplay) {
                        nameDisplay.textContent = 'Selected: ' + file.name;
                        nameDisplay.style.display = 'block';
                    }
                } else {
                    if (nameDisplay) nameDisplay.style.display = 'none';
                    return;
                }

                // quick type check
                if (!file.type.startsWith('image/')) {
                    warning.style.display = 'block';
                    warning.textContent = 'Please select a valid image file.';
                    uploadBtn.disabled = true;
                    return;
                }

                const img = new Image();
                img.onload = function() {
                    if (img.width < MIN_W || img.height < MIN_H) {
                        warning.style.display = 'block';
                        warning.textContent = `Selected image is too small (${img.width}×${img.height}). Minimum required is ${MIN_W}×${MIN_H} px.`;
                        uploadBtn.disabled = true;
                    } else {
                        // ok
                        warning.style.display = 'none';
                        warning.textContent = '';
                        uploadBtn.disabled = false;
                    }
                    URL.revokeObjectURL(img.src);
                };
                img.onerror = function() {
                    warning.style.display = 'block';
                    warning.textContent = 'Could not read the image. Try a different file.';
                    uploadBtn.disabled = true;
                    URL.revokeObjectURL(img.src);
                };
                img.src = URL.createObjectURL(file);
            });

            // Prevent form submit if uploadBtn disabled (safety)
            const form = document.getElementById('profileUploadForm');
            form.addEventListener('submit', function(e) {
                if (uploadBtn.disabled) {
                    e.preventDefault();
                }
            });
        })();
    </script>
</body>

</html>