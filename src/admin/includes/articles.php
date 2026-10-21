<?php
require_once __DIR__ . '/../../config/db.php';

// LẤY DỮ LIỆU TỪ DATABASE CHO BẢNG BÀI VIẾT
$articles = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC, id DESC")->fetchAll(PDO::FETCH_ASSOC);
$articleCategories = $pdo->query("SELECT DISTINCT category FROM articles ORDER BY category ASC")->fetchAll(PDO::FETCH_COLUMN);
?>

<section id="articles" class="admin-section">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Quản lý Bài viết</h2>
            <p class="text-sm text-gray-500 mt-1">Tin tức, tư vấn và mẹo hay hiển thị ở trang khách</p>
        </div>
        <button onclick="openArticleModal()" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm flex items-center transition-colors">
            <i class="fa-solid fa-plus mr-2"></i> Thêm bài viết
        </button>
    </div>

    <!-- Bảng danh sách bài viết -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between gap-4">
            <div class="relative max-w-xs w-full">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input id="admin-article-search" type="text" placeholder="Tìm tiêu đề bài viết..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
            </div>
            <select id="admin-article-category-filter" class="border border-gray-200 rounded-lg text-sm px-4 py-2 focus:outline-none focus:border-primary">
                <option value="all">Tất cả chuyên mục</option>
                <?php foreach ($articleCategories as $category): ?>
                    <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bài viết</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Chuyên mục</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tác giả</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày đăng</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white" id="admin-article-list">
                    <!-- Dữ liệu render bằng JS -->
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
            <span id="admin-article-count"></span>
        </div>
    </div>

    <!-- Modal Thêm / Sửa Bài Viết (dùng chung) -->
    <div id="article-modal" class="fixed inset-0 bg-black/50 z-[100] hidden flex items-center justify-center backdrop-blur-sm transition-opacity p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-full flex flex-col overflow-hidden transform scale-95 transition-transform" id="article-modal-content">
            <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800" id="article-modal-title"></h3>
                <button onclick="closeArticleModal()" class="text-gray-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form action="" method="POST" class="p-6 overflow-y-auto">
                <input type="hidden" name="action" id="article_action" value="add_article">
                <input type="hidden" name="id" id="article_id" value="">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tiêu đề *</label>
                        <input type="text" name="title" id="article_title" required maxlength="255" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none" placeholder="VD: Cách chọn laptop cho sinh viên">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Chuyên mục *</label>
                            <input type="text" name="category" id="article_category" required maxlength="50" list="article-category-options" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none" placeholder="VD: Tư vấn">
                            <datalist id="article-category-options">
                                <?php foreach ($articleCategories as $category): ?>
                                    <option value="<?= htmlspecialchars($category) ?>">
                                <?php endforeach; ?>
                            </datalist>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tác giả *</label>
                            <input type="text" name="author" id="article_author" required maxlength="100" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ngày đăng *</label>
                            <input type="date" name="created_at" id="article_created_at" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh bìa (URL)</label>
                        <input type="url" name="img" id="article_img" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none" placeholder="https://...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tóm tắt *</label>
                        <textarea name="excerpt" id="article_excerpt" required rows="2" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none" placeholder="1-2 câu giới thiệu hiển thị trên thẻ bài viết"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nội dung *</label>
                        <textarea name="content" id="article_content" required rows="8" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none" placeholder="Mỗi dòng là một đoạn văn"></textarea>
                        <p class="text-xs text-gray-400 mt-1">Xuống dòng để tách đoạn văn.</p>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <button type="button" onclick="closeArticleModal()" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">Hủy bỏ</button>
                    <button type="submit" class="px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-primary hover:bg-secondary shadow-md transition-colors flex items-center">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> Lưu Bài Viết
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Xác Nhận Xóa Bài Viết -->
    <div id="delete-article-modal" class="fixed inset-0 bg-black/50 z-[100] hidden flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform" id="delete-article-content">
            <div class="p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-red-100 text-red-500 flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Xóa bài viết?</h3>
                <p class="text-gray-500 text-sm mb-6">Bài viết <b id="delete-article-title"></b> sẽ bị xóa khỏi trang khách. Hành động này không thể hoàn tác.</p>

                <form action="" method="POST" class="flex justify-center space-x-3">
                    <input type="hidden" name="action" value="delete_article">
                    <input type="hidden" name="id" id="delete_article_id" value="">

                    <button type="button" onclick="closeDeleteArticleModal()" class="w-1/2 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">Hủy</button>
                    <button type="submit" class="w-1/2 px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-red-500 hover:bg-red-600 transition-colors shadow-md">Xóa Ngay</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    const dbArticles = <?= json_encode($articles, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

    function escapeArticleHtml(str) {
        return String(str ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    }

    function formatArticleDate(dateStr) {
        const [y, m, d] = dateStr.split('-');
        return `${d}/${m}/${y}`;
    }

    function renderArticleTable() {
        const searchInput = document.getElementById('admin-article-search');
        const filterSelect = document.getElementById('admin-article-category-filter');
        if (!searchInput || !filterSelect) return;

        const keyword = searchInput.value.toLowerCase().trim();
        const category = filterSelect.value;
        const tbody = document.getElementById('admin-article-list');

        const filtered = dbArticles.filter(a => {
            const matchKeyword = a.title.toLowerCase().includes(keyword);
            const matchCategory = (category === 'all') || (a.category === category);
            return matchKeyword && matchCategory;
        });

        if (filtered.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">Không tìm thấy bài viết nào.</td></tr>';
        } else {
            tbody.innerHTML = filtered.map(a => `
                <tr>
                    <td class="px-6 py-4">
                        <div class="flex items-center min-w-[280px]">
                            <div class="flex-shrink-0 h-12 w-16 border border-gray-200 rounded overflow-hidden bg-gray-50">
                                <img class="h-12 w-16 object-cover" src="${escapeArticleHtml(a.img || '../assets/placeholder-laptop.svg')}" alt="" onerror="this.src='../assets/placeholder-laptop.svg'">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 line-clamp-1">${escapeArticleHtml(a.title)}</div>
                                <div class="text-xs text-gray-500 line-clamp-1">${escapeArticleHtml(a.excerpt)}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">${escapeArticleHtml(a.category)}</span></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${escapeArticleHtml(a.author)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${formatArticleDate(a.created_at)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button onclick="openArticleModal(${a.id})" class="text-indigo-600 hover:text-indigo-900 mr-3 transition-colors"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button onclick="openDeleteArticleModal(${a.id})" class="text-red-600 hover:text-red-900 transition-colors"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>
            `).join('');
        }

        const countEl = document.getElementById('admin-article-count');
        if (countEl) countEl.innerText = `Hiển thị ${filtered.length} / ${dbArticles.length} bài viết`;
    }

    const articleSearchEl = document.getElementById('admin-article-search');
    const articleCategoryEl = document.getElementById('admin-article-category-filter');
    if (articleSearchEl) articleSearchEl.addEventListener('input', renderArticleTable);
    if (articleCategoryEl) articleCategoryEl.addEventListener('change', renderArticleTable);

    renderArticleTable();

    // Không truyền id => thêm mới, có id => sửa bài viết đó
    function openArticleModal(id) {
        const article = id ? dbArticles.find(a => a.id == id) : null;
        if (id && !article) return;

        document.getElementById('article-modal-title').innerHTML = article
            ? '<i class="fa-solid fa-pen-to-square text-primary mr-2"></i> Chỉnh Sửa Bài Viết'
            : '<i class="fa-solid fa-newspaper text-primary mr-2"></i> Thêm Bài Viết Mới';
        document.getElementById('article_action').value = article ? 'edit_article' : 'add_article';
        document.getElementById('article_id').value = article ? article.id : '';
        document.getElementById('article_title').value = article ? article.title : '';
        document.getElementById('article_category').value = article ? article.category : '';
        document.getElementById('article_author').value = article ? article.author : 'Ban biên tập TechNova';
        document.getElementById('article_created_at').value = article ? article.created_at : new Date().toLocaleDateString('en-CA');
        document.getElementById('article_img').value = article ? (article.img || '') : '';
        document.getElementById('article_excerpt').value = article ? article.excerpt : '';
        document.getElementById('article_content').value = article ? article.content : '';

        const modal = document.getElementById('article-modal');
        const content = document.getElementById('article-modal-content');
        modal.classList.remove('hidden');
        setTimeout(() => { content.classList.remove('scale-95'); content.classList.add('scale-100'); }, 10);
    }

    function closeArticleModal() {
        const modal = document.getElementById('article-modal');
        const content = document.getElementById('article-modal-content');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    function openDeleteArticleModal(id) {
        const article = dbArticles.find(a => a.id == id);
        if (!article) return;
        document.getElementById('delete_article_id').value = id;
        document.getElementById('delete-article-title').innerText = article.title;
        const modal = document.getElementById('delete-article-modal');
        const content = document.getElementById('delete-article-content');
        modal.classList.remove('hidden');
        setTimeout(() => { content.classList.remove('scale-95'); content.classList.add('scale-100'); }, 10);
    }

    function closeDeleteArticleModal() {
        const modal = document.getElementById('delete-article-modal');
        const content = document.getElementById('delete-article-content');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }
</script>
