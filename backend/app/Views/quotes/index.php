<?php
$title = 'Quotes';
ob_start();
?>

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Quotes</h1>
    <a href="<?= Response::url('/quotes/create') ?>" class="px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary-light transition">
        New Quote
    </a>
</div>

<section class="bg-white rounded-xl shadow-md px-6 py-5 w-full overflow-x-auto">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-100 text-gray-700 border-b border-gray-200">
                <th class="py-3 px-4 text-left text-sm font-semibold">Number</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Client</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Status</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Amount</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Date</th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($quotes)): ?>
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-500">No hay cotizaciones</td>
                </tr>
            <?php else: ?>
                <?php foreach ($quotes as $quote): ?>
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="py-3 px-4 text-sm"><?= htmlspecialchars($quote['quote_number'] ?? '') ?></td>
                        <td class="py-3 px-4 text-sm text-gray-600"><?= htmlspecialchars($quote['client_name'] ?? 'N/A') ?></td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                <?php 
                                $status = strtolower($quote['status'] ?? '');
                                if ($status === 'pending') echo 'bg-yellow-100 text-yellow-700';
                                elseif ($status === 'accepted' || $status === 'sent') echo 'bg-green-100 text-green-700';
                                elseif ($status === 'rejected' || $status === 'expired') echo 'bg-red-100 text-red-700';
                                elseif ($status === 'draft') echo 'bg-gray-100 text-gray-700';
                                else echo 'bg-gray-100 text-gray-700';
                                ?>">
                                <?= htmlspecialchars($quote['status']) ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">$<?= number_format($quote['total_amount'] ?? 0, 2) ?></td>
                        <td class="py-3 px-4 text-sm text-gray-600"><?= date('d/m/Y', strtotime($quote['created_at'])) ?></td>
                        <td class="py-3 px-4">
                            <a href="<?= Response::url('/quotes/' . $quote['id']) ?>" class="text-primary hover:text-primary-light text-sm font-medium">View</a>
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