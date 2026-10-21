<?php
// API đơn hàng cho trang khách (bắt buộc đăng nhập):
//   GET  -> danh sách đơn của người dùng hiện tại
//   POST -> đặt hàng: { items: [{id, quantity}], receiver_name, phone, address, payment }
require_once __DIR__ . '/../config/auth.php';

header('Content-Type: application/json; charset=utf-8');

const PAYMENT_METHODS = ['COD', 'Chuyển khoản ngân hàng'];

function respond(bool $ok, string $message, array $extra = [], int $status = 200): void
{
    http_response_code($status);
    echo json_encode(['ok' => $ok, 'message' => $message] + $extra, JSON_UNESCAPED_UNICODE);
    exit;
}

$user = currentUser();
if (!$user) {
    respond(false, 'Vui lòng đăng nhập.', [], 401);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("SELECT id, customer_name, phone, address, total_amount, payment_method, status, created_at
                           FROM orders WHERE user_id = ? ORDER BY created_at DESC, id DESC");
    $stmt->execute([$user['id']]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $items = [];
    if ($orders) {
        $ids = array_column($orders, 'id');
        $in = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT od.order_id, od.product_id, od.quantity, od.price, p.name, p.img
                               FROM order_details od JOIN products p ON p.id = od.product_id
                               WHERE od.order_id IN ($in)");
        $stmt->execute($ids);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $items[$row['order_id']][] = [
                'id' => (int)$row['product_id'],
                'name' => $row['name'],
                'img' => $row['img'],
                'price' => (int)$row['price'],
                'quantity' => (int)$row['quantity'],
            ];
        }
    }

    respond(true, '', ['orders' => array_map(fn($o) => [
        'id' => (int)$o['id'],
        'receiverName' => $o['customer_name'],
        'phone' => $o['phone'],
        'address' => $o['address'],
        'total' => (int)$o['total_amount'],
        'payment' => $o['payment_method'],
        'status' => $o['status'],
        'createdAt' => $o['created_at'],
        'items' => $items[$o['id']] ?? [],
    ], $orders)]);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Phương thức không hợp lệ.', [], 405);
}

$input = json_decode(file_get_contents('php://input'), true) ?: [];
$receiverName = trim($input['receiver_name'] ?? '');
$phone = trim($input['phone'] ?? '');
$address = trim($input['address'] ?? '');
$payment = $input['payment'] ?? 'COD';

if ($receiverName === '' || $address === '' || !preg_match('/^[0-9+() .-]{9,16}$/', $phone)) {
    respond(false, 'Vui lòng kiểm tra đầy đủ họ tên, số điện thoại và địa chỉ nhận hàng.', [], 422);
}
if (!in_array($payment, PAYMENT_METHODS, true)) {
    respond(false, 'Phương thức thanh toán không hợp lệ.', [], 422);
}

// Gộp số lượng theo sản phẩm (phòng trường hợp gửi trùng id)
$quantities = [];
foreach ($input['items'] ?? [] as $item) {
    $id = (int)($item['id'] ?? 0);
    $qty = (int)($item['quantity'] ?? 0);
    if ($id > 0 && $qty > 0) {
        $quantities[$id] = ($quantities[$id] ?? 0) + $qty;
    }
}
if (!$quantities) {
    respond(false, 'Không có sản phẩm để đặt hàng.', [], 422);
}

try {
    $pdo->beginTransaction();

    // Khoá các dòng sản phẩm để kiểm tra và trừ tồn kho an toàn; giá lấy từ DB, không tin giá client gửi
    $in = implode(',', array_fill(0, count($quantities), '?'));
    $stmt = $pdo->prepare("SELECT id, name, price, stock FROM products WHERE id IN ($in) FOR UPDATE");
    $stmt->execute(array_keys($quantities));
    $products = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $p) {
        $products[(int)$p['id']] = $p;
    }

    $total = 0;
    foreach ($quantities as $id => $qty) {
        if (!isset($products[$id])) {
            $pdo->rollBack();
            respond(false, 'Có sản phẩm không còn tồn tại, vui lòng kiểm tra lại giỏ hàng.', [], 409);
        }
        if ($products[$id]['stock'] < $qty) {
            $pdo->rollBack();
            respond(false, "{$products[$id]['name']} chỉ còn {$products[$id]['stock']} chiếc.", [], 409);
        }
        $total += $products[$id]['price'] * $qty;
    }

    $pdo->prepare("INSERT INTO orders (user_id, customer_name, phone, address, total_amount, payment_method, status)
                   VALUES (?, ?, ?, ?, ?, ?, 'pending')")
        ->execute([$user['id'], $receiverName, $phone, $address, $total, $payment]);
    $orderId = (int)$pdo->lastInsertId();

    $insertDetail = $pdo->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $updateStock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
    foreach ($quantities as $id => $qty) {
        $insertDetail->execute([$orderId, $id, $qty, $products[$id]['price']]);
        $updateStock->execute([$qty, $id]);
    }

    // Lưu lại thông tin nhận hàng làm mặc định cho lần sau
    $pdo->prepare("UPDATE users SET phone = ?, address = ? WHERE id = ?")->execute([$phone, $address, $user['id']]);

    $pdo->commit();
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    respond(false, 'Không thể đặt hàng lúc này, vui lòng thử lại.', [], 500);
}

setCurrentUser(['phone' => $phone, 'address' => $address] + $user);
respond(true, 'Đặt hàng thành công.', [
    'orderId' => $orderId,
    'total' => $total,
    'user' => currentUser(),
    'stock' => array_map(fn($id) => ['id' => $id, 'stock' => $products[$id]['stock'] - $quantities[$id]], array_keys($quantities)),
]);
