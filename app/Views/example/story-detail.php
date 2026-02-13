<?php
require 'config.php';
require 'includes/functions.php';

$story_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($story_id <= 0) {
  header('Location: discover.php');
  exit;
}

$story = getStoryById($story_id);
if (empty($story)) {
  header('Location: discover.php');
  exit;
}

$genres = array_filter(array_map('trim', explode(',', $story['genres'] ?? '')));
$primary_genre = !empty($genres) ? $genres[0] : 'Other';
$story_rating = $story['average_rating'] ? number_format($story['average_rating'], 1) : 'N/A';
$story_views = isset($story['total_views']) ? (int)$story['total_views'] : 0;
$story_updated = !empty($story['created_at']) ? formatDate($story['created_at']) : '';
$story_status = !empty($story['status']) ? ucfirst(strtolower($story['status'])) : 'Published';
$story_cover = getCoverImagePath($story['cover_image']);
$chapters = getLatestChaptersByStory($story_id, 3);
$reviews = getReviewsByStory($story_id, 3);
$related = getRelatedStoriesByGenre($story_id, $primary_genre, 3);

// Check if story is in user's library
$is_in_library = false;
if (isset($_SESSION['user_id'])) {
  $user_id = (int)$_SESSION['user_id'];
  $library_check = $conn->query("SELECT id FROM user_library WHERE user_id = $user_id AND story_id = $story_id LIMIT 1");
  $is_in_library = $library_check->num_rows > 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo escape($story['title']); ?> | CeritaKu</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
  <script id="tailwind-config">
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#2d333a',
            accent: '#7C3BD9',
            surface: '#ffffff',
            background: '#fdfdfd',
            border: '#e5e7eb',
          },
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
            serif: ['Lora', 'serif'],
          },
        },
      },
    }
  </script>
  <style type="text/tailwindcss">
    @layer base {
      body {@apply bg-background text-slate-800 antialiased;}
      h1,h2,h3,h4{@apply font-serif;}
    }
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    .book-card-shadow {
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
    }
    .star-rating {
      @apply flex gap-1 cursor-pointer;
    }
    .star-rating .star {
      @apply text-2xl transition-all duration-200 text-slate-300 hover:text-amber-400;
    }
    .star-rating .star.active {
      @apply text-amber-400;
    }
    .modal-overlay {
      @apply fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4 hidden;
    }
    .modal-overlay.active {
      @apply flex;
    }
  </style>
</head>
<body class="font-sans bg-background">
  <?php require 'includes/header.php'; ?>

  <main class="max-w-6xl mx-auto px-6 py-8">
    <!-- Breadcrumb -->
    <nav class="text-xs text-slate-500 mb-6">
      <a href="./index.php" class="hover:text-slate-900">Beranda</a>
      <span class="mx-2">/</span>
      <a href="./discover.php" class="hover:text-slate-900">Jelajahi</a>
      <span class="mx-2">/</span>
      <span class="text-slate-700" id="breadcrumb-title"><?php echo escape($story['title']); ?></span>
    </nav>

    <!-- Header Section -->
    <section class="grid md:grid-cols-3 gap-8 mb-10">
      <!-- Cover -->
      <div class="md:col-span-1">
        <div class="aspect-[2/3] bg-white rounded-xl overflow-hidden book-card-shadow border border-border">
          <img id="story-cover" src="<?php echo $story_cover; ?>" alt="<?php echo escape($story['title']); ?> cover" class="w-full h-full object-cover" />
        </div>
      </div>
      <!-- Meta and Actions -->
      <div class="md:col-span-2">
        <div class="flex items-center gap-2 mb-3">
          <span id="story-genre" class="px-2 py-0.5 bg-slate-100 text-[10px] font-bold uppercase tracking-wider text-slate-600 rounded"><?php echo escape($primary_genre); ?></span>
          <span class="text-slate-300">|</span>
          <span class="text-[12px] text-slate-600">Updated <span id="story-updated"><?php echo escape($story_updated); ?></span></span>
        </div>
        <h1 id="story-title" class="text-3xl md:text-4xl font-bold text-primary mb-2"><?php echo escape($story['title']); ?></h1>
        <p class="text-slate-600 mb-4">by <span id="story-author" class="text-slate-800 font-medium"><?php echo escape($story['author_name']); ?></span></p>

        <div class="flex items-center gap-3 mb-6">
          <div class="flex items-center gap-1 text-amber-600">
            <span class="material-symbols-outlined">star</span>
            <span id="story-rating" class="text-sm font-semibold"><?php echo $story_rating; ?></span>
          </div>
          <span class="text-slate-300">•</span>
          <span id="story-reads" class="text-sm text-slate-600"><?php echo number_format($story_views); ?> tampilan</span>
          <span class="text-slate-300">•</span>
          <span id="story-status" class="text-sm text-emerald-700 font-semibold"><?php echo escape($story_status); ?></span>
        </div>

        <p id="story-synopsis" class="text-sm md:text-base text-slate-700 leading-relaxed italic mb-6">
          <?php echo escape($story['description'] ?? ''); ?>
        </p>

        <div class="flex flex-wrap gap-3 mb-6">
          <a href="./read-chapter.php?id=<?php echo (int)$story['id']; ?>" class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-slate-800 transition-all">
            <span class="material-symbols-outlined">menu_book</span>
            Baca Sekarang
          </a>
          <button id="add-library" class="inline-flex items-center gap-2 <?php echo $is_in_library ? 'bg-accent text-white' : 'bg-white border border-border'; ?> px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-all">
            <span class="material-symbols-outlined"><?php echo $is_in_library ? 'bookmark_remove' : 'bookmark_add'; ?></span>
            <?php echo $is_in_library ? 'Hapus dari Perpustakaan' : 'Tambah ke Perpustakaan'; ?>
          </button>
          <button id="share-story" class="inline-flex items-center gap-2 bg-white border border-border px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-all">
            <span class="material-symbols-outlined">share</span>
            Bagikan
          </button>
        </div>

        <div class="flex flex-wrap gap-2">
          <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium">Total Bab: <?php echo (int)$story['total_chapters']; ?></span>
          <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium">Rating: <?php echo (int)$story['total_ratings']; ?></span>
        </div>
      </div>
    </section>

    <!-- Tags -->
    <section class="mb-10">
      <div class="flex flex-wrap gap-2">
        <?php if (!empty($genres)): ?>
          <?php foreach ($genres as $genre): ?>
            <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium"><?php echo escape($genre); ?></span>
          <?php endforeach; ?>
        <?php else: ?>
          <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium">General</span>
        <?php endif; ?>
      </div>
    </section>

    <!-- Chapters -->
    <section id="chapters" class="mb-14">
      <!-- Chapters List -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-xl md:text-2xl font-bold text-primary">Latest Chapters</h2>
          <a href="#" class="text-sm font-semibold text-accent hover:underline">View all →</a>
        </div>
        <div class="bg-white border border-border rounded-2xl shadow-sm">
          <ul class="divide-y divide-border">
            <?php if (!empty($chapters)): ?>
              <?php foreach ($chapters as $chapter): ?>
                <li class="p-4 flex items-center justify-between">
                  <div>
                    <p class="text-sm font-semibold text-slate-900">Chapter <?php echo (int)$chapter['chapter_number']; ?> • <?php echo escape($chapter['title']); ?></p>
                    <p class="text-xs text-slate-500">Updated <?php echo escape(formatDate($chapter['created_at'])); ?></p>
                  </div>
                  <a href="./read-chapter.php?id=<?php echo (int)$story['id']; ?>&ch=<?php echo (int)$chapter['chapter_number']; ?>" class="inline-flex items-center gap-1 text-xs font-bold text-accent hover:underline">
                    Read <span class="material-symbols-outlined text-sm">arrow_forward</span>
                  </a>
                </li>
              <?php endforeach; ?>
            <?php else: ?>
              <li class="p-4 text-sm text-slate-500">No chapters available yet.</li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </section>

    <!-- Reviews -->
    <section id="reviews" class="mb-16">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl md:text-2xl font-bold text-primary">Ulasan Pembaca</h2>
        <button id="open-review-modal" class="text-sm font-semibold text-accent hover:underline">Tulis ulasan →</button>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="reviews-list">
        <?php if (!empty($reviews)): ?>
          <?php foreach ($reviews as $review): ?>
            <article class="p-5 bg-white border border-border rounded-2xl shadow-sm">
              <div class="flex items-center gap-2 text-amber-600 mb-2">
                <span class="material-symbols-outlined text-base">star</span>
                <span class="text-sm font-semibold"><?php echo (int)$review['rating']; ?>/5</span>
              </div>
              <p class="text-sm text-slate-700 leading-relaxed"><?php echo escape($review['review']); ?></p>
              <p class="text-xs text-slate-500 mt-3">By <?php echo escape($review['user_name']); ?> • <?php echo escape(formatDate($review['created_at'])); ?></p>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-span-3 text-center text-slate-500 text-sm">Belum ada ulasan. Jadilah yang pertama memberikan ulasan!</div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Related -->
    <section id="related" class="mb-12">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl md:text-2xl font-bold text-primary">Mungkin Anda juga suka</h2>
        <a href="./discover.php" class="text-sm font-semibold text-accent hover:underline">Jelajahi lainnya →</a>
      </div>
      <div class="flex flex-wrap gap-2">
        <?php if (!empty($related)): ?>
          <?php foreach ($related as $item): ?>
            <a href="story-detail.php?id=<?php echo (int)$item['id']; ?>" class="block">
              <div class="p-3 bg-white border border-border rounded-xl shadow-sm w-[180px] flex-none hover:shadow-md transition-all duration-200">
                <div class="w-full aspect-[2/3] rounded overflow-hidden bg-slate-100 book-card-shadow mb-2">
                  <img src="<?php echo getCoverImagePath($item['cover_image']); ?>" alt="<?php echo escape($item['title']); ?>" class="w-full h-full object-cover" />
                </div>
                <h3 class="text-xs font-bold text-primary leading-tight line-clamp-2"><?php echo escape($item['title']); ?></h3>
                <p class="text-[10px] text-slate-500"><?php echo escape($item['genres']); ?> • <?php echo escape($item['author_name']); ?></p>
              </div>
            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="text-sm text-slate-500">No related stories found.</div>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <!-- Review Modal -->
  <div id="review-modal" class="modal-overlay">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 md:p-8">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl md:text-2xl font-bold text-primary">Tulis Ulasan Anda</h3>
        <button id="close-review-modal" class="text-slate-400 hover:text-slate-600 transition-colors">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <form id="review-form" class="space-y-6">
        <!-- Rating -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-3">Rating</label>
          <div id="star-rating" class="star-rating">
            <button type="button" class="star" data-rating="1" title="1 bintang">★</button>
            <button type="button" class="star" data-rating="2" title="2 bintang">★</button>
            <button type="button" class="star" data-rating="3" title="3 bintang">★</button>
            <button type="button" class="star" data-rating="4" title="4 bintang">★</button>
            <button type="button" class="star" data-rating="5" title="5 bintang">★</button>
          </div>
          <input type="hidden" id="rating-input" name="rating" value="0">
          <p id="rating-error" class="text-xs text-red-600 mt-2 hidden">Silakan pilih rating</p>
        </div>

        <!-- Review Text -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-3">Ulasan Anda</label>
          <textarea name="review" id="review-text" rows="4" placeholder="Bagikan pendapat Anda tentang cerita ini..." class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 resize-none" maxlength="1000"></textarea>
          <div class="flex justify-between mt-2">
            <p id="char-count" class="text-xs text-slate-500">0/1000</p>
            <p id="review-error" class="text-xs text-red-600 hidden">Ulasan harus antara 10-1000 karakter</p>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 justify-end pt-4">
          <button type="button" id="cancel-review" class="px-4 py-2 rounded-lg border border-border text-slate-700 font-semibold hover:bg-slate-50 transition-all">Batal</button>
          <button type="submit" class="px-4 py-2 rounded-lg bg-accent text-white font-semibold hover:bg-purple-700 transition-all">Kirim Ulasan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Notification Container -->
  <div id="notification" class="fixed top-4 right-4 bg-white border border-border rounded-xl shadow-lg p-4 flex items-center gap-3 z-50 min-w-[300px] transition-all duration-300 opacity-0 translate-x-full">
    <span id="notification-icon" class="material-symbols-outlined text-2xl text-accent">check_circle</span>
    <div>
      <p id="notification-title" class="font-semibold text-slate-900"></p>
      <p id="notification-message" class="text-sm text-slate-600"></p>
    </div>
    <button onclick="closeNotification()" class="ml-auto text-slate-400 hover:text-slate-600">
      <span class="material-symbols-outlined">close</span>
    </button>
  </div>

  <?php require 'includes/footer.php'; ?>

  <script src="./js/main.js"></script>
  <script>
    // Notification functions
    function showNotification(title, message, type = 'success') {
      const notification = document.getElementById('notification');
      const icon = document.getElementById('notification-icon');
      const titleEl = document.getElementById('notification-title');
      const messageEl = document.getElementById('notification-message');

      titleEl.textContent = title;
      messageEl.textContent = message;

      // Set icon dan warna berdasarkan type
      if (type === 'success') {
        icon.textContent = 'check_circle';
        icon.className = 'material-symbols-outlined text-2xl text-emerald-600';
        notification.classList.add('border-emerald-200', 'bg-emerald-50');
        notification.classList.remove('border-red-200', 'bg-red-50', 'border-amber-200', 'bg-amber-50');
      } else if (type === 'error') {
        icon.textContent = 'error';
        icon.className = 'material-symbols-outlined text-2xl text-red-600';
        notification.classList.add('border-red-200', 'bg-red-50');
        notification.classList.remove('border-emerald-200', 'bg-emerald-50', 'border-amber-200', 'bg-amber-50');
      } else if (type === 'warning') {
        icon.textContent = 'warning';
        icon.className = 'material-symbols-outlined text-2xl text-amber-600';
        notification.classList.add('border-amber-200', 'bg-amber-50');
        notification.classList.remove('border-emerald-200', 'bg-emerald-50', 'border-red-200', 'bg-red-50');
      }

      // Show notification
      notification.classList.remove('opacity-0', 'translate-x-full');
      notification.classList.add('opacity-100', 'translate-x-0');

      // Auto hide after 4 seconds
      setTimeout(closeNotification, 4000);
    }

    function closeNotification() {
      const notification = document.getElementById('notification');
      notification.classList.add('opacity-0', 'translate-x-full');
      notification.classList.remove('opacity-100', 'translate-x-0');
    }

    // Minimal interactions for detail page
    document.getElementById('share-story')?.addEventListener('click', async () => {
      const url = window.location.href;
      try {
        await navigator.clipboard.writeText(url);
        showNotification('Tautan Disalin!', 'Tautan cerita disalin ke papan klip', 'success');
      } catch {
        showNotification('Gagal Disalin', 'Tautan: ' + url, 'error');
      }
    });

    // Add to Library button
    const addLibraryBtn = document.getElementById('add-library');
    if (addLibraryBtn) {
      addLibraryBtn.addEventListener('click', async function() {
        const storyId = <?php echo (int)$story['id']; ?>;
        
        try {
          // Use the current state (window.isInLibrary) instead of static initial value
          const endpoint = window.isInLibrary ? './api/remove-from-library.php' : './api/add-to-library.php';
          
          const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'story_id=' + storyId
          });

          const data = await response.json();

          if (data.success) {
            if (window.isInLibrary) {
              // Currently in library, so we just removed it
              addLibraryBtn.classList.remove('bg-accent', 'text-white', 'hover:bg-purple-700');
              addLibraryBtn.classList.add('bg-white', 'border', 'border-border', 'hover:bg-slate-50');
              
              // Update button text
              const icon = addLibraryBtn.querySelector('.material-symbols-outlined');
              const text = addLibraryBtn.childNodes[addLibraryBtn.childNodes.length - 1];
              if (icon) icon.textContent = 'bookmark_add';
              if (text) text.textContent = 'Tambah ke Perpustakaan';
              
              showNotification('Dihapus dari Perpustakaan', 'Cerita dihapus dari perpustakaan Anda', 'success');
              // Update state
              window.isInLibrary = false;
            } else {
              // Not in library, so we just added it
              addLibraryBtn.classList.remove('bg-white', 'border', 'border-border', 'hover:bg-slate-50');
              addLibraryBtn.classList.add('bg-accent', 'text-white', 'hover:bg-purple-700');
              
              // Update button text
              const icon = addLibraryBtn.querySelector('.material-symbols-outlined');
              const text = addLibraryBtn.childNodes[addLibraryBtn.childNodes.length - 1];
              if (icon) icon.textContent = 'bookmark_remove';
              if (text) text.textContent = 'Hapus dari Perpustakaan';
              
              showNotification('Ditambahkan ke Perpustakaan', 'Cerita ditambahkan ke perpustakaan Anda dengan sukses', 'success');
              // Update state
              window.isInLibrary = true;
            }
          } else if (data.already_exists) {
            // Story already in library
            showNotification('Sudah ada di Perpustakaan', 'Cerita ini sudah ada di perpustakaan Anda', 'warning');
            
            // Change button appearance
            addLibraryBtn.classList.remove('bg-white', 'border', 'border-border', 'hover:bg-slate-50');
            addLibraryBtn.classList.add('bg-accent', 'text-white', 'hover:bg-purple-700');
            
            // Update button
            const icon = addLibraryBtn.querySelector('.material-symbols-outlined');
            const text = addLibraryBtn.childNodes[addLibraryBtn.childNodes.length - 1];
            if (icon) icon.textContent = 'bookmark_remove';
            if (text) text.textContent = 'Hapus dari Perpustakaan';
            window.isInLibrary = true;
          } else {
            if (response.status === 401) {
              showNotification('Masuk Diperlukan', 'Silakan masuk untuk mengelola perpustakaan Anda', 'warning');
            } else {
              showNotification('Kesalahan', data.message || 'Gagal memperbarui perpustakaan', 'error');
            }
          }
        } catch (error) {
          console.error('Fetch Error:', error);
          showNotification('Kesalahan', 'Kesalahan jaringan atau fetch. Periksa konsol (F12).', 'error');
        }
      });
      
      // Initialize state
      window.isInLibrary = <?php echo $is_in_library ? 'true' : 'false'; ?>;
    }

    // Review Modal
    const reviewModal = document.getElementById('review-modal');
    const openReviewBtn = document.getElementById('open-review-modal');
    const closeReviewBtn = document.getElementById('close-review-modal');
    const cancelReviewBtn = document.getElementById('cancel-review');
    const reviewForm = document.getElementById('review-form');
    const ratingInput = document.getElementById('rating-input');
    const reviewText = document.getElementById('review-text');
    const charCount = document.getElementById('char-count');
    const starButtons = document.querySelectorAll('.star');
    const storyId = <?php echo (int)$story['id']; ?>;

    // Open review modal
    openReviewBtn.addEventListener('click', () => {
      const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
      if (!isLoggedIn) {
        showNotification('Masuk Diperlukan', 'Silakan masuk untuk memberikan ulasan', 'warning');
        return;
      }
      reviewModal.classList.add('active');
    });

    // Close review modal
    closeReviewBtn.addEventListener('click', () => {
      reviewModal.classList.remove('active');
    });

    cancelReviewBtn.addEventListener('click', () => {
      reviewModal.classList.remove('active');
    });

    // Close modal when clicking outside
    reviewModal.addEventListener('click', (e) => {
      if (e.target === reviewModal) {
        reviewModal.classList.remove('active');
      }
    });

    // Star rating
    starButtons.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const rating = btn.getAttribute('data-rating');
        ratingInput.value = rating;
        
        // Update star display
        starButtons.forEach((star, idx) => {
          if ((idx + 1) <= rating) {
            star.classList.add('active');
          } else {
            star.classList.remove('active');
          }
        });
      });

      btn.addEventListener('mouseover', (e) => {
        const rating = btn.getAttribute('data-rating');
        starButtons.forEach((star, idx) => {
          if ((idx + 1) <= rating) {
            star.style.color = '#fbbf24';
          } else {
            star.style.color = '#cbd5e1';
          }
        });
      });
    });

    document.getElementById('star-rating').addEventListener('mouseleave', () => {
      const currentRating = ratingInput.value;
      starButtons.forEach((star, idx) => {
        if ((idx + 1) <= currentRating) {
          star.classList.add('active');
        } else {
          star.classList.remove('active');
        }
      });
    });

    // Character count
    reviewText.addEventListener('input', (e) => {
      charCount.textContent = e.target.value.length + '/1000';
    });

    // Submit review
    reviewForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const rating = ratingInput.value;
      const review = reviewText.value.trim();

      // Validation
      let isValid = true;
      document.getElementById('rating-error').classList.add('hidden');
      document.getElementById('review-error').classList.add('hidden');

      if (!rating || rating < 1) {
        document.getElementById('rating-error').classList.remove('hidden');
        isValid = false;
      }

      if (review.length < 10 || review.length > 1000) {
        document.getElementById('review-error').classList.remove('hidden');
        isValid = false;
      }

      if (!isValid) return;

      try {
        const response = await fetch('./api/add-review.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'story_id=' + storyId + '&rating=' + rating + '&review=' + encodeURIComponent(review)
        });

        const data = await response.json();

        if (data.success) {
          showNotification('Ulasan Ditambahkan!', 'Terima kasih atas ulasan Anda', 'success');
          
          // Add new review to the page
          const reviewsList = document.getElementById('reviews-list');
          const noReviewsMsg = reviewsList.querySelector('.text-center');
          
          const newReview = document.createElement('article');
          newReview.className = 'p-5 bg-white border border-border rounded-2xl shadow-sm';
          newReview.innerHTML = `
            <div class="flex items-center gap-2 text-amber-600 mb-2">
              <span class="material-symbols-outlined text-base">star</span>
              <span class="text-sm font-semibold">${data.review.rating}/5</span>
            </div>
            <p class="text-sm text-slate-700 leading-relaxed">${escapeHtml(data.review.review)}</p>
            <p class="text-xs text-slate-500 mt-3">Oleh ${escapeHtml(data.review.user_name)} • Baru saja</p>
          `;
          
          if (noReviewsMsg) {
            noReviewsMsg.replaceWith(newReview);
          } else {
            reviewsList.insertBefore(newReview, reviewsList.firstChild);
          }
          
          // Reset form and close modal
          reviewForm.reset();
          ratingInput.value = 0;
          starButtons.forEach(star => star.classList.remove('active'));
          charCount.textContent = '0/1000';
          reviewModal.classList.remove('active');
        } else {
          showNotification('Kesalahan', data.message || 'Gagal menambahkan ulasan', 'error');
        }
      } catch (error) {
        console.error('Error:', error);
        showNotification('Kesalahan', 'Kesalahan jaringan. Silakan coba lagi.', 'error');
      }
    });

    // Helper function to escape HTML
    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

  </script>
</body>
</html>
