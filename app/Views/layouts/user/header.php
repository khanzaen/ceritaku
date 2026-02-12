<?php
/**
 * User Header - Navbar untuk CeritaKu
 * Include file ini di bagian atas setiap halaman user
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

<header class="bg-white dark:bg-slate-800 border-b border-border dark:border-slate-700 sticky top-0 z-50 transition-colors duration-300">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
        <!-- Logo & Nav -->
        <div class="flex items-center gap-10">
            <a class="flex items-center gap-2" href="<?= base_url('/') ?>">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="CeritaKu" class="h-10 object-contain"/>
            </a>
            
            <!-- Desktop Navigation -->
            <nav class="hidden md:flex gap-8">
                <a href="<?= base_url('/') ?>" class="text-sm font-bold <?= ($current_uri === '' || $current_uri === '/') ? 'text-accent' : 'text-slate-600 dark:text-slate-300 hover:text-accent'; ?> transition-colors">
                    Home
                </a>
                <a href="<?= base_url('/discover') ?>" class="text-sm font-bold <?= strpos($current_uri, 'discover') !== false ? 'text-accent' : 'text-slate-600 hover:text-accent'; ?> transition-colors">
                    Discover
                </a>
                <a href="<?= base_url('/') ?>" class="text-sm font-bold text-slate-600 hover:text-accent transition-colors">
                    Write
                </a>
                <a href="<?= base_url('/') ?>" class="text-sm font-bold text-slate-600 hover:text-accent transition-colors">
                    Community
                </a>
            </nav>
        </div>

        <!-- Right Side Actions -->
        <div class="flex items-center gap-4">
            <?php if ($is_logged_in): ?>
                <!-- Notifications -->
                <button type="button" class="relative w-9 h-9 rounded-full border border-border bg-white hover:bg-slate-50 transition-colors flex items-center justify-center" aria-label="Notifications">
                    <span class="material-symbols-outlined text-slate-600 text-[20px]">notifications</span>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                <!-- Profile Dropdown -->
                <div class="flex items-center gap-3 relative">
                    <button type="button" id="profile-menu-button" class="w-9 h-9 rounded-full overflow-hidden border border-border hover:border-accent transition-colors flex items-center justify-center bg-gradient-to-br from-purple-400 to-purple-600" aria-label="Profile menu" aria-haspopup="true" aria-expanded="false">
                        <?php if (!empty($user_photo)): ?>
                            <img src="<?= base_url($user_photo) ?>" alt="<?= esc($user_name) ?>" class="w-full h-full object-cover" />
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
                            <span class="text-white text-sm font-bold"><?= $initials ?></span>
                        <?php endif; ?>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="profile-menu" class="hidden absolute right-0 top-12 w-48 bg-white dark:bg-slate-800 border border-border dark:border-slate-700 rounded-xl shadow-lg overflow-hidden z-50">
                        <div class="px-4 py-3 border-b border-border dark:border-slate-700 bg-slate-50 dark:bg-slate-700/50">
                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100"><?= esc($user_name) ?></p>
                            <p class="text-xs text-slate-600 dark:text-slate-400"><?= esc($user_email) ?></p>
                        </div>
                        <a href="<?= base_url('/profile') ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <span class="material-symbols-outlined text-[18px] mr-2 align-middle">person</span> My Profile
                        </a>
                        <a href="<?= base_url('/my-stories') ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <span class="material-symbols-outlined text-[18px] mr-2 align-middle">auto_stories</span> My Stories
                        </a>
                        <a href="<?= base_url('/library') ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <span class="material-symbols-outlined text-[18px] mr-2 align-middle">bookmarks</span> My Library
                        </a>
                        <a href="<?= base_url('/reviews') ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <span class="material-symbols-outlined text-[18px] mr-2 align-middle">rate_review</span> My Reviews
                        </a>
                        <hr class="my-2 border-border dark:border-slate-700">
                        <a href="#" id="logoutBtn" class="block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            <span class="material-symbols-outlined text-[18px] mr-2 align-middle">logout</span> Logout
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <button type="button" onclick="openModal('loginModal')" class="text-sm font-bold text-slate-600 dark:text-slate-300 hover:text-accent transition-colors">
                    Login
                </button>
                <button type="button" onclick="openModal('registerModal')" class="text-sm font-bold bg-accent text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-all">
                    Sign up
                </button>
            <?php endif; ?>

            <!-- Mobile Menu Toggle -->
            <button type="button" id="mobile-menu-toggle" class="md:hidden w-9 h-9 flex items-center justify-center">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </div>
</header>

<!-- Toast Notification Container -->
<div id="toastContainer" class="fixed top-20 right-6 z-[60] flex flex-col gap-3 pointer-events-none">
    <!-- Toasts will be inserted here by JavaScript -->
</div>

<!-- Logout Confirmation Modal -->
<div id="logoutConfirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[70] items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full transform transition-all" onclick="event.stopPropagation()">
        <div class="p-6">
            <!-- Icon -->
            <div class="w-14 h-14 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-[32px]">logout</span>
            </div>
            
            <!-- Title -->
            <h3 class="text-xl font-bold text-center text-slate-900 dark:text-slate-100 mb-2">Logout</h3>
            
            <!-- Message -->
            <p class="text-center text-slate-600 dark:text-slate-400 text-sm mb-6">
                Are you sure you want to logout from your account?
            </p>
            
            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="button" onclick="closeLogoutConfirm()" class="flex-1 px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors font-medium">
                    Cancel
                </button>
                <button type="button" onclick="confirmLogout()" class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                    Logout
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.getElementById('profile-menu-button');
        const menu = document.getElementById('profile-menu');
        
        if (menuButton && menu) {
            menuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                const isOpen = !menu.classList.contains('hidden');
                menu.classList.toggle('hidden', isOpen);
                menuButton.setAttribute('aria-expanded', (!isOpen).toString());
            });

            document.addEventListener('click', function(e) {
                if (!menu.classList.contains('hidden') && !menuButton.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add('hidden');
                    menuButton.setAttribute('aria-expanded', 'false');
                }
            });
        }

        // Logout button handler
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                showLogoutConfirm();
            });
        }
    });

    // Show logout confirmation modal
    function showLogoutConfirm() {
        const modal = document.getElementById('logoutConfirmModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    // Close logout confirmation modal
    function closeLogoutConfirm() {
        const modal = document.getElementById('logoutConfirmModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // Confirm logout
    function confirmLogout() {
        window.location.href = '<?= base_url('/auth/logout') ?>';
    }
</script>
