<!-- Register Modal -->
<div id="registerModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="w-full max-w-2xl mx-auto">
        <div class="bg-white shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[240px]">

            <!-- Left: Illustration -->
            <div class="hidden md:flex md:w-1/2 bg-gray-50">
                <img src="<?= base_url('assets/images/login_ilustration2.jpg') ?>" alt="Register Illustration"
                    class="w-full h-full object-cover">
            </div>

            <!-- Right: Form -->
            <div class="w-full md:w-1/2 px-6 md:px-10 py-4 md:py-8 flex flex-col justify-center">

                <!-- Logo -->
                <div class="text-center mb-2">
                    <img src="<?= base_url('assets/images/logo4.png') ?>" alt="Ceritaku" class="h-24 mx-auto">
                </div>

                <!-- Heading -->
                <div class="mb-4 text-center">
                    <h2 class="font-serif text-xl md:text-2xl text-gray-800 mb-1">Create Account</h2>
                    <p class="text-gray-400 text-xs">Join and start your reading journey</p>
                </div>

                <!-- Register Form -->
                <form id="registerForm" method="POST" action="<?= base_url('/auth/register') ?>" class="space-y-4">
                    <?= csrf_field() ?>

                    <div class="relative">
                        <input type="text" id="register_name" name="name" required
                            placeholder="Full Name"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#6c5ce7] focus:ring-1 focus:ring-[#6c5ce7] outline-none transition duration-200 text-sm placeholder:text-gray-400">
                    </div>

                    <div class="relative">
                        <input type="email" id="register_email" name="email" required
                            placeholder="Email"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#6c5ce7] focus:ring-1 focus:ring-[#6c5ce7] outline-none transition duration-200 text-sm placeholder:text-gray-400">
                    </div>

                    <div class="relative">
                        <input type="password" id="register_password" name="password" required
                            placeholder="Password"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#6c5ce7] focus:ring-1 focus:ring-[#6c5ce7] outline-none transition duration-200 text-sm placeholder:text-gray-400">
                        <button type="button" onclick="togglePassword('register_password', this)"
                            class="absolute right-4 top-4 text-gray-300 hover:text-gray-500 transition">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <div class="relative">
                        <input type="password" id="register_password_confirm" name="password_confirm" required
                            placeholder="Confirm Password"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#6c5ce7] focus:ring-1 focus:ring-[#6c5ce7] outline-none transition duration-200 text-sm placeholder:text-gray-400">
                        <button type="button" onclick="togglePassword('register_password_confirm', this)"
                            class="absolute right-4 top-4 text-gray-300 hover:text-gray-500 transition">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <div class="flex items-center text-xs text-gray-500 px-1">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="terms" required
                                class="rounded border-gray-300 text-[#6c5ce7] focus:ring-[#6c5ce7]">
                            <span>I agree to the
                                <a href="#" class="text-accent hover:text-purple-700 font-semibold">Terms & Conditions</a>
                            </span>
                        </label>
                    </div>

                    <div id="registerError" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                        <span id="registerErrorText"></span>
                    </div>

                    <button type="submit"
                        class="w-full bg-accent text-white font-semibold py-3 rounded-2xl shadow-lg transition-all hover:bg-purple-700 transform hover:scale-[1.02]">
                        Sign up
                    </button>
                </form>

                <!-- Login link -->
                <p class="text-center text-xs text-gray-400 mt-4 mb-2">
                    Already have an account?
                    <a href="#" class="text-[#6c5ce7] font-medium" onclick="event.preventDefault(); openModal('loginModal')">Login</a>
                </p>

                <!-- Social Login -->
                <div class="flex flex-col sm:flex-row gap-2">
                    <a href="<?= base_url('auth/google') ?>"
                        class="flex-1 flex items-center justify-center gap-2 border border-gray-200 py-2 rounded-xl hover:bg-gray-50 transition">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google">
                        <span class="text-sm font-medium text-gray-600">Google</span>
                    </a>
                    <button
                        class="flex-1 flex items-center justify-center gap-2 border border-gray-200 py-2 rounded-xl hover:bg-gray-50 transition">
                        <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="w-5 h-5" alt="Facebook">
                        <span class="text-sm font-medium text-gray-600">Facebook</span>
                    </button>
                </div>

                <!-- Footer -->
                <p class="text-center text-[10px] text-gray-400 mt-4">
                    @2026 CeritaKu. All Rights reserved.
                </p>

            </div>
        </div>
    </div>
</div>

<script>
// Toggle show/hide password
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// Register form: client-side validation before submit
document.getElementById('registerForm').addEventListener('submit', function (e) {
    const password = document.getElementById('register_password').value;
    const confirm  = document.getElementById('register_password_confirm').value;
    const terms    = this.querySelector('input[name="terms"]');
    const errorBox = document.getElementById('registerError');
    const errorMsg = document.getElementById('registerErrorText');

    errorBox.classList.add('hidden');

    if (password !== confirm) {
        e.preventDefault();
        errorMsg.textContent = 'Passwords do not match.';
        errorBox.classList.remove('hidden');
        return;
    }

    if (!terms.checked) {
        e.preventDefault();
        errorMsg.textContent = 'You must agree to the Terms & Conditions.';
        errorBox.classList.remove('hidden');
        return;
    }
});
</script>