<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="max-w-6xl mx-auto px-6 py-10">
  <!-- Hero Section -->
  <section class="bg-gradient-to-br from-purple-50 via-white to-purple-50 rounded-2xl border border-border p-8 md:p-12 mb-14 shadow-sm">
    <div class="flex flex-col items-center text-center gap-6 max-w-3xl mx-auto">
      <div class="flex items-center justify-center w-16 h-16 rounded-full bg-purple-100">
        <span class="material-symbols-outlined text-3xl text-accent">edit</span>
      </div>
      <h1 class="text-4xl md:text-5xl font-bold text-primary leading-tight">Share Your Story</h1>
      <p class="text-lg text-slate-600">Write, publish, and reach thousands of readers. Your story deserves to be heard.</p>
      <a href="/create-story" class="mt-4 inline-flex items-center gap-2 px-6 py-3 bg-accent text-white font-semibold rounded-xl shadow hover:bg-accent-dark transition-colors text-base">
        <span class="material-symbols-outlined text-lg">edit</span>
        Start Writing
      </a>
    </div>
  </section>

  <!-- Benefits Section -->
  <section class="mb-14">
    <h2 class="text-2xl md:text-3xl font-bold text-primary mb-8 text-center">Why Write on CeritaKu?</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="p-6 bg-white border border-border rounded-2xl shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 mb-4">
          <span class="material-symbols-outlined text-accent">public</span>
        </div>
        <h3 class="text-lg font-bold text-primary mb-2">Reach Thousands</h3>
        <p class="text-slate-600 text-sm">Your story will be discovered by thousands of readers looking for great content.</p>
      </div>
      <div class="p-6 bg-white border border-border rounded-2xl shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-pink-100 mb-4">
          <span class="material-symbols-outlined text-pink-600">favorite</span>
        </div>
        <h3 class="text-lg font-bold text-primary mb-2">Get Feedback</h3>
        <p class="text-slate-600 text-sm">Receive ratings and comments from the community to improve your writing.</p>
      </div>
      <div class="p-6 bg-white border border-border rounded-2xl shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 mb-4">
          <span class="material-symbols-outlined text-emerald-600">trending_up</span>
        </div>
        <h3 class="text-lg font-bold text-primary mb-2">Build Your Audience</h3>
        <p class="text-slate-600 text-sm">Grow your following and establish yourself as a writer in the community.</p>
      </div>
    </div>
  </section>

  <!-- Tips & Guidelines -->
  <section class="mb-14">
    <h2 class="text-2xl md:text-3xl font-bold text-primary mb-8 text-center">Writing Tips</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="p-6 bg-gradient-to-br from-purple-50 to-white border border-border rounded-2xl shadow-sm">
        <div class="flex items-start gap-4">
          <div class="flex items-center justify-center w-10 h-10 rounded-full bg-purple-200 flex-none">
            <span class="text-lg font-bold text-accent">1</span>
          </div>
          <div>
            <h3 class="font-bold text-slate-700 mb-1">Hook Your Readers</h3>
            <p class="text-sm text-slate-600">Start with an engaging opening that captures attention immediately.</p>
          </div>
        </div>
      </div>
      <div class="p-6 bg-gradient-to-br from-purple-100 to-white border border-border rounded-2xl shadow-sm">
        <div class="flex items-start gap-4">
          <div class="flex items-center justify-center w-10 h-10 rounded-full bg-purple-300 flex-none">
            <span class="text-lg font-bold text-purple-700">2</span>
          </div>
          <div>
            <h3 class="font-bold text-slate-700 mb-1">Develop Characters</h3>
            <p class="text-sm text-slate-600">Create relatable characters with depth and compelling motivations.</p>
          </div>
        </div>
      </div>
      <div class="p-6 bg-gradient-to-br from-fuchsia-50 to-white border border-border rounded-2xl shadow-sm">
        <div class="flex items-start gap-4">
          <div class="flex items-center justify-center w-10 h-10 rounded-full bg-fuchsia-200 flex-none">
            <span class="text-lg font-bold text-fuchsia-600">3</span>
          </div>
          <div>
            <h3 class="font-bold text-slate-700 mb-1">Build Tension</h3>
            <p class="text-sm text-slate-600">Keep the story moving with conflict and rising stakes.</p>
          </div>
        </div>
      </div>
      <div class="p-6 bg-gradient-to-br from-violet-50 to-white border border-border rounded-2xl shadow-sm">
        <div class="flex items-start gap-4">
          <div class="flex items-center justify-center w-10 h-10 rounded-full bg-violet-200 flex-none">
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
  <section class="mb-14">
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
  <section class="mb-14">
    <h2 class="text-2xl md:text-3xl font-bold text-primary mb-8 text-center">Success Stories</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="p-6 bg-gradient-to-br from-blue-50 to-white border border-border rounded-2xl shadow-sm">
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
      <div class="p-6 bg-gradient-to-br from-purple-50 to-white border border-border rounded-2xl shadow-sm">
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
      <div class="p-6 bg-gradient-to-br from-pink-50 to-white border border-border rounded-2xl shadow-sm">
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

<?= $this->endSection() ?>
