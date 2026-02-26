<!-- Edit Review Modal -->
<div id="editReviewModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md shadow-2xl animate-[slideUp_0.25s_ease]">

        <!-- Header -->
        <div class="flex items-center gap-3 p-6 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-accent text-xl">edit</span>
            </div>
            <div class="flex-1">
                <h3 class="text-base font-bold text-slate-900">Edit Your Review</h3>
                <p class="text-xs text-slate-400">Update your thoughts about this story</p>
            </div>
            <button onclick="closeEditReviewModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 transition-colors">
                <span class="material-symbols-outlined text-slate-500 text-lg">close</span>
            </button>
        </div>

        <!-- Form -->
        <div class="p-6 flex flex-col gap-5">
            <input type="hidden" id="edit-review-id" value="">

            <!-- Star Rating -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Rating</label>
                <div class="flex items-center gap-1" id="edit-star-rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <button type="button" onclick="setEditRating(<?= $i ?>)"
                                data-star="<?= $i ?>"
                                class="edit-star-btn text-slate-200 hover:text-yellow-400 transition-colors">
                            <span class="material-symbols-outlined" style="font-size:32px">star</span>
                        </button>
                    <?php endfor; ?>
                    <span id="edit-rating-label" class="ml-2 text-sm text-slate-400">Select rating</span>
                </div>
                <input type="hidden" id="edit-review-rating" value="">
            </div>

            <!-- Review Text -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Your Review</label>
                <textarea id="edit-review-text" rows="4" maxlength="1000"
                          placeholder="Write your review here… (min. 10 characters)"
                          oninput="document.getElementById('edit-review-char').textContent = this.value.length"
                          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all resize-none"></textarea>
                <div class="text-right text-xs text-slate-400 mt-1"><span id="edit-review-char">0</span>/1000</div>
            </div>

            <!-- Error -->
            <p id="edit-review-error" class="hidden text-sm text-red-500 bg-red-50 px-3 py-2 rounded-lg"></p>

            <!-- Actions -->
            <div class="flex gap-3">
                <button type="button" onclick="closeEditReviewModal()"
                        class="flex-1 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                    Cancel
                </button>
                <button type="button" onclick="submitEditReview()"
                        class="flex-[2] flex items-center justify-center gap-2 py-2.5 rounded-xl bg-accent text-white text-sm font-semibold hover:bg-purple-700 transition-all shadow-md shadow-purple-200">
                    <span class="material-symbols-outlined text-base">save</span>
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let selectedEditRating = 0;
const editRatingLabels = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];

function openEditReviewModal(reviewId, rating, reviewText) {
    document.getElementById('edit-review-id').value = reviewId;
    document.getElementById('edit-review-text').value = reviewText;
    document.getElementById('edit-review-char').textContent = reviewText.length;
    document.getElementById('edit-review-error').classList.add('hidden');
    setEditRating(rating);
    document.getElementById('editReviewModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditReviewModal() {
    document.getElementById('editReviewModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function setEditRating(value) {
    selectedEditRating = value;
    document.getElementById('edit-review-rating').value = value;
    document.getElementById('edit-rating-label').textContent = value ? editRatingLabels[value] : 'Select rating';
    document.querySelectorAll('.edit-star-btn').forEach(btn => {
        btn.style.color = parseInt(btn.dataset.star) <= value ? '#f59e0b' : '';
    });
}

function submitEditReview() {
    const review  = document.getElementById('edit-review-text').value.trim();
    const rating  = document.getElementById('edit-review-rating').value;
    const reviewId = document.getElementById('edit-review-id').value;
    const errorEl = document.getElementById('edit-review-error');

    if (!rating || rating == 0) { errorEl.textContent = 'Please select a rating.'; errorEl.classList.remove('hidden'); return; }
    if (review.length < 10)     { errorEl.textContent = 'Review must be at least 10 characters.'; errorEl.classList.remove('hidden'); return; }
    errorEl.classList.add('hidden');

    fetch(reviewBaseUrl + reviewId + '/update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: new URLSearchParams({ [csrfToken]: csrfHash, review, rating })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) { closeEditReviewModal(); location.reload(); }
        else { errorEl.textContent = data.message; errorEl.classList.remove('hidden'); }
    });
}

function deleteReview(reviewId) {
    if (!confirm('Are you sure you want to delete your review?')) return;
    fetch(reviewBaseUrl + reviewId + '/delete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: new URLSearchParams({ [csrfToken]: csrfHash })
    })
    .then(r => r.json())
    .then(data => { if (data.success) location.reload(); });
}

document.getElementById('editReviewModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditReviewModal();
});
</script>
