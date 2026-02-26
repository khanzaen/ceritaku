<!-- Write Review Modal -->
<div id="reviewModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md shadow-2xl animate-[slideUp_0.25s_ease]">

        <!-- Header -->
        <div class="flex items-center gap-3 p-6 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-accent text-xl">rate_review</span>
            </div>
            <div class="flex-1">
                <h3 class="text-base font-bold text-slate-900">Write a Review</h3>
                <p class="text-xs text-slate-400">Share your thoughts about this story</p>
            </div>
            <button onclick="closeReviewModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 transition-colors">
                <span class="material-symbols-outlined text-slate-500 text-lg">close</span>
            </button>
        </div>

        <!-- Form -->
        <div class="p-6 flex flex-col gap-5">

            <!-- Star Rating -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Rating</label>
                <div class="flex items-center gap-1" id="star-rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <button type="button" onclick="setRating(<?= $i ?>)"
                                data-star="<?= $i ?>"
                                class="star-btn text-slate-200 hover:text-yellow-400 transition-colors">
                            <span class="material-symbols-outlined" style="font-size:32px">star</span>
                        </button>
                    <?php endfor; ?>
                    <span id="rating-label" class="ml-2 text-sm text-slate-400">Select rating</span>
                </div>
                <input type="hidden" id="review-rating" value="">
            </div>

            <!-- Review Text -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Your Review</label>
                <textarea id="review-text" rows="4" maxlength="1000"
                          placeholder="Write your review here… (min. 10 characters)"
                          oninput="document.getElementById('review-char').textContent = this.value.length"
                          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all resize-none"></textarea>
                <div class="text-right text-xs text-slate-400 mt-1"><span id="review-char">0</span>/1000</div>
            </div>

            <!-- Error -->
            <p id="review-error" class="hidden text-sm text-red-500 bg-red-50 px-3 py-2 rounded-lg"></p>

            <!-- Actions -->
            <div class="flex gap-3">
                <button type="button" onclick="closeReviewModal()"
                        class="flex-1 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                    Cancel
                </button>
                <button type="button" onclick="submitReview()"
                        class="flex-[2] flex items-center justify-center gap-2 py-2.5 rounded-xl bg-accent text-white text-sm font-semibold hover:bg-purple-700 transition-all shadow-md shadow-purple-200">
                    <span class="material-symbols-outlined text-base">send</span>
                    Submit Review
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes slideUp {
    from { opacity: 0; transform: translateY(16px) scale(0.98); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
</style>

<script>
let selectedRating = 0;
const ratingLabels = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];

function openReviewModal() {
    <?php if (!session()->get('isLoggedIn')): ?>
        openModal('loginModal'); return;
    <?php endif; ?>
    document.getElementById('review-text').value = '';
    document.getElementById('review-char').textContent = '0';
    document.getElementById('review-error').classList.add('hidden');
    setRating(0);
    document.getElementById('reviewModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function setRating(value) {
    selectedRating = value;
    document.getElementById('review-rating').value = value;
    document.getElementById('rating-label').textContent = value ? ratingLabels[value] : 'Select rating';
    document.querySelectorAll('.star-btn').forEach(btn => {
        btn.style.color = parseInt(btn.dataset.star) <= value ? '#f59e0b' : '';
    });
}

function submitReview() {
    const review  = document.getElementById('review-text').value.trim();
    const rating  = document.getElementById('review-rating').value;
    const errorEl = document.getElementById('review-error');

    if (!rating || rating == 0) { errorEl.textContent = 'Please select a rating.'; errorEl.classList.remove('hidden'); return; }
    if (review.length < 10)     { errorEl.textContent = 'Review must be at least 10 characters.'; errorEl.classList.remove('hidden'); return; }
    errorEl.classList.add('hidden');

    fetch(storyReviewUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: new URLSearchParams({ [csrfToken]: csrfHash, review, rating })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) { closeReviewModal(); location.reload(); }
        else { errorEl.textContent = data.message; errorEl.classList.remove('hidden'); }
    });
}

document.getElementById('reviewModal').addEventListener('click', function(e) {
    if (e.target === this) closeReviewModal();
});
</script>
