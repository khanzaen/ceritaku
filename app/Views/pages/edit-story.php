<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="max-w-4xl mx-auto px-6 py-10">
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
    <a href="<?= site_url('my-stories') ?>" class="inline-flex items-center gap-1 text-sm text-slate-600 hover:text-accent transition-colors mb-4">
      <span class="material-symbols-outlined text-base">arrow_back</span>
      Back to My Stories
    </a>
    <h1 class="text-3xl md:text-4xl font-bold text-primary mb-2">Edit Story</h1>
    <p class="text-slate-600">Update your story details below.</p>
  </div>

  <form action="<?= site_url('story/update/' . $story['id']) ?>" method="post" enctype="multipart/form-data" class="space-y-6">
    <?= csrf_field() ?>
    
    <!-- Story Title -->
    <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
      <label for="title" class="block text-sm font-bold text-primary mb-2">Story Title *</label>
      <input type="text" id="title" name="title" value="<?= old('title', $story['title']) ?>" placeholder="Enter your story title" class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm" required maxlength="120" />
    </div>

    <!-- Story Description -->
    <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
      <label for="synopsis" class="block text-sm font-bold text-primary mb-2">Story Description *</label>
      <textarea id="synopsis" name="synopsis" rows="4" placeholder="Write a brief description of your story..." class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm resize-none" required maxlength="1000"><?= old('synopsis', $story['description']) ?></textarea>
    </div>

    <!-- Genre -->
    <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
      <h3 class="text-sm font-bold text-primary mb-4">Genre</h3>
      <div>
        <label class="block text-xs font-semibold text-slate-600 mb-3">Select Genres (Multiple) *</label>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
          <?php 
          $genres = ['Romance','Mystery','Fantasy','Drama','Sci-Fi','Thriller','Comedy','Politics','History','Adventure','Horror','Paranormal','Supernatural']; 
          $oldGenres = old('genre', $story['genres_array']);
          foreach($genres as $genre): 
            $genreLower = strtolower($genre);
          ?>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="genre[]" value="<?= $genreLower ?>" <?= in_array($genreLower, $oldGenres) ? 'checked' : '' ?> class="w-4 h-4 text-accent rounded focus:ring-2 focus:ring-accent/30" />
              <span class="text-sm text-slate-700"><?= $genre ?></span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Current Cover -->
    <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
      <label class="block text-sm font-bold text-primary mb-4">Current Cover</label>
      <?php if($story['cover_image'] && file_exists(FCPATH . 'uploads/' . $story['cover_image'])): ?>
        <div class="mb-4">
          <img src="<?= base_url('uploads/' . $story['cover_image']) ?>" alt="Current cover" class="w-32 h-40 object-cover rounded-lg border border-border">
        </div>
      <?php else: ?>
        <p class="text-sm text-slate-500 mb-4">No cover image</p>
      <?php endif; ?>

      <label class="block text-sm font-bold text-primary mb-2">Update Cover (Optional)</label>
      <div id="cover-upload-area" class="border-2 border-dashed border-border rounded-lg p-8 text-center hover:border-accent transition-colors cursor-pointer">
        <span id="cover-icon" class="material-symbols-outlined text-4xl text-slate-400 mb-2 block">upload</span>
        <p id="cover-label-text" class="text-sm text-slate-600 mb-1">Click to upload new cover</p>
        <p id="cover-sub-text" class="text-xs text-slate-500">PNG, JPG up to 5MB</p>
        <input type="file" name="cover" accept="image/*" id="cover-upload" class="hidden" />
      </div>
    </div>

    <!-- Publication Status -->
    <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
      <label class="block text-sm font-bold text-primary mb-4">Publication Status *</label>
      <div class="flex flex-wrap gap-3">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" name="publication_status" value="Ongoing" <?= (old('publication_status', $story['publication_status']) == 'Ongoing') ? 'checked' : '' ?> class="w-4 h-4 text-accent focus:ring-2 focus:ring-accent/30" />
          <span class="text-sm text-slate-700">Ongoing</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" name="publication_status" value="Completed" <?= (old('publication_status', $story['publication_status']) == 'Completed') ? 'checked' : '' ?> class="w-4 h-4 text-accent focus:ring-2 focus:ring-accent/30" />
          <span class="text-sm text-slate-700">Completed</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" name="publication_status" value="On Hiatus" <?= (old('publication_status', $story['publication_status']) == 'On Hiatus') ? 'checked' : '' ?> class="w-4 h-4 text-accent focus:ring-2 focus:ring-accent/30" />
          <span class="text-sm text-slate-700">On Hiatus</span>
        </label>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-3 pt-4">
      <a href="<?= site_url('my-stories') ?>" class="flex-1 px-6 py-3 bg-white border-2 border-slate-300 text-slate-700 rounded-lg font-semibold hover:bg-slate-50 transition-all inline-flex items-center justify-center gap-2">
        Cancel
      </a>
      <button type="submit" class="flex-1 px-6 py-3 bg-accent text-white rounded-lg font-semibold hover:bg-purple-700 transition-all inline-flex items-center justify-center gap-2">
        <span class="material-symbols-outlined text-base">save</span>
        Update Story
      </button>
    </div>
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
</script>

<?= $this->endSection() ?>