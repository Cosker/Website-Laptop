<?php
require_once __DIR__ . '/../config/auth.php';

// Chỉ tài khoản admin mới được vào trang quản trị
if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

$toastMessage = '';

// XỬ LÝ TẤT CẢ THAO TÁC POST (THÊM, SỬA, XÓA SẢN PHẨM) TRƯỚC KHI NẠP GIAO DIỆN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // 1. THÊM SẢN PHẨM
    if ($action === 'add_product') {
        $name = trim($_POST['name'] ?? '');
        $brand_id = (int)($_POST['brand_id'] ?? 0);
        $price = (int)($_POST['price'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);
        $img = trim($_POST['img'] ?? '');
        $spec = trim($_POST['spec'] ?? '');
        $specs = trim($_POST['specs'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (!empty($name) && $brand_id > 0 && $price >= 0 && $stock >= 0) {
            try {
                $stmt = $pdo->prepare("INSERT INTO products (brand_id, name, price, stock, img, spec, specs, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$brand_id, $name, $price, $stock, $img, $spec, $specs, $description]);
                $toastMessage = "showToast('Thêm sản phẩm thành công!', 'success');";
            } catch (PDOException $e) {
                $toastMessage = "showToast('Lỗi khi thêm sản phẩm: " . addslashes($e->getMessage()) . "', 'error');";
            }
        } else {
            $toastMessage = "showToast('Vui lòng điền thông tin hợp lệ!', 'error');";
        }
    } 
    // 2. SỬA SẢN PHẨM
    elseif ($action === 'edit_product') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $brand_id = (int)($_POST['brand_id'] ?? 0);
        $price = (int)($_POST['price'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);
        $img = trim($_POST['img'] ?? '');
        $spec = trim($_POST['spec'] ?? '');
        $specs = trim($_POST['specs'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($id > 0 && !empty($name) && $brand_id > 0 && $price >= 0 && $stock >= 0) {
            try {
                $stmt = $pdo->prepare("UPDATE products SET brand_id = ?, name = ?, price = ?, stock = ?, img = ?, spec = ?, specs = ?, description = ? WHERE id = ?");
                $stmt->execute([$brand_id, $name, $price, $stock, $img, $spec, $specs, $description, $id]);
                $toastMessage = "showToast('Cập nhật sản phẩm thành công!', 'success');";
            } catch (PDOException $e) {
                $toastMessage = "showToast('Lỗi khi cập nhật: " . addslashes($e->getMessage()) . "', 'error');";
            }
        }
    } 
    // 3. XÓA SẢN PHẨM
    elseif ($action === 'delete_product') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            try {
                $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
                $stmt->execute([$id]);
                $toastMessage = "showToast('Đã xóa sản phẩm thành công!', 'success');";
            } catch (PDOException $e) {
                if ($e->getCode() == '23000') {
                    $toastMessage = "showToast('Không thể xóa! Sản phẩm này đã phát sinh đơn hàng.', 'error');";
                } else {
                    $toastMessage = "showToast('Lỗi khi xóa: " . addslashes($e->getMessage()) . "', 'error');";
                }
            }
        }
    }
    // 4. CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG
    elseif ($action === 'update_order_status') {
        $order_id = (int)($_POST['order_id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $valid_statuses = ['pending', 'processing', 'completed', 'cancelled'];
        
        if ($order_id > 0 && in_array($status, $valid_statuses)) {
            try {
                $pdo->beginTransaction();
                $stmt = $pdo->prepare("SELECT status FROM orders WHERE id = ? FOR UPDATE");
                $stmt->execute([$order_id]);
                $oldStatus = $stmt->fetchColumn();

                // Huỷ đơn thì trả hàng về kho; khôi phục đơn đã huỷ thì trừ kho lại
                $stockDelta = 0;
                if ($oldStatus !== 'cancelled' && $status === 'cancelled') $stockDelta = 1;
                if ($oldStatus === 'cancelled' && $status !== 'cancelled') $stockDelta = -1;
                if ($stockDelta !== 0) {
                    $pdo->prepare("UPDATE products p JOIN order_details od ON od.product_id = p.id
                                   SET p.stock = p.stock + ? * od.quantity WHERE od.order_id = ?")
                        ->execute([$stockDelta, $order_id]);
                }

                $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
                $stmt->execute([$status, $order_id]);
                $pdo->commit();
                $toastMessage = "showToast('Cập nhật trạng thái đơn hàng thành công!', 'success');";
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) $pdo->rollBack();
                $toastMessage = "showToast('Lỗi khi cập nhật đơn hàng: " . addslashes($e->getMessage()) . "', 'error');";
            }
        }
    }
    // CODE THÊM MỚI: 5. XÓA KHÁCH HÀNG
    elseif ($action === 'delete_customer') {
        $customer_id = (int)($_POST['customer_id'] ?? 0);
        if ($customer_id > 0) {
            try {
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'user'");
                $stmt->execute([$customer_id]);
                $toastMessage = "showToast('Đã xóa khách hàng thành công!', 'success');";
            } catch (PDOException $e) {
                if ($e->getCode() == '23000') {
                    $toastMessage = "showToast('Không thể xóa! Khách hàng này đã có lịch sử đơn hàng.', 'error');";
                } else {
                    $toastMessage = "showToast('Lỗi khi xóa: " . addslashes($e->getMessage()) . "', 'error');";
                }
            }
        }
    }
    // 6. THÊM / SỬA BÀI VIẾT
    elseif ($action === 'add_article' || $action === 'edit_article') {
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $excerpt = trim($_POST['excerpt'] ?? '');
        // Chuẩn hoá xuống dòng, bỏ dòng trống: mỗi dòng còn lại là 1 đoạn văn
        $paragraphs = array_filter(array_map('trim', preg_split('/\R/u',$_POST['content'] ?? '')), 'strlen');
        $content = implode("\n", $paragraphs);
        $img = trim($_POST['img'] ?? '');
        $created_at = $_POST['created_at'] ?? '';
        $validDate = DateTime::createFromFormat('Y-m-d', $created_at) !== false;
        $isEdit = $action === 'edit_article';

        if (!empty($title) && !empty($category) && !empty($author) && !empty($excerpt) && !empty($content) && $validDate && (!$isEdit || $id > 0)) {
            try {
                if ($isEdit) {
                    $stmt = $pdo->prepare("UPDATE articles SET title = ?, category = ?, author = ?, excerpt = ?, content = ?, img = ?, created_at = ? WHERE id = ?");
                    $stmt->execute([$title, $category, $author, $excerpt, $content, $img, $created_at, $id]);
                    $toastMessage = "showToast('Cập nhật bài viết thành công!', 'success');";
                } else {
                    $stmt = $pdo->prepare("INSERT INTO articles (title, category, author, excerpt, content, img, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$title, $category, $author, $excerpt, $content, $img, $created_at]);
                    $toastMessage = "showToast('Thêm bài viết thành công!', 'success');";
                }
            } catch (PDOException $e) {
                $toastMessage = "showToast('Lỗi khi lưu bài viết: " . addslashes($e->getMessage()) . "', 'error');";
            }
        } else {
            $toastMessage = "showToast('Vui lòng điền đầy đủ thông tin bài viết!', 'error');";
        }
    }
    // 7. XÓA BÀI VIẾT
    elseif ($action === 'delete_article') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            try {
                $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
                $stmt->execute([$id]);
                $toastMessage = "showToast('Đã xóa bài viết thành công!', 'success');";
            } catch (PDOException $e) {
                $toastMessage = "showToast('Lỗi khi xóa: " . addslashes($e->getMessage()) . "', 'error');";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TechNova</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Escape chuỗi trước khi chèn vào HTML (tên, email do khách nhập)
        const esc = (str) => String(str ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { primary: '#2563eb', secondary: '#1d4ed8', dark: '#0f172a', light: '#f8fafc' }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
        .admin-section { display: none; animation: fadeIn 0.3s ease-in-out; }
        .admin-section.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideOutRight { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
        .toast-enter { animation: slideInRight 0.3s forwards; }
        .toast-exit { animation: slideOutRight 0.3s forwards; }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-gray-800">

    <!-- Sidebar -->
    <?php require_once 'includes/sidebar.php'; ?>

    <!-- Overlay for mobile sidebar -->
    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-10 hidden md:hidden"></div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-full overflow-hidden relative z-0">
        <!-- Topbar -->
        <?php require_once 'includes/topbar.php'; ?>

        <!-- Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 lg:p-6">
            <!-- SECTION: DASHBOARD -->
            <?php require_once 'includes/dashboard.php'; ?>

            <!-- SECTION: PRODUCTS -->
            <?php require_once 'includes/products.php'; ?>

            <!-- SECTION: ORDERS -->
            <?php require_once 'includes/orders.php'; ?>

            <!-- SECTION: STATISTICS -->
            <?php require_once 'includes/statistics.php'; ?>

            <!-- CODE THÊM MỚI: Nhúng file Khách hàng vào layout -->
            <!-- SECTION: CUSTOMERS -->
            <?php require_once 'includes/customers.php'; ?>

            <!-- SECTION: ARTICLES -->
            <?php require_once 'includes/articles.php'; ?>
        </main>
    </div>

    <!-- Custom Toast Notification -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-3"></div>

    <script>
        // SPA Navigation for Admin
        function navigateAdmin(targetId) {
            document.querySelectorAll('.admin-section').forEach(sec => sec.classList.remove('active'));
            document.getElementById(targetId).classList.add('active');

            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.classList.remove('bg-primary', 'text-white', 'shadow-sm');
                btn.classList.add('text-gray-300');
            });
            const activeBtn = document.querySelector(`.nav-btn[data-target="${targetId}"]`);
            if (activeBtn) {
                activeBtn.classList.remove('text-gray-300');
                activeBtn.classList.add('bg-primary', 'text-white', 'shadow-sm');
            }

            if (window.innerWidth < 768) {
                toggleSidebar();
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('flex');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('flex');
                overlay.classList.add('hidden');
            }
        }

        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            let icon = 'fa-info-circle text-blue-500';
            let border = 'border-blue-500';
            if (type === 'success') { icon = 'fa-check-circle text-green-500'; border = 'border-green-500'; }
            if (type === 'error') { icon = 'fa-times-circle text-red-500'; border = 'border-red-500'; }

            toast.className = `toast-enter flex items-center bg-white border-l-4 ${border} rounded shadow-lg px-4 py-3 min-w-[250px]`;
            toast.innerHTML = `
                <i class="fa-solid ${icon} text-xl mr-3"></i>
                <div class="text-sm font-medium text-gray-800">${message}</div>
            `;
            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('toast-enter');
                toast.classList.add('toast-exit');
                setTimeout(() => { toast.remove(); }, 300);
            }, 3000);
        }

        window.onload = () => {
            if(typeof initRealDashboardChart === 'function') {
                initRealDashboardChart();
            }
            if(typeof initRealStatsChart === 'function') {
                initRealStatsChart();
            }
        };

        window.addEventListener('resize', () => {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('flex');
                if(overlay) overlay.classList.add('hidden');
            } else if(overlay && !overlay.classList.contains('hidden')){
                sidebar.classList.add('flex');
                sidebar.classList.remove('hidden');
            } else {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('flex');
            }
        });
    </script>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])): ?>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if(typeof navigateAdmin === 'function') {
                <?php if (strpos($_POST['action'], 'product') !== false): ?>
                navigateAdmin('products');
                <?php elseif (strpos($_POST['action'], 'order') !== false): ?>
                navigateAdmin('orders');
                <?php elseif (strpos($_POST['action'], 'customer') !== false): ?>
                navigateAdmin('customers');
                <?php elseif (strpos($_POST['action'], 'article') !== false): ?>
                navigateAdmin('articles');
                <?php endif; ?>
            }
        });
    </script>
    <?php endif; ?>

    <?php if (isset($_GET['year'])): ?>
    <script>
        // Vừa chọn năm ở trang Thống kê -> mở lại đúng mục Thống kê
        document.addEventListener("DOMContentLoaded", () => navigateAdmin('statistics'));
    </script>
    <?php endif; ?>

    <script>
        <?= $toastMessage ?>
    </script>
</body>
</html>