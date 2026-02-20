<?php
require 'config.php';
require 'includes/functions.php';

$story_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$chapter_number = isset($_GET['ch']) ? (int)$_GET['ch'] : 1;

if ($story_id <= 0) {
  header('Location: discover.php');
  exit;
}

// Get story info
$story = getStoryById($story_id);
if (empty($story)) {
  header('Location: discover.php');
  exit;
}

// Get current chapter
$current_chapter = getChapterByStoryAndNumber($story_id, $chapter_number);
if (empty($current_chapter)) {
  // If chapter doesn't exist, get first chapter
  $current_chapter = getFirstChapterByStory($story_id);
  if (empty($current_chapter)) {
    header('Location: story-detail.php?id=' . $story_id);
    exit;
  }
  $chapter_number = (int)$current_chapter['chapter_number'];
}

// Get all chapters for navigation
$all_chapters = getAllChaptersByStory($story_id);
$total_chapters = count($all_chapters);

// Get next and previous chapter
$prev_chapter = null;
$next_chapter = null;

foreach ($all_chapters as $idx => $ch) {
  if ((int)$ch['chapter_number'] == $chapter_number) {
    if ($idx > 0) {
      $prev_chapter = $all_chapters[$idx - 1];
    }
    if ($idx < count($all_chapters) - 1) {
      $next_chapter = $all_chapters[$idx + 1];
    }
    break;
  }
}

// Record view for this chapter
recordChapterView($story_id, $current_chapter['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chapter <?php echo (int)$current_chapter['chapter_number']; ?>: <?php echo escape($current_chapter['title']); ?> | <?php echo escape($story['title']); ?> | CeritaKu</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:wght@400;600;700&display=swap" rel="stylesheet">
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
      body {@apply bg-gradient-to-br from-background via-background to-slate-50 text-slate-800 antialiased;}
      h1,h2,h3,h4{@apply font-serif;}
    }
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    .book-card-shadow {
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
    }
    .modern-card {
      @apply bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300;
    }
    .chapter-content-wrapper {
      @apply bg-white rounded-2xl p-8 md:p-10 shadow-sm border border-slate-200;
    }
    .reading-line {
      @apply h-1 bg-gradient-to-r from-accent/30 via-accent to-accent/30 rounded-full transition-all duration-300;
    }
    .reading-settings {
      @apply bg-white rounded-2xl border border-slate-200 shadow-xl p-6 space-y-6;
    }
    .reading-settings.dark-mode {
      @apply bg-slate-900 border-slate-700;
    }
    .reading-settings.dark-mode h4 {
      @apply text-white;
    }
    .reading-settings.dark-mode .slider {
      @apply accent-purple-500;
    }
    .reading-settings.dark-mode select,
    .reading-settings.dark-mode .font-btn {
      @apply bg-slate-800 text-white border-slate-600;
    }
    body.dark-mode {
      @apply bg-slate-950 text-slate-100;
    }
    body.dark-mode .modern-card,
    body.dark-mode .chapter-content-wrapper {
      @apply bg-slate-900 border-slate-700 text-slate-100;
    }
    body.dark-mode a {
      @apply text-accent;
    }
    body.dark-mode .text-slate-500,
    body.dark-mode .text-slate-600,
    body.dark-mode .text-slate-700,
    body.dark-mode .text-slate-800 {
      @apply text-slate-400;
    }
    .comment-item {
      @apply bg-slate-50 rounded-lg p-4 border border-slate-200;
    }
    body.dark-mode .comment-item {
      @apply bg-slate-800 border-slate-700;
    }
  </style>
</head>
<body class="font-sans bg-background" id="reading-body">
  <?php require 'includes/header.php'; ?>

  <div class="max-w-6xl mx-auto px-4 flex gap-4">
    <!-- Sidebar (Chapters List) - Hidden on mobile -->
    <aside id="sidebar" class="hidden md:block w-60 max-h-[calc(100vh-64px)] overflow-y-auto p-3 sticky top-20">
      <div class="modern-card p-3 mt-6">
          <h3 class="text-xs font-bold text-primary mb-4 uppercase tracking-widest">List of Chapters</h3>
          <ul class="space-y-2 max-h-[calc(100vh-200px)] overflow-y-auto pr-1">
            <?php foreach ($all_chapters as $ch): ?>
              <li>
                <a href="read-chapter.php?id=<?php echo (int)$story['id']; ?>&ch=<?php echo (int)$ch['chapter_number']; ?>" 
                   class="block p-2.5 rounded-lg text-sm font-medium transition-all duration-200 <?php echo ((int)$ch['chapter_number'] == $chapter_number) ? 'bg-slate-900 text-white shadow-md shadow-slate-300' : 'bg-white text-slate-800 border border-slate-200 hover:bg-slate-900 hover:text-white'; ?>">
                  <span class="flex items-center gap-2 min-w-0">
                    <span class="font-semibold shrink-0">Ch. <?php echo (int)$ch['chapter_number']; ?></span>
                    <span class="text-xs leading-snug truncate <?php echo ((int)$ch['chapter_number'] == $chapter_number) ? 'text-slate-200' : 'text-slate-500'; ?>"><?php echo escape(truncateText($ch['title'], 30)); ?></span>
                  </span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </aside>

    <!-- Main Content -->
    <main class="flex-1 flex justify-start px-2 md:px-4 py-8 md:py-10">
      <div class="w-full max-w-2xl">
        <!-- Breadcrumb -->
        <nav class="text-xs text-slate-500 mb-8 flex items-center gap-2 flex-wrap">
          <a href="./index.php" class="hover:text-accent transition-colors">Home</a>
          <span class="text-slate-300">/</span>
          <a href="./discover.php" class="hover:text-accent transition-colors">Browse</a>
          <span class="text-slate-300">/</span>
          <a href="story-detail.php?id=<?php echo (int)$story['id']; ?>" class="hover:text-accent transition-colors"><?php echo escape(truncateText($story['title'], 25)); ?></a>
          <span class="text-slate-300">/</span>
          <span class="text-slate-700 font-medium">Ch. <?php echo (int)$chapter_number; ?></span>
        </nav>

        <!-- Chapter Header Card -->
        <section class="modern-card p-8 mb-10">
          <div class="flex items-start justify-between mb-4">
            <div>
              <div class="inline-flex items-center gap-2 mb-3">
                <span class="text-xs font-bold text-accent uppercase tracking-widest bg-accent/10 px-3 py-1 rounded-full">Chapter <?php echo (int)$current_chapter['chapter_number']; ?></span>
                <span class="text-xs text-slate-500">•</span>
                <span class="text-xs text-slate-500"><?php echo escape(formatDate($current_chapter['created_at'])); ?></span>
              </div>
              <h1 class="text-2xl md:text-3xl font-bold text-primary leading-tight"><?php echo escape($current_chapter['title']); ?></h1>
            </div>
            <button id="settings-btn" class="ml-4 p-2.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-primary transition-all duration-200" title="Font & Text Settings">
              <span class="material-symbols-outlined">tune</span>
            </button>
          </div>
          <p class="text-slate-600 text-sm">
            From <a href="story-detail.php?id=<?php echo (int)$story['id']; ?>" class="text-accent font-semibold hover:underline"><?php echo escape($story['title']); ?></a> 
            <span class="text-slate-400">by</span> 
            <span class="font-semibold text-slate-800"><?php echo escape($story['author_name']); ?></span>
          </p>
        </section>

        <!-- Chapter Content -->
        <article id="chapter-content" class="chapter-content-wrapper mb-10 leading-relaxed text-slate-800 text-base space-y-5">
          <?php echo nl2br(escape($current_chapter['content'] ?? '')); ?>
        </article>

        <!-- Settings Panel -->
        <div id="settings-panel" class="hidden fixed top-20 right-4 z-50 w-80 max-h-[calc(100vh-100px)] overflow-y-auto">
          <div class="reading-settings">
            <div class="flex items-center justify-between mb-4">
              <h4 class="font-bold text-lg text-primary">Reading Settings</h4>
              <button id="close-settings" class="text-slate-500 hover:text-slate-700 transition-colors">
                <span class="material-symbols-outlined">close</span>
              </button>
            </div>

            <!-- Font Size -->
            <div>
              <label class="text-sm font-semibold text-slate-700 block mb-3">Font Size</label>
              <div class="flex items-center gap-4">
                <input id="font-size-slider" type="range" min="14" max="24" value="16" class="flex-1 slider h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-accent">
                <span id="font-size-value" class="text-sm font-semibold text-accent min-w-8">16px</span>
              </div>
            </div>

            <!-- Line Height -->
            <div>
              <label class="text-sm font-semibold text-slate-700 block mb-3">Line Height</label>
              <select id="line-height" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white text-slate-900 focus:outline-none focus:border-accent">
                <option value="1.5">1.5 - Compact</option>
                <option value="1.75">1.75 - Default</option>
                <option value="2">2.0 - Spacious</option>
                <option value="2.25">2.25 - Very Spacious</option>
              </select>
            </div>

            <!-- Font Type -->
            <div>
              <label class="text-sm font-semibold text-slate-700 block mb-3">Font Type</label>
              <div class="flex gap-2">
                <button id="font-sans" class="flex-1 font-btn px-4 py-2 rounded-lg border-2 border-slate-200 bg-white text-slate-900 font-sans font-semibold transition-all hover:border-accent">Sans</button>
                <button id="font-serif" class="flex-1 font-btn px-4 py-2 rounded-lg border-2 border-slate-200 bg-white text-slate-900 font-serif font-semibold transition-all hover:border-accent">Serif</button>
              </div>
            </div>

            <!-- Theme -->
            <div>
              <label class="text-sm font-semibold text-slate-700 block mb-3">Theme</label>
              <div class="flex gap-2">
                <button id="theme-light" class="flex-1 px-4 py-2 rounded-lg border-2 border-slate-200 bg-white text-slate-900 font-semibold transition-all hover:border-accent flex items-center justify-center gap-2">
                  <span class="material-symbols-outlined text-base">light_mode</span>
                  <span>Light</span>
                </button>
                <button id="theme-dark" class="flex-1 px-4 py-2 rounded-lg border-2 border-slate-200 bg-white text-slate-900 font-semibold transition-all hover:border-accent flex items-center justify-center gap-2">
                  <span class="material-symbols-outlined text-base">dark_mode</span>
                  <span>Dark</span>
                </button>
              </div>
            </div>

            <!-- Reset -->
            <button id="reset-btn" class="w-full px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition-all text-sm">
              Reset to Default
            </button>
          </div>
        </div>

        <!-- Reading Progress -->
        <div class="modern-card p-6 mb-8">
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Reading Progress</span>
            <span class="text-sm font-bold text-accent"><?php echo round(($chapter_number / $total_chapters) * 100); ?>%</span>
          </div>
          <div class="w-full bg-slate-200 rounded-full h-2.5">
            <div class="reading-line" style="width: <?php echo ($chapter_number / $total_chapters) * 100; ?>%"></div>
          </div>
          <p class="text-xs text-slate-500 mt-3">Chapter <span class="font-semibold text-primary"><?php echo (int)$chapter_number; ?></span> of <span class="font-semibold text-primary"><?php echo (int)$total_chapters; ?></span></p>
        </div>

        <!-- Navigation & Actions -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
          <!-- Previous Chapter -->
          <?php if ($prev_chapter): ?>
            <a href="read-chapter.php?id=<?php echo (int)$story['id']; ?>&ch=<?php echo (int)$prev_chapter['chapter_number']; ?>" class="modern-card p-5 group hover:border-accent/50 hover:shadow-lg hover:shadow-purple-100">
              <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-accent group-hover:-translate-x-1 transition-transform">arrow_back</span>
                <div class="flex-1 min-w-0">
                  <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Previous</p>
                  <p class="text-sm font-semibold text-slate-900 truncate">Chapter <?php echo (int)$prev_chapter['chapter_number']; ?></p>
                </div>
              </div>
            </a>
          <?php else: ?>
            <div class="modern-card p-5 opacity-50 cursor-not-allowed">
              <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-slate-400">arrow_back</span>
                <div>
                  <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Previous</p>
                  <p class="text-sm font-semibold text-slate-400">First Chapter</p>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <!-- Next Chapter -->
          <?php if ($next_chapter): ?>
            <a href="read-chapter.php?id=<?php echo (int)$story['id']; ?>&ch=<?php echo (int)$next_chapter['chapter_number']; ?>" class="modern-card p-5 group hover:border-accent/50 hover:shadow-lg hover:shadow-purple-100">
              <div class="flex items-center gap-3 justify-end md:justify-start">
                <div class="flex-1 min-w-0 md:text-left">
                  <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Next</p>
                  <p class="text-sm font-semibold text-slate-900 truncate">Chapter <?php echo (int)$next_chapter['chapter_number']; ?></p>
                </div>
                <span class="material-symbols-outlined text-accent group-hover:translate-x-1 transition-transform">arrow_forward</span>
              </div>
            </a>
          <?php else: ?>
            <div class="modern-card p-5 opacity-50 cursor-not-allowed">
              <div class="flex items-center gap-3">
                <div>
                  <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Next</p>
                  <p class="text-sm font-semibold text-slate-400">The End</p>
                </div>
                <span class="material-symbols-outlined text-slate-400 ml-auto">arrow_forward</span>
              </div>
            </div>
          <?php endif; ?>
        </section>

        <!-- Action Buttons -->
        <section class="modern-card p-6 mb-10">
          <div class="flex flex-wrap gap-3">
            <button class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-gradient-to-r from-slate-900 to-slate-800 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:shadow-lg hover:shadow-slate-900/20 transition-all duration-200 hover:-translate-y-0.5">
              <span class="material-symbols-outlined text-base">thumb_up</span>
              <span>Like</span>
            </button>
            <button class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-white border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm font-semibold hover:border-accent hover:text-accent transition-all duration-200">
              <span class="material-symbols-outlined text-base">bookmark_add</span>
              <span>Bookmark</span>
            </button>
            <button class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-white border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm font-semibold hover:border-accent hover:text-accent transition-all duration-200">
              <span class="material-symbols-outlined text-base">share</span>
              <span>Share</span>
            </button>
            <a href="./story-detail.php?id=<?php echo (int)$story['id']; ?>" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-gradient-to-r from-accent to-purple-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:shadow-lg hover:shadow-purple-400/30 transition-all duration-200 hover:-translate-y-0.5 ml-auto">
              <span class="material-symbols-outlined text-base">book</span>
              <span>Back to Story</span>
            </a>
          </div>
        </section>
      </div>
    </main>
  </div>

  <script>
    // Font & Text Settings
    const settingsBtn = document.getElementById('settings-btn');
    const settingsPanel = document.getElementById('settings-panel');
    const closeSettings = document.getElementById('close-settings');
    const fontSizeSlider = document.getElementById('font-size-slider');
    const fontSizeValue = document.getElementById('font-size-value');
    const lineHeightSelect = document.getElementById('line-height');
    const fontSansBtn = document.getElementById('font-sans');
    const fontSerifBtn = document.getElementById('font-serif');
    const themeLightBtn = document.getElementById('theme-light');
    const themeDarkBtn = document.getElementById('theme-dark');
    const resetBtn = document.getElementById('reset-btn');
    const readingBody = document.getElementById('reading-body');
    const chapterContent = document.getElementById('chapter-content');

    // Load settings from localStorage
    function loadSettings() {
      const settings = JSON.parse(localStorage.getItem('readingSettings')) || {
        fontSize: 16,
        lineHeight: 1.75,
        fontType: 'sans',
        theme: 'light'
      };
      
      applySettings(settings);
    }

    // Apply settings to the page
    function applySettings(settings) {
      // Font size
      fontSizeSlider.value = settings.fontSize;
      fontSizeValue.textContent = settings.fontSize + 'px';
      chapterContent.style.fontSize = settings.fontSize + 'px';

      // Line height
      lineHeightSelect.value = settings.lineHeight;
      chapterContent.style.lineHeight = settings.lineHeight;

      // Font type
      if (settings.fontType === 'sans') {
        readingBody.classList.remove('font-serif');
        readingBody.classList.add('font-sans');
        fontSansBtn.classList.add('border-accent', 'bg-accent/10');
        fontSerifBtn.classList.remove('border-accent', 'bg-accent/10');
      } else {
        readingBody.classList.add('font-serif');
        readingBody.classList.remove('font-sans');
        fontSerifBtn.classList.add('border-accent', 'bg-accent/10');
        fontSansBtn.classList.remove('border-accent', 'bg-accent/10');
      }

      // Theme
      if (settings.theme === 'dark') {
        readingBody.classList.add('dark-mode');
        document.querySelectorAll('.reading-settings').forEach(el => el.classList.add('dark-mode'));
        themeDarkBtn.classList.add('border-accent', 'bg-accent/10');
        themeLightBtn.classList.remove('border-accent', 'bg-accent/10');
      } else {
        readingBody.classList.remove('dark-mode');
        document.querySelectorAll('.reading-settings').forEach(el => el.classList.remove('dark-mode'));
        themeLightBtn.classList.add('border-accent', 'bg-accent/10');
        themeDarkBtn.classList.remove('border-accent', 'bg-accent/10');
      }
    }

    // Save settings to localStorage
    function saveSettings() {
      const settings = {
        fontSize: parseInt(fontSizeSlider.value),
        lineHeight: parseFloat(lineHeightSelect.value),
        fontType: readingBody.classList.contains('font-serif') ? 'serif' : 'sans',
        theme: readingBody.classList.contains('dark-mode') ? 'dark' : 'light'
      };
      localStorage.setItem('readingSettings', JSON.stringify(settings));
    }

    // Event listeners
    settingsBtn.addEventListener('click', () => {
      settingsPanel.classList.toggle('hidden');
    });

    closeSettings.addEventListener('click', () => {
      settingsPanel.classList.add('hidden');
    });

    fontSizeSlider.addEventListener('input', (e) => {
      fontSizeValue.textContent = e.target.value + 'px';
      chapterContent.style.fontSize = e.target.value + 'px';
      saveSettings();
    });

    lineHeightSelect.addEventListener('change', (e) => {
      chapterContent.style.lineHeight = e.target.value;
      saveSettings();
    });

    fontSansBtn.addEventListener('click', () => {
      readingBody.classList.remove('font-serif');
      readingBody.classList.add('font-sans');
      fontSansBtn.classList.add('border-accent', 'bg-accent/10');
      fontSerifBtn.classList.remove('border-accent', 'bg-accent/10');
      saveSettings();
    });

    fontSerifBtn.addEventListener('click', () => {
      readingBody.classList.add('font-serif');
      readingBody.classList.remove('font-sans');
      fontSerifBtn.classList.add('border-accent', 'bg-accent/10');
      fontSansBtn.classList.remove('border-accent', 'bg-accent/10');
      saveSettings();
    });

    themeLightBtn.addEventListener('click', () => {
      readingBody.classList.remove('dark-mode');
      document.querySelectorAll('.reading-settings').forEach(el => el.classList.remove('dark-mode'));
      themeLightBtn.classList.add('border-accent', 'bg-accent/10');
      themeDarkBtn.classList.remove('border-accent', 'bg-accent/10');
      saveSettings();
    });

    themeDarkBtn.addEventListener('click', () => {
      readingBody.classList.add('dark-mode');
      document.querySelectorAll('.reading-settings').forEach(el => el.classList.add('dark-mode'));
      themeDarkBtn.classList.add('border-accent', 'bg-accent/10');
      themeLightBtn.classList.remove('border-accent', 'bg-accent/10');
      saveSettings();
    });

    resetBtn.addEventListener('click', () => {
      localStorage.removeItem('readingSettings');
      location.reload();
    });

    // Close settings panel when clicking outside
    document.addEventListener('click', (e) => {
      if (!settingsPanel.contains(e.target) && !settingsBtn.contains(e.target)) {
        settingsPanel.classList.add('hidden');
      }
    });

    // Load settings on page load
    loadSettings();

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowLeft') {
        const prevLink = document.querySelector('a[href*="&ch=<?php echo isset($prev_chapter) ? (int)$prev_chapter['chapter_number'] : ''; ?>"]');
        if (prevLink) prevLink.click();
      }
      if (e.key === 'ArrowRight') {
        const nextLink = document.querySelector('a[href*="&ch=<?php echo isset($next_chapter) ? (int)$next_chapter['chapter_number'] : ''; ?>"]');
        if (nextLink) nextLink.click();
      }
    });
  </script>
</body>
</html>
