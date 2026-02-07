<?php
$title = 'Reports';
ob_start();
?>

<div class="mb-6">
    <div class="flex items-center gap-3">
        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10 text-primary">
            <i class="bi bi-graph-up-arrow text-2xl"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Reports</h1>
            <p class="text-sm text-gray-500 mt-0.5">View statistics and analysis</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <a href="<?= Response::url('/reports/quotes') ?>" class="flex items-center gap-4 p-6 bg-white rounded-xl shadow-sm border border-gray-200 hover:border-primary/30 hover:shadow-md transition">
        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
            <i class="bi bi-file-earmark-text text-xl"></i>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-lg font-semibold text-gray-800 mb-1">Quotes Report</h3>
            <p class="text-sm text-gray-600">View quotes statistics and analysis</p>
        </div>
        <i class="bi bi-chevron-right text-gray-400 flex-shrink-0"></i>
    </a>
    <a href="<?= Response::url('/reports/projects') ?>" class="flex items-center gap-4 p-6 bg-white rounded-xl shadow-sm border border-gray-200 hover:border-primary/30 hover:shadow-md transition">
        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
            <i class="bi bi-folder text-xl"></i>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-lg font-semibold text-gray-800 mb-1">Projects Report</h3>
            <p class="text-sm text-gray-600">View projects statistics and analysis</p>
        </div>
        <i class="bi bi-chevron-right text-gray-400 flex-shrink-0"></i>
    </a>
</div>

<?php
$content = ob_get_clean();
require APP_PATH . '/Views/layouts/admin.php';
?>
