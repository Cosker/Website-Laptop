            <section id="dashboard" class="admin-section active">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Tổng quan</h2>
                    <p class="text-sm text-gray-500 mt-1">Hoạt động kinh doanh hôm nay</p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Doanh thu -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-primary text-xl flex-shrink-0">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Doanh thu</p>
                            <p class="text-xl font-bold text-gray-900 mt-0.5">145,500,000 ₫</p>
                            <p class="text-xs text-green-500 mt-1 flex items-center"><i class="fa-solid fa-arrow-trend-up mr-1"></i> +12% so với tháng trước</p>
                        </div>
                    </div>
                    <!-- Đơn hàng -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
                        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-500 text-xl flex-shrink-0">
                            <i class="fa-solid fa-bag-shopping"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Đơn hàng mới</p>
                            <p class="text-xl font-bold text-gray-900 mt-0.5">24</p>
                            <p class="text-xs text-gray-500 mt-1 flex items-center">3 đơn chờ xử lý</p>
                        </div>
                    </div>
                    <!-- Khách hàng -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
                        <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center text-purple-500 text-xl flex-shrink-0">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Thành viên mới</p>
                            <p class="text-xl font-bold text-gray-900 mt-0.5">156</p>
                            <p class="text-xs text-green-500 mt-1 flex items-center"><i class="fa-solid fa-arrow-trend-up mr-1"></i> +5%</p>
                        </div>
                    </div>
                    <!-- Sản phẩm -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
                        <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 text-xl flex-shrink-0">
                            <i class="fa-solid fa-laptop"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Tổng sản phẩm</p>
                            <p class="text-xl font-bold text-gray-900 mt-0.5">1,240</p>
                            <p class="text-xs text-red-500 mt-1 flex items-center">15 sản phẩm sắp hết</p>
                        </div>
                    </div>
                </div>

                <!-- Charts & Recent Orders -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Chart -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-gray-800">Doanh thu 7 ngày qua</h3>
                        </div>
                        <div class="h-64">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    <!-- Mini Recent Orders -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-gray-800">Đơn hàng vừa xong</h3>
                            <button onclick="navigateAdmin('orders')" class="text-sm text-primary hover:underline">Xem tất cả</button>
                        </div>
                        <div class="space-y-4">
                            <!-- Order Item -->
                            <div class="flex items-center justify-between pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">#DH0012</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Nguyễn Văn A</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-primary">28,990,000 ₫</p>
                                    <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded mt-1">Chờ xử lý</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">#DH0011</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Trần Thị B</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-primary">45,990,000 ₫</p>
                                    <span class="inline-block px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded mt-1">Hoàn thành</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">#DH0010</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Lê Quang C</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-primary">21,990,000 ₫</p>
                                    <span class="inline-block px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded mt-1">Hoàn thành</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>