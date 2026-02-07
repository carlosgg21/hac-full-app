<?php
$title = 'Clients';
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
    'sort' => $sort,
    'order' => $order,
]);
$sortUrl = function($col) use ($baseParams, $sort, $orderNext) {
    $params = $baseParams;
    $params['sort'] = $col;
    $params['order'] = ($sort === $col) ? $orderNext : 'asc';
    $params['page'] = 1;
    $params['per_page'] = $per_page ?? 10;
    return Response::url('/clients?' . http_build_query($params));
};
$pageUrl = function($p, $pp = null) use ($baseParams) {
    $params = array_merge($baseParams, ['page' => $p, 'per_page' => $pp ?? ($per_page ?? 10)]);
    return Response::url('/clients?' . http_build_query($params));
};
ob_start();
?>

<?php $basePath = defined('BASE_PATH_URL') ? BASE_PATH_URL : '/backend'; ?>
<div id="clientListWrap" class="client-list-page" data-api-base="<?= htmlspecialchars($basePath) ?>/api">
<div class="mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10 text-primary">
                <i class="bi bi-people-fill text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Clients</h1>
                <p id="clientsCount" class="text-sm text-gray-500 mt-0.5"><?= $totalCount ?> <?= $totalCount === 1 ? 'contact registered' : 'contacts registered' ?></p>
            </div>
        </div>
        <a href="<?= Response::url('/clients/create') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium bg-primary text-white rounded-lg hover:bg-primary-light transition shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
            <i class="bi bi-person-plus-fill text-base"></i>
            New Client
        </a>
    </div>
</div>

<form method="get" action="<?= Response::url('/clients') ?>" class="mb-6 flex flex-wrap items-end gap-4" id="clientSearchForm">
    <div class="relative max-w-xl flex-1 min-w-[200px]">
        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg pointer-events-none"></i>
        <input type="search" id="clientSearchInput" name="search" value="<?= htmlspecialchars($searchValue) ?>" placeholder="Search by name..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition shadow-sm" autocomplete="off" aria-label="Search clients by name">
        <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
        <input type="hidden" name="order" value="<?= htmlspecialchars($order) ?>">
        <input type="hidden" name="per_page" value="<?= (int)$perPage ?>">
    </div>
    <button type="submit" class="px-4 py-2.5 bg-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-300 transition">Search</button>
</form>

<section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden w-full overflow-x-auto">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-50/80 text-gray-600 border-b border-gray-200">
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <a href="<?= $sortUrl('name') ?>" class="inline-flex items-center gap-2 hover:text-primary transition <?= $sort === 'name' ? 'text-primary' : '' ?>">
                        <i class="bi bi-person text-gray-400"></i>
                        Name
                        <?php if ($sort === 'name'): ?><i class="bi bi-arrow-<?= $order === 'asc' ? 'up' : 'down' ?> text-sm"></i><?php endif; ?>
                    </a>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-envelope text-gray-400"></i>
                        Email
                    </span>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <a href="<?= $sortUrl('phone') ?>" class="inline-flex items-center gap-2 hover:text-primary transition <?= $sort === 'phone' ? 'text-primary' : '' ?>">
                        <i class="bi bi-telephone text-gray-400"></i>
                        Phone
                        <?php if ($sort === 'phone'): ?><i class="bi bi-arrow-<?= $order === 'asc' ? 'up' : 'down' ?> text-sm"></i><?php endif; ?>
                    </a>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <a href="<?= $sortUrl('quotes') ?>" class="inline-flex items-center gap-2 hover:text-primary transition <?= $sort === 'quotes' ? 'text-primary' : '' ?>">
                        <i class="bi bi-file-earmark-text text-gray-400"></i>
                        Quotes
                        <?php if ($sort === 'quotes'): ?><i class="bi bi-arrow-<?= $order === 'asc' ? 'up' : 'down' ?> text-sm"></i><?php endif; ?>
                    </a>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-journal-text text-gray-400"></i>
                        Notes
                    </span>
                </th>
                <th class="py-3.5 px-5 text-right text-xs font-semibold uppercase tracking-wider w-24">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-gear text-gray-400"></i>
                        Actions
                    </span>
                </th>
            </tr>
        </thead>
        <tbody id="clientsTableBody">
            <?php if (empty($clients)): ?>
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <div class="flex flex-col items-center gap-3 text-gray-400">
                            <i class="bi bi-person-lines-fill text-5xl"></i>
                            <p class="text-gray-500 font-medium">No contacts yet</p>
                            <a href="<?= Response::url('/clients/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-primary hover:bg-primary/10 rounded-lg transition">Add your first client</a>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($clients as $client): ?>
                    <?php
                    $fn = $client['first_name'] ?? '';
                    $ln = $client['last_name'] ?? '';
                    $enc = 'UTF-8';
                    if (trim($fn) !== '' && trim($ln) !== '') {
                        $initials = mb_strtoupper(mb_substr($fn, 0, 1, $enc) . mb_substr($ln, 0, 1, $enc), $enc);
                    } else {
                        $full = $client['full_name'] ?? '';
                        $initials = mb_strtoupper(mb_substr($full, 0, 2, $enc), $enc);
                    }
                    $hasNotes = !empty(trim($client['notes'] ?? ''));
                    ?>
                    <?php $quotesCount = (int)($client['quotes_count'] ?? 0); ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition" data-client-id="<?= (int)($client['id'] ?? 0) ?>" data-client-name="<?= htmlspecialchars($client['full_name'] ?? '') ?>">
                        <td class="py-3.5 px-5">
                            <a href="<?= Response::url('/clients/' . (int)($client['id'] ?? 0)) ?>" class="flex items-center gap-3 rounded-lg p-1 -m-1 hover:bg-gray-100/80 transition group">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-semibold group-hover:bg-primary/20 transition">
                                    <?= htmlspecialchars($initials) ?>
                                </div>
                                <span class="text-sm font-medium text-gray-800 group-hover:text-primary transition"><?= htmlspecialchars($client['full_name'] ?? '') ?></span>
                            </a>
                        </td>
                        <td class="py-3.5 px-5">
                            <a href="mailto:<?= htmlspecialchars($client['email'] ?? '') ?>" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-primary transition">
                                <i class="bi bi-envelope text-gray-400 flex-shrink-0"></i>
                                <span class="truncate max-w-[200px]" title="<?= htmlspecialchars($client['email'] ?? '') ?>"><?= htmlspecialchars($client['email'] ?? '—') ?></span>
                            </a>
                        </td>
                        <td class="py-3.5 px-5">
                            <span class="inline-flex items-center gap-2 text-sm text-gray-600">
                                <i class="bi bi-telephone text-gray-400 flex-shrink-0"></i>
                                <span class="truncate max-w-[140px]" title="<?= htmlspecialchars($client['phone'] ?? '') ?>"><?= htmlspecialchars($client['phone'] ?? '—') ?></span>
                            </span>
                        </td>
                        <td class="py-3.5 px-5">
                            <span class="inline-flex items-center justify-center">
                                <span class="inline-flex items-center justify-center min-w-[1.75rem] px-2 py-0.5 text-xs font-semibold rounded-full bg-primary text-white" title="<?= $quotesCount ?> <?= $quotesCount === 1 ? 'quote' : 'quotes' ?>"><?= $quotesCount ?></span>
                            </span>
                        </td>
                        <td class="py-3.5 px-5">
                            <button type="button" class="client-notes-cell p-2 -m-2 rounded-lg cursor-pointer hover:bg-gray-100 transition text-left w-full" data-action="open-notes-modal" aria-label="<?= $hasNotes ? 'View or edit notes' : 'Add notes' ?>">
                                <span class="sr-only client-notes-text"><?= htmlspecialchars($client['notes'] ?? '') ?></span>
                                <?php if ($hasNotes): ?>
                                    <i class="bi bi-file-earmark-check text-green-600 text-lg" title="Has notes"></i>
                                <?php else: ?>
                                    <i class="bi bi-file-earmark text-gray-400 text-lg" title="No notes"></i>
                                <?php endif; ?>
                            </button>
                        </td>
                        <td class="py-3.5 px-5 text-right">
                            <?php $clientDeleteFormId = 'delete-form-client-' . (int)($client['id'] ?? 0); ?>
                            <div class="relative inline-block group">
                                <button type="button" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20 transition" aria-label="Actions" aria-haspopup="true" onclick="var m=this.nextElementSibling; document.querySelectorAll('.client-actions-menu').forEach(function(x){if(x!==m)x.classList.add('hidden');}); m.classList.toggle('hidden');">
                                    <i class="bi bi-three-dots-vertical text-lg"></i>
                                </button>
                                <div class="client-actions-menu hidden absolute right-0 top-full mt-1 py-1 min-w-[140px] bg-white rounded-xl shadow-lg border border-gray-200 z-20">
                                    <a href="<?= Response::url('/clients/' . $client['id']) ?>" class="flex items-center gap-2 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition first:rounded-t-xl">
                                        <i class="bi bi-eye text-gray-500"></i>
                                        Show
                                    </a>
                                    <a href="<?= Response::url('/clients/' . $client['id'] . '/edit') ?>" class="flex items-center gap-2 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                        <i class="bi bi-pencil text-gray-500"></i>
                                        Edit
                                    </a>
                                    <button type="button" class="client-delete-action flex items-center gap-2 px-3 py-2.5 text-sm text-red-600 hover:bg-red-50 transition w-full text-left last:rounded-b-xl" data-form-id="<?= $clientDeleteFormId ?>" data-client-name="<?= htmlspecialchars($client['full_name'] ?? '') ?>">
                                        <i class="bi bi-trash"></i>
                                        Delete
                                    </button>
                                </div>
                            </div>
                            <form id="<?= $clientDeleteFormId ?>" method="POST" action="<?= Response::url('/clients/' . (int)($client['id'])) ?>" style="display: none;">
                                <input type="hidden" name="_method" value="DELETE">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr id="clientListEmptyRow" style="display:none;">
                    <td colspan="6" class="py-16 text-center">
                        <div class="flex flex-col items-center gap-3 text-gray-400">
                            <i class="bi bi-search text-5xl"></i>
                            <p class="text-gray-500 font-medium">No contacts found</p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php if ($totalCount > 0): ?>
<div class="mt-4 flex flex-wrap items-center justify-between gap-4">
    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
        <span>Showing <?= $from ?>–<?= $to ?> of <?= $totalCount ?></span>
        <span class="inline-flex items-center gap-2">
            <label for="per_page_select" class="font-medium">Per page:</label>
            <select id="per_page_select" class="rounded-lg border border-gray-300 px-3 py-1.5 text-gray-700 focus:ring-2 focus:ring-primary/20 focus:border-primary" onchange="window.location.href=this.options[this.selectedIndex].dataset.url">
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
</div>

<dialog id="clientDeleteDialog" class="rounded-xl shadow-xl border border-gray-200 p-0 w-full max-w-md backdrop:bg-black/30" aria-labelledby="clientDeleteDialogTitle" aria-describedby="clientDeleteDialogDesc">
    <div class="p-6">
        <h2 id="clientDeleteDialogTitle" class="text-lg font-semibold text-gray-800 mb-2">Delete client</h2>
        <p id="clientDeleteDialogDesc" class="text-sm text-gray-600 mb-6">Are you sure you want to delete <strong id="clientDeleteDialogName" class="text-gray-800">this client</strong>? This action cannot be undone.</p>
        <div class="flex justify-end gap-3">
            <button type="button" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition" onclick="document.getElementById('clientDeleteDialog').close();">Cancel</button>
            <button type="button" id="clientDeleteDialogConfirm" class="px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">Delete</button>
        </div>
    </div>
</dialog>

<dialog id="clientNotesModal" class="rounded-xl shadow-xl border border-gray-200 p-0 max-w-lg w-full backdrop:bg-black/30" aria-labelledby="clientNotesModalTitle" aria-modal="true">
    <div class="bg-white rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 id="clientNotesModalTitle" class="text-lg font-semibold text-gray-800">Client notes</h2>
        </div>
        <div class="px-6 py-4">
            <textarea id="clientNotesTextarea" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition text-gray-700 placeholder-gray-400" placeholder="Notes about this client..."></textarea>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
            <button type="button" id="clientNotesCancel" class="px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">Cancel</button>
            <button type="button" id="clientNotesClear" class="px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">Clear</button>
            <button type="button" id="clientNotesSave" class="px-4 py-2.5 bg-primary text-white rounded-lg font-medium hover:bg-primary-light transition">Save</button>
        </div>
    </div>
</dialog>

<script src="<?= htmlspecialchars($basePath) ?>/public/assets/js/client.js"></script>
<script>
(function() {
    var dialog = document.getElementById('clientDeleteDialog');
    var confirmBtn = document.getElementById('clientDeleteDialogConfirm');
    document.querySelectorAll('.client-delete-action').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var formId = this.getAttribute('data-form-id');
            var clientName = this.getAttribute('data-client-name') || 'this client';
            if (formId) {
                window.clientDeleteFormId = formId;
                var nameEl = document.getElementById('clientDeleteDialogName');
                if (nameEl) nameEl.textContent = clientName || 'this client';
                document.querySelectorAll('.client-actions-menu').forEach(function(m) { m.classList.add('hidden'); });
                if (dialog) dialog.showModal();
            }
        });
    });
    if (dialog && confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            var formId = window.clientDeleteFormId;
            if (formId) {
                var form = document.getElementById(formId);
                if (form) form.submit();
                window.clientDeleteFormId = null;
            }
            dialog.close();
        });
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
