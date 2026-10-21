<?php
require_once __DIR__ . '/../../config/db.php';

// 1. LẤY DANH SÁCH KHÁCH HÀNG KÈM TỔNG ĐƠN VÀ TỔNG CHI TIÊU
$sqlCustomers = "SELECT u.id, u.name, u.email, u.created_at,
                        COUNT(o.id) AS total_orders,
                        COALESCE(SUM(CASE WHEN o.status = 'completed' THEN o.total_amount ELSE 0 END), 0) AS total_spent
                 FROM users u
                 LEFT JOIN orders o ON u.id = o.user_id
                 WHERE u.role = 'user'
                 GROUP BY u.id
                 ORDER BY u.id DESC";
$customers = $pdo->query($sqlCustomers)->fetchAll(PDO::FETCH_ASSOC);

// 2. LẤY TẤT CẢ ĐƠN HÀNG ĐỂ MAP VÀO MODAL LỊCH SỬ MUA HÀNG
$sqlCustomerOrders = "SELECT id, user_id, total_amount, status, created_at FROM orders ORDER BY created_at DESC";
$customerOrders = $pdo->query($sqlCustomerOrders)->fetchAll(PDO::FETCH_ASSOC);

$customerOrdersMap = [];
foreach ($customerOrders as $co) {
    $customerOrdersMap[$co['user_id']][] = $co;
}

// 3. TÍNH TOÁN THỐNG KÊ NHANH
$totalCustomers = count($customers);
$totalSpentAll = 0;
$activeBuyers = 0;

foreach ($customers as $c) {
    $totalSpentAll += $c['total_spent'];
    if ($c['total_orders'] > 0) {
        $activeBuyers++;
    }
}
$avgSpent = $totalCustomers > 0 ? round($totalSpentAll / $totalCustomers) : 0;
?>

<section id="customers" class="admin-section">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Quản lý Khách hàng</h2>
            <p class="text-sm text-gray-500 mt-1">Danh sách thành viên đăng ký và lịch sử giao dịch</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-primary text-xl flex-shrink-0">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Tổng khách hàng</p>
                <p class="text-2xl font-bold text-gray-900 mt-0.5"><?= number_format($totalCustomers) ?></p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-500 text-xl flex-shrink-0">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Đã phát sinh đơn hàng</p>
                <p class="text-2xl font-bold text-gray-900 mt-0.5"><?= number_format($activeBuyers) ?> <span class="text-xs font-normal text-gray-500">(<?= $totalCustomers > 0 ? round(($activeBuyers/$totalCustomers)*100) : 0 ?>%)</span></p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center text-purple-500 text-xl flex-shrink-0">
                <i class="fa-solid fa-calculator"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Chi tiêu trung bình/khách</p>
                <p class="text-xl font-bold text-gray-900 mt-0.5"><?= number_format($avgSpent, 0, ',', '.') ?> ₫</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between gap-4">
            <div class="relative max-w-xs w-full">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                <input id="admin-customer-search" oninput="renderCustomerTable()" type="text" placeholder="Tìm tên, email khách hàng..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Khách hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày tham gia</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Số đơn hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tổng chi tiêu</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white" id="admin-customer-list">
                    <!-- Rendered by JavaScript -->
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
            <span id="admin-customer-count"></span>
        </div>
    </div>

    <!-- Modal Lịch Sử Đơn Hàng Của Khách -->
    <div id="customer-history-modal" class="fixed inset-0 bg-black/50 z-[100] hidden flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden transform scale-95 transition-transform" id="customer-history-content">
            <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800" id="history-modal-title"><i class="fa-solid fa-clock-rotate-left text-primary mr-2"></i> Lịch Sử Đơn Hàng</h3>
                <button onclick="closeCustomerHistoryModal()" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            
            <div class="p-6">
                <div class="space-y-3 max-h-80 overflow-y-auto pr-1" id="history-orders-list">
                    <!-- Order Items Injected by JS -->
                </div>
            </div>

            <div class="p-4 border-t border-gray-100 flex justify-end bg-gray-50">
                <button onclick="closeCustomerHistoryModal()" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">Đóng</button>
            </div>
        </div>
    </div>

    <!-- Modal Xác Nhận Xóa Khách Hàng -->
    <div id="delete-customer-modal" class="fixed inset-0 bg-black/50 z-[100] hidden flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform" id="delete-customer-content">
            <div class="p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-red-100 text-red-500 flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="fa-solid fa-user-xmark"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Xóa khách hàng?</h3>
                <p class="text-gray-500 text-sm mb-6">Bạn có chắc chắn muốn xóa tài khoản này khỏi hệ thống không?</p>
                
                <form action="" method="POST" class="flex justify-center space-x-3">
                    <input type="hidden" name="action" value="delete_customer">
                    <input type="hidden" name="customer_id" id="delete_customer_id" value="">
                    
                    <button type="button" onclick="closeDeleteCustomerModal()" class="w-1/2 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">Hủy</button>
                    <button type="submit" class="w-1/2 px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-red-500 hover:bg-red-600 transition-colors shadow-md">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    const dbCustomers = <?= json_encode($customers) ?>;
    const dbCustomerOrdersMap = <?= json_encode($customerOrdersMap) ?>;

    const orderStatusMap = {
        'pending': { text: 'Chờ xử lý', class: 'bg-yellow-100 text-yellow-800' },
        'processing': { text: 'Đang giao', class: 'bg-blue-100 text-blue-800' },
        'completed': { text: 'Hoàn thành', class: 'bg-green-100 text-green-800' },
        'cancelled': { text: 'Đã hủy', class: 'bg-red-100 text-red-800' }
    };

    function renderCustomerTable() {
        const searchInput = document.getElementById('admin-customer-search');
        if (!searchInput) return;

        const keyword = searchInput.value.toLowerCase().trim();
        const tbody = document.getElementById('admin-customer-list');

        const filtered = dbCustomers.filter(c => {
            return c.name.toLowerCase().includes(keyword) || c.email.toLowerCase().includes(keyword);
        });

        if (filtered.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">Không tìm thấy khách hàng nào.</td></tr>';
        } else {
            tbody.innerHTML = filtered.map(c => {
                const dateStr = new Date(c.created_at).toLocaleDateString('vi-VN');
                const avatarLetter = c.name.charAt(0).toUpperCase();

                return `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-primary font-bold flex items-center justify-center text-base flex-shrink-0">
                                    ${avatarLetter}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900">${esc(c.name)}</div>
                                    <div class="text-xs text-gray-500">${esc(c.email)}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${dateStr}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">${c.total_orders} đơn</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-primary">${formatMoney(c.total_spent)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="openCustomerHistoryModal(${c.id}, '${addslashes(c.name)}')" class="text-blue-600 hover:text-blue-900 px-3 py-1.5 rounded mr-2 transition-colors border border-blue-200 hover:bg-blue-50" title="Xem lịch sử đơn hàng">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </button>
                            <button onclick="openDeleteCustomerModal(${c.id})" class="text-red-600 hover:text-red-900 px-3 py-1.5 rounded transition-colors border border-red-200 hover:bg-red-50" title="Xóa tài khoản">
                                <i class="fa-solid fa-user-xmark"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        const countEl = document.getElementById('admin-customer-count');
        if (countEl) countEl.innerText = `Hiển thị ${filtered.length} / ${dbCustomers.length} khách hàng`;
    }

    function addslashes(str) {
        return str.replace(/\\/g, '\\\\').replace(/\'/g, "\\'").replace(/\"/g, '\\"');
    }

    // Modal Lịch sử đơn hàng
    function openCustomerHistoryModal(userId, userName) {
        document.getElementById('history-modal-title').innerHTML = `<i class="fa-solid fa-clock-rotate-left text-primary mr-2"></i> Lịch Sử Đơn Hàng: <span class="text-primary">${userName}</span>`;
        const orders = dbCustomerOrdersMap[userId] || [];
        const container = document.getElementById('history-orders-list');

        if (orders.length === 0) {
            container.innerHTML = '<p class="text-center text-gray-500 py-6 text-sm">Khách hàng này chưa thực hiện đơn hàng nào.</p>';
        } else {
            container.innerHTML = orders.map(o => {
                const st = orderStatusMap[o.status] || { text: o.status, class: 'bg-gray-100 text-gray-800' };
                const dt = new Date(o.created_at).toLocaleString('vi-VN', { dateStyle: 'short', timeStyle: 'short' });
                return `
                    <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl bg-white shadow-sm">
                        <div>
                            <p class="font-bold text-gray-900 text-sm">#DH${o.id.toString().padStart(4, '0')}</p>
                            <p class="text-xs text-gray-500 mt-0.5">${dt}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-primary text-sm">${formatMoney(o.total_amount)}</p>
                            <span class="inline-block px-2 py-0.5 text-[11px] font-semibold rounded-full mt-1 ${st.class}">${st.text}</span>
                        </div>
                    </div>
                `;
            }).join('');
        }

        const modal = document.getElementById('customer-history-modal');
        const content = document.getElementById('customer-history-content');
        modal.classList.remove('hidden');
        setTimeout(() => { content.classList.remove('scale-95'); content.classList.add('scale-100'); }, 10);
    }

    function closeCustomerHistoryModal() {
        const modal = document.getElementById('customer-history-modal');
        const content = document.getElementById('customer-history-content');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    // Modal Xóa
    function openDeleteCustomerModal(id) {
        document.getElementById('delete_customer_id').value = id;
        const modal = document.getElementById('delete-customer-modal');
        const content = document.getElementById('delete-customer-content');
        modal.classList.remove('hidden');
        setTimeout(() => { content.classList.remove('scale-95'); content.classList.add('scale-100'); }, 10);
    }

    function closeDeleteCustomerModal() {
        const modal = document.getElementById('delete-customer-modal');
        const content = document.getElementById('delete-customer-content');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    renderCustomerTable();
</script>