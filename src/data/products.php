<?php
/**
 * Nguồn dữ liệu sản phẩm và hãng laptop: đọc từ bảng `products`, `brands`
 * trong database. Sản phẩm được thêm / sửa / xoá trong trang admin.
 *
 * Quy ước các trường của sản phẩm trả ra cho giao diện:
 *   id, brand (slug của hãng), name, price (VNĐ), stock (tồn kho),
 *   sold (số đã bán, tính từ order_details của các đơn không bị huỷ),
 *   img, spec, desc
 *
 * Nếu ảnh lỗi, giao diện tự hiện ảnh dự phòng assets/placeholder-laptop.svg.
 */

require_once __DIR__ . '/../config/db.php';

function getBrands(): array
{
    global $pdo;
    $rows = $pdo->query("SELECT slug, name, description FROM brands ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
    return array_map(fn($b) => [
        'slug' => $b['slug'],
        'name' => $b['name'],
        'short' => $b['name'],
        'desc' => $b['description'] ?? '',
    ], $rows);
}

function mapProductRow(array $row): array
{
    return [
        'id' => (int)$row['id'],
        'brand' => $row['brand'] ?? '',
        'name' => $row['name'],
        'price' => (int)$row['price'],
        'stock' => (int)$row['stock'],
        'sold' => (int)$row['sold'],
        'img' => $row['img'] ?: 'assets/placeholder-laptop.svg',
        'spec' => $row['spec'] ?? '',
        'desc' => $row['description'] ?? '',
    ];
}

const PRODUCT_SELECT_SQL = "SELECT p.*, b.slug AS brand,
        COALESCE((SELECT SUM(od.quantity) FROM order_details od
                  JOIN orders o ON o.id = od.order_id
                  WHERE od.product_id = p.id AND o.status <> 'cancelled'), 0) AS sold
    FROM products p
    LEFT JOIN brands b ON b.id = p.brand_id";

function getProducts(): array
{
    global $pdo;
    $rows = $pdo->query(PRODUCT_SELECT_SQL . " ORDER BY p.id DESC")->fetchAll(PDO::FETCH_ASSOC);
    return array_map('mapProductRow', $rows);
}

function getProductById(int $id): ?array
{
    global $pdo;
    $stmt = $pdo->prepare(PRODUCT_SELECT_SQL . " WHERE p.id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? mapProductRow($row) : null;
}
