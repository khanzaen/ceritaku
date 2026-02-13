<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
.hero-section {
    position: relative;
    min-height: 600px;
}

.hero-bg-1, .hero-bg-2 {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-size: cover;
    background-position: center;
    transition: opacity 2s ease-in-out;
}

.hero-bg-1 {
    background-image: 
        linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)),
        url('<?= base_url('assets/images/backround_webnovel.jpg') ?>');
    opacity: 1;
    z-index: 1;
}

.hero-bg-2 {
    background-image: 
        linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)),
        url('<?= base_url('assets/images/backround_webnovel2.jpg') ?>');
    opacity: 0;
    z-index: 2;
}

.hero-section.active-second .hero-bg-2 {
    opacity: 1;
}

.hero-content {
    position: relative;
    z-index: 10;
}

.book-card-shadow {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const heroElement = document.querySelector('.hero-section');
    
    setInterval(() => {
        heroElement.classList.toggle('active-second');
    }, 3000); // Ganti gambar setiap 3 detik
});
</script>

<!-- Hero Section -->
<section class="hero-section relative -mx-6 mb-20 overflow-hidden">
    <!-- Background Layers -->
    <div class="hero-bg-1"></div>
    <div class="hero-bg-2"></div>
    
    <!-- Hero Content -->
    <div class="hero-content max-w-6xl mx-auto px-6 py-10 md:py-14">
                <div class="text-center mb-20">
                    <h1 class="text-3xl md:text-5xl font-bold text-white mb-3 leading-tight drop-shadow-lg">
                        Where great stories find their
                        <span id="hero-words" class="text-yellow-300 opacity-100 transition-opacity duration-500">readers</span>.
                    </h1>
                    <p class="text-base md:text-lg text-white/90 mb-6 leading-relaxed max-w-3xl mx-auto drop-shadow">
                        Komunitas pembaca bertemu dengan cerita-cerita berkualitas dari penulis independen Indonesia.
                    </p>

                    <div class="max-w-xl mx-auto">
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
                        <div class="mt-3 flex justify-center gap-2 text-[11px] text-white/80 flex-wrap">
                            <span class="px-2 py-0.5 bg-white/20 backdrop-blur rounded-md"><?= number_format($total_users ?? 0) ?>k+ pembaca</span>
                            <span class="px-2 py-0.5 bg-white/20 backdrop-blur rounded-md"><?= number_format($total_reviews ?? 0) ?>+ ulasan</span>
                            <span class="px-2 py-0.5 bg-white/20 backdrop-blur rounded-md"><?= number_format($total_stories ?? 0) ?>+ cerita</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="<?= base_url('/discover') ?>" class="bg-white text-slate-900 px-5 py-2 rounded-full text-xs font-bold hover:bg-white/90 transition-all inline-flex items-center gap-2 shadow-lg justify-center">
                        <span class="material-symbols-outlined text-base">local_library</span>
                        Jelajahi Cerita
                    </a>
                    <button type="button" onclick="openModal('registerModal')" class="bg-white/10 backdrop-blur border-2 border-white text-white px-5 py-2 rounded-full text-xs font-bold hover:bg-white hover:text-slate-900 transition-all inline-flex items-center gap-2 justify-center">
                        <span class="material-symbols-outlined text-base">edit</span>
                        Mulai Menulis
                    </button>
                </div>

                <div class="mt-3 text-center">
                    <a href="#discover" class="inline-flex items-center gap-1 text-sm font-bold text-white/80 hover:text-white transition-colors">
                        Lihat ulasan trending
                        <span class="material-symbols-outlined text-base">south</span>
                    </a>
                </div>
            </div>
</section>

<!-- Content Section with White Background -->
<div class="bg-background relative z-10">
    <section id="discover" class="max-w-6xl mx-auto px-6 py-5">
        <div class="mb-10 border-b border-border pb-4">
            <button class="text-sm font-bold text-slate-900 border-b-2 border-slate-900 pb-4 -mb-[18px]">Featured</button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-10 gap-y-14">
            <?php if (!empty($featured_reviews)): ?>
                <?php foreach($featured_reviews as $review): ?>
                    <article class="flex gap-6 group">
                        <div class="w-32 md:w-44 flex-none">
                            <a href="<?= base_url('/story/' . $review['story_id']) ?>" class="block">
                                <div class="aspect-[2/3] bg-slate-100 rounded-sm overflow-hidden book-card-shadow transition-transform duration-300 group-hover:-translate-y-1">
                                    <?php if (!empty($review['cover_image'])): ?>
                                        <img alt="<?= esc($review['story_title']) ?> Cover" class="w-full h-full object-cover" src="<?= cover_url($review['cover_image']) ?>"/>
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-purple-200">
                                            <span class="material-symbols-outlined text-6xl text-purple-400">menu_book</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <div class="mt-4 text-center">
                                <span class="text-xs text-slate-500"><?= $review['likes_count'] ?> orang menyetujui</span>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-2 py-0.5 bg-slate-100 text-[10px] font-bold uppercase tracking-wider text-slate-500 rounded"><?= esc(trim(explode(', ', $review['genres'])[0])) ?></span>
                                <span class="text-slate-300">|</span>
                                <div class="flex items-center gap-1.5">
                                    <?php if (!empty($review['user_photo'])): ?>
                                        <img alt="Reviewer" class="w-5 h-5 rounded-full border border-border object-cover" src="<?= profile_url($review['user_photo']) ?>"/>
                                    <?php else: ?>
                                        <?php
                                        // Get initials from reviewer name
                                        $names = explode(' ', trim($review['user_name']));
                                        $initials = '';
                                        if (count($names) >= 2) {
                                            $initials = strtoupper(substr($names[0], 0, 1) . substr($names[count($names) - 1], 0, 1));
                                        } else {
                                            $initials = strtoupper(substr($review['user_name'], 0, 2));
                                        }
                                        ?>
                                        <div class="w-5 h-5 rounded-full border border-border bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
                                            <span class="text-white text-[9px] font-bold"><?= $initials ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <span class="text-xs text-slate-600">Diulas oleh <span class="font-semibold text-slate-900"><?= esc($review['user_name']) ?></span></span>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-2 leading-tight group-hover:text-accent transition-colors">
                                <a href="<?= base_url('/story/' . $review['story_id']) ?>" class="hover:text-accent transition-colors">
                                    <?= esc($review['story_title']) ?>
                                </a>
                            </h3>
                            <p class="text-slate-500 text-sm mb-4">oleh <span class="text-slate-700 font-medium"><?= esc($review['author_name']) ?></span></p>
                            <p class="text-slate-600 text-sm leading-relaxed mb-6 line-clamp-3 italic">
                                "<?= esc(substr($review['review'], 0, 150)) ?>"
                            </p>
                            <div class="mt-auto">
                                <a class="inline-flex items-center gap-1 text-sm font-bold text-accent hover:underline" href="<?= base_url('/story/' . $review['story_id']) ?>#reviews">
                                    Baca Ulasan <span class="material-symbols-outlined text-base">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-slate-500 col-span-2 text-center py-10">Tidak ada cerita unggulan tersedia untuk saat ini.</p>
            <?php endif; ?>
        </div>

        <div class="mt-7 text-center">
            <a href="<?= base_url('/discover') ?>" class="bg-white border-2 border-slate-900 text-slate-900 px-8 py-2.5 rounded-full font-bold hover:bg-slate-900 hover:text-white transition-all inline-block">
                Temukan cerita lainnya
            </a>
        </div>
    </section>

    <!-- Success Stories Section -->
    <section class="max-w-6xl mx-auto px-6 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Kisah Sukses Penulis</h2>
            <p class="text-slate-600 max-w-2xl mx-auto">Ratusan penulis telah menemukan kesuksesan mereka di platform CeritaKu</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Success Story 1 -->
            <div class="bg-gradient-to-br from-purple-50 to-white border border-border rounded-2xl p-6 shadow-sm hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-300 to-purple-500 flex items-center justify-center flex-none">
                        <span class="material-symbols-outlined text-2xl text-white">person</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-primary">Khanza Haura</h3>
                        <p class="text-sm text-slate-600">Romance Writer</p>
                    </div>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-slate-700 italic leading-relaxed">"Dimulai dari 50 pembaca, kini cerita saya diikuti lebih dari 120k orang. CeritaKu memberikan platform untuk berbagi passion saya."</p>
                </div>
                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-purple-100">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-accent">120k</p>
                        <p class="text-xs text-slate-500">Total Dibaca</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-accent">4.8</p>
                        <p class="text-xs text-slate-500">Rating</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-accent">3</p>
                        <p class="text-xs text-slate-500">Dipublikasi</p>
                    </div>
                </div>
            </div>

            <!-- Success Story 2 -->
            <div class="bg-gradient-to-br from-blue-50 to-white border border-border rounded-2xl p-6 shadow-sm hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-300 to-blue-500 flex items-center justify-center flex-none">
                        <span class="material-symbols-outlined text-2xl text-white">person</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-primary">Bima Pratama</h3>
                        <p class="text-sm text-slate-600">Mystery Author</p>
                    </div>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-slate-700 italic leading-relaxed">"Dari penulis hobi menjadi penulis terbitan. Feedback komunitas membantu saya meningkatkan kualitas karya dan percaya diri."</p>
                </div>
                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-blue-100">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-accent">89k</p>
                        <p class="text-xs text-slate-500">Total Dibaca</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-accent">4.7</p>
                        <p class="text-xs text-slate-500">Rating</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-accent">5</p>
                        <p class="text-xs text-slate-500">Dipublikasi</p>
                    </div>
                </div>
            </div>

            <!-- Success Story 3 -->
            <div class="bg-gradient-to-br from-pink-50 to-white border border-border rounded-2xl p-6 shadow-sm hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-pink-300 to-pink-500 flex items-center justify-center flex-none">
                        <span class="material-symbols-outlined text-2xl text-white">person</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-primary">Dewi Lestari</h3>
                        <p class="text-sm text-slate-600">Drama Writer</p>
                    </div>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-slate-700 italic leading-relaxed">"Membangun basis penggemar setia sebanyak 5k+ followers. Setiap cerita baru sekarang mencapai ribuan pembaca dalam seminggu pertama!"</p>
                </div>
                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-pink-100">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-accent">215k</p>
                        <p class="text-xs text-slate-500">Total Dibaca</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-accent">4.9</p>
                        <p class="text-xs text-slate-500">Rating</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-accent">7</p>
                        <p class="text-xs text-slate-500">Dipublikasi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
// Typing effect for hero words
const words = ['readers', 'writers', 'dreamers', 'creators'];
let wordIndex = 0;
let charIndex = 0;
let isDeleting = false;
const heroWords = document.getElementById('hero-words');

function typeEffect() {
    const currentWord = words[wordIndex];
    
    if (isDeleting) {
        heroWords.textContent = currentWord.substring(0, charIndex - 1);
        charIndex--;
        
        if (charIndex === 0) {
            isDeleting = false;
            wordIndex = (wordIndex + 1) % words.length;
            setTimeout(typeEffect, 500);
        } else {
            setTimeout(typeEffect, 100);
        }
    } else {
        heroWords.textContent = currentWord.substring(0, charIndex + 1);
        charIndex++;
        
        if (charIndex === currentWord.length) {
            isDeleting = true;
            setTimeout(typeEffect, 2000);
        } else {
            setTimeout(typeEffect, 100);
        }
    }
}

typeEffect();
</script>

<?= $this->endSection() ?>
