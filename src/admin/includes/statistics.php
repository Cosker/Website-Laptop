<?php
// Gọi kết nối Database để lấy dữ liệu thống kê
require_once __DIR__ . '/../../config/db.php';

// Chỉ tính các đơn đã hoàn thành. Doanh thu lấy từ orders.total_amount,
// số lượng lấy từ order_details -- tính riêng để không bị cộng trùng khi 1 đơn có nhiều sản phẩm.

// 1. Danh sách năm có đơn hàng (luôn có năm hiện tại) + năm đang xem (?year=)
$currentYear = (int)date('Y');
$years = $pdo->query("SELECT DISTINCT YEAR(created_at) FROM orders ORDER BY 1 DESC")->fetchAll(PDO::FETCH_COLUMN);
$years = array_map('intval', $years);
if (!in_array($currentYear, $years, true)) {
    array_unshift($years, $currentYear);
}
rsort($years);
$selectedYear = (int)($_GET['year'] ?? $currentYear);
if (!in_array($selectedYear, $years, true)) {
    $selectedYear = $currentYear;
}

// 2. Doanh thu, số đơn và số laptop bán ra theo từng tháng của năm đang xem
$chartMonths = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
$monthRevenue = array_fill(0, 12, 0);
$monthOrders = array_fill(0, 12, 0);
$monthQuantity = array_fill(0, 12, 0);

$stmt = $pdo->prepare("SELECT MONTH(created_at) AS m, SUM(total_amount) AS revenue, COUNT(*) AS orders
                       FROM orders WHERE status = 'completed' AND YEAR(created_at) = ?
                       GROUP BY MONTH(created_at)");
$stmt->execute([$selectedYear]);
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $monthRevenue[$row['m'] - 1] = (int)$row['revenue'];
    $monthOrders[$row['m'] - 1] = (int)$row['orders'];
}

$stmt = $pdo->prepare("SELECT MONTH(o.created_at) AS m, SUM(od.quantity) AS qty
                       FROM orders o JOIN order_details od ON od.order_id = o.id
                       WHERE o.status = 'completed' AND YEAR(o.created_at) = ?
                       GROUP BY MONTH(o.created_at)");
$stmt->execute([$selectedYear]);
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $monthQuantity[$row['m'] - 1] = (int)$row['qty'];
}

$totalYearRevenue = array_sum($monthRevenue);
$totalYearOrders = array_sum($monthOrders);
$totalYearProducts = array_sum($monthQuantity);

// 3. Thống kê theo Quý (gộp 3 tháng)
$quarters = [];
for ($q = 0; $q < 4; $q++) {
    $quarters['Q' . ($q + 1)] = [
        'revenue' => array_sum(array_slice($monthRevenue, $q * 3, 3)),
        'quantity' => array_sum(array_slice($monthQuantity, $q * 3, 3)),
    ];
}

// 4. Tháng tốt nhất
$bestMonthIndex = array_keys($monthRevenue, max($monthRevenue))[0];
$bestMonthName = $totalYearRevenue > 0 ? $chartMonths[$bestMonthIndex] : '—';
$bestMonthRevenue = $monthRevenue[$bestMonthIndex];

// 5. So sánh theo năm (tất cả các năm có đơn hoàn thành)
$yearRows = $pdo->query("SELECT YEAR(created_at) AS y, SUM(total_amount) AS revenue, COUNT(*) AS orders
                         FROM orders WHERE status = 'completed'
                         GROUP BY YEAR(created_at) ORDER BY y ASC")->fetchAll(PDO::FETCH_ASSOC);
$yearQty = $pdo->query("SELECT YEAR(o.created_at) AS y, SUM(od.quantity) AS qty
                        FROM orders o JOIN order_details od ON od.order_id = o.id
                        WHERE o.status = 'completed'
                        GROUP BY YEAR(o.created_at)")->fetchAll(PDO::FETCH_KEY_PAIR);

$yearlyStats = [];
$prevRevenue = null;
foreach ($yearRows as $row) {
    $revenue = (int)$row['revenue'];
    $yearlyStats[] = [
        'year' => (int)$row['y'],
        'revenue' => $revenue,
        'orders' => (int)$row['orders'],
        'quantity' => (int)($yearQty[$row['y']] ?? 0),
        // % tăng trưởng doanh thu so với năm liền trước có dữ liệu
        'growth' => ($prevRevenue) ? round(($revenue - $prevRevenue) / $prevRevenue * 100, 1) : null,
    ];
    $prevRevenue = $revenue;
}

$money = fn($v) => number_format($v, 0, ',', '.') . ' ₫';
?>

<section id="statistics" class="admin-section">
    <div class="mb-6 flex flex-col sm:flex-row justify-between sm:items-end gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Thống Kê Kinh Doanh</h2>
            <p class="text-sm text-gray-500 mt-1">Doanh thu và số laptop bán ra (đơn đã hoàn thành) năm <?= $selectedYear ?></p>
        </div>
        <form method="GET" class="flex items-center gap-2">
            <label for="stats-year" class="text-sm text-gray-600">Năm</label>
            <select id="stats-year" name="year" onchange="this.form.submit()" class="border border-gray-200 rounded-lg text-sm px-4 py-2 bg-white focus:outline-none focus:border-primary">
                <?php foreach ($years as $y): ?>
                    <option value="<?= $y ?>" <?= $y === $selectedYear ? 'selected' : '' ?>><?= $y ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Cards Tổng Quan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-primary">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Doanh Thu Năm <?= $selectedYear ?></h3>
            <p class="text-2xl font-bold text-gray-800"><?= $money($totalYearRevenue) ?></p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-green-500">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Laptop Bán Ra</h3>
            <p class="text-2xl font-bold text-gray-800"><?= $totalYearProducts ?> <span class="text-base font-normal text-gray-500">chiếc</span></p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-purple-500">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Đơn Hoàn Thành</h3>
            <p class="text-2xl font-bold text-gray-800"><?= $totalYearOrders ?> <span class="text-base font-normal text-gray-500">đơn</span></p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-orange-500">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Tháng Tốt Nhất (<?= $bestMonthName ?>)</h3>
            <p class="text-2xl font-bold text-gray-800"><?= $money($bestMonthRevenue) ?></p>
        </div>
    </div>

    <!-- Biểu đồ tháng & Bảng Quý -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-3">Doanh Thu & Số Lượng Theo Tháng</h3>
            <div class="h-80">
                <canvas id="realRevenueChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-3">Thống Kê Theo Quý</h3>
            <div class="space-y-4">
                <?php foreach ($quarters as $q => $data): $has = $data['revenue'] > 0; ?>
                    <div class="flex items-center justify-between p-3 <?= $has ? 'bg-blue-50/50' : 'bg-gray-50' ?> rounded-lg border border-gray-100">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full <?= $has ? 'bg-blue-100 text-primary' : 'bg-gray-200 text-gray-400' ?> flex items-center justify-center font-bold mr-3">
                                <?= $q ?>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Quý <?= substr($q, 1) ?></p>
                                <p class="text-xs text-gray-500"><?= $has ? $data['quantity'] . ' laptop' : 'Chưa có dữ liệu' ?></p>
                            </div>
                        </div>
                        <span class="font-bold <?= $has ? 'text-primary' : 'text-gray-400' ?>"><?= $money($data['revenue']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- So sánh theo năm -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <h3 class="font-bold text-gray-800 p-5 border-b">So Sánh Theo Năm</h3>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Năm</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Đơn hoàn thành</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Laptop bán ra</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Doanh thu</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">So với năm trước</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (!$yearlyStats): ?>
                            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">Chưa có đơn hàng hoàn thành.</td></tr>
                        <?php endif; ?>
                        <?php foreach (array_reverse($yearlyStats) as $row): ?>
                            <tr class="<?= $row['year'] === $selectedYear ? 'bg-blue-50/40' : '' ?>">
                                <td class="px-6 py-4 text-sm font-bold text-gray-900"><?= $row['year'] ?></td>
                                <td class="px-6 py-4 text-sm text-right text-gray-700"><?= $row['orders'] ?></td>
                                <td class="px-6 py-4 text-sm text-right text-gray-700"><?= $row['quantity'] ?></td>
                                <td class="px-6 py-4 text-sm text-right font-bold text-gray-900"><?= $money($row['revenue']) ?></td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <?php if ($row['growth'] === null): ?>
                                        <span class="text-gray-400">—</span>
                                    <?php else: ?>
                                        <span class="font-semibold <?= $row['growth'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                                            <i class="fa-solid <?= $row['growth'] >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' ?> mr-1"></i><?= ($row['growth'] > 0 ? '+' : '') . $row['growth'] ?>%
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-3">Doanh Thu Các Năm</h3>
            <div class="h-64">
                <canvas id="yearlyRevenueChart"></canvas>
            </div>
        </div>
    </div>
</section>

<!-- Đẩy dữ liệu PHP xuống JS để vẽ biểu đồ Thống Kê -->
<script>
    // Hàm này sẽ được gọi ở window.onload ở index.php
    function initRealStatsChart() {
        const millions = (value) => (value / 1000000) + ' Tr'; // Rút gọn đơn vị Triệu
        const moneyTooltip = (context) => context.dataset.label + ': ' + (context.dataset.yAxisID === 'qty'
            ? context.raw + ' chiếc'
            : (context.raw || 0).toLocaleString('vi-VN') + ' ₫');

        const ctx = document.getElementById('realRevenueChart');
        if (ctx) {
            new Chart(ctx.getContext('2d'), {
                data: {
                    labels: <?= json_encode($chartMonths) ?>,
                    datasets: [{
                        type: 'bar',
                        label: 'Doanh thu',
                        data: <?= json_encode($monthRevenue) ?>,
                        backgroundColor: 'rgba(37, 99, 235, 0.8)',
                        borderRadius: 4,
                        yAxisID: 'y',
                    }, {
                        type: 'line',
                        label: 'Số lượng',
                        data: <?= json_encode($monthQuantity) ?>,
                        borderColor: '#16a34a',
                        backgroundColor: '#16a34a',
                        tension: 0.3,
                        yAxisID: 'qty',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { tooltip: { callbacks: { label: moneyTooltip } } },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [2, 4] }, ticks: { callback: millions } },
                        qty: { beginAtZero: true, position: 'right', grid: { display: false }, ticks: { precision: 0 } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        const yearCtx = document.getElementById('yearlyRevenueChart');
        if (yearCtx) {
            new Chart(yearCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_column($yearlyStats, 'year')) ?>,
                    datasets: [{
                        label: 'Doanh thu',
                        data: <?= json_encode(array_column($yearlyStats, 'revenue')) ?>,
                        backgroundColor: 'rgba(249, 115, 22, 0.8)',
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { callbacks: { label: moneyTooltip } } },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [2, 4] }, ticks: { callback: millions } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    }
</script>
