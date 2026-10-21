-- Thông số kỹ thuật chi tiết (cột specs, mỗi dòng "Tên: giá trị") và mô tả dài hơn cho sản phẩm mẫu.
-- Chạy SAU migration_v2.sql.

ALTER TABLE `products` ADD COLUMN `specs` text DEFAULT NULL COMMENT 'Mỗi dòng: Tên thông số: giá trị' AFTER `spec`;

UPDATE `products` SET `specs` = 'CPU: Apple M2 Pro 10 nhân CPU
RAM: 16GB Unified Memory
Ổ cứng: 512GB SSD
Màn hình: 14.2" Liquid Retina XDR (3024x1964), 120Hz
Card đồ hoạ: GPU tích hợp 16 nhân
Pin: Lên đến 18 giờ
Trọng lượng: 1.6 kg
Hệ điều hành: macOS', `description` = CONCAT(`description`, '\n', 'Màn hình mini-LED độ sáng cao, hiển thị HDR chuẩn xác cho người làm ảnh và video. Máy có đủ cổng HDMI, khe thẻ SD và MagSafe, hạn chế phải dùng thêm hub chuyển.') WHERE `id` = 1;
UPDATE `products` SET `specs` = 'CPU: Apple M2 8 nhân CPU
RAM: 8GB Unified Memory
Ổ cứng: 256GB SSD
Màn hình: 13.6" Liquid Retina (2560x1664)
Card đồ hoạ: GPU tích hợp 8 nhân
Pin: Lên đến 18 giờ
Trọng lượng: 1.24 kg
Hệ điều hành: macOS', `description` = CONCAT(`description`, '\n', 'Thiết kế không quạt nên vận hành hoàn toàn yên tĩnh, rất hợp mang lên lớp hoặc thư viện. Sạc MagSafe và hai cổng Thunderbolt đáp ứng nhu cầu kết nối cơ bản.') WHERE `id` = 2;
UPDATE `products` SET `specs` = 'CPU: Apple M2 Max 12 nhân CPU
RAM: 32GB Unified Memory
Ổ cứng: 1TB SSD
Màn hình: 16.2" Liquid Retina XDR (3456x2234), 120Hz
Card đồ hoạ: GPU tích hợp 38 nhân
Pin: Lên đến 22 giờ
Trọng lượng: 2.15 kg
Hệ điều hành: macOS', `description` = CONCAT(`description`, '\n', 'Lựa chọn cho studio di động: xuất video 8K, dựng 3D và chạy nhiều máy ảo cùng lúc mà vẫn giữ pin lâu. Hệ thống loa 6 loa và micro chuẩn studio phục vụ tốt cho làm nội dung.') WHERE `id` = 3;
UPDATE `products` SET `specs` = 'CPU: Apple M1 8 nhân CPU
RAM: 8GB Unified Memory
Ổ cứng: 256GB SSD
Màn hình: 13.3" Retina (2560x1600)
Card đồ hoạ: GPU tích hợp 7 nhân
Pin: Lên đến 18 giờ
Trọng lượng: 1.29 kg
Hệ điều hành: macOS', `description` = CONCAT(`description`, '\n', 'Hiệu năng vẫn dư dả cho học tập, văn phòng và chỉnh sửa ảnh nhẹ. Là lựa chọn hợp lý cho người lần đầu chuyển sang hệ sinh thái Apple.') WHERE `id` = 4;
UPDATE `products` SET `specs` = 'CPU: Intel Core i7-1280P (14 nhân, tối đa 4.8GHz)
RAM: 16GB LPDDR5
Ổ cứng: 512GB SSD NVMe
Màn hình: 13.4" FHD+ (1920x1200) InfinityEdge
Card đồ hoạ: Intel Iris Xe
Pin: 55Wh
Trọng lượng: 1.26 kg
Hệ điều hành: Windows 11 Home', `description` = CONCAT(`description`, '\n', 'Khung nhôm CNC nguyên khối, bàn phím tràn viền và trackpad kính liền mạch tạo cảm giác cao cấp. Phù hợp doanh nhân và lập trình viên cần máy nhỏ gọn nhưng mạnh.') WHERE `id` = 5;
UPDATE `products` SET `specs` = 'CPU: Intel Core i5-1235U (10 nhân, tối đa 4.4GHz)
RAM: 8GB DDR4 (nâng cấp tối đa 16GB)
Ổ cứng: 512GB SSD NVMe
Màn hình: 15.6" FHD (1920x1080) 120Hz
Card đồ hoạ: Intel Iris Xe
Pin: 41Wh
Trọng lượng: 1.65 kg
Hệ điều hành: Windows 11 Home', `description` = CONCAT(`description`, '\n', 'Có bàn phím số riêng, tiện cho kế toán và nhập liệu. Máy còn khe RAM trống để nâng cấp khi cần chạy nhiều ứng dụng hơn.') WHERE `id` = 6;
UPDATE `products` SET `specs` = 'CPU: Intel Core i7-12700H (14 nhân, tối đa 4.7GHz)
RAM: 16GB DDR5 4800MHz
Ổ cứng: 1TB SSD NVMe
Màn hình: 15.6" QHD (2560x1440) 240Hz
Card đồ hoạ: NVIDIA GeForce RTX 3070 Ti 8GB
Pin: 86Wh
Trọng lượng: 2.42 kg
Hệ điều hành: Windows 11 Home', `description` = CONCAT(`description`, '\n', 'Hệ thống tản nhiệt Cryo-tech với buồng hơi giúp duy trì xung nhịp cao khi chơi game AAA. Đèn bàn phím RGB từng phím và phần mềm Alienware Command Center tuỳ chỉnh hiệu năng.') WHERE `id` = 7;
UPDATE `products` SET `specs` = 'CPU: Intel Core i5-1235U (10 nhân, tối đa 4.4GHz)
RAM: 8GB DDR4
Ổ cứng: 512GB SSD NVMe
Màn hình: 14" FHD (1920x1080) chống chói
Card đồ hoạ: Intel Iris Xe
Pin: 41Wh
Trọng lượng: 1.48 kg
Hệ điều hành: Windows 11 Home', `description` = CONCAT(`description`, '\n', 'Dòng máy dành cho doanh nghiệp nhỏ với bản lề bền, bàn phím chống tràn và đủ cổng USB-A, HDMI, LAN cho công việc văn phòng.') WHERE `id` = 8;
UPDATE `products` SET `specs` = 'CPU: AMD Ryzen 7 6800H (8 nhân, tối đa 4.7GHz)
RAM: 16GB DDR5
Ổ cứng: 512GB SSD NVMe
Màn hình: 15.6" FHD (1920x1080) 165Hz
Card đồ hoạ: NVIDIA GeForce RTX 3060 6GB
Pin: 90Wh
Trọng lượng: 2.3 kg
Hệ điều hành: Windows 11 Home', `description` = CONCAT(`description`, '\n', 'Màn hình 165Hz giúp chơi game bắn súng mượt mà, kết hợp tản nhiệt kim loại lỏng trên CPU. Thiết kế đậm chất ROG với dải LED và bàn phím RGB.') WHERE `id` = 9;
UPDATE `products` SET `specs` = 'CPU: Intel Core i5-1240P (12 nhân, tối đa 4.4GHz)
RAM: 16GB LPDDR5
Ổ cứng: 512GB SSD NVMe
Màn hình: 14" 2.8K OLED (2880x1800) 90Hz
Card đồ hoạ: Intel Iris Xe
Pin: 75Wh
Trọng lượng: 1.39 kg
Hệ điều hành: Windows 11 Home', `description` = CONCAT(`description`, '\n', 'Màn OLED đạt chuẩn màu DCI-P3 100%, đen sâu và tương phản cao, rất hợp xem phim và chỉnh ảnh. Pin 75Wh cho thời gian dùng cả ngày làm việc.') WHERE `id` = 10;
UPDATE `products` SET `specs` = 'CPU: Intel Core i5-11400H (6 nhân, tối đa 4.5GHz)
RAM: 8GB DDR4 (nâng cấp tối đa 32GB)
Ổ cứng: 512GB SSD NVMe
Màn hình: 15.6" FHD (1920x1080) 144Hz
Card đồ hoạ: NVIDIA GeForce RTX 3050 4GB
Pin: 48Wh
Trọng lượng: 2.3 kg
Hệ điều hành: Windows 11 Home', `description` = CONCAT(`description`, '\n', 'Đạt chuẩn độ bền quân đội MIL-STD-810H, chịu va đập tốt cho sinh viên hay di chuyển. Máy dễ nâng cấp RAM và ổ cứng nhờ có sẵn khe trống.') WHERE `id` = 11;
UPDATE `products` SET `specs` = 'CPU: Intel Core i5-1240P (12 nhân, tối đa 4.4GHz)
RAM: 8GB DDR4
Ổ cứng: 512GB SSD NVMe
Màn hình: 15.6" FHD (1920x1080)
Card đồ hoạ: Intel Iris Xe
Pin: 42Wh
Trọng lượng: 1.7 kg
Hệ điều hành: Windows 11 Home', `description` = CONCAT(`description`, '\n', 'Bản lề mở phẳng 180 độ, bàn phím có phím số và cảm biến vân tay tích hợp trên touchpad giúp đăng nhập nhanh.') WHERE `id` = 12;
UPDATE `products` SET `specs` = 'CPU: Intel Core i5-1230U (10 nhân, tối đa 4.4GHz)
RAM: 16GB LPDDR4x
Ổ cứng: 512GB SSD NVMe
Màn hình: 13.3" 2.8K OLED cảm ứng, xoay gập 360°
Card đồ hoạ: Intel Iris Xe
Pin: 66Wh
Trọng lượng: 1.34 kg
Hệ điều hành: Windows 11 Home', `description` = CONCAT(`description`, '\n', 'Đi kèm bút cảm ứng, phù hợp ghi chép tay và vẽ phác thảo. Camera 5MP có màn trập vật lý bảo vệ quyền riêng tư.') WHERE `id` = 13;
UPDATE `products` SET `specs` = 'CPU: Intel Core i5-1235U (10 nhân, tối đa 4.4GHz)
RAM: 16GB DDR4
Ổ cứng: 512GB SSD NVMe
Màn hình: 15.6" FHD IPS (1920x1080)
Card đồ hoạ: Intel Iris Xe
Pin: 41Wh
Trọng lượng: 1.75 kg
Hệ điều hành: Windows 11 Home', `description` = CONCAT(`description`, '\n', 'Loa B&O cho âm thanh rõ ràng khi họp trực tuyến và giải trí. Vỏ nhôm phần mặt bàn phím tạo cảm giác chắc chắn.') WHERE `id` = 14;
UPDATE `products` SET `specs` = 'CPU: Intel Core i7-12700H (14 nhân, tối đa 4.7GHz)
RAM: 16GB DDR5
Ổ cứng: 512GB SSD NVMe
Màn hình: 16.1" QHD (2560x1440) 165Hz
Card đồ hoạ: NVIDIA GeForce RTX 3060 6GB
Pin: 83Wh
Trọng lượng: 2.37 kg
Hệ điều hành: Windows 11 Home', `description` = CONCAT(`description`, '\n', 'Phần mềm OMEN Gaming Hub cho phép ép xung và theo dõi nhiệt độ. Màn hình 16 inch tỉ lệ viền mỏng mang lại không gian hiển thị rộng.') WHERE `id` = 15;
UPDATE `products` SET `specs` = 'CPU: Intel Core i5-12500H (12 nhân, tối đa 4.5GHz)
RAM: 16GB DDR4
Ổ cứng: 512GB SSD NVMe
Màn hình: 16.1" FHD (1920x1080) 144Hz
Card đồ hoạ: NVIDIA GeForce RTX 3050 4GB
Pin: 70Wh
Trọng lượng: 2.46 kg
Hệ điều hành: Windows 11 Home', `description` = CONCAT(`description`, '\n', 'Thiết kế tối giản, không quá hầm hố nên vừa dùng để học vừa chơi game. Pin 70Wh dư dả cho các tác vụ văn phòng.') WHERE `id` = 16;
UPDATE `products` SET `specs` = 'CPU: Intel Core i7-1260P (12 nhân, tối đa 4.7GHz)
RAM: 16GB LPDDR5
Ổ cứng: 1TB SSD NVMe
Màn hình: 14" WUXGA (1920x1200) chống chói
Card đồ hoạ: Intel Iris Xe
Pin: 57Wh
Trọng lượng: 1.12 kg
Hệ điều hành: Windows 11 Pro', `description` = CONCAT(`description`, '\n', 'Vỏ sợi carbon siêu nhẹ, bàn phím ThinkPad hành trình sâu và TrackPoint đặc trưng. Bảo mật với vân tay, camera hồng ngoại và chip TPM 2.0.') WHERE `id` = 17;
UPDATE `products` SET `specs` = 'CPU: AMD Ryzen 7 6800H (8 nhân, tối đa 4.7GHz)
RAM: 16GB DDR5
Ổ cứng: 512GB SSD NVMe
Màn hình: 15.6" WQHD (2560x1440) 165Hz
Card đồ hoạ: NVIDIA GeForce RTX 3060 6GB
Pin: 80Wh
Trọng lượng: 2.4 kg
Hệ điều hành: Windows 11 Home', `description` = CONCAT(`description`, '\n', 'Tản nhiệt Legion Coldfront 4.0 giữ máy mát và êm khi chơi game lâu. Bàn phím TrueStrike gõ thoải mái, phù hợp cả làm việc lẫn giải trí.') WHERE `id` = 18;
UPDATE `products` SET `specs` = 'CPU: Intel Core i3-1215U (6 nhân, tối đa 4.4GHz)
RAM: 8GB DDR4
Ổ cứng: 256GB SSD NVMe
Màn hình: 15.6" FHD (1920x1080)
Card đồ hoạ: Intel UHD Graphics
Pin: 38Wh
Trọng lượng: 1.63 kg
Hệ điều hành: Windows 11 Home', `description` = CONCAT(`description`, '\n', 'Mức giá dễ tiếp cận cho học sinh, sinh viên học online, soạn thảo văn bản và xem phim. Camera có nắp che vật lý.') WHERE `id` = 19;
UPDATE `products` SET `specs` = 'CPU: Intel Core i5-1235U (10 nhân, tối đa 4.4GHz)
RAM: 16GB LPDDR5
Ổ cứng: 512GB SSD NVMe
Màn hình: 14" 2.2K cảm ứng, xoay gập 360°
Card đồ hoạ: Intel Iris Xe
Pin: 71Wh
Trọng lượng: 1.49 kg
Hệ điều hành: Windows 11 Home', `description` = CONCAT(`description`, '\n', 'Vỏ nhôm bo cong mềm mại, hỗ trợ bút Lenovo Digital Pen. Loa Dolby Atmos cho âm thanh sống động.') WHERE `id` = 20;
UPDATE `products` SET `specs` = 'CPU: Apple M2 8 nhân CPU
RAM: 8GB Unified Memory
Ổ cứng: 256GB SSD
Màn hình: 13.3" Retina (2560x1600)
Card đồ hoạ: GPU tích hợp 10 nhân
Pin: Lên đến 20 giờ
Trọng lượng: 1.4 kg
Hệ điều hành: macOS', `description` = CONCAT(`description`, '\n', 'Có quạt tản nhiệt chủ động nên giữ hiệu năng ổn định khi xuất video dài. Touch Bar hỗ trợ phím tắt theo từng ứng dụng.') WHERE `id` = 21;
UPDATE `products` SET `specs` = 'CPU: Intel Core i7-1185G7 (4 nhân, tối đa 4.8GHz)
RAM: 16GB DDR4
Ổ cứng: 512GB SSD NVMe
Màn hình: 14" FHD (1920x1080) chống chói
Card đồ hoạ: Intel Iris Xe
Pin: 63Wh
Trọng lượng: 1.36 kg
Hệ điều hành: Windows 11 Pro', `description` = CONCAT(`description`, '\n', 'Dòng Latitude cao cấp hỗ trợ vPro để quản trị từ xa, phù hợp doanh nghiệp. Có cổng Thunderbolt 4 và tuỳ chọn nhận diện khuôn mặt.') WHERE `id` = 22;
UPDATE `products` SET `specs` = 'CPU: Intel Core i7-1165G7 (4 nhân, tối đa 4.7GHz)
RAM: 16GB LPDDR4x
Ổ cứng: 1TB SSD NVMe
Màn hình: 14" FHD (1920x1080) chống chói
Card đồ hoạ: Intel Iris Xe
Pin: 66Wh
Trọng lượng: 0.88 kg
Hệ điều hành: Windows 11 Pro', `description` = CONCAT(`description`, '\n', 'Một trong những laptop 14 inch nhẹ nhất thị trường, vỏ hợp kim magie-lithium bền chắc. Pin lâu, sạc nhanh qua USB-C.') WHERE `id` = 23;
UPDATE `products` SET `specs` = 'CPU: Intel Core i5-1245U (10 nhân, tối đa 4.4GHz)
RAM: 16GB DDR4
Ổ cứng: 512GB SSD NVMe
Màn hình: 14" WUXGA (1920x1200)
Card đồ hoạ: Intel Iris Xe
Pin: 51Wh
Trọng lượng: 1.36 kg
Hệ điều hành: Windows 11 Pro', `description` = CONCAT(`description`, '\n', 'Bảo mật HP Wolf Security tích hợp sẵn, bàn phím chống tràn và bản lề mở 180 độ cho làm việc nhóm.') WHERE `id` = 24;
UPDATE `products` SET `specs` = 'CPU: Intel Core i5-1235U (10 nhân, tối đa 4.4GHz)
RAM: 16GB DDR4
Ổ cứng: 512GB SSD NVMe
Màn hình: 14" FHD IPS (1920x1080)
Card đồ hoạ: Intel Iris Xe
Pin: 45Wh
Trọng lượng: 1.4 kg
Hệ điều hành: Windows 11 Home', `description` = CONCAT(`description`, '\n', 'Vỏ nhôm hai tông màu hiện đại, nút nguồn tích hợp vân tay. Đủ cổng HDMI, USB-C, LAN cho văn phòng nhỏ.') WHERE `id` = 25;
UPDATE `products` SET `specs` = 'CPU: Apple M2 Max 12 nhân CPU
RAM: 32GB Unified Memory
Ổ cứng: 512GB SSD
Màn hình: Không kèm màn hình (hỗ trợ tối đa 5 màn hình)
Card đồ hoạ: GPU tích hợp 30 nhân
Pin: Dùng điện trực tiếp
Trọng lượng: 2.7 kg
Hệ điều hành: macOS', `description` = CONCAT(`description`, '\n', 'Máy tính để bàn nhỏ gọn với rất nhiều cổng kết nối phía trước và sau, phù hợp phòng dựng phim và studio âm nhạc.') WHERE `id` = 26;
UPDATE `products` SET `specs` = 'CPU: Intel Core i7-12800H (14 nhân, tối đa 4.8GHz)
RAM: 32GB DDR5
Ổ cứng: 1TB SSD NVMe
Màn hình: 15.6" FHD (1920x1080) chống chói
Card đồ hoạ: NVIDIA RTX A1000 4GB
Pin: 64Wh
Trọng lượng: 1.79 kg
Hệ điều hành: Windows 11 Pro', `description` = CONCAT(`description`, '\n', 'Máy trạm được chứng nhận ISV cho AutoCAD, SolidWorks và Revit. Hỗ trợ nâng cấp RAM, thêm ổ cứng để mở rộng dự án.') WHERE `id` = 27;
UPDATE `products` SET `specs` = 'CPU: AMD Ryzen 9 6900HS (8 nhân, tối đa 4.9GHz)
RAM: 16GB DDR5
Ổ cứng: 1TB SSD NVMe
Màn hình: 14" QHD+ (2560x1600) 120Hz
Card đồ hoạ: AMD Radeon RX 6700S 8GB
Pin: 76Wh
Trọng lượng: 1.65 kg
Hệ điều hành: Windows 11 Home', `description` = CONCAT(`description`, '\n', 'Nắp lưng AniMe Matrix có thể hiển thị hình ảnh tuỳ chỉnh. Hiệu năng gaming mạnh trong thân máy 14 inch dễ mang theo.') WHERE `id` = 28;
UPDATE `products` SET `specs` = 'CPU: Intel Core i5-1235U (10 nhân, tối đa 4.4GHz)
RAM: 8GB DDR4
Ổ cứng: 512GB SSD NVMe
Màn hình: 15.6" FHD (1920x1080)
Card đồ hoạ: Intel Iris Xe
Pin: 51Wh
Trọng lượng: 1.74 kg
Hệ điều hành: Windows 11 Pro', `description` = CONCAT(`description`, '\n', 'Dòng máy doanh nghiệp tầm trung, dễ bảo trì và nâng cấp. Bàn phím có phím số, phù hợp làm việc với bảng tính.') WHERE `id` = 29;
UPDATE `products` SET `specs` = 'CPU: AMD Ryzen 5 5625U (6 nhân, tối đa 4.3GHz)
RAM: 8GB DDR4
Ổ cứng: 512GB SSD NVMe
Màn hình: 14" FHD IPS (1920x1080)
Card đồ hoạ: AMD Radeon Graphics
Pin: 57Wh
Trọng lượng: 1.64 kg
Hệ điều hành: Windows 11 Home', `description` = CONCAT(`description`, '\n', 'Bàn phím ThinkPad gõ êm, chống tràn, khung máy đạt chuẩn độ bền quân đội. Là lựa chọn giá tốt cho sinh viên muốn dùng ThinkPad.') WHERE `id` = 30;
UPDATE `products` SET `specs` = 'CPU: Apple M2 8 nhân CPU
RAM: 16GB Unified Memory
Ổ cứng: 512GB SSD
Màn hình: 13.3" Retina (2560x1600)
Card đồ hoạ: GPU tích hợp 10 nhân
Pin: Lên đến 20 giờ
Trọng lượng: 1.4 kg
Hệ điều hành: macOS', `description` = CONCAT(`description`, '\n', 'RAM 16GB giúp mở nhiều ứng dụng và tab trình duyệt cùng lúc mà vẫn mượt. Phù hợp lập trình viên và người làm thiết kế nhẹ.') WHERE `id` = 31;

-- Ngân hàng khách chọn khi thanh toán chuyển khoản (mã trong src/config/banks.php)
ALTER TABLE `orders` ADD COLUMN `bank_code` varchar(10) DEFAULT NULL AFTER `payment_method`;
