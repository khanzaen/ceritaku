<!-- Register Modal -->

<div id="registerModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="w-full max-w-2xl mx-auto">
        <div class="bg-white shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[240px]">
            <div class="hidden md:flex md:w-1/2 bg-gray-50 p-0">
                <img src="<?= base_url('assets/images/login_ilustration2.jpg') ?>" alt="Register Illustration" class="w-full h-full object-cover">
            </div>
            <div class="w-full md:w-1/2 px-6 md:px-10 py-4 md:py-8 flex flex-col justify-center relative">
                <div class="text-center mb-2">
                    <img src="<?= base_url('assets/images/logo4.png') ?>" alt="Ceritaku" class="h-24 mx-auto mb-0">
                </div>
                <div class="mb-4 mt-0 text-center">
                    <h2 class="font-serif text-xl md:text-2xl text-gray-800 mb-1 mt-0">Create Account</h2>
                    <p class="text-gray-400 text-xs">Join and start your reading journey</p>
                </div>
                <form id="registerForm" method="POST" action="<?= base_url('/auth/register') ?>" class="space-y-4">
                    <?= csrf_field() ?>
                    <div class="relative">
                        <input type="text" id="register_name" name="name" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#6c5ce7] focus:ring-1 focus:ring-[#6c5ce7] outline-none transition duration-200 text-sm placeholder:text-gray-400"
                            placeholder="Full Name">
                    </div>
                    <div class="relative">
                        <input type="email" id="register_email" name="email" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#6c5ce7] focus:ring-1 focus:ring-[#6c5ce7] outline-none transition duration-200 text-sm placeholder:text-gray-400"
                            placeholder="Email">
                    </div>
                    <div class="relative">
                        <input type="password" id="register_password" name="password" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#6c5ce7] focus:ring-1 focus:ring-[#6c5ce7] outline-none transition duration-200 text-sm placeholder:text-gray-400"
                            placeholder="Password">
                        <i class="fa-solid fa-eye absolute right-4 top-4 text-gray-300"></i>
                    </div>
                    <div class="relative">
                        <input type="password" id="register_password_confirm" name="password_confirm" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#6c5ce7] focus:ring-1 focus:ring-[#6c5ce7] outline-none transition duration-200 text-sm placeholder:text-gray-400"
                            placeholder="Confirm Password">
                        <i class="fa-solid fa-eye absolute right-4 top-4 text-gray-300"></i>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500 px-1">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="terms" class="rounded border-gray-300 text-[#6c5ce7] focus:ring-[#6c5ce7]">
                            <span>I agree to the <a href="#" class="text-accent hover:text-purple-700 font-semibold">Terms & Conditions</a></span>
                        </label>
                    </div>
                    <div id="registerError" class="mb-2 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 hidden">
                        <span id="registerErrorText"></span>
                    </div>
                    <button type="submit" class="w-full bg-accent text-white font-semibold py-3 rounded-2xl shadow-lg transition-all hover:bg-purple-700 transform hover:scale-[1.02]">
                        Sign up
                    </button>
                </form>
                <div class="mt-4">
                    <p class="text-center text-xs text-gray-400 mb-2">Or continue with <a href="#" class="text-[#6c5ce7] font-medium">Login</a></p>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <button class="flex-1 flex items-center justify-center gap-2 border border-gray-200 py-2 rounded-xl hover:bg-gray-50 transition">
                            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5">
                            <span class="text-sm font-medium text-gray-600">Google</span>
                        </button>
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
