<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Panel') ?> – CeritaKu Admin</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary:  { DEFAULT: '#6c5ce7', dark: '#5a4bd1' },
                        sidebar:  { DEFAULT: '#1e1b4b', light: '#2d2a5e', hover: '#3730a3' },
                    }
                }
            }
        }
    </script>

    <!-- Google Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Sidebar scrollbar */
        #admin-sidebar::-webkit-scrollbar { width: 4px; }
        #admin-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }

        /* Active nav link */
        .nav-link.active {
            background: rgba(109, 92, 231, 0.3);
            border-left: 3px solid #6c5ce7;
        }
        .nav-link { border-left: 3px solid transparent; }

        /* Sidebar collapse transition */
        #admin-sidebar { transition: width 0.3s ease; }

        /* Mobile overlay */
        #sidebar-overlay { display: none; }
        @media (max-width: 1023px) {
            #admin-sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            #admin-sidebar.open { transform: translateX(0); }
            #sidebar-overlay.show { display: block; }
        }

        /* Content area */
        #main-content { transition: margin-left 0.3s ease; }
    </style>
</head>
<body class="h-full bg-gray-100">

<!-- Mobile Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 lg:hidden" onclick="closeSidebar()"></div>

<!-- ===================== SIDEBAR ===================== -->
<aside id="admin-sidebar"
    class="fixed top-0 left-0 h-full w-64 bg-[#1e1b4b] text-white z-40 flex flex-col overflow-y-auto">

    <!-- Brand -->
    <div class="flex items-center gap-1 px-2 py-7 border-b border-white/10 flex-shrink-0">
        <div class="w-full flex justify-center">
            <img src="<?= base_url('assets/images/logo.png') ?>" alt="CeritaKu Logo" class="h-24 object-contain flex-shrink-0" />
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1">
        <p class="px-3 mb-2 text-[10px] font-semibold text-indigo-400 uppercase tracking-widest">Main Menu</p>

        <a href="<?= base_url('/admin/dashboard') ?>"
            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10 hover:text-white transition-all group <?= (uri_string() === 'admin/dashboard') ? 'active' : '' ?>">
            <span class="material-symbols-outlined text-xl group-hover:text-primary">dashboard</span>
            Dashboard
        </a>

        <p class="px-3 mt-4 mb-2 text-[10px] font-semibold text-indigo-400 uppercase tracking-widest">Konten</p>

        <a href="<?= base_url('/admin/stories') ?>"
            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10 hover:text-white transition-all group <?= str_contains(uri_string(), 'admin/stories') ? 'active' : '' ?>">
            <span class="material-symbols-outlined text-xl group-hover:text-primary">menu_book</span>
            Story Management
        </a>

        <a href="<?= base_url('/admin/chapters') ?>"
            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10 hover:text-white transition-all group <?= str_contains(uri_string(), 'admin/chapters') ? 'active' : '' ?>">
            <span class="material-symbols-outlined text-xl group-hover:text-primary">article</span>
            Chapter Management
        </a>

        <a href="<?= base_url('/admin/reviews') ?>"
            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10 hover:text-white transition-all group <?= str_contains(uri_string(), 'admin/reviews') ? 'active' : '' ?>">
            <span class="material-symbols-outlined text-xl group-hover:text-primary">rate_review</span>
            Review Management
        </a>

        <p class="px-3 mt-4 mb-2 text-[10px] font-semibold text-indigo-400 uppercase tracking-widest">Pengguna</p>

        <a href="<?= base_url('/admin/users') ?>"
            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10 hover:text-white transition-all group <?= str_contains(uri_string(), 'admin/users') ? 'active' : '' ?>">
            <span class="material-symbols-outlined text-xl group-hover:text-primary">group</span>
            User Management
        </a>

        <p class="px-3 mt-4 mb-2 text-[10px] font-semibold text-indigo-400 uppercase tracking-widest">Sistem</p>

        <a href="<?= base_url('/') ?>" target="_blank"
            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10 hover:text-white transition-all group">
            <span class="material-symbols-outlined text-xl group-hover:text-primary">open_in_new</span>
            Lihat Website
        </a>
    </nav>

    <!-- Logout -->
    <div class="px-3 py-4 border-t border-white/10 flex-shrink-0">
        <form action="<?= base_url('/auth/logout') ?>" method="POST">
            <?= csrf_field() ?>
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-300 hover:bg-red-500/20 hover:text-red-200 transition-all group">
                <span class="material-symbols-outlined text-xl">logout</span>
                Logout
            </button>
        </form>
    </div>
</aside>

<!-- ===================== MAIN WRAPPER ===================== -->
<div class="lg:ml-64 flex flex-col min-h-screen">

    <!-- Top Bar -->
    <header class="sticky top-0 z-20 bg-white border-b border-gray-200 shadow-sm">
        <div class="flex items-center justify-between px-4 lg:px-6 h-16">
            <!-- Mobile menu toggle -->
            <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                <span class="material-symbols-outlined">menu</span>
            </button>

            <!-- Page title -->
            <h2 class="font-semibold text-gray-800 text-lg"><?= esc($title ?? 'Dashboard') ?></h2>

            <!-- Right actions -->
            <div class="flex items-center gap-3">
                <!-- Notifikasi -->
                <button class="relative p-2 rounded-lg hover:bg-gray-100 transition">
                    <span class="material-symbols-outlined text-gray-600">notifications</span>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                <!-- User dropdown -->
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center">
                        <span class="text-white text-xs font-bold">
                            <?= strtoupper(substr(session()->get('user_name') ?? 'A', 0, 1)) ?>
                        </span>
                    </div>
                    <span class="hidden sm:block text-sm font-medium text-gray-700">
                        <?= esc(session()->get('user_name') ?? 'Admin') ?>
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="flex-1 p-4 lg:p-6">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-2 text-green-700">
                <span class="material-symbols-outlined">check_circle</span>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center gap-2 text-red-700">
                <span class="material-symbols-outlined">error</span>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>

    <!-- ===================== FOOTER ===================== -->
    <?= $this->include('layouts/admin/footer') ?>
</div>

<script>
    function toggleSidebar() {
        const sidebar  = document.getElementById('admin-sidebar');
        const overlay  = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    }

    function closeSidebar() {
        document.getElementById('admin-sidebar').classList.remove('open');
        document.getElementById('sidebar-overlay').classList.remove('show');
    }
</script>
</body>
</html>
