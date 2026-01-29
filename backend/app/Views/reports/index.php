<?php
$title = 'Reports';
ob_start();
?>

<h2>Reports</h2>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin: 2rem 0;">
    <a href="<?= Response::url('/reports/quotes') ?>" style="background: white; padding: 2rem; border-radius: 8px; text-decoration: none; color: #333; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h3>Reporte de Cotizaciones</h3>
        <p>View quotes statistics and analysis</p>
    </a>
    
    <a href="<?= Response::url('/reports/projects') ?>" style="background: white; padding: 2rem; border-radius: 8px; text-decoration: none; color: #333; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h3>Projects Report</h3>
        <p>View projects statistics and analysis</p>
    </a>
</div>

<?php
$content = ob_get_clean();
require APP_PATH . '/Views/layouts/admin.php';
?>