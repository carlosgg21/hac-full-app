<?php
$title = 'Questions';
$questionsCount = is_array($questions) ? count($questions) : 0;
$f = $filters ?? [];
$currentSort = $f['sort'] ?? 'id';
$currentDir = $f['dir'] ?? 'desc';
$currentServiceId = $f['service_id'] ?? '';
$currentSearch = $f['search'] ?? '';

// Helper para generar URL de sort
$sortUrl = function($col) use ($currentSort, $currentDir, $currentServiceId, $currentSearch) {
    $dir = ($currentSort === $col && $currentDir === 'asc') ? 'desc' : 'asc';
    $params = ['sort' => $col, 'dir' => $dir];
    if ($currentServiceId) $params['service_id'] = $currentServiceId;
    if ($currentSearch) $params['search'] = $currentSearch;
    return Response::url('/questions') . '?' . http_build_query($params);
};

$sortIcon = function($col) use ($currentSort, $currentDir) {
    if ($currentSort !== $col) return '<i class="bi bi-chevron-expand text-gray-300 text-xs ml-1"></i>';
    $icon = $currentDir === 'asc' ? 'bi-chevron-up' : 'bi-chevron-down';
    return '<i class="bi ' . $icon . ' text-primary text-xs ml-1"></i>';
};

ob_start();
?>

<div class="mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10 text-primary">
                <i class="bi bi-question-circle-fill text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Questions</h1>
                <p class="text-sm text-gray-500 mt-0.5"><?= $questionsCount ?> <?= $questionsCount === 1 ? 'question' : 'questions' ?></p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <?php $hasFilters = $currentServiceId || $currentSearch; ?>
            <button type="button" id="toggleFilters" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium <?= $hasFilters ? 'bg-primary/10 text-primary border-primary/30' : 'text-gray-600 border-gray-200' ?> rounded-lg hover:bg-gray-100 transition border">
                <i class="bi bi-funnel"></i>
                Filters
                <?php if ($hasFilters): ?>
                    <span class="w-2 h-2 rounded-full bg-primary"></span>
                <?php endif; ?>
            </button>
            <a href="<?= Response::url('/questions/create') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium bg-primary text-white rounded-lg hover:bg-primary-light transition shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                <i class="bi bi-plus-lg text-base"></i>
                New Question
            </a>
        </div>
    </div>
</div>

<!-- Filters Panel (collapsible) -->
<form method="get" action="<?= Response::url('/questions') ?>" id="filtersPanel" class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-5 <?= $hasFilters ? '' : 'hidden' ?>">
    <div class="flex flex-wrap items-end gap-4">
        <div class="flex-1 min-w-[180px]">
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Service</label>
            <select name="service_id" class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition shadow-sm text-sm">
                <option value="">All services</option>
                <?php foreach ($services as $s): ?>
                <option value="<?= (int)$s['id'] ?>" <?= $currentServiceId == $s['id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Search</label>
            <input type="text" name="search" value="<?= htmlspecialchars($currentSearch) ?>" placeholder="Search question text..." class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition shadow-sm text-sm">
        </div>
        <?php if ($currentSort !== 'id' || $currentDir !== 'desc'): ?>
        <input type="hidden" name="sort" value="<?= htmlspecialchars($currentSort) ?>">
        <input type="hidden" name="dir" value="<?= htmlspecialchars($currentDir) ?>">
        <?php endif; ?>
        <div class="flex items-center gap-2">
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium bg-primary text-white rounded-lg hover:bg-primary-light transition shadow-sm">
                <i class="bi bi-search"></i> Apply
            </button>
            <a href="<?= Response::url('/questions') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 transition border border-gray-200">
                <i class="bi bi-x-lg"></i> Clear
            </a>
        </div>
    </div>
</form>

<section class="bg-white rounded-xl shadow-sm border border-gray-200 w-full">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-50/80 text-gray-600 border-b border-gray-200">
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <a href="<?= $sortUrl('id') ?>" class="inline-flex items-center gap-1 hover:text-primary transition">
                        <i class="bi bi-hash text-gray-400"></i> ID <?= $sortIcon('id') ?>
                    </a>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <a href="<?= $sortUrl('service_name') ?>" class="inline-flex items-center gap-1 hover:text-primary transition">
                        <i class="bi bi-briefcase text-gray-400"></i> Service <?= $sortIcon('service_name') ?>
                    </a>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <a href="<?= $sortUrl('question_text') ?>" class="inline-flex items-center gap-1 hover:text-primary transition">
                        <i class="bi bi-chat-text text-gray-400"></i> Question <?= $sortIcon('question_text') ?>
                    </a>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <a href="<?= $sortUrl('question_type') ?>" class="inline-flex items-center gap-1 hover:text-primary transition">
                        <i class="bi bi-list-ul text-gray-400"></i> Type <?= $sortIcon('question_type') ?>
                    </a>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <a href="<?= $sortUrl('order') ?>" class="inline-flex items-center gap-1 hover:text-primary transition">
                        <i class="bi bi-sort-numeric-down text-gray-400"></i> Order <?= $sortIcon('order') ?>
                    </a>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <a href="<?= $sortUrl('is_active') ?>" class="inline-flex items-center gap-1 hover:text-primary transition">
                        <i class="bi bi-toggle-on text-gray-400"></i> Status <?= $sortIcon('is_active') ?>
                    </a>
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
            <?php if (empty($questions)): ?>
                <tr>
                    <td colspan="7" class="py-16 text-center">
                        <div class="flex flex-col items-center gap-3 text-gray-400">
                            <i class="bi bi-question-circle text-5xl"></i>
                            <p class="text-gray-500 font-medium">No questions found</p>
                            <?php if ($currentServiceId || $currentSearch): ?>
                                <a href="<?= Response::url('/questions') ?>" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-primary hover:bg-primary/10 rounded-lg transition">Clear filters</a>
                            <?php else: ?>
                                <a href="<?= Response::url('/questions/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-primary hover:bg-primary/10 rounded-lg transition">Add your first question</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($questions as $question): ?>
                    <?php $deleteFormId = 'delete-form-question-' . (int)$question['id']; ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition">
                        <td class="py-3.5 px-5 text-sm text-gray-600"><?= $question['id'] ?></td>
                        <td class="py-3.5 px-5 text-sm text-gray-600"><?= htmlspecialchars($question['service_name'] ?? 'N/A') ?></td>
                        <td class="py-3.5 px-5 text-sm font-medium text-gray-800"><?= htmlspecialchars($question['question_text'] ?? '') ?></td>
                        <td class="py-3.5 px-5 text-sm text-gray-600"><?= htmlspecialchars($question['question_type'] ?? '') ?></td>
                        <td class="py-3.5 px-5 text-sm text-gray-600"><?= $question['order'] ?? 0 ?></td>
                        <td class="py-3.5 px-5">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full <?= ($question['is_active'] ?? 0) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                <?= ($question['is_active'] ?? 0) ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-right">
                            <div class="relative inline-block group">
                                <button type="button" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20 transition" aria-label="Actions" aria-haspopup="true">
                                    <i class="bi bi-three-dots-vertical text-lg"></i>
                                </button>
                                <div class="question-actions-menu hidden absolute right-0 py-1 min-w-[140px] bg-white rounded-xl shadow-lg border border-gray-200 z-20">
                                    <a href="<?= Response::url('/questions/' . $question['id'] . '/edit') ?>" class="flex items-center gap-2 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition first:rounded-t-xl">
                                        <i class="bi bi-pencil text-gray-500"></i>
                                        Edit
                                    </a>
                                    <button type="button" class="question-delete-action flex items-center gap-2 px-3 py-2.5 text-sm text-red-600 hover:bg-red-50 transition w-full text-left last:rounded-b-xl" data-form-id="<?= $deleteFormId ?>">
                                        <i class="bi bi-trash"></i>
                                        Delete
                                    </button>
                                </div>
                            </div>
                            <form id="<?= $deleteFormId ?>" method="POST" action="<?= Response::url('/questions/' . (int)$question['id']) ?>" style="display: none;">
                                <input type="hidden" name="_method" value="DELETE">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<dialog id="questionDeleteDialog" class="rounded-xl shadow-xl border border-gray-200 p-0 w-full max-w-md backdrop:bg-black/30" aria-labelledby="questionDeleteDialogTitle" aria-describedby="questionDeleteDialogDesc">
    <div class="p-6">
        <h2 id="questionDeleteDialogTitle" class="text-lg font-semibold text-gray-800 mb-2">Delete question</h2>
        <p id="questionDeleteDialogDesc" class="text-sm text-gray-600 mb-6">Are you sure you want to delete this question?</p>
        <div class="flex justify-end gap-3">
            <button type="button" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition" onclick="document.getElementById('questionDeleteDialog').close();">Cancel</button>
            <button type="button" id="questionDeleteDialogConfirm" class="px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">Delete</button>
        </div>
    </div>
</dialog>

<script>
(function() {
    var dialog = document.getElementById('questionDeleteDialog');
    var confirmBtn = document.getElementById('questionDeleteDialogConfirm');
    document.querySelectorAll('.question-delete-action').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var formId = this.getAttribute('data-form-id');
            if (formId) {
                window.questionDeleteFormId = formId;
                document.querySelectorAll('.question-actions-menu').forEach(function(m) { m.classList.add('hidden'); });
                if (dialog) dialog.showModal();
            }
        });
    });
    if (dialog && confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            var formId = window.questionDeleteFormId;
            if (formId) {
                var form = document.getElementById(formId);
                if (form) form.submit();
                window.questionDeleteFormId = null;
            }
            dialog.close();
        });
        dialog.addEventListener('click', function(e) {
            if (e.target === dialog) dialog.close();
        });
    }
    // Toggle filters panel
    var toggleBtn = document.getElementById('toggleFilters');
    var panel = document.getElementById('filtersPanel');
    if (toggleBtn && panel) {
        toggleBtn.addEventListener('click', function() {
            panel.classList.toggle('hidden');
        });
    }

    if (typeof URLSearchParams !== 'undefined' && window.location.search) {
        var params = new URLSearchParams(window.location.search);
        if (params.get('deleted') === '1' && window.appToast) {
            appToast({ type: 'success', text: 'Question deleted.' });
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
