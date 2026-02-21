<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$isLoggedIn  = session()->get('isLoggedIn') === true;
$totalChaps  = count($all_chapters ?? []);
$currentNum  = (int)($chapter['chapter_number'] ?? 1);
$progressPct = $totalChaps > 0 ? round(($currentNum / $totalChaps) * 100) : 0;
?>

<main class="max-w-6xl mx-auto px-6 py-8">

    <div class="grid md:grid-cols-4 gap-8">

        <!-- ═══════════════════════════════
             SIDEBAR — Daftar Bab
        ═══════════════════════════════ -->
        <aside class="md:col-span-1">
            <div class="sticky top-20 space-y-3">
                <!-- Back — di luar card -->
                <a href="<?= base_url('/story/' . $chapter['story_id']) ?>"
                   class="inline-flex items-center gap-1 text-xs text-slate-500 hover:text-slate-900 transition-colors">
                    <span>←</span> Back
                </a>

                <!-- Card Daftar Bab -->
                <div class="bg-white border border-border rounded-2xl shadow-sm">
                <div class="px-4 py-2 border-b border-border">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Daftar Bab</p>
                </div>
                <ul class="divide-y divide-border max-h-[60vh] overflow-y-auto" id="chapter-list">
                    <?php foreach ($all_chapters as $c): ?>
                        <?php $isCurrent = $c['id'] == $chapter['id']; ?>
                        <li>
                            <a href="<?= base_url('/read-chapter/' . $c['id']) ?>"
                               class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors
                                      <?= $isCurrent ? 'bg-accent text-white font-semibold' : 'text-slate-700 hover:bg-slate-50' ?>">
                                <span class="text-xs shrink-0 w-5 text-center <?= $isCurrent ? 'text-white/70' : 'text-slate-400' ?>">
                                    <?= (int)$c['chapter_number'] ?>
                                </span>
                                <span class="truncate"><?= esc($c['title']) ?></span>
                                <?php if (!empty($c['is_premium'])): ?>
                                    <span class="ml-auto shrink-0 px-1.5 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase rounded">Pro</span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                </div><!-- end card -->
            </div><!-- end sticky -->
        </aside>

        <!-- ═══════════════════════════════
             MAIN — Konten
        ═══════════════════════════════ -->
        <div class="md:col-span-3 space-y-6">

            <!-- Breadcrumb — sejajar dengan konten -->
            <nav class="text-xs text-slate-500">
                <a href="<?= base_url() ?>" class="hover:text-slate-900">Beranda</a>
                <span class="mx-2">/</span>
                <a href="<?= base_url('/discover') ?>" class="hover:text-slate-900">Jelajahi</a>
                <span class="mx-2">/</span>
                <a href="<?= base_url('/story/' . $chapter['story_id']) ?>" class="hover:text-slate-900"><?= esc($chapter['story_title']) ?></a>
                <span class="mx-2">/</span>
                <span class="text-slate-700 font-medium">Bab <?= $currentNum ?></span>
            </nav>
            <div class="bg-white border border-border rounded-2xl shadow-sm p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 bg-slate-100 text-[10px] font-bold uppercase tracking-wider text-slate-600 rounded">
                                Bab <?= $currentNum ?>
                            </span>
                            <?php if (!empty($chapter['is_premium'])): ?>
                                <span class="px-2 py-0.5 bg-amber-100 text-[10px] font-bold uppercase tracking-wider text-amber-700 rounded">Premium</span>
                            <?php endif; ?>
                            <span class="text-[12px] text-slate-400"><?= date('d M Y', strtotime($chapter['created_at'])) ?></span>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-bold text-primary mb-1"><?= esc($chapter['title']) ?></h1>
                        <p class="text-slate-600 text-sm">dari
                            <a href="<?= base_url('/story/' . $chapter['story_id']) ?>" class="font-medium text-slate-800 hover:text-accent transition-colors"><?= esc($chapter['story_title']) ?></a>
                            · oleh
                            <a href="<?= base_url('/user/' . $chapter['author_id']) ?>" class="font-medium text-slate-800 hover:text-accent transition-colors"><?= esc($chapter['author_name']) ?></a>
                        </p>
                    </div>
                    <button id="settings-btn" title="Reading Settings"
                            class="shrink-0 p-2 rounded-lg bg-accent/10 hover:bg-accent/20 text-accent transition-colors">
                        <span class="material-symbols-outlined text-xl">tune</span>
                    </button>
                </div>

                <!-- Progress -->
                <div class="mt-5 pt-5 border-t border-border">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs text-slate-500">Reading progress</span>
                        <span class="text-xs font-semibold text-slate-700">Chapter <?= $currentNum ?> of <?= $totalChaps ?></span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full bg-accent transition-all" style="width: <?= $progressPct ?>%"></div>
                    </div>
                </div>
            </div>

            <!-- Settings Popup (floating) -->
            <div id="settings-panel"
                 class="hidden fixed top-24 right-6 z-50 w-72 bg-white border border-border rounded-2xl shadow-2xl p-5 space-y-5">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-bold text-primary">Reading Settings</h4>
                    <button id="close-settings" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                </div>

                <!-- Font Size -->
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-2">Font Size</label>
                    <div class="flex items-center gap-3">
                        <input id="font-size-slider" type="range" min="14" max="24" value="16"
                               class="flex-1 h-1.5 appearance-none rounded-full cursor-pointer accent-violet-600">
                        <span id="font-size-value" class="text-xs font-bold text-accent w-9 text-right">16px</span>
                    </div>
                </div>

                <!-- Font Style -->
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-2">Font Style</label>
                    <div class="grid grid-cols-3 gap-1.5">
                        <button data-font="font-sans"    class="font-btn text-xs py-2 rounded-lg border border-border text-slate-600 hover:border-accent transition-all" style="font-family: 'Inter', sans-serif">Sans</button>
                        <button data-font="font-serif"   class="font-btn text-xs py-2 rounded-lg border border-accent bg-accent/10 text-accent transition-all" style="font-family: 'Lora', serif">Serif</button>
                        <button data-font="font-mono"    class="font-btn text-xs py-2 rounded-lg border border-border text-slate-600 hover:border-accent transition-all" style="font-family: monospace">Mono</button>
                        <button data-font="font-georgia" class="font-btn text-xs py-2 rounded-lg border border-border text-slate-600 hover:border-accent transition-all" style="font-family: Georgia, serif">Georgia</button>
                        <button data-font="font-garamond" class="font-btn text-xs py-2 rounded-lg border border-border text-slate-600 hover:border-accent transition-all" style="font-family: Garamond, serif">Garamond</button>
                        <button data-font="font-palatino" class="font-btn text-xs py-2 rounded-lg border border-border text-slate-600 hover:border-accent transition-all" style="font-family: Palatino, serif">Palatino</button>
                    </div>
                </div>

                <!-- Theme -->
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-2">Theme</label>
                    <div class="flex gap-2">
                        <button id="theme-light" class="flex-1 flex items-center justify-center gap-1.5 text-sm py-2 rounded-lg border border-accent bg-accent/10 text-accent transition-all">
                            <span class="material-symbols-outlined text-base">light_mode</span> Light
                        </button>
                        <button id="theme-dark" class="flex-1 flex items-center justify-center gap-1.5 text-sm py-2 rounded-lg border border-border text-slate-600 hover:border-accent transition-all">
                            <span class="material-symbols-outlined text-base">dark_mode</span> Dark
                        </button>
                    </div>
                </div>

                <button id="reset-btn" class="w-full py-2 text-xs text-slate-400 hover:text-slate-600 transition-colors">
                    Reset to default
                </button>
            </div>

            <!-- Chapter Content -->
            <article id="chapter-content"
                     class="bg-white border border-border rounded-2xl shadow-sm px-8 py-10
                            font-serif text-base leading-relaxed text-slate-800 space-y-4">
                <?= nl2br(esc($chapter['content'])) ?>
            </article>

            <!-- Prev / Next -->
            <div class="flex items-center justify-between py-2">
                <?php if (!empty($prev_chapter)): ?>
                    <a href="<?= base_url('/read-chapter/' . $prev_chapter['id']) ?>"
                       class="text-sm font-semibold text-accent hover:underline">
                        ← Sebelumnya
                    </a>
                <?php else: ?>
                    <span></span>
                <?php endif; ?>

                <?php if (!empty($next_chapter)): ?>
                    <a href="<?= base_url('/read-chapter/' . $next_chapter['id']) ?>"
                       class="text-sm font-semibold text-accent hover:underline">
                        Selanjutnya →
                    </a>
                <?php else: ?>
                    <span class="text-sm text-slate-400 italic">Tamat</span>
                <?php endif; ?>
            </div>

            <!-- Komentar -->
            <section id="comments" class="mb-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl md:text-2xl font-bold text-primary">
                        Comments
                        <span class="text-base font-normal text-slate-400">(<?= (int)($total_comments ?? 0) ?>)</span>
                    </h2>
                </div>

                <!-- Form Komentar -->
                <?php if ($isLoggedIn): ?>
                    <form method="POST" action="<?= base_url('/chapter/' . $chapter['id'] . '/comment') ?>" class="mb-6">
                        <?= csrf_field() ?>
                        <div class="bg-white border border-border rounded-2xl shadow-sm p-4 flex gap-3">
                            <div class="shrink-0 w-8 h-8 rounded-full bg-accent text-white flex items-center justify-center text-xs font-bold">
                                <?= strtoupper(substr(session()->get('user_name') ?? 'U', 0, 1)) ?>
                            </div>
                            <div class="flex-1">
                                <textarea name="comment" rows="2" required placeholder="Write a comment..."
                                          class="w-full text-sm resize-none focus:outline-none placeholder:text-slate-300 text-slate-800"></textarea>
                                <div class="flex justify-end mt-2 pt-2 border-t border-border">
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 bg-accent text-white px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-purple-700 transition-all">
                                        Send
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="bg-white border border-border rounded-2xl shadow-sm p-4 mb-6 text-sm text-slate-500">
                        <button onclick="document.getElementById('loginModal').classList.remove('hidden'); document.getElementById('loginModal').classList.add('flex');"
                                class="text-accent font-semibold hover:underline">Login</button>
                        to leave a comment.
                    </div>
                <?php endif; ?>

                <!-- List Komentar -->
                <div class="bg-white border border-border rounded-2xl shadow-sm">
                    <?php if (!empty($comments)): ?>
                        <ul class="divide-y divide-border">
                            <?php foreach ($comments as $comment): ?>
                                <li class="p-4 flex gap-3">
                                    <div class="shrink-0 w-8 h-8 rounded-full bg-accent/20 overflow-hidden flex items-center justify-center text-accent text-xs font-bold">
                                        <?php if (!empty($comment['user_photo'])): ?>
                                            <img src="<?= profile_url($comment['user_photo']) ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <?= strtoupper(substr($comment['user_name'] ?? 'A', 0, 1)) ?>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900"><?= esc($comment['user_name'] ?? 'Anonim') ?>
                                            <span class="text-xs font-normal text-slate-400 ml-1"><?= date('d M Y', strtotime($comment['created_at'])) ?></span>
                                        </p>
                                        <p class="text-sm text-slate-700 mt-0.5 leading-relaxed"><?= esc($comment['comment']) ?></p>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="text-center text-slate-500 text-sm p-8">
                            No comments yet. Be the first!
                        </div>
                    <?php endif; ?>
                </div>
            </section>

        </div>
    </div>
</main>

<?= $this->endSection() ?>

<?= $this->section('additionalScripts') ?>
<script>
const settingsBtn   = document.getElementById('settings-btn');
const settingsPanel = document.getElementById('settings-panel');
const closeSettings = document.getElementById('close-settings');
const slider  = document.getElementById('font-size-slider');
const sizeVal = document.getElementById('font-size-value');
const content = document.getElementById('chapter-content');
const body    = document.body;
const DEFAULTS = { fontSize: 16, fontFamily: 'font-serif', theme: 'light' };

const FONT_MAP = {
    'font-sans':     "'Inter', sans-serif",
    'font-serif':    "'Lora', serif",
    'font-mono':     "monospace",
    'font-georgia':  "Georgia, serif",
    'font-garamond': "Garamond, serif",
    'font-palatino': "Palatino, serif",
};

// Toggle popup
settingsBtn.addEventListener('click', (e) => { e.stopPropagation(); settingsPanel.classList.toggle('hidden'); });
closeSettings.addEventListener('click', () => settingsPanel.classList.add('hidden'));
document.addEventListener('click', (e) => {
    if (!settingsPanel.contains(e.target) && !settingsBtn.contains(e.target))
        settingsPanel.classList.add('hidden');
});

function getSaved() {
    try { return { ...DEFAULTS, ...JSON.parse(localStorage.getItem('readingSettings') || '{}') }; }
    catch { return DEFAULTS; }
}

function applyFont(fontKey) {
    content.style.fontFamily = FONT_MAP[fontKey] || FONT_MAP['font-serif'];
    document.querySelectorAll('.font-btn').forEach(btn => {
        const active = btn.dataset.font === fontKey;
        btn.classList.toggle('border-accent', active);
        btn.classList.toggle('bg-accent/10',  active);
        btn.classList.toggle('text-accent',   active);
        btn.classList.toggle('border-border', !active);
        btn.classList.toggle('text-slate-600',!active);
    });
}

function applyTheme(theme) {
    const dark = theme === 'dark';

    // Body & page background
    body.classList.toggle('bg-slate-950', dark);
    body.classList.toggle('bg-background', !dark);

    // Article content
    content.classList.toggle('bg-slate-900',   dark);
    content.classList.toggle('text-slate-200', dark);
    content.classList.toggle('bg-white',       !dark);
    content.classList.toggle('text-slate-800', !dark);

    // Sidebar card
    document.querySelectorAll('.dark-card').forEach(el => {
        el.classList.toggle('bg-slate-900', dark);
        el.classList.toggle('border-slate-700', dark);
        el.classList.toggle('bg-white', !dark);
        el.classList.toggle('border-border', !dark);
    });

    // Theme buttons
    const lightBtn = document.getElementById('theme-light');
    const darkBtn  = document.getElementById('theme-dark');
    lightBtn.classList.toggle('border-accent', !dark);
    lightBtn.classList.toggle('bg-accent/10',  !dark);
    lightBtn.classList.toggle('text-accent',   !dark);
    lightBtn.classList.toggle('border-border', dark);
    lightBtn.classList.toggle('text-slate-600',dark);
    darkBtn.classList.toggle('border-accent',  dark);
    darkBtn.classList.toggle('bg-accent/10',   dark);
    darkBtn.classList.toggle('text-accent',    dark);
    darkBtn.classList.toggle('border-border',  !dark);
    darkBtn.classList.toggle('text-slate-600', !dark);
}

function applySettings(s) {
    slider.value           = s.fontSize;
    sizeVal.textContent    = s.fontSize + 'px';
    content.style.fontSize = s.fontSize + 'px';
    applyFont(s.fontFamily);
    applyTheme(s.theme);
}

function save() {
    const activeFontBtn = document.querySelector('.font-btn.border-accent');
    localStorage.setItem('readingSettings', JSON.stringify({
        fontSize:   parseInt(slider.value),
        fontFamily: activeFontBtn ? activeFontBtn.dataset.font : 'font-serif',
        theme:      body.classList.contains('bg-slate-950') ? 'dark' : 'light',
    }));
}

applySettings(getSaved());

slider.addEventListener('input', (e) => {
    sizeVal.textContent = e.target.value + 'px';
    content.style.fontSize = e.target.value + 'px';
    save();
});

document.querySelectorAll('.font-btn').forEach(btn => {
    btn.addEventListener('click', () => { applyFont(btn.dataset.font); save(); });
});

document.getElementById('theme-light').addEventListener('click', () => { applyTheme('light'); save(); });
document.getElementById('theme-dark').addEventListener('click',  () => { applyTheme('dark');  save(); });
document.getElementById('reset-btn').addEventListener('click',   () => { localStorage.removeItem('readingSettings'); location.reload(); });

// Auto-scroll sidebar ke bab aktif
const activeItem = document.querySelector('#chapter-list a.bg-accent');
if (activeItem) activeItem.scrollIntoView({ block: 'center', behavior: 'smooth' });
</script>
<?= $this->endSection() ?>