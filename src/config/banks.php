<?php
// Tài khoản ngân hàng của cửa hàng để khách chọn khi thanh toán "Chuyển khoản ngân hàng".
// LƯU Ý: số tài khoản bên dưới là dữ liệu mẫu, cần thay bằng tài khoản thật của cửa hàng trước khi chạy thật.
const SHOP_BANKS = [
    'VCB' => [
        'name' => 'Vietcombank',
        'full_name' => 'Ngân hàng TMCP Ngoại thương Việt Nam',
        'account_no' => '1012345678',
        'account_name' => 'CONG TY TNHH TECHNOVA',
        'branch' => 'Chi nhánh TP. Hồ Chí Minh',
        'color' => '#007a3d',
    ],
    'TCB' => [
        'name' => 'Techcombank',
        'full_name' => 'Ngân hàng TMCP Kỹ thương Việt Nam',
        'account_no' => '19031234567890',
        'account_name' => 'CONG TY TNHH TECHNOVA',
        'branch' => 'Chi nhánh Gò Vấp',
        'color' => '#e31b23',
    ],
    'BIDV' => [
        'name' => 'BIDV',
        'full_name' => 'Ngân hàng TMCP Đầu tư và Phát triển Việt Nam',
        'account_no' => '31410001234567',
        'account_name' => 'CONG TY TNHH TECHNOVA',
        'branch' => 'Chi nhánh Bắc Sài Gòn',
        'color' => '#1f6f6f',
    ],
    'MB' => [
        'name' => 'MB Bank',
        'full_name' => 'Ngân hàng TMCP Quân đội',
        'account_no' => '0801234567890',
        'account_name' => 'CONG TY TNHH TECHNOVA',
        'branch' => 'Chi nhánh Sài Gòn',
        'color' => '#1e3a8a',
    ],
    'ACB' => [
        'name' => 'ACB',
        'full_name' => 'Ngân hàng TMCP Á Châu',
        'account_no' => '123456789',
        'account_name' => 'CONG TY TNHH TECHNOVA',
        'branch' => 'Chi nhánh Phú Nhuận',
        'color' => '#0b4ea2',
    ],
    'VTB' => [
        'name' => 'VietinBank',
        'full_name' => 'Ngân hàng TMCP Công thương Việt Nam',
        'account_no' => '104001234567',
        'account_name' => 'CONG TY TNHH TECHNOVA',
        'branch' => 'Chi nhánh 12',
        'color' => '#005b9a',
    ],
];
