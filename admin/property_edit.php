<?php
require_once __DIR__ . '/includes/admin_header.php';
require_once __DIR__ . '/../includes/mongodb_connect.php';

$property_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$property = $db->properties->findOne(['id' => $property_id]);

if (!$property) {
    echo "<div class='alert alert-danger'>Property not found!</div>";
    exit();
}

$cities = $db->cities->find()->toArray();
$amenities = $db->amenities->find()->toArray();

$pa_docs = $db->properties_amenities->find(['property_id' => $property_id])->toArray();
$selected_amenity_ids = array_map(fn($pa) => (int)$pa['amenity_id'], $pa_docs);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="font-weight-bold mb-0 text-dark">Edit PG / Hotel Property (#<?= $property_id ?>)</h3>
    <a href="properties.php" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;">
        <i class="fas fa-arrow-left mr-1"></i> Back to Properties
    </a>
</div>

<div class="admin-card">
    <div id="form-alert" class="alert alert-danger d-none" role="alert"></div>

    <form id="property-form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $property_id ?>">

        <div class="row">
            <div class="col-md-6 form-group">
                <label class="text-secondary font-weight-bold">PG / Hotel Name *</label>
                <input type="text" name="name" class="form-control bg-white border-secondary text-dark" value="<?= htmlspecialchars($property['name'] ?? '') ?>" required>
            </div>
            <div class="col-md-6 form-group">
                <label class="text-secondary font-weight-bold">City *</label>
                <select name="city_id" class="form-control bg-white border-secondary text-dark" required>
                    <?php foreach ($cities as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($property['city_id'] ?? 1) == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="text-secondary font-weight-bold">Full Address *</label>
            <input type="text" name="address" class="form-control bg-white border-secondary text-dark" value="<?= htmlspecialchars($property['address'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label class="text-secondary font-weight-bold">Description</label>
            <textarea name="description" rows="3" class="form-control bg-white border-secondary text-dark"><?= htmlspecialchars($property['description'] ?? '') ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-4 form-group">
                <label class="text-secondary font-weight-bold">Gender Preference</label>
                <select name="gender" class="form-control bg-white border-secondary text-dark">
                    <option value="unisex" <?= ($property['gender'] ?? '') === 'unisex' ? 'selected' : '' ?>>Unisex (Co-Living)</option>
                    <option value="male" <?= ($property['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male Only</option>
                    <option value="female" <?= ($property['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female Only</option>
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label class="text-secondary font-weight-bold">Monthly Rent (₹) *</label>
                <input type="number" name="rent" class="form-control bg-white border-secondary text-dark" value="<?= $property['rent'] ?? 0 ?>" required>
            </div>
            <div class="col-md-4 form-group">
                <label class="text-secondary font-weight-bold">Change Image (Optional)</label>
                <input type="file" name="image" class="form-control-file text-secondary">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 form-group">
                <label class="text-secondary font-weight-bold">Cleanliness Rating (1 to 5)</label>
                <input type="number" step="0.1" min="1" max="5" name="rating_clean" value="<?= $property['rating_clean'] ?? 4.5 ?>" class="form-control bg-white border-secondary text-dark">
            </div>
            <div class="col-md-4 form-group">
                <label class="text-secondary font-weight-bold">Food Rating (1 to 5)</label>
                <input type="number" step="0.1" min="1" max="5" name="rating_food" value="<?= $property['rating_food'] ?? 4.5 ?>" class="form-control bg-white border-secondary text-dark">
            </div>
            <div class="col-md-4 form-group">
                <label class="text-secondary font-weight-bold">Safety Rating (1 to 5)</label>
                <input type="number" step="0.1" min="1" max="5" name="rating_safety" value="<?= $property['rating_safety'] ?? 4.5 ?>" class="form-control bg-white border-secondary text-dark">
            </div>
        </div>

        <div class="form-group mt-3">
            <label class="text-secondary font-weight-bold d-block">Select Amenities Included:</label>
            <div class="row">
                <?php foreach ($amenities as $a): ?>
                    <?php $isChecked = in_array((int)$a['id'], $selected_amenity_ids); ?>
                    <div class="col-6 col-md-3 mb-2">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="amenities[]" value="<?= $a['id'] ?>" class="custom-control-input" id="amenity-<?= $a['id'] ?>" <?= $isChecked ? 'checked' : '' ?>>
                            <label class="custom-control-label text-dark font-weight-bold" for="amenity-<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?></label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


        <div class="mt-4">
            <button type="submit" class="btn-admin-primary px-5 py-3">
                <i class="fas fa-save mr-2"></i> Update Property Details
            </button>
        </div>
    </form>
</div>

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
                    alert('Property updated successfully!');
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

