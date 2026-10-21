<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/order_status.php';
require_once __DIR__ . '/../../config/banks.php';

// 1. LẤY DANH SÁCH ĐƠN HÀNG (kèm tài khoản đã đặt đơn)
$sqlOrders = "SELECT o.*, u.name AS account_name, u.email AS account_email
              FROM orders o LEFT JOIN users u ON u.id = o.user_id
              ORDER BY o.created_at DESC";
$orders = $pdo->query($sqlOrders)->fetchAll(PDO::FETCH_ASSOC);

// 2. LẤY CHI TIẾT ĐƠN HÀNG ĐỂ HIỂN THỊ TRONG MODAL
$sqlDetails = "SELECT od.*, p.name, p.img, p.spec, b.name AS brand_name
               FROM order_details od
               JOIN products p ON od.product_id = p.id
               LEFT JOIN brands b ON b.id = p.brand_id";
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
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[92vh] flex flex-col overflow-hidden transform scale-95 transition-transform m-4" id="order-detail-content">
            <div class="flex justify-between items-start p-5 border-b border-gray-100 bg-gray-50">
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h3 class="text-lg font-bold text-gray-800" id="detail-modal-title"></h3>
                        <span id="detail-status-badge"></span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1" id="detail-created-at"></p>
                </div>
                <button onclick="closeOrderDetailModal()" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <div class="p-6 overflow-y-auto space-y-6">
                <!-- Tiến trình xử lý -->
                <div id="detail-progress"></div>

                <!-- Khách hàng & Thanh toán -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border border-gray-100 rounded-xl p-4">
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3"><i class="fa-solid fa-location-dot text-primary mr-1"></i> Thông tin nhận hàng</h4>
                        <dl class="space-y-2 text-sm">
                            <div class="flex gap-2"><dt class="w-20 shrink-0 text-gray-500">Người nhận</dt><dd class="font-semibold text-gray-900" id="detail-customer-name"></dd></div>
                            <div class="flex gap-2"><dt class="w-20 shrink-0 text-gray-500">Điện thoại</dt><dd class="text-gray-900" id="detail-customer-phone"></dd></div>
                            <div class="flex gap-2"><dt class="w-20 shrink-0 text-gray-500">Địa chỉ</dt><dd class="text-gray-900" id="detail-customer-address"></dd></div>
                            <div class="flex gap-2"><dt class="w-20 shrink-0 text-gray-500">Tài khoản</dt><dd class="text-gray-900" id="detail-account"></dd></div>
                        </dl>
                    </div>
                    <div class="border border-gray-100 rounded-xl p-4">
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3"><i class="fa-solid fa-credit-card text-primary mr-1"></i> Thanh toán</h4>
                        <dl class="space-y-2 text-sm">
                            <div class="flex gap-2"><dt class="w-20 shrink-0 text-gray-500">Hình thức</dt><dd class="font-semibold text-gray-900" id="detail-payment-method"></dd></div>
                            <div class="flex gap-2"><dt class="w-20 shrink-0 text-gray-500">Tình trạng</dt><dd id="detail-payment-status"></dd></div>
                        </dl>
                        <div id="detail-bank-info" class="hidden mt-3 p-3 rounded-lg bg-blue-50 text-xs text-gray-700 space-y-1"></div>
                    </div>
                </div>

                <!-- Sản phẩm -->
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3"><i class="fa-solid fa-box-open text-primary mr-1"></i> Sản phẩm (<span id="detail-item-count">0</span>)</h4>
                    <div class="border border-gray-100 rounded-xl overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                                <tr>
                                    <th class="px-4 py-2.5 text-left font-semibold">Sản phẩm</th>
                                    <th class="px-4 py-2.5 text-right font-semibold whitespace-nowrap">Đơn giá</th>
                                    <th class="px-4 py-2.5 text-center font-semibold">SL</th>
                                    <th class="px-4 py-2.5 text-right font-semibold whitespace-nowrap">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" id="detail-items-list"></tbody>
                        </table>
                    </div>
                    <div class="mt-4 ml-auto max-w-xs space-y-1.5 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Tạm tính</span><span class="text-gray-900" id="detail-subtotal"></span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Phí giao hàng</span><span class="text-green-600 font-medium">Miễn phí</span></div>
                        <div class="flex justify-between border-t border-gray-100 pt-2"><span class="font-bold text-gray-800">Tổng cộng</span><span class="font-bold text-primary text-lg" id="detail-total-amount"></span></div>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                <button onclick="closeOrderDetailModal()" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">Đóng</button>
                <button id="detail-update-status-btn" class="px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-primary hover:bg-secondary shadow-md transition-colors flex items-center">
                    <i class="fa-solid fa-pen-clip mr-2"></i> Cập nhật trạng thái
                </button>
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
    const orderSteps = <?= json_encode(ORDER_STEPS) ?>;
    const shopBanks = <?= json_encode(SHOP_BANKS, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const orderCode = (id) => '#DH' + String(id).padStart(4, '0');

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

    // Thanh tiến trình: Chờ xác nhận -> Đã xác nhận -> Đang giao -> Đã giao (đơn huỷ hiện khung đỏ)
    function orderProgressHTML(statusKey) {
        if (statusKey === 'cancelled') {
            return `<div class="flex items-center gap-2 p-3 rounded-lg bg-red-50 text-red-700 text-sm"><i class="fa-solid fa-ban"></i>Đơn hàng đã bị huỷ, sản phẩm đã được hoàn lại kho.</div>`;
        }
        const current = orderSteps.indexOf(statusKey);
        return '<div class="flex items-start">' + orderSteps.map((key, i) => {
            const done = i <= current;
            const circle = done ? 'bg-primary text-white' : 'bg-gray-100 text-gray-400';
            const text = i === current ? 'text-primary font-semibold' : (done ? 'text-gray-700' : 'text-gray-400');
            const line = i < orderSteps.length - 1
                ? `<div class="flex-1 flex items-center h-9"><div class="flex-1 h-1 mx-2 rounded ${i < current ? 'bg-primary' : 'bg-gray-200'}"></div></div>` : '';
            return `
                <div class="flex flex-col items-center w-20 shrink-0">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center ${circle}"><i class="fa-solid ${orderStatuses[key].icon} text-sm"></i></div>
                    <span class="text-xs mt-1.5 text-center leading-tight ${text}">${orderStatuses[key].label}</span>
                </div>${line}`;
        }).join('') + '</div>';
    }

    // Modal Chi tiết đơn
    function openOrderDetailModal(orderId) {
        const order = dbOrders.find(o => o.id == orderId);
        if(!order) return;

        const status = orderStatuses[order.status] || { label: order.status, badge: 'bg-gray-100 text-gray-700', icon: 'fa-circle', desc: '' };
        const notProvided = '<span class="text-gray-400 italic">Chưa có</span>';

        // Tiêu đề + trạng thái
        document.getElementById('detail-modal-title').innerText = `Đơn hàng ${orderCode(order.id)}`;
        document.getElementById('detail-status-badge').innerHTML =
            `<span class="px-2.5 py-1 inline-flex items-center text-xs font-semibold rounded-full ${status.badge}"><i class="fa-solid ${status.icon} mr-1.5"></i>${status.label}</span>`;
        document.getElementById('detail-created-at').innerText =
            'Đặt lúc ' + new Date(order.created_at.replace(' ', 'T')).toLocaleString('vi-VN', { dateStyle: 'full', timeStyle: 'short' });
        document.getElementById('detail-progress').innerHTML = orderProgressHTML(order.status);

        // Thông tin nhận hàng
        document.getElementById('detail-customer-name').innerText = order.customer_name || '';
        document.getElementById('detail-customer-phone').innerHTML = order.phone
            ? `<a href="tel:${esc(order.phone)}" class="text-primary hover:underline">${esc(order.phone)}</a>` : notProvided;
        document.getElementById('detail-customer-address').innerHTML = order.address ? esc(order.address) : notProvided;
        document.getElementById('detail-account').innerHTML = order.account_email
            ? `${esc(order.account_name)}<br><span class="text-xs text-gray-500">${esc(order.account_email)}</span>` : notProvided;

        // Thanh toán: COD thu khi giao; chuyển khoản cần đối soát trước khi xác nhận
        const isBank = !!order.bank_code || order.payment_method.startsWith('Chuyển khoản');
        document.getElementById('detail-payment-method').innerText = order.payment_method;
        let paymentStatus;
        if (order.status === 'cancelled') paymentStatus = '<span class="text-gray-500">Không thu tiền (đơn đã huỷ)</span>';
        else if (order.status === 'completed') paymentStatus = '<span class="text-green-600 font-medium"><i class="fa-solid fa-circle-check mr-1"></i>Đã thanh toán</span>';
        else if (isBank && order.status === 'pending') paymentStatus = '<span class="text-amber-600 font-medium"><i class="fa-solid fa-clock mr-1"></i>Chờ khách chuyển khoản</span>';
        else if (isBank) paymentStatus = '<span class="text-green-600 font-medium"><i class="fa-solid fa-circle-check mr-1"></i>Đã nhận chuyển khoản</span>';
        else paymentStatus = '<span class="text-amber-600 font-medium"><i class="fa-solid fa-money-bill-wave mr-1"></i>Thu tiền khi giao hàng</span>';
        document.getElementById('detail-payment-status').innerHTML = paymentStatus;

        const bank = shopBanks[order.bank_code];
        const bankBox = document.getElementById('detail-bank-info');
        bankBox.classList.toggle('hidden', !bank);
        if (bank) {
            bankBox.innerHTML = `
                <p><span class="text-gray-500">Tài khoản nhận:</span> <b>${esc(bank.name)}</b> - ${esc(bank.account_no)}</p>
                <p><span class="text-gray-500">Chủ tài khoản:</span> ${esc(bank.account_name)}</p>
                <p><span class="text-gray-500">Nội dung cần đối soát:</span> <b>${orderCode(order.id).slice(1)}</b></p>`;
        }

        // Sản phẩm
        const items = dbOrderDetails[orderId] || [];
        document.getElementById('detail-item-count').innerText = items.reduce((sum, item) => sum + Number(item.quantity), 0);
        document.getElementById('detail-items-list').innerHTML = items.map(item => `
            <tr>
                <td class="px-4 py-3">
                    <div class="flex items-center min-w-[220px]">
                        <img src="${esc(item.img)}" alt="" class="w-12 h-12 object-contain rounded border border-gray-100 bg-gray-50 mr-3 shrink-0" onerror="this.src='../assets/placeholder-laptop.svg'">
                        <div>
                            <p class="font-medium text-gray-900">${esc(item.name)}</p>
                            <p class="text-xs text-gray-500">TN${String(item.product_id).padStart(4, '0')}${item.brand_name ? ' · ' + esc(item.brand_name) : ''}</p>
                            ${item.spec ? `<p class="text-xs text-gray-400 line-clamp-1">${esc(item.spec)}</p>` : ''}
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-right whitespace-nowrap text-gray-700">${formatMoney(item.price)}</td>
                <td class="px-4 py-3 text-center font-semibold">${item.quantity}</td>
                <td class="px-4 py-3 text-right whitespace-nowrap font-semibold text-gray-900">${formatMoney(item.price * item.quantity)}</td>
            </tr>
        `).join('') || '<tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Đơn hàng không có sản phẩm.</td></tr>';

        const subtotal = items.reduce((sum, item) => sum + item.price * item.quantity, 0);
        document.getElementById('detail-subtotal').innerText = formatMoney(subtotal);
        document.getElementById('detail-total-amount').innerText = formatMoney(order.total_amount);

        document.getElementById('detail-update-status-btn').onclick = () => {
            closeOrderDetailModal();
            setTimeout(() => openOrderStatusModal(order.id, order.status), 300);
        };

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