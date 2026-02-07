<?php
$title = 'Quotes';
$quotesCount = is_array($quotes) ? count($quotes) : 0;
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
                <p class="text-sm text-gray-500 mt-0.5"><?= $quotesCount ?> <?= $quotesCount === 1 ? 'quote' : 'quotes' ?></p>
            </div>
        </div>
        <a href="<?= Response::url('/quotes/create') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium bg-primary text-white rounded-lg hover:bg-primary-light transition shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
            <i class="bi bi-plus-lg text-base"></i>
            New Quote
        </a>
    </div>
</div>

<section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden w-full overflow-x-auto">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-50/80 text-gray-600 border-b border-gray-200">
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-hash text-gray-400"></i>
                        Number
                    </span>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-person text-gray-400"></i>
                        Client
                    </span>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-flag text-gray-400"></i>
                        Status
                    </span>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-currency-dollar text-gray-400"></i>
                        Amount
                    </span>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-calendar3 text-gray-400"></i>
                        Date
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
        <tbody>
            <?php if (empty($quotes)): ?>
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <div class="flex flex-col items-center gap-3 text-gray-400">
                            <i class="bi bi-file-earmark-text text-5xl"></i>
                            <p class="text-gray-500 font-medium">No quotes yet</p>
                            <a href="<?= Response::url('/quotes/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-primary hover:bg-primary/10 rounded-lg transition">Create your first quote</a>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($quotes as $quote): ?>
                    <?php $deleteFormId = 'delete-form-quote-' . (int)$quote['id']; ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition">
                        <td class="py-3.5 px-5 text-sm font-medium text-gray-800"><?= htmlspecialchars($quote['quote_number'] ?? '') ?></td>
                        <td class="py-3.5 px-5 text-sm text-gray-600"><?= htmlspecialchars($quote['client_name'] ?? 'N/A') ?></td>
                        <td class="py-3.5 px-5">
                            <?php
                            $status = strtolower($quote['status'] ?? '');
                            if ($status === 'pending') $statusClass = 'bg-yellow-100 text-yellow-700';
                            elseif ($status === 'accepted' || $status === 'sent') $statusClass = 'bg-green-100 text-green-700';
                            elseif ($status === 'rejected' || $status === 'expired') $statusClass = 'bg-red-100 text-red-700';
                            elseif ($status === 'draft') $statusClass = 'bg-gray-100 text-gray-700';
                            else $statusClass = 'bg-gray-100 text-gray-700';
                            ?>
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>"><?= htmlspecialchars($quote['status']) ?></span>
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
                                    <button type="button" class="quote-delete-action flex items-center gap-2 px-3 py-2.5 text-sm text-red-600 hover:bg-red-50 transition w-full text-left last:rounded-b-xl" data-form-id="<?= $deleteFormId ?>">
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

<dialog id="quoteDeleteDialog" class="rounded-xl shadow-xl border border-gray-200 p-0 w-full max-w-md backdrop:bg-black/30" aria-labelledby="quoteDeleteDialogTitle" aria-describedby="quoteDeleteDialogDesc">
    <div class="p-6">
        <h2 id="quoteDeleteDialogTitle" class="text-lg font-semibold text-gray-800 mb-2">Delete quote</h2>
        <p id="quoteDeleteDialogDesc" class="text-sm text-gray-600 mb-6">Are you sure you want to delete this quote? This action cannot be undone.</p>
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
            if (formId) {
                window.quoteDeleteFormId = formId;
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
    });
    if (typeof URLSearchParams !== 'undefined' && window.location.search) {
        var params = new URLSearchParams(window.location.search);
        if (params.get('deleted') === '1' && window.appToast) {
            appToast({ type: 'success', text: 'Quote deleted.' });
            params.delete('deleted');
            var newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            window.history.replaceState({}, '', newUrl);
        }
    }
})();
</script>

<?php
$content = ob_get_clean();
require APP_PATH . '/Views/layouts/admin.php';
?>
