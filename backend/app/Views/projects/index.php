<?php
$title = 'Projects';
$projectsCount = is_array($projects) ? count($projects) : 0;
ob_start();
?>

<?php $basePath = defined('BASE_PATH_URL') ? BASE_PATH_URL : '/backend'; ?>
<div class="mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10 text-primary">
                <i class="bi bi-folder-fill text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Projects</h1>
                <p class="text-sm text-gray-500 mt-0.5"><?= $projectsCount ?> <?= $projectsCount === 1 ? 'project' : 'projects' ?></p>
            </div>
        </div>
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
                        <i class="bi bi-tag text-gray-400"></i>
                        Name
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
                        <i class="bi bi-graph-up text-gray-400"></i>
                        Progress
                    </span>
                </th>
                <th class="py-3.5 px-5 text-left text-xs font-semibold uppercase tracking-wider">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-currency-dollar text-gray-400"></i>
                        Budget
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
            <?php if (empty($projects)): ?>
                <tr>
                    <td colspan="7" class="py-16 text-center">
                        <div class="flex flex-col items-center gap-3 text-gray-400">
                            <i class="bi bi-folder text-5xl"></i>
                            <p class="text-gray-500 font-medium">No projects yet</p>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($projects as $project): ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition">
                        <td class="py-3.5 px-5 text-sm font-medium text-gray-800"><?= htmlspecialchars($project['project_number'] ?? '') ?></td>
                        <td class="py-3.5 px-5 text-sm text-gray-800"><?= htmlspecialchars($project['name'] ?? '') ?></td>
                        <td class="py-3.5 px-5 text-sm text-gray-600"><?= htmlspecialchars($project['client_name'] ?? 'N/A') ?></td>
                        <td class="py-3.5 px-5">
                            <?php
                            $status = strtolower($project['status'] ?? '');
                            if ($status === 'in_progress') $statusClass = 'bg-blue-100 text-blue-700';
                            elseif ($status === 'completed') $statusClass = 'bg-green-100 text-green-700';
                            elseif ($status === 'cancelled') $statusClass = 'bg-red-100 text-red-700';
                            elseif ($status === 'on_hold') $statusClass = 'bg-yellow-100 text-yellow-700';
                            elseif ($status === 'planning') $statusClass = 'bg-gray-100 text-gray-700';
                            else $statusClass = 'bg-gray-100 text-gray-700';
                            ?>
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>"><?= htmlspecialchars($project['status'] ?? '') ?></span>
                        </td>
                        <td class="py-3.5 px-5 text-sm text-gray-600"><?= $project['progress'] ?? 0 ?>%</td>
                        <td class="py-3.5 px-5 text-sm text-gray-600">$<?= number_format($project['budget'] ?? 0, 2) ?></td>
                        <td class="py-3.5 px-5 text-right">
                            <div class="relative inline-block group">
                                <button type="button" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20 transition" aria-label="Actions" aria-haspopup="true" onclick="var m=this.nextElementSibling; document.querySelectorAll('.project-actions-menu').forEach(function(x){if(x!==m)x.classList.add('hidden');}); m.classList.toggle('hidden');">
                                    <i class="bi bi-three-dots-vertical text-lg"></i>
                                </button>
                                <div class="project-actions-menu hidden absolute right-0 top-full mt-1 py-1 min-w-[140px] bg-white rounded-xl shadow-lg border border-gray-200 z-20">
                                    <a href="<?= Response::url('/projects/' . $project['id']) ?>" class="flex items-center gap-2 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition rounded-xl">
                                        <i class="bi bi-eye text-gray-500"></i>
                                        Show
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<script>
(function() {
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.relative.inline-block.group')) {
            document.querySelectorAll('.project-actions-menu').forEach(function(m) { m.classList.add('hidden'); });
        }
    });
})();
</script>

<?php
$content = ob_get_clean();
require APP_PATH . '/Views/layouts/admin.php';
?>
