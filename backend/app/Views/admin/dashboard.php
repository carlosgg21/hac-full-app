<?php
$title = 'Dashboard';
ob_start();
?>

<!-- Cards de Estadísticas - Arriba en 1-2 filas -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-md px-6 py-5">
        <h3 class="text-sm text-gray-600 mb-2">Total Clients</h3>
        <p class="text-3xl font-bold" style="color: #1e3a5f;"><?= $stats['total_clients'] ?? 0 ?></p>
    </div>
    
    <div class="bg-white rounded-xl shadow-md px-6 py-5">
        <h3 class="text-sm text-gray-600 mb-2">Total Cotizaciones</h3>
        <p class="text-3xl font-bold" style="color: #1e3a5f;"><?= $stats['total_quotes'] ?? 0 ?></p>
    </div>
    
    <div class="bg-white rounded-xl shadow-md px-6 py-5">
        <h3 class="text-sm text-gray-600 mb-2">Pending Quotes</h3>
        <p class="text-3xl font-bold" style="color: #e67e22;"><?= $stats['pending_quotes'] ?? 0 ?></p>
    </div>
    
    <div class="bg-white rounded-xl shadow-md px-6 py-5">
        <h3 class="text-sm text-gray-600 mb-2">Total Projects</h3>
        <p class="text-3xl font-bold" style="color: #1e3a5f;"><?= $stats['total_projects'] ?? 0 ?></p>
    </div>
    
    <div class="bg-white rounded-xl shadow-md px-6 py-5">
        <h3 class="text-sm text-gray-600 mb-2">Proyectos Activos</h3>
        <p class="text-3xl font-bold" style="color: #4caf50;"><?= $stats['active_projects'] ?? 0 ?></p>
    </div>
</div>

<!-- Gráficos - Abajo -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Services Chart -->
    <section class="bg-white rounded-xl shadow-md px-6 py-5">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">Services Overview</h2>
        <div id="servicesChart" style="height: 300px;"></div>
    </section>
    
    <!-- Clients by Month Chart -->
    <section class="bg-white rounded-xl shadow-md px-6 py-5">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">Clients by Month</h2>
        <div id="clientsChart" style="height: 300px;"></div>
    </section>
</div>

<!-- Tablas de Recientes -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-md px-6 py-5">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Recent Quotes</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 border-b border-gray-200">
                        <th class="py-3 px-4 text-left text-sm font-semibold">Número</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Cliente</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Estado</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentQuotes)): ?>
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-500">No hay cotizaciones</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentQuotes as $quote): ?>
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="py-3 px-4 text-sm">
                                    <a href="<?= Response::url('/quotes/' . $quote['id']) ?>" class="text-primary hover:underline">
                                        <?= htmlspecialchars($quote['quote_number']) ?>
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600"><?= htmlspecialchars($quote['client_name'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4 text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php 
                                        $status = strtolower($quote['status'] ?? '');
                                        if ($status === 'pending') echo 'bg-yellow-100 text-yellow-700';
                                        elseif ($status === 'accepted') echo 'bg-green-100 text-green-700';
                                        elseif ($status === 'rejected') echo 'bg-red-100 text-red-700';
                                        else echo 'bg-gray-100 text-gray-700';
                                        ?>">
                                        <?= htmlspecialchars($quote['status']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">$<?= number_format($quote['total_amount'] ?? 0, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md px-6 py-5">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Recent Projects</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 border-b border-gray-200">
                        <th class="py-3 px-4 text-left text-sm font-semibold">Número</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Name</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Estado</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Progreso</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentProjects)): ?>
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-500">No hay proyectos</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentProjects as $project): ?>
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="py-3 px-4 text-sm">
                                    <a href="<?= Response::url('/projects/' . $project['id']) ?>" class="text-primary hover:underline">
                                        <?= htmlspecialchars($project['project_number']) ?>
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600"><?= htmlspecialchars($project['name']) ?></td>
                                <td class="py-3 px-4 text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php 
                                        $status = strtolower($project['status'] ?? '');
                                        if ($status === 'in_progress') echo 'bg-blue-100 text-blue-700';
                                        elseif ($status === 'completed') echo 'bg-green-100 text-green-700';
                                        elseif ($status === 'cancelled') echo 'bg-red-100 text-red-700';
                                        else echo 'bg-gray-100 text-gray-700';
                                        ?>">
                                        <?= htmlspecialchars($project['status']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600"><?= $project['progress'] ?? 0 ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script para gráficos ApexCharts -->
<script>
    // Services Chart (Bar) - ApexCharts
    const services = ['Painting', 'Demolition', 'Electricity', 'Plumbing', 'Remodeling'];
    const counts = Array.from({ length: services.length }, () => Math.floor(Math.random() * 40) + 10);
    
    if (document.getElementById('servicesChart')) {
        const servicesOptions = {
            series: [{
                name: 'Services',
                data: counts
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '60%',
                    distributed: true
                }
            },
            dataLabels: {
                enabled: false
            },
            colors: ['#1e3a5f', '#2c4f7c', '#e67e22', '#f39c12', '#2563eb'],
            xaxis: {
                categories: services,
                labels: {
                    style: {
                        colors: '#6b7280',
                        fontSize: '12px'
                    }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#6b7280',
                        fontSize: '12px'
                    }
                },
                grid: {
                    borderColor: '#f3f4f6'
                }
            },
            grid: {
                borderColor: '#f3f4f6',
                strokeDashArray: 0,
                xaxis: { lines: { show: false } }
            },
            tooltip: {
                theme: 'light'
            }
        };
        const servicesChart = new ApexCharts(document.querySelector("#servicesChart"), servicesOptions);
        servicesChart.render();
    }

    // Clients by Month Chart (Area) - ApexCharts
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const clientCounts = Array.from({ length: 12 }, () => Math.floor(Math.random() * 30) + 5);
    
    if (document.getElementById('clientsChart')) {
        const clientsOptions = {
            series: [{
                name: 'New Clients',
                data: clientCounts
            }],
            chart: {
                type: 'area',
                height: 300,
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3,
                colors: ['#1e3a5f']
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.1,
                    stops: [0, 90, 100]
                },
                colors: ['#1e3a5f']
            },
            colors: ['#1e3a5f'],
            xaxis: {
                categories: months,
                labels: {
                    style: {
                        colors: '#6b7280',
                        fontSize: '12px'
                    }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#6b7280',
                        fontSize: '12px'
                    }
                },
                grid: {
                    borderColor: '#f3f4f6'
                }
            },
            grid: {
                borderColor: '#f3f4f6',
                strokeDashArray: 0,
                xaxis: { lines: { show: false } }
            },
            tooltip: {
                theme: 'light'
            },
            markers: {
                size: 5,
                colors: ['#1e3a5f'],
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: { size: 7 }
            }
        };
        const clientsChart = new ApexCharts(document.querySelector("#clientsChart"), clientsOptions);
        clientsChart.render();
    }
</script>

<?php
$content = ob_get_clean();
require APP_PATH . '/Views/layouts/admin.php';
?>
