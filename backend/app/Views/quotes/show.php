<?php
$title = 'Quote';
$quote = $quote ?? [];
$quoteStatuses = $quoteStatuses ?? [];
$currentStatus = strtolower($quote['status'] ?? '');
$statusConfig = $quoteStatuses[$currentStatus] ?? $quoteStatuses['draft'] ?? ['label' => 'Draft', 'class' => 'bg-gray-100 text-gray-700', 'icon' => 'bi-file-earmark'];
ob_start();
?>

<?php $basePath = defined('BASE_PATH_URL') ? BASE_PATH_URL : '/backend'; ?>
<div class="mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($quote['quote_number'] ?? 'Quote') ?></h1>
            <p class="text-sm text-gray-500 mt-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full <?= htmlspecialchars($statusConfig['class']) ?>">
                    <i class="bi <?= htmlspecialchars($statusConfig['icon']) ?>"></i>
                    <?= htmlspecialchars($statusConfig['label']) ?>
                </span>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= Response::url('/quotes') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
                <i class="bi bi-arrow-left"></i>
                Back to list
            </a>
            <a href="<?= Response::url('/quotes/' . (int)($quote['id'] ?? '') . '/edit') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary-light transition">
                <i class="bi bi-pencil"></i>
                Edit
            </a>
            <button type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition" id="quoteShowDeleteBtn" aria-label="Delete quote">
                <i class="bi bi-trash"></i>
                Delete
            </button>
        </div>
    </div>
</div>

<section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
        <h2 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Quote details</h2>
    </div>
    <div class="px-6 py-5 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Client</p>
                <a href="<?= Response::url('/clients/' . (int)($quote['client_id'] ?? '')) ?>" class="text-primary font-medium hover:underline"><?= htmlspecialchars($quote['client_name'] ?? 'N/A') ?></a>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</p>
                <p class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($quote['currency'] ?? 'MXN') ?> <?= number_format($quote['total_amount'] ?? 0, 2) ?></p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Valid until</p>
                <p class="text-gray-800"><?= !empty($quote['valid_until']) ? date('d/m/Y', strtotime($quote['valid_until'])) : '—' ?></p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</p>
                <p class="text-gray-800"><?= !empty($quote['created_at']) ? date('d/m/Y H:i', strtotime($quote['created_at'])) : '—' ?></p>
            </div>
        </div>
        <?php if (!empty(trim($quote['notes'] ?? ''))): ?>
        <div class="pt-4 border-t border-gray-100">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Notes</p>
            <p class="text-gray-700 whitespace-pre-wrap"><?= htmlspecialchars($quote['notes']) ?></p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php if (!empty($quote['answers']) && is_array($quote['answers'])): ?>
<section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
        <h2 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Questionnaire answers</h2>
    </div>
    <div class="px-6 py-5">
        <ul class="space-y-3">
            <?php foreach ($quote['answers'] as $answer): ?>
            <li class="flex flex-col sm:flex-row sm:items-baseline gap-1 sm:gap-3 py-2 border-b border-gray-100 last:border-0">
                <span class="text-sm font-medium text-gray-700 sm:min-w-[200px]"><?= htmlspecialchars($answer['question_text'] ?? '') ?></span>
                <span class="text-sm text-gray-800">
                    <?php
                    if (!empty($answer['answer_text'])) echo htmlspecialchars($answer['answer_text']);
                    elseif (isset($answer['answer_value'])) echo htmlspecialchars($answer['answer_value']);
                    elseif (!empty($answer['answer_json'])) echo htmlspecialchars(is_string($answer['answer_json']) ? $answer['answer_json'] : json_encode($answer['answer_json']));
                    else echo '—';
                    ?>
                </span>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
<?php endif; ?>

<form id="quoteShowDeleteForm" method="POST" action="<?= Response::url('/quotes/' . (int)($quote['id'] ?? '')) ?>" style="display: none;">
    <input type="hidden" name="_method" value="DELETE">
</form>

<dialog id="quoteShowDeleteDialog" class="rounded-xl shadow-xl border border-gray-200 p-0 w-full max-w-md backdrop:bg-black/30" aria-labelledby="quoteShowDeleteDialogTitle" aria-describedby="quoteShowDeleteDialogDesc">
    <div class="p-6">
        <h2 id="quoteShowDeleteDialogTitle" class="text-lg font-semibold text-gray-800 mb-2">Delete quote</h2>
        <p id="quoteShowDeleteDialogDesc" class="text-sm text-gray-600 mb-6">Are you sure you want to delete this quote? This action cannot be undone.</p>
        <div class="flex justify-end gap-3">
            <button type="button" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition" onclick="document.getElementById('quoteShowDeleteDialog').close();">Cancel</button>
            <button type="button" id="quoteShowDeleteDialogConfirm" class="px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">Delete</button>
        </div>
    </div>
</dialog>

<script>
(function() {
    var dialog = document.getElementById('quoteShowDeleteDialog');
    var confirmBtn = document.getElementById('quoteShowDeleteDialogConfirm');
    var deleteBtn = document.getElementById('quoteShowDeleteBtn');
    var form = document.getElementById('quoteShowDeleteForm');
    if (deleteBtn && dialog) {
        deleteBtn.addEventListener('click', function() { dialog.showModal(); });
    }
    if (confirmBtn && form) {
        confirmBtn.addEventListener('click', function() {
            form.submit();
            dialog.close();
        });
    }
    if (dialog) {
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
