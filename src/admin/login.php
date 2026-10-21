<?php
require_once __DIR__ . '/../config/auth.php';

if (isAdmin()) {
    header('Location: index.php');
    exit;
}

$error = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if (!attemptLogin($email, $password)) {
        $error = 'Email hoặc mật khẩu không đúng.';
    } elseif (!isAdmin()) {
        logout();
        $error = 'Tài khoản này không có quyền quản trị.';
    } else {
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập quản trị - TechNova</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { primary: '#2563eb', secondary: '#1d4ed8', dark: '#0f172a' }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-dark flex items-center justify-center p-4 font-sans">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-8">
        <div class="text-center mb-8">
            <div class="flex items-center justify-center mb-3">
                <i class="fa-solid fa-laptop-code text-primary text-3xl mr-2"></i>
                <span class="text-2xl font-bold text-dark">Tech<span class="text-primary">Admin</span></span>
            </div>
            <p class="text-sm text-gray-500">Đăng nhập bằng tài khoản quản trị viên</p>
        </div>

        <?php if ($error): ?>
            <div class="mb-5 px-4 py-3 rounded-lg bg-red-50 text-red-600 text-sm flex items-center">
                <i class="fa-solid fa-circle-exclamation mr-2"></i><?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input name="email" type="email" required autofocus autocomplete="email" value="<?= htmlspecialchars($email) ?>" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                <input name="password" type="password" required autocomplete="current-password" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            </div>
            <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-3 rounded-lg transition-colors shadow-md">
                <i class="fa-solid fa-right-to-bracket mr-2"></i>Đăng nhập
            </button>
        </form>

        <a href="../index.php" class="block text-center text-sm text-gray-500 hover:text-primary mt-6"><i class="fa-solid fa-arrow-left mr-1"></i> Về trang khách</a>
    </div>
</body>
</html>
