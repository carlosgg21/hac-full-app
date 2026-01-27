<?php
$title = 'Editar Pregunta';
ob_start();
?>

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Edit Question</h1>
    <a href="<?= Response::url('/questions') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
        Back
    </a>
</div>

<section class="bg-white rounded-xl shadow-md px-6 py-5">
    <form method="POST" action="<?= Response::url('/questions/' . $question['id']) ?>">
        <input type="hidden" name="_method" value="PUT">
        
        <div class="space-y-4">
            <div>
                <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">Service *</label>
                <select name="service_id" id="service_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Select a service</option>
                    <?php foreach ($services as $service): ?>
                        <option value="<?= $service['id'] ?>" <?= ($question['service_id'] == $service['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($service['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="question_text" class="block text-sm font-medium text-gray-700 mb-1">Question Text *</label>
                <textarea name="question_text" id="question_text" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?= htmlspecialchars($question['question_text'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="question_type" class="block text-sm font-medium text-gray-700 mb-1">Question Type *</label>
                <select name="question_type" id="question_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="text" <?= ($question['question_type'] == 'text') ? 'selected' : '' ?>>Text</option>
                    <option value="number" <?= ($question['question_type'] == 'number') ? 'selected' : '' ?>>Number</option>
                    <option value="select" <?= ($question['question_type'] == 'select') ? 'selected' : '' ?>>Select</option>
                    <option value="radio" <?= ($question['question_type'] == 'radio') ? 'selected' : '' ?>>Radio</option>
                    <option value="checkbox" <?= ($question['question_type'] == 'checkbox') ? 'selected' : '' ?>>Checkbox</option>
                    <option value="textarea" <?= ($question['question_type'] == 'textarea') ? 'selected' : '' ?>>Textarea</option>
                </select>
            </div>

            <div id="options-container" style="display: <?= in_array($question['question_type'], ['select', 'radio', 'checkbox']) ? 'block' : 'none' ?>;">
                <label for="options" class="block text-sm font-medium text-gray-700 mb-1">Options (one per line)</label>
                <textarea name="options" id="options" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?php
                    $options = Question::getOptions($question);
                    echo htmlspecialchars(implode("\n", $options));
                ?></textarea>
            </div>

            <div>
                <label for="translation_fr" class="block text-sm font-medium text-gray-700 mb-1">French Translation</label>
                <textarea name="translation_fr" id="translation_fr" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?= htmlspecialchars($translations['fr'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="translation_es" class="block text-sm font-medium text-gray-700 mb-1">Spanish Translation</label>
                <textarea name="translation_es" id="translation_es" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?= htmlspecialchars($translations['es'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                <input type="number" name="order" id="order" value="<?= htmlspecialchars($question['order'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>

            <div>
                <label for="form_position" class="block text-sm font-medium text-gray-700 mb-1">Form Position (Wizard Step) *</label>
                <select name="form_position" id="form_position" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="1" <?= (isset($question['form_position']) && $question['form_position'] == 1) ? 'selected' : '' ?>>Step 1 - Basic Info</option>
                    <option value="2" <?= (isset($question['form_position']) && $question['form_position'] == 2) ? 'selected' : (empty($question['form_position']) ? 'selected' : '') ?>>Step 2 - Project Details</option>
                    <option value="3" <?= (isset($question['form_position']) && $question['form_position'] == 3) ? 'selected' : '' ?>>Step 3 - Budget & Time</option>
                    <option value="4" <?= (isset($question['form_position']) && $question['form_position'] == 4) ? 'selected' : '' ?>>Step 4 - Review</option>
                </select>
            </div>

            <div class="flex items-center space-x-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_required" value="1" <?= ($question['is_required'] ?? 0) ? 'checked' : '' ?> class="mr-2">
                    <span class="text-sm text-gray-700">Required</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" <?= ($question['is_active'] ?? 0) ? 'checked' : '' ?> class="mr-2">
                    <span class="text-sm text-gray-700">Active</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="<?= Response::url('/questions') ?>" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary-light transition">
                    Update Question
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
