<!-- ═══════════════════════ TOP BAR ═══════════════════════ -->
<header id="topbar" class="sticky top-0 z-20 shadow-sm">
    <div class="flex items-center justify-between px-4 lg:px-6 h-16">

        <!-- Mobile toggle -->
        <button onclick="toggleSidebar()"
            class="lg:hidden p-2 rounded-xl hover:bg-gray-100 transition text-gray-600">
            <span class="material-symbols-rounded">menu</span>
        </button>

        <!-- Breadcrumb / title -->
        <div class="flex items-center gap-2">
            <span class="text-xs text-gray-400 hidden sm:block">Admin</span>
            <span class="text-xs text-gray-300 hidden sm:block">/</span>
            <h2 class="font-bold text-gray-800 text-base"><?= esc($title ?? 'Dashboard') ?></h2>
        </div>

        <!-- Right actions -->
        <div class="flex items-center gap-2">
            <!-- Notification -->
            <button class="relative p-2.5 rounded-xl hover:bg-gray-100 transition text-gray-500 hover:text-gray-700">
                <span class="material-symbols-rounded text-xl">notifications</span>
                <span class="absolute top-2 right-2 w-1.5 h-1.5 bg-red-500 rounded-full ring-2 ring-white"></span>
            </button>

            <!-- Divider -->
            <div class="w-px h-6 bg-gray-200 mx-1"></div>

            <!-- User avatar -->
            <div class="flex items-center gap-2.5 pl-1">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-white font-bold text-xs shadow-md"
                    style="background: linear-gradient(135deg,#6c5ce7,#a855f7)">
                    <?= strtoupper(substr(session()->get('user_name') ?? 'A', 0, 1)) ?>
                </div>
                <div class="hidden sm:block">
                    <p class="text-sm font-semibold text-gray-800 leading-none">
                        <?= esc(session()->get('user_name') ?? 'Admin') ?>
                    </p>
                    <p class="text-[11px] text-violet-500 font-medium mt-0.5">Administrator</p>
                </div>
            </div>
        </div>
    </div>
</header>