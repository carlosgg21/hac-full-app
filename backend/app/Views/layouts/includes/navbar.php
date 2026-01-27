<?php
/**
 * Navbar Superior - Admin Layout
 */
$currentUser = Auth::user();
$username = $currentUser ? ($currentUser['username'] ?? 'Admin') : ($_SESSION['username'] ?? 'Admin');
$basePath = defined('BASE_PATH_URL') ? BASE_PATH_URL : '/backend';
$logoPath = $basePath . '/public/logo.png';
?>
<!-- NAVBAR TOP -->
<nav class="fixed top-0 left-0 w-full h-16 bg-white border-b border-gray-200 z-30 flex items-center justify-between px-6 shadow-sm">
  <div class="flex items-center gap-3">
    <!-- Mobile menu button -->
    <button id="sidebarToggle" class="lg:hidden p-2 inline-flex items-center rounded hover:bg-gray-100 transition">
      <!-- Menu Icon -->
      <i class="bi bi-list text-2xl" style="color: #1e3a5f;"></i>
    </button>
    <!-- Logo/Brand -->
    <span class="inline-flex items-center gap-2 text-xl font-bold tracking-tight" style="color: #1e3a5f;">
      <img src="<?= $logoPath ?>" alt="HAC Logo" class="w-10 h-10 object-contain" onerror="this.style.display='none'">
      <span class="hidden sm:inline">H.A.C. Renovation Inc</span>
    </span>
  </div>
  <div class="flex items-center gap-4">
    <!-- User avatar + Logout -->
    <button class="relative flex items-center gap-2 p-2 bg-gray-100 rounded-full hover:bg-gray-200 transition">
      <!-- Person Icon -->
      <i class="bi bi-person-circle text-2xl" style="color: #1e3a5f;"></i>
      <span class="hidden md:inline font-medium" style="color: #1e3a5f;"><?= htmlspecialchars($username) ?></span>
    </button>
    <a href="<?= Response::url('/logout') ?>" class="ml-2 px-3 py-2 rounded-lg border font-semibold bg-white shadow-sm hover:bg-primary hover:text-white transition" style="border-color: #1e3a5f; color: #1e3a5f;">Logout</a>
  </div>
</nav>
