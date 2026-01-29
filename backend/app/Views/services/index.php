<?php
$title = 'Services';
ob_start();
?>

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Services</h1>
    <a href="<?= Response::url('/services/create') ?>" class="px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary-light transition">
        New Service
    </a>
</div>

<section class="bg-white rounded-xl shadow-md px-6 py-5 w-full overflow-x-auto">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-100 text-gray-700 border-b border-gray-200">
                <th class="py-3 px-4 text-left text-sm font-semibold">ID</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Name</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Description</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Status</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($services)): ?>
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500">No hay servicios</td>
                </tr>
            <?php else: ?>
                <?php foreach ($services as $service): ?>
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="py-3 px-4 text-sm"><?= $service['id'] ?></td>
                        <td class="py-3 px-4 text-sm font-medium"><?= htmlspecialchars($service['name'] ?? '') ?></td>
                        <td class="py-3 px-4 text-sm text-gray-600"><?= htmlspecialchars(substr($service['description'] ?? '', 0, 100)) ?><?= strlen($service['description'] ?? '') > 100 ? '...' : '' ?></td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                <?= ($service['is_active'] ?? 0) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                <?= ($service['is_active'] ?? 0) ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <a href="<?= Response::url('/services/' . $service['id'] . '/edit') ?>" class="text-primary hover:text-primary-light text-sm font-medium mr-3">Edit</a>
                            <a href="<?= Response::url('/services/' . $service['id']) ?>" onclick="event.preventDefault(); if(confirm('¿Está seguro de eliminar este servicio?')) { document.getElementById('delete-form-<?= $service['id'] ?>').submit(); }" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</a>
                            <form id="delete-form-<?= $service['id'] ?>" method="POST" action="<?= Response::url('/services/' . $service['id']) ?>" style="display: none;">
                                <input type="hidden" name="_method" value="DELETE">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php
$content = ob_get_clean();
require APP_PATH . '/Views/layouts/admin.php';
?>
