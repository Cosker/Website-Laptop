    <div id="custom-modal" class="fixed inset-0 z-[60] hidden flex items-center justify-center">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl p-6 w-11/12 max-w-sm transform scale-95 opacity-0 transition-all duration-300" id="modal-content">
            <div class="flex flex-col items-center text-center">
                <div id="modal-icon" class="w-16 h-16 rounded-full flex items-center justify-center text-3xl mb-4"></div>
                <h3 id="modal-title" class="text-xl font-bold text-gray-900 mb-2">Title</h3>
                <p id="modal-message" class="text-gray-600 text-sm mb-6">Message</p>
                <button onclick="closeModal()" class="w-full bg-gray-900 hover:bg-black text-white font-medium py-2.5 rounded-xl transition-colors">Đồng ý</button>
            </div>
        </div>
    </div>