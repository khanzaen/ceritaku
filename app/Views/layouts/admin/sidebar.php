<!-- ═══════════════════════ SIDEBAR ═══════════════════════ -->
<aside id="admin-sidebar" class="fixed top-0 left-0 h-full w-64 text-gray-700 z-40 flex flex-col overflow-y-auto">

    <!-- Brand -->
    <div class="brand-section flex items-center justify-center px-6 py-6 flex-shrink-0">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="CeritaKu" class="h-14 object-contain"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
        <!-- Fallback wordmark -->
        <div style="display:none" class="items-center gap-2">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-500 to-purple-700 flex items-center justify-center shadow-lg shadow-purple-900/40">
                <span class="material-symbols-rounded text-white text-base"
                    style="font-variation-settings:'FILL' 1">auto_stories</span>
            </div>
            <span class="text-base font-extrabold tracking-tight text-gray-800">Cerita<span
                    class="text-violet-400">Ku</span></span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 pb-4">

        <p class="nav-section-label">Main Menu</p>

        <a href="<?= base_url('/admin/dashboard') ?>"
            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 mb-0.5
                <?= (uri_string() === 'admin/dashboard') ? 'active' : '' ?>">
            <span class="nav-icon material-symbols-rounded text-xl text-violet-400"
                style="font-variation-settings:'FILL' 1">dashboard</span>
            Dashboard
        </a>

        <p class="nav-section-label">Content</p>

        <a href="<?= base_url('/admin/stories') ?>"
            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 mb-0.5
                <?= str_contains(uri_string(), 'admin/stories') ? 'active' : '' ?>">
            <span class="nav-icon material-symbols-rounded text-xl text-violet-400"
                style="font-variation-settings:'FILL' 1">menu_book</span>
            Story Management
        </a>

        <a href="<?= base_url('/admin/chapters') ?>"
            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 mb-0.5
                <?= str_contains(uri_string(), 'admin/chapters') ? 'active' : '' ?>">
            <span class="nav-icon material-symbols-rounded text-xl text-violet-400"
                style="font-variation-settings:'FILL' 1">article</span>
            Chapter Management
        </a>

        <a href="<?= base_url('/admin/reviews') ?>"
            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 mb-0.5
                <?= str_contains(uri_string(), 'admin/reviews') ? 'active' : '' ?>">
            <span class="nav-icon material-symbols-rounded text-xl text-violet-400"
                style="font-variation-settings:'FILL' 1">rate_review</span>
            Review Management
        </a>

        <a href="<?= base_url('/admin/reports') ?>"
            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 mb-0.5
                <?= str_contains(uri_string(), 'admin/reports') ? 'active' : '' ?>">
            <span class="nav-icon material-symbols-rounded text-xl text-violet-400"
                style="font-variation-settings:'FILL' 1">report</span>
            Report Story Management
        </a>

        <a href="<?= base_url('/admin/comments') ?>"
            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 mb-0.5
                <?= str_contains(uri_string(), 'admin/comments') ? 'active' : '' ?>">
            <span class="nav-icon material-symbols-rounded text-xl text-violet-400"
                style="font-variation-settings:'FILL' 1">comment</span>
            Comment Management
        </a>

        <a href="<?= base_url('/admin/library') ?>"
            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 mb-0.5
                <?= str_contains(uri_string(), 'admin/library') ? 'active' : '' ?>">
            <span class="nav-icon material-symbols-rounded text-xl text-violet-400"
                style="font-variation-settings:'FILL' 1">library_books</span>
            Library Management
        </a>

        <p class="nav-section-label">User</p>

        <a href="<?= base_url('/admin/users') ?>"
            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 mb-0.5
                <?= str_contains(uri_string(), 'admin/users') ? 'active' : '' ?>">
            <span class="nav-icon material-symbols-rounded text-xl text-violet-400"
                style="font-variation-settings:'FILL' 1">group</span>
            User Management
        </a>

        <p class="nav-section-label">Sistem</p>

        <a href="<?= base_url('/') ?>" target="_blank"
            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 mb-0.5">
            <span class="nav-icon material-symbols-rounded text-xl text-violet-400">open_in_new</span>
            See Website
        </a>
    </nav>

    <!-- User card + Logout -->
    <div class="px-3 py-4 flex-shrink-0" style="border-top: 1px solid #ede9fe">
        <!-- User info mini card -->
        <div class="flex items-center gap-3 px-3 py-2.5 mb-2 rounded-xl" style="background: #f5f3ff">
            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs flex-shrink-0 text-white"
                style="background: linear-gradient(135deg,#6c5ce7,#a855f7)">
                <?= strtoupper(substr(session()->get('user_name') ?? 'A', 0, 1)) ?>
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-gray-800 truncate">
                    <?= esc(session()->get('user_name') ?? 'Admin') ?>
                </p>
                <p class="text-[11px] text-violet-500 font-medium">Administrator</p>
            </div>
        </div>

        <!-- Logout -->
        <form action="<?= base_url('/auth/logout') ?>" method="POST">
            <?= csrf_field() ?>
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-red-500 hover:text-red-600 transition-all"
                style="border: 1px solid #fecaca; background: #fff5f5;"
                onmouseover="this.style.background='#fee2e2'"
                onmouseout="this.style.background='#fff5f5'">
                <span class="material-symbols-rounded text-xl">logout</span>
                Logout
            </button>
        </form>
    </div>
</aside>