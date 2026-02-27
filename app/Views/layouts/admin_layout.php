<!DOCTYPE html>
<html lang="id" class="h-full">

<?= $this->include('layouts/admin/header') ?>

<body class="h-full bg-[#f5f4f9]">

    <!-- Mobile Overlay -->
    <div id="sidebar-overlay"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 lg:hidden hidden transition-opacity"
        onclick="closeSidebar()"></div>

    <?= $this->include('layouts/admin/sidebar') ?>

    <!-- ═══════════════════════ MAIN WRAPPER ═══════════════════════ -->
    <div class="lg:ml-64 flex flex-col min-h-screen">

        <?= $this->include('layouts/admin/topbar') ?>

        <!-- Page Content -->
        <main class="flex-1 p-4 lg:p-6">

            <?php if (session()->getFlashdata('success')): ?>
                <div class="flash-success mb-5 px-4 py-3 rounded-xl border flex items-center gap-2.5 text-green-700 text-sm font-medium">
                    <span class="material-symbols-rounded text-green-500"
                        style="font-variation-settings:'FILL' 1">check_circle</span>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="flash-error mb-5 px-4 py-3 rounded-xl border flex items-center gap-2.5 text-red-600 text-sm font-medium">
                    <span class="material-symbols-rounded text-red-500"
                        style="font-variation-settings:'FILL' 1">error</span>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>

        <?= $this->include('layouts/admin/footer') ?>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('admin-sidebar').classList.toggle('open');
            document.getElementById('sidebar-overlay').classList.toggle('hidden');
        }
        function closeSidebar() {
            document.getElementById('admin-sidebar').classList.remove('open');
            document.getElementById('sidebar-overlay').classList.add('hidden');
        }
        // ✅ JS auto-active dihapus — active state ditangani sepenuhnya oleh PHP
    </script>
</body>

</html>