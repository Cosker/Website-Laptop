            <section id="products" class="admin-section">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Quản lý Sản phẩm</h2>
                        <p class="text-sm text-gray-500 mt-1">Danh sách tất cả laptop trong cửa hàng</p>
                    </div>
                    <button onclick="showToast('Mở Form thêm sản phẩm', 'info')" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm flex items-center transition-colors">
                        <i class="fa-solid fa-plus mr-2"></i> Thêm sản phẩm
                    </button>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between gap-4">
                        <div class="relative max-w-xs w-full">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" placeholder="Tìm tên, mã SP..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                        </div>
                        <select class="border border-gray-200 rounded-lg text-sm px-4 py-2 focus:outline-none focus:border-primary">
                            <option>Tất cả danh mục</option>
                            <option>Apple (MacBook)</option>
                            <option>Dell</option>
                            <option>Asus</option>
                            <option>HP</option>
                        </select>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Giá bán</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tồn kho</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white" id="admin-product-list">
                                <!-- Products injected by JS -->
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
                        <span>Hiển thị 1 đến 5 của 120 sản phẩm</span>
                        <div class="flex space-x-1">
                            <button class="px-3 py-1 border border-gray-200 rounded hover:bg-gray-50 cursor-not-allowed text-gray-400">Trước</button>
                            <button class="px-3 py-1 bg-primary text-white border border-primary rounded">1</button>
                            <button class="px-3 py-1 border border-gray-200 rounded hover:bg-gray-50">2</button>
                            <button class="px-3 py-1 border border-gray-200 rounded hover:bg-gray-50">3</button>
                            <button class="px-3 py-1 border border-gray-200 rounded hover:bg-gray-50">Sau</button>
                        </div>
                    </div>
                </div>
            </section>