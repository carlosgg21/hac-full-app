<?php
$title = 'Services';
$servicesCount = is_array($services) ? count($services) : 0;
ob_start();
?>

<?php $basePath = defined('BASE_PATH_URL') ? BASE_PATH_URL : '/backend'; ?>
<div class="service-list-page">
<div class="mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-gray-100 text-primary">
                <i class="bi bi-briefcase text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Services</h1>
                <p class="text-sm text-gray-500 mt-0.5"><?= $servicesCount ?> <?= $servicesCount === 1 ? 'service' : 'services' ?></p>
            </div>
        </div>
        <a href="<?= Response::url('/services/create') ?>" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition shadow-sm">
            <i class="bi bi-plus-lg text-base"></i>
            New Service
        </a>
    </div>
</div>

<section class="bg-white rounded-xl shadow-md overflow-hidden w-full overflow-x-auto">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-50 text-gray-700 border-b border-gray-200">
                <th class="py-3 px-4 text-left text-sm font-semibold">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-hash text-gray-500"></i>
                        ID
                    </span>
                </th>
                <th class="py-3 px-4 text-left text-sm font-semibold">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-tag text-gray-500"></i>
                        Name
                    </span>
                </th>
                <th class="py-3 px-4 text-left text-sm font-semibold">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-file-text text-gray-500"></i>
                        Description
                    </span>
                </th>
                <th class="py-3 px-4 text-left text-sm font-semibold">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-toggle-on text-gray-500"></i>
                        Status
                    </span>
                </th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($services)): ?>
                <tr>
                    <td colspan="5" class="py-12 text-center text-gray-500">
                        <i class="bi bi-inbox text-4xl block mb-2 text-gray-300"></i>
                        No services
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($services as $service): ?>
                    <?php
                    $active = (int)($service['is_active'] ?? 0);
                    $toggleFormId = 'toggle-form-' . (int)$service['id'];
                    $deleteFormId = 'delete-form-' . (int)$service['id'];
                    ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50/80 transition">
                        <td class="py-3 px-4 text-sm text-gray-600"><?= (int)$service['id'] ?></td>
                        <td class="py-3 px-4 text-sm font-medium text-gray-800"><?= htmlspecialchars($service['name'] ?? '') ?></td>
                        <td class="py-3 px-4 text-sm text-gray-600"><?= htmlspecialchars(substr($service['description'] ?? '', 0, 100)) ?><?= strlen($service['description'] ?? '') > 100 ? '...' : '' ?></td>
                        <td class="py-3 px-4">
                            <form id="<?= $toggleFormId ?>" method="POST" action="<?= Response::url('/services/' . (int)$service['id']) ?>" class="inline">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($service['name'] ?? '') ?>">
                                <input type="hidden" name="description" value="<?= htmlspecialchars($service['description'] ?? '') ?>">
                                <input type="hidden" name="is_active" value="<?= $active ? '0' : '1' ?>">
                                <button type="submit" class="cursor-pointer border-0 bg-transparent p-0 text-left" title="<?= $active ? 'Click to deactivate' : 'Click to activate' ?>">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full transition <?= $active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' ?>">
                                        <i class="bi <?= $active ? 'bi-check-circle' : 'bi-x-circle' ?>"></i>
                                        <?= $active ? 'Active' : 'Inactive' ?>
                                    </span>
                                </button>
                            </form>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <a href="<?= Response::url('/services/' . (int)$service['id'] . '/edit') ?>" class="inline-flex items-center gap-1.5 px-2 py-1.5 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition">
                                    <i class="bi bi-pencil"></i>
                                    Edit
                                </a>
                                <button type="button" class="inline-flex items-center gap-1.5 px-2 py-1.5 text-sm text-red-600 hover:bg-red-50 rounded-lg transition" onclick="window.serviceDeleteFormId='<?= $deleteFormId ?>'; document.getElementById('serviceDeleteDialog').showModal();">
                                    <i class="bi bi-trash"></i>
                                    Delete
                                </button>
                            </div>
                            <form id="<?= $deleteFormId ?>" method="POST" action="<?= Response::url('/services/' . (int)$service['id']) ?>" style="display: none;">
                                <input type="hidden" name="_method" value="DELETE">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>
</div>

<dialog id="serviceDeleteDialog" class="rounded-xl shadow-lg border border-gray-200 p-0 w-full max-w-md backdrop:bg-black/30" aria-labelledby="serviceDeleteDialogTitle" aria-describedby="serviceDeleteDialogDesc">
    <div class="p-6">
        <h2 id="serviceDeleteDialogTitle" class="text-lg font-semibold text-gray-800 mb-2">Delete service</h2>
        <p id="serviceDeleteDialogDesc" class="text-sm text-gray-600 mb-6">Are you sure you want to delete this service? This action cannot be undone.</p>
        <div class="flex justify-end gap-3">
            <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition" onclick="document.getElementById('serviceDeleteDialog').close();">Cancel</button>
            <button type="button" id="serviceDeleteDialogConfirm" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">Delete</button>
        </div>
    </div>
</dialog>

<script>
(function() {
    var dialog = document.getElementById('serviceDeleteDialog');
    var confirmBtn = document.getElementById('serviceDeleteDialogConfirm');
    if (dialog && confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            var formId = window.serviceDeleteFormId;
            if (formId) {
                var form = document.getElementById(formId);
                if (form) form.submit();
                window.serviceDeleteFormId = null;
            }
            dialog.close();
        });
        dialog.addEventListener('click', function(e) {
            if (e.target === dialog) dialog.close();
        });
    }
})();
</script>

<?php
$content = ob_get_clean();
require APP_PATH . '/Views/layouts/admin.php';
?>
