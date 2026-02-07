<?php
$title = 'Quotes';
$totalCount = (int)($total_count ?? 0);
$totalPages = (int)($total_pages ?? 1);
$page = (int)($page ?? 1);
$perPage = (int)($per_page ?? 10);
$from = $totalCount === 0 ? 0 : ($page - 1) * $perPage + 1;
$to = min($page * $perPage, $totalCount);
$searchValue = $search ?? '';
$sort = $sort ?? 'created_at';
$order = $order ?? 'desc';
$orderNext = $order === 'asc' ? 'desc' : 'asc';
$baseParams = array_filter([
    'search' => $searchValue !== '' ? $searchValue : null,
    'status' => ($status ?? '') !== '' ? $status : null,
    'sort' => $sort,
    'order' => $order,
]);
$sortUrl = function($col) use ($baseParams, $sort, $orderNext, $perPage) {
    $params = $baseParams;
    $params['sort'] = $col;
    $params['order'] = ($sort === $col) ? $orderNext : 'asc';
    $params['page'] = 1;
    $params['per_page'] = $perPage;
    return Response::url('/quotes?' . http_build_query($params));
};
$pageUrl = function($p, $pp = null) use ($baseParams, $perPage) {
    $params = array_merge($baseParams, ['page' => $p, 'per_page' => $pp ?? $perPage]);
    return Response::url('/quotes?' . http_build_query($params));
};
$clearSearchParams = array_filter([
    'status' => ($status ?? '') !== '' ? $status : null,
    'sort' => $sort,
    'order' => $order,
    'per_page' => $perPage,
]);
$clearSearchUrl = Response::url('/quotes?' . http_build_query($clearSearchParams));
$indexSortColumns = [
    'quote_number' => ['label' => 'Number', 'icon' => 'bi-hash'],
    'client_name'  => ['label' => 'Client', 'icon' => 'bi-person'],
    'status'       => ['label' => 'Status', 'icon' => 'bi-flag'],
    'total_amount' => ['label' => 'Amount', 'icon' => 'bi-currency-dollar'],
    'created_at'   => ['label' => 'Date', 'icon' => 'bi-calendar3'],
];
ob_start();
?>

<?php $basePath = defined('BASE_PATH_URL') ? BASE_PATH_URL : '/backend'; ?>
<div class="mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10 text-primary">
                <i class="bi bi-file-earmark-text-fill text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Quotes</h1>
                <p class="text-sm text-gray-500 mt-0.5"><?= $totalCount ?> <?= $totalCount === 1 ? 'quote' : 'quotes' ?></p>
            </div>
        </div>
        <a href="<?= Response::url('/quotes/create') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium bg-primary text-white rounded-lg hover:bg-primary-light transition shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
            <i class="bi bi-plus-lg text-base"></i>
            New Quote
        </a>
    </div>
</div>

<form method="get" action="<?= Response::url('/quotes') ?>" class="mb-6 flex flex-wrap items-end gap-4" id="quoteSearchForm">
    <div class="relative max-w-xl flex-1 min-w-[200px]">
        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg pointer-events-none"></i>
        <input type="search" id="quoteSearchInput" name="search" value="<?= htmlspecialchars($searchValue) ?>" placeholder="Search by quote number or client..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition shadow-sm" autocomplete="off" aria-label="Search quotes">
        <input type="hidden" name="status" value="<?= htmlspecialchars($status ?? '') ?>">
        <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
        <input type="hidden" name="order" value="<?= htmlspecialchars($order) ?>">
        <input type="hidden" name="per_page" value="<?= (int)$perPage ?>">
    </div>
    <button type="submit" class="px-4 py-2.5 bg-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-300 transition">Search</button>
    <?php if ($searchValue !== ''): ?>
    <a href="<?= htmlspecialchars($clearSearchUrl) ?>" class="inline-flex items-center gap-2 px-4 py-2.5 text-gray-600 rounded-xl font-medium hover:bg-gray-100 hover:text-gray-800 transition border border-gray-200" aria-label="Clear search">
        <i class="bi bi-x-lg"></i>
        Clear search
    </a>
    <?php endif; ?>
</form>

<section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden w-full overflow-x-auto">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-50/80 text-gray-600 border-b border-gray-200">
                <?php foreach ($indexSortColumns as $col => $cfg): ?>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <a href="<?= $sortUrl($col) ?>" class="inline-flex items-center gap-2 hover:text-primary transition <?= $sort === $col ? 'text-primary' : '' ?>">
                        <i class="bi <?= $cfg['icon'] ?> text-gray-400"></i>
                        <?= htmlspecialchars($cfg['label']) ?>
                        <?php if ($sort === $col): ?><i class="bi bi-arrow-<?= $order === 'asc' ? 'up' : 'down' ?> text-sm"></i><?php endif; ?>
                    </a>
                </th>
                <?php endforeach; ?>
                <th class="py-3.5 px-5 text-right text-xs font-semibold uppercase tracking-wider w-24">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-gear text-gray-400"></i>
                        Actions
                    </span>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($quotes)): ?>
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <div class="flex flex-col items-center gap-3 text-gray-400">
                            <i class="bi bi-file-earmark-text text-5xl"></i>
                            <p class="text-gray-500 font-medium"><?= $searchValue !== '' ? 'No quotes found' : 'No quotes yet' ?></p>
                            <?php if ($searchValue === ''): ?>
                            <a href="<?= Response::url('/quotes/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-primary hover:bg-primary/10 rounded-lg transition">Create your first quote</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($quotes as $quote): ?>
                    <?php $deleteFormId = 'delete-form-quote-' . (int)$quote['id']; ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition">
                        <td class="py-3.5 px-5">
                            <a href="<?= Response::url('/quotes/' . (int)$quote['id']) ?>" class="text-sm font-medium text-primary hover:underline"><?= htmlspecialchars($quote['quote_number'] ?? '') ?></a>
                        </td>
                        <td class="py-3.5 px-5">
                            <a href="<?= Response::url('/clients/' . (int)($quote['client_id'] ?? 0)) ?>" class="text-sm text-gray-600 hover:text-primary hover:underline"><?= htmlspecialchars($quote['client_name'] ?? 'N/A') ?></a>
                        </td>
                        <td class="py-3.5 px-5">
                            <?php
                            $currentStatus = strtolower($quote['status'] ?? '');
                            $statusConfig = $quoteStatuses[$currentStatus] ?? $quoteStatuses['draft'];
                            $statusClass = $statusConfig['class'];
                            $statusFormId = 'quote-status-form-' . (int)$quote['id'];
                            $statusMenuId = 'quote-status-menu-' . (int)$quote['id'];
                            ?>
                            <div class="relative inline-block quote-status-cell">
                                <button type="button" class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full <?= $statusClass ?> hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary/30 transition" aria-haspopup="true" aria-expanded="false" aria-controls="<?= $statusMenuId ?>" onclick="var m=document.getElementById('<?= $statusMenuId ?>'); document.querySelectorAll('.quote-status-menu').forEach(function(x){if(x!==m)x.classList.add('hidden');}); m.classList.toggle('hidden');">
                                    <span><?= htmlspecialchars($statusConfig['label']) ?></span>
                                    <i class="bi bi-chevron-down text-[10px] opacity-75"></i>
                                </button>
                                <div id="<?= $statusMenuId ?>" class="quote-status-menu hidden absolute left-0 top-full mt-1 py-1 min-w-[140px] bg-white rounded-xl shadow-lg border border-gray-200 z-30" role="menu">
                                    <form id="<?= $statusFormId ?>" method="POST" action="<?= Response::url('/quotes/' . (int)$quote['id']) ?>" class="block">
                                        <input type="hidden" name="_method" value="PUT">
                                        <input type="hidden" name="redirect_to" value="<?= Response::url('/quotes') ?>">
                                        <?php foreach ($quoteStatuses as $value => $cfg): ?>
                                        <button type="submit" name="status" value="<?= htmlspecialchars($value) ?>" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 transition w-full text-left first:rounded-t-xl last:rounded-b-xl <?= $value === $currentStatus ? 'bg-primary/5 text-primary font-medium' : '' ?>" role="menuitem">
                                            <i class="bi <?= $cfg['icon'] ?> w-4 text-center <?= $value === $currentStatus ? 'text-primary' : 'text-gray-400' ?>"></i>
                                            <?= htmlspecialchars($cfg['label']) ?>
                                            <?php if ($value === $currentStatus): ?><i class="bi bi-check ml-auto text-primary"></i><?php endif; ?>
                                        </button>
                                        <?php endforeach; ?>
                                    </form>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-5 text-sm text-gray-600">$<?= number_format($quote['total_amount'] ?? 0, 2) ?></td>
                        <td class="py-3.5 px-5 text-sm text-gray-600"><?= date('d/m/Y', strtotime($quote['created_at'])) ?></td>
                        <td class="py-3.5 px-5 text-right">
                            <div class="relative inline-block group">
                                <button type="button" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20 transition" aria-label="Actions" aria-haspopup="true" onclick="var m=this.nextElementSibling; document.querySelectorAll('.quote-actions-menu').forEach(function(x){if(x!==m)x.classList.add('hidden');}); m.classList.toggle('hidden');">
                                    <i class="bi bi-three-dots-vertical text-lg"></i>
                                </button>
                                <div class="quote-actions-menu hidden absolute right-0 top-full mt-1 py-1 min-w-[140px] bg-white rounded-xl shadow-lg border border-gray-200 z-20">
                                    <a href="<?= Response::url('/quotes/' . $quote['id']) ?>" class="flex items-center gap-2 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition first:rounded-t-xl">
                                        <i class="bi bi-eye text-gray-500"></i>
                                        Show
                                    </a>
                                    <a href="<?= Response::url('/quotes/' . $quote['id'] . '/edit') ?>" class="flex items-center gap-2 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                        <i class="bi bi-pencil text-gray-500"></i>
                                        Edit
                                    </a>
                                    <button type="button" class="quote-delete-action flex items-center gap-2 px-3 py-2.5 text-sm text-red-600 hover:bg-red-50 transition w-full text-left last:rounded-b-xl" data-form-id="<?= $deleteFormId ?>" data-quote-number="<?= htmlspecialchars($quote['quote_number'] ?? '') ?>">
                                        <i class="bi bi-trash"></i>
                                        Delete
                                    </button>
                                </div>
                            </div>
                            <form id="<?= $deleteFormId ?>" method="POST" action="<?= Response::url('/quotes/' . (int)$quote['id']) ?>" style="display: none;">
                                <input type="hidden" name="_method" value="DELETE">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php if ($totalCount > 0): ?>
<div class="mt-4 flex flex-wrap items-center justify-between gap-4">
    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
        <span>Showing <?= $from ?>–<?= $to ?> of <?= $totalCount ?></span>
        <span class="inline-flex items-center gap-2">
            <label for="quote_per_page_select" class="font-medium">Per page:</label>
            <select id="quote_per_page_select" class="rounded-lg border border-gray-300 px-3 py-1.5 text-gray-700 focus:ring-2 focus:ring-primary/20 focus:border-primary" onchange="window.location.href=this.options[this.selectedIndex].dataset.url">
                <option value="10" data-url="<?= htmlspecialchars($pageUrl(1, 10)) ?>" <?= $perPage === 10 ? 'selected' : '' ?>>10</option>
                <option value="15" data-url="<?= htmlspecialchars($pageUrl(1, 15)) ?>" <?= $perPage === 15 ? 'selected' : '' ?>>15</option>
                <option value="25" data-url="<?= htmlspecialchars($pageUrl(1, 25)) ?>" <?= $perPage === 25 ? 'selected' : '' ?>>25</option>
            </select>
        </span>
    </div>
    <div class="flex items-center gap-2">
        <?php if ($page > 1): ?>
            <a href="<?= htmlspecialchars($pageUrl($page - 1)) ?>" class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                <i class="bi bi-chevron-left"></i> Prev
            </a>
        <?php endif; ?>
        <?php if ($page < $totalPages): ?>
            <a href="<?= htmlspecialchars($pageUrl($page + 1)) ?>" class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                Next <i class="bi bi-chevron-right"></i>
            </a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<dialog id="quoteDeleteDialog" class="rounded-xl shadow-xl border border-gray-200 p-0 w-full max-w-md backdrop:bg-black/30" aria-labelledby="quoteDeleteDialogTitle" aria-describedby="quoteDeleteDialogDesc">
    <div class="p-6">
        <h2 id="quoteDeleteDialogTitle" class="text-lg font-semibold text-gray-800 mb-2">Delete quote</h2>
        <p id="quoteDeleteDialogDesc" class="text-sm text-gray-600 mb-6">Are you sure you want to delete <strong id="quoteDeleteDialogNumber" class="text-gray-800">this quote</strong>? This action cannot be undone.</p>
        <div class="flex justify-end gap-3">
            <button type="button" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition" onclick="document.getElementById('quoteDeleteDialog').close();">Cancel</button>
            <button type="button" id="quoteDeleteDialogConfirm" class="px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">Delete</button>
        </div>
    </div>
</dialog>

<script>
(function() {
    var dialog = document.getElementById('quoteDeleteDialog');
    var confirmBtn = document.getElementById('quoteDeleteDialogConfirm');
    document.querySelectorAll('.quote-delete-action').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var formId = this.getAttribute('data-form-id');
            var quoteNumber = this.getAttribute('data-quote-number') || 'this quote';
            if (formId) {
                window.quoteDeleteFormId = formId;
                var numberEl = document.getElementById('quoteDeleteDialogNumber');
                if (numberEl) numberEl.textContent = quoteNumber;
                document.querySelectorAll('.quote-actions-menu').forEach(function(m) { m.classList.add('hidden'); });
                if (dialog) dialog.showModal();
            }
        });
    });
    if (dialog && confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            var formId = window.quoteDeleteFormId;
            if (formId) {
                var form = document.getElementById(formId);
                if (form) form.submit();
                window.quoteDeleteFormId = null;
            }
            dialog.close();
        });
        dialog.addEventListener('click', function(e) {
            if (e.target === dialog) dialog.close();
        });
    }
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.relative.inline-block.group')) {
            document.querySelectorAll('.quote-actions-menu').forEach(function(m) { m.classList.add('hidden'); });
        }
        if (!e.target.closest('.quote-status-cell')) {
            document.querySelectorAll('.quote-status-menu').forEach(function(m) { m.classList.add('hidden'); });
        }
    });
})();
</script>

<?php
$content = ob_get_clean();
require APP_PATH . '/Views/layouts/admin.php';
?>
