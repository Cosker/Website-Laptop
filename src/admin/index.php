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
        // Mock Data for Admin Products
        const adminProducts = [
            { id: 1, name: "MacBook Pro 14 M2 Pro", price: 45990000, stock: 12, status: "Còn hàng", img: "https://images.unsplash.com/photo-1517336714731-489689fd1ca8?ixlib=rb-1.2.1&auto=format&fit=crop&w=150&q=80" },
            { id: 2, name: "Dell XPS 13 Plus 9320", price: 39500000, stock: 5, status: "Còn hàng", img: "https://images.unsplash.com/photo-1593642702821-c823b2816291?ixlib=rb-1.2.1&auto=format&fit=crop&w=150&q=80" },
            { id: 3, name: "Asus ROG Strix G15", price: 28990000, stock: 0, status: "Hết hàng", img: "https://images.unsplash.com/photo-1603302576837-37561b2e2302?ixlib=rb-1.2.1&auto=format&fit=crop&w=150&q=80" },
            { id: 4, name: "Lenovo ThinkPad X1 Carbon Gen 10", price: 42000000, stock: 8, status: "Còn hàng", img: "https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?ixlib=rb-1.2.1&auto=format&fit=crop&w=150&q=80" },
            { id: 5, name: "HP Envy x360 13", price: 22500000, stock: 24, status: "Còn hàng", img: "https://images.unsplash.com/photo-1531297172867-4f40f09805d7?ixlib=rb-1.2.1&auto=format&fit=crop&w=150&q=80" }
        ];

        // Format Currency
        function formatCurrency(number) {
            return number.toLocaleString('vi-VN') + ' ₫';
        }

        // Render Product Table
        function renderAdminProducts() {
            const tbody = document.getElementById('admin-product-list');
            tbody.innerHTML = '';

            adminProducts.forEach(product => {
                const statusClass = product.stock > 0 
                    ? 'bg-green-100 text-green-800' 
                    : 'bg-red-100 text-red-800';
                
                tbody.innerHTML += `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 border border-gray-200 rounded overflow-hidden">
                                    <img class="h-10 w-10 object-contain bg-white" src="${product.img}" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">${product.name}</div>
                                    <div class="text-xs text-gray-500">Mã: TN${product.id.toString().padStart(4, '0')}</div>
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
                                ${product.status}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="showToast('Sửa sản phẩm ID: ${product.id}', 'info')" class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button onclick="showToast('Xoá sản phẩm ID: ${product.id}', 'error')" class="text-red-600 hover:text-red-900"><i class="fa-solid fa-trash-can"></i></button>
                        </td>
                    </tr>
                `;
            });
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