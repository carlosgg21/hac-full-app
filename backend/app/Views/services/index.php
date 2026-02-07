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
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10 text-primary">
                <i class="bi bi-briefcase-fill text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Services</h1>
                <p class="text-sm text-gray-500 mt-0.5"><?= $servicesCount ?> <?= $servicesCount === 1 ? 'service' : 'services' ?></p>
            </div>
        </div>
        <a href="<?= Response::url('/services/create') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium bg-primary text-white rounded-lg hover:bg-primary-light transition shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
            <i class="bi bi-plus-lg text-base"></i>
            New Service
        </a>
    </div>
</div>

<section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden w-full overflow-x-auto">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-50/80 text-gray-600 border-b border-gray-200">
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-hash text-gray-400"></i>
                        ID
                    </span>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-tag text-gray-400"></i>
                        Name
                    </span>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-file-text text-gray-400"></i>
                        Description
                    </span>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-toggle-on text-gray-400"></i>
                        Status
                    </span>
                </th>
                <th class="py-3.5 px-5 text-right text-xs font-semibold uppercase tracking-wider w-24">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-gear text-gray-400"></i>
                        Actions
                    </span>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($services)): ?>
                <tr>
                    <td colspan="5" class="py-16 text-center">
                        <div class="flex flex-col items-center gap-3 text-gray-400">
                            <i class="bi bi-briefcase text-5xl"></i>
                            <p class="text-gray-500 font-medium">No services yet</p>
                            <a href="<?= Response::url('/services/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-primary hover:bg-primary/10 rounded-lg transition">Add your first service</a>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($services as $service): ?>
                    <?php
                    $active = (int)($service['is_active'] ?? 0);
                    $toggleFormId = 'toggle-form-' . (int)$service['id'];
                    $deleteFormId = 'delete-form-' . (int)$service['id'];
                    ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition">
                        <td class="py-3.5 px-5 text-sm text-gray-600"><?= (int)$service['id'] ?></td>
                        <td class="py-3.5 px-5 text-sm font-medium text-gray-800"><?= htmlspecialchars($service['name'] ?? '') ?></td>
                        <td class="py-3.5 px-5 text-sm text-gray-600 max-w-xs truncate" title="<?= htmlspecialchars($service['description'] ?? '') ?>"><?= htmlspecialchars(substr($service['description'] ?? '', 0, 100)) ?><?= strlen($service['description'] ?? '') > 100 ? '...' : '' ?></td>
                        <td class="py-3.5 px-5">
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
                        <td class="py-3.5 px-5 text-right">
                            <div class="relative inline-block group">
                                <button type="button" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20 transition" aria-label="Actions" aria-haspopup="true" onclick="var m=this.nextElementSibling; document.querySelectorAll('.service-actions-menu').forEach(function(x){if(x!==m)x.classList.add('hidden');}); m.classList.toggle('hidden');">
                                    <i class="bi bi-three-dots-vertical text-lg"></i>
                                </button>
                                <div class="service-actions-menu hidden absolute right-0 top-full mt-1 py-1 min-w-[140px] bg-white rounded-xl shadow-lg border border-gray-200 z-20">
                                    <a href="<?= Response::url('/services/' . (int)$service['id'] . '/edit') ?>" class="flex items-center gap-2 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition first:rounded-t-xl">
                                        <i class="bi bi-pencil text-gray-500"></i>
                                        Edit
                                    </a>
                                    <button type="button" class="service-delete-action flex items-center gap-2 px-3 py-2.5 text-sm text-red-600 hover:bg-red-50 transition w-full text-left last:rounded-b-xl" data-form-id="<?= $deleteFormId ?>">
                                        <i class="bi bi-trash"></i>
                                        Delete
                                    </button>
                                </div>
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

<dialog id="serviceDeleteDialog" class="rounded-xl shadow-xl border border-gray-200 p-0 w-full max-w-md backdrop:bg-black/30" aria-labelledby="serviceDeleteDialogTitle" aria-describedby="serviceDeleteDialogDesc">
    <div class="p-6">
        <h2 id="serviceDeleteDialogTitle" class="text-lg font-semibold text-gray-800 mb-2">Delete service</h2>
        <p id="serviceDeleteDialogDesc" class="text-sm text-gray-600 mb-6">Are you sure you want to delete this service? This action cannot be undone.</p>
        <div class="flex justify-end gap-3">
            <button type="button" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition" onclick="document.getElementById('serviceDeleteDialog').close();">Cancel</button>
            <button type="button" id="serviceDeleteDialogConfirm" class="px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">Delete</button>
        </div>
    </div>
</dialog>

<script>
(function() {
    var dialog = document.getElementById('serviceDeleteDialog');
    var confirmBtn = document.getElementById('serviceDeleteDialogConfirm');
    document.querySelectorAll('.service-delete-action').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var formId = this.getAttribute('data-form-id');
            if (formId) {
                window.serviceDeleteFormId = formId;
                document.querySelectorAll('.service-actions-menu').forEach(function(m) { m.classList.add('hidden'); });
                if (dialog) dialog.showModal();
            }
        });
    });
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
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.relative.inline-block.group')) {
            document.querySelectorAll('.service-actions-menu').forEach(function(m) { m.classList.add('hidden'); });
        }
    });
    if (typeof URLSearchParams !== 'undefined' && window.location.search) {
        var params = new URLSearchParams(window.location.search);
        if (params.get('deleted') === '1' && window.appToast) {
            appToast({ type: 'success', text: 'Service deleted.' });
            params.delete('deleted');
            var newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            window.history.replaceState({}, '', newUrl);
        }
    }
})();
</script>

<?php
$content = ob_get_clean();
require APP_PATH . '/Views/layouts/admin.php';
?>
