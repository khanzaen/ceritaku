<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<main class="max-w-6xl mx-auto px-6 py-8">
    <!-- Breadcrumb -->
    <nav class="text-xs text-slate-500 mb-6">
        <a href="<?= base_url() ?>" class="hover:text-slate-900">Beranda</a>
        <span class="mx-2">/</span>
        <a href="<?= base_url('/discover') ?>" class="hover:text-slate-900">Jelajahi</a>
        <span class="mx-2">/</span>
        <span class="text-slate-700"><?= esc($story['title']) ?></span>
    </nav>

    <!-- Header Section -->
    <section class="grid md:grid-cols-3 gap-8 mb-10">
        <!-- Cover -->
        <div class="md:col-span-1">
            <div class="aspect-[2/3] bg-white rounded-xl overflow-hidden book-card-shadow border border-border">
                <?php if (!empty($story['cover_image'])): ?>
                    <img src="<?= cover_url($story['cover_image']) ?>" alt="<?= esc($story['title']) ?> cover" class="w-full h-full object-cover" />
                <?php else: ?>
                    <div class="w-full h-full bg-gradient-to-br from-purple-200 to-purple-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-400 text-[48px]">auto_stories</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Meta and Actions -->
        <div class="md:col-span-2">
            <div class="flex items-center gap-2 mb-3">
                <span class="px-2 py-0.5 bg-slate-100 text-[10px] font-bold uppercase tracking-wider text-slate-600 rounded">
                    <?= esc(trim(explode(', ', $story['genres'] ?? 'Fiction')[0])) ?>
                </span>
                <span class="text-slate-300">|</span>
                <span class="text-[12px] text-slate-600">Updated <?= date('d M Y', strtotime($story['updated_at'])) ?></span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-primary mb-2"><?= esc($story['title']) ?></h1>
            <p class="text-slate-600 mb-4">by <span class="text-slate-800 font-medium">
                <a href="<?= base_url('/user/' . $story['author_id']) ?>" class="hover:text-accent transition-colors">
                    <?= esc($story['author_name']) ?>
                </a>
            </span></p>

            <div class="flex items-center gap-3 mb-6">
                <div class="flex items-center gap-1 text-amber-600">
                    <span class="material-symbols-outlined">star</span>
                    <span class="text-sm font-semibold"><?= number_format($story['avg_rating'] ?? 0, 1) ?></span>
                </div>
                <span class="text-slate-300">•</span>
                <span class="text-sm text-slate-600"><?= number_format($story['total_views'] ?? 0) ?> tampilan</span>
                <span class="text-slate-300">•</span>
                <span class="text-sm text-emerald-700 font-semibold"><?= ucfirst(strtolower($story['status'])) ?></span>
            </div>

            <p class="text-sm md:text-base text-slate-700 leading-relaxed italic mb-6">
                <?= esc($story['description'] ?? '') ?>
            </p>

            <div class="flex flex-wrap gap-3 mb-6">
                <a href="<?= base_url('/chapter/' . $story['id']) ?>" class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-slate-800 transition-all">
                    <span class="material-symbols-outlined">menu_book</span>
                    Baca Sekarang
                </a>
                <button onclick="toggleLibrary(<?= (int)$story['id'] ?>)" id="add-library-btn" class="inline-flex items-center gap-2 <?= $is_bookmarked ? 'bg-accent text-white' : 'bg-white border border-border text-slate-900' ?> px-4 py-2 rounded-lg text-sm font-semibold hover:transition-all">
                    <span class="material-symbols-outlined"><?= $is_bookmarked ? 'bookmark_remove' : 'bookmark_add' ?></span>
                    <?= $is_bookmarked ? 'Hapus dari Perpustakaan' : 'Tambah ke Perpustakaan' ?>
                </button>
                <button class="inline-flex items-center gap-2 bg-white border border-border text-slate-900 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-all">
                    <span class="material-symbols-outlined">share</span>
                    Bagikan
                </button>
            </div>

            <div class="flex flex-wrap gap-2">
                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium">Total Bab: <?= $story['total_chapters'] ?? 0 ?></span>
                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium">Rating: <?= $story['total_ratings'] ?? 0 ?></span>
            </div>
        </div>
    </section>

    <!-- Tags/Genres -->
    <section class="mb-10">
        <div class="flex flex-wrap gap-2">
            <?php 
            $genres = array_filter(array_map('trim', explode(',', $story['genres'] ?? '')));
            if (!empty($genres)): 
                foreach ($genres as $genre): 
            ?>
                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium"><?= esc($genre) ?></span>
            <?php 
                endforeach; 
            else: 
            ?>
                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium">General</span>
            <?php endif; ?>
        </div>
    </section>

    <!-- Chapters -->
    <section id="chapters" class="mb-14">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl md:text-2xl font-bold text-primary">Bab Terbaru</h2>
            <a href="<?= base_url('/chapters/' . $story['id']) ?>" class="text-sm font-semibold text-accent hover:underline">Lihat semua →</a>
        </div>
        <div class="bg-white border border-border rounded-2xl shadow-sm">
            <ul class="divide-y divide-border">
                <?php if (!empty($chapters)): ?>
                    <?php foreach ($chapters as $chapter): ?>
                        <li class="p-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Bab <?= (int)$chapter['chapter_number'] ?> • <?= esc($chapter['title']) ?></p>
                                <p class="text-xs text-slate-500">Updated <?= date('d M Y', strtotime($chapter['created_at'])) ?></p>
                            </div>
                            <a href="<?= base_url('/read/' . $story['id'] . '/' . $chapter['chapter_number']) ?>" class="inline-flex items-center gap-1 text-xs font-bold text-accent hover:underline">
                                Baca <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="p-4 text-sm text-slate-500">Belum ada bab yang tersedia.</li>
                <?php endif; ?>
            </ul>
        </div>
    </section>

    <!-- Reviews -->
    <section id="reviews" class="mb-16">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl md:text-2xl font-bold text-primary">Ulasan Pembaca</h2>
            <?php if (session()->get('isLoggedIn')): ?>
                <button onclick="openReviewModal()" class="text-sm font-semibold text-accent hover:underline">Tulis ulasan →</button>
            <?php else: ?>
                <a href="<?= base_url('/login') ?>" class="text-sm font-semibold text-accent hover:underline">Lihat ulasan →</a>
            <?php endif; ?>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="reviews-list">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <article class="p-5 bg-white border border-border rounded-2xl shadow-sm">
                        <p class="text-sm text-slate-700 leading-relaxed"><?= esc($review['review']) ?></p>
                        <div class="flex items-center gap-2 mt-3">
                            <?php if (!empty($review['user_photo'])): ?>
                                <img src="<?= profile_url($review['user_photo']) ?>" alt="<?= esc($review['user_name']) ?>" class="w-5 h-5 rounded-full object-cover" />
                            <?php else: ?>
                                <div class="w-5 h-5 rounded-full bg-accent text-white flex items-center justify-center text-xs font-bold">
                                    <?= strtoupper(substr($review['user_name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <p class="text-xs text-slate-500">
                                <span class="font-semibold"><?= esc($review['user_name']) ?></span> • <?= date('d M Y', strtotime($review['created_at'])) ?>
                            </p>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3 text-center text-slate-500 text-sm p-8">
                    Belum ada ulasan. Jadilah yang pertama memberikan ulasan!
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Related Stories -->
    <section id="related" class="mb-12">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl md:text-2xl font-bold text-primary">Mungkin Anda juga suka</h2>
            <a href="<?= base_url('/discover') ?>" class="text-sm font-semibold text-accent hover:underline">Jelajahi lainnya →</a>
        </div>
        <div class="flex flex-wrap gap-3">
            <?php if (!empty($related_stories)): ?>
                <?php foreach ($related_stories as $item): ?>
                    <a href="<?= base_url('/story/' . $item['id']) ?>" class="block">
                        <div class="p-3 bg-white border border-border rounded-xl shadow-sm w-[180px] flex-none hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                            <div class="w-full aspect-[2/3] rounded overflow-hidden bg-slate-100 book-card-shadow mb-2">
                                <?php if (!empty($item['cover_image'])): ?>
                                    <img src="<?= cover_url($item['cover_image']) ?>" alt="<?= esc($item['title']) ?>" class="w-full h-full object-cover" />
                                <?php else: ?>
                                    <div class="w-full h-full bg-gradient-to-br from-purple-200 to-purple-100 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-purple-400 text-[32px]">auto_stories</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <h3 class="text-xs font-bold text-primary leading-tight line-clamp-2"><?= esc($item['title']) ?></h3>
                            <p class="text-[10px] text-slate-500">
                                <?= esc(trim(explode(', ', $item['genres'] ?? 'Fiction')[0])) ?> • <?= esc($item['author_name']) ?>
                            </p>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-sm text-slate-500">Tidak ada cerita terkait yang ditemukan.</div>
            <?php endif; ?>
        </div>
    </section>
</main>

<!-- Review Modal -->
<div id="review-modal" class="modal-overlay" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 md:p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl md:text-2xl font-bold text-primary">Tulis Ulasan Anda</h3>
            <button onclick="closeReviewModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="review-form" onsubmit="submitReview(event, <?= (int)$story['id'] ?>)" class="space-y-6">
            <!-- Review Text -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-3">Ulasan Anda</label>
                <textarea name="review" id="review-text" rows="4" placeholder="Bagikan pendapat Anda tentang cerita ini..." maxlength="1000" class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 resize-none text-slate-700"></textarea>
                <div class="flex justify-between mt-2">
                    <p id="char-count" class="text-xs text-slate-500">0/1000</p>
                    <p id="review-error" class="text-xs text-red-600" style="display: none;">Ulasan harus antara 10-1000 karakter</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 justify-end pt-4">
                <button type="button" onclick="closeReviewModal()" class="px-4 py-2 rounded-lg border border-border text-slate-700 font-semibold hover:bg-slate-50 transition-all">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-accent text-white font-semibold hover:bg-purple-700 transition-all">Kirim Ulasan</button>
            </div>
        </form>
    </div>
</div>

<style>
.modal-overlay {
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
    padding: 1rem;
}
</style>

<script>
// Character count
document.getElementById('review-text')?.addEventListener('input', (e) => {
    document.getElementById('char-count').textContent = e.target.value.length + '/1000';
});

function openReviewModal() {
    document.getElementById('review-modal').style.display = 'flex';
}

function closeReviewModal() {
    document.getElementById('review-modal').style.display = 'none';
}

function submitReview(e, storyId) {
    e.preventDefault();
    const review = document.getElementById('review-text').value;
    
    if (review.length < 10 || review.length > 1000) {
        document.getElementById('review-error').style.display = 'block';
        return;
    }
    
    // Submit review via API
    alert('Ulasan berhasil dikirim!');
    closeReviewModal();
}

function toggleLibrary(storyId) {
    const btn = document.getElementById('add-library-btn');
    const isBookmarked = btn.classList.contains('bg-accent');
    
    if (isBookmarked) {
        btn.classList.remove('bg-accent', 'text-white');
        btn.classList.add('bg-white', 'border', 'border-border', 'text-slate-900');
        btn.innerHTML = '<span class="material-symbols-outlined">bookmark_add</span>Tambah ke Perpustakaan';
    } else {
        btn.classList.add('bg-accent', 'text-white');
        btn.classList.remove('bg-white', 'border', 'border-border', 'text-slate-900');
        btn.innerHTML = '<span class="material-symbols-outlined">bookmark_remove</span>Hapus dari Perpustakaan';
    }
}
</script>

<?= $this->endSection() ?>
