<?php
// Đăng nhập dùng PHP session; thông tin tài khoản lưu ở bảng `users`
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Thông tin người dùng đang đăng nhập (không có mật khẩu), null nếu chưa đăng nhập
function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

function setCurrentUser(array $user): void
{
    $_SESSION['user'] = [
        'id' => (int)$user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'phone' => $user['phone'] ?? '',
        'address' => $user['address'] ?? '',
        'role' => $user['role'],
    ];
}

function isAdmin(): bool
{
    return (currentUser()['role'] ?? '') === 'admin';
}

// Kiểm tra email + mật khẩu, trả về dòng user nếu đúng.
// Mật khẩu cũ lưu dạng chữ thường (dữ liệu mẫu) được mã hoá lại ngay khi đăng nhập đúng.
function attemptLogin(string $email, string $password): ?array
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        return null;
    }

    $isHashed = password_get_info($user['password'])['algo'] !== null;
    $ok = $isHashed ? password_verify($password, $user['password']) : hash_equals($user['password'], $password);
    if (!$ok) {
        return null;
    }

    if (!$isHashed || password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
        $pdo->prepare("UPDATE users SET password = ? WHERE id = ?")
            ->execute([password_hash($password, PASSWORD_DEFAULT), $user['id']]);
    }
    session_regenerate_id(true);
    setCurrentUser($user);
    return $user;
}

function logout(): void
{
    $_SESSION = [];
    session_destroy();
}
