<?php
require 'config.php';
require 'includes/functions.php';

// Check if search query exists
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$search_results = array();
if (!empty($search_query)) {
  // Search for stories
  $search_results = searchStories($search_query);
}

// Get featured stories sorted by average rating
$featured_stories = getFeaturedStories(10);

// Get latest releases
$latest_releases = getLatestReleases(3);

// Genre styling configuration (with fallback for custom genres)
$genre_styles = array(
    'Romance' => array(
        'gradient' => 'from-pink-50 via-white to-white',
        'icon' => 'favorite',
        'progress_bar' => 'bg-pink-400',
        'text_color' => 'text-pink-700',
        'bg_color' => 'bg-pink-100',
        'border_color' => 'border-pink-100',
        'light_bg' => 'bg-white/80',
    ),
    'Mystery' => array(
        'gradient' => 'from-amber-50 via-white to-white',
        'icon' => 'visibility',
        'progress_bar' => 'bg-amber-400',
        'text_color' => 'text-amber-700',
        'bg_color' => 'bg-amber-100',
        'border_color' => 'border-amber-100',
        'light_bg' => 'bg-white/80',
    ),
    'Short Reads' => array(
        'gradient' => 'from-blue-50 via-white to-white',
        'icon' => 'sparkles',
        'progress_bar' => 'bg-blue-400',
        'text_color' => 'text-blue-700',
        'bg_color' => 'bg-blue-100',
        'border_color' => 'border-blue-100',
        'light_bg' => 'bg-white/80',
    ),
    'Fantasy' => array(
        'gradient' => 'from-purple-50 via-white to-white',
        'icon' => 'auto_awesome',
        'progress_bar' => 'bg-purple-400',
        'text_color' => 'text-purple-700',
        'bg_color' => 'bg-purple-100',
        'border_color' => 'border-purple-100',
        'light_bg' => 'bg-white/80',
    ),
    'Adventure' => array(

        'text_color' => 'text-yellow-700',
        'bg_color' => 'bg-yellow-100',
        'border_color' => 'border-yellow-100',
        'light_bg' => 'bg-white/80',
    ),
);

// Fetch trending stories by specific genres
$trending_genres = array('Romance', 'Mystery', 'Fantasy');
$trending_data = array();
foreach($trending_genres as $genre) {
    $trending_data[$genre] = getStoriesByGenre($genre, 3);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Discover | CeritaKu</title>
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
    <style>
      /* Featured Slider Styles */
      .featured-slide {
        display: none;
        animation: fadeIn 0.7s ease-in-out;
      }
    
      .featured-slide.active {
        display: block;
      }
    
      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(10px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
    
      .slide-dot {
        cursor: pointer;
        transition: all 0.3s ease;
      }
    
      .slide-dot:hover {
        transform: scale(1.3);
      }
    </style>
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
  </style>
</head>
<body class="font-sans">
  <?php require 'includes/header.php'; ?>

  <main class="max-w-6xl mx-auto px-6 py-10">
    
    <!-- Search Section -->
    <div class="mb-8">
      <form method="GET" action="discover.php" class="mb-3">
        <div class="flex gap-2 flex-wrap items-center">
          <div class="relative flex-[3] min-w-[240px]">
            <div class="absolute inset-y-0 left-2.5 flex items-center pointer-events-none">
              <span class="material-symbols-outlined text-slate-400" style="font-size: 16px;">search</span>
            </div>
            <input name="q" type="text" placeholder="Search stories or authors..." class="w-full pl-8 pr-3 py-2 bg-white border border-border rounded-lg focus:ring-1 focus:ring-accent/20 focus:border-accent outline-none transition-all text-xs text-slate-900" />
          </div>
          <select id="genre-filter" class="w-40 px-3 py-2 bg-white border border-border rounded-lg focus:ring-1 focus:ring-accent/20 focus:border-accent outline-none transition-all text-xs text-slate-900 font-medium cursor-pointer hover:border-accent">
            <option value="">All Genres</option>
            <option value="romance">Romance</option>
            <option value="mystery">Mystery</option>
            <option value="drama">Drama</option>
            <option value="fantasy">Fantasy</option>
            <option value="sci-fi">Sci-Fi</option>
            <option value="thriller">Thriller</option>
            <option value="comedy">Comedy</option>
            <option value="politic">Politics</option>
            <option value="history">History</option>
          </select>
          <button type="submit" class="px-3 py-2 bg-accent text-white rounded-lg text-xs font-semibold hover:bg-purple-700 transition-all inline-flex items-center gap-1">
            <span class="material-symbols-outlined" style="font-size: 16px;">search</span>
          </button>
        </div>
      </form>
      <div class="flex flex-wrap gap-2 items-center">
        <a href="discover.php?q=romance" class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Romance</a>
        <a href="discover.php?q=mystery" class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Mystery</a>
        <a href="discover.php?q=fantasy" class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Fantasy</a>
        <a href="discover.php?q=drama" class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Drama</a>
        <a href="discover.php?q=sci-fi" class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Sci-Fi</a>
        <a href="discover.php?q=thriller" class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Thriller</a>
        <a href="discover.php?q=comedy" class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Comedy</a>
        <a href="discover.php?q=politic" class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Politics</a>
        <a href="discover.php?q=history" class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">History</a>
        <a href="discover.php?q=adventure" class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Adventure</a>
        <a href="discover.php?q=horror" class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Horror</a>
        <a href="discover.php?q=paranormal" class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Paranormal</a>
        <a href="discover.php?q=supernatural" class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Supernatural</a>
      </div>
    </div>
    
    <!-- Search Results Section -->
    <?php if (!empty($search_query)): ?>
    <section class="mb-12 pb-8 border-b-2 border-slate-200">
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-primary mb-2">Search Results</h2>
        <p class="text-slate-600 text-sm">Found <?php echo count($search_results); ?> result(s) for "<strong><?php echo htmlspecialchars($search_query); ?></strong>"</p>
      </div>
      
      <?php if (count($search_results) > 0): ?>
      <div class="bg-white border border-border rounded-2xl shadow-sm p-5 overflow-x-auto">
        <div class="flex gap-3 min-w-full">
          <?php foreach ($search_results as $story): ?>
          <a href="./story-detail.php?id=<?php echo $story['id']; ?>" class="block">
            <div class="w-[180px] flex-none p-3 rounded-xl border border-border bg-white hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
              <div class="relative w-full aspect-[2/3] rounded overflow-hidden bg-slate-100 book-card-shadow mb-3 group">
                <img src="<?php echo getCoverImagePath($story['cover_image']); ?>" alt="<?php echo htmlspecialchars($story['title']); ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
              </div>
              <div class="space-y-1">
                <div class="flex items-center gap-1 text-[10px] text-slate-500">
                  <span class="px-1.5 py-0.5 bg-slate-100 rounded uppercase tracking-widest font-bold"><?php echo htmlspecialchars(substr($story['genres'] ?? 'Fiction', 0, 15)); ?></span>
                </div>
                <h3 class="text-sm font-bold text-primary leading-tight line-clamp-2"><?php echo htmlspecialchars($story['title']); ?></h3>
                <p class="text-[10px] text-slate-500"><?php echo htmlspecialchars($story['author_name'] ?? 'Unknown'); ?></p>
                <p class="text-[10px] text-slate-600 line-clamp-2"><?php echo htmlspecialchars(truncateText($story['description'] ?? '', 60)); ?></p>
                <p class="text-[10px] text-slate-500">
                  <span class="text-amber-600 font-semibold"><?php echo number_format($story['average_rating'] ?? 0, 1); ?></span>
                </p>
              </div>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php else: ?>
      <div class="text-center py-12 bg-slate-50 rounded-xl border border-border">
        <span class="material-symbols-outlined text-5xl text-slate-300 mb-4 inline-block">search</span>
        <p class="text-slate-600 font-medium">No stories found matching your search</p>
        <p class="text-slate-500 text-sm">Try different keywords or browse featured stories below</p>
      </div>
      <?php endif; ?>
    </section>
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-primary mb-6">Browse More Stories</h2>
    </div>
    <?php endif; ?>
    
    <!-- Only show featured and other sections when NOT searching -->
    <?php if (empty($search_query)): ?>
    
    <!-- Featured Story Hero Section with Auto Transition -->
    <section class="bg-gradient-to-br from-purple-50 via-white to-pink-50 rounded-2xl border border-border mb-10 shadow-lg overflow-hidden">
      <div class="relative">
        <!-- Featured Story Slides -->
        <div id="featured-slider" class="transition-all duration-700 ease-in-out">
          
          <!-- Slide 1: Laut Bercerita -->
          <div class="featured-slide active" data-index="0">
            <div class="grid md:grid-cols-2 gap-8 p-8 md:p-10">
              <div class="flex flex-col justify-center">
                <div class="flex items-center gap-2 mb-4">
                  <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded text-xs font-bold uppercase tracking-wide">Politic</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-primary mb-2">Laut Bercerita</h1>
                <p class="text-lg text-slate-600 mb-6">Leila S. Chudori</p>
                
                <div class="bg-white/80 backdrop-blur-sm border-l-4 border-purple-400 rounded-r-xl p-5 mb-6 shadow-md">
                  <div class="flex items-start gap-3 mb-3">
                    <span class="material-symbols-outlined text-purple-600 text-2xl flex-none">format_quote</span>
                    <p class="text-base text-slate-700 italic leading-relaxed font-serif">
                      "Laut tak pernah benar-benar sunyi. Ia menyimpan cerita-cerita yang tak pernah didengar, jeritan yang tak pernah sampai ke telinga."
                    </p>
                  </div>
                </div>
                
                
                
                
              </div>
              
              <div class="flex items-center justify-center">
                <div class="relative w-48 h-64 rounded-2xl overflow-hidden shadow-2xl transform hover:scale-105 transition-transform duration-300">
                  <img src="./assets/LautBercerita_featured.jpg" alt="Laut Bercerita" class="w-full h-full object-cover" />
                </div>
              </div>
            </div>
          </div>

          <!-- Slide 2: Dilan 1990 -->
          <div class="featured-slide" data-index="1">
            <div class="grid md:grid-cols-2 gap-8 p-8 md:p-10">
              <div class="flex flex-col justify-center">
                <div class="flex items-center gap-2 mb-4">
                  <span class="px-2 py-0.5 bg-pink-100 text-pink-700 rounded text-xs font-bold uppercase tracking-wide">Romance</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-primary mb-2">Dilan 1990</h1>
                <p class="text-lg text-slate-600 mb-6">Pidi Baiq</p>
                
                <div class="bg-white/80 backdrop-blur-sm border-l-4 border-pink-400 rounded-r-xl p-5 mb-6 shadow-md">
                  <div class="flex items-start gap-3 mb-3">
                    <span class="material-symbols-outlined text-pink-600 text-2xl flex-none">format_quote</span>
                    <p class="text-base text-slate-700 italic leading-relaxed font-serif">
                      "Rindu itu berat. Aku tidak ingin kau menanggung bebanku, jadi kupendam sendiri."
                    </p>
                  </div>
                </div>
                
                
                
                
              </div>
              
              <div class="flex items-center justify-center">
                <div class="relative w-48 h-64 rounded-2xl overflow-hidden shadow-2xl transform hover:scale-105 transition-transform duration-300">
                  <img src="./assets/Dilan1990_featured.jpg" alt="Dilan 1990" class="w-full h-full object-cover" />
                </div>
              </div>
            </div>
          </div>

          <!-- Slide 3: Love Me When It Hurts -->
          <div class="featured-slide" data-index="2">
            <div class="grid md:grid-cols-2 gap-8 p-8 md:p-10">
              <div class="flex flex-col justify-center">
                <div class="flex items-center gap-2 mb-4">
                  <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-xs font-bold uppercase tracking-wide">Romance</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-primary mb-2">Love Me When It Hurts</h1>
                <p class="text-lg text-slate-600 mb-6">Wulan Fadi</p>
                
                <div class="bg-white/80 backdrop-blur-sm border-l-4 border-emerald-400 rounded-r-xl p-5 mb-6 shadow-md">
                  <div class="flex items-start gap-3 mb-3">
                    <span class="material-symbols-outlined text-emerald-600 text-2xl flex-none">format_quote</span>
                    <p class="text-base text-slate-700 italic leading-relaxed font-serif">
                      "Cinta sejati bukan tentang mencintai di saat mudah, tapi tentang tetap bertahan di saat sakit hati."
                    </p>
                  </div>
                </div>
                
                
                
                
              </div>
              
              <div class="flex items-center justify-center">
                <div class="relative w-48 h-64 rounded-2xl overflow-hidden shadow-2xl transform hover:scale-105 transition-transform duration-300">
                  <img src="./assets/LoveMeWhenItHurts_featured.jpg" alt="Love Me When It Hurts" class="w-full h-full object-cover" />
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- Slider Controls -->
        <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex items-center gap-3 z-10">
          <button onclick="prevSlide()" class="w-10 h-10 rounded-full bg-white/90 backdrop-blur-sm border border-slate-200 flex items-center justify-center hover:bg-white transition-all shadow-lg">
            <span class="material-symbols-outlined text-slate-700">chevron_left</span>
          </button>
          
          <div class="flex gap-2">
            <button onclick="goToSlide(0)" class="slide-dot w-2 h-2 rounded-full bg-purple-600 transition-all"></button>
            <button onclick="goToSlide(1)" class="slide-dot w-2 h-2 rounded-full bg-slate-300 transition-all"></button>
            <button onclick="goToSlide(2)" class="slide-dot w-2 h-2 rounded-full bg-slate-300 transition-all"></button>
          </div>
          
          <button onclick="nextSlide()" class="w-10 h-10 rounded-full bg-white/90 backdrop-blur-sm border border-slate-200 flex items-center justify-center hover:bg-white transition-all shadow-lg">
            <span class="material-symbols-outlined text-slate-700">chevron_right</span>
          </button>
        </div>
      </div>
    </section>


    <section class="mb-14">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl md:text-3xl font-bold text-primary">Cerita teratas minggu ini</h2>
        <a href="discover.php" class="text-sm font-semibold text-accent hover:underline">See all →</a>
      </div>
      <div class="bg-white border border-border rounded-2xl shadow-sm p-5 overflow-x-auto">
        <div class="flex gap-3 min-w-full">
          <?php 
          if (!empty($featured_stories)):
            foreach($featured_stories as $index => $story):
              $rank = $index + 1;
              $bg_color = match($rank) {
                1 => 'from-emerald-50',
                2 => 'from-blue-50',
                3 => 'from-purple-50',
                default => 'from-slate-50'
              };
              $badge_color = match($rank) {
                1 => 'bg-slate-900',
                2 => 'bg-slate-800',
                3 => 'bg-slate-700',
                default => 'bg-slate-600'
              };
          ?>
          <a href="story-detail.php?id=<?php echo (int)$story['id']; ?>" class="block">
            <div class="w-[180px] flex-none p-3 rounded-xl border border-border bg-gradient-to-br <?php echo $bg_color; ?> via-white to-white shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
              <div class="relative w-full aspect-[2/3] rounded overflow-hidden bg-slate-100 book-card-shadow mb-3 group">
                <img src="<?php echo getCoverImagePath($story['cover_image']); ?>" alt="<?php echo escape($story['title']); ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                <span class="absolute top-2 left-2 w-8 h-8 rounded-full <?php echo $badge_color; ?> text-white text-xs font-bold flex items-center justify-center shadow-lg"><?php echo $rank; ?></span>
              </div>
              <div class="space-y-1">
                <div class="flex items-center gap-1 text-[10px] text-slate-500">
                  <span class="px-1.5 py-0.5 bg-slate-100 rounded uppercase tracking-widest font-bold"><?php echo escape(substr($story['genres'], 0, 15)); ?></span>
                </div>
                <h3 class="text-sm font-bold text-primary leading-tight line-clamp-2"><?php echo escape($story['title']); ?></h3>
                <p class="text-[10px] text-slate-500"><?php echo escape($story['author_name']); ?></p>
                <p class="text-[10px] text-slate-500">
                  <span class="text-amber-600 font-semibold"><?php echo number_format($story['average_rating'], 1); ?></span>
                  <span class="text-slate-400">| <?php echo (int)$story['total_views']; ?> views</span>
                </p>
              </div>
            </div>
          </a>
          <?php 
            endforeach;
          endif;
          ?>
        </div>
      </div>
    </section>

    <section class="mb-14">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl md:text-3xl font-bold text-primary">Trending by genre</h2>
        <a href="#" class="text-sm font-semibold text-accent hover:underline">Browse all →</a>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php 
        if (!empty($trending_data)):
          foreach($trending_genres as $genre):
            $genre_stories = $trending_data[$genre];
            $styles = isset($genre_styles[$genre]) ? $genre_styles[$genre] : $genre_styles['Romance'];
            
            // Calculate average rating for progress bar
            $avg_rating = 0;
            if (!empty($genre_stories)) {
              $ratings = array_map(function($s) { return $s['average_rating'] ?? 0; }, $genre_stories);
              $avg_rating = !empty($ratings) ? (array_sum($ratings) / count($ratings)) : 0;
            }
            $progress_width = $avg_rating > 0 ? min(100, ($avg_rating / 5) * 100) : 0;
        ?>
        <div class="p-5 bg-gradient-to-br <?php echo $styles['gradient']; ?> border border-border rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
          <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2 text-sm font-semibold <?php echo $styles['text_color']; ?>">
              <span class="material-symbols-outlined text-base"><?php echo $styles['icon']; ?></span><?php echo $genre; ?>
            </div>
            <span class="text-xs px-2 py-0.5 <?php echo $styles['light_bg']; ?> <?php echo $styles['text_color']; ?> rounded-full border <?php echo $styles['border_color']; ?>">Ratings & Views</span>
          </div>
          <div class="h-1.5 w-full bg-white rounded-full border <?php echo $styles['border_color']; ?> mb-4 overflow-hidden">
            <div class="h-full <?php echo $styles['progress_bar']; ?>" style="width: <?php echo $progress_width; ?>%"></div>
          </div>
          <ul class="space-y-3 text-sm text-slate-800">
            <?php 
            if (!empty($genre_stories)):
              foreach($genre_stories as $index => $story):
                $rank = $index + 1;
                $cover_image = getCoverImagePath($story['cover_image']);
                $rating = round($story['average_rating'], 1);
                $total_ratings = (int)$story['total_ratings'];
            ?>
            <li>
              <a href="story-detail.php?id=<?php echo (int)$story['id']; ?>" class="flex items-start gap-3">
                <div class="w-12 h-16 rounded overflow-hidden bg-slate-100 book-card-shadow flex-none group">
                  <img src="<?php echo $cover_image; ?>" alt="<?php echo htmlspecialchars($story['title']); ?> cover" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                </div>
                <div class="flex-1 flex justify-between gap-3">
                  <div class="flex flex-col">
                    <span class="font-semibold line-clamp-1"><?php echo htmlspecialchars($story['title']); ?></span>
                    <span class="text-xs text-slate-500">Rating <?php echo $rating; ?> | <?php echo (int)$story['total_views']; ?> views</span>
                  </div>
                  <span class="text-xs px-2 py-0.5 <?php echo $rank === 1 ? $styles['bg_color'] : 'bg-slate-50'; ?> <?php echo $styles['text_color']; ?> rounded-full h-fit flex-none">#<?php echo $rank; ?></span>
                </div>
              </a>
            </li>
            <?php 
              endforeach;
            else:
            ?>
            <li class="text-sm text-slate-500 text-center py-4">No stories found in this genre yet.</li>
            <?php 
            endif;
            ?>
          </ul>
        </div>
        <?php 
          endforeach;
        endif;
        ?>
      </div>
    </section>

    <section class="mb-14">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl md:text-3xl font-bold text-primary">Latest releases</h2>
        <a href="#" class="text-sm font-semibold text-accent hover:underline">View all →</a>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php 
        if (!empty($latest_releases)):
          foreach($latest_releases as $story):
            $cover_image = getCoverImagePath($story['cover_image']);
            $first_genre = !empty($story['genres']) ? explode(',', $story['genres'])[0] : 'Other';
            $first_genre = trim($first_genre);
        ?>
        <a href="story-detail.php?id=<?php echo (int)$story['id']; ?>" class="block">
          <article class="p-5 bg-white border border-border rounded-2xl shadow-sm hover:shadow-md transition-shadow flex gap-4">
            <div class="w-20 flex-none">
              <div class="aspect-[2/3] bg-slate-100 rounded-lg overflow-hidden book-card-shadow">
                <img src="<?php echo $cover_image; ?>" alt="<?php echo htmlspecialchars($story['title']); ?>" class="w-full h-full object-cover" />
              </div>
            </div>
            <div class="flex flex-col">
              <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <span class="px-2 py-0.5 bg-slate-100 rounded uppercase tracking-widest font-bold"><?php echo htmlspecialchars($first_genre); ?></span>
                <span class="text-slate-300">|</span>
                <span class="text-emerald-600 font-semibold">New</span>
              </div>
              <h3 class="text-lg font-bold text-primary leading-tight line-clamp-2"><?php echo htmlspecialchars($story['title']); ?></h3>
              <p class="text-xs text-slate-500 mb-2">
                <span class="text-amber-600 font-semibold"><?php echo $story['average_rating'] > 0 ? round($story['average_rating'], 1) : 'N/A'; ?></span>
                <span class="text-slate-400">| <?php echo (int)$story['total_views']; ?> views</span>
              </p>
              <p class="text-sm text-slate-600 line-clamp-3 italic"><?php echo htmlspecialchars(truncateText($story['description'] ?? '', 100)); ?></p>
            </div>
          </article>
        </a>
        <?php 
          endforeach;
        else:
        ?>
        <div class="col-span-3 text-center py-8 text-slate-500">
          <p>No releases found yet.</p>
        </div>
        <?php 
        endif;
        ?>
      </div>
    </section>

    <section id="community" class="mb-12">
      <div class="bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 text-white rounded-2xl p-12 text-center shadow-lg">
        <p class="text-xs uppercase tracking-widest font-bold text-purple-200 mb-3">Join community</p>
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Share your story</h2>
        <p class="text-white/80 mb-8 max-w-2xl mx-auto">Publish your work, get feedback from readers, or help surface the best stories for the community.</p>
        <div class="flex gap-4 justify-center flex-wrap">
          <a href="./write.php" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-purple-600 rounded-xl font-bold hover:bg-slate-100 transition-all shadow-lg">
            <span class="material-symbols-outlined">edit</span>
            Start Writing
          </a>
          <a href="#" class="inline-flex items-center gap-2 px-8 py-4 bg-white/10 hover:bg-white/20 border border-white/30 text-white rounded-xl font-bold transition-all backdrop-blur-sm">
            <span class="material-symbols-outlined">rate_review</span>
            Become Reviewer
          </a>
        </div>
      </div>
    </section>

  </main>

  <footer class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-16 mt-20">
    <div class="max-w-6xl mx-auto px-6">
      <div class="grid grid-cols-1 md:grid-cols-5 gap-12 mb-12">
        <!-- Brand Section -->
        <div class="md:col-span-2">
          <a href="./index.php" class="flex items-center gap-3 mb-6">
            <img src="./assets/logo.png" alt="CeritaKu logo" class="w-24 h-12 object-contain brightness-0 invert" />
          </a>
          <p class="text-slate-300 text-sm leading-relaxed max-w-xs mb-6">Platform literatur independen yang menghubungkan penulis berbakat dengan pembaca yang passionate tentang cerita berkualitas.</p>
          <div class="flex gap-4">
            <a href="#" class="p-2 bg-slate-700 hover:bg-accent text-white rounded-lg transition-colors">
              <span class="material-symbols-outlined text-lg">language</span>
            </a>
            <a href="#" class="p-2 bg-slate-700 hover:bg-accent text-white rounded-lg transition-colors">
              <span class="material-symbols-outlined text-lg">mail</span>
            </a>
            <a href="#" class="p-2 bg-slate-700 hover:bg-accent text-white rounded-lg transition-colors">
              <span class="material-symbols-outlined text-lg">share</span>
            </a>
          </div>
        </div>

        <!-- Discover Column -->
        <div>
          <h6 class="text-xs font-bold uppercase tracking-widest text-accent mb-6">Jelajahi</h6>
          <ul class="space-y-3 text-sm text-slate-300">
            <li><a class="hover:text-accent transition-colors" href="./discover.php">Browse Stories</a></li>
            <li><a class="hover:text-accent transition-colors" href="./discover.php">Top Picks</a></li>
            <li><a class="hover:text-accent transition-colors" href="./discover.php">Trending</a></li>
            <li><a class="hover:text-accent transition-colors" href="./reviews.php">Reviews</a></li>
          </ul>
        </div>

        <!-- For Authors Column -->
        <div>
          <h6 class="text-xs font-bold uppercase tracking-widest text-accent mb-6">Untuk Penulis</h6>
          <ul class="space-y-3 text-sm text-slate-300">
            <li><a class="hover:text-accent transition-colors" href="./write.php">Tulis Cerita</a></li>
            <li><a class="hover:text-accent transition-colors" href="./create_story.php">Publikasikan</a></li>
            <li><a class="hover:text-accent transition-colors" href="#">Dashboard</a></li>
            <li><a class="hover:text-accent transition-colors" href="#">Monetisasi</a></li>
          </ul>
        </div>

        <!-- Support Column -->
        <div>
          <h6 class="text-xs font-bold uppercase tracking-widest text-accent mb-6">Bantuan</h6>
          <ul class="space-y-3 text-sm text-slate-300">
            <li><a class="hover:text-accent transition-colors" href="#">Tentang Kami</a></li>
            <li><a class="hover:text-accent transition-colors" href="#">Kebijakan</a></li>
            <li><a class="hover:text-accent transition-colors" href="#">Hubungi Kami</a></li>
            <li><a class="hover:text-accent transition-colors" href="#">FAQ</a></li>
          </ul>
        </div>
      </div>

      <!-- Divider -->
      <div class="border-t border-slate-700 pt-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
          <p class="text-slate-400 text-xs font-medium">© 2026 CeritaKu. Semua hak dilindungi. Platform literatur independen Indonesia.</p>
          <div class="flex gap-8 text-slate-400 text-xs">
            <a href="#" class="hover:text-accent transition-colors">Privacy Policy</a>
            <a href="#" class="hover:text-accent transition-colors">Terms of Service</a>
            <a href="#" class="hover:text-accent transition-colors">Cookie Settings</a>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <script src="./js/main.js"></script>
  <script>
    // Featured Stories Slider
    let currentSlide = 0;
    let autoSlideInterval;
    const slides = document.querySelectorAll('.featured-slide');
    const dots = document.querySelectorAll('.slide-dot');
    const totalSlides = slides.length;

    function showSlide(index) {
      // Remove active class from all slides
      slides.forEach(slide => slide.classList.remove('active'));
      dots.forEach(dot => {
        dot.classList.remove('bg-purple-600');
        dot.classList.add('bg-slate-300');
      });
      
      // Add active class to current slide
      slides[index].classList.add('active');
      dots[index].classList.remove('bg-slate-300');
      dots[index].classList.add('bg-purple-600');
      currentSlide = index;
    }

    function nextSlide() {
      let next = (currentSlide + 1) % totalSlides;
      showSlide(next);
      resetAutoSlide();
    }

    function prevSlide() {
      let prev = (currentSlide - 1 + totalSlides) % totalSlides;
      showSlide(prev);
      resetAutoSlide();
    }

    function goToSlide(index) {
      showSlide(index);
      resetAutoSlide();
    }

    function startAutoSlide() {
      autoSlideInterval = setInterval(() => {
        nextSlide();
      }, 5000); // Auto transition every 5 seconds
    }

    function resetAutoSlide() {
      clearInterval(autoSlideInterval);
      startAutoSlide();
    }

    // Initialize slider
    showSlide(0);
    startAutoSlide();

    // Pause auto-slide when hovering over slider
    const slider = document.getElementById('featured-slider');
    slider.addEventListener('mouseenter', () => {
      clearInterval(autoSlideInterval);
    });

    slider.addEventListener('mouseleave', () => {
      startAutoSlide();
    });
  </script>
    
    <?php endif; ?>
    <?php require 'includes/login-modal.php'; ?>
    <?php require 'includes/register-modal.php'; ?>
</body>
</html>
