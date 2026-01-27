<?php
$title = 'Edit Company';
ob_start();
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Edit Company</h1>
        <p class="text-sm text-gray-500 mt-1">Update company information and settings</p>
    </div>
    <a href="<?= Response::url('/companies') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition flex items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        Back to List
    </a>
</div>

<form method="POST" action="<?= Response::url('/companies/' . $company['id']) ?>" id="companyForm">
    <input type="hidden" name="_method" value="PUT">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information Section -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information Card -->
            <section class="bg-white rounded-xl shadow-md px-6 py-5">
                <div class="flex items-center gap-2 mb-5 pb-4 border-b border-gray-200">
                    <i class="bi bi-building text-primary text-xl"></i>
                    <h2 class="text-xl font-semibold text-gray-800">Basic Information</h2>
                </div>
                
                <div class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Company Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" 
                               value="<?= htmlspecialchars($company['name'] ?? '') ?>"
                               placeholder="Enter company name">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="acronym" class="block text-sm font-semibold text-gray-700 mb-2">Acronym</label>
                            <input type="text" name="acronym" id="acronym" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" 
                                   value="<?= htmlspecialchars($company['acronym'] ?? '') ?>"
                                   placeholder="e.g., HAC">
                        </div>
                        <div>
                            <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">Company Code</label>
                            <input type="text" name="code" id="code" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" 
                                   value="<?= htmlspecialchars($company['code'] ?? '') ?>"
                                   placeholder="e.g., HAC-001">
                        </div>
                    </div>

                    <div>
                        <label for="slogan" class="block text-sm font-semibold text-gray-700 mb-2">Slogan</label>
                        <input type="text" name="slogan" id="slogan" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" 
                               value="<?= htmlspecialchars($company['slogan'] ?? '') ?>"
                               placeholder="Company slogan or tagline">
                    </div>

                    <div>
                        <label for="logo" class="block text-sm font-semibold text-gray-700 mb-2">Logo URL</label>
                        <div class="flex gap-2">
                            <input type="text" name="logo" id="logo" 
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" 
                                   value="<?= htmlspecialchars($company['logo'] ?? '') ?>"
                                   placeholder="/assets/images/logo.png">
                            <?php if (!empty($company['logo'])): ?>
                                <img src="<?= htmlspecialchars($company['logo']) ?>" alt="Logo" class="h-12 w-12 rounded-lg object-cover border border-gray-200" onerror="this.style.display='none'">
                            <?php endif; ?>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Enter the path or URL to the company logo</p>
                    </div>
                </div>
            </section>

            <!-- Contact Information Card -->
            <section class="bg-white rounded-xl shadow-md px-6 py-5">
                <div class="flex items-center gap-2 mb-5 pb-4 border-b border-gray-200">
                    <i class="bi bi-envelope text-primary text-xl"></i>
                    <h2 class="text-xl font-semibold text-gray-800">Contact Information</h2>
                </div>
                
                <div class="space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" id="email" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" 
                                   value="<?= htmlspecialchars($company['email'] ?? '') ?>"
                                   placeholder="contact@company.com">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                            <input type="text" name="phone" id="phone" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" 
                                   value="<?= htmlspecialchars($company['phone'] ?? '') ?>"
                                   placeholder="+1 234 567 8900">
                        </div>
                    </div>

                    <div>
                        <label for="web_site" class="block text-sm font-semibold text-gray-700 mb-2">Website</label>
                        <input type="url" name="web_site" id="web_site" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" 
                               value="<?= htmlspecialchars($company['web_site'] ?? '') ?>"
                               placeholder="https://www.company.com">
                    </div>
                </div>
            </section>

            <!-- Social Media Card -->
            <section class="bg-white rounded-xl shadow-md px-6 py-5">
                <div class="flex items-center gap-2 mb-5 pb-4 border-b border-gray-200">
                    <i class="bi bi-share text-primary text-xl"></i>
                    <h2 class="text-xl font-semibold text-gray-800">Social Media</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="social_facebook" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="bi bi-facebook text-blue-600"></i>
                            Facebook
                        </label>
                        <input type="url" name="social_facebook" id="social_facebook" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" 
                               value="<?= htmlspecialchars($socialMedia['facebook'] ?? '') ?>"
                               placeholder="https://facebook.com/company">
                    </div>
                    <div>
                        <label for="social_instagram" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="bi bi-instagram text-pink-600"></i>
                            Instagram
                        </label>
                        <input type="url" name="social_instagram" id="social_instagram" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" 
                               value="<?= htmlspecialchars($socialMedia['instagram'] ?? '') ?>"
                               placeholder="https://instagram.com/company">
                    </div>
                    <div>
                        <label for="social_twitter" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="bi bi-twitter text-blue-400"></i>
                            Twitter
                        </label>
                        <input type="url" name="social_twitter" id="social_twitter" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" 
                               value="<?= htmlspecialchars($socialMedia['twitter'] ?? '') ?>"
                               placeholder="https://twitter.com/company">
                    </div>
                    <div>
                        <label for="social_linkedin" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="bi bi-linkedin text-blue-700"></i>
                            LinkedIn
                        </label>
                        <input type="url" name="social_linkedin" id="social_linkedin" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" 
                               value="<?= htmlspecialchars($socialMedia['linkedin'] ?? '') ?>"
                               placeholder="https://linkedin.com/company/company">
                    </div>
                    <div class="md:col-span-2">
                        <label for="social_youtube" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="bi bi-youtube text-red-600"></i>
                            YouTube
                        </label>
                        <input type="url" name="social_youtube" id="social_youtube" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" 
                               value="<?= htmlspecialchars($socialMedia['youtube'] ?? '') ?>"
                               placeholder="https://youtube.com/@company">
                    </div>
                </div>
            </section>
        </div>

        <!-- Action Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md px-6 py-5 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
                
                <div class="space-y-3">
                    <button type="submit" class="w-full px-6 py-3 bg-primary text-white rounded-lg font-semibold hover:bg-primary-light transition flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                        <i class="bi bi-check-circle"></i>
                        Update Company
                    </button>
                    
                    <a href="<?= Response::url('/companies') ?>" class="block w-full px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition text-center">
                        Cancel
                    </a>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Quick Info</h4>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span class="font-medium">ID:</span>
                            <span><?= $company['id'] ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Created:</span>
                            <span><?= date('M d, Y', strtotime($company['created_at'] ?? 'now')) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Updated:</span>
                            <span><?= date('M d, Y', strtotime($company['updated_at'] ?? 'now')) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
// Form validation and submission feedback
document.getElementById('companyForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    if (!name) {
        e.preventDefault();
        alert('Company name is required');
        document.getElementById('name').focus();
        return false;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Updating...';
    submitBtn.disabled = true;
});
</script>

<?php
$content = ob_get_clean();
require APP_PATH . '/Views/layouts/admin.php';
?>
