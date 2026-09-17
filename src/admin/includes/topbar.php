        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 lg:px-6">     
            <div class="flex items-center">
                <button onclick="toggleSidebar()" class="md:hidden text-gray-500 hover:text-dark mr-4 focus:outline-none">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div class="relative hidden sm:block w-64">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" placeholder="Tìm kiếm..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-gray-50">
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button class="relative p-2 text-gray-400 hover:text-dark transition-colors">
                    <i class="fa-regular fa-bell text-xl"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <a href="index.html" class="text-sm font-medium text-primary hover:underline hidden sm:block">Xem trang web <i class="fa-solid fa-up-right-from-square ml-1"></i></a>
            </div>
        </header>