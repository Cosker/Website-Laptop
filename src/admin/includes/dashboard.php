<?php
require_once __DIR__ . '/../../config/db.php';

// 1. Doanh thu tháng này (Chỉ tính đơn hoàn thành)
$sqlRevenue = "SELECT SUM(total_amount) as total FROM orders WHERE status = 'completed' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";
$stmtRev = $pdo->query($sqlRevenue);
$revenueMonth = $stmtRev->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// 2. Đơn hàng tháng này & Đơn đang chờ xử lý
$sqlTotalOrders = "SELECT COUNT(*) as count FROM orders WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";
$ordersMonth = $pdo->query($sqlTotalOrders)->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

$sqlPending = "SELECT COUNT(*) as count FROM orders WHERE status = 'pending'";
$pendingOrders = $pdo->query($sqlPending)->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

// 3. Khách hàng (User)
$sqlUsers = "SELECT COUNT(*) as count FROM users WHERE role = 'user'";
$totalUsers = $pdo->query($sqlUsers)->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

// 4. Sản phẩm & Cảnh báo hết hàng
$sqlProducts = "SELECT COUNT(*) as count FROM products";
$totalProducts = $pdo->query($sqlProducts)->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

$sqlLowStock = "SELECT COUNT(*) as count FROM products WHERE stock < 5";
$lowStockProducts = $pdo->query($sqlLowStock)->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

// 5. Lấy 5 đơn hàng mới nhất
$sqlRecentOrders = "SELECT id, customer_name, total_amount, status FROM orders ORDER BY created_at DESC LIMIT 5";
$recentOrders = $pdo->query($sqlRecentOrders)->fetchAll(PDO::FETCH_ASSOC);

// Map trạng thái để hiển thị màu sắc
$statusMap = [
    'pending' => ['label' => 'Chờ xử lý', 'class' => 'bg-yellow-100 text-yellow-700'],
    'processing' => ['label' => 'Đang giao', 'class' => 'bg-blue-100 text-blue-700'],
    'completed' => ['label' => 'Hoàn thành', 'class' => 'bg-green-100 text-green-700'],
    'cancelled' => ['label' => 'Đã hủy', 'class' => 'bg-red-100 text-red-700']
];

// 6. Dữ liệu biểu đồ 7 ngày gần nhất CÓ DOANH THU
$sqlChart = "SELECT DATE(created_at) as order_date, SUM(total_amount) as total 
             FROM orders WHERE status = 'completed' 
             GROUP BY DATE(created_at) 
             ORDER BY DATE(created_at) DESC LIMIT 7";
$chartDataQuery = $pdo->query($sqlChart)->fetchAll(PDO::FETCH_ASSOC);

$chartLabels = [];
$chartValues = [];

if (count($chartDataQuery) > 0) {
    // Đảo ngược mảng để vẽ từ ngày cũ đến ngày mới (từ trái qua phải)
    $chartDataQuery = array_reverse($chartDataQuery);
    foreach ($chartDataQuery as $row) {
        $chartLabels[] = date('d/m', strtotime($row['order_date']));
        $chartValues[] = $row['total'];
    }
} else {
    // Fallback nếu CSDL rỗng
    $chartLabels = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'];
    $chartValues = [0, 0, 0, 0, 0, 0, 0];
}
?>

<section id="dashboard" class="admin-section active">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Tổng quan</h2>
        <p class="text-sm text-gray-500 mt-1">Hoạt động kinh doanh và số liệu thống kê thực tế</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Doanh thu -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-primary text-xl flex-shrink-0">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Doanh thu tháng này</p>
                <p class="text-xl font-bold text-gray-900 mt-0.5"><?= number_format($revenueMonth, 0, ',', '.') ?> ₫</p>
            </div>
        </div>
        <!-- Đơn hàng -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-500 text-xl flex-shrink-0">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Đơn hàng trong tháng</p>
                <p class="text-xl font-bold text-gray-900 mt-0.5"><?= $ordersMonth ?> <span class="text-sm font-normal text-gray-500">đơn</span></p>
                <p class="text-xs text-orange-500 mt-1 flex items-center"><?= $pendingOrders ?> đơn đang chờ xử lý</p>
            </div>
        </div>
        <!-- Khách hàng -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
            <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center text-purple-500 text-xl flex-shrink-0">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Tổng thành viên</p>
                <p class="text-xl font-bold text-gray-900 mt-0.5"><?= $totalUsers ?></p>
            </div>
        </div>
        <!-- Sản phẩm -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
            <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 text-xl flex-shrink-0">
                <i class="fa-solid fa-laptop"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Tổng sản phẩm</p>
                <p class="text-xl font-bold text-gray-900 mt-0.5"><?= $totalProducts ?></p>
                <p class="text-xs <?= $lowStockProducts > 0 ? 'text-red-500' : 'text-green-500' ?> mt-1 flex items-center">
                    <?= $lowStockProducts ?> sản phẩm sắp hết hàng
                </p>
            </div>
        </div>
    </div>

    <!-- Charts & Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-800">Biểu đồ doanh thu 7 ngày qua</h3>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Mini Recent Orders -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-800">Đơn hàng mới nhất</h3>
                <button onclick="navigateAdmin('orders')" class="text-sm text-primary hover:underline">Xem tất cả</button>
            </div>
            <div class="space-y-4">
                <?php if (count($recentOrders) > 0): ?>
                    <?php foreach ($recentOrders as $order): ?>
                        <div class="flex items-center justify-between pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                            <div>
                                <p class="text-sm font-medium text-gray-900">#DH<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?></p>
                                <p class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars($order['customer_name']) ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-primary"><?= number_format($order['total_amount'], 0, ',', '.') ?> ₫</p>
                                <span class="inline-block px-2 py-1 <?= $statusMap[$order['status']]['class'] ?> text-[10px] font-bold rounded mt-1">
                                    <?= $statusMap[$order['status']]['label'] ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-sm text-gray-500 text-center py-4">Chưa có đơn hàng nào.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
    // Hàm vẽ biểu đồ với dữ liệu thực tế từ PHP
    function initRealDashboardChart() {
        const ctx = document.getElementById('revenueChart');
        if(!ctx) return;
        
        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: <?= json_encode($chartLabels) ?>,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: <?= json_encode($chartValues) ?>,
                    borderColor: '#2563eb', // primary color
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#2563eb',
                    pointRadius: 4,
                    pointHoverRadius: 6
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
                                return (value / 1000000) + ' Tr'; 
                            }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }
</script>