<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="max-w-4xl mx-auto px-6 py-10">

  <!-- Flash messages -->
  <?php if(session()->getFlashdata('error')): ?>
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
      <?= session()->getFlashdata('error') ?>
    </div>
  <?php endif; ?>
  <?php if(session()->getFlashdata('success')): ?>
    <div class="mb-4 p-4 bg-green-100 border border-green-500 text-green-700 rounded-lg">
      <?= session()->getFlashdata('success') ?>
    </div>
  <?php endif; ?>
  <?php if(session()->getFlashdata('errors')): ?>
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
      <ul class="list-disc list-inside">
        <?php foreach(session()->getFlashdata('errors') as $error): ?>
          <li><?= $error ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <!-- Header -->
  <div class="mb-6">
    <a href="<?= site_url('my-stories') ?>" class="inline-flex items-center gap-1 text-sm text-slate-600 hover:text-accent transition-colors mb-4">
      <span class="material-symbols-outlined text-base">arrow_back</span>
      Back to My Stories
    </a>
    <h1 class="text-3xl md:text-4xl font-bold text-primary mb-1">Edit Story</h1>
    <p class="text-slate-500 text-sm"><?= esc($story['title']) ?></p>
  </div>

  <!-- Tab Navigation -->
  <div class="flex gap-1 mb-6 bg-slate-100 p-1 rounded-xl w-fit">
    <button id="tab-btn-detail"
      onclick="switchTab('detail')"
      class="tab-btn px-5 py-2.5 rounded-lg text-sm font-semibold transition-all">
      <span class="material-symbols-outlined text-base align-middle mr-1">menu_book</span>
      Story Detail
    </button>
    <button id="tab-btn-chapters"
      onclick="switchTab('chapters')"
      class="tab-btn px-5 py-2.5 rounded-lg text-sm font-semibold transition-all">
      <span class="material-symbols-outlined text-base align-middle mr-1">format_list_numbered</span>
      Chapters
      <span class="ml-1 bg-slate-300 text-slate-700 text-xs font-bold px-2 py-0.5 rounded-full" id="chapter-count-badge">
        <?= count($chapters ?? []) ?>
      </span>
    </button>
  </div>

  <!-- ============================= -->
  <!-- TAB: STORY DETAIL             -->
  <!-- ============================= -->
  <div id="tab-detail">
    <form action="<?= site_url('story/update/' . $story['id']) ?>" method="post" enctype="multipart/form-data" class="space-y-6">
      <?= csrf_field() ?>

      <!-- Story Title -->
      <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
        <label for="title" class="block text-sm font-bold text-primary mb-2">Story Title *</label>
        <input type="text" id="title" name="title"
          value="<?= old('title', $story['title']) ?>"
          placeholder="Enter your story title"
          class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm"
          required maxlength="120" />
      </div>

      <!-- Description -->
      <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
        <label for="synopsis" class="block text-sm font-bold text-primary mb-2">Story Description *</label>
        <textarea id="synopsis" name="synopsis" rows="4"
          placeholder="Write a brief description of your story..."
          class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm resize-none"
          required maxlength="1000"><?= old('synopsis', $story['description']) ?></textarea>
      </div>

      <!-- Genre -->
      <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
        <h3 class="text-sm font-bold text-primary mb-4">Genre *</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
          <?php
          $genreOptions = ['Romance','Mystery','Fantasy','Drama','Sci-Fi','Thriller','Comedy','Politics','History','Adventure','Horror','Paranormal','Supernatural'];
$oldGenres = array_map('strtolower', old('genre', $story['genres_array']));
          foreach($genreOptions as $g):
            $gLower = strtolower($g);
          ?>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="genre[]" value="<?= $gLower ?>"
                <?= in_array($gLower, $oldGenres) ? 'checked' : '' ?>
                class="w-4 h-4 text-accent rounded focus:ring-2 focus:ring-accent/30" />
              <span class="text-sm text-slate-700"><?= $g ?></span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Cover -->
      <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
        <label class="block text-sm font-bold text-primary mb-4">Cover Image</label>
        <?php if($story['cover_image'] && file_exists(FCPATH . 'uploads/' . $story['cover_image'])): ?>
          <div class="mb-4">
            <img src="<?= base_url('uploads/' . $story['cover_image']) ?>" alt="Current cover"
              class="w-32 h-40 object-cover rounded-lg border border-border">
            <p class="text-xs text-slate-500 mt-1">Cover saat ini</p>
          </div>
        <?php else: ?>
          <p class="text-sm text-slate-500 mb-4">Belum ada cover</p>
        <?php endif; ?>
        <div id="cover-upload-area" class="border-2 border-dashed border-border rounded-lg p-8 text-center hover:border-accent transition-colors cursor-pointer">
          <span id="cover-icon" class="material-symbols-outlined text-4xl text-slate-400 mb-2 block">upload</span>
          <p id="cover-label-text" class="text-sm text-slate-600 mb-1">Klik untuk upload cover baru (opsional)</p>
          <p id="cover-sub-text" class="text-xs text-slate-500">PNG, JPG hingga 5MB</p>
          <input type="file" name="cover" accept="image/*" id="cover-upload" class="hidden" />
        </div>
      </div>

      <!-- Publication Status -->
      <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
        <label class="block text-sm font-bold text-primary mb-4">Publication Status *</label>
        <div class="flex flex-wrap gap-4">
          <?php foreach(['Ongoing','Completed','On Hiatus'] as $status): ?>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="publication_status" value="<?= $status ?>"
                <?= (old('publication_status', $story['publication_status']) == $status) ? 'checked' : '' ?>
                class="w-4 h-4 text-accent focus:ring-2 focus:ring-accent/30" />
              <span class="text-sm text-slate-700"><?= $status ?></span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-3 pt-2">
        <a href="<?= site_url('my-stories') ?>" class="flex-1 px-6 py-3 bg-white border-2 border-slate-300 text-slate-700 rounded-lg font-semibold hover:bg-slate-50 transition-all inline-flex items-center justify-center gap-2">
          Cancel
        </a>
        <button type="submit" class="flex-1 px-6 py-3 bg-accent text-white rounded-lg font-semibold hover:bg-purple-700 transition-all inline-flex items-center justify-center gap-2">
          <span class="material-symbols-outlined text-base">save</span>
          Update Story Detail
        </button>
      </div>
    </form>
  </div>

  <!-- ============================= -->
  <!-- TAB: CHAPTERS                 -->
  <!-- ============================= -->
  <div id="tab-chapters" class="hidden">

    <!-- Tombol Add Chapter -->
    <div class="flex items-center justify-between mb-4">
      <p class="text-sm text-slate-500"><?= count($chapters ?? []) ?> saved chapters</p>
      <a href="<?= site_url('story/' . $story['id'] . '/chapter/create') ?>"
        class="inline-flex items-center gap-2 px-4 py-2 bg-accent text-white rounded-lg text-sm font-semibold hover:bg-purple-700 transition-all">
        <span class="material-symbols-outlined text-base">add</span>
        Add Chapter
      </a>
    </div>

    <!-- Daftar Chapter -->
    <?php if(!empty($chapters)): ?>
      <div class="space-y-3">
        <?php foreach($chapters as $ch): ?>
          <div class="bg-white border border-border rounded-xl p-4 shadow-sm flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
              <!-- Chapter number badge -->
              <span class="flex-shrink-0 w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-sm font-bold text-slate-600">
                <?= $ch['chapter_number'] ?>
              </span>
              <div class="min-w-0">
                <p class="font-semibold text-primary text-sm truncate"><?= esc($ch['title']) ?></p>
                <div class="flex items-center gap-2 mt-0.5">
                  <!-- Status badge -->
                  <?php if($ch['status'] === 'PUBLISHED'): ?>
                    <span class="text-xs bg-green-100 text-green-700 font-semibold px-2 py-0.5 rounded-full">Published</span>
                  <?php elseif($ch['status'] === 'DRAFT'): ?>
                    <span class="text-xs bg-yellow-100 text-yellow-700 font-semibold px-2 py-0.5 rounded-full">Draft</span>
                  <?php else: ?>
                    <span class="text-xs bg-slate-100 text-slate-500 font-semibold px-2 py-0.5 rounded-full"><?= $ch['status'] ?></span>
                  <?php endif; ?>
                  <?php if($ch['is_premium']): ?>
                    <span class="text-xs bg-amber-100 text-amber-700 font-semibold px-2 py-0.5 rounded-full">Premium</span>
                  <?php endif; ?>
                  <span class="text-xs text-slate-400"><?= date('d M Y', strtotime($ch['updated_at'])) ?></span>
                </div>
              </div>
            </div>

            <!-- Action buttons -->
            <div class="flex items-center gap-2 flex-shrink-0">
              <?php if($ch['status'] === 'PUBLISHED'): ?>
                <a href="<?= site_url('read-chapter/' . $ch['id']) ?>" target="_blank"
                  class="p-2 text-slate-400 hover:text-accent transition-colors" title="Baca">
                  <span class="material-symbols-outlined text-lg">visibility</span>
                </a>
              <?php endif; ?>
              <a href="<?= site_url('story/' . $story['id'] . '/chapter/' . $ch['id'] . '/edit') ?>"
                class="p-2 text-slate-400 hover:text-blue-600 transition-colors" title="Edit">
                <span class="material-symbols-outlined text-lg">edit</span>
              </a>
              <!-- Delete confirmation -->
              <form action="<?= site_url('story/' . $story['id'] . '/chapter/' . $ch['id'] . '/delete') ?>" method="post"
                onsubmit="return confirm('Hapus chapter \'<?= esc($ch['title']) ?>\'? Tindakan ini tidak bisa dibatalkan.')">
                <?= csrf_field() ?>
                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition-colors" title="Hapus">
                  <span class="material-symbols-outlined text-lg">delete</span>
                </button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <!-- Empty state -->
      <div class="bg-white border-2 border-dashed border-border rounded-2xl p-12 text-center">
        <span class="material-symbols-outlined text-5xl text-slate-300 mb-3 block">auto_stories</span>
        <p class="text-slate-500 font-medium mb-1">Belum ada chapter</p>
        <p class="text-slate-400 text-sm mb-4">Mulai tulis chapter pertama ceritamu</p>
        <a href="<?= site_url('story/' . $story['id'] . '/chapter/create') ?>"
          class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent text-white rounded-lg text-sm font-semibold hover:bg-purple-700 transition-all">
          <span class="material-symbols-outlined text-base">add</span>
          Tulis Chapter Pertama
        </a>
      </div>
    <?php endif; ?>
  </div>

</main>

<script>
  // ---- Tab switching ----
  function switchTab(tab) {
    const tabs = ['detail', 'chapters'];

    tabs.forEach(t => {
      const el = document.getElementById('tab-' + t);
      const btn = document.getElementById('tab-btn-' + t);
      if (t === tab) {
        el.classList.remove('hidden');
        btn.classList.add('bg-white', 'text-primary', 'shadow-sm');
        btn.classList.remove('text-slate-500');
      } else {
        el.classList.add('hidden');
        btn.classList.remove('bg-white', 'text-primary', 'shadow-sm');
        btn.classList.add('text-slate-500');
      }
    });

    // Update URL tanpa reload
    const url = new URL(window.location);
    url.searchParams.set('tab', tab);
    history.replaceState(null, '', url);
  }

  // ---- Baca tab dari URL param ----
  (function() {
    const params = new URLSearchParams(window.location.search);
    const tab = params.get('tab') || 'detail';
    switchTab(tab);
  })();

  // ---- Cover upload preview ----
  const coverUploadArea = document.getElementById('cover-upload-area');
  const coverInput = document.getElementById('cover-upload');

  coverUploadArea.addEventListener('click', () => coverInput.click());

  coverInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
      const fileName = e.target.files[0].name;
      document.getElementById('cover-icon').textContent = 'check_circle';
      document.getElementById('cover-icon').classList.replace('text-slate-400', 'text-accent');
      document.getElementById('cover-label-text').textContent = fileName;
      document.getElementById('cover-sub-text').textContent = 'Klik untuk ganti gambar';
    }
  });
</script>

<?= $this->endSection() ?>
