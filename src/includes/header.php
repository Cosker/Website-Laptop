    <header class="bg-white shadow-sm sticky top-0 z-40">   
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <a onclick="navigate('home')" class="flex items-center cursor-pointer">
                    <i class="fa-solid fa-laptop-code text-primary text-3xl mr-2"></i>
                    <span class="text-2xl font-bold text-dark">Tech<span class="text-primary">Nova</span></span>
                </a>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex space-x-8">
                    <button onclick="navigate('home')" class="font-medium text-gray-600 hover:text-primary transition-colors">Trang chủ</button>
                    <button onclick="navigate('products')" class="font-medium text-gray-600 hover:text-primary transition-colors">Sản phẩm</button>
                    <button onclick="showModal('Thông tin', 'Tính năng đang phát triển', 'info')" class="font-medium text-gray-600 hover:text-primary transition-colors">Liên hệ</button>
                </nav>

                <!-- Actions -->
                <div class="flex items-center space-x-3 md:space-x-5">
                    <!-- Auth Buttons (JS sẽ cập nhật khối này) -->
                    <div id="auth-buttons" class="hidden md:flex items-center space-x-3 mr-2">
                        <!-- Rendered by JS -->
                    </div>

                    <!-- Cart Toggle -->
                    <button onclick="toggleCart()" class="relative text-gray-600 hover:text-primary transition-colors p-2">
                        <i class="fa-solid fa-cart-shopping text-xl"></i>
                        <span id="cart-count" class="absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center -mr-1 -mt-1 shadow-sm">0</span>
                    </button>
                    
                    <button class="md:hidden text-gray-600 hover:text-primary focus:outline-none">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>