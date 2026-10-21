-- Nâng cấp DB technova để trang khách dùng DB thật:
--   * products: thêm cấu hình (spec) + mô tả (description)
--   * brands:   thêm mô tả dòng máy
--   * users:    thêm số điện thoại, địa chỉ (mật khẩu cũ dạng chữ thường sẽ tự
--               được mã hoá bằng password_hash ở lần đăng nhập đầu tiên)
--   * orders:   thêm thông tin nhận hàng, phương thức thanh toán; đơn mới mặc định 'pending'
-- Chạy SAU technova.sql và articles.sql.

ALTER TABLE `products`
  ADD COLUMN `spec` varchar(255) DEFAULT NULL AFTER `stock`,
  ADD COLUMN `description` text DEFAULT NULL AFTER `spec`;

ALTER TABLE `brands`
  ADD COLUMN `description` varchar(255) DEFAULT NULL AFTER `slug`;

ALTER TABLE `users`
  ADD COLUMN `phone` varchar(20) DEFAULT NULL AFTER `password`,
  ADD COLUMN `address` varchar(255) DEFAULT NULL AFTER `phone`;

ALTER TABLE `orders`
  ADD COLUMN `phone` varchar(20) DEFAULT NULL AFTER `customer_name`,
  ADD COLUMN `address` varchar(255) DEFAULT NULL AFTER `phone`,
  ADD COLUMN `payment_method` varchar(50) NOT NULL DEFAULT 'COD' AFTER `total_amount`,
  MODIFY `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending';

UPDATE `brands` SET `description` = CASE `slug`
  WHEN 'apple'  THEN 'MacBook Air, MacBook Pro'
  WHEN 'dell'   THEN 'XPS, Inspiron, Alienware'
  WHEN 'asus'   THEN 'Zenbook, Vivobook, ROG'
  WHEN 'hp'     THEN 'Envy, Pavilion, Omen'
  WHEN 'lenovo' THEN 'ThinkPad, IdeaPad, Legion'
  ELSE `description` END;

UPDATE `products` p JOIN (
  SELECT 1 AS id, 'Apple M2 Pro / 16GB RAM / 512GB SSD' AS spec, 'Sức mạnh từ chip M2 Pro giúp xử lý mượt mà các tác vụ đồ hoạ, dựng video và lập trình nặng.' AS description
  UNION ALL SELECT 2, 'Apple M2 / 8GB RAM / 256GB SSD', 'Thiết kế mỏng nhẹ, pin dùng cả ngày, phù hợp sinh viên và dân văn phòng cần máy gọn.'
  UNION ALL SELECT 3, 'Apple M2 Max / 32GB RAM / 1TB SSD', 'Màn hình 16 inch, hiệu năng cực mạnh cho dựng video, 3D và các dự án chuyên nghiệp.'
  UNION ALL SELECT 4, 'Apple M1 / 8GB RAM / 256GB SSD', 'Chiếc MacBook giá dễ tiếp cận nhất, không quạt, êm ái và pin rất bền.'
  UNION ALL SELECT 5, 'Core i7-1280P / 16GB / 512GB SSD', 'Thiết kế tương lai với hàng phím cảm ứng và trackpad liền mạch, màn hình sắc nét.'
  UNION ALL SELECT 6, 'Core i5-1235U / 8GB / 512GB SSD', 'Laptop học tập, văn phòng giá hợp lý với màn hình 15.6 inch rộng rãi.'
  UNION ALL SELECT 7, 'Core i7-12700H / RTX 3070 Ti / 16GB / 1TB', 'Dòng gaming cao cấp với hiệu năng mạnh mẽ và thiết kế đặc trưng Alienware.'
  UNION ALL SELECT 8, 'Core i5-1235U / 8GB / 512GB SSD', 'Laptop doanh nghiệp nhỏ gọn, bền bỉ, đủ cổng kết nối cho công việc hằng ngày.'
  UNION ALL SELECT 9, 'Ryzen 7 6800H / RTX 3060 / 16GB / 512GB', 'Cỗ máy chiến game với tản nhiệt thông minh và màn hình 165Hz.'
  UNION ALL SELECT 10, 'Core i5-1240P / 16GB / 512GB SSD / OLED', 'Màn hình OLED sống động, thân máy mỏng nhẹ, lý tưởng cho người hay di chuyển.'
  UNION ALL SELECT 11, 'Core i5-11400H / RTX 3050 / 8GB / 512GB', 'Laptop gaming bền chuẩn quân đội, giá tốt cho game thủ và sinh viên kỹ thuật.'
  UNION ALL SELECT 12, 'Core i5-1240P / 8GB / 512GB SSD', 'Laptop phổ thông đáp ứng tốt nhu cầu học tập, làm việc văn phòng hằng ngày.'
  UNION ALL SELECT 13, 'Core i5-1230U / 16GB / 512GB SSD', 'Màn hình cảm ứng xoay gập 360 độ, vừa là laptop vừa là máy tính bảng.'
  UNION ALL SELECT 14, 'Core i5-1235U / 16GB / 512GB SSD', 'Thiết kế thanh lịch, cấu hình ổn định cho công việc văn phòng và giải trí.'
  UNION ALL SELECT 15, 'Core i7-12700H / RTX 3060 / 16GB / 512GB', 'Laptop gaming màn hình lớn, mạnh cho cả chơi game lẫn sáng tạo nội dung.'
  UNION ALL SELECT 16, 'Core i5-12500H / RTX 3050 / 16GB / 512GB', 'Hiệu năng gaming tốt trong tầm giá, thiết kế gọn gàng, dễ mang đi học.'
  UNION ALL SELECT 17, 'Core i7-1260P / 16GB / 1TB SSD', 'Đẳng cấp doanh nhân với độ bền chuẩn quân đội và bàn phím gõ rất tốt.'
  UNION ALL SELECT 18, 'Ryzen 7 6800H / RTX 3060 / 16GB / 512GB', 'Laptop gaming nổi tiếng về tản nhiệt tốt và bàn phím gõ thoải mái.'
  UNION ALL SELECT 19, 'Core i3-1215U / 8GB / 256GB SSD', 'Lựa chọn tiết kiệm cho học online và các tác vụ văn phòng cơ bản.'
  UNION ALL SELECT 20, 'Core i5-1235U / 16GB / 512GB SSD', 'Xoay gập linh hoạt, màn hình cảm ứng, phù hợp người làm việc sáng tạo.'
  UNION ALL SELECT 21, 'Apple M2 / 8GB RAM / 256GB SSD', 'Có quạt tản nhiệt và Touch Bar, duy trì hiệu năng ổn định khi làm việc lâu.'
  UNION ALL SELECT 22, 'Core i7-1185G7 / 16GB / 512GB SSD', 'Laptop doanh nghiệp cao cấp, bảo mật tốt, vỏ nhôm chắc chắn.'
  UNION ALL SELECT 23, 'Core i7-1165G7 / 16GB / 1TB SSD', 'Siêu nhẹ dưới 1kg, pin lâu, dành cho doanh nhân thường xuyên di chuyển.'
  UNION ALL SELECT 24, 'Core i5-1245U / 16GB / 512GB SSD', 'Laptop doanh nghiệp bền bỉ, nhiều tính năng bảo mật và quản trị từ xa.'
  UNION ALL SELECT 25, 'Core i5-1235U / 16GB / 512GB SSD', 'Thiết kế hiện đại, cấu hình cân bằng cho văn phòng và học tập.'
  UNION ALL SELECT 26, 'Apple M2 Max / 32GB RAM / 512GB SSD', 'Máy trạm nhỏ gọn cho dựng phim, đồ hoạ và lập trình chuyên nghiệp.'
  UNION ALL SELECT 27, 'Core i7-12800H / RTX A1000 / 32GB / 1TB', 'Máy trạm di động cho kỹ sư, kiến trúc sư với card đồ hoạ chuyên dụng.'
  UNION ALL SELECT 28, 'Ryzen 9 6900HS / RX 6700S / 16GB / 1TB', 'Laptop gaming 14 inch mạnh mẽ, nhỏ gọn, dễ mang theo.'
  UNION ALL SELECT 29, 'Core i5-1235U / 8GB / 512GB SSD', 'Laptop văn phòng giá tốt, bền, bàn phím có phím số tiện lợi.'
  UNION ALL SELECT 30, 'Ryzen 5 5625U / 8GB / 512GB SSD', 'ThinkPad giá mềm cho sinh viên và văn phòng, bàn phím gõ êm.'
  UNION ALL SELECT 31, 'Apple M2 / 16GB RAM / 512GB SSD', 'MacBook Pro cân bằng giữa hiệu năng và thời lượng pin.'
) s ON p.id = s.id
SET p.spec = s.spec, p.description = s.description;

-- Ảnh Unsplash: thêm tham số để tải ảnh nhỏ hơn
UPDATE `products` SET `img` = CONCAT(`img`, '?auto=format&fit=crop&w=500&q=80')
WHERE `img` LIKE 'https://images.unsplash.com/%' AND `img` NOT LIKE '%?%';
