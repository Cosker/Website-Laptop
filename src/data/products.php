<?php
/**
 * Nguồn dữ liệu sản phẩm và hãng laptop (dùng chung cho trang khách và trang admin).
 *
 * Hiện dữ liệu nằm trong mảng PHP. Khi có database, chỉ cần viết lại
 * getBrands(), getProducts(), getProductById() để truy vấn bảng
 * `brands` và `products` -- phần giao diện không cần sửa.
 *
 * Quy ước các trường của sản phẩm:
 *   id, brand (slug của hãng), name, price (VNĐ), stock (tồn kho),
 *   sold (số đã bán, dùng để lấy mục "Bán chạy"), img, spec, desc
 *
 * `img` có thể là link ảnh online hoặc đường dẫn ảnh trong website,
 * ví dụ 'assets/images/macbook-air-m2.jpg'. Nếu ảnh lỗi, giao diện tự
 * hiện ảnh dự phòng assets/placeholder-laptop.svg.
 *
 * LƯU Ý: giá, tồn kho, số đã bán là dữ liệu mẫu để làm giao diện.
 */

function getBrands(): array
{
    return [
        ['slug' => 'apple',  'name' => 'Apple (MacBook)', 'short' => 'Apple',  'desc' => 'MacBook Air, MacBook Pro'],
        ['slug' => 'dell',   'name' => 'Dell',            'short' => 'Dell',   'desc' => 'XPS, Inspiron, Alienware'],
        ['slug' => 'asus',   'name' => 'Asus',            'short' => 'Asus',   'desc' => 'Zenbook, Vivobook, ROG'],
        ['slug' => 'lenovo', 'name' => 'Lenovo',          'short' => 'Lenovo', 'desc' => 'ThinkPad, IdeaPad, Legion'],
        ['slug' => 'hp',     'name' => 'HP',              'short' => 'HP',     'desc' => 'Envy, Pavilion, Omen'],
        ['slug' => 'acer',   'name' => 'Acer',            'short' => 'Acer',   'desc' => 'Aspire, Swift, Nitro'],
        ['slug' => 'msi',    'name' => 'MSI',             'short' => 'MSI',    'desc' => 'Modern, Katana, Thin'],
    ];
}

function getProducts(): array
{
    $u = fn(string $id) => "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&w=500&q=80";

    // Ảnh minh hoạ (Unsplash). Nên thay bằng ảnh thật của từng sản phẩm.
    $imgMacPro  = $u('1517336714731-489689fd1ca8');
    $imgMacAir  = $u('1525547719571-a2d4ac8945e2');
    $imgDell    = $u('1588872657578-7efd1f1555ed');
    $imgDellB   = $u('1593642632823-8f785ba67e45');
    $imgRog     = $u('1603302576837-37561b2e2302');
    $imgThink   = $u('1588872657578-7efd1f1555ed');
    $imgHp      = $u('1517336714731-489689fd1ca8');
    $imgGeneric = $u('1496181133206-80ce9b88a853');
    $imgCode    = $u('1498050108023-c5249f4df085');
    $imgDesk    = $u('1541807084-5c52b6b3adef');

    return [
        // ---------- APPLE ----------
        ['id' => 1,  'brand' => 'apple',  'name' => 'MacBook Pro 14 M2 Pro',           'price' => 45990000, 'stock' => 12, 'sold' => 86,
         'img' => $imgMacPro, 'spec' => 'Apple M2 Pro / 16GB RAM / 512GB SSD',
         'desc' => 'Sức mạnh siêu việt từ chip M2 Pro giúp bạn xử lý mượt mà mọi tác vụ đồ hoạ nặng nhất.'],
        ['id' => 6,  'brand' => 'apple',  'name' => 'MacBook Air 13 M2',               'price' => 26990000, 'stock' => 20, 'sold' => 140,
         'img' => $imgMacAir, 'spec' => 'Apple M2 / 8GB RAM / 256GB SSD',
         'desc' => 'Thiết kế mỏng nhẹ, pin dùng cả ngày, phù hợp sinh viên và dân văn phòng cần máy gọn.'],
        ['id' => 7,  'brand' => 'apple',  'name' => 'MacBook Pro 16 M2 Max',           'price' => 79990000, 'stock' => 4,  'sold' => 18,
         'img' => $imgCode, 'spec' => 'Apple M2 Max / 32GB RAM / 1TB SSD',
         'desc' => 'Màn hình lớn, hiệu năng cực mạnh cho dựng video, 3D và lập trình các dự án nặng.'],

        // ---------- DELL ----------
        ['id' => 2,  'brand' => 'dell',   'name' => 'Dell XPS 13 Plus 9320',           'price' => 39500000, 'stock' => 5,  'sold' => 47,
         'img' => $imgDell, 'spec' => 'Core i7-1280P / 16GB / 512GB',
         'desc' => 'Thiết kế đến từ tương lai với thanh Touchbar ẩn và Trackpad vô cực tuyệt đẹp.'],
        ['id' => 8,  'brand' => 'dell',   'name' => 'Dell Inspiron 15 3520',           'price' => 14990000, 'stock' => 30, 'sold' => 120,
         'img' => $imgDellB, 'spec' => 'Core i5-1235U / 8GB / 512GB SSD',
         'desc' => 'Mẫu laptop học tập, văn phòng giá hợp lý với màn hình 15.6 inch rộng rãi.'],
        ['id' => 9,  'brand' => 'dell',   'name' => 'Dell Alienware m15 R7',           'price' => 52990000, 'stock' => 3,  'sold' => 12,
         'img' => $imgRog, 'spec' => 'Core i7-12700H / RTX 3070 Ti / 16GB / 1TB',
         'desc' => 'Dòng gaming cao cấp với hiệu năng mạnh mẽ và thiết kế nổi bật đặc trưng.'],

        // ---------- ASUS ----------
        ['id' => 3,  'brand' => 'asus',   'name' => 'Asus ROG Strix G15',              'price' => 28990000, 'stock' => 0,  'sold' => 95,
         'img' => $imgRog, 'spec' => 'Ryzen 7 6800H / RTX 3060 / 16GB',
         'desc' => 'Cỗ máy chiến game hoàn hảo với hệ thống tản nhiệt thông minh và màn hình 165Hz.'],
        ['id' => 10, 'brand' => 'asus',   'name' => 'Asus Zenbook 14 OLED',            'price' => 23990000, 'stock' => 14, 'sold' => 64,
         'img' => $imgDesk, 'spec' => 'Core i5-1240P / 16GB / 512GB SSD',
         'desc' => 'Màn hình OLED sống động, thân máy mỏng nhẹ, lý tưởng cho người hay di chuyển.'],
        ['id' => 11, 'brand' => 'asus',   'name' => 'Asus Vivobook 15',                'price' => 13990000, 'stock' => 26, 'sold' => 110,
         'img' => $imgGeneric, 'spec' => 'Core i5-1240P / 8GB / 512GB SSD',
         'desc' => 'Laptop phổ thông đáp ứng tốt nhu cầu học tập, làm việc văn phòng hằng ngày.'],

        // ---------- LENOVO ----------
        ['id' => 4,  'brand' => 'lenovo', 'name' => 'Lenovo ThinkPad X1 Carbon Gen 10', 'price' => 42000000, 'stock' => 8, 'sold' => 52,
         'img' => $imgThink, 'spec' => 'Core i7-1260P / 16GB / 1TB',
         'desc' => 'Đẳng cấp doanh nhân với độ bền đạt chuẩn quân đội, bàn phím gõ tốt nhất thế giới.'],
        ['id' => 12, 'brand' => 'lenovo', 'name' => 'Lenovo IdeaPad Slim 5',           'price' => 16990000, 'stock' => 18, 'sold' => 72,
         'img' => $imgDesk, 'spec' => 'Ryzen 5 7530U / 16GB / 512GB SSD',
         'desc' => 'Vỏ kim loại gọn nhẹ, cấu hình cân bằng giữa hiệu năng và thời lượng pin.'],
        ['id' => 13, 'brand' => 'lenovo', 'name' => 'Lenovo Legion 5',                 'price' => 31990000, 'stock' => 9,  'sold' => 58,
         'img' => $imgCode, 'spec' => 'Ryzen 7 6800H / RTX 3060 / 16GB / 512GB',
         'desc' => 'Laptop gaming nổi tiếng về tản nhiệt tốt và bàn phím gõ thoải mái.'],

        // ---------- HP ----------
        ['id' => 5,  'brand' => 'hp',     'name' => 'HP Envy x360 13',                 'price' => 22500000, 'stock' => 24, 'sold' => 66,
         'img' => $imgHp, 'spec' => 'Core i5-1230U / 16GB / 512GB SSD',
         'desc' => 'Màn hình cảm ứng xoay gập 360 độ, vừa là laptop vừa là máy tính bảng.'],
        ['id' => 14, 'brand' => 'hp',     'name' => 'HP Pavilion 15',                  'price' => 15990000, 'stock' => 22, 'sold' => 88,
         'img' => $imgGeneric, 'spec' => 'Core i5-1235U / 16GB / 512GB SSD',
         'desc' => 'Thiết kế thanh lịch, cấu hình ổn định cho công việc văn phòng và giải trí.'],
        ['id' => 15, 'brand' => 'hp',     'name' => 'HP Omen 16',                      'price' => 33990000, 'stock' => 6,  'sold' => 31,
         'img' => $imgRog, 'spec' => 'Core i7-12700H / RTX 3060 / 16GB / 512GB',
         'desc' => 'Laptop gaming màn hình lớn, hiệu năng mạnh cho cả chơi game lẫn sáng tạo nội dung.'],

        // ---------- ACER ----------
        ['id' => 16, 'brand' => 'acer',   'name' => 'Acer Aspire 5 A515',              'price' => 12990000, 'stock' => 35, 'sold' => 130,
         'img' => $imgDellB, 'spec' => 'Core i5-1235U / 8GB / 512GB SSD',
         'desc' => 'Lựa chọn tiết kiệm cho sinh viên với cấu hình đủ dùng và độ bền tốt.'],
        ['id' => 17, 'brand' => 'acer',   'name' => 'Acer Nitro 5 AN515',              'price' => 22990000, 'stock' => 15, 'sold' => 99,
         'img' => $imgRog, 'spec' => 'Core i5-12500H / RTX 3050 / 8GB / 512GB',
         'desc' => 'Laptop gaming giá dễ tiếp cận, màn hình tần số quét cao chơi game mượt mà.'],
        ['id' => 18, 'brand' => 'acer',   'name' => 'Acer Swift 3 SF314',              'price' => 18990000, 'stock' => 11, 'sold' => 41,
         'img' => $imgDesk, 'spec' => 'Core i5-1240P / 16GB / 512GB SSD',
         'desc' => 'Mỏng nhẹ, vỏ kim loại, phù hợp người cần máy gọn để mang theo mỗi ngày.'],

        // ---------- MSI ----------
        ['id' => 19, 'brand' => 'msi',    'name' => 'MSI Modern 14',                   'price' => 14490000, 'stock' => 19, 'sold' => 45,
         'img' => $imgGeneric, 'spec' => 'Core i5-1235U / 16GB / 512GB SSD',
         'desc' => 'Thiết kế tối giản, nhẹ, hợp với học tập và làm việc văn phòng.'],
        ['id' => 20, 'brand' => 'msi',    'name' => 'MSI Katana GF66',                 'price' => 27990000, 'stock' => 7,  'sold' => 38,
         'img' => $imgCode, 'spec' => 'Core i7-12700H / RTX 3060 / 16GB / 512GB',
         'desc' => 'Dòng gaming mạnh mẽ với thiết kế góc cạnh và hệ thống tản nhiệt hiệu quả.'],
        ['id' => 21, 'brand' => 'msi',    'name' => 'MSI Thin GF63',                   'price' => 17990000, 'stock' => 13, 'sold' => 77,
         'img' => $imgHp, 'spec' => 'Core i5-12450H / RTX 3050 / 8GB / 512GB',
         'desc' => 'Laptop gaming mỏng nhẹ, giá hợp lý cho người mới bắt đầu chơi game.'],
    ];
}

function getProductById(int $id): ?array
{
    foreach (getProducts() as $product) {
        if ($product['id'] === $id) {
            return $product;
        }
    }
    return null;
}
