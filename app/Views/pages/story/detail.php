<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<main class="max-w-6xl mx-auto px-6 py-8">
    <!-- Breadcrumb -->
    <nav class="text-xs text-slate-500 mb-6">
        <a href="<?= base_url() ?>" class="hover:text-slate-900">Home</a>
        <span class="mx-2">/</span>
        <a href="<?= base_url('/discover') ?>" class="hover:text-slate-900">Explore</a>
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
                <!-- Views count removed -->
                <span class="text-slate-300">•</span>
                <span class="text-sm text-emerald-700 font-semibold"><?= ucfirst(strtolower($story['publication_status'])) ?></span>
                <span class="text-slate-300">•</span>
                <span class="text-sm text-slate-700 font-semibold">
                    <?= isset($chapter_count) ? $chapter_count : ($story['total_chapters'] ?? 0) ?> chapters
                </span>
            </div>

            <p class="text-sm md:text-base text-slate-700 leading-relaxed italic mb-6">
                <?= esc($story['description'] ?? '') ?>
            </p>

            <div class="flex flex-wrap gap-3 mb-6">
                <?php if (!session()->get('isLoggedIn')): ?>
                    <button type="button" onclick="openModal('loginModal')" class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-slate-800 transition-all">
                        <span class="material-symbols-outlined">menu_book</span>
                        Read Now
                    </button>
                <?php else: ?>
                    <a href="<?= base_url('/chapter/' . $story['id']) ?>" class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-slate-800 transition-all">
                        <span class="material-symbols-outlined">menu_book</span>
                        Read Now
                    </a>
                <?php endif; ?>
                <?php if (!session()->get('isLoggedIn')): ?>
                    <button type="button" onclick="openModal('loginModal')" class="inline-flex items-center gap-2 bg-white border border-border text-slate-900 px-4 py-2 rounded-lg text-sm font-semibold hover:transition-all">
                        <span class="material-symbols-outlined">bookmark_add</span>
                        Add to Library
                    </button>
                    <button type="button" onclick="openModal('loginModal')" class="inline-flex items-center gap-2 bg-red-100 text-red-700 border border-red-200 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-200 transition-all">
                        <span class="material-symbols-outlined">flag</span>
                        Report Story
                    </button>
                <?php else: ?>
                    <button onclick="toggleLibraryAjax(<?= (int)$story['id'] ?>)" id="add-library-btn" class="inline-flex items-center gap-2 <?= $is_bookmarked ? 'bg-accent text-white' : 'bg-white border border-border text-slate-900' ?> px-4 py-2 rounded-lg text-sm font-semibold hover:transition-all">
                        <span class="material-symbols-outlined"><?= $is_bookmarked ? 'bookmark_remove' : 'bookmark_add' ?></span>
                        <?= $is_bookmarked ? 'Remove from Library' : 'Add to Library' ?>
                    </button>
                    <a href="<?= base_url('/report-story/create' . $story['id']) ?>" class="inline-flex items-center gap-2 bg-red-100 text-red-700 border border-red-200 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-200 transition-all" onclick="event.preventDefault(); openReportModal(<?= (int)$story['id'] ?>)">
                        <span class="material-symbols-outlined">flag</span>
                        Report Story
                    </a>
                <?php endif; ?>
            <script>
            function toggleLibraryAjax(storyId) {
                const btn = document.getElementById('add-library-btn');
                const isBookmarked = btn.classList.contains('bg-accent');
                btn.disabled = true;
                let url = isBookmarked
                    ? '<?= base_url('/story/') ?>' + storyId + '/remove-from-library'
                    : '<?= base_url('/story/') ?>' + storyId + '/add-to-library';
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    },
                    body: JSON.stringify({})
                })
                .then(res => res.json ? res.json() : res)
                .then(data => {
                    if (!isBookmarked) {
                        btn.classList.add('bg-accent', 'text-white');
                        btn.classList.remove('bg-white', 'border', 'border-border', 'text-slate-900');
                        btn.innerHTML = '<span class="material-symbols-outlined">bookmark_remove</span>Remove from Library';
                    } else {
                        btn.classList.remove('bg-accent', 'text-white');
                        btn.classList.add('bg-white', 'border', 'border-border', 'text-slate-900');
                        btn.innerHTML = '<span class="material-symbols-outlined">bookmark_add</span>Add to Library';
                    }
                    btn.disabled = false;
                    if (data && data.message) {
                        alert(data.message);
                    }
                })
                .catch(() => {
                    btn.disabled = false;
                    alert('Failed to update library.');
                });
            }
            </script>


            <div class="flex flex-wrap gap-2">
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
            <h2 class="text-xl md:text-2xl font-bold text-primary">Latest Chapters</h2>
            <a href="<?= base_url('/chapters/' . $story['id']) ?>" class="text-sm font-semibold text-accent hover:underline">See all →</a>
        </div>
        <div class="bg-white border border-border rounded-2xl shadow-sm">
            <ul class="divide-y divide-border">
                <?php if (!empty($chapters)): ?>
                    <?php foreach ($chapters as $chapter): ?>
                        <li class="p-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Chapter <?= (int)$chapter['chapter_number'] ?> • <?= esc($chapter['title']) ?></p>
                                <p class="text-xs text-slate-500">Updated <?= date('d M Y', strtotime($chapter['created_at'])) ?></p>
                            </div>
                            <?php if (!session()->get('isLoggedIn')): ?>
                                <button type="button" onclick="openModal('loginModal')" class="inline-flex items-center gap-1 text-xs font-bold text-accent hover:underline">
                                    Read <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </button>
                            <?php else: ?>
                                <a href="<?= base_url('/read-chapter/' . $chapter['id']) ?>" class="inline-flex items-center gap-1 text-xs font-bold text-accent hover:underline">
                                    Read <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <script>
                function openModal(modalId) {
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    }
                }
                </script>
                <?php else: ?>
                    <li class="p-4 text-sm text-slate-500">No chapters available yet.</li>
                <?php endif; ?>
            </ul>
        </div>
    </section>

    <!-- Reviews -->
    <section id="reviews" class="mb-16">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl md:text-2xl font-bold text-primary">Reader Reviews</h2>
            <?php if (session()->get('isLoggedIn')): ?>
                <?php if (empty($user_review)): ?>
                    <button onclick="openReviewModal()" class="text-sm font-semibold text-accent hover:underline">Write a review →</button>
                <?php else: ?>
                    <span class="text-xs text-slate-400 italic">You have already reviewed this story</span>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="reviews-list">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <?php $isOwner = session()->get('isLoggedIn') && session()->get('user_id') == $review['user_id']; ?>
                    <article class="p-5 bg-white border <?= $isOwner ? 'border-accent/40 bg-purple-50/30' : 'border-border' ?> rounded-2xl shadow-sm relative">
                        <div class="flex items-center justify-between mt-3 mb-2">
                            <div class="flex items-center gap-2">
                                <?php if (!empty($review['user_photo'])): ?>
                                    <img src="<?= profile_url($review['user_photo']) ?>" alt="<?= esc($review['user_name']) ?>" class="w-5 h-5 rounded-full object-cover" />
                                <?php else: ?>
                                    <div class="w-5 h-5 rounded-full bg-accent text-white flex items-center justify-center text-xs font-bold">
                                        <?= strtoupper(substr($review['user_name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <span class="text-xs text-slate-500 font-semibold"><?= esc($review['user_name']) ?></span>
                                <?php if ($isOwner): ?>
                                    <span class="text-[10px] bg-accent text-white px-1.5 py-0.5 rounded-full">You</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1 text-amber-500 text-xs font-semibold">
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <?= number_format($review['rating'] ?? 0, 1) ?>
                                </div>
                                <?php if ($isOwner): ?>
                                    <div class="flex items-center gap-1">
                                        <button onclick="openEditReviewModal(<?= $review['id'] ?>, <?= $review['rating'] ?>, `<?= esc($review['review'], 'js') ?>`)"
                                                class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-slate-100 transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-slate-400 text-sm">edit</span>
                                        </button>
                                        <button onclick="deleteReview(<?= $review['id'] ?>)"
                                                class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-red-50 transition-colors" title="Delete">
                                            <span class="material-symbols-outlined text-red-400 text-sm">delete</span>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <p class="text-sm text-slate-700 leading-relaxed mb-6"><?= esc($review['review']) ?></p>
                        <div class="absolute bottom-5 right-5 text-xs text-slate-400">
                            <?= date('d M Y', strtotime($review['created_at'])) ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3 text-center text-slate-500 text-sm p-8">
                    No reviews yet. Be the first to leave a review!
                </div>
            <?php endif; ?>
    </section>

    <!-- Related Stories -->
    <section id="related" class="mb-12">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl md:text-2xl font-bold text-primary">You might also like</h2>
            <a href="<?= base_url('/discover') ?>" class="text-sm font-semibold text-accent hover:underline">Explore more →</a>
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
                <div class="text-sm text-slate-500">No related stories found.</div>
            <?php endif; ?>
        </div>
    </section>
</main>



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
    alert('Review submitted successfully!');
    closeReviewModal();
}

function toggleLibrary(storyId) {
    const btn = document.getElementById('add-library-btn');
    const isBookmarked = btn.classList.contains('bg-accent');
    
    if (isBookmarked) {
        btn.classList.remove('bg-accent', 'text-white');
        btn.classList.add('bg-white', 'border', 'border-border', 'text-slate-900');
        btn.innerHTML = '<span class="material-symbols-outlined">bookmark_add</span>Add to Library';
    } else {
        btn.classList.add('bg-accent', 'text-white');
        btn.classList.remove('bg-white', 'border', 'border-border', 'text-slate-900');
        btn.innerHTML = '<span class="material-symbols-outlined">bookmark_remove</span>Remove from Library';
    }
}
</script>

<!-- Report Story Modal -->
<div id="reportStoryModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md shadow-2xl animate-[slideUp_0.25s_ease]">

        <!-- Header -->
        <div class="flex items-center gap-3 p-6 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-red-600 text-xl">flag</span>
            </div>
            <div class="flex-1">
                <h3 class="text-base font-bold text-slate-900">Report Story</h3>
                <p class="text-xs text-slate-400">Help us keep the community safe</p>
            </div>
            <button onclick="closeReportModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 transition-colors">
                <span class="material-symbols-outlined text-slate-500 text-lg">close</span>
            </button>
        </div>

        <!-- Form -->
        <form method="POST" action="<?= base_url('/report-story/submit') ?>" enctype="multipart/form-data" class="p-6 flex flex-col gap-5">
            <?= csrf_field() ?>
            <input type="hidden" name="story_id" id="report_story_id" value="">

            <!-- Reason -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Reason</label>
                <div class="relative">
                    <select name="report_reason" required
                            class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 pr-10 text-sm text-slate-900 focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition-all">
                        <option value="" disabled selected>Select a reason…</option>
                        <option value="spam">📢 Spam</option>
                        <option value="plagiarism">📋 Plagiarism</option>
                        <option value="hate_speech">🚫 Hate Speech</option>
                        <option value="harassment">😡 Harassment</option>
                        <option value="inappropriate_content">⚠️ Inappropriate Content</option>
                        <option value="misinformation">❌ Misinformation</option>
                        <option value="other">💬 Other</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xl">expand_more</span>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="flex items-center justify-between text-sm font-semibold text-slate-700 mb-1.5">
                    Description
                    <span class="text-xs font-normal text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">Optional</span>
                </label>
                <textarea name="description" rows="3" maxlength="1000"
                          placeholder="Describe the issue in more detail…"
                          oninput="document.getElementById('rs-char').textContent = this.value.length"
                          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition-all resize-none"></textarea>
                <div class="text-right text-xs text-slate-400 mt-1"><span id="rs-char">0</span>/1000</div>
            </div>

            <!-- Evidence Image -->
            <div>
                <label class="flex items-center justify-between text-sm font-semibold text-slate-700 mb-1.5">
                    Evidence Image
                    <span class="text-xs font-normal text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">Optional</span>
                </label>
                <label for="evidence_image"
                       class="flex items-center gap-3 px-4 py-3 bg-slate-50 border border-slate-200 border-dashed rounded-xl cursor-pointer hover:bg-slate-100 transition-colors">
                    <span class="material-symbols-outlined text-slate-400 text-xl">upload_file</span>
                    <span id="evidence-label" class="text-sm text-slate-400">Click to upload image (JPG, PNG, max 2MB)</span>
                </label>
                <input type="file" id="evidence_image" name="evidence_image"
                       accept="image/jpeg,image/png,image/jpg"
                       class="hidden"
                       onchange="document.getElementById('evidence-label').textContent = this.files[0]?.name ?? 'Click to upload image'" />
            </div>

            <!-- Actions -->
            <div class="flex gap-3 mt-1">
                <button type="button" onclick="closeReportModal()"
                        class="flex-1 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-2 flex-[2] flex items-center justify-center gap-2 py-2.5 rounded-xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition-all shadow-md shadow-red-200">
                    <span class="material-symbols-outlined text-base">send</span>
                    Submit Report
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes slideUp {
    from { opacity: 0; transform: translateY(16px) scale(0.98); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
</style>

<script>
function openReportModal(storyId) {
    document.getElementById('report_story_id').value = storyId;
    const modal = document.getElementById('reportStoryModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeReportModal() {
    document.getElementById('reportStoryModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close on backdrop click
document.getElementById('reportStoryModal').addEventListener('click', function(e) {
    if (e.target === this) closeReportModal();
});
</script>

<?php
// Inject URL & CSRF ke JS agar modal tidak bergantung pada $story
?>
<script>
    const storyReviewUrl = '<?= base_url('/story/' . $story['id'] . '/review') ?>';
    const reviewBaseUrl  = '<?= base_url('/review/') ?>';
    const csrfToken      = '<?= csrf_token() ?>';
    const csrfHash       = '<?= csrf_hash() ?>';
</script>

<?= $this->include('pages/review/create') ?>
<?= $this->include('pages/review/edit') ?>

<?= $this->endSection() ?>