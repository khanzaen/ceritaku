<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
/* ============================================================
   HERO SECTION
   ============================================================ */
.hero-section {
    position: relative;
    min-height: 600px;
}

.hero-bg-1, .hero-bg-2 {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background-size: cover;
    background-position: center;
    transition: opacity 2s ease-in-out;
}

.hero-bg-1 {
    background-image: 
        linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)),
        url('<?= base_url('assets/images/backround_webnovel.jpg') ?>');
    opacity: 1;
    z-index: 1;
}

.hero-bg-2 {
    background-image: 
        linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)),
        url('<?= base_url('assets/images/backround_webnovel2.jpg') ?>');
    opacity: 0;
    z-index: 2;
}

.hero-section.active-second .hero-bg-2 { opacity: 1; }

.hero-content {
    position: relative;
    z-index: 10;
}

/* ============================================================
   PARTICLES
   ============================================================ */
#particles-canvas {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    z-index: 3;
    pointer-events: none;
}

/* ============================================================
   TYPING CURSOR
   ============================================================ */
#hero-words {
    display: inline-block;
    border-right: 3px solid #fcd34d;
    padding-right: 4px;
    animation: blink 0.7s infinite;
}

@keyframes blink {
    0%, 49%, 100% { border-right-color: #fcd34d; }
    50%, 99%      { border-right-color: transparent; }
}

/* ============================================================
   HERO ENTRANCE
   ============================================================ */
.hero-title {
    animation: fadeSlideDown 0.8s cubic-bezier(0.22, 1, 0.36, 1) both;
}
.hero-subtitle {
    animation: fadeSlideDown 0.8s 0.15s cubic-bezier(0.22, 1, 0.36, 1) both;
}
.hero-search {
    animation: fadeSlideDown 0.8s 0.3s cubic-bezier(0.22, 1, 0.36, 1) both;
}
.hero-badges {
    animation: fadeSlideDown 0.8s 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
}
.hero-buttons {
    animation: fadeSlideUp 0.8s 0.55s cubic-bezier(0.22, 1, 0.36, 1) both;
}
.hero-link {
    animation: fadeIn 1s 0.8s both;
}

@keyframes fadeSlideDown {
    from { opacity: 0; transform: translateY(-24px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}

/* ============================================================
   GLOWING CTA BUTTONS
   ============================================================ */
.btn-glow {
    position: relative;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}
.btn-glow::after {
    content: '';
    position: absolute;
    inset: -2px;
    background: conic-gradient(#fcd34d, #f97316, #ec4899, #8b5cf6, #fcd34d);
    border-radius: inherit;
    z-index: -1;
    opacity: 0;
    transition: opacity 0.3s;
    animation: spin 3s linear infinite;
}
.btn-glow:hover::after { opacity: 1; }
.btn-glow:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(0,0,0,0.25); }

@keyframes spin { to { transform: rotate(360deg); } }

/* Ripple */
.ripple-btn { position: relative; overflow: hidden; }
.ripple {
    position: absolute;
    border-radius: 50%;
    transform: scale(0);
    animation: ripple-anim 0.6s linear;
    background: rgba(255,255,255,0.35);
    pointer-events: none;
}
@keyframes ripple-anim {
    to { transform: scale(4); opacity: 0; }
}

/* ============================================================
   SCROLL INDICATOR
   ============================================================ */
.scroll-indicator {
    animation: bounce 2s infinite;
}
@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(6px); }
}

/* ============================================================
   SCROLL REVEAL
   ============================================================ */
.reveal {
    opacity: 0;
    transform: translateY(40px);
    transition: opacity 0.7s cubic-bezier(0.22,1,0.36,1), transform 0.7s cubic-bezier(0.22,1,0.36,1);
}
.reveal.visible {
    opacity: 1;
    transform: translateY(0);
}
.reveal-left {
    opacity: 0;
    transform: translateX(-40px);
    transition: opacity 0.7s cubic-bezier(0.22,1,0.36,1), transform 0.7s cubic-bezier(0.22,1,0.36,1);
}
.reveal-left.visible {
    opacity: 1;
    transform: translateX(0);
}

/* Stagger delays */
.delay-1 { transition-delay: 0.1s; }
.delay-2 { transition-delay: 0.2s; }
.delay-3 { transition-delay: 0.3s; }
.delay-4 { transition-delay: 0.4s; }
.delay-5 { transition-delay: 0.5s; }

/* ============================================================
   BOOK CARD
   ============================================================ */
.book-card-shadow {
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.08), 0 2px 4px -1px rgba(0,0,0,0.04);
    transition: box-shadow 0.3s, transform 0.3s;
}
.group:hover .book-card-shadow {
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

/* Image shimmer */
.img-shimmer {
    position: relative;
    overflow: hidden;
}
.img-shimmer::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.25) 50%, transparent 100%);
    transform: translateX(-100%);
    animation: shimmer 1.5s infinite;
    z-index: 1;
}
@keyframes shimmer {
    to { transform: translateX(100%); }
}

/* ============================================================
   SUCCESS CARDS — 3D TILT
   ============================================================ */
.tilt-card {
    transform-style: preserve-3d;
    transition: transform 0.15s ease, box-shadow 0.3s ease;
    will-change: transform;
}
.tilt-card:hover {
    box-shadow: 0 24px 48px rgba(0,0,0,0.12);
}

/* Counter */
.stat-num {
    display: inline-block;
    transition: transform 0.1s;
}

/* ============================================================
   SECTION HEADING LINE
   ============================================================ */


/* ============================================================
   PARALLAX MOUSE
   ============================================================ */
.parallax-layer {
    transition: transform 0.1s linear;
}

/* ============================================================
   BADGE PULSE (hero stats)
   ============================================================ */
.badge-pulse {
    animation: badge-in 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
}
@keyframes badge-in {
    from { opacity: 0; transform: scale(0.7); }
    to   { opacity: 1; transform: scale(1); }
}
</style>

<!-- ============================================================
     HERO SECTION
     ============================================================ -->
<section class="hero-section relative -mx-6 mb-20 overflow-hidden">
    <div class="hero-bg-1"></div>
    <div class="hero-bg-2"></div>

    <!-- Floating particles -->
    <canvas id="particles-canvas"></canvas>

    <div class="hero-content max-w-6xl mx-auto px-6 py-10 md:py-14">
        <div class="text-center mb-20 parallax-layer" id="hero-parallax">
            <h1 class="hero-title text-3xl md:text-5xl font-bold text-white mb-3 leading-tight drop-shadow-lg">
                Where great stories find their
                <span id="hero-words" class="text-yellow-300">readers</span>.
            </h1>
            <p class="hero-subtitle text-base md:text-lg text-white/90 mb-6 leading-relaxed max-w-3xl mx-auto drop-shadow">
                A community of readers meets quality stories from independent Indonesian writers.
            </p>

            <div class="hero-search max-w-xl mx-auto">
                <form method="GET" action="<?= base_url('/discover') ?>" class="relative">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 text-sm">search</span>
                    </div>
                    <input name="q" type="text" placeholder="Cari cerita, penulis, atau genre..."
                        class="w-full pl-9 pr-24 py-2 bg-white/95 backdrop-blur border-0 rounded-xl focus:ring-2 focus:ring-white/50 outline-none transition-all text-slate-900 text-sm shadow-lg"/>
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-slate-900 text-white rounded-lg text-xs font-semibold hover:bg-slate-800 transition-all">
                        Cari
                    </button>
                </form>

                <div class="hero-badges mt-3 flex justify-center gap-2 text-[11px] text-white/80 flex-wrap">
                    <span class="badge-pulse px-2 py-0.5 bg-white/20 backdrop-blur rounded-md" style="animation-delay:.6s">
                        <span class="counter" data-target="<?= $total_users ?? 0 ?>">0</span>k+ readers
                    </span>
                    <span class="badge-pulse px-2 py-0.5 bg-white/20 backdrop-blur rounded-md" style="animation-delay:.75s">
                        <span class="counter" data-target="<?= $total_reviews ?? 0 ?>">0</span>+ reviews
                    </span>
                    <span class="badge-pulse px-2 py-0.5 bg-white/20 backdrop-blur rounded-md" style="animation-delay:.9s">
                        <span class="counter" data-target="<?= $total_stories ?? 0 ?>">0</span>+ stories
                    </span>
                </div>
            </div>
        </div>

        <div class="hero-buttons mt-4 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="<?= base_url('/discover') ?>" class="btn-glow ripple-btn bg-white text-slate-900 px-5 py-2 rounded-full text-xs font-bold hover:bg-white/90 transition-all inline-flex items-center gap-2 shadow-lg justify-center">
                <span class="material-symbols-outlined text-base">local_library</span>
                Explore Stories
            </a>
            <a href="<?= base_url('/write') ?>" class="ripple-btn bg-white/10 backdrop-blur border-2 border-white text-white px-5 py-2 rounded-full text-xs font-bold hover:bg-white hover:text-slate-900 transition-all inline-flex items-center gap-2 justify-center">
                <span class="material-symbols-outlined text-base">edit</span>
                Start Writing
            </a>
        </div>

        <div class="hero-link mt-3 text-center">
            <a href="#discover" class="scroll-indicator inline-flex items-center gap-1 text-sm font-bold text-white/80 hover:text-white transition-colors">
                See trending reviews
                <span class="material-symbols-outlined text-base">south</span>
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     CONTENT SECTION
     ============================================================ -->
<div class="bg-background relative z-10">
    <section id="discover" class="max-w-6xl mx-auto px-6 py-5">
        <div class="mb-10 border-b border-border pb-4 reveal">
            <button class="text-sm font-bold text-slate-900 border-b-2 border-slate-900 pb-4 -mb-[18px]">Featured</button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-10 gap-y-14">
            <?php if (!empty($featured_reviews)): ?>
                <?php foreach($featured_reviews as $i => $review): ?>
                    <article class="flex gap-6 group reveal delay-<?= min($i+1, 5) ?>">
                        <div class="w-32 md:w-44 flex-none">
                            <a href="<?= base_url('/story/' . $review['story_id']) ?>" class="block">
                                <div class="img-shimmer aspect-[2/3] bg-slate-100 rounded-sm overflow-hidden book-card-shadow">
                                    <?php if (!empty($review['cover_image'])): ?>
                                        <img alt="<?= esc($review['story_title']) ?> Cover"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                             src="<?= cover_url($review['cover_image']) ?>"/>
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-purple-200">
                                            <span class="material-symbols-outlined text-6xl text-purple-400">menu_book</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <div class="mt-4 text-center">
                                <span class="text-xs text-slate-500"><?= $review['likes_count'] ?> people agree</span>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-2 py-0.5 bg-slate-100 text-[10px] font-bold uppercase tracking-wider text-slate-500 rounded">
                                    <?= esc(trim(explode(', ', $review['genres'])[0])) ?>
                                </span>
                                <span class="text-slate-300">|</span>
                                <div class="flex items-center gap-1.5">
                                    <?php if (!empty($review['user_photo'])): ?>
                                        <img alt="Reviewer" class="w-5 h-5 rounded-full border border-border object-cover" src="<?= profile_url($review['user_photo']) ?>"/>
                                    <?php else: ?>
                                        <?php
                                        $names = explode(' ', trim($review['user_name']));
                                        $initials = count($names) >= 2
                                            ? strtoupper(substr($names[0],0,1).substr($names[count($names)-1],0,1))
                                            : strtoupper(substr($review['user_name'],0,2));
                                        ?>
                                        <div class="w-5 h-5 rounded-full border border-border bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
                                            <span class="text-white text-[9px] font-bold"><?= $initials ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <span class="text-xs text-slate-600">Reviewed by <span class="font-semibold text-slate-900"><?= esc($review['user_name']) ?></span></span>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-2 leading-tight group-hover:text-accent transition-colors">
                                <a href="<?= base_url('/story/' . $review['story_id']) ?>" class="hover:text-accent transition-colors">
                                    <?= esc($review['story_title']) ?>
                                </a>
                            </h3>
                            <p class="text-slate-500 text-sm mb-4">by <span class="text-slate-700 font-medium"><?= esc($review['author_name']) ?></span></p>
                            <div class="mb-2 flex items-center gap-2">
                                <span class="flex items-center gap-1 text-amber-500 text-xs font-semibold">
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <?= number_format($review['rating'] ?? 0, 1) ?>
                                </span>
                            </div>
                            <p class="text-slate-600 text-sm leading-relaxed mb-6 line-clamp-3 italic">
                                "<?= esc(substr($review['review'], 0, 150)) ?>"
                            </p>
                            <div class="mt-auto">
                                <a class="inline-flex items-center gap-1 text-sm font-bold text-accent hover:underline group/link" href="<?= base_url('/story/' . $review['story_id']) ?>#reviews">
                                    Read Review
                                    <span class="material-symbols-outlined text-base transition-transform group-hover/link:translate-x-1">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-slate-500 col-span-2 text-center py-10">No featured stories available at the moment.</p>
            <?php endif; ?>
        </div>

        <div class="mt-7 text-center reveal">
            <a href="<?= base_url('/discover') ?>" class="ripple-btn bg-white border-2 border-slate-900 text-slate-900 px-8 py-2.5 rounded-full font-bold hover:bg-slate-900 hover:text-white transition-all inline-block">
                Find more stories
            </a>
        </div>
    </section>

    <!-- ============================================================
         SUCCESS STORIES SECTION
         ============================================================ -->
    <section class="max-w-6xl mx-auto px-6 py-16">
        <div class="text-center mb-12 reveal">
            <h2 class="section-line text-3xl md:text-4xl font-bold text-primary mb-4">Writer Success Stories</h2>
            <p class="text-slate-600 max-w-2xl mx-auto">Hundreds of writers have found their success on the CeritaKu platform</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div class="tilt-card reveal delay-1 bg-gradient-to-br from-purple-50 to-white border border-border rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-300 to-purple-500 flex items-center justify-center flex-none shadow-md">
                        <span class="material-symbols-outlined text-2xl text-white">person</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-primary">Khanza Haura</h3>
                        <p class="text-sm text-slate-600">Romance Writer</p>
                    </div>
                </div>
                <p class="text-sm text-slate-700 italic leading-relaxed mb-4">"Started with 50 readers, now my story is followed by more than 120k people. CeritaKu provides a platform to share my passion."</p>
                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-purple-100">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-accent stat-num" data-target="120" data-suffix="k">0k</p>
                        <p class="text-xs text-slate-500">Total Reads</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-accent stat-num" data-target="4.8" data-suffix="" data-float="1">0</p>
                        <p class="text-xs text-slate-500">Rating</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-accent stat-num" data-target="3" data-suffix="">0</p>
                        <p class="text-xs text-slate-500">Published</p>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="tilt-card reveal delay-2 bg-gradient-to-br from-blue-50 to-white border border-border rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-300 to-blue-500 flex items-center justify-center flex-none shadow-md">
                        <span class="material-symbols-outlined text-2xl text-white">person</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-primary">Bima Pratama</h3>
                        <p class="text-sm text-slate-600">Mystery Author</p>
                    </div>
                </div>
                <p class="text-sm text-slate-700 italic leading-relaxed mb-4">"From hobby writer to published author. Community feedback helped me improve my work and gain confidence."</p>
                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-blue-100">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-accent stat-num" data-target="89" data-suffix="k">0k</p>
                        <p class="text-xs text-slate-500">Total Reads</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-accent stat-num" data-target="4.7" data-suffix="" data-float="1">0</p>
                        <p class="text-xs text-slate-500">Rating</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-accent stat-num" data-target="5" data-suffix="">0</p>
                        <p class="text-xs text-slate-500">Published</p>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="tilt-card reveal delay-3 bg-gradient-to-br from-pink-50 to-white border border-border rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-pink-300 to-pink-500 flex items-center justify-center flex-none shadow-md">
                        <span class="material-symbols-outlined text-2xl text-white">person</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-primary">Dewi Lestari</h3>
                        <p class="text-sm text-slate-600">Drama Writer</p>
                    </div>
                </div>
                <p class="text-sm text-slate-700 italic leading-relaxed mb-4">"Built a loyal fanbase of 5k+ followers. Every new story now reaches thousands of readers in the first week!"</p>
                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-pink-100">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-accent stat-num" data-target="215" data-suffix="k">0k</p>
                        <p class="text-xs text-slate-500">Total Reads</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-accent stat-num" data-target="4.9" data-suffix="" data-float="1">0</p>
                        <p class="text-xs text-slate-500">Rating</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-accent stat-num" data-target="7" data-suffix="">0</p>
                        <p class="text-xs text-slate-500">Published</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- ============================================================
     SCRIPTS
     ============================================================ -->
<script>
/* ── Hero background toggle ────────────────────────────── */
const heroEl = document.querySelector('.hero-section');
setInterval(() => heroEl.classList.toggle('active-second'), 3000);

/* ── Typing effect ─────────────────────────────────────── */
const words = ['readers', 'writers', 'dreamers', 'creators'];
let wordIndex = 0, charIndex = 0, isDeleting = false;
const heroWords = document.getElementById('hero-words');

function typeEffect() {
    const cur = words[wordIndex];
    heroWords.textContent = isDeleting
        ? cur.substring(0, charIndex - 1)
        : cur.substring(0, charIndex + 1);
    isDeleting ? charIndex-- : charIndex++;

    if (!isDeleting && charIndex === cur.length) {
        isDeleting = true;
        setTimeout(typeEffect, 2000);
    } else if (isDeleting && charIndex === 0) {
        isDeleting = false;
        wordIndex = (wordIndex + 1) % words.length;
        setTimeout(typeEffect, 500);
    } else {
        setTimeout(typeEffect, isDeleting ? 80 : 100);
    }
}
typeEffect();

/* ── Floating particles ────────────────────────────────── */
(function() {
    const canvas = document.getElementById('particles-canvas');
    const ctx = canvas.getContext('2d');
    let particles = [];

    function resize() {
        canvas.width  = heroEl.offsetWidth;
        canvas.height = heroEl.offsetHeight;
    }
    resize();
    window.addEventListener('resize', resize);

    for (let i = 0; i < 55; i++) {
        particles.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            r: Math.random() * 1.8 + 0.4,
            speed: Math.random() * 0.5 + 0.15,
            opacity: Math.random() * 0.5 + 0.2,
            drift: (Math.random() - 0.5) * 0.3
        });
    }

    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particles.forEach(p => {
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(255,255,255,${p.opacity})`;
            ctx.fill();
            p.y -= p.speed;
            p.x += p.drift;
            if (p.y < -4) { p.y = canvas.height + 4; p.x = Math.random() * canvas.width; }
        });
        requestAnimationFrame(draw);
    }
    draw();
})();

/* ── Mouse parallax on hero ────────────────────────────── */
document.addEventListener('mousemove', e => {
    const layer = document.getElementById('hero-parallax');
    if (!layer) return;
    const { innerWidth: w, innerHeight: h } = window;
    const dx = (e.clientX / w - 0.5) * 12;
    const dy = (e.clientY / h - 0.5) * 8;
    layer.style.transform = `translate(${dx}px, ${dy}px)`;
});

/* ── Ripple effect ─────────────────────────────────────── */
document.querySelectorAll('.ripple-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        const rect = btn.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top  - size / 2;
        const ripple = document.createElement('span');
        ripple.className = 'ripple';
        ripple.style.cssText = `width:${size}px;height:${size}px;left:${x}px;top:${y}px`;
        btn.appendChild(ripple);
        setTimeout(() => ripple.remove(), 700);
    });
});

/* ── Scroll reveal ─────────────────────────────────────── */
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            // also trigger section-line animation
            entry.target.querySelectorAll('.section-line').forEach(el => el.classList.add('visible'));
            if (entry.target.classList.contains('section-line')) entry.target.classList.add('visible');
        }
    });
}, { threshold: 0.12 });

document.querySelectorAll('.reveal, .reveal-left, .section-line').forEach(el => revealObserver.observe(el));

/* ── Animated number counters ──────────────────────────── */
function animateCounter(el, target, suffix, isFloat) {
    const duration = 1400;
    const start = performance.now();
    function step(now) {
        const progress = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        const val = isFloat
            ? (eased * target).toFixed(1)
            : Math.round(eased * target);
        el.textContent = val + suffix;
        if (progress < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
}

const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        const target  = parseFloat(el.dataset.target);
        const suffix  = el.dataset.suffix || '';
        const isFloat = !!el.dataset.float;
        animateCounter(el, target, suffix, isFloat);
        counterObserver.unobserve(el);
    });
}, { threshold: 0.5 });

document.querySelectorAll('.stat-num').forEach(el => counterObserver.observe(el));

// Hero badges counter (simpler)
document.querySelectorAll('.counter').forEach(el => {
    const target = parseInt(el.dataset.target) || 0;
    animateCounter(el, target, '', false);
});

/* ── 3D card tilt ──────────────────────────────────────── */
document.querySelectorAll('.tilt-card').forEach(card => {
    card.addEventListener('mousemove', e => {
        const rect = card.getBoundingClientRect();
        const cx = rect.left + rect.width  / 2;
        const cy = rect.top  + rect.height / 2;
        const rx = ((e.clientY - cy) / (rect.height / 2)) * -8;
        const ry = ((e.clientX - cx) / (rect.width  / 2)) *  8;
        card.style.transform = `perspective(800px) rotateX(${rx}deg) rotateY(${ry}deg) scale(1.02)`;
    });
    card.addEventListener('mouseleave', () => {
        card.style.transform = 'perspective(800px) rotateX(0deg) rotateY(0deg) scale(1)';
    });
});
</script>

<?= $this->endSection() ?>
