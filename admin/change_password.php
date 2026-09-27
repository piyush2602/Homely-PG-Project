<?php
require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="font-weight-bold mb-0 text-dark">
            <i class="fas fa-key text-primary mr-2"></i> Change Admin Password
        </h3>
        <small class="text-muted">Update your administrative access credentials securely.</small>
    </div>
    <a href="dashboard.php" class="btn btn-outline-secondary font-weight-bold" style="border-radius: 10px;">
        <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
    </a>
</div>

<div class="row justify-content-center my-4">
    <div class="col-lg-7 col-md-10">
        <div class="admin-form-section">
            <div class="admin-form-section-title mb-4">
                <i class="fas fa-user-lock"></i> Security Verification &amp; Password Reset
            </div>

            <div id="password-alert" class="alert d-none mb-4" role="alert"></div>

            <form id="change-password-form">
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-secondary">
                        <i class="fas fa-lock mr-1 text-danger"></i> Current Password *
                    </label>
                    <div class="input-group">
                        <input type="password" id="current_password" name="current_password" class="form-control admin-input" placeholder="Enter your current admin password..." required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary border-left-0" type="button" onclick="togglePassVisibility('current_password', 'eye-curr')" style="border-radius: 0 10px 10px 0;">
                                <i class="fas fa-eye" id="eye-curr"></i>
                            </button>
                        </div>
                    </div>
                    <small class="text-muted">You must confirm your current password before setting a new one.</small>
                </div>

                <hr class="my-4" style="border-top: 1px dashed #bae6fd;">

                <div class="form-group mb-3">
                    <label class="font-weight-bold text-secondary">
                        <i class="fas fa-key mr-1 text-primary"></i> New Password *
                    </label>
                    <div class="input-group">
                        <input type="password" id="new_password" name="new_password" class="form-control admin-input" placeholder="Enter new password (min. 6 characters)..." minlength="6" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary border-left-0" type="button" onclick="togglePassVisibility('new_password', 'eye-new')" style="border-radius: 0 10px 10px 0;">
                                <i class="fas fa-eye" id="eye-new"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-secondary">
                        <i class="fas fa-check-double mr-1 text-success"></i> Confirm New Password *
                    </label>
                    <div class="input-group">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control admin-input" placeholder="Re-enter your new password..." minlength="6" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary border-left-0" type="button" onclick="togglePassVisibility('confirm_password', 'eye-conf')" style="border-radius: 0 10px 10px 0;">
                                <i class="fas fa-eye" id="eye-conf"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end align-items-center mt-4">
                    <button type="reset" class="btn btn-light border font-weight-bold mr-3 px-4 py-2" style="border-radius: 10px;">Reset Form</button>
                    <button type="submit" id="btn-save-pass" class="btn-admin-primary px-5 py-2" style="border-radius: 10px;">
                        <i class="fas fa-shield-alt mr-2"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    function togglePassVisibility(inputId, eyeId) {
        const input = document.getElementById(inputId);
        const eye = document.getElementById(eyeId);
        if (input.type === "password") {
            input.type = "text";
            eye.classList.remove("fa-eye");
            eye.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            eye.classList.remove("fa-eye-slash");
            eye.classList.add("fa-eye");
        }
    }

    $('#change-password-form').on('submit', function(e) {
        e.preventDefault();
        const alertBox = $('#password-alert');
        alertBox.addClass('d-none').removeClass('alert-danger alert-success');

        const currentPass = $('#current_password').val().trim();
        const newPass = $('#new_password').val().trim();
        const confPass = $('#confirm_password').val().trim();

        if (newPass !== confPass) {
            alertBox.removeClass('d-none').addClass('alert-danger').html('<i class="fas fa-exclamation-triangle mr-2"></i> New password and confirm password do not match.');
            return;
        }

        if (newPass.length < 6) {
            alertBox.removeClass('d-none').addClass('alert-danger').html('<i class="fas fa-exclamation-triangle mr-2"></i> Password must be at least 6 characters long.');
            return;
        }

        $('#btn-save-pass').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Updating...');

        $.ajax({
            url: 'api/change_password.php',
            type: 'POST',
            data: {
                current_password: currentPass,
                new_password: newPass,
                confirm_password: confPass
            },
            dataType: 'json',
            success: function(res) {
                $('#btn-save-pass').prop('disabled', false).html('<i class="fas fa-shield-alt mr-2"></i> Update Password');
                if (res.success) {
                    alertBox.removeClass('d-none').addClass('alert-success').html('<i class="fas fa-check-circle mr-2"></i> ' + res.message);
                    $('#change-password-form')[0].reset();
                } else {
                    alertBox.removeClass('d-none').addClass('alert-danger').html('<i class="fas fa-times-circle mr-2"></i> ' + res.message);
                }
            },
            error: function() {
                $('#btn-save-pass').prop('disabled', false).html('<i class="fas fa-shield-alt mr-2"></i> Update Password');
                alertBox.removeClass('d-none').addClass('alert-danger').html('<i class="fas fa-times-circle mr-2"></i> An error occurred while processing your request.');
            }
        });
    });
</script>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
</main>
</div>
</body>
</html>
