    <aside id="sidebar" class="sidebar-transition bg-dark text-white w-64 flex-shrink-0 hidden md:flex flex-col h-full absolute md:relative z-20">
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b border-gray-800">
            <i class="fa-solid fa-laptop-code text-primary text-2xl mr-2"></i>
            <span class="text-xl font-bold">Tech<span class="text-primary">Admin</span></span>
            <button onclick="toggleSidebar()" class="ml-auto md:hidden text-gray-400 hover:text-white">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4 space-y-1 px-3">
            <button onclick="navigateAdmin('dashboard')" class="nav-btn w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-800 text-white bg-primary shadow-sm transition-colors" data-target="dashboard">
                <i class="fa-solid fa-chart-pie w-6 text-center mr-2"></i> Tổng quan
            </button>
            <button onclick="navigateAdmin('products')" class="nav-btn w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-800 text-gray-300 transition-colors" data-target="products">
                <i class="fa-solid fa-box-open w-6 text-center mr-2"></i> Sản phẩm
            </button>
            <button onclick="navigateAdmin('orders')" class="nav-btn w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-800 text-gray-300 transition-colors" data-target="orders">
                <i class="fa-solid fa-cart-flatbed w-6 text-center mr-2"></i> Đơn hàng
                <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">3</span>
            </button>
            <button onclick="showToast('Tính năng đang phát triển', 'info')" class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-800 text-gray-300 transition-colors">
                <i class="fa-solid fa-users w-6 text-center mr-2"></i> Khách hàng
            </button>
            <button onclick="showToast('Tính năng đang phát triển', 'info')" class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-800 text-gray-300 transition-colors">
                <i class="fa-solid fa-gear w-6 text-center mr-2"></i> Cài đặt
            </button>
        </nav>

        <!-- User Profile -->
        <div class="p-4 border-t border-gray-800">
            <div class="flex items-center">
                <img src="https://ui-avatars.com/api/?name=Admin&background=2563eb&color=fff" alt="Admin" class="w-9 h-9 rounded-full">
                <div class="ml-3">
                    <p class="text-sm font-medium text-white">Admin</p>
                    <p class="text-xs text-gray-400">Quản trị viên</p>
                </div>
            </div>
            <button onclick="window.location.href='index.html'" class="mt-4 w-full flex items-center justify-center px-3 py-2 rounded-lg text-sm font-medium border border-gray-700 text-gray-300 hover:bg-gray-800 transition-colors">
                <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Đăng xuất
            </button>
        </div>
    </aside>