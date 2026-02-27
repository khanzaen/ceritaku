<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-indigo-600 text-2xl" title="Total Users">group</span>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Users</p>
            <p class="text-2xl font-bold text-gray-800"><?= number_format($total_users ?? 0) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-purple-600 text-2xl" title="Total Stories">menu_book</span>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Stories</p>
            <p class="text-2xl font-bold text-gray-800"><?= number_format($total_stories ?? 0) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-green-600 text-2xl" title="Published">check_circle</span>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Published</p>
            <p class="text-2xl font-bold text-gray-800"><?= number_format($total_published ?? 0) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-amber-600 text-2xl" title="Pending Review">pending</span>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Pending Review</p>
            <p class="text-2xl font-bold text-gray-800"><?= number_format($total_pending ?? 0) ?></p>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
</div>

<?= $this->endSection() ?>