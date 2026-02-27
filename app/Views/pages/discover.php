<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
/* ── Slider ─────────────────────────────────────────────── */
.featured-slide {
  display: none;
  animation: slideReveal 0.6s cubic-bezier(0.22,1,0.36,1);
}
.featured-slide.active { display: block; }

@keyframes slideReveal {
  from { opacity: 0; transform: translateY(16px); }
  to   { opacity: 1; transform: translateY(0); }
}

.slide-dot {
  cursor: pointer;
  transition: all 0.3s ease;
}
.slide-dot:hover { transform: scale(1.4); }

/* ── Book card shadow ───────────────────────────────────── */
.book-card-shadow {
  box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
}

/* ── Scroll Reveal ──────────────────────────────────────── */
.reveal {
  opacity: 0;
  transform: translateY(32px);
  transition: opacity 0.65s cubic-bezier(0.22,1,0.36,1),
              transform 0.65s cubic-bezier(0.22,1,0.36,1);
}
.reveal.visible { opacity: 1; transform: translateY(0); }

/* Stagger delays */
.d1 { transition-delay: 0.05s; }
.d2 { transition-delay: 0.12s; }
.d3 { transition-delay: 0.19s; }
.d4 { transition-delay: 0.26s; }
.d5 { transition-delay: 0.33s; }
.d6 { transition-delay: 0.40s; }

/* ── Genre filter pill hover ────────────────────────────── */
.genre-pill {
  transition: background 0.2s, color 0.2s, transform 0.15s, box-shadow 0.15s;
}
.genre-pill:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.genre-pill:active { transform: translateY(0); }

/* ── Story card hover lift ──────────────────────────────── */
.story-card {
  transition: transform 0.25s cubic-bezier(0.22,1,0.36,1),
              box-shadow 0.25s cubic-bezier(0.22,1,0.36,1);
}
.story-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 16px 32px rgba(0,0,0,0.1);
}

/* ── Progress bar animate ───────────────────────────────── */
.progress-bar-fill {
  width: 0 !important;
  transition: width 1s cubic-bezier(0.22,1,0.36,1);
}
.progress-bar-fill.animated { width: var(--target-width) !important; }

/* ── Ripple ─────────────────────────────────────────────── */
.ripple-btn { position: relative; overflow: hidden; }
.ripple {
  position: absolute;
  border-radius: 50%;
  transform: scale(0);
  animation: ripple-anim 0.55s linear;
  background: rgba(255,255,255,0.3);
  pointer-events: none;
}
@keyframes ripple-anim { to { transform: scale(4); opacity: 0; } }


/* ── Author card pulse ring on hover ────────────────────── */
.author-card {
  transition: transform 0.25s, box-shadow 0.25s;
}
.author-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(139,92,246,0.15); }
.author-avatar {
  transition: box-shadow 0.25s;
}
.author-card:hover .author-avatar {
  box-shadow: 0 0 0 4px rgba(139,92,246,0.25);
}

/* ── Search bar focus glow ──────────────────────────────── */
.search-input:focus {
  box-shadow: 0 0 0 3px rgba(139,92,246,0.15);
}

/* ── Page entrance ──────────────────────────────────────── */
.page-enter {
  animation: pageEnter 0.5s cubic-bezier(0.22,1,0.36,1) both;
}
@keyframes pageEnter {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>

<main class="max-w-6xl mx-auto px-6 py-10 page-enter">
    
    <!-- Search Section -->
    <div class="mb-8">
      <form method="GET" action="<?= base_url('/discover') ?>" class="mb-3">
        <div class="flex gap-2 flex-wrap items-center">
          <div class="relative flex-[3] min-w-[240px]">
            <div class="absolute inset-y-0 left-2.5 flex items-center pointer-events-none">
              <span class="material-symbols-outlined text-slate-400" style="font-size: 16px;">search</span>
            </div>
            <input name="q" type="text" value="<?= esc($search_query ?? '') ?>" placeholder="Search stories or authors..." class="search-input w-full pl-8 pr-3 py-2 bg-white border border-border rounded-lg focus:ring-1 focus:ring-accent/20 focus:border-accent outline-none transition-all text-xs text-slate-900" />
          </div>
          <button type="submit" class="px-3 py-2 bg-accent text-white rounded-lg text-xs font-semibold hover:bg-purple-700 transition-all inline-flex items-center gap-1">
            <span class="material-symbols-outlined" style="font-size: 16px;">search</span>
          </button>
        </div>
      </form>
      <div class="flex flex-wrap gap-2 items-center">
        <a href="<?= base_url('/discover') ?>" class="genre-pill px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">All</a>
        <a href="<?= base_url('/discover?genre=Romance') ?>" class="genre-pill px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Romance</a>
        <a href="<?= base_url('/discover?genre=Mystery') ?>" class="genre-pill px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Mystery</a>
        <a href="<?= base_url('/discover?genre=Fantasy') ?>" class="genre-pill px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Fantasy</a>
        <a href="<?= base_url('/discover?genre=Drama') ?>" class="genre-pill px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Drama</a>
        <a href="<?= base_url('/discover?genre=Sci-Fi') ?>" class="genre-pill px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Sci-Fi</a>
        <a href="<?= base_url('/discover?genre=Thriller') ?>" class="genre-pill px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Thriller</a>
        <a href="<?= base_url('/discover?genre=Horror') ?>" class="genre-pill px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Horror</a>
        <a href="<?= base_url('/discover?genre=Comedy') ?>" class="genre-pill px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Comedy</a>
        <a href="<?= base_url('/discover?genre=Adventure') ?>" class="genre-pill px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Adventure</a>
        <a href="<?= base_url('/discover?genre=Historical') ?>" class="genre-pill px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Historical</a>
        <a href="<?= base_url('/discover?genre=Adventure') ?>" class="genre-pill px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium hover:bg-accent hover:text-white transition-all cursor-pointer">Teen</a>      </div>
    </div>
    
    <!-- Search Results Section -->
    <?php 
    // Pastikan $stories selalu array agar tidak error pada count()
    if (!isset($stories) || !is_array($stories)) {
        $stories = [];
    }
    ?>
    <?php if (!empty($search_query)): ?>
    <section class="mb-12 pb-8 border-b-2 border-slate-200">
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-primary mb-2">Search Results</h2>
        <p class="text-slate-600 text-sm">Found <?= count($stories) ?> result(s) for "<strong><?= esc($search_query) ?></strong>"</p>
      </div>
      <?php if (count($stories) > 0): ?>
        <div class="bg-white border border-border rounded-2xl shadow-sm p-5 overflow-x-auto">
          <div class="flex gap-3 min-w-full">
            <?php foreach ($stories as $story): ?>
            <a href="<?= base_url('/story/' . $story['id']) ?>" class="block">
              <div class="w-[180px] flex-none p-3 rounded-xl border border-border bg-white hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                <div class="relative w-full aspect-[2/3] rounded overflow-hidden bg-slate-100 book-card-shadow mb-3 group">
                  <?php if (!empty($story['cover_image'])): ?>
                    <img src="<?= cover_url($story['cover_image']) ?>" alt="<?= esc($story['title']) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                  <?php else: ?>
                    <div class="w-full h-full bg-gradient-to-br from-purple-200 to-purple-100 flex items-center justify-center">
                      <span class="material-symbols-outlined text-purple-400 text-[48px]">auto_stories</span>
                    </div>
                  <?php endif; ?>
                </div>
                <div class="space-y-1">
                  <div class="flex items-center gap-1 text-[10px] text-slate-500">
                    <span class="px-1.5 py-0.5 bg-slate-100 rounded uppercase tracking-widest font-bold"><?= esc(trim(explode(', ', $story['genres'] ?? 'Fiction')[0])) ?></span>
                  </div>
                  <h3 class="text-sm font-bold text-primary leading-tight line-clamp-2"><?= esc($story['title']) ?></h3>
                  <p class="text-[10px] text-slate-500"><?= esc($story['author_name'] ?? 'Unknown') ?></p>
                  <p class="text-[10px] text-slate-500">
                    <span class="text-amber-600 font-semibold"><?= number_format($story['avg_rating'] ?? 0, 1) ?></span>
                    <span class="text-slate-400">| <?= number_format($story['total_views'] ?? 0) ?> reads</span>
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
<?php endif; ?>

<!-- Genre Filter Results Section -->
<?php if (empty($search_query) && !empty($current_genre)): ?>
                  <section class="mb-12 pb-8 border-b-2 border-slate-200">
                    <div class="mb-6">
                      <h2 class="text-2xl font-bold text-primary mb-2">Genre: <?= esc($current_genre) ?></h2>
                      <p class="text-slate-600 text-sm">Found <?= count($stories) ?> result(s) for genre "<strong><?= esc($current_genre) ?></strong>"</p>
                    </div>
                    <?php if (count($stories) > 0): ?>
                    <div class="bg-white border border-border rounded-2xl shadow-sm p-5 overflow-x-auto">
                      <div class="flex gap-3 min-w-full">
                        <?php foreach ($stories as $story): ?>
                        <a href="<?= base_url('/story/' . $story['id']) ?>" class="block">
                          <div class="w-[180px] flex-none p-3 rounded-xl border border-border bg-white hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                            <div class="relative w-full aspect-[2/3] rounded overflow-hidden bg-slate-100 book-card-shadow mb-3 group">
                              <?php if (!empty($story['cover_image'])): ?>
                                <img src="<?= cover_url($story['cover_image']) ?>" alt="<?= esc($story['title']) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                              <?php else: ?>
                                <div class="w-full h-full bg-gradient-to-br from-purple-200 to-purple-100 flex items-center justify-center">
                                  <span class="material-symbols-outlined text-purple-400 text-[48px]">auto_stories</span>
                                </div>
                              <?php endif; ?>
                            </div>
                            <div class="space-y-1">
                              <div class="flex items-center gap-1 text-[10px] text-slate-500">
                                <span class="px-1.5 py-0.5 bg-slate-100 rounded uppercase tracking-widest font-bold"><?= esc(trim(explode(', ', $story['genres'] ?? 'Fiction')[0])) ?></span>
                              </div>
                              <h3 class="text-sm font-bold text-primary leading-tight line-clamp-2"><?= esc($story['title']) ?></h3>
                              <p class="text-[10px] text-slate-500"><?= esc($story['author_name'] ?? 'Unknown') ?></p>
                              <p class="text-[10px] text-slate-500">
                                <span class="text-amber-600 font-semibold"><?= number_format($story['avg_rating'] ?? 0, 1) ?></span>
                                <span class="text-slate-400">| <?= number_format($story['total_views'] ?? 0) ?> reads</span>
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
                      <p class="text-slate-600 font-medium">No stories found in this genre</p>
                    </div>
                    <?php endif; ?>
                  </section>
                  <?php endif; ?>
    
    <!-- Only show featured and other sections when NOT searching or filtering by genre -->
    <?php if (empty($search_query) && empty($current_genre)): ?>
    
    <!-- Featured Story Hero Section -->
    <section class="reveal bg-gradient-to-br from-purple-50 via-white to-pink-50 rounded-2xl border border-border mb-10 shadow-lg overflow-hidden">
      <div class="relative">
        <div id="featured-slider" class="transition-all duration-700 ease-in-out">
          <?php 
          $featured_count = min(3, count($featured_stories ?? []));
          for ($i = 0; $i < $featured_count; $i++): 
            $story = $featured_stories[$i];
            $isActive = $i === 0 ? 'active' : '';
          ?>
          <div class="featured-slide <?= $isActive ?>" data-index="<?= $i ?>">
            <div class="grid md:grid-cols-2 gap-8 p-8 md:p-10">
              <div class="flex flex-col justify-center">
                <div class="flex items-center gap-2 mb-4">
                  <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded text-xs font-bold uppercase tracking-wide"><?= esc(trim(explode(', ', $story['genres'] ?? 'Fiction')[0])) ?></span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-primary mb-2"><?= esc($story['title']) ?></h1>
                <p class="text-lg text-slate-600 mb-6"><?= esc($story['author_name'] ?? 'Unknown') ?></p>
                
                <div class="bg-white/80 backdrop-blur-sm border-l-4 border-purple-400 rounded-r-xl p-5 mb-6 shadow-md">
                  <div class="flex items-start gap-3 mb-3">
                    <span class="material-symbols-outlined text-purple-600 text-2xl flex-none">format_quote</span>
                    <p class="text-base text-slate-700 italic leading-relaxed font-serif">
                      <?= esc(substr($story['description'] ?? '', 0, 150)) ?>...
                    </p>
                  </div>
                </div>
              </div>
              
              <div class="flex items-center justify-center">
                <div class="relative w-48 h-64 rounded-2xl overflow-hidden shadow-2xl transform hover:scale-105 transition-transform duration-300">
                  <?php if (!empty($story['cover_image'])): ?>
                    <img src="<?= cover_url($story['cover_image']) ?>" alt="<?= esc($story['title']) ?>" class="w-full h-full object-cover" />
                  <?php else: ?>
                    <div class="w-full h-full bg-gradient-to-br from-purple-300 to-purple-500 flex items-center justify-center">
                      <span class="material-symbols-outlined text-white text-[120px]">auto_stories</span>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          <?php endfor; ?>
        </div>

        <!-- Slider Controls -->
        <?php if ($featured_count > 1): ?>
        <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex items-center gap-3 z-10">
          <button onclick="prevSlide()" class="w-10 h-10 rounded-full bg-white/90 backdrop-blur-sm border border-slate-200 flex items-center justify-center hover:bg-white transition-all shadow-lg">
            <span class="material-symbols-outlined text-slate-700">chevron_left</span>
          </button>
          
          <div class="flex gap-2">
            <?php for ($i = 0; $i < $featured_count; $i++): ?>
              <button onclick="goToSlide(<?= $i ?>)" class="slide-dot w-2 h-2 rounded-full <?= $i === 0 ? 'bg-purple-600' : 'bg-slate-300' ?> transition-all"></button>
            <?php endfor; ?>
          </div>
          
          <button onclick="nextSlide()" class="w-10 h-10 rounded-full bg-white/90 backdrop-blur-sm border border-slate-200 flex items-center justify-center hover:bg-white transition-all shadow-lg">
            <span class="material-symbols-outlined text-slate-700">chevron_right</span>
          </button>
        </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Top picks this week -->
    <section class="mb-14 reveal">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl md:text-3xl font-bold text-primary">Top picks this week</h2>
        <a href="<?= base_url('/discover/all') ?>" class="text-sm font-semibold text-accent hover:underline">See all →</a>
      </div>
      <div class="bg-white border border-border rounded-2xl shadow-sm p-5 overflow-x-auto">
        <div class="flex gap-3 min-w-full">
          <?php 
          $rank_colors = ['from-emerald-50', 'from-blue-50', 'from-purple-50', 'from-slate-50'];
          $badge_colors = ['bg-slate-900', 'bg-slate-800', 'bg-slate-700', 'bg-slate-600'];
          
          foreach($stories as $index => $story):
            $rank = $index + 1;
            $bg_color = $rank_colors[min($index, 3)];
            $badge_color = $badge_colors[min($index, 3)];
          ?>
          <a href="<?= base_url('/story/' . $story['id']) ?>" class="block">
            <div class="story-card w-[180px] flex-none p-3 rounded-xl border border-border bg-gradient-to-br <?= $bg_color ?> via-white to-white shadow-sm">
              <div class="relative w-full aspect-[2/3] rounded overflow-hidden bg-slate-100 book-card-shadow mb-3 group">
                <?php if (!empty($story['cover_image'])): ?>
                  <img src="<?= cover_url($story['cover_image']) ?>" alt="<?= esc($story['title']) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                <?php else: ?>
                  <div class="w-full h-full bg-gradient-to-br from-purple-200 to-purple-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-purple-400 text-[48px]">auto_stories</span>
                  </div>
                <?php endif; ?>
                <span class="absolute top-2 left-2 w-8 h-8 rounded-full <?= $badge_color ?> text-white text-xs font-bold flex items-center justify-center shadow-lg"><?= $rank ?></span>
              </div>
              <div class="space-y-1">
                <div class="flex items-center gap-1 text-[10px] text-slate-500">
                  <span class="px-2 py-0.5 bg-slate-100 text-[10px] font-bold uppercase tracking-wider text-slate-500 rounded"><?= esc(trim(explode(', ', $story['genres'] ?? 'Fiction')[0])) ?></span>
                </div>
                <h3 class="text-sm font-bold text-primary leading-tight line-clamp-2"><?= esc($story['title']) ?></h3>
                <p class="text-[10px] text-slate-500"><?= esc($story['author_name'] ?? 'Unknown') ?></p>
                <p class="text-[10px] text-slate-500">
                  <span class="text-amber-600 font-semibold"><?= number_format($story['avg_rating'] ?? 0, 1) ?></span>
                  <span class="text-slate-400">| <?= number_format($story['total_views'] ?? 0) ?> reads</span>
                </p>
              </div>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Trending by genre -->
    <section class="mb-14 reveal">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl md:text-3xl font-bold text-primary">Trending by genre</h2>
        <a href="<?= base_url('/discover') ?>" class="text-sm font-semibold text-accent hover:underline">Browse all →</a>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php 
        $genre_styles = [
          'Romance' => [
            'gradient' => 'from-pink-50 via-white to-white',
            'icon' => 'favorite',
            'progress_bar' => 'bg-pink-400',
            'text_color' => 'text-pink-700',
            'bg_color' => 'bg-pink-100',
            'border_color' => 'border-pink-100',
            'light_bg' => 'bg-white/80',
          ],
          'Mystery' => [
            'gradient' => 'from-amber-50 via-white to-white',
            'icon' => 'visibility',
            'progress_bar' => 'bg-amber-400',
            'text_color' => 'text-amber-700',
            'bg_color' => 'bg-amber-100',
            'border_color' => 'border-amber-100',
            'light_bg' => 'bg-white/80',
          ],
          'Fantasy' => [
            'gradient' => 'from-purple-50 via-white to-white',
            'icon' => 'auto_awesome',
            'progress_bar' => 'bg-purple-400',
            'text_color' => 'text-purple-700',
            'bg_color' => 'bg-purple-100',
            'border_color' => 'border-purple-100',
            'light_bg' => 'bg-white/80',
          ],
        ];

        if (!empty($trending_data)):
          foreach ($trending_genres as $genre):
            $genre_stories = $trending_data[$genre] ?? [];
            $styles = $genre_styles[$genre] ?? $genre_styles['Romance'];
            // Calculate average rating for progress bar
            $avg_rating = 0;
            if (!empty($genre_stories)) {
              $ratings = array_map(function($s) { return $s['avg_rating'] ?? 0; }, $genre_stories);
              $avg_rating = !empty($ratings) ? (array_sum($ratings) / count($ratings)) : 0;
            }
            $progress_width = min(100, ($avg_rating / 5) * 100);
        ?>
        <div class="p-5 bg-gradient-to-br <?= $styles['gradient'] ?> border border-border rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
          <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2 text-sm font-semibold <?= $styles['text_color'] ?>">
              <span class="material-symbols-outlined text-base"><?= $styles['icon'] ?></span><?= $genre ?>
            </div>
            <span class="text-xs px-2 py-0.5 <?= $styles['light_bg'] ?> <?= $styles['text_color'] ?> rounded-full border <?= $styles['border_color'] ?>">Ratings & Reads</span>
          </div>
          <div class="h-1.5 w-full bg-white rounded-full border <?= $styles['border_color'] ?> mb-4 overflow-hidden">
            <div class="progress-bar-fill h-full <?= $styles['progress_bar'] ?>" style="--target-width: <?= $progress_width ?>%"></div>
          </div>
          <ul class="space-y-3 text-sm text-slate-800">
            <?php 
            if (!empty($genre_stories)):
              foreach ($genre_stories as $index => $story):
                $rank = $index + 1;
            ?>
            <li>
              <a href="<?= base_url('/story/' . $story['id']) ?>" class="flex items-start gap-3">
                <div class="w-12 h-16 rounded overflow-hidden bg-slate-100 book-card-shadow flex-none group">
                  <?php if (!empty($story['cover_image'])): ?>
                    <img src="<?= cover_url($story['cover_image']) ?>" alt="<?= esc($story['title']) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                  <?php else: ?>
                    <div class="w-full h-full bg-gradient-to-br from-purple-200 to-purple-100 flex items-center justify-center">
                      <span class="material-symbols-outlined text-purple-400 text-[24px]">auto_stories</span>
                    </div>
                  <?php endif; ?>
                </div>
                <div class="flex-1 flex justify-between gap-3">
                  <div class="flex flex-col">
                    <span class="font-semibold line-clamp-1"><?= esc($story['title']) ?></span>
                    <span class="text-xs text-slate-500">Rating <?= number_format($story['avg_rating'] ?? 0, 1) ?> | <?= number_format($story['total_views'] ?? 0) ?> dibaca</span>
                  </div>
                  <span class="text-xs px-2 py-0.5 <?= $rank === 1 ? $styles['bg_color'] : 'bg-slate-50' ?> <?= $styles['text_color'] ?> rounded-full h-fit flex-none">#<?= $rank ?></span>
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

    <!-- Latest releases -->
    <section class="mb-14 reveal">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl md:text-3xl font-bold text-primary">Latest releases</h2>
        <a href="<?= base_url('/discover') ?>" class="text-sm font-semibold text-accent hover:underline">View all →</a>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php 
        if (!empty($latest_releases)):
          foreach ($latest_releases as $story):
            $first_genre = !empty($story['genres']) ? explode(',', $story['genres'])[0] : 'Other';
            $first_genre = trim($first_genre);
        ?>
        <a href="<?= base_url('/story/' . $story['id']) ?>" class="block">
          <article class="story-card p-5 bg-white border border-border rounded-2xl shadow-sm flex gap-4">
            <div class="w-20 flex-none">
              <div class="aspect-[2/3] bg-slate-100 rounded-lg overflow-hidden book-card-shadow">
                <?php if (!empty($story['cover_image'])): ?>
                  <img src="<?= cover_url($story['cover_image']) ?>" alt="<?= esc($story['title']) ?>" class="w-full h-full object-cover" />
                <?php else: ?>
                  <div class="w-full h-full bg-gradient-to-br from-purple-200 to-purple-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-purple-400 text-[24px]">auto_stories</span>
                  </div>
                <?php endif; ?>
              </div>
            </div>
            <div class="flex flex-col">
              <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <span class="px-2 py-0.5 bg-slate-100 rounded uppercase tracking-widest font-bold"><?= esc($first_genre) ?></span>
                <span class="text-slate-300">|</span>
                <span class="text-emerald-600 font-semibold">New</span>
              </div>
              <h3 class="text-lg font-bold text-primary leading-tight line-clamp-2"><?= esc($story['title']) ?></h3>
              <p class="text-xs text-slate-500 mb-2">
                <span class="text-amber-600 font-semibold"><?= number_format($story['avg_rating'] ?? 0, 1) ?></span>
                <span class="text-slate-400">| <?= number_format($story['total_views'] ?? 0) ?> reads</span>
              </p>
              <p class="text-sm text-slate-600 line-clamp-3 italic"><?= esc(substr($story['description'] ?? '', 0, 100)) ?>...</p>
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

    <!-- Popular Authors -->
    <section class="mb-14 reveal">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl md:text-3xl font-bold text-primary">Popular Authors</h2>
        <a href="<?= base_url('/discover') ?>" class="text-sm font-semibold text-accent hover:underline">View all →</a>
      </div>
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <?php 
        if (!empty($popular_authors)):
          foreach ($popular_authors as $author):
            // Get author initials
            $names = explode(' ', trim($author['name']));
            if (count($names) >= 2) {
                $initials = strtoupper(substr($names[0], 0, 1) . substr($names[count($names) - 1], 0, 1));
            } else {
                $initials = strtoupper(substr($author['name'], 0, 2));
            }
        ?>
        <a href="<?= base_url('/profile/' . $author['id']) ?>" class="block">
          <div class="author-card bg-white border border-border rounded-2xl shadow-sm p-4 text-center">
            <!-- Author Avatar -->
            <div class="author-avatar w-16 h-16 mx-auto mb-3 rounded-full overflow-hidden border-2 border-accent flex items-center justify-center bg-gradient-to-br from-purple-400 to-purple-600">
              <?php if (!empty($author['profile_photo'])): ?>
                <img src="<?= profile_url($author['profile_photo']) ?>" alt="<?= esc($author['name']) ?>" class="w-full h-full object-cover" />
              <?php else: ?>
                <span class="text-white text-xl font-bold"><?= $initials ?></span>
              <?php endif; ?>
            </div>

            <!-- Author Info -->
            <h3 class="font-semibold text-slate-900 text-sm line-clamp-2 mb-1"><?= esc($author['name']) ?></h3>
            
            <?php if (!empty($author['bio'])): ?>
              <p class="text-xs text-slate-500 line-clamp-2 mb-3"><?= esc(substr($author['bio'], 0, 60)) ?></p>
            <?php endif; ?>
          </div>
        </a>
        <?php 
          endforeach;
        else:
        ?>
        <div class="col-span-6 text-center py-8 text-slate-500">
          <p>No popular authors found yet.</p>
        </div>
        <?php 
        endif;
        ?>
      </div>
    </section>

    <?php endif; ?>

</main>

<script>
/* ── Featured Stories Slider ────────────────────────────── */
<?php if (empty($search_query) && count($stories) > 1): ?>
let currentSlide = 0;
let autoSlideInterval;
const slides = document.querySelectorAll('.featured-slide');
const dots = document.querySelectorAll('.slide-dot');
const totalSlides = slides.length;

function showSlide(index) {
  slides.forEach(s => s.classList.remove('active'));
  dots.forEach(d => { d.classList.remove('bg-purple-600'); d.classList.add('bg-slate-300'); });
  slides[index].classList.add('active');
  dots[index].classList.remove('bg-slate-300');
  dots[index].classList.add('bg-purple-600');
  currentSlide = index;
}
function nextSlide() { showSlide((currentSlide + 1) % totalSlides); resetAutoSlide(); }
function prevSlide() { showSlide((currentSlide - 1 + totalSlides) % totalSlides); resetAutoSlide(); }
function goToSlide(i) { showSlide(i); resetAutoSlide(); }
function startAutoSlide() { autoSlideInterval = setInterval(nextSlide, 2000); }
function resetAutoSlide() { clearInterval(autoSlideInterval); startAutoSlide(); }

showSlide(0);
startAutoSlide();

const slider = document.getElementById('featured-slider');
if (slider) {
  slider.addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
  slider.addEventListener('mouseleave', startAutoSlide);
}
<?php endif; ?>

/* ── Scroll Reveal ──────────────────────────────────────── */
const revealObs = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (!entry.isIntersecting) return;
    entry.target.classList.add('visible');
    // animate progress bars inside revealed sections
    entry.target.querySelectorAll('.progress-bar-fill').forEach(bar => {
      bar.classList.add('animated');
    });
    revealObs.unobserve(entry.target);
  });
}, { threshold: 0.1 });

document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));

/* ── Ripple ─────────────────────────────────────────────── */
document.querySelectorAll('.ripple-btn').forEach(btn => {
  btn.addEventListener('click', function(e) {
    const rect = btn.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = e.clientX - rect.left - size / 2;
    const y = e.clientY - rect.top  - size / 2;
    const r = document.createElement('span');
    r.className = 'ripple';
    r.style.cssText = `width:${size}px;height:${size}px;left:${x}px;top:${y}px`;
    btn.appendChild(r);
    setTimeout(() => r.remove(), 600);
  });
});
</script>

<?= $this->endSection() ?>