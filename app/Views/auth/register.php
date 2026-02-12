<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen flex items-center justify-center py-12 px-6">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-primary mb-2">Register</h1>
            <p class="text-slate-600">Bergabung dengan komunitas CeritaKu</p>
        </div>
        
        <div class="bg-white p-8 rounded-2xl shadow-lg border border-border">
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <p class="text-red-700 text-sm"><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form action="<?= base_url('/register') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="name" name="name" required
                        class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                        placeholder="John Doe"
                        value="<?= old('name') ?>">
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                        placeholder="email@example.com"
                        value="<?= old('email') ?>">
                </div>
                
                <div class="mb-4">
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                        placeholder="Minimal 8 karakter">
                </div>
                
                <div class="mb-6">
                    <label for="confirm_password" class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required
                        class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                        placeholder="Ulangi password">
                </div>
                
                <button type="submit" 
                    class="w-full bg-accent text-white py-3 rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                    Daftar Sekarang
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-slate-600 text-sm">
                    Sudah punya akun? 
                    <a href="<?= base_url('/login') ?>" class="text-accent font-semibold hover:underline">Login di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
