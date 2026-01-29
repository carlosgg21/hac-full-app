<?php
$title = 'Create Question';
ob_start();
?>

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Create Question</h1>
    <a href="<?= Response::url('/questions') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
        Back
    </a>
</div>

<section class="bg-white rounded-xl shadow-md px-6 py-5">
    <form method="POST" action="<?= Response::url('/questions') ?>">
        <div class="space-y-4">
            <div>
                <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">Service *</label>
                <select name="service_id" id="service_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Select a service</option>
                    <?php foreach ($services as $service): ?>
                        <option value="<?= $service['id'] ?>"><?= htmlspecialchars($service['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="question_text" class="block text-sm font-medium text-gray-700 mb-1">Question Text *</label>
                <textarea name="question_text" id="question_text" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?= htmlspecialchars($_POST['question_text'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="question_type" class="block text-sm font-medium text-gray-700 mb-1">Question Type *</label>
                <select name="question_type" id="question_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="text">Text</option>
                    <option value="number">Number</option>
                    <option value="select">Select</option>
                    <option value="radio">Radio</option>
                    <option value="checkbox">Checkbox</option>
                    <option value="textarea">Textarea</option>
                </select>
            </div>

            <div id="options-container" style="display: none;">
                <label for="options" class="block text-sm font-medium text-gray-700 mb-1">Options (one per line)</label>
                <textarea name="options" id="options" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
            </div>

            <div>
                <label for="translation_fr" class="block text-sm font-medium text-gray-700 mb-1">French Translation</label>
                <textarea name="translation_fr" id="translation_fr" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?= htmlspecialchars($_POST['translation_fr'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="translation_es" class="block text-sm font-medium text-gray-700 mb-1">Spanish Translation</label>
                <textarea name="translation_es" id="translation_es" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?= htmlspecialchars($_POST['translation_es'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                <input type="number" name="order" id="order" value="<?= htmlspecialchars($_POST['order'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>

            <div>
                <label for="form_position" class="block text-sm font-medium text-gray-700 mb-1">Form Position (Wizard Step) *</label>
                <select name="form_position" id="form_position" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="1" <?= (isset($_POST['form_position']) && $_POST['form_position'] == '1') ? 'selected' : '' ?>>Step 1 - Basic Info</option>
                    <option value="2" <?= (isset($_POST['form_position']) && $_POST['form_position'] == '2') ? 'selected' : (empty($_POST['form_position']) ? 'selected' : '') ?>>Step 2 - Project Details</option>
                    <option value="3" <?= (isset($_POST['form_position']) && $_POST['form_position'] == '3') ? 'selected' : '' ?>>Step 3 - Budget & Time</option>
                    <option value="4" <?= (isset($_POST['form_position']) && $_POST['form_position'] == '4') ? 'selected' : '' ?>>Step 4 - Review</option>
                </select>
            </div>

            <div class="flex items-center space-x-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_required" value="1" checked class="mr-2">
                    <span class="text-sm text-gray-700">Required</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                    <span class="text-sm text-gray-700">Active</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="<?= Response::url('/questions') ?>" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary-light transition">
                    Create Question
                </button>
            </div>
        </div>
    </form>
</section>

<script>
document.getElementById('question_type').addEventListener('change', function() {
    const optionsContainer = document.getElementById('options-container');
    const typesWithOptions = ['select', 'radio', 'checkbox'];
    if (typesWithOptions.includes(this.value)) {
        optionsContainer.style.display = 'block';
    } else {
        optionsContainer.style.display = 'none';
    }
});
</script>

<?php
$content = ob_get_clean();
require APP_PATH . '/Views/layouts/admin.php';
?>
