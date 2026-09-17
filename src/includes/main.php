    <main class="flex-grow">
        <section id="home" class="page-section active">
            <!-- Banner -->
            <div class="relative bg-dark overflow-hidden">
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1531297172867-4f40f09805d7?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80" alt="Banner" class="w-full h-full object-cover opacity-40">
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-900 to-transparent"></div>
                </div>
                <div class="relative container mx-auto px-4 lg:px-8 py-24 lg:py-32 flex items-center">
                    <div class="w-full md:w-2/3 lg:w-1/2">
                        <span class="inline-block py-1 px-3 rounded-full bg-primary/20 text-blue-300 font-semibold text-sm mb-4 border border-primary/30">Mùa Tựu Trường 2023</span>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                            Đỉnh Cao Công Nghệ, <br><span class="text-primary">Dẫn Lối Tương Lai</span>
                        </h1>
                        <p class="text-lg text-gray-300 mb-8">
                            Sở hữu ngay những dòng laptop mạnh mẽ nhất với mức giá ưu đãi cực khủng. Trả góp 0% - Giao hàng hoả tốc 2h.
                        </p>
                        <button onclick="navigate('products')" class="bg-primary hover:bg-secondary text-white font-semibold py-3 px-8 rounded-lg shadow-lg transition duration-300 flex items-center">
                            Khám Phá Ngay <i class="fa-solid fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="container mx-auto px-4 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-start space-x-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-primary flex-shrink-0 text-xl"><i class="fa-solid fa-truck-fast"></i></div>
                        <div><h3 class="font-bold text-lg mb-1">Giao Hàng Siêu Tốc</h3><p class="text-gray-500 text-sm">Nhận máy trong 2h tại nội thành</p></div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-start space-x-4">
                        <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center text-green-500 flex-shrink-0 text-xl"><i class="fa-solid fa-shield-halved"></i></div>
                        <div><h3 class="font-bold text-lg mb-1">Bảo Hành 24 Tháng</h3><p class="text-gray-500 text-sm">Lỗi 1 đổi 1 trong 30 ngày đầu</p></div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-start space-x-4">
                        <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center text-purple-500 flex-shrink-0 text-xl"><i class="fa-solid fa-credit-card"></i></div>
                        <div><h3 class="font-bold text-lg mb-1">Trả Góp 0%</h3><p class="text-gray-500 text-sm">Thủ tục nhanh gọn, duyệt 5 phút</p></div>
                    </div>
                </div>
            </div>
        </section>

        <section id="products" class="page-section py-12">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="flex justify-between items-center mb-8 border-b pb-4">
                    <div>
                        <h2 class="text-3xl font-bold text-dark mb-2">Tất Cả Sản Phẩm</h2>
                        <p class="text-gray-600">Đa dạng cấu hình, đáp ứng mọi nhu cầu</p>
                    </div>
                </div>
                <!-- Vùng chứa danh sách sản phẩm sẽ được render bằng JS -->
                <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"></div>
            </div>
        </section>

        <section id="detail" class="page-section py-12">
            <div class="container mx-auto px-4 lg:px-8 max-w-5xl">
                <button onclick="navigate('products')" class="text-gray-500 hover:text-primary mb-6 flex items-center text-sm font-medium">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
                </button>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <!-- Hình ảnh SP -->
                        <div class="flex items-center justify-center bg-gray-50 rounded-xl p-8 border border-gray-100">
                            <img id="detail-img" src="" alt="Product" class="w-full max-w-md object-contain hover:scale-105 transition-transform duration-300">
                        </div>
                        <!-- Thông tin SP -->
                        <div class="flex flex-col">
                            <h1 id="detail-name" class="text-3xl font-bold text-dark mb-3">Product Name</h1>
                            <div class="flex items-center space-x-2 mb-4 text-sm">
                                <div class="text-yellow-400"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></div>
                                <span class="text-gray-500">(12 đánh giá)</span>
                            </div>
                            <div id="detail-price" class="text-3xl font-bold text-primary mb-6">0 ₫</div>
                            <div class="mb-6">
                                <h3 class="font-semibold text-gray-800 mb-2">Thông số cơ bản:</h3>
                                <p id="detail-spec" class="text-gray-600 text-sm bg-gray-50 p-3 rounded-lg border border-gray-100"></p>
                            </div>
                            <div class="mb-8">
                                <h3 class="font-semibold text-gray-800 mb-2">Đặc điểm nổi bật:</h3>
                                <p id="detail-desc" class="text-gray-600 text-sm leading-relaxed"></p>
                            </div>
                            <!-- Buttons -->
                            <div class="mt-auto grid grid-cols-2 gap-4">
                                <button id="btn-add-cart" class="w-full border-2 border-primary text-primary hover:bg-primary hover:text-white font-bold py-3.5 rounded-xl transition-colors flex items-center justify-center">
                                    <i class="fa-solid fa-cart-plus mr-2"></i> Thêm vào giỏ
                                </button>
                                <button id="btn-buy-now" class="w-full bg-primary hover:bg-secondary text-white font-bold py-3.5 rounded-xl transition-colors shadow-lg hover:shadow-xl flex items-center justify-center">
                                    Mua Ngay <i class="fa-solid fa-bolt ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="checkout" class="page-section py-12 bg-gray-100 min-h-[80vh]">
            <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
                <h2 class="text-3xl font-bold text-dark mb-8 text-center">Hoàn Tất Đơn Hàng</h2>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Form Thông tin -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <h3 class="text-xl font-bold mb-4 flex items-center text-dark"><i class="fa-solid fa-location-dot text-primary mr-2"></i> 1. Thông tin nhận hàng</h3>
                            <form id="checkout-form" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div><label class="block text-sm text-gray-600 mb-1">Họ tên</label><input type="text" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none"></div>
                                    <div><label class="block text-sm text-gray-600 mb-1">Số điện thoại</label><input type="tel" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none"></div>
                                </div>
                                <div><label class="block text-sm text-gray-600 mb-1">Địa chỉ chi tiết</label><input type="text" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none"></div>
                            </form>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <h3 class="text-xl font-bold mb-4 flex items-center text-dark"><i class="fa-solid fa-credit-card text-primary mr-2"></i> 2. Phương thức thanh toán</h3>
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 bg-blue-50/50 border-primary">
                                    <input type="radio" name="payment" checked class="w-4 h-4 text-primary focus:ring-primary">
                                    <span class="ml-3 font-medium">Thanh toán khi nhận hàng (COD)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment" class="w-4 h-4 text-primary focus:ring-primary">
                                    <span class="ml-3 font-medium">Chuyển khoản ngân hàng</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 sticky top-24">
                            <h3 class="text-xl font-bold mb-4 border-b pb-4">Tóm tắt đơn hàng</h3>
                            <div id="checkout-items" class="space-y-4 mb-4 max-h-60 overflow-y-auto pr-2">
                                <!-- Rendered by JS -->
                            </div>
                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center mb-2"><span class="text-gray-500">Tạm tính:</span><span id="checkout-subtotal" class="font-semibold">0 ₫</span></div>
                                <div class="flex justify-between items-center mb-4"><span class="text-gray-500">Phí giao hàng:</span><span class="font-semibold text-green-500">Miễn phí</span></div>
                                <div class="flex justify-between items-center mb-6"><span class="font-bold text-lg text-dark">Tổng cộng:</span><span id="checkout-total" class="font-bold text-2xl text-primary">0 ₫</span></div>
                                <button onclick="processOrder()" class="w-full bg-primary hover:bg-secondary text-white font-bold py-3.5 rounded-xl shadow-lg transition-colors flex justify-center items-center">
                                    Đặt Hàng Ngay <i class="fa-solid fa-check-circle ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="login" class="page-section py-20 bg-gray-50">
            <div class="container mx-auto px-4 flex justify-center">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 w-full max-w-md">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-dark">Chào mừng trở lại</h2>
                        <p class="text-gray-500 mt-2">Đăng nhập để tiếp tục mua sắm</p>
                    </div>
                    <form onsubmit="handleLogin(event)" class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none bg-gray-50 focus:bg-white transition-colors" placeholder="Nhập email của bạn">
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                                <a href="#" class="text-xs text-primary hover:underline">Quên mật khẩu?</a>
                            </div>
                            <input type="password" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none bg-gray-50 focus:bg-white transition-colors" placeholder="••••••••">
                        </div>
                        <button type="submit" class="w-full bg-dark hover:bg-black text-white font-bold py-3 rounded-lg transition-colors shadow-md">Đăng Nhập</button>
                    </form>
                    <p class="text-center text-sm text-gray-600 mt-6">
                        Chưa có tài khoản? <button onclick="navigate('register')" class="text-primary font-semibold hover:underline">Đăng ký ngay</button>
                    </p>
                </div>
            </div>
        </section>

        <section id="register" class="page-section py-20 bg-gray-50">
            <div class="container mx-auto px-4 flex justify-center">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 w-full max-w-md">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-dark">Tạo Tài Khoản</h2>
                        <p class="text-gray-500 mt-2">Gia nhập cộng đồng TechNova</p>
                    </div>
                    <form onsubmit="handleRegister(event)" class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
                            <input type="text" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none bg-gray-50" placeholder="Nguyễn Văn A">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none bg-gray-50" placeholder="email@example.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                            <input type="password" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none bg-gray-50" placeholder="••••••••">
                        </div>
                        <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-3 rounded-lg transition-colors shadow-md">Đăng Ký</button>
                    </form>
                    <p class="text-center text-sm text-gray-600 mt-6">
                        Đã có tài khoản? <button onclick="navigate('login')" class="text-primary font-semibold hover:underline">Đăng nhập</button>
                    </p>
                </div>
            </div>
        </section>
    </main>