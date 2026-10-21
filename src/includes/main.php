    <main class="flex-grow">
        <section id="home" class="page-section active">
            <!-- Banner -->
            <div class="relative bg-dark overflow-hidden">
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=1600&q=70" alt="" class="w-full h-full object-cover opacity-25">
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-900/90 to-gray-900/40"></div>
                </div>
                <div class="relative container mx-auto px-4 lg:px-8 py-14 lg:py-20 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                    <div>
                        <span class="inline-block py-1 px-3 rounded-full bg-primary/20 text-blue-300 font-semibold text-sm mb-4 border border-primary/30">
                            <i class="fa-solid fa-tags mr-1"></i> Ưu đãi tháng <?= date('m/Y') ?>
                        </span>
                        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-5">
                            Laptop chính hãng<br><span class="text-blue-400">cho học tập &amp; công việc</span>
                        </h1>
                        <p class="text-base md:text-lg text-gray-300 mb-8 max-w-xl">
                            Hơn <span id="hero-product-count">30</span> mẫu laptop từ <span id="hero-brand-names">các hãng lớn</span>. Bảo hành chính hãng 12 tháng, miễn phí giao hàng toàn quốc.
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <button onclick="navigate('products')" class="bg-primary hover:bg-secondary text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition duration-300 flex items-center">
                                Xem sản phẩm <i class="fa-solid fa-arrow-right ml-2"></i>
                            </button>
                            <button onclick="document.getElementById('bestseller-grid').scrollIntoView({ behavior: 'smooth', block: 'center' })" class="border border-gray-500 hover:border-white text-gray-200 hover:text-white font-semibold py-3 px-6 rounded-lg transition duration-300 flex items-center">
                                <i class="fa-solid fa-fire mr-2 text-orange-400"></i> Bán chạy nhất
                            </button>
                        </div>
                    </div>

                    <!-- Sản phẩm nổi bật (bán chạy nhất, lấy từ DB) -->
                    <div id="hero-product" class="hidden lg:block"></div>
                </div>
            </div>

            <!-- Features -->
            <div class="container mx-auto px-4 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-start space-x-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-primary flex-shrink-0 text-xl"><i class="fa-solid fa-truck-fast"></i></div>
                        <div><h3 class="font-bold text-lg mb-1">Miễn Phí Giao Hàng</h3><p class="text-gray-500 text-sm">Giao toàn quốc, kiểm tra máy trước khi nhận</p></div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-start space-x-4">
                        <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center text-green-500 flex-shrink-0 text-xl"><i class="fa-solid fa-shield-alt"></i></div>
                        <div><h3 class="font-bold text-lg mb-1">Bảo Hành Chính Hãng</h3><p class="text-gray-500 text-sm">12 tháng, đổi mới trong 7 ngày nếu lỗi</p></div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-start space-x-4">
                        <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center text-purple-500 flex-shrink-0 text-xl"><i class="fa-solid fa-credit-card"></i></div>
                        <div><h3 class="font-bold text-lg mb-1">Thanh Toán Linh Hoạt</h3><p class="text-gray-500 text-sm">Trả tiền khi nhận hàng hoặc chuyển khoản</p></div>
                    </div>
                </div>
            </div>

            <!-- Danh mục theo hãng -->
            <div class="container mx-auto px-4 lg:px-8 pb-12">
                <div class="mb-6 border-b pb-4">
                    <h2 class="text-2xl md:text-3xl font-bold text-dark mb-1">Mua Theo Hãng</h2>
                    <p class="text-gray-600 text-sm">Chọn thương hiệu laptop bạn yêu thích</p>
                </div>
                <div id="home-brands" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4"></div>
            </div>

            <!-- Sản phẩm bán chạy -->
            <div class="container mx-auto px-4 lg:px-8 pb-12">
                <div class="flex justify-between items-end mb-6 border-b pb-4">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-dark mb-1">Sản Phẩm Bán Chạy</h2>
                        <p class="text-gray-600 text-sm">Những mẫu laptop được khách hàng chọn mua nhiều nhất</p>
                    </div>
                    <button onclick="goToBrand('all')" class="text-primary font-medium text-sm hover:underline flex items-center">
                        Xem tất cả <i class="fa-solid fa-arrow-right ml-2"></i>
                    </button>
                </div>
                <div id="bestseller-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"></div>
            </div>

            <!-- Bài viết mới nhất -->
            <div class="container mx-auto px-4 lg:px-8 pb-16">
                <div class="flex justify-between items-end mb-6 border-b pb-4">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-dark mb-1">Bài Viết Mới Nhất</h2>
                        <p class="text-gray-600 text-sm">Tư vấn, so sánh và mẹo hay về laptop</p>
                    </div>
                    <button onclick="navigate('blog')" class="text-primary font-medium text-sm hover:underline flex items-center">
                        Xem tất cả <i class="fa-solid fa-arrow-right ml-2"></i>
                    </button>
                </div>
                <div id="home-articles" class="grid grid-cols-1 md:grid-cols-3 gap-6"></div>
            </div>
        </section>

        <!-- TRANG GIỚI THIỆU -->
        <section id="about" class="page-section">
            <div class="relative bg-dark overflow-hidden">
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=1920&q=80" alt="" class="w-full h-full object-cover opacity-30">
                </div>
                <div class="relative container mx-auto px-4 lg:px-8 py-16 md:py-20 text-center">
                    <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">Về <span class="text-primary">TechNova</span></h1>
                    <p class="text-gray-300 max-w-2xl mx-auto">Hệ thống bán lẻ laptop chính hãng, tư vấn đúng nhu cầu và đồng hành cùng bạn sau khi mua.</p>
                </div>
            </div>

            <div class="container mx-auto px-4 lg:px-8 py-12 max-w-5xl">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center mb-14">
                    <div>
                        <h2 class="text-2xl font-bold text-dark mb-4">Câu chuyện của chúng tôi</h2>
                        <p class="text-gray-600 leading-relaxed mb-3">TechNova ra đời với mong muốn giúp mọi người tìm được chiếc laptop phù hợp với nhu cầu học tập và công việc, thay vì chỉ chạy theo cấu hình hay giá rẻ.</p>
                        <p class="text-gray-600 leading-relaxed">Chúng tôi kinh doanh các dòng laptop từ nhiều thương hiệu như Apple, Dell, Asus, Lenovo, HP, kèm theo tư vấn rõ ràng và chính sách bảo hành minh bạch.</p>
                    </div>
                    <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=800&q=80" alt="Laptop" class="rounded-2xl shadow-md w-full object-cover h-64">
                </div>

                <h2 class="text-2xl font-bold text-dark mb-6 text-center">Giá trị cốt lõi</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-14">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
                        <div class="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center text-primary mx-auto mb-4 text-2xl"><i class="fa-solid fa-circle-check"></i></div>
                        <h3 class="font-bold text-lg mb-2">Chính hãng</h3>
                        <p class="text-gray-500 text-sm">Sản phẩm có nguồn gốc rõ ràng, đầy đủ hoá đơn và phiếu bảo hành.</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
                        <div class="w-14 h-14 bg-green-50 rounded-full flex items-center justify-center text-green-500 mx-auto mb-4 text-2xl"><i class="fa-solid fa-headset"></i></div>
                        <h3 class="font-bold text-lg mb-2">Tư vấn tận tâm</h3>
                        <p class="text-gray-500 text-sm">Lắng nghe nhu cầu để gợi ý cấu hình vừa đủ, không bán thừa.</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
                        <div class="w-14 h-14 bg-purple-50 rounded-full flex items-center justify-center text-purple-500 mx-auto mb-4 text-2xl"><i class="fa-solid fa-shield-alt"></i></div>
                        <h3 class="font-bold text-lg mb-2">Hậu mãi dài lâu</h3>
                        <p class="text-gray-500 text-sm">Bảo hành 24 tháng, hỗ trợ kỹ thuật và đổi trả trong 30 ngày đầu.</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <h2 class="text-2xl font-bold text-dark mb-4">Thông tin liên hệ</h2>
                    <!-- TODO: thay bằng thông tin thật của cửa hàng -->
                    <ul class="space-y-3 text-gray-600 text-sm">
                        <li class="flex items-start"><i class="fa-solid fa-location-dot text-primary w-6 mt-0.5"></i><span>Địa chỉ cửa hàng: 166/40 Trần Văn Dư, Phường Tân Bình, TP.Hồ Chí Minh</span></li>
                        <li class="flex items-start"><i class="fa-solid fa-phone text-primary w-6 mt-0.5"></i><span>Hotline: 19002331</span></li>
                        <li class="flex items-start"><i class="fa-solid fa-envelope text-primary w-6 mt-0.5"></i><span>Email: Technova@info.vn</span></li>
                        <li class="flex items-start"><i class="fa-regular fa-clock text-primary w-6 mt-0.5"></i><span>Giờ làm việc: 8:00 - 21:00 hằng ngày</span></li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- DANH SÁCH BÀI VIẾT -->
        <section id="blog" class="page-section py-12">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="mb-8 border-b pb-4">
                    <h2 class="text-3xl font-bold text-dark mb-2">Tin Tức &amp; Bài Viết</h2>
                    <p class="text-gray-600">Kinh nghiệm chọn mua, so sánh và mẹo sử dụng laptop</p>
                </div>
                <div id="blog-categories" class="flex flex-wrap gap-2 mb-8"></div>
                <div id="blog-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
            </div>
        </section>

        <!-- CHI TIẾT BÀI VIẾT -->
        <section id="article" class="page-section py-12">
            <div class="container mx-auto px-4 lg:px-8 max-w-3xl">
                <button onclick="navigate('blog')" class="text-gray-500 hover:text-primary mb-6 flex items-center text-sm font-medium">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại bài viết
                </button>
                <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <img id="article-img" src="" alt="" class="w-full h-64 md:h-80 object-cover">
                    <div class="p-6 md:p-10">
                        <span id="article-category" class="inline-block py-1 px-3 rounded-full bg-blue-50 text-primary font-semibold text-xs mb-3"></span>
                        <h1 id="article-title" class="text-2xl md:text-4xl font-bold text-dark mb-4 leading-tight"></h1>
                        <div class="flex items-center text-sm text-gray-500 mb-8 pb-6 border-b border-gray-100">
                            <i class="fa-regular fa-user mr-2"></i><span id="article-author" class="mr-5"></span>
                            <i class="fa-regular fa-calendar mr-2"></i><span id="article-date"></span>
                        </div>
                        <div id="article-content" class="space-y-4 text-gray-700 leading-relaxed"></div>
                    </div>
                </article>

                <h3 class="text-xl font-bold text-dark mt-12 mb-4">Bài viết liên quan</h3>
                <div id="article-related" class="grid grid-cols-1 sm:grid-cols-2 gap-6"></div>
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
                <!-- Tìm kiếm sản phẩm -->
                <div class="mb-5 max-w-xl">
                    <label for="product-search" class="block text-sm font-medium text-gray-700 mb-2">Tìm laptop theo tên hoặc cấu hình</label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input id="product-search" type="search" oninput="searchProducts(this.value)"
                               placeholder="Ví dụ: MacBook, Dell, Core i7..."
                               class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none bg-white">
                    </div>
                </div>
                <!-- Bộ lọc theo hãng (render bằng JS) -->
                <div id="brand-filters" class="flex flex-wrap gap-2 mb-4"></div>
                <p id="product-count" class="text-sm text-gray-500 mb-6"></p>
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
                            <span id="detail-brand" class="inline-block w-max py-1 px-3 rounded-full bg-blue-50 text-primary font-semibold text-xs mb-3"></span>
                            <h1 id="detail-name" class="text-3xl font-bold text-dark mb-3">Product Name</h1>
                            <div id="detail-meta" class="flex flex-wrap items-center gap-x-4 gap-y-1 mb-4 text-sm text-gray-500"></div>
                            <div id="detail-price" class="text-3xl font-bold text-primary mb-6">0 ₫</div>
                            <div class="mb-6">
                                <h3 class="font-semibold text-gray-800 mb-2">Cấu hình:</h3>
                                <p id="detail-spec" class="text-gray-600 text-sm bg-gray-50 p-3 rounded-lg border border-gray-100"></p>
                            </div>
                            <div class="mb-8">
                                <h3 class="font-semibold text-gray-800 mb-2">Đặc điểm nổi bật:</h3>
                                <p id="detail-summary" class="text-gray-600 text-sm leading-relaxed"></p>
                                <ul class="mt-3 space-y-1.5 text-sm text-gray-600">
                                    <li><i class="fa-solid fa-shield-alt text-primary w-5"></i> Bảo hành chính hãng 12 tháng</li>
                                    <li><i class="fa-solid fa-rotate-left text-primary w-5"></i> Đổi mới trong 7 ngày nếu lỗi nhà sản xuất</li>
                                    <li><i class="fa-solid fa-truck-fast text-primary w-5"></i> Miễn phí giao hàng toàn quốc</li>
                                </ul>
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

                <!-- Mô tả + Thông số kỹ thuật -->
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mt-6">
                    <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                        <h2 class="text-xl font-bold text-dark mb-4">Mô tả sản phẩm</h2>
                        <div id="detail-desc" class="space-y-3 text-gray-600 leading-relaxed"></div>
                    </div>
                    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                        <h2 class="text-xl font-bold text-dark mb-4">Thông số kỹ thuật</h2>
                        <table class="w-full text-sm">
                            <tbody id="detail-specs" class="divide-y divide-gray-100"></tbody>
                        </table>
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
                                    <div><label class="block text-sm text-gray-600 mb-1">Họ tên</label><input name="receiver_name" id="checkout-name" type="text" required autocomplete="name" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none"></div>
                                    <div><label class="block text-sm text-gray-600 mb-1">Số điện thoại</label><input name="phone" id="checkout-phone" type="tel" required pattern="[0-9+\(\) .\-]{9,16}" autocomplete="tel" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none"></div>
                                </div>
                                <div><label class="block text-sm text-gray-600 mb-1">Địa chỉ chi tiết</label><input name="address" id="checkout-address" type="text" required autocomplete="street-address" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none"></div>
                            </form>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <h3 class="text-xl font-bold mb-4 flex items-center text-dark"><i class="fa-solid fa-credit-card text-primary mr-2"></i> 2. Phương thức thanh toán</h3>
                            <div class="space-y-3">
                                <label class="payment-option flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment" value="COD" checked onchange="updatePaymentUI()" class="w-4 h-4 text-primary focus:ring-primary">
                                    <i class="fa-solid fa-money-bill-wave text-green-600 ml-3 w-5"></i>
                                    <span class="ml-2 font-medium">Thanh toán khi nhận hàng (COD)</span>
                                </label>
                                <label class="payment-option flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment" value="BANK" onchange="updatePaymentUI()" class="w-4 h-4 text-primary focus:ring-primary">
                                    <i class="fa-solid fa-building-columns text-primary ml-3 w-5"></i>
                                    <span class="ml-2 font-medium">Chuyển khoản ngân hàng</span>
                                </label>

                                <!-- Chọn ngân hàng: chỉ hiện khi chọn chuyển khoản -->
                                <div id="bank-picker" class="hidden pt-2">
                                    <p class="text-sm text-gray-600 mb-3">Chọn ngân hàng bạn muốn chuyển khoản đến:</p>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                        <?php foreach (SHOP_BANKS as $code => $bank): ?>
                                            <label class="bank-option relative flex flex-col items-center justify-center p-3 border-2 rounded-xl cursor-pointer hover:border-primary transition-colors text-center">
                                                <input type="radio" name="bank" value="<?= $code ?>" onchange="updatePaymentUI()" class="sr-only">
                                                <span class="w-10 h-10 rounded-full flex items-center justify-center text-white text-xs font-bold mb-2" style="background: <?= $bank['color'] ?>"><?= htmlspecialchars($code) ?></span>
                                                <span class="text-sm font-semibold text-dark"><?= htmlspecialchars($bank['name']) ?></span>
                                                <i class="bank-check fa-solid fa-circle-check text-primary absolute top-2 right-2 hidden"></i>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>

                                    <div id="bank-info" class="hidden mt-4 p-4 rounded-xl bg-blue-50 border border-blue-100 text-sm">
                                        <p class="font-semibold text-dark mb-2"><i class="fa-solid fa-circle-info text-primary mr-1"></i> Thông tin chuyển khoản</p>
                                        <dl class="grid grid-cols-[auto,1fr] gap-x-4 gap-y-1.5">
                                            <dt class="text-gray-500">Ngân hàng</dt><dd id="bank-info-name" class="font-medium text-dark"></dd>
                                            <dt class="text-gray-500">Số tài khoản</dt><dd id="bank-info-account" class="font-bold text-dark tracking-wide"></dd>
                                            <dt class="text-gray-500">Chủ tài khoản</dt><dd id="bank-info-holder" class="font-medium text-dark"></dd>
                                            <dt class="text-gray-500">Số tiền</dt><dd id="bank-info-amount" class="font-bold text-primary"></dd>
                                            <dt class="text-gray-500">Nội dung</dt><dd class="font-medium text-dark">Mã đơn hàng (hiện sau khi đặt hàng)</dd>
                                        </dl>
                                        <p class="text-xs text-gray-500 mt-3">Đơn hàng được xác nhận sau khi cửa hàng nhận được tiền chuyển khoản.</p>
                                    </div>
                                </div>
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


        <section id="account" class="page-section py-12">
            <div class="container mx-auto px-4 lg:px-8 max-w-5xl">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-dark">Tài khoản của tôi</h2>
                        <p class="text-gray-500 mt-1">Quản lý thông tin cá nhân và theo dõi đơn hàng</p>
                    </div>
                    <button onclick="handleLogout()" class="px-4 py-2 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 font-medium">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i>Đăng xuất
                    </button>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-lg font-bold mb-5"><i class="fa-regular fa-user text-primary mr-2"></i>Thông tin cá nhân</h3>
                        <form id="profile-form" onsubmit="saveProfile(event)" class="space-y-4">
                            <div><label class="block text-sm text-gray-600 mb-1">Họ và tên</label><input id="profile-name" name="name" required class="w-full px-3 py-2.5 rounded-lg border border-gray-200 outline-none focus:border-primary"></div>
                            <div><label class="block text-sm text-gray-600 mb-1">Email</label><input id="profile-email" type="email" disabled class="w-full px-3 py-2.5 rounded-lg border border-gray-200 bg-gray-100 text-gray-500"></div>
                            <div><label class="block text-sm text-gray-600 mb-1">Số điện thoại</label><input id="profile-phone" name="phone" type="tel" class="w-full px-3 py-2.5 rounded-lg border border-gray-200 outline-none focus:border-primary"></div>
                            <div><label class="block text-sm text-gray-600 mb-1">Địa chỉ</label><textarea id="profile-address" name="address" rows="3" class="w-full px-3 py-2.5 rounded-lg border border-gray-200 outline-none focus:border-primary"></textarea></div>
                            <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-semibold py-2.5 rounded-lg">Lưu thông tin</button>
                        </form>
                    </div>
                    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <div class="flex items-center justify-between gap-3 mb-5">
                            <h3 class="text-lg font-bold"><i class="fa-solid fa-box-open text-primary mr-2"></i>Lịch sử đơn hàng</h3>
                            <span id="order-count" class="text-sm text-gray-500">0 đơn hàng</span>
                        </div>
                        <div id="order-history" class="space-y-4">
                            <p class="text-gray-500 text-sm">Bạn chưa có đơn hàng nào.</p>
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
                            <input name="email" type="email" required autocomplete="email" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none bg-gray-50 focus:bg-white transition-colors" placeholder="Nhập email của bạn">
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                                <a href="#" class="text-xs text-primary hover:underline">Quên mật khẩu?</a>
                            </div>
                            <input name="password" type="password" required autocomplete="current-password" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none bg-gray-50 focus:bg-white transition-colors" placeholder="••••••••">
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
                            <input name="name" type="text" required minlength="2" autocomplete="name" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none bg-gray-50" placeholder="Nguyễn Văn A">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input name="email" type="email" required autocomplete="email" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none bg-gray-50" placeholder="email@example.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                            <input name="password" type="password" required minlength="8" autocomplete="new-password" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none bg-gray-50" placeholder="Tối thiểu 8 ký tự">
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