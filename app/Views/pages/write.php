<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
/* ── Page entrance ──────────────────────────────────────── */
.page-enter { animation: pageEnter 0.55s cubic-bezier(0.22,1,0.36,1) both; }
@keyframes pageEnter {
  from { opacity: 0; transform: translateY(14px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── Scroll reveal ──────────────────────────────────────── */
.reveal {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.6s cubic-bezier(0.22,1,0.36,1),
              transform 0.6s cubic-bezier(0.22,1,0.36,1);
}
.reveal.visible { opacity: 1; transform: translateY(0); }
.d1{transition-delay:.05s} .d2{transition-delay:.12s} .d3{transition-delay:.19s}
.d4{transition-delay:.26s} .d5{transition-delay:.33s} .d6{transition-delay:.40s}

/* ── Benefit / tip card hover ───────────────────────────── */
.benefit-card {
  transition: transform 0.25s cubic-bezier(0.22,1,0.36,1),
              box-shadow 0.25s cubic-bezier(0.22,1,0.36,1);
}
.benefit-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.08);
}

/* ── Icon bounce on card hover ──────────────────────────── */
.benefit-card:hover .icon-bounce {
  animation: iconBounce 0.4s cubic-bezier(0.34,1.56,0.64,1);
}
@keyframes iconBounce {
  0%   { transform: scale(1); }
  50%  { transform: scale(1.25); }
  100% { transform: scale(1); }
}

/* ── Hero CTA glow pulse ────────────────────────────────── */
.cta-glow {
  position: relative;
  transition: transform 0.2s, box-shadow 0.2s;
}
.cta-glow:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 30px rgba(139,92,246,0.4);
}

/* ── Hero section float ─────────────────────────────────── */
.hero-float {
  animation: heroFloat 4s ease-in-out infinite;
}
@keyframes heroFloat {
  0%, 100% { transform: translateY(0); }
  50%       { transform: translateY(-6px); }
}

/* ── FAQ accordion smooth ───────────────────────────────── */
details summary { transition: color 0.2s; }
details[open] summary { color: #7c3aed; }
details summary .expand-icon {
  transition: transform 0.3s cubic-bezier(0.22,1,0.36,1);
}
details[open] summary .expand-icon { transform: rotate(180deg); }

/* ── Number badge pop ───────────────────────────────────── */
.tip-card:hover .num-badge {
  animation: numPop 0.35s cubic-bezier(0.34,1.56,0.64,1);
}
@keyframes numPop {
  0%   { transform: scale(1); }
  50%  { transform: scale(1.3); }
  100% { transform: scale(1); }
}
.tip-card {
  transition: transform 0.25s cubic-bezier(0.22,1,0.36,1),
              box-shadow 0.25s;
}
.tip-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 28px rgba(0,0,0,0.07);
}

/* ── Success card tilt ──────────────────────────────────── */
.tilt-card {
  transform-style: preserve-3d;
  transition: transform 0.15s ease, box-shadow 0.25s;
}
.tilt-card:hover { box-shadow: 0 20px 40px rgba(0,0,0,0.1); }

</style>

<main class="max-w-6xl mx-auto px-6 py-10 page-enter">
  <!-- Hero Section -->
  <section class="reveal bg-gradient-to-br from-purple-50 via-white to-purple-50 rounded-2xl border border-border p-8 md:p-12 mb-14 shadow-sm">
    <div class="flex flex-col items-center text-center gap-6 max-w-3xl mx-auto">
      <div class="hero-float flex items-center justify-center w-16 h-16 rounded-full bg-purple-100">
        <span class="material-symbols-outlined text-3xl text-accent">edit</span>
      </div>
      <h1 class="text-4xl md:text-5xl font-bold text-primary leading-tight">Share Your Story</h1>
      <p class="text-lg text-slate-600">Write, publish, and reach thousands of readers. Your story deserves to be heard.</p>
      <?php if (session()->get('isLoggedIn')): ?>
        <a href="/create-story" class="cta-glow mt-4 inline-flex items-center gap-2 px-6 py-3 bg-accent text-white font-semibold rounded-xl shadow hover:bg-accent-dark transition-colors text-base">
          <span class="material-symbols-outlined text-lg">edit</span>
          Start Writing
        </a>
      <?php else: ?>
        <button type="button" onclick="openModal('loginModal')" class="cta-glow mt-4 inline-flex items-center gap-2 px-6 py-3 bg-accent text-white font-semibold rounded-xl shadow hover:bg-accent-dark transition-colors text-base">
          <span class="material-symbols-outlined text-lg">edit</span>
          Start Writing
        </button>
      <?php endif; ?>
    </div>
  </section>

  <!-- Benefits Section -->
  <section class="mb-14 reveal">
    <h2 class="text-2xl md:text-3xl font-bold text-primary mb-8 text-center">Why Write on CeritaKu?</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="benefit-card reveal d1 p-6 bg-white border border-border rounded-2xl shadow-sm">
        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 mb-4">
          <span class="material-symbols-outlined text-accent icon-bounce">public</span>
        </div>
        <h3 class="text-lg font-bold text-primary mb-2">Reach Thousands</h3>
        <p class="text-slate-600 text-sm">Your story will be discovered by thousands of readers looking for great content.</p>
      </div>
      <div class="benefit-card reveal d2 p-6 bg-white border border-border rounded-2xl shadow-sm">
        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-pink-100 mb-4">
          <span class="material-symbols-outlined text-pink-600 icon-bounce">favorite</span>
        </div>
        <h3 class="text-lg font-bold text-primary mb-2">Get Feedback</h3>
        <p class="text-slate-600 text-sm">Receive ratings and comments from the community to improve your writing.</p>
      </div>
      <div class="benefit-card reveal d3 p-6 bg-white border border-border rounded-2xl shadow-sm">
        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 mb-4">
          <span class="material-symbols-outlined text-emerald-600 icon-bounce">trending_up</span>
        </div>
        <h3 class="text-lg font-bold text-primary mb-2">Build Your Audience</h3>
        <p class="text-slate-600 text-sm">Grow your following and establish yourself as a writer in the community.</p>
      </div>
    </div>
  </section>

  <!-- Tips & Guidelines -->
  <section class="mb-14 reveal">
    <h2 class="text-2xl md:text-3xl font-bold text-primary mb-8 text-center">Writing Tips</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="tip-card reveal d1 p-6 bg-gradient-to-br from-purple-50 to-white border border-border rounded-2xl shadow-sm">
        <div class="flex items-start gap-4">
          <div class="num-badge flex items-center justify-center w-10 h-10 rounded-full bg-purple-200 flex-none">
            <span class="text-lg font-bold text-accent">1</span>
          </div>
          <div>
            <h3 class="font-bold text-slate-700 mb-1">Hook Your Readers</h3>
            <p class="text-sm text-slate-600">Start with an engaging opening that captures attention immediately.</p>
          </div>
        </div>
      </div>
      <div class="tip-card reveal d2 p-6 bg-gradient-to-br from-purple-100 to-white border border-border rounded-2xl shadow-sm">
        <div class="flex items-start gap-4">
          <div class="num-badge flex items-center justify-center w-10 h-10 rounded-full bg-purple-300 flex-none">
            <span class="text-lg font-bold text-purple-700">2</span>
          </div>
          <div>
            <h3 class="font-bold text-slate-700 mb-1">Develop Characters</h3>
            <p class="text-sm text-slate-600">Create relatable characters with depth and compelling motivations.</p>
          </div>
        </div>
      </div>
      <div class="tip-card reveal d3 p-6 bg-gradient-to-br from-fuchsia-50 to-white border border-border rounded-2xl shadow-sm">
        <div class="flex items-start gap-4">
          <div class="num-badge flex items-center justify-center w-10 h-10 rounded-full bg-fuchsia-200 flex-none">
            <span class="text-lg font-bold text-fuchsia-600">3</span>
          </div>
          <div>
            <h3 class="font-bold text-slate-700 mb-1">Build Tension</h3>
            <p class="text-sm text-slate-600">Keep the story moving with conflict and rising stakes.</p>
          </div>
        </div>
      </div>
      <div class="tip-card reveal d4 p-6 bg-gradient-to-br from-violet-50 to-white border border-border rounded-2xl shadow-sm">
        <div class="flex items-start gap-4">
          <div class="num-badge flex items-center justify-center w-10 h-10 rounded-full bg-violet-200 flex-none">
            <span class="text-lg font-bold text-violet-600">4</span>
          </div>
          <div>
            <h3 class="font-bold text-slate-700 mb-1">Polish Your Work</h3>
            <p class="text-sm text-slate-600">Proofread carefully for grammar, spelling, and consistency.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section class="mb-14 reveal">
    <h2 class="text-2xl md:text-3xl font-bold text-primary mb-8 text-center">Frequently Asked Questions</h2>
    <div class="space-y-4 max-w-3xl mx-auto">
      <details class="bg-white border border-border rounded-xl p-6 shadow-sm group cursor-pointer">
        <summary class="flex items-center justify-between font-semibold text-slate-700">
          <span>How do I publish my story?</span>
          <span class="material-symbols-outlined text-slate-400 group-open:rotate-180 transition-transform">expand_more</span>
        </summary>
        <p class="text-slate-600 text-sm mt-4">Fill out the story submission form above with your title, genre, synopsis, and full story content. You can save it as a draft first or publish it directly. Once published, your story will be visible to all readers.</p>
      </details>
      <details class="bg-white border border-border rounded-xl p-6 shadow-sm group cursor-pointer">
        <summary class="flex items-center justify-between font-semibold text-slate-700">
          <span>Can I edit my story after publishing?</span>
          <span class="material-symbols-outlined text-slate-400 group-open:rotate-180 transition-transform">expand_more</span>
        </summary>
        <p class="text-slate-600 text-sm mt-4">Yes! You can edit your story anytime. Go to your dashboard, find your published story, and click 'Edit'. Changes will be updated immediately.</p>
      </details>
      <details class="bg-white border border-border rounded-xl p-6 shadow-sm group cursor-pointer">
        <summary class="flex items-center justify-between font-semibold text-slate-700">
          <span>What file formats are accepted for cover images?</span>
          <span class="material-symbols-outlined text-slate-400 group-open:rotate-180 transition-transform">expand_more</span>
        </summary>
        <p class="text-slate-600 text-sm mt-4">We accept JPG and PNG formats. The file size should be under 5MB. For best quality, use images with dimensions of at least 400x600 pixels.</p>
      </details>
      <details class="bg-white border border-border rounded-xl p-6 shadow-sm group cursor-pointer">
        <summary class="flex items-center justify-between font-semibold text-slate-700">
          <span>Can I delete my story?</span>
          <span class="material-symbols-outlined text-slate-400 group-open:rotate-180 transition-transform">expand_more</span>
        </summary>
        <p class="text-slate-600 text-sm mt-4">Yes, you can delete your story from your dashboard at any time. Please note that deleted stories cannot be recovered.</p>
      </details>
      <details class="bg-white border border-border rounded-xl p-6 shadow-sm group cursor-pointer">
        <summary class="flex items-center justify-between font-semibold text-slate-700">
          <span>How are stories ranked and promoted?</span>
          <span class="material-symbols-outlined text-slate-400 group-open:rotate-180 transition-transform">expand_more</span>
        </summary>
        <p class="text-slate-600 text-sm mt-4">Stories are ranked based on reader ratings, views, and engagement. High-quality, well-received stories appear in featured sections and trending lists.</p>
      </details>
    </div>
  </section>

  <!-- Success Stories -->
  <section class="mb-14 reveal">
    <h2 class="text-2xl md:text-3xl font-bold text-primary mb-8 text-center">Success Stories</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="tilt-card reveal d1 p-6 bg-gradient-to-br from-blue-50 to-white border border-border rounded-2xl shadow-sm">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 rounded-full bg-slate-300"></div>
          <div>
            <p class="font-semibold text-slate-700">Sarah Chen</p>
            <p class="text-xs text-slate-500">Romance Writer</p>
          </div>
        </div>
        <p class="text-sm text-slate-600 italic">"My story reached 50k readers in just 3 months! The feedback from the community helped me improve as a writer."</p>
        <div class="mt-4 flex items-center gap-1">
          <span class="text-amber-500">★★★★★</span>
        </div>
      </div>
      <div class="tilt-card reveal d2 p-6 bg-gradient-to-br from-purple-50 to-white border border-border rounded-2xl shadow-sm">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 rounded-full bg-slate-300"></div>
          <div>
            <p class="font-semibold text-slate-700">Marcus Lee</p>
            <p class="text-xs text-slate-500">Sci-Fi Novelist</p>
          </div>
        </div>
        <p class="text-sm text-slate-600 italic">"CeritaKu gave me the platform to share my passion. Now I'm writing full-time!"</p>
        <div class="mt-4 flex items-center gap-1">
          <span class="text-amber-500">★★★★★</span>
        </div>
      </div>
      <div class="tilt-card reveal d3 p-6 bg-gradient-to-br from-pink-50 to-white border border-border rounded-2xl shadow-sm">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 rounded-full bg-slate-300"></div>
          <div>
            <p class="font-semibold text-slate-700">Emma Wilson</p>
            <p class="text-xs text-slate-500">Mystery Author</p>
          </div>
        </div>
        <p class="text-sm text-slate-600 italic">"The supportive community here keeps me motivated to write every single day."</p>
        <div class="mt-4 flex items-center gap-1">
          <span class="text-amber-500">★★★★★</span>
        </div>
      </div>
    </div>
  </section>
</main>

<script>
/* ── Scroll Reveal ──────────────────────────────────────── */
const obs = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (!e.isIntersecting) return;
    e.target.classList.add('visible');
    obs.unobserve(e.target);
  });
}, { threshold: 0.1 });

document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

/* ── 3D Tilt on success cards ───────────────────────────── */
document.querySelectorAll('.tilt-card').forEach(card => {
  card.addEventListener('mousemove', e => {
    const r = card.getBoundingClientRect();
    const rx = ((e.clientY - r.top  - r.height/2) / (r.height/2)) * -7;
    const ry = ((e.clientX - r.left - r.width/2)  / (r.width/2))  *  7;
    card.style.transform = `perspective(700px) rotateX(${rx}deg) rotateY(${ry}deg) scale(1.02)`;
  });
  card.addEventListener('mouseleave', () => {
    card.style.transform = 'perspective(700px) rotateX(0) rotateY(0) scale(1)';
  });
});
</script>

<?= $this->endSection() ?>
