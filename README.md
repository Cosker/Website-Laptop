# Website-Laptop

Website bán laptop TechNova (PHP + MySQL, chạy trên XAMPP).

## Cài đặt database

Tạo database `technova` (utf8mb4) rồi import lần lượt:

1. `technova.sql` (dữ liệu gốc: brands, products, users, orders, order_details)
2. `database/articles.sql` (bảng bài viết)
3. `database/migration_v2.sql` (cấu hình/mô tả sản phẩm, thông tin nhận hàng của đơn, SĐT/địa chỉ của user)
4. `database/migration_v3.sql` (thông số kỹ thuật chi tiết, mô tả dài, ngân hàng chuyển khoản của đơn)
5. `database/migration_v4.sql` (trạng thái đơn chi tiết: chờ xác nhận, đã xác nhận, đang giao, đã giao, đã huỷ)

Tài khoản ngân hàng nhận chuyển khoản cấu hình ở `src/config/banks.php` (số tài khoản mẫu, cần thay bằng tài khoản thật).

Kết nối DB cấu hình ở `src/config/db.php` (mặc định XAMPP: `root`, không mật khẩu).

## Đường dẫn

- Trang khách: `http://localhost/Website-Laptop/src/index.php`
- Trang quản trị: `http://localhost/Website-Laptop/src/admin/` (chỉ tài khoản có `role = 'admin'`)

Mật khẩu cũ dạng chữ thường trong dữ liệu mẫu sẽ tự được mã hoá (`password_hash`) ở lần đăng nhập đầu tiên.
