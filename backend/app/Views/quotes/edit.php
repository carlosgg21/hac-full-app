<?php
$title = 'Edit Quote';
$quote = $quote ?? [];
$clients = $clients ?? [];
$quoteStatuses = $quoteStatuses ?? [];
ob_start();
?>

<?php $basePath = defined('BASE_PATH_URL') ? BASE_PATH_URL : '/backend'; ?>
<div class="mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Quote</h1>
            <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($quote['quote_number'] ?? '') ?></p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= Response::url('/quotes') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
                <i class="bi bi-arrow-left"></i>
                Back to list
            </a>
            <a href="<?= Response::url('/quotes/' . (int)($quote['id'] ?? '')) ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
                <i class="bi bi-eye"></i>
                View
            </a>
        </div>
    </div>
</div>

<form method="POST" action="<?= Response::url('/quotes/' . (int)($quote['id'] ?? '')) ?>" id="quoteEditForm">
    <input type="hidden" name="_method" value="PUT">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <section class="bg-white rounded-xl shadow-sm border border-gray-200 px-6 py-5">
                <div class="flex items-center gap-2 mb-5 pb-4 border-b border-gray-200">
                    <i class="bi bi-person text-primary text-xl"></i>
                    <h2 class="text-xl font-semibold text-gray-800">Quote information</h2>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="client_id" class="block text-sm font-semibold text-gray-700 mb-2">Client <span class="text-red-500">*</span></label>
                        <select name="client_id" id="client_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
                            <option value="">Select client</option>
                            <?php foreach ($clients as $c): ?>
                            <option value="<?= (int)$c['id'] ?>" <?= (int)($quote['client_id'] ?? 0) === (int)$c['id'] ? 'selected' : '' ?>><?= htmlspecialchars(trim(($c['first_name'] ?? '') . ' ' . ($c['last_name'] ?? ''))) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
                            <?php foreach ($quoteStatuses as $value => $cfg): ?>
                            <option value="<?= htmlspecialchars($value) ?>" <?= ($quote['status'] ?? '') === $value ? 'selected' : '' ?>><?= htmlspecialchars($cfg['label']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="total_amount" class="block text-sm font-semibold text-gray-700 mb-2">Total amount</label>
                            <input type="number" name="total_amount" id="total_amount" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" value="<?= htmlspecialchars($quote['total_amount'] ?? '0') ?>" placeholder="0.00">
                        </div>
                        <div>
                            <label for="currency" class="block text-sm font-semibold text-gray-700 mb-2">Currency</label>
                            <input type="text" name="currency" id="currency" maxlength="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" value="<?= htmlspecialchars($quote['currency'] ?? 'MXN') ?>" placeholder="MXN">
                        </div>
                    </div>
                    <div>
                        <label for="valid_until" class="block text-sm font-semibold text-gray-700 mb-2">Valid until</label>
                        <input type="date" name="valid_until" id="valid_until" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" value="<?= !empty($quote['valid_until']) ? date('Y-m-d', strtotime($quote['valid_until'])) : '' ?>" placeholder="YYYY-MM-DD">
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-xl shadow-sm border border-gray-200 px-6 py-5">
                <div class="flex items-center gap-2 mb-5 pb-4 border-b border-gray-200">
                    <i class="bi bi-file-text text-primary text-xl"></i>
                    <h2 class="text-xl font-semibold text-gray-800">Notes</h2>
                </div>
                <div>
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" placeholder="Optional notes"><?= htmlspecialchars($quote['notes'] ?? '') ?></textarea>
                </div>
            </section>
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-3">
        <a href="<?= Response::url('/quotes/' . (int)($quote['id'] ?? '')) ?>" class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">Cancel</a>
        <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg font-medium hover:bg-primary-light transition">Update quote</button>
    </div>
</form>

<?php
$content = ob_get_clean();
require APP_PATH . '/Views/layouts/admin.php';
?>
