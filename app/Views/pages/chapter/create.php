<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="max-w-4xl mx-auto px-6 py-10">

  <!-- Flash errors -->
  <?php if(session()->getFlashdata('errors')): ?>
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
      <ul class="list-disc list-inside">
        <?php foreach(session()->getFlashdata('errors') as $error): ?>
          <li><?= $error ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
  <?php if(session()->getFlashdata('error')): ?>
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
      <?= session()->getFlashdata('error') ?>
    </div>
  <?php endif; ?>

  <!-- Header -->
  <div class="mb-8">
    <a href="<?= site_url('story/edit/' . $story['id'] . '?tab=chapters') ?>"
      class="inline-flex items-center gap-1 text-sm text-slate-600 hover:text-accent transition-colors mb-4">
      <span class="material-symbols-outlined text-base">arrow_back</span>
      Back to Chapters
    </a>
    <div class="flex items-center gap-2 mb-2">
      <span class="text-xs bg-slate-100 text-slate-500 px-3 py-1 rounded-full font-medium">
        <?= esc($story['title']) ?>
      </span>
    </div>
    <h1 class="text-3xl md:text-4xl font-bold text-primary">Tambah Chapter Baru</h1>
    <p class="text-slate-500 text-sm mt-1">Chapter <?= $next_number ?></p>
  </div>

  <form action="<?= site_url('story/' . $story['id'] . '/chapter/save') ?>" method="post" class="space-y-6">
    <?= csrf_field() ?>

    <!-- Chapter Title -->
    <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
      <label for="chapter_title" class="block text-sm font-bold text-primary mb-2">Chapter Title *</label>
      <input
        type="text"
        id="chapter_title"
        name="chapter_title"
        value="<?= old('chapter_title') ?>"
        placeholder="Contoh: Chapter <?= $next_number ?>: Awal Petualangan"
        class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm"
        required
        maxlength="150"
      />
    </div>

    <!-- Chapter Content -->
    <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
      <div class="flex items-center justify-between mb-2">
        <label for="chapter_content" class="block text-sm font-bold text-primary">Chapter Content *</label>
        <span id="word-count" class="text-xs text-slate-400 font-medium">0 kata</span>
      </div>
      <textarea
        id="chapter_content"
        name="chapter_content"
        rows="22"
        placeholder="Mulai menulis chapter-mu di sini..."
        class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm resize-none font-serif leading-relaxed"
        required
      ><?= old('chapter_content') ?></textarea>
      <p class="text-xs text-slate-400 mt-2">Minimum 500 kata disarankan</p>
    </div>

    <!-- Settings -->
    <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
      <h3 class="text-sm font-bold text-primary mb-4">Pengaturan Chapter</h3>
      <label class="flex items-center gap-2 cursor-pointer select-none">
        <input
          type="checkbox"
          name="is_premium"
          value="1"
          <?= old('is_premium') == '1' ? 'checked' : '' ?>
          class="w-4 h-4 text-accent rounded focus:ring-2 focus:ring-accent/30"
        />
        <span class="text-sm text-slate-700 font-medium">Premium Chapter</span>
        <span class="text-xs text-slate-400">(chapter berbayar / khusus member premium)</span>
      </label>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-3 pt-2">
      <a href="<?= site_url('story/edit/' . $story['id'] . '?tab=chapters') ?>"
        class="flex-1 px-6 py-3 bg-white border-2 border-slate-300 text-slate-700 rounded-lg font-semibold hover:bg-slate-50 transition-all inline-flex items-center justify-center gap-2">
        Cancel
      </a>
      <button type="submit" name="save_draft" value="1"
        class="flex-1 px-6 py-3 bg-white border-2 border-accent text-accent rounded-lg font-semibold hover:bg-accent/5 transition-all inline-flex items-center justify-center gap-2">
        <span class="material-symbols-outlined text-base">draft</span>
        Simpan Draft
      </button>
      <button type="submit"
        class="flex-1 px-6 py-3 bg-accent text-white rounded-lg font-semibold hover:bg-purple-700 transition-all inline-flex items-center justify-center gap-2">
        <span class="material-symbols-outlined text-base">publish</span>
        Publish Chapter
      </button>
    </div>
  </form>

</main>

<script>
  const textarea = document.getElementById('chapter_content');
  const counter  = document.getElementById('word-count');

  function updateWordCount() {
    const words = textarea.value.trim() ? textarea.value.trim().split(/\s+/).filter(w => w).length : 0;
    counter.textContent = words.toLocaleString('id-ID') + ' kata';
    counter.classList.toggle('text-green-600', words >= 500);
    counter.classList.toggle('text-slate-400', words < 500);
  }

  textarea.addEventListener('input', updateWordCount);
</script>

<?= $this->endSection() ?>
