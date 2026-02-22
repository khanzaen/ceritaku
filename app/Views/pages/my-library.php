<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
.story-card {
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}
.story-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}
.cover-inner {
    overflow: hidden;
    border-radius: 8px;
}
.cover-inner img {
    transition: transform 0.3s ease;
}
.story-card:hover .cover-inner img {
    transform: scale(1.04);
}
.progress-fill {
    height: 100%;
    border-radius: 9999px;
    background: #6c5ce7;
    width: 0%;
    transition: width 0.8s ease;
}
</style>

<main class="max-w-6xl mx-auto px-6 py-10">

    <!-- Header -->
    <div class="flex items-end justify-between mb-8">
        <div>
            <p class="text-xs font-semibold tracking-widest text-accent uppercase mb-1">Your collection</p>
            <h1 class="text-3xl md:text-4xl font-bold text-primary leading-none">My Library</h1>
        </div>
        <?php if (!empty($library)): ?>
        <span class="text-sm text-slate-400"><?= count($library) ?> <?= count($library) === 1 ? 'story' : 'stories' ?></span>
        <?php endif; ?>
    </div>

    <?php if (!empty($library)): ?>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
            <?php foreach ($library as $item): ?>

                <div class="story-card bg-white border border-border rounded-2xl p-2.5 flex flex-col group cursor-pointer"
                     onclick="window.location='<?= base_url('/story/' . $item['story_id']) ?>'">

                    <!-- Cover -->
                    <div class="cover-inner aspect-[3/4] mb-2 bg-slate-100">
                        <?php if (!empty($item['cover_image'])): ?>
                            <img src="<?= cover_url($item['cover_image']) ?>"
                                 alt="<?= esc($item['title']) ?>"
                                 class="w-full h-full object-cover" />
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-50 to-slate-100">
                                <span class="material-symbols-outlined text-[48px] text-purple-300">auto_stories</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Meta -->
                    <div class="flex flex-col flex-1">
                        <h2 class="text-xs font-bold text-primary mb-0.5 line-clamp-1 leading-snug">
                            <?= esc($item['title']) ?>
                        </h2>
                        <p class="text-[10px] text-slate-400 mb-1">by <?= esc($item['author_name']) ?></p>
                        <p class="text-[10px] text-slate-500 line-clamp-2 mb-2">
                            <?= !empty($item['description']) ? esc($item['description']) : 'No description available.' ?>
                        </p>

                        <!-- Progress -->
                        <div class="mt-auto">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] font-semibold text-accent"><?= $item['progress_percent'] ?>%</span>
                                <span class="text-[10px] text-slate-400">completed</span>
                            </div>
                            <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden mb-2">
                                <div class="progress-fill" data-width="<?= $item['progress_percent'] ?>"></div>
                            </div>

                            <!-- Button -->
                            <a href="<?= base_url('/story/' . $item['story_id']) ?>"
                               onclick="event.stopPropagation()"
                               class="block w-full text-center text-xs font-semibold py-2 rounded-xl bg-accent text-white hover:bg-purple-700 transition-colors">
                                <?= $item['progress_percent'] > 0 ? 'Continue Reading' : 'Start Reading' ?>
                            </a>
                        </div>
                    </div>

                </div>

            <?php endforeach; ?>
        </div>

    <?php else: ?>

        <!-- Empty state -->
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="mb-6">
                <span class="material-symbols-outlined text-[80px] text-purple-200">auto_stories</span>
            </div>
            <h2 class="text-xl font-bold text-primary mb-2">Your library is empty</h2>
            <p class="text-slate-400 text-sm mb-6">Bookmark stories to save them here and track your progress.</p>
            <a href="<?= base_url('/discover') ?>"
               class="px-6 py-2.5 bg-accent text-white text-sm font-semibold rounded-2xl hover:bg-purple-700 transition-all hover:scale-105 shadow-md shadow-purple-200">
                Discover Stories
            </a>
        </div>

    <?php endif; ?>

</main>

<script>
// Animate progress bars after cards are revealed
document.addEventListener('DOMContentLoaded', () => {
    const fills = document.querySelectorAll('.progress-fill');
    // Small delay so the card entrance animation plays first
    setTimeout(() => {
        fills.forEach(fill => {
            fill.style.width = fill.dataset.width + '%';
        });
    }, 400);
});
</script>

<?= $this->endSection() ?>