<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Review Management</h1>
        <p class="text-sm text-gray-500 mt-1">Moderasi ulasan dari pembaca</p>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 mb-1">Total Review</p>
        <p class="text-2xl font-bold text-gray-800"><?= count($reviews ?? []) ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 mb-1">Rata-rata Rating</p>
        <?php
        $avg = 0;
        if (!empty($reviews)) {
            $avg = array_sum(array_column($reviews, 'rating')) / count($reviews);
        }
        ?>
        <p class="text-2xl font-bold text-yellow-500"><?= number_format($avg, 1) ?> ⭐</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 mb-1">Rating ≥ 4</p>
        <?php $positive = count(array_filter($reviews ?? [], fn($r) => $r['rating'] >= 4)); ?>
        <p class="text-2xl font-bold text-green-600"><?= $positive ?></p>
    </div>
</div>

<!-- Search -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-6 flex flex-wrap gap-3 items-center">
    <div class="relative flex-1 min-w-[200px]">
        <input type="text" onkeyup="searchTable('reviewTable', this.value)"
            placeholder="Cari reviewer / isi review..."
            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
        <span class="material-symbols-outlined absolute left-2.5 top-2.5 text-gray-400 text-base">search</span>
    </div>

    <select onchange="filterByRating(this.value)" class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-purple-400">
        <option value="">Semua Rating</option>
        <option value="5">⭐ 5 Bintang</option>
        <option value="4">⭐ 4 Bintang</option>
        <option value="3">⭐ 3 Bintang</option>
        <option value="2">⭐ 2 Bintang</option>
        <option value="1">⭐ 1 Bintang</option>
    </select>
</div>

<!-- Table -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800">Daftar Ulasan</h3>
    </div>

    <div class="overflow-x-auto">
        <table id="reviewTable" class="w-full text-sm">
            <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left">Reviewer</th>
                    <th class="px-4 py-3 text-left">Cerita</th>
                    <th class="px-4 py-3 text-left">Rating</th>
                    <th class="px-4 py-3 text-left">Review</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $review): ?>
                    <tr class="hover:bg-gray-50 transition review-row" data-rating="<?= esc($review['rating']) ?>">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                    <span class="text-indigo-600 font-bold text-xs"><?= strtoupper(substr($review['user_name'] ?? 'U', 0, 1)) ?></span>
                                </div>
                                <span class="font-medium text-gray-800"><?= esc($review['user_name'] ?? 'Unknown') ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600 max-w-[150px] truncate"><?= esc($review['story_title'] ?? '-') ?></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="text-<?= $i <= $review['rating'] ? 'yellow-400' : 'gray-200' ?> text-base">★</span>
                                <?php endfor; ?>
                                <span class="text-xs text-gray-500 ml-1">(<?= $review['rating'] ?>)</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600 max-w-[250px]">
                            <p class="line-clamp-2 text-sm"><?= esc($review['content'] ?? $review['comment'] ?? '-') ?></p>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs"><?= date('d M Y', strtotime($review['created_at'])) ?></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1">
                                <!-- Hapus -->
                                <form action="<?= base_url('/admin/reviews/delete/' . $review['id']) ?>" method="POST" class="inline"
                                    onsubmit="return confirm('Hapus review ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                            <span class="material-symbols-outlined text-5xl block mb-2">rate_review</span>
                            Belum ada review
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
function searchTable(tableId, query) {
    document.querySelectorAll('#' + tableId + ' tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(query.toLowerCase()) ? '' : 'none';
    });
}

function filterByRating(rating) {
    document.querySelectorAll('#reviewTable tbody tr.review-row').forEach(row => {
        row.style.display = (!rating || row.dataset.rating === rating) ? '' : 'none';
    });
}
</script>

<?= $this->endSection() ?>
