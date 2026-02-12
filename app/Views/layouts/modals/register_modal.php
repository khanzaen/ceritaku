<!-- Register Modal -->
<div id="registerModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="w-full max-w-lg">
        <!-- Register Panel -->
        <div class="bg-white dark:bg-slate-800 backdrop-blur-lg rounded-lg shadow-2xl border border-white/20 dark:border-slate-700">
            <!-- Form Content -->
            <div class="px-6 pb-6 pt-6">
                <!-- Header with Logo -->
                <div class="text-center mb-6">
                    <img src="<?= base_url('assets/images/logo.png') ?>" alt="Ceritaku" class="h-12 mx-auto mb-3">
                    <p class="text-xs text-slate-600 dark:text-slate-400">Start your journey as a reader and writer</p>
                </div>

                <form id="registerForm" method="POST" action="<?= base_url('/auth/register') ?>">
                    <?= csrf_field() ?>
                    
                    <!-- Name Field -->
                    <div class="mb-3">
                        <input type="text" id="register_name" name="name" required 
                            class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent outline-none transition-all text-sm placeholder:text-slate-400 dark:placeholder:text-slate-500"
                            placeholder="Enter your full name">
                    </div>

                    <!-- Email Field -->
                    <div class="mb-3">
                        <input type="email" id="register_email" name="email" required 
                            class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent outline-none transition-all text-sm placeholder:text-slate-400 dark:placeholder:text-slate-500"
                            placeholder="Enter your email">
                    </div>

                    <!-- Password Field -->
                    <div class="mb-3">
                        <div class="relative">
                            <input type="password" id="register_password" name="password" required 
                                class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent outline-none transition-all text-sm placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                placeholder="Enter your password (min. 8 characters)">
                            <button type="button" onclick="togglePassword('register_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="mb-3">
                        <div class="relative">
                            <input type="password" id="register_password_confirm" name="password_confirm" required 
                                class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent outline-none transition-all text-sm placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                placeholder="Confirm your password">
                            <button type="button" onclick="togglePassword('register_password_confirm')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Terms Checkbox -->
                    <div class="mb-4">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" name="terms" required class="w-4 h-4 text-accent border-slate-300 dark:border-slate-600 rounded focus:ring-accent mt-0.5">
                            <span class="ml-2 text-xs text-slate-600 dark:text-slate-400">
                                I agree to the <a href="#" class="text-accent hover:text-purple-700 font-semibold">Terms & Conditions</a> and <a href="#" class="text-accent hover:text-purple-700 font-semibold">Privacy Policy</a>
                            </span>
                        </label>
                    </div>

                    <!-- Error Message -->
                    <div id="registerError" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 hidden">
                        <span id="registerErrorText"></span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-accent text-white py-2.5 rounded-lg font-semibold hover:bg-purple-700 transition-all shadow-lg shadow-accent/30 mb-5">
                        Sign up
                    </button>

                    <!-- Divider -->
                    <div class="relative my-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="px-3 bg-white dark:bg-slate-800 text-slate-400 dark:text-slate-500">Or continue with</span>
                        </div>
                    </div>

                    <!-- Social Login Buttons -->
                    <div class="grid grid-cols-2 gap-2 mb-5">
                        <button type="button" class="flex items-center justify-center gap-1.5 px-3 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Google</span>
                        </button>
                        <button type="button" class="flex items-center justify-center gap-1.5 px-3 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                            <svg class="w-4 h-4 fill-[#1877F2]" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Facebook</span>
                        </button>
                    </div>

                    <!-- Login Link -->
                    <p class="text-center text-xs text-slate-600 dark:text-slate-400">
                        Already have an account? 
                        <button type="button" onclick="switchModal('registerModal', 'loginModal')" class="text-accent hover:text-purple-700 font-semibold">
                            Login
                        </button>
                    </p>

                    <!-- Footer -->
                    <p class="text-center text-xs text-slate-400 dark:text-slate-500 mt-6">
                        @2026 CeritaKu. All rights reserved.
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
