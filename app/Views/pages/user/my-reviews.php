<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
.review-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.review-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.07);
}
.star-rating {
    display: inline-flex;
    gap: 2px;
}
.star-filled { color: #f59e0b; }
.star-empty  { color: #e2e8f0; }

/* Edit modal animation */
#editModal .modal-box {
    transition: transform 0.25s cubic-bezier(.4,0,.2,1), opacity 0.2s;
}
#editModal.hidden .modal-box {
    transform: translateY(20px);
    opacity: 0;
}
</style>

<main class="max-w-4xl mx-auto px-6 py-10">

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
            <h1 class="text-3xl font-bold text-primary">My Reviews</h1>
            <p class="text-slate-400 text-sm mt-1">
                <?= count($reviews ?? []) ?> review<?= count($reviews ?? []) != 1 ? 's' : '' ?> written
            </p>
        </div>
        <a href="<?= site_url('discover') ?>"
           class="inline-flex items-center gap-2 text-sm font-semibold text-accent border border-accent/30 bg-accent/5 hover:bg-accent/10 px-4 py-2.5 rounded-xl transition-colors">
            <span class="material-symbols-outlined text-base">explore</span>
            Discover Stories
        </a>
    </div>

    <?php if (empty($reviews)): ?>
        <!-- Empty State -->
        <div class="text-center py-20 bg-white rounded-2xl border border-border">
            <span class="material-symbols-outlined text-[64px] text-slate-200 mb-4 block">rate_review</span>
            <h2 class="text-lg font-bold text-primary mb-2">No reviews yet</h2>
            <p class="text-slate-400 text-sm mb-6">Start reading and share your thoughts on stories you love.</p>
            <a href="<?= site_url('discover') ?>"
               class="bg-accent text-white px-6 py-2.5 rounded-xl hover:bg-purple-700 transition-colors inline-flex items-center gap-2 text-sm font-semibold">
                <span class="material-symbols-outlined text-base">explore</span>
                Browse Stories
            </a>
        </div>

    <?php else: ?>

        <div class="space-y-4">
            <?php foreach($reviews as $review): ?>
                <div class="review-card bg-white rounded-2xl border border-border overflow-hidden"
                     id="review-card-<?= $review['id'] ?>">

                    <div class="flex gap-4 p-5">

                        <!-- Cover Thumbnail -->
                        <a href="<?= site_url('story/' . $review['story_id']) ?>"
                           class="flex-shrink-0 w-16 h-24 rounded-lg overflow-hidden bg-gradient-to-br from-purple-50 to-slate-100 block">
                            <?php if (!empty($review['cover_image'])): ?>
                                <img src="<?= base_url('uploads/' . $review['cover_image']) ?>"
                                     alt="<?= esc($review['story_title']) ?>"
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-2xl text-slate-300">menu_book</span>
                                </div>
                            <?php endif; ?>
                        </a>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">

                            <!-- Top row: story title + date -->
                            <div class="flex flex-wrap items-start justify-between gap-2 mb-2">
                                <div>
                                    <a href="<?= site_url('story/' . $review['story_id']) ?>"
                                       class="font-bold text-primary text-sm hover:text-accent transition-colors line-clamp-1">
                                        <?= esc($review['story_title']) ?>
                                    </a>
                                    <?php if (!empty($review['author_name'])): ?>
                                        <p class="text-xs text-slate-400 mt-0.5">by <?= esc($review['author_name']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <span class="text-[11px] text-slate-400 flex-shrink-0">
                                    <?= date('d M Y', strtotime($review['created_at'])) ?>
                                </span>
                            </div>

                            <!-- Star Rating -->
                            <div class="star-rating mb-2" aria-label="Rating: <?= $review['rating'] ?> out of 5">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="material-symbols-outlined text-base <?= $i <= $review['rating'] ? 'star-filled' : 'star-empty' ?>"
                                          style="font-variation-settings: 'FILL' <?= $i <= $review['rating'] ? '1' : '0' ?>, 'wght' 400, 'GRAD' 0, 'opsz' 20;">
                                        star
                                    </span>
                                <?php endfor; ?>
                                <span class="ml-1 text-xs font-semibold text-amber-600"><?= number_format($review['rating'], 1) ?></span>
                            </div>

                            <!-- Review Text -->
                            <p class="text-sm text-slate-600 leading-relaxed line-clamp-3 review-text-<?= $review['id'] ?>">
                                <?= esc($review['review']) ?>
                            </p>

                            <!-- Likes + Actions -->
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
                                <div class="flex items-center gap-1 text-xs text-slate-400">
                                    <span class="material-symbols-outlined text-sm"
                                          style="font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 20;">
                                        thumb_up
                                    </span>
                                    <span><?= $review['likes_count'] ?? 0 ?> helpful</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button onclick="openEditModal(
                                                <?= $review['id'] ?>,
                                                <?= $review['rating'] ?>,
                                                <?= json_encode($review['review']) ?>
                                            )"
                                            class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 hover:text-accent px-3 py-1.5 rounded-lg hover:bg-accent/5 transition-colors">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                        Edit
                                    </button>
                                    <button onclick="confirmDelete(<?= $review['id'] ?>, '<?= esc($review['story_title'], 'js') ?>')"
                                            class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 hover:text-red-500 px-3 py-1.5 rounded-lg hover:bg-red-50 transition-colors">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                        Delete
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</main>

<!-- ===================== EDIT MODAL ===================== -->
<div id="editModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
    <div class="modal-box bg-white rounded-2xl p-6 max-w-lg w-full shadow-xl">

        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-bold text-primary">Edit Review</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Star Picker -->
        <label class="block text-xs font-semibold text-slate-600 mb-2">Rating</label>
        <div class="flex gap-1 mb-4" id="editStarPicker">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <button type="button"
                        onclick="setEditRating(<?= $i ?>)"
                        class="edit-star text-2xl transition-transform hover:scale-110 focus:outline-none"
                        data-value="<?= $i ?>"
                        aria-label="<?= $i ?> stars">
                    <span class="material-symbols-outlined text-3xl star-empty"
                          style="font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 28;">
                        star
                    </span>
                </button>
            <?php endfor; ?>
        </div>

        <!-- Review Text -->
        <label for="editReviewText" class="block text-xs font-semibold text-slate-600 mb-2">Review</label>
        <textarea id="editReviewText"
                  rows="5"
                  placeholder="Share your thoughts about this story..."
                  class="w-full px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm resize-none"></textarea>
        <p class="text-xs text-slate-400 mt-1">Minimum 10 characters</p>

        <div class="flex gap-3 mt-5">
            <button onclick="closeEditModal()"
                    class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-600 rounded-xl text-sm hover:bg-slate-50 transition-colors">
                Cancel
            </button>
            <button onclick="submitEdit()"
                    class="flex-1 px-4 py-2.5 bg-accent text-white rounded-xl text-sm font-semibold hover:bg-purple-700 transition-colors flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-base">save</span>
                Save Changes
            </button>
        </div>

    </div>
</div>

<!-- ===================== DELETE MODAL ===================== -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-xl">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-red-500 text-base">delete</span>
            </div>
            <h3 class="text-base font-bold text-primary">Delete Review?</h3>
        </div>
        <p class="text-slate-500 text-sm mb-1">Your review for:</p>
        <p id="deleteStoryTitle" class="font-semibold text-primary text-sm mb-4 line-clamp-2"></p>
        <p class="text-slate-400 text-xs mb-6">This action cannot be undone.</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm hover:bg-slate-50 transition-colors">
                Cancel
            </button>
            <button id="confirmDeleteBtn"
                    class="flex-1 px-4 py-2 bg-red-500 text-white rounded-xl text-sm font-semibold hover:bg-red-600 transition-colors">
                Delete
            </button>
        </div>
    </div>
</div>

<script>
// ─── CSRF ───────────────────────────────────────────────
const CSRF_TOKEN_NAME = '<?= csrf_token() ?>';
const CSRF_TOKEN_HASH = '<?= csrf_hash() ?>';

// ─── EDIT MODAL ─────────────────────────────────────────
let currentEditId   = null;
let currentEditRating = 0;

function openEditModal(id, rating, reviewText) {
    currentEditId = id;
    document.getElementById('editReviewText').value = reviewText;
    setEditRating(rating);
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function setEditRating(val) {
    currentEditRating = val;
    document.querySelectorAll('.edit-star').forEach(btn => {
        const star = btn.querySelector('.material-symbols-outlined');
        const v    = parseInt(btn.dataset.value);
        if (v <= val) {
            star.style.fontVariationSettings = "'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 28";
            star.classList.remove('star-empty');
            star.classList.add('star-filled');
        } else {
            star.style.fontVariationSettings = "'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 28";
            star.classList.remove('star-filled');
            star.classList.add('star-empty');
        }
    });
}

async function submitEdit() {
    const reviewText = document.getElementById('editReviewText').value.trim();

    if (reviewText.length < 10) {
        alert('Review must be at least 10 characters.');
        return;
    }
    if (currentEditRating < 1) {
        alert('Please select a rating.');
        return;
    }

    try {
        const res = await fetch(`<?= site_url('review/update/') ?>${currentEditId}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                [CSRF_TOKEN_NAME]: CSRF_TOKEN_HASH,
                review: reviewText,
                rating: currentEditRating
            })
        });

        const data = await res.json();

        if (data.success) {
            closeEditModal();
            showToast('Review updated successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            alert(data.message || 'Failed to update review.');
        }
    } catch (e) {
        alert('An error occurred. Please try again.');
    }
}

// ─── DELETE MODAL ────────────────────────────────────────
let currentDeleteId = null;

function confirmDelete(id, storyTitle) {
    currentDeleteId = id;
    document.getElementById('deleteStoryTitle').textContent = storyTitle;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

document.getElementById('confirmDeleteBtn').addEventListener('click', async function () {
    if (!currentDeleteId) return;

    try {
        const res = await fetch(`<?= site_url('review/delete/') ?>${currentDeleteId}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ [CSRF_TOKEN_NAME]: CSRF_TOKEN_HASH })
        });

        const data = await res.json();

        if (data.success) {
            closeDeleteModal();
            const card = document.getElementById('review-card-' + currentDeleteId);
            if (card) {
                card.style.transition = 'opacity 0.3s, transform 0.3s';
                card.style.opacity = '0';
                card.style.transform = 'translateX(20px)';
                setTimeout(() => card.remove(), 300);
            }
            showToast('Review deleted.', 'error');
        } else {
            alert(data.message || 'Failed to delete review.');
        }
    } catch (e) {
        alert('An error occurred. Please try again.');
    }
});

// ─── CLOSE MODALS ON BACKDROP CLICK ─────────────────────
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

// ─── TOAST NOTIFICATION ──────────────────────────────────
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    const bg    = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    toast.className = `fixed bottom-6 right-6 z-[100] ${bg} text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-lg flex items-center gap-2 transition-all`;
    toast.innerHTML = `
        <span class="material-symbols-outlined text-base">${type === 'success' ? 'check_circle' : 'delete'}</span>
        ${message}
    `;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        setTimeout(() => toast.remove(), 300);
    }, 2500);
}
</script>

<?= $this->endSection() ?>