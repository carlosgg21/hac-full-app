<?php
$title = 'Create Service';
ob_start();
?>

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Create Service</h1>
    <a href="<?= Response::url('/services') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
        Back
    </a>
</div>

<section class="bg-white rounded-xl shadow-md px-6 py-5">
    <form method="POST" action="<?= Response::url('/services') ?>">
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                <input type="text" name="name" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="flex items-center">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                    <span class="text-sm text-gray-700">Active</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="<?= Response::url('/services') ?>" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary-light transition">
                    Create Service
                </button>
            </div>
        </div>
    </form>
</section>

<?php
$content = ob_get_clean();
require APP_PATH . '/Views/layouts/admin.php';
?>
