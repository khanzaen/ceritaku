<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Story Management</h1>
    <p class="text-sm text-gray-500 mt-1">Kelola semua cerita yang ada di platform</p>
    <div class="mt-4">
        <select id="filterStatus" onchange="filterStories()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option value="">Semua Status</option>
            <option value="PUBLISHED">Published</option>
            <option value="PENDING_REVIEW">Pending Review</option>
            <option value="DRAFT">Draft</option>
            <option value="ARCHIVED">Archived</option>
        </select>
    </div>
</div>

<!-- Stats mini -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <?php
    $statuses = [
        ['label' => 'Total',        'key' => 'all',            'color' => 'indigo'],
        ['label' => 'Published',    'key' => 'PUBLISHED',      'color' => 'green'],
        ['label' => 'Pending',      'key' => 'PENDING_REVIEW', 'color' => 'orange'],
        ['label' => 'Archived',     'key' => 'ARCHIVED',       'color' => 'gray'],
    ];
    $counts = [];
    if (!empty($stories)) {
        foreach ($stories as $s) {
            $counts[$s['status']] = ($counts[$s['status']] ?? 0) + 1;
        }
    }
    $colors = ['indigo' => 'bg-indigo-50 text-indigo-700', 'green' => 'bg-green-50 text-green-700', 'orange' => 'bg-orange-50 text-orange-700', 'gray' => 'bg-gray-50 text-gray-700'];
    foreach ($statuses as $st):
        $count = $st['key'] === 'all' ? count($stories ?? []) : ($counts[$st['key']] ?? 0);
    ?>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 mb-1"><?= $st['label'] ?></p>
        <p class="text-2xl font-bold <?= explode(' ', $colors[$st['color']])[1] ?>"><?= $count ?></p>
    </div>
    <?php endforeach; ?>
</div>

<!-- Table -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-gray-800">Daftar Cerita</h3>
        <div class="relative">
            <input type="text" id="searchStory" onkeyup="searchTable('storyTable', this.value)"
                placeholder="Cari judul / penulis..."
                class="pl-8 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400 w-56">
            <span class="material-symbols-outlined absolute left-2 top-2 text-gray-400 text-base">search</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table id="storyTable" class="w-full text-sm">
            <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left">Judul</th>
                    <th class="px-4 py-3 text-left">Penulis</th>
                    <th class="px-4 py-3 text-left">Genre</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($stories)): ?>
                    <?php foreach ($stories as $story): ?>
                    <tr class="hover:bg-gray-50 transition story-row" data-status="<?= esc($story['status']) ?>">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <?php if (!empty($story['cover_image'])): ?>
                                    <img src="<?= base_url('uploads/' . $story['cover_image']) ?>"
                                        class="w-10 h-12 object-cover rounded-md flex-shrink-0">
                                <?php else: ?>
                                    <div class="w-10 h-12 bg-purple-100 rounded-md flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-purple-400 text-base">menu_book</span>
                                    </div>
                                <?php endif; ?>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900 truncate max-w-[200px]"><?= esc($story['title']) ?></p>
                                    <p class="text-xs text-gray-400 truncate max-w-[200px]"><?= esc(substr($story['synopsis'] ?? '', 0, 60)) ?>...</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600"><?= esc($story['author_name'] ?? '-') ?></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded text-xs"><?= esc($story['genre'] ?? '-') ?></span>
                        </td>
                        <td class="px-4 py-3">
                            <?php
                            $statusMap = [
                                'PUBLISHED'      => 'bg-green-100 text-green-700',
                                'PENDING_REVIEW' => 'bg-orange-100 text-orange-700',
                                'DRAFT'          => 'bg-gray-100 text-gray-600',
                                'ARCHIVED'       => 'bg-red-100 text-red-600',
                            ];
                            $cls = $statusMap[$story['status']] ?? 'bg-gray-100 text-gray-600';
                            ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium <?= $cls ?>">
                                <?= esc($story['status']) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs"><?= date('d M Y', strtotime($story['created_at'])) ?></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1">
                                <!-- Approve -->
                                <?php if ($story['status'] === 'PENDING_REVIEW'): ?>
                                <form action="<?= base_url('/admin/stories/approve/' . $story['id']) ?>" method="POST" class="inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" title="Approve"
                                        class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg transition">
                                        <span class="material-symbols-outlined text-base">check_circle</span>
                                    </button>
                                </form>
                                <?php endif; ?>

                                <!-- Archive -->
                                <?php if ($story['status'] !== 'ARCHIVED'): ?>
                                <form action="<?= base_url('/admin/stories/archive/' . $story['id']) ?>" method="POST" class="inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" title="Archive"
                                        class="p-1.5 text-orange-500 hover:bg-orange-50 rounded-lg transition">
                                        <span class="material-symbols-outlined text-base">inventory_2</span>
                                    </button>
                                </form>
                                <?php endif; ?>

                                <!-- Delete -->
                                <form action="<?= base_url('/admin/stories/delete/' . $story['id']) ?>" method="POST" class="inline"
                                    onsubmit="return confirm('Hapus cerita ini? Tindakan tidak dapat dibatalkan.')">
                                    <?= csrf_field() ?>
                                    <button type="submit" title="Hapus"
                                        class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition">
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
                            <span class="material-symbols-outlined text-5xl block mb-2">menu_book</span>
                            Belum ada cerita
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function searchTable(tableId, query) {
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr.story-row');
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(query.toLowerCase()) ? '' : 'none';
    });
}

function filterStories() {
    const status = document.getElementById('filterStatus').value;
    document.querySelectorAll('#storyTable tbody tr.story-row').forEach(row => {
        row.style.display = (!status || row.dataset.status === status) ? '' : 'none';
    });
}
</script>

<?= $this->endSection() ?>
