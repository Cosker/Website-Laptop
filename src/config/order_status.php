<?php
// Các trạng thái đơn hàng (giá trị cột orders.status), theo đúng thứ tự xử lý.
// Dùng chung cho trang khách và trang admin để nhãn / màu hiển thị luôn khớp nhau.
//   label: tên hiển thị, desc: mô tả cho khách, badge: class màu Tailwind, icon: FontAwesome
const ORDER_STATUSES = [
    'pending' => [
        'label' => 'Chờ xác nhận',
        'desc' => 'Cửa hàng đã nhận đơn và sẽ gọi xác nhận với bạn.',
        'badge' => 'bg-yellow-100 text-yellow-800',
        'icon' => 'fa-hourglass-half',
    ],
    'confirmed' => [
        'label' => 'Đã xác nhận',
        'desc' => 'Đơn đã được xác nhận, đang đóng gói sản phẩm.',
        'badge' => 'bg-indigo-100 text-indigo-800',
        'icon' => 'fa-clipboard-check',
    ],
    'shipping' => [
        'label' => 'Đang giao',
        'desc' => 'Đơn hàng đã bàn giao cho đơn vị vận chuyển.',
        'badge' => 'bg-blue-100 text-blue-800',
        'icon' => 'fa-truck-fast',
    ],
    'completed' => [
        'label' => 'Đã giao',
        'desc' => 'Giao hàng thành công. Cảm ơn bạn đã mua sắm tại TechNova!',
        'badge' => 'bg-green-100 text-green-800',
        'icon' => 'fa-circle-check',
    ],
    'cancelled' => [
        'label' => 'Đã huỷ',
        'desc' => 'Đơn hàng đã bị huỷ.',
        'badge' => 'bg-red-100 text-red-800',
        'icon' => 'fa-ban',
    ],
];

// Các bước của một đơn bình thường (không tính huỷ) -- dùng để vẽ thanh tiến trình
const ORDER_STEPS = ['pending', 'confirmed', 'shipping', 'completed'];
