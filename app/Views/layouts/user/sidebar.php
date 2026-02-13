<?php
/**
 * Mobile Sidebar - Navigation untuk Mobile/Responsive
 */

// Get session data
$is_logged_in = session()->get('isLoggedIn') === true;
$user_id = session()->get('user_id');
$user_name = session()->get('user_name') ?? 'User';
$user_email = session()->get('user_email') ?? '';
$user_photo = session()->get('user_photo') ?? '';

// Get current URI for active navigation
$current_uri = uri_string();
?>

<!-- Mobile Sidebar Overlay -->
<div id="mobile-sidebar-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[60] md:hidden transition-opacity" onclick="closeMobileSidebar()"></div>

<!-- Mobile Sidebar -->
<aside id="mobile-sidebar" class="fixed top-0 right-0 h-full w-80 bg-white dark:bg-slate-800 shadow-2xl z-[70] transform translate-x-full transition-transform duration-300 md:hidden overflow-y-auto">
    <div class="flex flex-col h-full">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between p-6 border-b border-border dark:border-slate-700">
            <a href="<?= base_url('/') ?>" class="flex items-center gap-2">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="CeritaKu" class="h-8 object-contain"/>
            </a>
            <button type="button" onclick="closeMobileSidebar()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                <span class="material-symbols-outlined text-slate-600 dark:text-slate-300">close</span>
            </button>
        </div>

        <!-- User Profile Section (if logged in) -->
        <?php if ($is_logged_in): ?>
            <div class="p-6 border-b border-border dark:border-slate-700 bg-slate-50 dark:bg-slate-700/50">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-border dark:border-slate-600 flex items-center justify-center bg-gradient-to-br from-purple-400 to-purple-600">
                        <?php if (!empty($user_photo)): ?>
                            <img src="<?= profile_url($user_photo) ?>" alt="<?= esc($user_name) ?>" class="w-full h-full object-cover" />
                        <?php else: ?>
                            <?php
                            // Get initials from user name
                            $names = explode(' ', trim($user_name));
                            $initials = '';
                            if (count($names) >= 2) {
                                $initials = strtoupper(substr($names[0], 0, 1) . substr($names[count($names) - 1], 0, 1));
                            } else {
                                $initials = strtoupper(substr($user_name, 0, 2));
                            }
                            ?>
                            <span class="text-white font-bold"><?= $initials ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-900 dark:text-slate-100 truncate"><?= esc($user_name) ?></p>
                        <p class="text-sm text-slate-600 dark:text-slate-400 truncate"><?= esc($user_email) ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Navigation Links -->
        <nav class="flex-1 p-6">
            <!-- Main Navigation -->
            <div class="space-y-2 mb-6">
                <a href="<?= base_url('/') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= ($current_uri === '' || $current_uri === '/') ? 'bg-accent text-white' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700'; ?> transition-colors">
                    <span class="material-symbols-outlined text-[22px]">home</span>
                    <span class="font-semibold">Home</span>
                </a>
                <a href="<?= base_url('/discover') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= strpos($current_uri, 'discover') !== false ? 'bg-accent text-white' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700'; ?> transition-colors">
                    <span class="material-symbols-outlined text-[22px]">explore</span>
                    <span class="font-semibold">Discover</span>
                </a>
                <a href="<?= base_url('/') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                    <span class="material-symbols-outlined text-[22px]">edit</span>
                    <span class="font-semibold">Write</span>
                </a>
                <a href="<?= base_url('/') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                    <span class="material-symbols-outlined text-[22px]">groups</span>
                    <span class="font-semibold">Community</span>
                </a>
            </div>

            <?php if ($is_logged_in): ?>
                <!-- Divider -->
                <hr class="my-6 border-border dark:border-slate-700">

                <!-- User Menu -->
                <div class="space-y-2">
                    <p class="px-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3">My Account</p>
                    
                    <a href="<?= base_url('/profile') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <span class="material-symbols-outlined text-[22px]">person</span>
                        <span class="font-medium">My Profile</span>
                    </a>
                    <a href="<?= base_url('/my-stories') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <span class="material-symbols-outlined text-[22px]">auto_stories</span>
                        <span class="font-medium">My Stories</span>
                    </a>
                    <a href="<?= base_url('/library') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <span class="material-symbols-outlined text-[22px]">bookmarks</span>
                        <span class="font-medium">My Library</span>
                    </a>
                    <a href="<?= base_url('/reviews') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <span class="material-symbols-outlined text-[22px]">rate_review</span>
                        <span class="font-medium">My Reviews</span>
                    </a>
                    <a href="<?= base_url('/') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors relative">
                        <span class="material-symbols-outlined text-[22px]">notifications</span>
                        <span class="font-medium">Notifications</span>
                        <span class="ml-auto w-2 h-2 bg-red-500 rounded-full"></span>
                    </a>
                </div>

                <!-- Divider -->
                <hr class="my-6 border-border dark:border-slate-700">

                <!-- Logout Button -->
                <button type="button" onclick="showLogoutConfirm()" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    <span class="material-symbols-outlined text-[22px]">logout</span>
                    <span class="font-semibold">Logout</span>
                </button>
            <?php else: ?>
                <!-- Divider -->
                <hr class="my-6 border-border dark:border-slate-700">

                <!-- Auth Buttons -->
                <div class="space-y-3">
                    <button type="button" onclick="openModal('loginModal'); closeMobileSidebar();" class="w-full px-4 py-3 border-2 border-accent text-accent rounded-lg font-bold hover:bg-accent hover:text-white transition-colors">
                        Login
                    </button>
                    <button type="button" onclick="openModal('registerModal'); closeMobileSidebar();" class="w-full px-4 py-3 bg-accent text-white rounded-lg font-bold hover:bg-purple-700 transition-colors">
                        Sign up
                    </button>
                </div>
            <?php endif; ?>
        </nav>
    </div>
</aside>

<script>
// Mobile Sidebar Functions
function openMobileSidebar() {
    const sidebar = document.getElementById('mobile-sidebar');
    const overlay = document.getElementById('mobile-sidebar-overlay');
    
    if (sidebar && overlay) {
        overlay.classList.remove('hidden');
        setTimeout(() => {
            sidebar.classList.remove('translate-x-full');
            document.body.style.overflow = 'hidden';
        }, 10);
    }
}

function closeMobileSidebar() {
    const sidebar = document.getElementById('mobile-sidebar');
    const overlay = document.getElementById('mobile-sidebar-overlay');
    
    if (sidebar && overlay) {
        sidebar.classList.add('translate-x-full');
        setTimeout(() => {
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }
}

// Mobile Menu Toggle Event
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('mobile-menu-toggle');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            openMobileSidebar();
        });
    }
});

// Close sidebar when window is resized to desktop
window.addEventListener('resize', function() {
    if (window.innerWidth >= 768) {
        closeMobileSidebar();
    }
});
</script>
