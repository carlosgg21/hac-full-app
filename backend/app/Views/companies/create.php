<?php
$title = 'Create Company';
ob_start();
?>

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Create Company</h1>
    <a href="<?= Response::url('/companies') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
        Back
    </a>
</div>

<section class="bg-white rounded-xl shadow-md px-6 py-5">
    <form method="POST" action="<?= Response::url('/companies') ?>">
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                <input type="text" name="name" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="acronym" class="block text-sm font-medium text-gray-700 mb-1">Acronym</label>
                    <input type="text" name="acronym" id="acronym" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="<?= htmlspecialchars($_POST['acronym'] ?? '') ?>">
                </div>
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                    <input type="text" name="code" id="code" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="<?= htmlspecialchars($_POST['code'] ?? '') ?>">
                </div>
            </div>

            <div>
                <label for="slogan" class="block text-sm font-medium text-gray-700 mb-1">Slogan</label>
                <input type="text" name="slogan" id="slogan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="<?= htmlspecialchars($_POST['slogan'] ?? '') ?>">
            </div>

            <div>
                <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Logo (URL or path)</label>
                <input type="text" name="logo" id="logo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="<?= htmlspecialchars($_POST['logo'] ?? '') ?>">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" id="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                </div>
            </div>

            <div>
                <label for="web_site" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                <input type="url" name="web_site" id="web_site" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="<?= htmlspecialchars($_POST['web_site'] ?? '') ?>">
            </div>

            <div class="border-t pt-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Social Media</h3>
                <div class="space-y-3">
                    <div>
                        <label for="social_facebook" class="block text-sm font-medium text-gray-700 mb-1">Facebook</label>
                        <input type="url" name="social_facebook" id="social_facebook" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="<?= htmlspecialchars($_POST['social_facebook'] ?? '') ?>">
                    </div>
                    <div>
                        <label for="social_instagram" class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                        <input type="url" name="social_instagram" id="social_instagram" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="<?= htmlspecialchars($_POST['social_instagram'] ?? '') ?>">
                    </div>
                    <div>
                        <label for="social_twitter" class="block text-sm font-medium text-gray-700 mb-1">Twitter</label>
                        <input type="url" name="social_twitter" id="social_twitter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="<?= htmlspecialchars($_POST['social_twitter'] ?? '') ?>">
                    </div>
                    <div>
                        <label for="social_linkedin" class="block text-sm font-medium text-gray-700 mb-1">LinkedIn</label>
                        <input type="url" name="social_linkedin" id="social_linkedin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="<?= htmlspecialchars($_POST['social_linkedin'] ?? '') ?>">
                    </div>
                    <div>
                        <label for="social_youtube" class="block text-sm font-medium text-gray-700 mb-1">YouTube</label>
                        <input type="url" name="social_youtube" id="social_youtube" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="<?= htmlspecialchars($_POST['social_youtube'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="<?= Response::url('/companies') ?>" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary-light transition">
                    Create Company
                </button>
            </div>
        </div>
    </form>
</section>

<?php
$content = ob_get_clean();
require APP_PATH . '/Views/layouts/admin.php';
?>
