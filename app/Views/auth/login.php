<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen flex items-center justify-center py-12 px-6">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-primary mb-2">Login</h1>
            <p class="text-slate-600">Selamat datang kembali di CeritaKu</p>
        </div>
        
        <div class="bg-white p-8 rounded-2xl shadow-lg border border-border">
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <p class="text-red-700 text-sm"><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form action="<?= base_url('/login') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                        placeholder="email@example.com"
                        value="<?= old('email') ?>">
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                        placeholder="••••••••">
                </div>
                
                <button type="submit" 
                    class="w-full bg-accent text-white py-3 rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                    Login
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-slate-600 text-sm">
                    Belum punya akun? 
                    <a href="<?= base_url('/register') ?>" class="text-accent font-semibold hover:underline">Daftar di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
