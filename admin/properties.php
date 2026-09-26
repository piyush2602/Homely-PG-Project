<?php
require_once __DIR__ . '/includes/admin_header.php';
require_once __DIR__ . '/../includes/mongodb_connect.php';

$properties = $db->properties->find([], ['sort' => ['id' => -1]])->toArray();
$cities = $db->cities->find()->toArray();
$cities_by_id = [];
foreach ($cities as $c) {
    $cities_by_id[$c['id']] = $c['name'];
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="font-weight-bold mb-0 text-dark">Listed PGs &amp; Hotels</h3>
    <a href="property_add.php" class="btn-admin-primary">
        <i class="fas fa-plus-circle"></i> Add New PG / Hotel
    </a>
</div>

<div class="admin-table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>PG / Hotel Name</th>
                <th>City</th>
                <th>Monthly Rent</th>
                <th>Gender</th>
                <th>Ratings (Avg)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($properties)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No PG properties listed yet. Click "Add New PG" above.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($properties as $p): ?>
                    <?php 
                    $city_name = $cities_by_id[$p['city_id']] ?? 'Unknown'; 
                    $avg_rating = round((($p['rating_clean'] ?? 0) + ($p['rating_food'] ?? 0) + ($p['rating_safety'] ?? 0)) / 3, 1);
                    ?>
                    <tr id="prop-row-<?= $p['id'] ?>">
                        <td><strong>#<?= $p['id'] ?></strong></td>
                        <td>
                            <div class="font-weight-bold text-dark"><?= htmlspecialchars($p['name'] ?? '') ?></div>
                            <div class="small text-muted"><?= htmlspecialchars(substr($p['address'] ?? '', 0, 45)) ?>...</div>
                        </td>
                        <td><span class="badge badge-info"><?= htmlspecialchars($city_name) ?></span></td>
                        <td><strong class="text-success">₹<?= number_format($p['rent'] ?? 0) ?></strong> /mo</td>
                        <td><span class="badge badge-dark text-capitalize"><?= htmlspecialchars($p['gender'] ?? 'unisex') ?></span></td>
                        <td><span class="text-warning">★ <?= $avg_rating ?></span></td>
                        <td>
                            <a href="property_edit.php?id=<?= $p['id'] ?>" class="btn-admin-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button onclick="deleteProperty(<?= $p['id'] ?>)" class="btn-admin-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

        </tbody>
    </table>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    function deleteProperty(id) {
        if (confirm('Are you sure you want to delete this property? This action cannot be undone.')) {
            $.ajax({
                url: 'api/property_delete.php',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        $('#prop-row-' + id).fadeOut(300, function() { $(this).remove(); });
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

