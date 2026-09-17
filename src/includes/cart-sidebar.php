    <div id="cart-sidebar" class="fixed inset-y-0 right-0 w-full sm:w-[400px] bg-white shadow-2xl z-50 transform translate-x-full flex flex-col">
        <div class="flex justify-between items-center p-5 border-b bg-gray-50">
            <h2 class="text-xl font-bold flex items-center text-dark">
                <i class="fa-solid fa-cart-shopping mr-3 text-primary"></i> Giỏ Hàng
            </h2>
            <button onclick="toggleCart()" class="text-gray-400 hover:text-red-500 transition-colors p-2 rounded-full hover:bg-red-50">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <div id="cart-items" class="flex-grow p-5 overflow-y-auto space-y-4 bg-gray-50">
            <!-- Items rendered by JS -->
        </div>
        <div class="border-t p-5 bg-white shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)]">
            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-600 font-medium">Tổng cộng:</span>
                <span id="cart-total" class="text-2xl font-bold text-primary">0 ₫</span>
            </div>
            <button onclick="checkoutCart()" class="w-full bg-primary hover:bg-secondary text-white font-bold py-3.5 rounded-xl transition-colors flex justify-center items-center shadow-lg hover:shadow-xl">
                Tiến Hành Thanh Toán <i class="fa-solid fa-arrow-right ml-2"></i>
            </button>
        </div>
    </div>