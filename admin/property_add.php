<?php
require_once __DIR__ . '/includes/admin_header.php';
require_once __DIR__ . '/../includes/mongodb_connect.php';

$cities = $db->cities->find()->toArray();
$amenities = $db->amenities->find()->toArray();

$amenity_icons = [
    'wifi'         => 'fas fa-wifi',
    'ac'           => 'fas fa-snowflake',
    'ro water'     => 'fas fa-tint',
    'tv'           => 'fas fa-tv',
    'laundry'      => 'fas fa-tshirt',
    'cleaning'     => 'fas fa-broom',
    'power backup' => 'fas fa-bolt',
    'geyser'       => 'fas fa-fire'
];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="font-weight-bold mb-0 text-dark"><i class="fas fa-plus-circle text-primary mr-2"></i> Add New PG / Hotel Property</h3>
        <small class="text-muted">Fill out the property details, amenities, pricing, and optional tenant feedback below.</small>
    </div>
    <a href="properties.php" class="btn btn-outline-secondary font-weight-bold" style="border-radius: 10px;">
        <i class="fas fa-arrow-left mr-1"></i> Back to Properties
    </a>
</div>

<div id="form-alert" class="alert alert-danger d-none" role="alert"></div>

<form id="property-form" enctype="multipart/form-data" class="mb-5">
    <input type="hidden" name="id" value="0">

    <!-- Section 1: Basic Information -->
    <div class="admin-form-section">
        <div class="admin-form-section-title">
            <i class="fas fa-building"></i> Basic Property Information
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label class="font-weight-bold text-secondary">PG / Hotel Name *</label>
                <input type="text" name="name" class="form-control admin-input" placeholder="e.g. Royal Palace PG & Hostel" required>
            </div>
            <div class="col-md-6 form-group">
                <label class="font-weight-bold text-secondary">City Location *</label>
                <select name="city_id" class="form-control admin-input" required>
                    <?php foreach ($cities as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="font-weight-bold text-secondary">Full Address *</label>
            <input type="text" name="address" class="form-control admin-input" placeholder="e.g. Plot No 45, Near Metro Station, Sector 15, Dwarka" required>
        </div>
        <div class="form-group mb-0">
            <label class="font-weight-bold text-secondary">Detailed Description</label>
            <textarea name="description" rows="3" class="form-control admin-input" placeholder="Enter detailed description of rooms, security, meal plans, and rules..."></textarea>
        </div>
    </div>

    <!-- Section 2: Pricing, Gender & Image -->
    <div class="admin-form-section">
        <div class="admin-form-section-title">
            <i class="fas fa-tag"></i> Rent, Occupancy & Media
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label class="font-weight-bold text-secondary">Gender Preference</label>
                <select name="gender" class="form-control admin-input">
                    <option value="unisex">Unisex (Co-Living)</option>
                    <option value="male">Male Only</option>
                    <option value="female">Female Only</option>
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label class="font-weight-bold text-secondary">Monthly Rent (₹) *</label>
                <input type="number" name="rent" class="form-control admin-input" placeholder="8500" required>
            </div>
            <div class="col-md-4 form-group">
                <label class="font-weight-bold text-secondary">Property Cover Photo</label>
                <input type="file" name="image" class="form-control-file admin-input py-2">
            </div>
        </div>
    </div>

    <!-- Section 3: Ratings -->
    <div class="admin-form-section">
        <div class="admin-form-section-title">
            <i class="fas fa-star text-warning"></i> Quality &amp; Hygiene Ratings (1.0 to 5.0)
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label class="font-weight-bold text-secondary">Cleanliness Rating</label>
                <input type="number" step="0.1" min="1" max="5" name="rating_clean" value="4.8" class="form-control admin-input">
            </div>
            <div class="col-md-4 form-group">
                <label class="font-weight-bold text-secondary">Food Quality Rating</label>
                <input type="number" step="0.1" min="1" max="5" name="rating_food" value="4.5" class="form-control admin-input">
            </div>
            <div class="col-md-4 form-group">
                <label class="font-weight-bold text-secondary">Safety &amp; Security Rating</label>
                <input type="number" step="0.1" min="1" max="5" name="rating_safety" value="4.9" class="form-control admin-input">
            </div>
        </div>
    </div>

    <!-- Section 4: Amenities -->
    <div class="admin-form-section">
        <div class="admin-form-section-title">
            <i class="fas fa-concierge-bell text-info"></i> Select Included Amenities
        </div>
        <div class="amenity-grid">
            <?php foreach ($amenities as $a): ?>
                <?php 
                $name_lower = strtolower($a['name']);
                $icon_class = $amenity_icons[$name_lower] ?? 'fas fa-check-circle';
                ?>
                <div class="amenity-pill-checkbox">
                    <input type="checkbox" name="amenities[]" value="<?= $a['id'] ?>" id="amenity-<?= $a['id'] ?>" checked>
                    <label class="amenity-pill-label" for="amenity-<?= $a['id'] ?>">
                        <i class="<?= $icon_class ?>"></i>
                        <span><?= htmlspecialchars($a['name']) ?></span>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Section 5: Optional Feedback / Testimonial Review -->
    <div class="admin-form-section">
        <div class="admin-form-section-title">
            <i class="fas fa-comment-dots text-success"></i> Tenant Review / Feedback (Optional)
        </div>
        <div class="row">
            <div class="col-md-5 form-group">
                <label class="font-weight-bold text-secondary">Reviewer / Student Name (Optional)</label>
                <input type="text" name="reviewer_name" class="form-control admin-input" placeholder="e.g. Aman Sharma">
            </div>
            <div class="col-md-7 form-group">
                <label class="font-weight-bold text-secondary">Testimonial Review Comment (Optional)</label>
                <textarea name="feedback_text" rows="2" class="form-control admin-input" placeholder="e.g. Highly hygienic rooms with great food and peaceful study environment..."></textarea>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="d-flex justify-content-end align-items-center my-4 pb-4">
        <a href="properties.php" class="btn btn-light font-weight-bold border mr-3 px-4 py-2" style="border-radius: 10px;">Cancel</a>
        <button type="submit" class="btn-admin-primary px-5 py-3">
            <i class="fas fa-save mr-2"></i> Save PG Property
        </button>
    </div>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $('#property-form').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        $('#form-alert').addClass('d-none');

        $.ajax({
            url: 'api/property_save.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    alert('Property saved successfully!');
                    window.location.href = 'properties.php';
                } else {
                    $('#form-alert').text(res.message).removeClass('d-none');
                }
            }
        });
    });
</script>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
</main>
</div>
</body>
</html>
