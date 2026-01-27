<?php
$title = 'Preguntas';
ob_start();
?>

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Questions</h1>
    <a href="<?= Response::url('/questions/create') ?>" class="px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary-light transition">
        New Question
    </a>
</div>

<section class="bg-white rounded-xl shadow-md px-6 py-5 w-full overflow-x-auto">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-100 text-gray-700 border-b border-gray-200">
                <th class="py-3 px-4 text-left text-sm font-semibold">ID</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Service</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Question</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Type</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Order</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Status</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($questions)): ?>
                <tr>
                    <td colspan="7" class="py-8 text-center text-gray-500">No hay preguntas</td>
                </tr>
            <?php else: ?>
                <?php foreach ($questions as $question): ?>
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="py-3 px-4 text-sm"><?= $question['id'] ?></td>
                        <td class="py-3 px-4 text-sm text-gray-600"><?= htmlspecialchars($question['service_name'] ?? $question['category'] ?? 'N/A') ?></td>
                        <td class="py-3 px-4 text-sm"><?= htmlspecialchars($question['question_text'] ?? '') ?></td>
                        <td class="py-3 px-4 text-sm text-gray-600"><?= htmlspecialchars($question['question_type'] ?? '') ?></td>
                        <td class="py-3 px-4 text-sm text-gray-600"><?= $question['order'] ?? 0 ?></td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                <?= ($question['is_active'] ?? 0) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                <?= ($question['is_active'] ?? 0) ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <a href="<?= Response::url('/questions/' . $question['id'] . '/edit') ?>" class="text-primary hover:text-primary-light text-sm font-medium mr-3">Edit</a>
                            <a href="<?= Response::url('/questions/' . $question['id']) ?>" onclick="return confirm('¿Está seguro de eliminar esta pregunta?')" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</a>
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
