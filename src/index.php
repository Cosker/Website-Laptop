<?php
require_once __DIR__ . '/data/products.php';
require_once __DIR__ . '/data/articles.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNova - Hệ Thống Bán Lẻ Laptop</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { primary: '#2563eb', secondary: '#1d4ed8', dark: '#0f172a' }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
        
        /* Layout Transitions */
        .page-section { display: none; animation: fadeIn 0.4s ease-in-out; }
        .page-section.active { display: block; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Cart Animations */
        #cart-sidebar { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .cart-open { transform: translateX(0) !important; }
        
        /* Product Card Hover */
        .product-card { transition: all 0.2s ease-in-out; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <!-- Header -->
    <?php require_once 'includes/header.php'; ?>

    <!-- Main -->
    <?php require_once 'includes/main.php'; ?>

    <!-- Cart Overlay -->
    <div id="cart-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden transition-opacity" onclick="toggleCart()"></div>
    
    <!-- Cart Sidebar -->
    <?php require_once 'includes/cart-sidebar.php'; ?>

    <!-- Custom Modal -->
    <?php require_once 'includes/custom-modal.php'; ?>

    <!-- Footer -->
    <?php require_once 'includes/footer.php'; ?>

    <script>
        // 1. DATA (lấy từ PHP: data/products.php -- sau này đổi sang DB không cần sửa JS)
        const brands = <?= json_encode(getBrands(), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const products = <?= json_encode(getProducts(), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        // Ảnh dự phòng: ảnh nào tải lỗi sẽ tự đổi sang ảnh này
        const FALLBACK_IMG = 'assets/placeholder-laptop.svg';
        document.addEventListener('error', (e) => {
            const t = e.target;
            if (t && t.tagName === 'IMG' && !t.dataset.fallback) {
                t.dataset.fallback = '1';
                t.src = FALLBACK_IMG;
            }
        }, true);

        // Bài viết: dữ liệu lấy từ PHP (data/articles.php), sau này đổi sang DB không cần sửa JS
        const articles = <?= json_encode(getArticles(), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        // 2. STATE VARIABLES
        let cart = JSON.parse(localStorage.getItem('technova_cart')) || [];
        let currentUser = JSON.parse(localStorage.getItem('technova_user')) || null;
        let registeredUsers = JSON.parse(localStorage.getItem('technova_users')) || [];
        let orders = JSON.parse(localStorage.getItem('technova_orders')) || [];
        let checkoutType = 'cart'; // 'cart' or 'buy_now'
        let buyNowItem = null;

        // 3. UTILITIES
        const formatCurrency = (num) => num.toLocaleString('vi-VN') + ' ₫';

        // Custom Modal
        function showModal(title, message, type = 'info') {
            const modal = document.getElementById('custom-modal');
            const content = document.getElementById('modal-content');
            const icon = document.getElementById('modal-icon');
            
            document.getElementById('modal-title').innerText = title;
            document.getElementById('modal-message').innerText = message;
            
            if (type === 'success') { icon.innerHTML = '<i class="fa-solid fa-check text-green-500"></i>'; icon.className = "w-16 h-16 rounded-full flex items-center justify-center text-3xl mb-4 bg-green-100"; }
            else if (type === 'error') { icon.innerHTML = '<i class="fa-solid fa-xmark text-red-500"></i>'; icon.className = "w-16 h-16 rounded-full flex items-center justify-center text-3xl mb-4 bg-red-100"; }
            else { icon.innerHTML = '<i class="fa-solid fa-info text-blue-500"></i>'; icon.className = "w-16 h-16 rounded-full flex items-center justify-center text-3xl mb-4 bg-blue-100"; }
            
            modal.classList.remove('hidden');
            setTimeout(() => { content.classList.remove('scale-95', 'opacity-0'); content.classList.add('scale-100', 'opacity-100'); }, 10);
        }
        function closeModal() {
            const modal = document.getElementById('custom-modal');
            const content = document.getElementById('modal-content');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        // 4. NAVIGATION & RENDERING
        function navigate(pageId, param = null) {
            document.querySelectorAll('.page-section').forEach(sec => sec.classList.remove('active'));
            document.getElementById(pageId).classList.add('active');
            window.scrollTo(0, 0);

            if (pageId === 'detail' && param) renderProductDetail(param);
            else if (pageId === 'checkout') renderCheckout();
            else if (pageId === 'article' && param) renderArticleDetail(param);
            else if (pageId === 'account') renderAccount();
        }

        // 4b. ARTICLES (BÀI VIẾT)
        let currentBlogCategory = 'Tất cả';

        const escapeHtml = (str) => String(str).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
        const formatDate = (iso) => new Date(iso + 'T00:00:00').toLocaleDateString('vi-VN');

        function articleCardHTML(a) {
            return `
                <div class="product-card bg-white rounded-xl border border-gray-100 overflow-hidden flex flex-col h-full cursor-pointer" onclick="navigate('article', ${a.id})">
                    <img src="${escapeHtml(a.img)}" alt="${escapeHtml(a.title)}" class="h-44 w-full object-cover">
                    <div class="p-5 flex flex-col flex-grow">
                        <span class="text-xs font-semibold text-primary mb-2">${escapeHtml(a.category)}</span>
                        <h3 class="font-bold text-base text-dark mb-2 line-clamp-2 hover:text-primary transition-colors">${escapeHtml(a.title)}</h3>
                        <p class="text-sm text-gray-500 mb-4 flex-grow line-clamp-3">${escapeHtml(a.excerpt)}</p>
                        <div class="text-xs text-gray-400 flex items-center mt-auto"><i class="fa-regular fa-calendar mr-2"></i>${formatDate(a.created_at)}</div>
                    </div>
                </div>`;
        }

        function renderHomeArticles() {
            const latest = [...articles].sort((a, b) => b.created_at.localeCompare(a.created_at)).slice(0, 3);
            document.getElementById('home-articles').innerHTML = latest.map(articleCardHTML).join('');
        }

        function renderBlog() {
            const categories = ['Tất cả', ...new Set(articles.map(a => a.category))];
            document.getElementById('blog-categories').innerHTML = categories.map(c => {
                const active = c === currentBlogCategory;
                const cls = active
                    ? 'bg-primary text-white border-primary'
                    : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50';
                return `<button onclick="filterBlog('${escapeHtml(c)}')" class="px-4 py-2 rounded-full text-sm font-medium border transition-colors ${cls}">${escapeHtml(c)}</button>`;
            }).join('');

            const list = articles
                .filter(a => currentBlogCategory === 'Tất cả' || a.category === currentBlogCategory)
                .sort((a, b) => b.created_at.localeCompare(a.created_at));
            document.getElementById('blog-grid').innerHTML = list.length
                ? list.map(articleCardHTML).join('')
                : '<p class="text-gray-500 col-span-full text-center py-10">Chưa có bài viết nào trong mục này.</p>';
        }

        function filterBlog(category) {
            currentBlogCategory = category;
            renderBlog();
        }

        function renderArticleDetail(id) {
            const a = articles.find(item => item.id === id);
            if (!a) return;
            const img = document.getElementById('article-img');
            img.src = a.img;
            img.alt = a.title;
            document.getElementById('article-category').innerText = a.category;
            document.getElementById('article-title').innerText = a.title;
            document.getElementById('article-author').innerText = a.author;
            document.getElementById('article-date').innerText = formatDate(a.created_at);
            document.getElementById('article-content').innerHTML = a.content.map(p => `<p>${escapeHtml(p)}</p>`).join('');

            const related = articles.filter(x => x.id !== a.id)
                .sort((x, y) => (y.category === a.category) - (x.category === a.category))
                .slice(0, 2);
            document.getElementById('article-related').innerHTML = related.map(articleCardHTML).join('');
        }

        // PRODUCTS (SẢN PHẨM) & PHÂN LOẠI THEO HÃNG
        let currentBrand = 'all';
        let currentSearch = '';

        function brandName(slug) {
            const b = brands.find(x => x.slug === slug);
            return b ? b.short : slug;
        }

        function productCardHTML(p, badge = '') {
            const outOfStock = p.stock <= 0;
            let tag = '';
            if (outOfStock) tag = '<span class="absolute top-3 left-3 z-10 bg-gray-800 text-white text-xs font-semibold px-2.5 py-1 rounded-full">Hết hàng</span>';
            else if (badge) tag = `<span class="absolute top-3 left-3 z-10 bg-red-500 text-white text-xs font-semibold px-2.5 py-1 rounded-full">${escapeHtml(badge)}</span>`;
            return `
                <div class="product-card relative bg-white rounded-xl border border-gray-100 overflow-hidden flex flex-col h-full cursor-pointer" onclick="navigate('detail', ${p.id})">
                    ${tag}
                    <div class="block h-48 overflow-hidden bg-white p-6 border-b border-gray-50 flex items-center justify-center group">
                        <img src="${escapeHtml(p.img)}" alt="${escapeHtml(p.name)}" class="object-contain h-full w-full group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <span class="text-xs font-semibold text-primary mb-1">${escapeHtml(brandName(p.brand))}</span>
                        <h3 class="font-bold text-base text-dark mb-1 line-clamp-2 hover:text-primary transition-colors">${escapeHtml(p.name)}</h3>
                        <p class="text-xs text-gray-500 mb-4 flex-grow line-clamp-2">${escapeHtml(p.spec)}</p>
                        <div class="flex items-center justify-between mt-auto">
                            <span class="text-primary font-bold text-lg">${formatCurrency(p.price)}</span>
                            <button onclick="event.stopPropagation(); addToCart(${p.id})" class="bg-gray-50 hover:bg-primary hover:text-white text-gray-700 w-10 h-10 rounded-full flex items-center justify-center transition-colors border border-gray-200 hover:border-primary">
                                <i class="fa-solid fa-cart-plus text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        function renderBrandFilters() {
            const chip = (slug, label, count) => {
                const active = slug === currentBrand;
                const cls = active
                    ? 'bg-primary text-white border-primary'
                    : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50';
                return `<button onclick="filterBrand('${slug}')" class="px-4 py-2 rounded-full text-sm font-medium border transition-colors ${cls}">${escapeHtml(label)} <span class="${active ? 'text-blue-100' : 'text-gray-400'}">(${count})</span></button>`;
            };
            document.getElementById('brand-filters').innerHTML =
                chip('all', 'Tất cả', products.length) +
                brands.map(b => chip(b.slug, b.short, products.filter(p => p.brand === b.slug).length)).join('');
        }

        function filterBrand(slug) {
            currentBrand = slug;
            renderBrandFilters();
            renderProducts();
        }

        // Từ trang chủ bấm vào hãng -> sang trang Sản phẩm đã lọc theo hãng đó
        function goToBrand(slug) {
            filterBrand(slug);
            navigate('products');
        }

        function renderHomeBrands() {
            document.getElementById('home-brands').innerHTML = brands.map(b => {
                const count = products.filter(p => p.brand === b.slug).length;
                return `
                    <div onclick="goToBrand('${b.slug}')" class="product-card bg-white rounded-xl border border-gray-100 p-5 text-center cursor-pointer">
                        <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-primary mx-auto mb-3 text-xl"><i class="fa-solid fa-laptop"></i></div>
                        <h3 class="font-bold text-dark">${escapeHtml(b.short)}</h3>
                        <p class="text-xs text-gray-500 mt-1">${count} sản phẩm</p>
                    </div>`;
            }).join('');
        }

        function renderBestSellers() {
            const top = [...products].sort((a, b) => b.sold - a.sold).slice(0, 4);
            document.getElementById('bestseller-grid').innerHTML = top.map(p => productCardHTML(p, 'Bán chạy')).join('');
        }

        function searchProducts(value) {
            currentSearch = String(value || '').trim().toLocaleLowerCase('vi');
            renderProducts();
        }

        function renderProducts() {
            let list = currentBrand === 'all' ? [...products] : products.filter(p => p.brand === currentBrand);
            if (currentSearch) {
                list = list.filter(p =>
                    `${p.name} ${p.spec} ${p.desc} ${brandName(p.brand)}`.toLocaleLowerCase('vi').includes(currentSearch)
                );
            }
            const label = currentBrand === 'all' ? 'tất cả các hãng' : brandName(currentBrand);
            document.getElementById('product-count').innerText = `Hiển thị ${list.length} sản phẩm - ${label}`;
            document.getElementById('product-grid').innerHTML = list.length
                ? list.map(p => productCardHTML(p)).join('')
                : '<p class="text-gray-500 col-span-full text-center py-10">Không tìm thấy sản phẩm phù hợp.</p>';
        }

        function renderProductDetail(id) {
            const p = products.find(item => item.id === id);
            if(!p) return;
            document.getElementById('detail-img').src = p.img;
            document.getElementById('detail-brand').innerText = brandName(p.brand);
            document.getElementById('detail-name').innerText = p.name;
            document.getElementById('detail-price').innerText = formatCurrency(p.price);
            document.getElementById('detail-spec').innerText = p.spec;
            document.getElementById('detail-desc').innerText = p.desc;
            document.getElementById('btn-add-cart').onclick = () => addToCart(p.id);
            document.getElementById('btn-buy-now').onclick = () => buyNow(p.id);
        }

        // 5. AUTHENTICATION & TÀI KHOẢN
        function updateAuthUI() {
            const authButtons = document.getElementById('auth-buttons');
            if (currentUser) {
                authButtons.innerHTML = `
                    <button onclick="navigate('account')" class="text-sm font-medium text-gray-600 bg-gray-100 px-3 py-1.5 rounded-full hover:bg-blue-50 hover:text-primary">
                        <i class="fa-regular fa-user mr-1"></i> ${escapeHtml(currentUser.name || 'Tài khoản')}
                    </button>
                    <button onclick="handleLogout()" class="text-sm text-red-500 hover:underline ml-1 font-medium">Đăng xuất</button>
                `;
            } else {
                authButtons.innerHTML = `
                    <button onclick="navigate('login')" class="text-sm font-medium text-gray-600 hover:text-primary transition-colors">Đăng nhập</button>
                    <button onclick="navigate('register')" class="text-sm font-medium bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200 transition-colors shadow-sm border border-gray-200">Đăng ký</button>
                `;
            }
        }

        function handleLogin(e) {
            e.preventDefault();
            const form = e.currentTarget;
            const email = form.elements.email.value.trim().toLowerCase();
            const password = form.elements.password.value;
            const user = registeredUsers.find(u => u.email.toLowerCase() === email && u.password === password);

            if (!user) {
                showModal('Đăng nhập thất bại', 'Email hoặc mật khẩu không đúng. Nếu chưa có tài khoản, hãy đăng ký trước.', 'error');
                return;
            }

            currentUser = { name: user.name, email: user.email, phone: user.phone || '', address: user.address || '' };
            localStorage.setItem('technova_user', JSON.stringify(currentUser));
            updateAuthUI();
            showModal('Thành công', `Xin chào ${currentUser.name}!`, 'success');
            navigate('home');
        }

        function handleRegister(e) {
            e.preventDefault();
            const form = e.currentTarget;
            const name = form.elements.name.value.trim();
            const email = form.elements.email.value.trim().toLowerCase();
            const password = form.elements.password.value;

            if (password.length < 8) {
                showModal('Mật khẩu chưa hợp lệ', 'Mật khẩu cần có ít nhất 8 ký tự.', 'error');
                return;
            }
            if (registeredUsers.some(u => u.email.toLowerCase() === email)) {
                showModal('Email đã tồn tại', 'Email này đã được đăng ký. Hãy đăng nhập hoặc sử dụng email khác.', 'error');
                return;
            }

            registeredUsers.push({ name, email, password, phone: '', address: '', createdAt: new Date().toISOString() });
            localStorage.setItem('technova_users', JSON.stringify(registeredUsers));
            form.reset();
            navigate('login');
            const loginForm = document.querySelector('#login form');
            if (loginForm) loginForm.elements.email.value = email;
            showModal('Đăng ký thành công', 'Tài khoản đã được lưu trên trình duyệt. Hãy đăng nhập để tiếp tục.', 'success');
        }

        function handleLogout() {
            currentUser = null;
            localStorage.removeItem('technova_user');
            updateAuthUI();
            showModal('Thông báo', 'Bạn đã đăng xuất khỏi hệ thống.', 'info');
            navigate('home');
        }

        function renderAccount() {
            if (!currentUser) {
                showModal('Yêu cầu đăng nhập', 'Vui lòng đăng nhập để xem tài khoản.', 'error');
                navigate('login');
                return;
            }

            document.getElementById('profile-name').value = currentUser.name || '';
            document.getElementById('profile-email').value = currentUser.email || '';
            document.getElementById('profile-phone').value = currentUser.phone || '';
            document.getElementById('profile-address').value = currentUser.address || '';

            const myOrders = orders.filter(order => order.email === currentUser.email);
            document.getElementById('order-count').innerText = `${myOrders.length} đơn hàng`;
            const history = document.getElementById('order-history');

            if (!myOrders.length) {
                history.innerHTML = `<div class="text-center py-10 text-gray-500"><i class="fa-solid fa-box-open text-3xl mb-3"></i><p>Bạn chưa có đơn hàng nào.</p><button onclick="navigate('products')" class="mt-4 text-primary font-semibold hover:underline">Khám phá sản phẩm</button></div>`;
                return;
            }

            history.innerHTML = [...myOrders].reverse().map(order => `
                <article class="border border-gray-200 rounded-xl p-4">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 border-b border-gray-100 pb-3 mb-3">
                        <div>
                            <p class="font-bold text-dark">Đơn hàng #${escapeHtml(order.id)}</p>
                            <p class="text-xs text-gray-500 mt-1">${new Date(order.createdAt).toLocaleString('vi-VN')}</p>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-amber-50 text-amber-700">${escapeHtml(order.status || 'Đang xử lý')}</span>
                    </div>
                    <div class="space-y-2">
                        ${order.items.map(item => `
                            <div class="flex items-center gap-3">
                                <img src="${escapeHtml(item.img)}" alt="" class="w-12 h-12 object-contain bg-gray-50 rounded">
                                <div class="flex-1 min-w-0"><p class="text-sm font-medium">${escapeHtml(item.name)}</p><p class="text-xs text-gray-500">Số lượng: ${item.quantity}</p></div>
                                <span class="text-sm font-semibold">${formatCurrency(item.price * item.quantity)}</span>
                            </div>`).join('')}
                    </div>
                    <div class="border-t border-gray-100 mt-3 pt-3 flex justify-between items-center gap-3">
                        <span class="text-sm text-gray-500">Thanh toán: ${escapeHtml(order.payment)}</span>
                        <strong class="text-primary">${formatCurrency(order.total)}</strong>
                    </div>
                </article>
            `).join('');
        }

        function saveProfile(e) {
            e.preventDefault();
            if (!currentUser) return;
            const name = document.getElementById('profile-name').value.trim();
            const phone = document.getElementById('profile-phone').value.trim();
            const address = document.getElementById('profile-address').value.trim();
            if (!name) {
                showModal('Thiếu thông tin', 'Vui lòng nhập họ tên.', 'error');
                return;
            }

            currentUser = { ...currentUser, name, phone, address };
            localStorage.setItem('technova_user', JSON.stringify(currentUser));
            registeredUsers = registeredUsers.map(user => user.email === currentUser.email
                ? { ...user, name, phone, address } : user);
            localStorage.setItem('technova_users', JSON.stringify(registeredUsers));
            updateAuthUI();
            showModal('Thành công', 'Đã cập nhật thông tin tài khoản.', 'success');
        }

        // 6. CART LOGIC
        function toggleCart() {
            const sidebar = document.getElementById('cart-sidebar');
            const overlay = document.getElementById('cart-overlay');
            sidebar.classList.toggle('cart-open');
            overlay.classList.toggle('hidden');
        }

        function addToCart(id) {
            if (!currentUser) {
                showModal('Yêu cầu đăng nhập', 'Vui lòng đăng nhập để lưu sản phẩm vào giỏ hàng!', 'error');
                navigate('login');
                return;
            }
            const product = products.find(p => p.id === id);
            if (product.stock <= 0) {
                showModal('Hết hàng', `${product.name} hiện đã hết hàng.`, 'error');
                return;
            }
            const existing = cart.find(i => i.id === id);
            if (existing) { existing.quantity++; } 
            else { cart.push({ ...product, quantity: 1 }); }
            
            localStorage.setItem('technova_cart', JSON.stringify(cart));
            updateCartUI();
            showModal('Thành công', `Đã thêm ${product.name} vào giỏ!`, 'success');
        }

        function updateQuantity(id, delta) {
            const item = cart.find(i => i.id === id);
            if (item) {
                item.quantity += delta;
                if (item.quantity <= 0) cart = cart.filter(i => i.id !== id);
                localStorage.setItem('technova_cart', JSON.stringify(cart));
                updateCartUI();
            }
        }

        function viewFromCart(id) {
            toggleCart();
            navigate('detail', id);
        }

        function updateCartUI() {
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cart-count').innerText = totalItems;
            
            const container = document.getElementById('cart-items');
            let total = 0;
            
            if (cart.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-gray-400 mt-20">
                        <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4"><i class="fa-solid fa-basket-shopping text-3xl"></i></div>
                        <p class="text-lg font-medium text-gray-500">Giỏ hàng trống</p>
                    </div>`;
            } else {
                container.innerHTML = cart.map(item => {
                    total += item.price * item.quantity;
                    return `
                        <div class="flex items-center gap-3 border border-gray-100 p-3 rounded-xl bg-white shadow-sm mb-3">
                            <img src="${item.img}" class="w-16 h-16 object-contain cursor-pointer" onclick="viewFromCart(${item.id})">
                            <div class="flex-grow">
                                <h4 class="text-sm font-semibold text-dark line-clamp-1 cursor-pointer hover:text-primary transition-colors" onclick="viewFromCart(${item.id})">${item.name}</h4>
                                <div class="text-primary text-sm font-bold mb-2">${formatCurrency(item.price)}</div>
                                <div class="flex items-center border border-gray-200 rounded w-max bg-gray-50">
                                    <button onclick="updateQuantity(${item.id}, -1)" class="px-2.5 py-1 text-gray-500 hover:bg-gray-200 rounded-l">-</button>
                                    <span class="px-3 py-1 text-xs font-semibold bg-white border-x border-gray-200 w-8 text-center">${item.quantity}</span>
                                    <button onclick="updateQuantity(${item.id}, 1)" class="px-2.5 py-1 text-gray-500 hover:bg-gray-200 rounded-r">+</button>
                                </div>
                            </div>
                        </div>`;
                }).join('');
            }
            document.getElementById('cart-total').innerText = formatCurrency(total);
        }

        // 7. CHECKOUT LOGIC
        function buyNow(id) {
            if (!currentUser) {
                showModal('Yêu cầu đăng nhập', 'Vui lòng đăng nhập trước khi mua hàng.', 'error');
                navigate('login');
                return;
            }
            const product = products.find(p => p.id === id);
            if (!product || product.stock <= 0) {
                showModal('Hết hàng', `${product ? product.name : 'Sản phẩm'} hiện đã hết hàng.`, 'error');
                return;
            }
            buyNowItem = { ...product, quantity: 1 };
            checkoutType = 'buy_now';
            navigate('checkout');
        }

        function checkoutCart() {
            if (!currentUser) {
                showModal('Yêu cầu đăng nhập', 'Vui lòng đăng nhập để tiến hành thanh toán!', 'error');
                toggleCart();
                navigate('login');
                return;
            }
            if (cart.length === 0) {
                showModal('Lỗi', 'Giỏ hàng đang trống!', 'error');
                return;
            }
            checkoutType = 'cart'; // Chỉ định cờ là thanh toán toàn bộ giỏ
            toggleCart();
            navigate('checkout');
        }

        function renderCheckout() {
            if (!currentUser) {
                showModal('Yêu cầu đăng nhập', 'Vui lòng đăng nhập trước khi thanh toán.', 'error');
                navigate('login');
                return;
            }
            const container = document.getElementById('checkout-items');
            const items = (checkoutType === 'buy_now') ? (buyNowItem ? [buyNowItem] : []) : cart;
            if (!items.length) {
                showModal('Giỏ hàng trống', 'Hãy thêm sản phẩm trước khi thanh toán.', 'error');
                navigate('products');
                return;
            }
            document.getElementById('checkout-name').value = currentUser.name || '';
            document.getElementById('checkout-phone').value = currentUser.phone || '';
            document.getElementById('checkout-address').value = currentUser.address || '';
            let total = 0;

            container.innerHTML = items.map(item => {
                total += item.price * item.quantity;
                return `
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                        <div class="flex items-center">
                            <img src="${escapeHtml(item.img)}" alt="" class="w-12 h-12 object-contain bg-gray-50 rounded border border-gray-100 mr-3">
                            <div><p class="text-sm font-medium line-clamp-1">${escapeHtml(item.name)}</p><p class="text-xs text-gray-500">SL: ${item.quantity}</p></div>
                        </div>
                        <span class="font-semibold text-sm">${formatCurrency(item.price * item.quantity)}</span>
                    </div>`;
            }).join('');

            document.getElementById('checkout-subtotal').innerText = formatCurrency(total);
            document.getElementById('checkout-total').innerText = formatCurrency(total);
        }

        function processOrder() {
            if (!currentUser) {
                showModal('Yêu cầu đăng nhập', 'Vui lòng đăng nhập trước khi đặt hàng.', 'error');
                navigate('login');
                return;
            }

            const form = document.getElementById('checkout-form');
            if (!form.checkValidity()) {
                form.reportValidity();
                showModal('Thiếu thông tin', 'Vui lòng kiểm tra đầy đủ họ tên, số điện thoại và địa chỉ nhận hàng.', 'error');
                return;
            }

            const items = (checkoutType === 'buy_now') ? (buyNowItem ? [buyNowItem] : []) : [...cart];
            if (!items.length) {
                showModal('Giỏ hàng trống', 'Không có sản phẩm để đặt hàng.', 'error');
                return;
            }

            const total = items.reduce((sum, item) => sum + item.price * item.quantity, 0);
            const paymentInput = document.querySelector('input[name="payment"]:checked');
            const order = {
                id: 'TN' + Date.now().toString().slice(-8),
                email: currentUser.email,
                items: items.map(item => ({ id: item.id, name: item.name, img: item.img, price: item.price, quantity: item.quantity })),
                total,
                receiverName: form.elements.receiver_name.value.trim(),
                phone: form.elements.phone.value.trim(),
                address: form.elements.address.value.trim(),
                payment: paymentInput ? paymentInput.value : 'COD',
                status: 'Đang xử lý',
                createdAt: new Date().toISOString()
            };

            orders.push(order);
            localStorage.setItem('technova_orders', JSON.stringify(orders));

            currentUser = { ...currentUser, name: order.receiverName, phone: order.phone, address: order.address };
            localStorage.setItem('technova_user', JSON.stringify(currentUser));
            registeredUsers = registeredUsers.map(user => user.email === currentUser.email
                ? { ...user, name: currentUser.name, phone: currentUser.phone, address: currentUser.address } : user);
            localStorage.setItem('technova_users', JSON.stringify(registeredUsers));

            if (checkoutType === 'cart') {
                cart = [];
                localStorage.setItem('technova_cart', JSON.stringify(cart));
                updateCartUI();
            }
            buyNowItem = null;
            form.reset();
            updateAuthUI();
            showModal('Đặt hàng thành công!', `Đơn hàng #${order.id} đã được lưu. Tổng tiền: ${formatCurrency(total)}. Bạn có thể theo dõi trong Tài khoản của tôi.`, 'success');
            navigate('account');
        }

        // INIT
        window.onload = () => {
            renderBrandFilters();
            renderProducts();
            renderHomeBrands();
            renderBestSellers();
            renderHomeArticles();
            renderBlog();
            updateAuthUI();
            updateCartUI();
        };
    </script>
</body>
</html>