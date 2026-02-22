    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="px-4 lg:px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p class="text-xs text-gray-500">
                &copy; <?= date('Y') ?> <span class="font-semibold text-[#6c5ce7]">CeritaKu</span>. All rights reserved.
            </p>
            <div class="flex items-center gap-4 text-xs text-gray-400">
                <span>Admin Panel v1.0</span>
                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                <span>Logged in as <strong class="text-gray-600"><?= esc(session()->get('user_name') ?? 'Admin') ?></strong></span>
                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 bg-green-400 rounded-full inline-block"></span>
                    Online
                </span>
            </div>
        </div>
    </footer>
