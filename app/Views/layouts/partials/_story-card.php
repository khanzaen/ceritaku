<div class="story-card bg-white rounded-2xl border border-border overflow-hidden flex flex-col">

    <!-- Cover -->
    <div class="overflow-hidden h-44 bg-gradient-to-br from-purple-50 to-slate-100 flex-shrink-0">
        <?php if($story['cover_image'] && file_exists(FCPATH . 'uploads/' . $story['cover_image'])): ?>
            <img src="<?= base_url('uploads/' . $story['cover_image']) ?>"
                 alt="<?= esc($story['title']) ?>"
                 class="cover-img w-full h-full object-cover">
        <?php else: ?>
            <div class="w-full h-full flex items-center justify-center">
                <span class="material-symbols-outlined text-[56px] text-slate-300">menu_book</span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Content -->
    <div class="p-4 flex flex-col flex-1">

        <!-- Publication status badge -->
        <div class="flex flex-wrap gap-1.5 mb-2">
            <span class="px-2 py-0.5 bg-<?= $story['publication_badge']['color'] ?>-100 text-<?= $story['publication_badge']['color'] ?>-700 rounded-full text-[10px] font-semibold">
                <?= $story['publication_badge']['text'] ?>
            </span>
        </div>

        <h3 class="font-bold text-primary text-sm mb-1 line-clamp-1"><?= esc($story['title']) ?></h3>
        <p class="text-xs text-slate-500 line-clamp-2 mb-4 flex-1"><?= esc($story['description']) ?></p>

        <!-- Actions -->
        <div class="flex gap-2 mt-auto">
            <?php if($story['status'] === 'PUBLISHED'): ?>
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
