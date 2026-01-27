<?php
$title = 'Clientes';
ob_start();
?>

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Client List</h1>
    <a href="<?= Response::url('/clients/create') ?>" class="px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary-light transition">
        Add Client
    </a>
</div>

<section class="bg-white rounded-xl shadow-md px-6 py-5 w-full overflow-x-auto">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-100 text-gray-700 border-b border-gray-200">
                <th class="py-3 px-4 text-left text-sm font-semibold">Name</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Email</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Phone</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($clients)): ?>
                <tr>
                    <td colspan="4" class="py-8 text-center text-gray-500">No hay clientes</td>
                </tr>
            <?php else: ?>
                <?php foreach ($clients as $client): ?>
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="py-3 px-4 text-sm"><?= htmlspecialchars($client['full_name'] ?? '') ?></td>
                        <td class="py-3 px-4 text-sm text-gray-600"><?= htmlspecialchars($client['email'] ?? '') ?></td>
                        <td class="py-3 px-4 text-sm text-gray-600"><?= htmlspecialchars($client['phone'] ?? '') ?></td>
                        <td class="py-3 px-4">
                            <a href="<?= Response::url('/clients/' . $client['id']) ?>" class="text-primary hover:text-primary-light text-sm font-medium">View</a>
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
