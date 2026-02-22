<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Chapter Management</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola semua chapter dari setiap cerita</p>
    </div>
</div>

<!-- Filter Bar -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-6 flex flex-wrap gap-3 items-center">
    <div class="relative flex-1 min-w-[200px]">
        <input type="text" id="searchChapter" onkeyup="searchTable('chapterTable', this.value)"
            placeholder="Cari judul chapter / cerita..."
            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
        <span class="material-symbols-outlined absolute left-2.5 top-2.5 text-gray-400 text-base">search</span>
    </div>

    <select id="filterStory" onchange="filterByStory()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-purple-400">
        <option value="">Semua Cerita</option>
        <?php if (!empty($stories)): ?>
            <?php foreach ($stories as $story): ?>
                <option value="<?= esc($story['id']) ?>"><?= esc($story['title']) ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>

<!-- Table -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800">Daftar Chapter</h3>
    </div>

    <div class="overflow-x-auto">
        <table id="chapterTable" class="w-full text-sm">
            <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left">Cerita</th>
                    <th class="px-4 py-3 text-left">No.</th>
                    <th class="px-4 py-3 text-left">Judul Chapter</th>
                    <th class="px-4 py-3 text-left">Kata</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($chapters)): ?>
                    <?php foreach ($chapters as $chapter): ?>
                    <tr class="hover:bg-gray-50 transition chapter-row" data-story-id="<?= esc($chapter['story_id'] ?? '') ?>">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800 truncate max-w-[160px]"><?= esc($chapter['story_title'] ?? '-') ?></p>
                        </td>
                        <td class="px-4 py-3 text-gray-500 font-mono"><?= esc($chapter['chapter_number'] ?? '-') ?></td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-900 truncate max-w-[200px]"><?= esc($chapter['title']) ?></p>
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            <?= number_format(str_word_count(strip_tags($chapter['content'] ?? ''))) ?> kata
                        </td>
                        <td class="px-4 py-3">
                            <?php $pub = !empty($chapter['published_at']); ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium <?= $pub ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' ?>">
                                <?= $pub ? 'Published' : 'Draft' ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs"><?= date('d M Y', strtotime($chapter['created_at'])) ?></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1">
                                <!-- View -->
                                <a href="<?= base_url('/stories/' . ($chapter['story_id'] ?? '') . '/chapters/' . $chapter['id']) ?>"
                                    target="_blank"
                                    class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition" title="Lihat">
                                    <span class="material-symbols-outlined text-base">visibility</span>
                                </a>

                                <!-- Delete -->
                                <form action="<?= base_url('/admin/chapters/delete/' . $chapter['id']) ?>" method="POST" class="inline"
                                    onsubmit="return confirm('Hapus chapter ini?')">
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
                        <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                            <span class="material-symbols-outlined text-5xl block mb-2">article</span>
                            Belum ada chapter
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function searchTable(tableId, query) {
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(query.toLowerCase()) ? '' : 'none';
    });
}

function filterByStory() {
    const storyId = document.getElementById('filterStory').value;
    document.querySelectorAll('#chapterTable tbody tr.chapter-row').forEach(row => {
        row.style.display = (!storyId || row.dataset.storyId === storyId) ? '' : 'none';
    });
}
</script>

<?= $this->endSection() ?>
