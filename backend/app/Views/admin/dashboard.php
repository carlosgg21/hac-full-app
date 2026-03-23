<?php
$title = 'Dashboard';
ob_start();
?>

<!-- Cards de Estadísticas -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-md px-6 py-5">
        <h3 class="text-sm text-gray-600 mb-2">Total Clients</h3>
        <p class="text-3xl font-bold" style="color: #1e3a5f;"><?= $stats['total_clients'] ?? 0 ?></p>
    </div>

    <div class="bg-white rounded-xl shadow-md px-6 py-5">
        <h3 class="text-sm text-gray-600 mb-2">Total Quotes</h3>
        <p class="text-3xl font-bold" style="color: #1e3a5f;"><?= $stats['total_quotes'] ?? 0 ?></p>
    </div>

    <div class="bg-white rounded-xl shadow-md px-6 py-5">
        <h3 class="text-sm text-gray-600 mb-2">Pending Quotes</h3>
        <p class="text-3xl font-bold" style="color: #e67e22;"><?= $stats['pending_quotes'] ?? 0 ?></p>
    </div>

    <div class="bg-white rounded-xl shadow-md px-6 py-5">
        <h3 class="text-sm text-gray-600 mb-2">Accepted Quotes</h3>
        <p class="text-3xl font-bold" style="color: #4caf50;"><?= $stats['accepted_quotes'] ?? 0 ?></p>
    </div>

    <div class="bg-white rounded-xl shadow-md px-6 py-5">
        <h3 class="text-sm text-gray-600 mb-2">Rejected Quotes</h3>
        <p class="text-3xl font-bold" style="color: #dc2626;"><?= $stats['rejected_quotes'] ?? 0 ?></p>
    </div>
</div>

<!-- Gráficos -->
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

<!-- Recent Quotes -->
<div class="bg-white rounded-xl shadow-md px-6 py-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Recent Quotes</h3>
        <a href="<?= Response::url('/quotes') ?>" class="text-sm text-primary hover:underline font-medium">View all</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-100 text-gray-700 border-b border-gray-200">
                    <th class="py-3 px-4 text-left text-sm font-semibold">Number</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold">Client</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold">Status</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold">Amount</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentQuotes)): ?>
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500">No quotes yet</td>
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
                                    elseif ($status === 'expired') echo 'bg-red-100 text-red-600';
                                    else echo 'bg-gray-100 text-gray-700';
                                    ?>">
                                    <?= htmlspecialchars($quote['status']) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600">$<?= number_format($quote['total_amount'] ?? 0, 2) ?></td>
                            <td class="py-3 px-4 text-sm text-gray-600"><?= isset($quote['created_at']) ? date('d/m/Y', strtotime($quote['created_at'])) : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ApexCharts: solo en dashboard -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Services Chart (Bar) - ApexCharts
    const services = <?= json_encode(array_column($serviceStats ?? [], 'service_type')) ?>;
    const counts = <?= json_encode(array_map('intval', array_column($serviceStats ?? [], 'total'))) ?>;

    if (document.getElementById('servicesChart')) {
        const servicesOptions = {
            series: [{
                name: 'Quotes',
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
                    columnWidth: '50%',
                    distributed: true,
                    dataLabels: { position: 'top' }
                }
            },
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: {
                    fontSize: '13px',
                    fontWeight: 700,
                    colors: ['#374151']
                }
            },
            legend: { show: false },
            colors: ['#1e3a5f', '#2c4f7c', '#e67e22', '#f39c12', '#2563eb'],
            xaxis: {
                categories: services,
                labels: {
                    rotate: 0,
                    trim: true,
                    maxHeight: 60,
                    style: {
                        colors: '#374151',
                        fontSize: '12px',
                        fontWeight: 500
                    }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: { show: false }
            },
            grid: {
                borderColor: '#f3f4f6',
                strokeDashArray: 0,
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: false } }
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
    <?php
        $monthlyData = array_fill(1, 12, 0);
        foreach ($clientsByMonth ?? [] as $row) {
            $monthlyData[(int)$row['month']] = (int)$row['total'];
        }
    ?>
    const clientCounts = <?= json_encode(array_values($monthlyData)) ?>;

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
