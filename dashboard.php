<?php
session_start();
require "includes/database_connect.php";

if (!isset($_SESSION["user_id"])) {
    header("location: index.php");
    die();
}
$user_id = (int) $_SESSION['user_id']; // cast to int for safety

// Fetch user
$sql_1 = "SELECT * FROM users WHERE id = $user_id";
$result_1 = mysqli_query($conn, $sql_1);
if (!$result_1) {
    echo "Something went wrong!";
    return;
}
$user = mysqli_fetch_assoc($result_1);
if (!$user) {
    echo "Something went wrong!";
    return;
}

// Fetch interested properties
$sql_2 = "SELECT iup.*, p.* 
            FROM interested_users_properties iup
            INNER JOIN properties p ON iup.property_id = p.id
            WHERE iup.user_id = $user_id";
$result_2 = mysqli_query($conn, $sql_2);
if (!$result_2) {
    echo "Something went wrong!";
    return;
}
$interested_properties = mysqli_fetch_all($result_2, MYSQLI_ASSOC);
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

    <div class="my-profile page-container">
        <h1>My Profile</h1>
        <div class="row">
            <div class="col-md-3 profile-img-container text-center">
                <?php if (!empty($user['profile_image']) && file_exists($user['profile_image'])) { ?>
                    <img src="<?= htmlspecialchars($user['profile_image']) ?>" class="profile-img" alt="Profile Image">
                <?php } else { ?>
                    <i class="fas fa-user profile-placeholder" aria-hidden="true"></i>
                <?php } ?>

                <?php
                // Show upload error if present
                if (!empty($_SESSION['error'])) { ?>
                    <div class="upload-error" id="server-upload-error">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                    </div>
                <?php
                    unset($_SESSION['error']);
                }
                ?>

                <form id="profileUploadForm" action="upload_profile.php" method="POST" enctype="multipart/form-data" class="mt-2">
                    <input id="profileImageInput" type="file" name="profile_image" accept="image/*" required class="form-control form-control-sm mb-1">
                    <div id="clientImageWarning" class="image-warning" style="display:none;"></div>
                    <button id="uploadBtn" type="submit" class="btn btn-primary btn-sm">Upload</button>
                </form>

                <small class="text-muted d-block mt-2">Minimum size: 563×688 px</small>
            </div>

            <div class="col-md-9">
                <div class="row no-gutters justify-content-between align-items-end">
                    <div class="profile">
                        <div class="name"><?= htmlspecialchars($user['full_name']) ?></div>
                        <div class="email"><?= htmlspecialchars($user['email']) ?></div>
                        <div class="phone"><?= htmlspecialchars($user['phone']) ?></div>
                        <div class="college"><?= htmlspecialchars($user['college_name']) ?></div>
                    </div>
                    <a href="chat.php" class="chat-btn">Chat With Us</a>
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
                if (!file) return;

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