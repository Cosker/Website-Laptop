<?php
require_once __DIR__ . '/../../config/db.php';

// LẤY DỮ LIỆU TỪ DATABASE CHO BẢNG SẢN PHẨM
$brands = $pdo->query("SELECT * FROM brands ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

$sqlProducts = "SELECT p.*, b.name as brand_name 
                FROM products p 
                LEFT JOIN brands b ON p.brand_id = b.id 
                ORDER BY p.id DESC";
$products = $pdo->query($sqlProducts)->fetchAll(PDO::FETCH_ASSOC);
?>

<section id="products" class="admin-section">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Quản lý Sản phẩm</h2>
            <p class="text-sm text-gray-500 mt-1">Danh sách tất cả laptop trong cơ sở dữ liệu</p>
        </div>
        <button onclick="openAddProductModal()" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm flex items-center transition-colors">
            <i class="fa-solid fa-plus mr-2"></i> Thêm sản phẩm
        </button>
    </div>

    <!-- Bảng danh sách sản phẩm -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between gap-4">
            <div class="relative max-w-xs w-full">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input id="admin-product-search" type="text" placeholder="Tìm tên, mã SP..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
            </div>
            <select id="admin-brand-filter" class="border border-gray-200 rounded-lg text-sm px-4 py-2 focus:outline-none focus:border-primary">
                <option value="all">Tất cả hãng</option>
                <?php foreach ($brands as $brand): ?>
                    <option value="<?= $brand['id'] ?>"><?= htmlspecialchars($brand['name']) ?></option>
                <?php endforeach; ?>
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
                    <!-- Dữ liệu render bằng JS -->
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
            <span id="admin-product-count"></span>
        </div>
    </div>

    <!-- Modal Thêm Sản Phẩm -->
    <div id="add-product-modal" class="fixed inset-0 bg-black/50 z-[100] hidden flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform" id="add-product-content">
            <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-laptop-medical text-primary mr-2"></i> Thêm Laptop Mới</h3>
                <button onclick="closeAddProductModal()" class="text-gray-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            
            <form action="" method="POST" class="p-6">
                <input type="hidden" name="action" value="add_product">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tên sản phẩm *</label>
                        <input type="text" name="name" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none" placeholder="VD: MacBook Pro 14 M3">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hãng sản xuất *</label>
                            <select name="brand_id" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none bg-white">
                                <option value="">-- Chọn hãng --</option>
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?= $brand['id'] ?>"><?= htmlspecialchars($brand['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Số lượng tồn kho *</label>
                            <input type="number" name="stock" min="0" value="10" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Giá bán (VNĐ) *</label>
                        <input type="number" name="price" min="0" step="1000" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none" placeholder="VD: 25000000">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Đường dẫn ảnh (URL) *</label>
                        <input type="url" name="img" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none" placeholder="https://...">
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <button type="button" onclick="closeAddProductModal()" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">Hủy bỏ</button>
                    <button type="submit" class="px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-primary hover:bg-secondary shadow-md transition-colors flex items-center">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> Lưu Sản Phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Sửa Sản Phẩm -->
    <div id="edit-product-modal" class="fixed inset-0 bg-black/50 z-[100] hidden flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform" id="edit-product-content">
            <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-blue-50/50">
                <h3 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-pen-to-square text-primary mr-2"></i> Chỉnh Sửa Sản Phẩm</h3>
                <button onclick="closeEditProductModal()" class="text-gray-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            
            <form action="" method="POST" class="p-6">
                <input type="hidden" name="action" value="edit_product">
                <input type="hidden" name="id" id="edit_id" value="">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tên sản phẩm *</label>
                        <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hãng sản xuất *</label>
                            <select name="brand_id" id="edit_brand_id" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none bg-white">
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?= $brand['id'] ?>"><?= htmlspecialchars($brand['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Số lượng tồn kho *</label>
                            <input type="number" name="stock" id="edit_stock" min="0" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Giá bán (VNĐ) *</label>
                        <input type="number" name="price" id="edit_price" min="0" step="1000" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Đường dẫn ảnh (URL) *</label>
                        <input type="url" name="img" id="edit_img" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditProductModal()" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">Hủy bỏ</button>
                    <button type="submit" class="px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-primary hover:bg-secondary shadow-md transition-colors flex items-center">
                        <i class="fa-solid fa-check mr-2"></i> Cập Nhật
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Xác Nhận Xóa -->
    <div id="delete-confirm-modal" class="fixed inset-0 bg-black/50 z-[100] hidden flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform" id="delete-confirm-content">
            <div class="p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-red-100 text-red-500 flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Xác nhận xóa?</h3>
                <p class="text-gray-500 text-sm mb-6">Bạn có chắc chắn muốn xóa sản phẩm này không? Hành động này không thể hoàn tác.</p>
                
                <form action="" method="POST" class="flex justify-center space-x-3">
                    <input type="hidden" name="action" value="delete_product">
                    <input type="hidden" name="id" id="delete_id" value="">
                    
                    <button type="button" onclick="closeDeleteConfirmModal()" class="w-1/2 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">Hủy</button>
                    <button type="submit" class="w-1/2 px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-red-500 hover:bg-red-600 transition-colors shadow-md">Xóa Ngay</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    const dbProducts = <?= json_encode($products) ?>;
    
    function formatMoney(amount) {
        return parseInt(amount).toLocaleString('vi-VN') + ' ₫';
    }

    function renderProductTable() {
        const searchInput = document.getElementById('admin-product-search');
        const filterSelect = document.getElementById('admin-brand-filter');
        if(!searchInput || !filterSelect) return;

        const keyword = searchInput.value.toLowerCase().trim();
        const brandId = filterSelect.value;
        const tbody = document.getElementById('admin-product-list');
        
        const filtered = dbProducts.filter(p => {
            const matchKeyword = p.name.toLowerCase().includes(keyword) || ('tn' + p.id.toString().padStart(4, '0')).includes(keyword);
            const matchBrand = (brandId === 'all') || (p.brand_id == brandId);
            return matchKeyword && matchBrand;
        });

        if(filtered.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">Không tìm thấy sản phẩm nào.</td></tr>';
        } else {
            tbody.innerHTML = filtered.map(p => {
                const inStock = p.stock > 0;
                const statusClass = inStock ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                const statusText = inStock ? 'Còn hàng' : 'Hết hàng';
                
                return `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 border border-gray-200 rounded overflow-hidden">
                                    <img class="h-10 w-10 object-contain bg-white" src="${p.img}" alt="${p.name}" onerror="this.src='https://via.placeholder.com/150?text=No+Image'">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">${p.name}</div>
                                    <div class="text-xs text-gray-500">Mã: TN${p.id.toString().padStart(4, '0')} &middot; ${p.brand_name || 'Không rõ'}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-900 font-bold">${formatMoney(p.price)}</div></td>
                        <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-900">${p.stock} chiếc</div></td>
                        <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">${statusText}</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="openEditProductModal(${p.id})" class="text-indigo-600 hover:text-indigo-900 mr-3 transition-colors"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button onclick="openDeleteConfirmModal(${p.id})" class="text-red-600 hover:text-red-900 transition-colors"><i class="fa-solid fa-trash-can"></i></button>
                        </td>
                    </tr>
                `;
            }).join('');
        }
        
        const countEl = document.getElementById('admin-product-count');
        if(countEl) countEl.innerText = `Hiển thị ${filtered.length} / ${dbProducts.length} sản phẩm`;
    }

    const searchInputEl = document.getElementById('admin-product-search');
    const brandFilterEl = document.getElementById('admin-brand-filter');
    if(searchInputEl) searchInputEl.addEventListener('input', renderProductTable);
    if(brandFilterEl) brandFilterEl.addEventListener('change', renderProductTable);
    
    renderProductTable();

    function openAddProductModal() {
        const modal = document.getElementById('add-product-modal');
        const content = document.getElementById('add-product-content');
        modal.classList.remove('hidden');
        setTimeout(() => { content.classList.remove('scale-95'); content.classList.add('scale-100'); }, 10);
    }
    
    function closeAddProductModal() {
        const modal = document.getElementById('add-product-modal');
        const content = document.getElementById('add-product-content');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    function openEditProductModal(id) {
        const product = dbProducts.find(p => p.id == id);
        if (!product) return;

        document.getElementById('edit_id').value = product.id;
        document.getElementById('edit_name').value = product.name;
        document.getElementById('edit_brand_id').value = product.brand_id;
        document.getElementById('edit_stock').value = product.stock;
        document.getElementById('edit_price').value = product.price;
        document.getElementById('edit_img').value = product.img;

        const modal = document.getElementById('edit-product-modal');
        const content = document.getElementById('edit-product-content');
        modal.classList.remove('hidden');
        setTimeout(() => { content.classList.remove('scale-95'); content.classList.add('scale-100'); }, 10);
    }
    
    function closeEditProductModal() {
        const modal = document.getElementById('edit-product-modal');
        const content = document.getElementById('edit-product-content');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    function openDeleteConfirmModal(id) {
        document.getElementById('delete_id').value = id;
        const modal = document.getElementById('delete-confirm-modal');
        const content = document.getElementById('delete-confirm-content');
        modal.classList.remove('hidden');
        setTimeout(() => { content.classList.remove('scale-95'); content.classList.add('scale-100'); }, 10);
    }

    function closeDeleteConfirmModal() {
        const modal = document.getElementById('delete-confirm-modal');
        const content = document.getElementById('delete-confirm-content');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }
</script>