<?php
// admin/includes/admin_footer.php
?>
<footer class="admin-footer mt-auto">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between px-4 py-3">
        <div class="admin-footer-left text-muted small">
            &copy; <?= date('Y') ?> <strong>Homely PG Admin Portal</strong> • Crafted with <i class="fas fa-heart text-danger"></i> by 
            <a href="https://www.linkedin.com/in/piyush-agrawal-b01249203/" target="_blank" class="font-weight-bold text-primary">Piyush Agrawal</a> 
            (<a href="https://www.ietlucknow.ac.in/" target="_blank" class="text-secondary">IET Lucknow</a>)
        </div>
        <div class="admin-footer-right small mt-2 mt-md-0">
            <a href="../index.php" target="_blank" class="text-secondary mr-3"><i class="fas fa-globe mr-1"></i> Main Website</a>
            <a href="dashboard.php" class="text-secondary mr-3"><i class="fas fa-chart-pie mr-1"></i> Dashboard</a>
            <a href="#top" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;" class="text-secondary"><i class="fas fa-arrow-up mr-1"></i> Top</a>
        </div>
    </div>
</footer>
