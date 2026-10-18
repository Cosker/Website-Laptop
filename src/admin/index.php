<?php require_once __DIR__ . '/../data/products.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TechNova</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js for Dashboard Graphics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#2563eb',
                        secondary: '#1d4ed8',
                        dark: '#0f172a',
                        light: '#f8fafc'
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
        }

        .admin-section {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }

        .admin-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Sidebar transitions */
        .sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent; 
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }

        /* Toast Animation */
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        .toast-enter {
            animation: slideInRight 0.3s forwards;
        }
        .toast-exit {
            animation: slideOutRight 0.3s forwards;
        }
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
        </main>
    </div>

    <!-- Custom Toast Notification -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-3"></div>

    <script>
        // Dữ liệu lấy từ PHP (data/products.php), dùng chung với trang khách
        const brands = <?= json_encode(getBrands(), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const adminProducts = <?= json_encode(getProducts(), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        // Ảnh dự phòng khi ảnh sản phẩm tải lỗi
        const FALLBACK_IMG = '../assets/placeholder-laptop.svg';
        document.addEventListener('error', (e) => {
            const t = e.target;
            if (t && t.tagName === 'IMG' && !t.dataset.fallback) {
                t.dataset.fallback = '1';
                t.src = FALLBACK_IMG;
            }
        }, true);

        const escapeHtml = (str) => String(str).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
        const brandName = (slug) => {
            const b = brands.find(x => x.slug === slug);
            return b ? b.short : slug;
        };

        // Format Currency
        function formatCurrency(number) {
            return number.toLocaleString('vi-VN') + ' ₫';
        }

        // Render Product Table
        function renderAdminProducts() {
            const tbody = document.getElementById('admin-product-list');
            const keyword = (document.getElementById('admin-product-search').value || '').trim().toLowerCase();
            const brand = document.getElementById('admin-brand-filter').value || 'all';

            const list = adminProducts.filter(p => {
                const code = 'tn' + p.id.toString().padStart(4, '0');
                const matchBrand = brand === 'all' || p.brand === brand;
                const matchKeyword = !keyword || p.name.toLowerCase().includes(keyword) || code.includes(keyword);
                return matchBrand && matchKeyword;
            });

            tbody.innerHTML = list.map(product => {
                const inStock = product.stock > 0;
                const statusClass = inStock ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                const statusText = inStock ? 'Còn hàng' : 'Hết hàng';

                return `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 border border-gray-200 rounded overflow-hidden">
                                    <img class="h-10 w-10 object-contain bg-white" src="${escapeHtml(product.img)}" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">${escapeHtml(product.name)}</div>
                                    <div class="text-xs text-gray-500">Mã: TN${product.id.toString().padStart(4, '0')} &middot; ${escapeHtml(brandName(product.brand))}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-bold">${formatCurrency(product.price)}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${product.stock} chiếc</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                ${statusText}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="showToast('Sửa sản phẩm ID: ${product.id}', 'info')" class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button onclick="showToast('Xoá sản phẩm ID: ${product.id}', 'error')" class="text-red-600 hover:text-red-900"><i class="fa-solid fa-trash-can"></i></button>
                        </td>
                    </tr>
                `;
            }).join('') || '<tr><td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">Không tìm thấy sản phẩm phù hợp.</td></tr>';

            document.getElementById('admin-product-count').innerText = `Hiển thị ${list.length} / ${adminProducts.length} sản phẩm`;
        }

        // SPA Navigation for Admin
        function navigateAdmin(targetId) {
            // Hide all
            document.querySelectorAll('.admin-section').forEach(sec => sec.classList.remove('active'));
            // Show target
            document.getElementById(targetId).classList.add('active');

            // Update Nav styling
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.classList.remove('bg-primary', 'text-white', 'shadow-sm');
                btn.classList.add('text-gray-300');
            });
            const activeBtn = document.querySelector(`.nav-btn[data-target="${targetId}"]`);
            if (activeBtn) {
                activeBtn.classList.remove('text-gray-300');
                activeBtn.classList.add('bg-primary', 'text-white', 'shadow-sm');
            }

            // Close sidebar on mobile after clicking
            if (window.innerWidth < 768) {
                toggleSidebar();
            }
        }

        // Mobile Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (sidebar.classList.contains('hidden')) {
                // Mở
                sidebar.classList.remove('hidden');
                sidebar.classList.add('flex');
                overlay.classList.remove('hidden');
            } else {
                // Đóng
                sidebar.classList.add('hidden');
                sidebar.classList.remove('flex');
                overlay.classList.add('hidden');
            }
        }

        // Toast Notification System (Replace alert)
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            // Set styles based on type
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

            // Auto remove after 3 seconds
            setTimeout(() => {
                toast.classList.remove('toast-enter');
                toast.classList.add('toast-exit');
                setTimeout(() => {
                    toast.remove();
                }, 300); // Wait for exit animation
            }, 3000);
        }

        // Initialize Chart.js
        function initChart() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
                    datasets: [{
                        label: 'Doanh thu (Triệu VNĐ)',
                        data: [65, 59, 80, 81, 56, 120, 140],
                        borderColor: '#2563eb', // primary color
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // Init on load
        window.onload = () => {
            renderAdminProducts();
            initChart();
        };

        // Resize event to fix sidebar bug when rotating devices
        window.addEventListener('resize', () => {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('flex');
                overlay.classList.add('hidden');
            } else if(!overlay.classList.contains('hidden')){
                // if it was open on mobile, keep it open, otherwise hide it
                sidebar.classList.add('flex');
                sidebar.classList.remove('hidden');
            } else {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('flex');
            }
        });
    </script>
</body>
</html>