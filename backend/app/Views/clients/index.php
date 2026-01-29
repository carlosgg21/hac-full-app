<?php
$title = 'Clientes';
$clientsCount = is_array($clients) ? count($clients) : 0;
$searchValue = $search ?? '';
ob_start();
?>

<?php $basePath = defined('BASE_PATH_URL') ? BASE_PATH_URL : '/backend'; ?>
<div id="clientListWrap" class="client-list-page" data-api-url="<?= htmlspecialchars(Response::url('/api/clients')) ?>" data-base-url="<?= htmlspecialchars(rtrim(Response::url(''), '/')) ?>">
<div class="mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-gray-100 text-primary">
                <i class="bi bi-people text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Contacts</h1>
                <p id="clientsCount" class="text-sm text-gray-500 mt-0.5"><?= $clientsCount ?> <?= $clientsCount === 1 ? 'contact registered' : 'contacts registered' ?></p>
            </div>
        </div>
        <a href="<?= Response::url('/clients/create') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 text-white rounded-lg font-medium hover:bg-gray-800 transition shadow-sm">
            <i class="bi bi-person-plus text-lg"></i>
            New Contact
        </a>
    </div>
</div>

<form method="get" action="<?= Response::url('/clients') ?>" class="mb-6" id="clientSearchForm">
    <div class="relative max-w-xl">
        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
        <input type="search" id="clientSearchInput" name="search" value="<?= htmlspecialchars($searchValue) ?>" placeholder="Search by name or email..." class="w-full pl-10 pr-4 py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" autocomplete="off">
    </div>
</form>

<section class="bg-white rounded-xl shadow-md overflow-hidden w-full overflow-x-auto">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-50 text-gray-700 border-b border-gray-200">
                <th class="py-3 px-4 text-left text-sm font-semibold">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-person text-gray-500"></i>
                        Name
                    </span>
                </th>
                <th class="py-3 px-4 text-left text-sm font-semibold">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-envelope text-gray-500"></i>
                        Email
                    </span>
                </th>
                <th class="py-3 px-4 text-left text-sm font-semibold">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-telephone text-gray-500"></i>
                        Phone
                    </span>
                </th>
                <th class="py-3 px-4 text-left text-sm font-semibold">
                    <span class="inline-flex items-center gap-2">
                        <i class="bi bi-file-text text-gray-500"></i>
                        Notes
                    </span>
                </th>
                <th class="py-3 px-4 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody id="clientsTableBody">
            <?php if (empty($clients)): ?>
                <tr>
                    <td colspan="5" class="py-12 text-center text-gray-500">
                        <i class="bi bi-inbox text-4xl block mb-2 text-gray-300"></i>
                        No contacts found
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($clients as $client): ?>
                    <?php
                    $fn = $client['first_name'] ?? '';
                    $ln = $client['last_name'] ?? '';
                    $initials = trim($fn) !== '' && trim($ln) !== ''
                        ? strtoupper(substr($fn, 0, 1) . substr($ln, 0, 1))
                        : strtoupper(substr($client['full_name'] ?? '', 0, 2));
                    $hasNotes = !empty(trim($client['notes'] ?? ''));
                    ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50/80 transition">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-sm font-medium">
                                    <?= htmlspecialchars($initials) ?>
                                </div>
                                <span class="text-sm font-medium text-gray-800"><?= htmlspecialchars($client['full_name'] ?? '') ?></span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center gap-2 text-sm text-gray-600">
                                <i class="bi bi-envelope text-gray-400"></i>
                                <?= htmlspecialchars($client['email'] ?? '') ?>
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center gap-2 text-sm text-gray-600">
                                <i class="bi bi-telephone text-gray-400"></i>
                                <?= htmlspecialchars($client['phone'] ?? '—') ?>
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <?php if ($hasNotes): ?>
                                <span class="inline-flex items-center gap-2 text-sm text-green-700">
                                    <i class="bi bi-file-earmark-check text-green-600"></i>
                                    Has notes
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-2 text-sm text-gray-400">
                                    <i class="bi bi-file-earmark text-gray-400"></i>
                                    No notes
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4">
                            <div class="relative inline-block group">
                                <button type="button" class="p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20" aria-label="Actions" onclick="this.nextElementSibling.classList.toggle('hidden')">
                                    <i class="bi bi-three-dots-vertical text-lg"></i>
                                </button>
                                <div class="client-actions-menu hidden absolute right-0 top-full mt-1 py-1 w-44 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                    <a href="<?= Response::url('/clients/' . $client['id'] . '/edit') ?>" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-t-lg">
                                        <i class="bi bi-pencil"></i>
                                        Edit
                                    </a>
                                    <a href="<?= Response::url('/clients/' . $client['id']) ?>" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-b-lg">
                                        <i class="bi bi-receipt"></i>
                                        Show Quote
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
</div>

<script src="<?= htmlspecialchars($basePath) ?>/public/assets/js/client.js"></script>

<?php
$content = ob_get_clean();
require APP_PATH . '/Views/layouts/admin.php';
?>
