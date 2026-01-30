<?php
$title = 'Client';
$client = $client ?? [];
$quotes = $quotes ?? [];
ob_start();
?>

<?php $basePath = defined('BASE_PATH_URL') ? BASE_PATH_URL : '/backend'; ?>
<div class="mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Client</h1>
            <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($client['full_name'] ?? '') ?></p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= Response::url('/clients') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
                <i class="bi bi-arrow-left"></i>
                Back to list
            </a>
            <a href="<?= Response::url('/clients/' . ($client['id'] ?? '') . '/edit') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary-light transition">
                <i class="bi bi-pencil"></i>
                Edit
            </a>
        </div>
    </div>
</div>

<!-- Bill to / Invoice-style client info -->
<section class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden mb-6">
    <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
        <h2 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Contact Information</h2>
    </div>
    <div class="px-6 py-5 space-y-2 font-medium text-gray-800">
        <p class="text-lg"><?= htmlspecialchars($client['full_name'] ?? '') ?></p>
        <p class="text-sm text-gray-600">
            <i class="bi bi-envelope text-gray-400 mr-1"></i>
            <?= htmlspecialchars($client['email'] ?? '') ?>
        </p>
        <p class="text-sm text-gray-600">
            <i class="bi bi-telephone text-gray-400 mr-1"></i>
            <?= htmlspecialchars($client['phone'] ?? '—') ?>
        </p>
        <p class="text-sm text-gray-600 flex items-start gap-2">
            <i class="bi bi-geo-alt text-gray-400 flex-shrink-0 mt-0.5" aria-hidden="true"></i>
            <span class="whitespace-pre-line"><?= htmlspecialchars($client['full_address'] ?? '—') ?></span>
        </p>
    </div>
</section>

<!-- Quotes list -->
<section class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">
            <i class="bi bi-receipt text-primary mr-2"></i>
            Quotes
        </h2>
    </div>
    <?php if (empty($quotes)): ?>
        <div class="px-6 py-12 text-center text-gray-500">
            <i class="bi bi-inbox text-4xl block mb-2 text-gray-300"></i>
            No quotes
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 border-b border-gray-200">
                        <th class="py-3 px-4 text-left text-sm font-semibold">Quote #</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Status</th>
                        <th class="py-3 px-4 text-right text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quotes as $quote): ?>
                        <?php
                        $status = strtolower($quote['status'] ?? '');
                        $statusClass = 'bg-gray-100 text-gray-700';
                        if ($status === 'pending') $statusClass = 'bg-yellow-100 text-yellow-700';
                        elseif ($status === 'accepted' || $status === 'sent') $statusClass = 'bg-green-100 text-green-700';
                        elseif ($status === 'rejected' || $status === 'expired') $statusClass = 'bg-red-100 text-red-700';
                        elseif ($status === 'draft') $statusClass = 'bg-gray-100 text-gray-700';
                        ?>
                        <tr class="border-b border-gray-100 hover:bg-gray-50/80 transition">
                            <td class="py-3 px-4">
                                <a href="<?= Response::url('/quotes/' . ($quote['id'] ?? '')) ?>" class="text-primary font-medium hover:underline">
                                    <?= htmlspecialchars($quote['quote_number'] ?? '') ?>
                                </a>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>">
                                    <?= htmlspecialchars($quote['status'] ?? '') ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="<?= Response::url('/quotes/' . ($quote['id'] ?? '')) ?>" class="text-sm text-primary hover:underline">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php if (!empty(trim($client['notes'] ?? ''))): ?>
<section class="bg-white rounded-xl shadow-md px-6 py-5 mb-6 border border-gray-200">
    <div class="flex items-center gap-2 mb-3 pb-2 border-b border-gray-200">
        <i class="bi bi-file-text text-primary text-xl"></i>
        <h2 class="text-lg font-semibold text-gray-800">Notes</h2>
    </div>
    <p class="text-sm text-gray-700 whitespace-pre-wrap"><?= htmlspecialchars($client['notes']) ?></p>
</section>
<?php endif; ?>


<?php
$content = ob_get_clean();
require APP_PATH . '/Views/layouts/admin.php';
?>
