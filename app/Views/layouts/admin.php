<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) . ' — CeritaKu Admin' : 'CeritaKu Admin' ?></title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6c5ce7',
                        'primary-dark': '#5a4bd1',
                        sidebar: '#1e1b2e',
                        'sidebar-hover': '#2d2a3e',
                    }
                }
            }
        }
    </script>

    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Sidebar transition */
        #sidebar { transition: width 0.3s ease, transform 0.3s ease; }

        /* Active sidebar link */
        .sidebar-link.active {
            background-color: rgba(108, 92, 231, 0.2);
            color: #a29bfe;
            border-left: 3px solid #6c5ce7;
        }
        .sidebar-link:not(.active):hover {
            background-color: rgba(255,255,255,0.05);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #6c5ce7; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #5a4bd1; }

        /* Table row hover */
        tbody tr { transition: background-color 0.15s ease; }
        tbody tr:hover { background-color: #f8f7ff; }

        /* Badge pulse */
        .badge-pulse { animation: pulse 2s infinite; }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
    </style>
</head>
<body class="bg-gray-50 flex min-h-screen">

    <!-- ==================== SIDEBAR ==================== -->
    <?php include APPPATH . 'Views/layouts/admin/sidebar.php'; ?>

    <!-- ==================== MAIN CONTENT ==================== -->
    <div class="flex-1 flex flex-col min-h-screen overflow-hidden" id="mainContent">

        <!-- Top Bar -->
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between sticky top-0 z-40 shadow-sm">
            <div class="flex items-center gap-4">
                <!-- Mobile toggle -->
                <button id="sidebarToggle" class="lg:hidden text-gray-500 hover:text-gray-700 p-1 rounded-lg hover:bg-gray-100 transition">
                    <span class="material-symbols-outlined text-2xl">menu</span>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-gray-900"><?= isset($title) ? esc($title) : 'Dashboard' ?></h1>
                    <p class="text-xs text-gray-400 hidden sm:block">CeritaKu Admin Panel</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- Notification bell (placeholder) -->
                <button class="relative p-2 rounded-full hover:bg-gray-100 transition text-gray-500">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full badge-pulse"></span>
                </button>

                <!-- User avatar -->
                <div class="flex items-center gap-2 pl-3 border-l border-gray-200">
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white font-bold text-sm">
                        <?= strtoupper(substr(session()->get('user_name') ?? 'A', 0, 1)) ?>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold text-gray-800"><?= esc(session()->get('user_name') ?? 'Admin') ?></p>
                        <p class="text-xs text-gray-400">Administrator</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-6 overflow-auto">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    <span class="material-symbols-outlined text-green-500">check_circle</span>
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <span class="material-symbols-outlined text-red-500">error</span>
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>

        <!-- ==================== FOOTER ==================== -->
        <?php include APPPATH . 'Views/layouts/admin/footer.php'; ?>
    </div>

    <!-- Mobile sidebar overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden" onclick="closeSidebar()"></div>

    <script>
        // Mobile sidebar toggle
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        document.getElementById('sidebarToggle')?.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        // Set active sidebar link
        const currentPath = window.location.pathname;
        document.querySelectorAll('.sidebar-link').forEach(link => {
            const href = link.getAttribute('href');
            if (href && currentPath.includes(href.replace(/\/+$/, ''))) {
                link.classList.add('active');
            }
        });
    </script>
</body>
</html>
