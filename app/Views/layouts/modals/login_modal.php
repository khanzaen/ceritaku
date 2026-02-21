<!-- Login Modal (Versi Awal Sederhana) -->
<div id="loginModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="w-full max-w-2xl mx-auto">
        <div class="bg-white shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[400px]">
            <div class="hidden md:flex md:w-1/2 bg-gray-50 p-0">
                <img src="<?= base_url('assets/images/login_ilustration2.jpg') ?>" alt="Login Illustration" class="w-full h-full object-cover">
            </div>
            <div class="w-full md:w-1/2 px-6 md:px-10 py-4 md:py-8 flex flex-col justify-center relative">
                <div class="text-center mb-2">
                    <img src="<?= base_url('assets/images/logo4.png') ?>" alt="Ceritaku" class="h-24 mx-auto mb-0">
                </div>
                <div class="mb-4 mt-0 text-center">
                    <h2 class="font-serif text-xl md:text-2xl text-gray-800 mb-1 mt-0">Welcome Back</h2>
                    <p class="text-gray-400 text-xs">Continue your reading journey</p>
                </div>
                <form id="loginForm" method="POST" action="<?= base_url('/auth/login') ?>" class="space-y-4">
                    <?= csrf_field() ?>
                    <div class="relative">
                        <input type="email" id="login_email" name="email" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#6c5ce7] focus:ring-1 focus:ring-[#6c5ce7] outline-none transition duration-200 text-sm placeholder:text-gray-400"
                            placeholder="Email">
                        <i class="fa-solid fa-envelope absolute right-4 top-4 text-gray-300"></i>
                    </div>
                    <div class="relative">
                        <input type="password" id="login_password" name="password" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#6c5ce7] focus:ring-1 focus:ring-[#6c5ce7] outline-none transition duration-200 text-sm placeholder:text-gray-400"
                            placeholder="Password">
                        <i class="fa-solid fa-eye absolute right-4 top-4 text-gray-300"></i>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500 px-1">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#6c5ce7] focus:ring-[#6c5ce7]">
                            <span>Remember me</span>
                        </label>
                        <a href="#" class="hover:text-[#6c5ce7] transition underline">Forgot password?</a>
                    </div>
                    <div id="loginError" class="mb-2 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 hidden">
                        <span id="loginErrorText"></span>
                    </div>
                    <button type="submit" class="w-full bg-accent text-white font-semibold py-3 rounded-2xl shadow-lg transition-all hover:bg-purple-700 transform hover:scale-[1.02]">
                        Login
                    </button>
                </form>
                <div class="mt-4">
                    <p class="text-center text-xs text-gray-400 mb-2">Or continue with <a href="#" class="text-[#6c5ce7] font-medium">Sign up now</a></p>
                    <div class="flex flex-col sm:flex-row gap-2">
                       <a href="<?= base_url('auth/google') ?>"
   class="flex-1 flex items-center justify-center gap-2 border border-gray-200 py-2 rounded-xl hover:bg-gray-50 transition">
    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5">
    <span class="text-sm font-medium text-gray-600">Google</span>
</a>
                        <button class="flex-1 flex items-center justify-center gap-2 border border-gray-200 py-2 rounded-xl hover:bg-gray-50 transition">
                            <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="w-5 h-5">
                            <span class="text-sm font-medium text-gray-600">Facebook</span>
                        </button>
                    </div>
                </div>
                <p class="text-center text-[10px] text-gray-400 mt-4">
                    @2026 CeritaKu. All Rights reserved.
                </p>
            </div>
        </div>
    </div>
</div>
