<?php
$title = 'Projects';
ob_start();
?>

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Projects</h1>
</div>

<section class="bg-white rounded-xl shadow-md px-6 py-5 w-full overflow-x-auto">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-100 text-gray-700 border-b border-gray-200">
                <th class="py-3 px-4 text-left text-sm font-semibold">Number</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Name</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Client</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Status</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Progress</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Budget</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($projects)): ?>
                <tr>
                    <td colspan="7" class="py-8 text-center text-gray-500">No hay proyectos</td>
                </tr>
            <?php else: ?>
                <?php foreach ($projects as $project): ?>
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="py-3 px-4 text-sm"><?= htmlspecialchars($project['project_number'] ?? '') ?></td>
                        <td class="py-3 px-4 text-sm"><?= htmlspecialchars($project['name']) ?></td>
                        <td class="py-3 px-4 text-sm text-gray-600"><?= htmlspecialchars($project['client_name'] ?? 'N/A') ?></td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                <?php 
                                $status = strtolower($project['status'] ?? '');
                                if ($status === 'in_progress') echo 'bg-blue-100 text-blue-700';
                                elseif ($status === 'completed') echo 'bg-green-100 text-green-700';
                                elseif ($status === 'cancelled') echo 'bg-red-100 text-red-700';
                                elseif ($status === 'on_hold') echo 'bg-yellow-100 text-yellow-700';
                                elseif ($status === 'planning') echo 'bg-gray-100 text-gray-700';
                                else echo 'bg-gray-100 text-gray-700';
                                ?>">
                                <?= htmlspecialchars($project['status']) ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600"><?= $project['progress'] ?? 0 ?>%</td>
                        <td class="py-3 px-4 text-sm text-gray-600">$<?= number_format($project['budget'] ?? 0, 2) ?></td>
                        <td class="py-3 px-4">
                            <a href="<?= Response::url('/projects/' . $project['id']) ?>" class="text-primary hover:text-primary-light text-sm font-medium">View</a>
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