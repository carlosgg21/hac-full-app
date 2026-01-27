<?php
/**
 * Sidebar Lateral - Admin Layout
 * Menú de navegación lateral
 */

// Detectar página actual desde REQUEST_URI
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$requestUri = strtok($requestUri, '?'); // Remover query string
$requestUri = preg_replace('#^/hac-tests/backend/?#', '', $requestUri);
$requestUri = preg_replace('#^/backend/?#', '', $requestUri);
$requestUri = trim($requestUri, '/');

// Determinar página activa
$currentPage = '';
if (empty($requestUri) || $requestUri === 'dashboard' || $requestUri === '/') {
    $currentPage = 'dashboard';
} elseif (strpos($requestUri, 'clients') === 0) {
    $currentPage = 'clients';
} elseif (strpos($requestUri, 'quotes') === 0) {
    $currentPage = 'quotes';
} elseif (strpos($requestUri, 'projects') === 0) {
    $currentPage = 'projects';
} elseif (strpos($requestUri, 'questions') === 0) {
    $currentPage = 'questions';
} elseif (strpos($requestUri, 'services') === 0) {
    $currentPage = 'services';
} elseif (strpos($requestUri, 'companies') === 0) {
    $currentPage = 'companies';
} elseif (strpos($requestUri, 'reports') === 0) {
    $currentPage = 'reports';
} elseif (strpos($requestUri, 'settings') === 0) {
    $currentPage = 'settings';
} elseif (strpos($requestUri, 'calendar') === 0) {
    $currentPage = 'calendar';
}

// Función helper para generar clase activa
function isActive($page) {
    global $currentPage;
    return $currentPage === $page ? 'bg-white/20 text-white' : 'text-white/80';
}
?>
<!-- SIDEBAR -->
<aside class="sidebar open lg:static lg:open" id="mainSidebar">
  <nav class="flex flex-col h-full py-6">
    <div class="mb-6 px-4">
      <span class="text-sm font-semibold tracking-wider text-white/70 uppercase hidden lg:block">Menu</span>
    </div>
    <div class="flex-1 space-y-0.5 px-3">
      <a href="<?= Response::url('/dashboard') ?>" class="flex items-center px-3 py-2.5 rounded-lg <?= isActive('dashboard') ?> gap-2.5 font-medium text-sm hover:bg-white/20 transition">
        <!-- Dashboard Icon -->
        <i class="bi bi-grid-3x3-gap text-base"></i>
        Dashboard
      </a>
      <a href="<?= Response::url('/clients') ?>" class="flex items-center px-3 py-2.5 rounded-lg <?= isActive('clients') ?> gap-2.5 font-medium text-sm hover:bg-white/20 transition">
        <!-- Users/Clients Icon -->
        <i class="bi bi-people text-base"></i>
        Clients
      </a>
      <a href="<?= Response::url('/quotes') ?>" class="flex items-center px-3 py-2.5 rounded-lg <?= isActive('quotes') ?> gap-2.5 font-medium text-sm hover:bg-white/20 transition">
        <!-- Quotes Icon -->
        <i class="bi bi-receipt text-base"></i>
        Quotes
      </a>
      <a href="<?= Response::url('/projects') ?>" class="flex items-center px-3 py-2.5 rounded-lg <?= isActive('projects') ?> gap-2.5 font-medium text-sm hover:bg-white/20 transition">
        <!-- Projects Icon -->
        <i class="bi bi-folder text-base"></i>
        Projects
      </a>
      <a href="<?= Response::url('/questions') ?>" class="flex items-center px-3 py-2.5 rounded-lg <?= isActive('questions') ?> gap-2.5 font-medium text-sm hover:bg-white/20 transition">
        <!-- Questions Icon -->
        <i class="bi bi-chat-left-text text-base"></i>
        Questions
      </a>
      <a href="<?= Response::url('/services') ?>" class="flex items-center px-3 py-2.5 rounded-lg <?= isActive('services') ?> gap-2.5 font-medium text-sm hover:bg-white/20 transition">
        <!-- Services Icon -->
        <i class="bi bi-briefcase text-base"></i>
        Services
      </a>
      <a href="<?= Response::url('/companies') ?>" class="flex items-center px-3 py-2.5 rounded-lg <?= isActive('companies') ?> gap-2.5 font-medium text-sm hover:bg-white/20 transition">
        <!-- Companies Icon -->
        <i class="bi bi-building text-base"></i>
        Companies
      </a>
      <a href="<?= Response::url('/reports') ?>" class="flex items-center px-3 py-2.5 rounded-lg <?= isActive('reports') ?> gap-2.5 font-medium text-sm hover:bg-white/20 transition">
        <!-- Reports Icon -->
        <i class="bi bi-file-text text-base"></i>
        Reports
      </a>
    </div>
  </nav>
</aside>
