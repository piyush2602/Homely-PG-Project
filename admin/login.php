<?php
session_start();
if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Homely PG</title>
    <link rel="icon" type="image/png" href="img/admin_favicon.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="css/admin.css" rel="stylesheet" />
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #0284c7 100%);
        }
        .admin-login-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border: 1px solid #bae6fd;
            border-radius: 20px;
            padding: 40px 32px;
            box-shadow: 0 20px 40px -10px rgba(2, 132, 199, 0.25);
        }
        .admin-login-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: #0284c7;
            text-align: center;
            margin-bottom: 8px;
        }
        .admin-login-sub {
            color: #64748b;
            text-align: center;
            font-size: 0.95rem;
            margin-bottom: 30px;
        }
        .form-control-dark {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            color: #0f172a !important;
            border-radius: 12px;
            padding: 12px 16px;
        }
        .form-control-dark:focus {
            background: #ffffff;
            border-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.2);
        }
    </style>

</head>
<body>

    <div class="admin-login-card">
        <div class="text-center mb-3">
            <i class="fas fa-user-shield text-primary" style="font-size: 42px;"></i>
        </div>
        <h2 class="admin-login-title">Admin Portal</h2>
        <p class="admin-login-sub">Log in with your administrator credentials</p>

        <div id="error-alert" class="alert alert-danger d-none" role="alert"></div>

        <form id="admin-login-form">
            <div class="form-group">
                <label class="text-secondary font-weight-bold small">Email Address</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-secondary text-secondary"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="email" id="email" class="form-control form-control-dark" placeholder="admin@gmail.com" value="admin@gmail.com" required>
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="text-secondary font-weight-bold small">Password</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-secondary text-secondary"><i class="fas fa-lock"></i></span>
                    </div>
                    <input type="password" id="password" class="form-control form-control-dark" placeholder="admin123" value="admin123" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block py-3 font-weight-bold" style="border-radius: 12px; background: linear-gradient(135deg, #0284c7, #0369a1); border: none;">
                <i class="fas fa-sign-in-alt mr-2"></i> Log In to Admin Portal
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="../index.php" class="text-secondary small text-decoration-none font-weight-bold">
                <i class="fas fa-arrow-left mr-1"></i> Back to Main Website
            </a>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $('#admin-login-form').on('submit', function(e) {
            e.preventDefault();
            const email = $('#email').val();
            const password = $('#password').val();
            $('#error-alert').addClass('d-none');

            $.ajax({
                url: 'api/admin_login.php',
                type: 'POST',
                data: { email: email, password: password },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        window.location.href = 'dashboard.php';
                    } else {
                        $('#error-alert').text(res.message).removeClass('d-none');
                    }
                },
                error: function() {
                    $('#error-alert').text('An error occurred during authentication.').removeClass('d-none');
                }
            });
        });
    </script>
</body>
</html>
