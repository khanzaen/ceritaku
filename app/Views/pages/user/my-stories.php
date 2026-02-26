<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
.story-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.story-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}
.story-card .cover-img {
    transition: transform 0.3s ease;
}
.story-card:hover .cover-img {
    transform: scale(1.04);
}
</style>

<main class="max-w-6xl mx-auto px-6 py-10">

    <!-- Flash Messages -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-base">check_circle</span>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-base">error</span>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <p class="text-xs font-semibold tracking-widest text-accent uppercase mb-1">Dashboard</p>
            <h1 class="text-3xl font-bold text-primary">My Stories</h1>
            <p class="text-slate-400 text-sm mt-1">
                <?= count($published ?? []) ?> published &nbsp;·&nbsp; <?= count($drafts ?? []) ?> draft
            </p>
        </div>
        <a href="<?= site_url('create-story') ?>"
           class="bg-accent text-white px-5 py-2.5 rounded-xl hover:bg-purple-700 transition-colors inline-flex items-center gap-2 text-sm font-semibold shadow-sm">
            <span class="material-symbols-outlined text-base">add</span>
            Create New Story
        </a>
    </div>

    <?php $allStories = array_merge($published ?? [], $drafts ?? []); ?>

    <?php if(empty($allStories)): ?>
        <!-- Empty State -->
        <div class="text-center py-20 bg-white rounded-2xl border border-border">
            <span class="material-symbols-outlined text-[64px] text-slate-200 mb-4 block">menu_book</span>
            <h2 class="text-lg font-bold text-primary mb-2">No stories yet</h2>
            <p class="text-slate-400 text-sm mb-6">Start writing your first story and share it with the world.</p>
            <a href="<?= site_url('create-story') ?>"
               class="bg-accent text-white px-6 py-2.5 rounded-xl hover:bg-purple-700 transition-colors inline-flex items-center gap-2 text-sm font-semibold">
                <span class="material-symbols-outlined text-base">edit</span>
                Write a Story
            </a>
        </div>

    <?php else: ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php foreach($allStories as $story): ?>
                <?php $isDraft = $story['status'] !== 'PUBLISHED'; ?>

                <div class="story-card bg-white rounded-2xl border border-border overflow-hidden flex flex-col <?= $isDraft ? 'opacity-80' : '' ?>">

                    <!-- Cover -->
                    <div class="relative overflow-hidden h-44 bg-gradient-to-br from-purple-50 to-slate-100 flex-shrink-0">
                        <?php if($story['cover_image'] && file_exists(FCPATH . 'uploads/' . $story['cover_image'])): ?>
                            <img src="<?= base_url('uploads/' . $story['cover_image']) ?>"
                                 alt="<?= esc($story['title']) ?>"
                                 class="cover-img w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-[56px] text-slate-300">menu_book</span>
                            </div>
                        <?php endif; ?>

                        <!-- Draft overlay -->
                        <?php if($isDraft): ?>
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                <span class="text-white font-black text-xl tracking-widest uppercase border-2 border-white/70 px-4 py-1 rounded-lg rotate-[-8deg] opacity-90">
                                    Draft
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Content -->
                    <div class="p-4 flex flex-col flex-1">

                        <!-- Badge publication status -->
                        <div class="flex flex-wrap gap-1.5 mb-2">
                            <span class="px-2 py-0.5 bg-<?= $story['publication_badge']['color'] ?>-100 text-<?= $story['publication_badge']['color'] ?>-700 rounded-full text-[10px] font-semibold">
                                <?= $story['publication_badge']['text'] ?>
                            </span>
                        </div>

                        <h3 class="font-bold text-primary text-sm mb-1 line-clamp-1"><?= esc($story['title']) ?></h3>
                        <p class="text-xs text-slate-500 line-clamp-2 mb-4 flex-1"><?= esc($story['description']) ?></p>

                        <!-- Actions -->
                        <div class="flex gap-2 mt-auto">
                            <?php if(!$isDraft): ?>
                                <a href="<?= site_url('story/' . $story['id']) ?>"
                                   class="flex-1 text-center text-xs font-semibold py-2 rounded-lg bg-accent/10 text-accent hover:bg-accent/20 transition-colors">
                                    Read
                                </a>
                            <?php endif; ?>
                            <a href="<?= site_url('story/edit/' . $story['id']) ?>"
                               class="flex-1 text-center text-xs font-semibold py-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                                Edit
                            </a>
                            <button onclick="confirmDelete(<?= $story['id'] ?>, '<?= esc($story['title'], 'js') ?>')"
                                    class="flex-1 text-center text-xs font-semibold py-2 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition-colors">
                                Delete
                            </button>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</main>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-xl">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-red-500 text-base">delete</span>
            </div>
            <h3 class="text-base font-bold text-primary">Delete Story?</h3>
        </div>
        <p class="text-slate-500 text-sm mb-1">You're about to delete:</p>
        <p id="deleteStoryTitle" class="font-semibold text-primary text-sm mb-3 line-clamp-2"></p>
        <p class="text-slate-400 text-xs mb-6">This action cannot be undone. All chapters will also be deleted.</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm hover:bg-slate-50 transition-colors">
                Cancel
            </button>
            <form id="deleteForm" method="post" class="flex-1">
                <?= csrf_field() ?>
                <button type="submit"
                        class="w-full px-4 py-2 bg-red-500 text-white rounded-xl text-sm font-semibold hover:bg-red-600 transition-colors">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, title) {
    document.getElementById('deleteStoryTitle').textContent = title;
    document.getElementById('deleteForm').action = `<?= site_url('story/delete/') ?>${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>

<?= $this->endSection() ?>