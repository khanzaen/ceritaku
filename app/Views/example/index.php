<?php
require 'config.php';
require 'includes/functions.php';

// Get data for homepage
// Ambil featured reviews yang sudah diurutkan berdasarkan likes count
$featured_reviews = getFeaturedReviews(6);
$total_stories = getTotalStories();
$total_users = getTotalUsers();
$total_reviews = getTotalReviews();
$latest_reviews = getLatestReviews(4);
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>CeritaKu - Platform Novel Indonesia</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Lora:ital,wght@0,400;0,700;1,400&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#2d333a",
                        "accent": "#7C3BD9",
                        "surface": "#ffffff",
                        "background": "#fdfdfd",
                        "border": "#e5e7eb",
                    },
                    fontFamily: {
                        "sans": ["Inter", "sans-serif"],
                        "serif": ["Lora", "serif"]
                    },
                },
            },
        }
    </script>
<style type="text/tailwindcss">
        @layer base {
            body {
                @apply bg-background text-slate-800 antialiased;
            }
            h1, h2, h3, h4 {
                @apply font-serif;
            }
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .book-card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
          .hero-gradient {
            /* Background image with dark overlay */
            background-image: 
                linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)),
                url('./assets/backround_webnovel.jpg');
            background-size: cover;
            background-position: center;
        }
        .hero-gradient-2 {
            /* Second background image with dark overlay */
            background-image: 
                linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)),
                url('./assets/backround_webnovel2.jpg');
            background-size: cover;
            background-position: center;
            position: absolute;
            inset: 0;
            animation: fadeInOut 8s ease-in-out infinite;
        }
        @keyframes fadeInOut {
            0%, 100% { opacity: 0; }
            50% { opacity: 1; }
        }
        #hero-words {
            display: inline-block;
            border-right: 3px solid #fcd34d;
            padding-right: 4px;
            animation: blink 0.7s infinite;
        }
        @keyframes blink {
            0%, 49%, 100% { border-right-color: #fcd34d; }
            50%, 99% { border-right-color: transparent; }
        }
    </style>
</head>
<body class="font-sans bg-background">

<?php require 'includes/header.php'; ?>

<!-- Hero Container with Gradient -->
<div class="relative">
  <div class="hero-gradient absolute inset-0"></div>
  <div class="hero-gradient-2 absolute inset-0"></div>
  
<main class="max-w-6xl mx-auto px-6 py-3">

    <!-- Hero Section-->
<section class="relative -mx-6 mb-20 overflow-hidden">
  <!-- Hero Content -->
  <div class="relative max-w-6xl mx-auto px-6 py-10 md:py-14">
    <div class="text-center mb-20">
      <h1 class="text-3xl md:text-5xl font-bold text-white mb-3 leading-tight drop-shadow-lg">
        Where great stories find their
        <span id="hero-words" class="text-yellow-300 opacity-100 transition-opacity duration-500">readers</span>.
      </h1>
      <p class="text-base md:text-lg text-white/90 mb-6 leading-relaxed max-w-3xl mx-auto drop-shadow">A community-driven novel platform connecting readers with curated stories from independent writers.</p>

      <div class="max-w-xl mx-auto">
        <form method="GET" action="search.php" class="relative">
          <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
            <span class="material-symbols-outlined text-slate-400 text-sm">search</span>
          </div>
          <input name="q" type="text" placeholder="Search stories, authors, or genres" class="w-full pl-9 pr-24 py-2 bg-white/95 backdrop-blur border-0 rounded-xl focus:ring-2 focus:ring-white/50 outline-none transition-all text-slate-900 text-sm shadow-lg"/>
          <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-slate-900 text-white rounded-lg text-xs font-semibold hover:bg-slate-800 transition-all">Cari</button>
        </form>
        <div class="mt-3 flex justify-center gap-2 text-[11px] text-white/80">
          <span class="px-2 py-0.5 bg-white/20 backdrop-blur rounded-md"><?php echo $total_users; ?>k+ pembaca</span>
          <span class="px-2 py-0.5 bg-white/20 backdrop-blur rounded-md"><?php echo $total_reviews; ?>+ ulasan</span>
          <span class="px-2 py-0.5 bg-white/20 backdrop-blur rounded-md">1.5k+ cerita dipublikasi</span>
        </div>
      </div>
    </div>

    <div class="mt-4 flex flex-col sm:flex-row gap-3 justify-center">
      <a href="./discover.php" class="bg-white text-slate-900 px-5 py-2 rounded-full text-xs font-bold hover:bg-white/90 transition-all inline-flex items-center gap-2 shadow-lg justify-center">
        <span class="material-symbols-outlined text-base">local_library</span>
        Jelajahi Cerita
      </a>
      <a href="./write.php" class="bg-white/10 backdrop-blur border-2 border-white text-white px-5 py-2 rounded-full text-xs font-bold hover:bg-white hover:text-slate-900 transition-all inline-flex items-center gap-2 justify-center">
        <span class="material-symbols-outlined text-base">edit</span>
        Mulai Menulis
      </a>
    </div>

    <div class="mt-3 text-center">
      <a href="#discover" class="inline-flex items-center gap-1 text-sm font-bold text-white/80 hover:text-white transition-colors">
        Lihat ulasan trending
        <span class="material-symbols-outlined text-base">south</span>
      </a>
    </div>
  </div>
</section>
</div>
<!-- End Hero Container -->

<!-- Content Section with White Background -->
<div class="bg-background relative z-10">
<section id="discover" class="max-w-6xl mx-auto px-6 py-5">
<div class="flex items-center justify-between mb-10 border-b border-border pb-4">
<div>
<button class="text-sm font-bold text-slate-900 border-b-2 border-slate-900 pb-4 -mb-[18px]">Featured</button>
</div>
<div class="flex items-center gap-2">
<span class="text-sm text-slate-500">Filter berdasarkan</span>
<button class="flex items-center gap-1 bg-white border border-border px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-slate-50">
                    Genre <span class="material-symbols-outlined text-sm">expand_more</span>
</button>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-x-10 gap-y-14">

<?php
// Display featured reviews sorted by likes
if (!empty($featured_reviews)): 
    foreach($featured_reviews as $index => $review):
?>

<article class="flex gap-6 group">
<div class="w-32 md:w-44 flex-none">
<a href="./story-detail.php?id=<?php echo $review['story_id']; ?>" class="block">
<div class="aspect-[2/3] bg-slate-100 rounded-sm overflow-hidden book-card-shadow transition-transform duration-300 group-hover:-translate-y-1">
<img alt="<?php echo escape($review['story_title']); ?> Cover" class="w-full h-full object-cover" src="<?php echo getCoverImagePath($review['cover_image']); ?>"/>
</div>
</a>
<div class="mt-4 text-center">
<span class="text-xs text-slate-500"><?php echo $review['likes_count']; ?> orang menyetujui</span>
</div>
</div>
<div class="flex flex-col">
<div class="flex items-center gap-2 mb-3">
<span class="px-2 py-0.5 bg-slate-100 text-[10px] font-bold uppercase tracking-wider text-slate-500 rounded"><?php echo escape($review['genres']); ?></span>
<span class="text-slate-300">|</span>
<div class="flex items-center gap-1.5">
<img alt="Reviewer" class="w-5 h-5 rounded-full border border-border" src="<?php echo getProfilePhoto($review['user_photo']); ?>"/>
<span class="text-xs text-slate-600">Diulas oleh <span class="font-semibold text-slate-900"><?php echo escape($review['user_name']); ?></span></span>
</div>
</div>
<h3 class="text-2xl font-bold text-slate-900 mb-2 leading-tight group-hover:text-accent transition-colors">
  <a href="./story-detail.php?id=<?php echo $review['story_id']; ?>" class="hover:text-accent transition-colors">
    <?php echo escape($review['story_title']); ?>
  </a>
</h3>
<p class="text-slate-500 text-sm mb-4">by <span class="text-slate-700 font-medium"><?php echo escape($review['author_name']); ?></span></p>

<p class="text-slate-600 text-sm leading-relaxed mb-6 line-clamp-3 italic">
"<?php echo truncateText(escape($review['review']), 150); ?>"
</p>

<div class="mt-auto">
<a class="inline-flex items-center gap-1 text-sm font-bold text-accent hover:underline" href="./story-detail.php?id=<?php echo $review['story_id']; ?>#reviews">
Baca Ulasan <span class="material-symbols-outlined text-base">arrow_forward</span>
</a>
</div>
</div>
</article>

<?php 
    endforeach;
else: 
?>
<p class="text-slate-500">Tidak ada cerita unggulan tersedia untuk saat ini.</p>
<?php endif; ?>

</div>
<div class="mt-7 text-center">
  <a href="./discover.php" class="bg-white border-2 border-slate-900 text-slate-900 px-8 py-2.5 rounded-full font-bold hover:bg-slate-900 hover:text-white transition-all inline-block">
    Temukan cerita lainnya
  </a>
</div>
</section>

<!-- Success Stories Section -->
<section class="max-w-6xl mx-auto px-6 py-16">
  <div class="text-center mb-12">
    <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Kisah Sukses Penulis</h2>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Success Story 1 -->
    <div class="bg-gradient-to-br from-purple-50 to-white border border-border rounded-2xl p-6 shadow-sm hover:shadow-lg transition-shadow">
      <div class="flex items-center gap-4 mb-4">
        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBPRS1KzX2ETA8NZwVnOVQrL_P1mKw1pTBxDiELHmmPtcBUO-XSecj1q4z23Sb7qHttcAQRdUGYePzvZPhV8MTUgaRjuPw01E4u2jbT79aiLrh7GfSD3xUdOLYmr14hFbxWBL0UE0cDeaGqT09Ve7TZTgPcOsDFyVl9iyCBYETAaq5SM_nRxVMmeRbMfUvF4p0w7QXBqasg8CSKLpzJIbKXL25t6wKZvtOaMkqFdaQm38Z57S3QI2oJbeZ_0qJNJ8uPoFDvr1diuHA" alt="Khanza Haura" class="w-16 h-16 rounded-full border-2 border-purple-200 object-cover" />
        <div>
          <h3 class="font-bold text-lg text-primary">Khanza Haura</h3>
          <p class="text-sm text-slate-600">Romance Writer</p>
        </div>
      </div>
      <div class="mb-4">
        <p class="text-sm text-slate-700 italic leading-relaxed">"Started with just 50 readers, now my stories reach over 120k people. CeritaKu gave me the platform to share my passion."</p>
      </div>
      <div class="grid grid-cols-3 gap-3 pt-4 border-t border-purple-100">
        <div class="text-center">
          <p class="text-2xl font-bold text-accent">120k</p>
          <p class="text-xs text-slate-500">Total Reads</p>
        </div>
        <div class="text-center">
          <p class="text-2xl font-bold text-accent">4.8</p>
          <p class="text-xs text-slate-500">Avg Rating</p>
        </div>
        <div class="text-center">
          <p class="text-2xl font-bold text-accent">3</p>
          <p class="text-xs text-slate-500">Published</p>
        </div>
      </div>
    </div>

    <!-- Success Story 2 -->
    <div class="bg-gradient-to-br from-blue-50 to-white border border-border rounded-2xl p-6 shadow-sm hover:shadow-lg transition-shadow">
      <div class="flex items-center gap-4 mb-4">
        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuANDd3AoTSw-hC8cWN_Zgg44jlY5a7fvVIVlxtHJXy4zhXAR9QTCAt0om9F8KOwjU9fVkSk69m6R1T-Naw1AdUsD_h0AE3PjkTuxnOSZp91w9SwxWYF-eDsr7RsjuXTodwQ3wCdJ7FyPB9lAhz4et3-LHORI0pDg61rShmN0p0Lu1Yfo1GPIAPrk35jeVTdZbB9CKDt9JAw9lt5vXvsaea1rZJwhgf3FxRjSKcr6mHYViXE937a_x1qs7X2CmYrg5B-CEOpVQ6SXKY" alt="Bima Pratama" class="w-16 h-16 rounded-full border-2 border-blue-200 object-cover" />
        <div>
          <h3 class="font-bold text-lg text-primary">Bima Pratama</h3>
          <p class="text-sm text-slate-600">Mystery Author</p>
        </div>
      </div>
      <div class="mb-4">
        <p class="text-sm text-slate-700 italic leading-relaxed">"From hobby writer to published author. The community feedback helped me improve my craft and gain confidence."</p>
      </div>
      <div class="grid grid-cols-3 gap-3 pt-4 border-t border-blue-100">
        <div class="text-center">
          <p class="text-2xl font-bold text-accent">89k</p>
          <p class="text-xs text-slate-500">Total Reads</p>
        </div>
        <div class="text-center">
          <p class="text-2xl font-bold text-accent">4.7</p>
          <p class="text-xs text-slate-500">Avg Rating</p>
        </div>
        <div class="text-center">
          <p class="text-2xl font-bold text-accent">5</p>
          <p class="text-xs text-slate-500">Published</p>
        </div>
      </div>
    </div>

    <!-- Success Story 3 -->
    <div class="bg-gradient-to-br from-pink-50 to-white border border-border rounded-2xl p-6 shadow-sm hover:shadow-lg transition-shadow">
      <div class="flex items-center gap-4 mb-4">
        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAjUHraC2ufzKUyHo2lDgpRtdmQDAKWedAyU-KVfF-dDcMVmOcQJlSz4GxISwKe6tIZyzpQlVWrdwTLH2En40j17ts7HxsMiIRIq0EvzaDjQiL81HEYQHeOHNLkfMvjpfG--W5J3355cybQT4cOVfzDeFFzzoNsRzozrsp1yMdp6m8fGcvhDuXEP0xYAN7biJs6_PqmxKBO8Qxnqi5sLfezhWiZuOVtn3hqO3LDmPIwkB7DZwgNN_pQuraPLZRUYQBux7o2ZxjmG0M" alt="Dewi Lestari" class="w-16 h-16 rounded-full border-2 border-pink-200 object-cover" />
        <div>
          <h3 class="font-bold text-lg text-primary">Dewi Lestari</h3>
          <p class="text-sm text-slate-600">Drama Writer</p>
        </div>
      </div>
      <div class="mb-4">
        <p class="text-sm text-slate-700 italic leading-relaxed">"Built a loyal fanbase of 5k+ followers. Every story I publish now reaches thousands within the first week!"</p>
      </div>
      <div class="grid grid-cols-3 gap-3 pt-4 border-t border-pink-100">
        <div class="text-center">
          <p class="text-2xl font-bold text-accent">215k</p>
          <p class="text-xs text-slate-500">Total Reads</p>
        </div>
        <div class="text-center">
          <p class="text-2xl font-bold text-accent">4.9</p>
          <p class="text-xs text-slate-500">Avg Rating</p>
        </div>
        <div class="text-center">
          <p class="text-2xl font-bold text-accent">7</p>
          <p class="text-xs text-slate-500">Published</p>
        </div>
      </div>
    </div>
  </div>


</section>

</div>
</main>

<?php require 'includes/footer.php'; ?>
<?php require 'includes/login-modal.php'; ?>
<?php require 'includes/register-modal.php'; ?>
</body>
</html>