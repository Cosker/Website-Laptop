-- Trạng thái đơn hàng chi tiết hơn:
--   pending (Chờ xác nhận) -> confirmed (Đã xác nhận) -> shipping (Đang giao) -> completed (Đã giao)
--   cancelled (Đã huỷ)
-- Trạng thái cũ 'processing' (Đang giao) được đổi thành 'shipping'.
-- Chạy SAU migration_v3.sql.

ALTER TABLE `orders`
  MODIFY `status` enum('pending','processing','confirmed','shipping','completed','cancelled') DEFAULT 'pending';

UPDATE `orders` SET `status` = 'shipping' WHERE `status` = 'processing';

ALTER TABLE `orders`
  MODIFY `status` enum('pending','confirmed','shipping','completed','cancelled') DEFAULT 'pending';
