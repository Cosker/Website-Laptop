<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/order_status.php';

// 1. LẤY DANH SÁCH ĐƠN HÀNG
$sqlOrders = "SELECT * FROM orders ORDER BY created_at DESC";
$orders = $pdo->query($sqlOrders)->fetchAll(PDO::FETCH_ASSOC);

// 2. LẤY CHI TIẾT ĐƠN HÀNG ĐỂ HIỂN THỊ TRONG MODAL
$sqlDetails = "SELECT od.*, p.name, p.img 
               FROM order_details od 
               JOIN products p ON od.product_id = p.id";
$details = $pdo->query($sqlDetails)->fetchAll(PDO::FETCH_ASSOC);

// Gom nhóm chi tiết theo order_id
$orderDetailsMap = [];
foreach ($details as $d) {
    $orderDetailsMap[$d['order_id']][] = $d;
}

// 3. ĐẾM THỐNG KÊ NHANH CÁC TRẠNG THÁI
$countAll = count($orders);
$statusCounts = array_fill_keys(array_keys(ORDER_STATUSES), 0);
foreach ($orders as $o) {
    if (isset($statusCounts[$o['status']])) {
        $statusCounts[$o['status']]++;
    }
}
?>

<section id="orders" class="admin-section">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Quản lý Đơn hàng</h2>
        <p class="text-sm text-gray-500 mt-1">Kiểm duyệt và xử lý đơn đặt hàng từ khách</p>
    </div>

    <!-- Bảng Đơn Hàng -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Các nút Filter -->
        <div class="p-4 border-b border-gray-100 flex flex-wrap gap-2" id="order-filters">
            <button onclick="filterOrders('all')" class="order-filter-btn px-4 py-2 rounded-lg text-sm font-medium border transition-colors bg-blue-50 text-primary border-blue-100" data-filter="all">Tất cả (<?= $countAll ?>)</button>
            <?php foreach (ORDER_STATUSES as $key => $info): ?>
                <button onclick="filterOrders('<?= $key ?>')" class="order-filter-btn px-4 py-2 bg-white text-gray-600 rounded-lg text-sm font-medium border border-gray-200 hover:bg-gray-50" data-filter="<?= $key ?>"><?= $info['label'] ?> (<?= $statusCounts[$key] ?>)</button>
            <?php endforeach; ?>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mã ĐH</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Khách hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white" id="admin-order-list">
                    <!-- Javascript render dữ liệu vào đây -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Chi Tiết Đơn Hàng -->
    <div id="order-detail-modal" class="fixed inset-0 bg-black/50 z-[100] hidden flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden transform scale-95 transition-transform" id="order-detail-content">
            <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800" id="detail-modal-title">Chi Tiết Đơn Hàng #</h3>
                <button onclick="closeOrderDetailModal()" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            
            <div class="p-6">
                <div class="flex justify-between items-start mb-6 pb-4 border-b border-gray-100">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Khách hàng</p>
                        <p class="font-bold text-gray-900 text-lg" id="detail-customer-name"></p>
                        <p class="text-sm text-gray-600 mt-1" id="detail-customer-contact"></p>
                        <p class="text-xs text-gray-500 mt-1" id="detail-payment-method"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 mb-1">Tổng cộng</p>
                        <p class="font-bold text-primary text-xl" id="detail-total-amount"></p>
                    </div>
                </div>

                <h4 class="font-bold text-gray-800 mb-3 text-sm uppercase">Danh sách sản phẩm</h4>
                <div class="space-y-4 max-h-64 overflow-y-auto pr-2" id="detail-items-list">
                    <!-- Items -->
                </div>
            </div>
            <div class="p-4 border-t border-gray-100 flex justify-end bg-gray-50">
                <button onclick="closeOrderDetailModal()" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">Đóng</button>
            </div>
        </div>
    </div>

    <!-- Modal Cập Nhật Trạng Thái -->
    <div id="order-status-modal" class="fixed inset-0 bg-black/50 z-[100] hidden flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform scale-95 transition-transform" id="order-status-content">
            <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-blue-50/50">
                <h3 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-truck-fast text-primary mr-2"></i> Cập Nhật Trạng Thái</h3>
                <button onclick="closeOrderStatusModal()" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            
            <form action="" method="POST" class="p-6">
                <input type="hidden" name="action" value="update_order_status">
                <input type="hidden" name="order_id" id="update_order_id" value="">
                
                <p class="text-gray-600 text-sm mb-4">Đơn hàng: <span class="font-bold text-dark" id="update_order_code"></span></p>

                <div class="space-y-3">
                    <?php foreach (ORDER_STATUSES as $key => $info): ?>
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="<?= $key ?>" class="w-4 h-4 text-primary">
                            <span class="ml-3 w-8 h-8 rounded-full flex items-center justify-center <?= $info['badge'] ?>"><i class="fa-solid <?= $info['icon'] ?> text-sm"></i></span>
                            <span class="ml-3">
                                <span class="block font-medium text-gray-800"><?= $info['label'] ?></span>
                                <span class="block text-xs text-gray-500"><?= $info['desc'] ?></span>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <button type="button" onclick="closeOrderStatusModal()" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">Hủy</button>
                    <button type="submit" class="px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-primary hover:bg-secondary shadow-md transition-colors flex items-center">
                        <i class="fa-solid fa-check mr-2"></i> Lưu Trạng Thái
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    // Dữ liệu từ PHP
    const dbOrders = <?= json_encode($orders) ?>;
    const dbOrderDetails = <?= json_encode($orderDetailsMap) ?>;

    // Trạng thái đơn hàng lấy từ config/order_status.php
    const orderStatuses = <?= json_encode(ORDER_STATUSES, JSON_UNESCAPED_UNICODE) ?>;

    function renderOrdersTable(filterStatus = 'all') {
        const tbody = document.getElementById('admin-order-list');
        
        // Lọc dữ liệu
        const filteredOrders = filterStatus === 'all' 
            ? dbOrders 
            : dbOrders.filter(o => o.status === filterStatus);

        if(filteredOrders.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-10 text-center text-gray-500">Không có đơn hàng nào.</td></tr>`;
            return;
        }

        tbody.innerHTML = filteredOrders.map(o => {
            const statusInfo = orderStatuses[o.status] || { label: o.status, badge: 'bg-gray-100 text-gray-700', icon: 'fa-circle' };
            const dateStr = new Date(o.created_at).toLocaleString('vi-VN', { dateStyle: 'short', timeStyle: 'short' });
            
            return `
                <tr>
                    <td class="px-6 py-4 text-sm font-bold text-gray-900">#DH${o.id.toString().padStart(4, '0')}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 font-medium">${esc(o.customer_name)}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">${dateStr}</td>
                    <td class="px-6 py-4 text-sm font-bold text-primary">${formatMoney(o.total_amount)}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full ${statusInfo.badge}"><i class="fa-solid ${statusInfo.icon} mr-1.5"></i>${statusInfo.label}</span>
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-medium">
                        <button onclick="openOrderStatusModal(${o.id}, '${o.status}')" class="text-blue-600 hover:text-blue-900 px-3 py-1.5 rounded mr-2 transition-colors border border-blue-200 hover:bg-blue-50" title="Cập nhật trạng thái">
                            <i class="fa-solid fa-pen-clip"></i>
                        </button>
                        <button onclick="openOrderDetailModal(${o.id})" class="text-gray-500 hover:text-primary transition-colors border border-gray-200 hover:bg-gray-50 px-3 py-1.5 rounded" title="Xem chi tiết">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // Logic Đổi màu Nút Filter
    function filterOrders(status) {
        document.querySelectorAll('.order-filter-btn').forEach(btn => {
            if(btn.dataset.filter === status) {
                btn.className = "order-filter-btn px-4 py-2 rounded-lg text-sm font-medium border transition-colors bg-blue-50 text-primary border-blue-100";
            } else {
                btn.className = "order-filter-btn px-4 py-2 bg-white text-gray-600 rounded-lg text-sm font-medium border border-gray-200 hover:bg-gray-50 transition-colors";
            }
        });
        renderOrdersTable(status);
    }

    // Modal Chi tiết đơn
    function openOrderDetailModal(orderId) {
        const order = dbOrders.find(o => o.id == orderId);
        if(!order) return;

        document.getElementById('detail-modal-title').innerText = `Chi Tiết Đơn Hàng #DH${order.id.toString().padStart(4, '0')}`;
        document.getElementById('detail-customer-name').innerText = order.customer_name;
        document.getElementById('detail-customer-contact').innerText = [order.phone, order.address].filter(Boolean).join(' · ');
        document.getElementById('detail-payment-method').innerText = 'Thanh toán: ' + order.payment_method;
        document.getElementById('detail-total-amount').innerText = formatMoney(order.total_amount);

        const items = dbOrderDetails[orderId] || [];
        const itemsHtml = items.map(item => `
            <div class="flex items-center justify-between p-3 border border-gray-100 rounded-xl bg-white shadow-sm">
                <div class="flex items-center">
                    <img src="${esc(item.img)}" class="w-12 h-12 object-contain rounded border border-gray-100 bg-gray-50 mr-3">
                    <div>
                        <p class="text-sm font-medium text-gray-900 line-clamp-1">${esc(item.name)}</p>
                        <p class="text-xs text-gray-500">Đơn giá: ${formatMoney(item.price)}</p>
                    </div>
                </div>
                <div class="text-right ml-4">
                    <p class="text-sm font-bold text-gray-900">x${item.quantity}</p>
                </div>
            </div>
        `).join('');

        document.getElementById('detail-items-list').innerHTML = itemsHtml;

        const modal = document.getElementById('order-detail-modal');
        const content = document.getElementById('order-detail-content');
        modal.classList.remove('hidden');
        setTimeout(() => { content.classList.remove('scale-95'); content.classList.add('scale-100'); }, 10);
    }

    function closeOrderDetailModal() {
        const modal = document.getElementById('order-detail-modal');
        const content = document.getElementById('order-detail-content');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    // Modal Cập nhật trạng thái
    function openOrderStatusModal(orderId, currentStatus) {
        document.getElementById('update_order_id').value = orderId;
        document.getElementById('update_order_code').innerText = `#DH${orderId.toString().padStart(4, '0')}`;
        
        document.querySelectorAll('#order-status-modal input[name="status"]').forEach(input => {
            input.checked = (input.value === currentStatus);
        });

        const modal = document.getElementById('order-status-modal');
        const content = document.getElementById('order-status-content');
        modal.classList.remove('hidden');
        setTimeout(() => { content.classList.remove('scale-95'); content.classList.add('scale-100'); }, 10);
    }

    function closeOrderStatusModal() {
        const modal = document.getElementById('order-status-modal');
        const content = document.getElementById('order-status-content');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    // Khởi tạo
    renderOrdersTable('all');
</script>