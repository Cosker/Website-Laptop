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
        // 1. DATA MOCKUP
        const mockProducts = [
            { id: 1, name: "MacBook Pro 14 M2 Pro", price: 45990000, img: "https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=500&q=80", spec: "Apple M2 Pro / 16GB RAM / 512GB SSD", desc: "Sức mạnh siêu việt từ chip M2 Pro giúp bạn xử lý mượt mà mọi tác vụ đồ hoạ nặng nhất." },
            { id: 2, name: "Dell XPS 13 Plus 9320", price: 39500000, img: "https://images.unsplash.com/photo-1593642702821-c823b2816291?auto=format&fit=crop&w=500&q=80", spec: "Core i7-1280P / 16GB / 512GB", desc: "Thiết kế đến từ tương lai với thanh Touchbar ẩn và Trackpad vô cực tuyệt đẹp." },
            { id: 3, name: "Asus ROG Strix G15", price: 28990000, img: "https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&w=500&q=80", spec: "Ryzen 7 6800H / RTX 3060 / 16GB", desc: "Cỗ máy chiến game hoàn hảo với hệ thống tản nhiệt thông minh và màn hình 165Hz." },
            { id: 4, name: "Lenovo ThinkPad X1 Carbon", price: 42000000, img: "https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=500&q=80", spec: "Core i7-1260P / 16GB / 1TB", desc: "Đẳng cấp doanh nhân với độ bền đạt chuẩn quân đội, bàn phím gõ tốt nhất thế giới." }
        ];

        // 2. STATE VARIABLES
        let cart = JSON.parse(localStorage.getItem('technova_cart')) || [];
        let currentUser = JSON.parse(localStorage.getItem('technova_user')) || null;
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
        }

        function renderProducts() {
            const container = document.getElementById('product-grid');
            container.innerHTML = mockProducts.map(p => `
                <div class="product-card bg-white rounded-xl border border-gray-100 overflow-hidden flex flex-col h-full cursor-pointer" onclick="navigate('detail', ${p.id})">
                    <div class="block h-48 overflow-hidden bg-white p-6 border-b border-gray-50 flex items-center justify-center group">
                        <img src="${p.img}" alt="${p.name}" class="object-contain h-full w-full group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <h3 class="font-bold text-base text-dark mb-1 line-clamp-2 hover:text-primary transition-colors">${p.name}</h3>
                        <p class="text-xs text-gray-500 mb-4 flex-grow line-clamp-2">${p.spec}</p>
                        <div class="flex items-center justify-between mt-auto">
                            <span class="text-primary font-bold text-lg">${formatCurrency(p.price)}</span>
                            <button onclick="event.stopPropagation(); addToCart(${p.id})" class="bg-gray-50 hover:bg-primary hover:text-white text-gray-700 w-10 h-10 rounded-full flex items-center justify-center transition-colors border border-gray-200 hover:border-primary">
                                <i class="fa-solid fa-cart-plus text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function renderProductDetail(id) {
            const p = mockProducts.find(item => item.id === id);
            if(!p) return;
            document.getElementById('detail-img').src = p.img;
            document.getElementById('detail-name').innerText = p.name;
            document.getElementById('detail-price').innerText = formatCurrency(p.price);
            document.getElementById('detail-spec').innerText = p.spec;
            document.getElementById('detail-desc').innerText = p.desc;
            
            document.getElementById('btn-add-cart').onclick = () => addToCart(p.id);
            document.getElementById('btn-buy-now').onclick = () => buyNow(p.id);
        }

        // 5. AUTHENTICATION
        function updateAuthUI() {
            const authButtons = document.getElementById('auth-buttons');
            if (currentUser) {
                authButtons.innerHTML = `
                    <span class="text-sm font-medium text-gray-600 bg-gray-100 px-3 py-1.5 rounded-full"><i class="fa-regular fa-user mr-1"></i> Chào, ${currentUser.name}</span>
                    <button onclick="handleLogout()" class="text-sm text-red-500 hover:underline ml-2 font-medium">Đăng xuất</button>
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
            currentUser = { name: "Khách hàng" };
            localStorage.setItem('technova_user', JSON.stringify(currentUser));
            updateAuthUI();
            showModal('Thành công', 'Đăng nhập thành công!', 'success');
            navigate('home');
        }

        function handleRegister(e) {
            e.preventDefault();
            showModal('Thành công', 'Đăng ký tài khoản thành công! Vui lòng đăng nhập.', 'success');
            navigate('login');
        }

        function handleLogout() {
            currentUser = null;
            localStorage.removeItem('technova_user');
            updateAuthUI();
            showModal('Thông báo', 'Bạn đã đăng xuất khỏi hệ thống.', 'info');
            navigate('home');
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
            const product = mockProducts.find(p => p.id === id);
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
            const product = mockProducts.find(p => p.id === id);
            buyNowItem = { ...product, quantity: 1 };
            checkoutType = 'buy_now'; // Chỉ định cờ là mua ngay 1 sản phẩm
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
            const container = document.getElementById('checkout-items');
            // Quyết định nguồn dữ liệu thanh toán dựa vào cờ checkoutType
            const items = (checkoutType === 'buy_now') ? [buyNowItem] : cart;
            let total = 0;
            
            container.innerHTML = items.map(item => {
                total += item.price * item.quantity;
                return `
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                        <div class="flex items-center">
                            <img src="${item.img}" class="w-12 h-12 object-contain bg-gray-50 rounded border border-gray-100 mr-3">
                            <div><p class="text-sm font-medium line-clamp-1">${item.name}</p><p class="text-xs text-gray-500">SL: ${item.quantity}</p></div>
                        </div>
                        <span class="font-semibold text-sm">${formatCurrency(item.price * item.quantity)}</span>
                    </div>`;
            }).join('');
            
            document.getElementById('checkout-subtotal').innerText = formatCurrency(total);
            document.getElementById('checkout-total').innerText = formatCurrency(total);
        }

        function processOrder() {
            const form = document.getElementById('checkout-form');
            if(!form.checkValidity()) {
                showModal('Thiếu thông tin', 'Vui lòng điền đầy đủ thông tin nhận hàng!', 'error');
                return;
            }
            // Thành công
            showModal('Đặt hàng thành công!', 'Cảm ơn bạn đã mua sắm. Đơn hàng sẽ sớm được giao.', 'success');
            
            // Nếu là thanh toán giỏ hàng thì xoá giỏ hàng sau khi đặt xong
            if (checkoutType === 'cart') {
                cart = [];
                localStorage.setItem('technova_cart', JSON.stringify(cart));
                updateCartUI();
            }
            
            form.reset();
            navigate('home');
        }

        // INIT
        window.onload = () => {
            renderProducts();
            updateAuthUI();
            updateCartUI();
        };
    </script>
</body>
</html>