<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="max-w-4xl mx-auto px-6 py-10">
  <!-- Tampilkan pesan sukses -->
  <?php if(session()->getFlashdata('success')): ?>
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
      <?= session()->getFlashdata('success') ?>
    </div>
  <?php endif; ?>

  <!-- Tampilkan pesan error -->
  <?php if(session()->getFlashdata('error')): ?>
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
      <?= session()->getFlashdata('error') ?>
    </div>
  <?php endif; ?>

  <!-- Tampilkan validation errors -->
  <?php if(session()->getFlashdata('errors')): ?>
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
      <ul class="list-disc list-inside">
      <?php foreach(session()->getFlashdata('errors') as $error): ?>
        <li><?= $error ?></li>
      <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="mb-8">
    <a href="<?= site_url('write') ?>" class="inline-flex items-center gap-1 text-sm text-slate-600 hover:text-accent transition-colors mb-4">
      <span class="material-symbols-outlined text-base">arrow_back</span>
      Back
    </a>
    <h1 class="text-3xl md:text-4xl font-bold text-primary mb-2">Create Your Story</h1>
    <p class="text-slate-600">Fill in the details below to start sharing your story with the world.</p>
  </div>

  <form action="<?= site_url('create-story') ?>" method="post" enctype="multipart/form-data" class="space-y-6">
    <?= csrf_field() ?>
    
    <!-- Story Title -->
    <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
      <label for="title" class="block text-sm font-bold text-primary mb-2">Story Title *</label>
      <input type="text" id="title" name="title" value="<?= old('title') ?>" placeholder="Enter your story title" class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm" required maxlength="120" />
      <p class="text-xs text-slate-500 mt-2">Choose a captivating title that represents your story.</p>
    </div>

    <!-- Story Description -->
    <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
      <label for="synopsis" class="block text-sm font-bold text-primary mb-2">Story Description *</label>
      <textarea id="synopsis" name="synopsis" rows="4" placeholder="Write a brief description of your story..." class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm resize-none" required maxlength="1000"><?= old('synopsis') ?></textarea>
      <p class="text-xs text-slate-500 mt-2">A compelling synopsis helps readers discover your story.</p>
    </div>

    <!-- Genre -->
    <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
      <h3 class="text-sm font-bold text-primary mb-4">Genre</h3>
      <div class="space-y-4">
        <div>
          <label class="block text-xs font-semibold text-slate-600 mb-3">Select Genres (Multiple) *</label>
          <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <?php 
            $genres = ['Romance','Mystery','Fantasy','Drama','Sci-Fi','Thriller','Comedy','Politics','History','Adventure','Horror','Paranormal','Supernatural']; 
            $oldGenres = old('genre') ?: [];
            foreach($genres as $genre): 
              $genreLower = strtolower($genre);
            ?>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="genre[]" value="<?= $genreLower ?>" <?= in_array($genreLower, $oldGenres) ? 'checked' : '' ?> class="w-4 h-4 text-accent rounded focus:ring-2 focus:ring-accent/30" />
                <span class="text-sm text-slate-700"><?= $genre ?></span>
              </label>
            <?php endforeach; ?>
          </div>
          <p class="text-xs text-slate-500 mt-2">Select at least one genre for your story.</p>
        </div>
      </div>
    </div>

    <!-- Cover Image -->
    <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
      <label class="block text-sm font-bold text-primary mb-2">Story Cover</label>
      <div id="cover-upload-area" class="border-2 border-dashed border-border rounded-lg p-8 text-center hover:border-accent transition-colors cursor-pointer">
        <span id="cover-icon" class="material-symbols-outlined text-4xl text-slate-400 mb-2 block">upload</span>
        <p id="cover-label-text" class="text-sm text-slate-600 mb-1">Click to upload or drag and drop</p>
        <p id="cover-sub-text" class="text-xs text-slate-500">PNG, JPG up to 5MB (Recommended: 800x1200px)</p>
        <input type="file" name="cover" accept="image/*" id="cover-upload" class="hidden" />
      </div>
    </div>

    <!-- Story Status (Publication Status) -->
    <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
      <label class="block text-sm font-bold text-primary mb-4">Story Status *</label>
      <div class="flex flex-wrap gap-3">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" name="status" value="ongoing" <?= old('status') == 'ongoing' ? 'checked' : 'checked' ?> class="w-4 h-4 text-accent focus:ring-2 focus:ring-accent/30" />
          <span class="text-sm text-slate-700">Ongoing</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" name="status" value="completed" <?= old('status') == 'completed' ? 'checked' : '' ?> class="w-4 h-4 text-accent focus:ring-2 focus:ring-accent/30" />
          <span class="text-sm text-slate-700">Completed</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" name="status" value="hiatus" <?= old('status') == 'hiatus' ? 'checked' : '' ?> class="w-4 h-4 text-accent focus:ring-2 focus:ring-accent/30" />
          <span class="text-sm text-slate-700">On Hiatus</span>
        </label>
      </div>
    </div>

    <!-- First Chapter -->
    <div class="bg-gradient-to-br from-purple-50 to-white border border-border rounded-2xl p-6 shadow-sm">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-bold text-primary">First Chapter (Optional)</h3>
        <span class="text-xs text-slate-500">You can add more chapters after publishing</span>
      </div>
      <div class="space-y-4">
        <div>
          <label for="chapter-title" class="block text-xs font-semibold text-slate-600 mb-2">Chapter Title</label>
          <input type="text" id="chapter-title" name="chapter-title" placeholder="e.g., Chapter 1: The Beginning" class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm" />
        </div>
        <div>
          <label for="chapter-content" class="block text-xs font-semibold text-slate-600 mb-2">Chapter Content</label>
          <textarea id="chapter-content" name="chapter-content" rows="10" placeholder="Start writing your first chapter here..." class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm resize-none font-serif leading-relaxed"></textarea>
          <div class="flex items-center justify-between mt-2">
            <p class="text-xs text-slate-500">Minimum 500 words recommended</p>
            <span class="text-xs text-slate-500" id="word-count">0 words</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-3 pt-4">
      <button type="submit" name="save_draft" value="1" class="flex-1 px-6 py-3 bg-white border-2 border-slate-300 text-slate-700 rounded-lg font-semibold hover:bg-slate-50 transition-all inline-flex items-center justify-center gap-2">
        <span class="material-symbols-outlined text-base">visibility</span>
        Save as Draft
      </button>
      <button type="submit" class="flex-1 px-6 py-3 bg-accent text-white rounded-lg font-semibold hover:bg-purple-700 transition-all inline-flex items-center justify-center gap-2">
        <span class="material-symbols-outlined text-base">publish</span>
        Publish
      </button>
    </div>
    <p class="text-xs text-slate-500 text-center">By creating a story, you agree to our <a href="#" class="text-accent hover:underline">Terms of Service</a> and <a href="#" class="text-accent hover:underline">Content Guidelines</a>.</p>
  </form>
</main>

<script>
  // Cover upload handling
  const coverUploadArea = document.getElementById('cover-upload-area');
  const coverInput = document.getElementById('cover-upload');
  
  coverUploadArea.addEventListener('click', () => { 
    coverInput.click(); 
  });
  
  coverInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
      const fileName = e.target.files[0].name;
      // ✅ Hanya update teks & ikon, JANGAN replace innerHTML
      // karena akan menghapus <input name="cover"> dari DOM
      // sehingga file tidak ikut terkirim saat form disubmit
      const icon = document.getElementById('cover-icon');
      const labelText = document.getElementById('cover-label-text');
      const subText = document.getElementById('cover-sub-text');

      icon.textContent = 'check_circle';
      icon.classList.remove('text-slate-400');
      icon.classList.add('text-accent');

      labelText.textContent = fileName;
      labelText.classList.add('text-accent', 'font-semibold');
      labelText.classList.remove('text-slate-600');

      subText.textContent = 'Click to change image';
    }
  });

  // Word count for chapter content
  const chapterContent = document.getElementById('chapter-content');
  const wordCount = document.getElementById('word-count');
  
  if (chapterContent && wordCount) {
    chapterContent.addEventListener('input', () => {
      const text = chapterContent.value.trim();
      const words = text ? text.split(/\s+/).length : 0;
      wordCount.textContent = words + ' words';
    });
    
    // Hitung kata awal jika ada old value
    if (chapterContent.value) {
      const text = chapterContent.value.trim();
      const words = text ? text.split(/\s+/).length : 0;
      wordCount.textContent = words + ' words';
    }
  }
</script>

<?= $this->endSection() ?>