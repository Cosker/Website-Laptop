            <section id="orders" class="admin-section">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Quản lý Đơn hàng</h2>
                    <p class="text-sm text-gray-500 mt-1">Kiểm duyệt và xử lý đơn đặt hàng từ khách</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 border-b border-gray-100 flex flex-wrap gap-2">
                        <button class="px-4 py-2 bg-blue-50 text-primary rounded-lg text-sm font-medium border border-blue-100">Tất cả (45)</button>
                        <button class="px-4 py-2 bg-white text-gray-600 rounded-lg text-sm font-medium border border-gray-200 hover:bg-gray-50">Chờ xử lý (3)</button>
                        <button class="px-4 py-2 bg-white text-gray-600 rounded-lg text-sm font-medium border border-gray-200 hover:bg-gray-50">Đang giao (12)</button>
                        <button class="px-4 py-2 bg-white text-gray-600 rounded-lg text-sm font-medium border border-gray-200 hover:bg-gray-50">Hoàn thành (30)</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mã ĐH</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Khách hàng</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                <!-- Order 1 -->
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">#DH0012</td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 font-medium">Nguyễn Văn A</div>
                                        <div class="text-xs text-gray-500">0987.654.321</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Hôm nay, 14:30</td>
                                    <td class="px-6 py-4 text-sm font-bold text-primary">28,990,000 ₫</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Chờ xử lý</span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <button onclick="showToast('Đã xác nhận đơn hàng', 'success')" class="text-white bg-green-500 hover:bg-green-600 px-3 py-1.5 rounded mr-2 transition-colors">Duyệt</button>
                                        <button onclick="showToast('Mở chi tiết', 'info')" class="text-gray-500 hover:text-primary transition-colors"><i class="fa-solid fa-eye"></i></button>
                                    </td>
                                </tr>
                                <!-- Order 2 -->
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">#DH0011</td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 font-medium">Trần Thị B</div>
                                        <div class="text-xs text-gray-500">0912.345.678</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Hôm qua, 09:15</td>
                                    <td class="px-6 py-4 text-sm font-bold text-primary">45,990,000 ₫</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Đang giao</span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <button onclick="showToast('Mở chi tiết', 'info')" class="text-gray-500 hover:text-primary transition-colors"><i class="fa-solid fa-eye"></i></button>
                                    </td>
                                </tr>
                                <!-- Order 3 -->
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">#DH0010</td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 font-medium">Lê Quang C</div>
                                        <div class="text-xs text-gray-500">0909.123.456</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">12/09/2023</td>
                                    <td class="px-6 py-4 text-sm font-bold text-primary">21,990,000 ₫</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hoàn thành</span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <button onclick="showToast('Mở chi tiết', 'info')" class="text-gray-500 hover:text-primary transition-colors"><i class="fa-solid fa-eye"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>