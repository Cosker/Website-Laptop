<?php
// Gọi kết nối Database để lấy dữ liệu thống kê
require_once __DIR__ . '/../../config/db.php';

// Lấy năm hiện tại
$currentYear = date('Y');

// 1. Lấy dữ liệu theo Từng Tháng trong Năm (Đã hoàn thành)
$sqlMonth = "SELECT MONTH(o.created_at) as month, SUM(o.total_amount) as total_revenue, SUM(od.quantity) as total_sold
             FROM orders o
             JOIN order_details od ON o.id = od.order_id
             WHERE YEAR(o.created_at) = :year AND o.status = 'completed'
             GROUP BY MONTH(o.created_at)
             ORDER BY MONTH(o.created_at)";
$stmtMonth = $pdo->prepare($sqlMonth);
$stmtMonth->execute(['year' => $currentYear]);
$monthlyData = $stmtMonth->fetchAll(PDO::FETCH_ASSOC);

// Chuẩn bị dữ liệu cho Chart.js (Mảng 12 tháng mặc định là 0)
$chartMonths = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
$chartRevenue = array_fill(0, 12, 0);
$totalYearRevenue = 0;
$totalYearProducts = 0;

foreach ($monthlyData as $row) {
    $monthIndex = $row['month'] - 1; // Mảng bắt đầu từ 0
    $chartRevenue[$monthIndex] = $row['total_revenue'];
    $totalYearRevenue += $row['total_revenue'];
    $totalYearProducts += $row['total_sold'];
}

// 2. Thống kê theo Quý
$quarters = [
    'Q1' => array_sum(array_slice($chartRevenue, 0, 3)),
    'Q2' => array_sum(array_slice($chartRevenue, 3, 3)),
    'Q3' => array_sum(array_slice($chartRevenue, 6, 3)),
    'Q4' => array_sum(array_slice($chartRevenue, 9, 3))
];

// 3. Tháng tốt nhất
$bestMonthIndex = array_keys($chartRevenue, max($chartRevenue))[0];
$bestMonthName = $chartMonths[$bestMonthIndex];
$bestMonthRevenue = $chartRevenue[$bestMonthIndex];
?>

<section id="statistics" class="admin-section">
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Thống Kê Kinh Doanh</h2>
            <p class="text-sm text-gray-500 mt-1">Dữ liệu phân tích doanh thu và sản phẩm bán ra năm <?= $currentYear ?></p>
        </div>
        <button class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-gray-50 flex items-center">
            <i class="fa-solid fa-download mr-2"></i> Xuất Báo Cáo
        </button>
    </div>

    <!-- Cards Tổng Quan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-primary">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Tổng Doanh Thu (Năm <?= $currentYear ?>)</h3>
            <p class="text-3xl font-bold text-gray-800"><?= number_format($totalYearRevenue, 0, ',', '.') ?> ₫</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-green-500">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Laptop Bán Ra</h3>
            <p class="text-3xl font-bold text-gray-800"><?= $totalYearProducts ?> <span class="text-lg font-normal text-gray-500">chiếc</span></p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-orange-500">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Tháng Tốt Nhất (<?= $bestMonthName ?>)</h3>
            <p class="text-3xl font-bold text-gray-800"><?= number_format($bestMonthRevenue, 0, ',', '.') ?> ₫</p>
        </div>
    </div>

    <!-- Layout chia Biểu đồ & Bảng Quý -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Biểu đồ 12 Tháng -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-3">Biểu Đồ Doanh Thu Theo Tháng</h3>
            <div class="h-80">
                <canvas id="realRevenueChart"></canvas>
            </div>
        </div>

        <!-- Thống kê theo Quý -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-3">Thống Kê Theo Quý</h3>
            <div class="space-y-4">
                <?php foreach($quarters as $q => $rev): ?>
                    <div class="flex items-center justify-between p-3 <?= $rev > 0 ? 'bg-blue-50/50' : 'bg-gray-50' ?> rounded-lg border border-gray-100">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full <?= $rev > 0 ? 'bg-blue-100 text-primary' : 'bg-gray-200 text-gray-400' ?> flex items-center justify-center font-bold mr-3">
                                <?= $q ?>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Quý <?= substr($q, 1) ?></p>
                                <p class="text-xs text-gray-500"><?= $rev > 0 ? 'Hoàn thành' : 'Chưa có DL' ?></p>
                            </div>
                        </div>
                        <span class="font-bold <?= $rev > 0 ? 'text-primary' : 'text-gray-400' ?>"><?= number_format($rev, 0, ',', '.') ?> ₫</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Đẩy dữ liệu PHP xuống JS để vẽ biểu đồ Thống Kê -->
<script>
    // Hàm này sẽ được gọi ở window.onload ở index.php
    function initRealStatsChart() {
        const ctx = document.getElementById('realRevenueChart');
        if(!ctx) return;
        
        new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($chartMonths) ?>,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: <?= json_encode($chartRevenue) ?>,
                    backgroundColor: 'rgba(37, 99, 235, 0.8)',
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.raw || 0;
                                return value.toLocaleString('vi-VN') + ' ₫';
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [2, 4] },
                        ticks: {
                            callback: function(value) {
                                return (value / 1000000) + ' Tr'; // Rút gọn đơn vị Triệu
                            }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }
</script>