<?php
$title = 'Quotes Report';
$startDate = $filters['start_date'] ?? '';
$endDate = $filters['end_date'] ?? '';
$filterStatus = $filters['status'] ?? '';
$quoteStatusOptions = [
    'draft' => ['label' => 'Draft', 'class' => 'bg-gray-100 text-gray-700', 'icon' => 'bi-file-earmark'],
    'pending' => ['label' => 'Pending', 'class' => 'bg-yellow-100 text-yellow-700', 'icon' => 'bi-clock'],
    'accepted' => ['label' => 'Accepted', 'class' => 'bg-green-100 text-green-700', 'icon' => 'bi-check-circle'],
    'rejected' => ['label' => 'Rejected', 'class' => 'bg-red-100 text-red-700', 'icon' => 'bi-x-circle'],
    'expired' => ['label' => 'Expired', 'class' => 'bg-red-100 text-red-600', 'icon' => 'bi-calendar-x'],
];
ob_start();
?>

<div class="mb-6">
    <div class="flex items-center gap-3">
        <a href="<?= Response::url('/reports') ?>" class="flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-primary transition" title="Back to Reports">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10 text-primary">
            <i class="bi bi-file-earmark-text text-2xl"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Quotes Report</h1>
            <p class="text-sm text-gray-500 mt-0.5"><?= (int)$stats['total'] ?> quotes found</p>
        </div>
    </div>
</div>

<!-- Filters -->
<form method="get" action="<?= Response::url('/reports/quotes') ?>" class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-5">
    <div class="flex flex-wrap items-end gap-4">
        <div class="flex-1 min-w-[150px]">
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">From</label>
            <input type="date" name="start_date" value="<?= htmlspecialchars($startDate) ?>" class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition shadow-sm text-sm">
        </div>
        <div class="flex-1 min-w-[150px]">
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">To</label>
            <input type="date" name="end_date" value="<?= htmlspecialchars($endDate) ?>" class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition shadow-sm text-sm">
        </div>
        <div class="flex-1 min-w-[150px]">
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Status</label>
            <select name="status" class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition shadow-sm text-sm">
                <option value="">All statuses</option>
                <?php foreach ($quoteStatusOptions as $value => $cfg): ?>
                <option value="<?= htmlspecialchars($value) ?>" <?= $filterStatus === $value ? 'selected' : '' ?>><?= htmlspecialchars($cfg['label']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium bg-primary text-white rounded-lg hover:bg-primary-light transition shadow-sm">
                <i class="bi bi-funnel"></i> Filter
            </button>
            <a href="<?= Response::url('/reports/quotes') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 transition border border-gray-200">
                <i class="bi bi-x-lg"></i> Clear
            </a>
        </div>
    </div>
</form>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                <i class="bi bi-file-earmark-text text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Quotes</p>
                <p class="text-2xl font-bold text-gray-800"><?= (int)$stats['total'] ?></p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center">
                <i class="bi bi-currency-dollar text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Amount</p>
                <p class="text-2xl font-bold text-gray-800">$<?= number_format($stats['total_amount'] ?? 0, 2) ?></p>
            </div>
        </div>
    </div>
    <?php
    $accepted = $stats['by_status']['accepted'] ?? 0;
    $pending = $stats['by_status']['pending'] ?? 0;
    ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center">
                <i class="bi bi-clock text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pending</p>
                <p class="text-2xl font-bold text-gray-800"><?= $pending ?></p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center">
                <i class="bi bi-check-circle text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Accepted</p>
                <p class="text-2xl font-bold text-gray-800"><?= $accepted ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Status Breakdown -->
<?php if (!empty($stats['by_status'])): ?>
<div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-5">
    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">Status Breakdown</h3>
    <div class="flex flex-wrap gap-3">
        <?php foreach ($stats['by_status'] as $statusKey => $count): ?>
            <?php $cfg = $quoteStatusOptions[$statusKey] ?? ['label' => ucfirst($statusKey), 'class' => 'bg-gray-100 text-gray-700']; ?>
            <div class="inline-flex items-center gap-2 px-3 py-2 rounded-lg <?= $cfg['class'] ?>">
                <span class="text-sm font-semibold"><?= htmlspecialchars($cfg['label']) ?></span>
                <span class="text-sm font-bold"><?= (int)$count ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Quotes Table -->
<section class="bg-white rounded-xl shadow-sm border border-gray-200 w-full">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-50/80 text-gray-600 border-b border-gray-200">
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <span class="inline-flex items-center gap-2"><i class="bi bi-hash text-gray-400"></i> Number</span>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <span class="inline-flex items-center gap-2"><i class="bi bi-person text-gray-400"></i> Client</span>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <span class="inline-flex items-center gap-2"><i class="bi bi-flag text-gray-400"></i> Status</span>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <span class="inline-flex items-center gap-2"><i class="bi bi-currency-dollar text-gray-400"></i> Amount</span>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <span class="inline-flex items-center gap-2"><i class="bi bi-calendar3 text-gray-400"></i> Date</span>
                </th>
                <th class="py-3.5 px-5 text-right text-xs font-semibold uppercase tracking-wider w-20">
                    <span class="inline-flex items-center gap-2"><i class="bi bi-eye text-gray-400"></i> View</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($quotes)): ?>
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <div class="flex flex-col items-center gap-3 text-gray-400">
                            <i class="bi bi-file-earmark-text text-5xl"></i>
                            <p class="text-gray-500 font-medium">No quotes match the selected filters</p>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($quotes as $quote): ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition">
                        <td class="py-3.5 px-5">
                            <a href="<?= Response::url('/quotes/' . (int)$quote['id']) ?>" class="text-sm font-medium text-primary hover:underline"><?= htmlspecialchars($quote['quote_number'] ?? '') ?></a>
                        </td>
                        <td class="py-3.5 px-5 text-sm text-gray-600"><?= htmlspecialchars($quote['client_name'] ?? 'N/A') ?></td>
                        <td class="py-3.5 px-5">
                            <?php
                            $s = strtolower($quote['status'] ?? 'draft');
                            $sc = $quoteStatusOptions[$s] ?? $quoteStatusOptions['draft'];
                            ?>
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full <?= $sc['class'] ?>"><?= htmlspecialchars($sc['label']) ?></span>
                        </td>
                        <td class="py-3.5 px-5 text-sm text-gray-600">$<?= number_format($quote['total_amount'] ?? 0, 2) ?></td>
                        <td class="py-3.5 px-5 text-sm text-gray-600"><?= isset($quote['created_at']) ? date('d/m/Y', strtotime($quote['created_at'])) : '-' ?></td>
                        <td class="py-3.5 px-5 text-right">
                            <a href="<?= Response::url('/quotes/' . (int)$quote['id']) ?>" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-primary transition inline-flex" title="View quote">
                                <i class="bi bi-eye text-lg"></i>
                            </a>
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
