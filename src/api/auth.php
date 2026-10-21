<?php
// API tài khoản cho trang khách: POST JSON { action: login | register | logout | update_profile, ... }
require_once __DIR__ . '/../config/auth.php';

header('Content-Type: application/json; charset=utf-8');

function respond(bool $ok, string $message, array $extra = [], int $status = 200): void
{
    http_response_code($status);
    echo json_encode(['ok' => $ok, 'message' => $message] + $extra, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Phương thức không hợp lệ.', [], 405);
}

$input = json_decode(file_get_contents('php://input'), true) ?: [];
$action = $input['action'] ?? '';

if ($action === 'login') {
    $email = strtolower(trim($input['email'] ?? ''));
    $password = (string)($input['password'] ?? '');
    if (!attemptLogin($email, $password)) {
        respond(false, 'Email hoặc mật khẩu không đúng.', [], 401);
    }
    respond(true, 'Đăng nhập thành công.', ['user' => currentUser()]);
}

if ($action === 'register') {
    $name = trim($input['name'] ?? '');
    $email = strtolower(trim($input['email'] ?? ''));
    $password = (string)($input['password'] ?? '');

    if (mb_strlen($name) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        respond(false, 'Vui lòng nhập họ tên và email hợp lệ.', [], 422);
    }
    if (strlen($password) < 8) {
        respond(false, 'Mật khẩu cần có ít nhất 8 ký tự.', [], 422);
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        respond(false, 'Email này đã được đăng ký. Hãy đăng nhập hoặc dùng email khác.', [], 409);
    }

    $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')")
        ->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
    respond(true, 'Đăng ký thành công. Hãy đăng nhập để tiếp tục.');
}

if ($action === 'logout') {
    logout();
    respond(true, 'Bạn đã đăng xuất.');
}

if ($action === 'update_profile') {
    $user = currentUser();
    if (!$user) {
        respond(false, 'Vui lòng đăng nhập.', [], 401);
    }
    $name = trim($input['name'] ?? '');
    $phone = trim($input['phone'] ?? '');
    $address = trim($input['address'] ?? '');
    if ($name === '') {
        respond(false, 'Vui lòng nhập họ tên.', [], 422);
    }

    $pdo->prepare("UPDATE users SET name = ?, phone = ?, address = ? WHERE id = ?")
        ->execute([$name, $phone, $address, $user['id']]);
    setCurrentUser(['name' => $name, 'phone' => $phone, 'address' => $address] + $user);
    respond(true, 'Đã cập nhật thông tin tài khoản.', ['user' => currentUser()]);
}

respond(false, 'Thao tác không hợp lệ.', [], 400);
