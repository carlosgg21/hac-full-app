<?php
$title = 'Edit Contact';
$client = $client ?? [];
ob_start();
?>

<div class="mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Contact</h1>
            <p class="text-sm text-gray-500 mt-1">Update contact information</p>
        </div>
        <a href="<?= Response::url('/clients') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
            <i class="bi bi-arrow-left"></i>
            Back to List
        </a>
    </div>
</div>

<form method="POST" action="<?= Response::url('/clients/' . ($client['id'] ?? '')) ?>" id="clientEditForm">
    <input type="hidden" name="_method" value="PUT">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <section class="bg-white rounded-xl shadow-md px-6 py-5">
                <div class="flex items-center gap-2 mb-5 pb-4 border-b border-gray-200">
                    <i class="bi bi-person text-primary text-xl"></i>
                    <h2 class="text-xl font-semibold text-gray-800">Personal Information</h2>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" id="first_name" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                   value="<?= htmlspecialchars($client['first_name'] ?? '') ?>"
                                   placeholder="First name">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" id="last_name" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                   value="<?= htmlspecialchars($client['last_name'] ?? '') ?>"
                                   placeholder="Last name">
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-xl shadow-md px-6 py-5">
                <div class="flex items-center gap-2 mb-5 pb-4 border-b border-gray-200">
                    <i class="bi bi-envelope text-primary text-xl"></i>
                    <h2 class="text-xl font-semibold text-gray-800">Contact</h2>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                               value="<?= htmlspecialchars($client['email'] ?? '') ?>"
                               placeholder="email@example.com">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                        <input type="text" name="phone" id="phone"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                               value="<?= htmlspecialchars($client['phone'] ?? '') ?>"
                               placeholder="(555) 123-4567">
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-xl shadow-md px-6 py-5">
                <div class="flex items-center gap-2 mb-5 pb-4 border-b border-gray-200">
                    <i class="bi bi-geo-alt text-primary text-xl"></i>
                    <h2 class="text-xl font-semibold text-gray-800">Address</h2>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Street Address</label>
                        <input type="text" name="address" id="address"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                               value="<?= htmlspecialchars($client['address'] ?? '') ?>"
                               placeholder="Street address">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">City</label>
                            <input type="text" name="city" id="city"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                   value="<?= htmlspecialchars($client['city'] ?? '') ?>"
                                   placeholder="City">
                        </div>
                        <div>
                            <label for="state" class="block text-sm font-semibold text-gray-700 mb-2">State / Province</label>
                            <input type="text" name="state" id="state"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                   value="<?= htmlspecialchars($client['state'] ?? '') ?>"
                                   placeholder="State">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="zip_code" class="block text-sm font-semibold text-gray-700 mb-2">ZIP / Postal Code</label>
                            <input type="text" name="zip_code" id="zip_code"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                   value="<?= htmlspecialchars($client['zip_code'] ?? '') ?>"
                                   placeholder="ZIP code">
                        </div>
                        <div>
                            <label for="country" class="block text-sm font-semibold text-gray-700 mb-2">Country</label>
                            <input type="text" name="country" id="country"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                   value="<?= htmlspecialchars($client['country'] ?? 'México') ?>"
                                   placeholder="Country">
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-xl shadow-md px-6 py-5">
                <div class="flex items-center gap-2 mb-5 pb-4 border-b border-gray-200">
                    <i class="bi bi-file-text text-primary text-xl"></i>
                    <h2 class="text-xl font-semibold text-gray-800">Notes</h2>
                </div>
                <div>
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                              placeholder="Optional notes about this contact"><?= htmlspecialchars($client['notes'] ?? '') ?></textarea>
                </div>
            </section>
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-3">
        <a href="<?= Response::url('/clients') ?>" class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
            Cancel
        </a>
        <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg font-medium hover:bg-primary-light transition">
            Update Contact
        </button>
    </div>
</form>

<?php
$content = ob_get_clean();
require APP_PATH . '/Views/layouts/admin.php';
?>
