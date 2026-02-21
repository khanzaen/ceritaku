<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="max-w-6xl mx-auto px-6 py-10">
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

  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <h1 class="text-3xl font-bold text-primary">My Stories</h1>
    <a href="<?= site_url('write') ?>" class="bg-accent text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors inline-flex items-center gap-2">
      <span class="material-symbols-outlined text-base">add</span>
      Buat Cerita Baru
    </a>
  </div>

  <?php if(empty($stories)): ?>
    <div class="text-center py-12 bg-white rounded-2xl border border-border">
      <span class="material-symbols-outlined text-6xl text-slate-300 mb-4">menu_book</span>
      <p class="text-slate-500 mb-4">Anda belum memiliki cerita. Mulai buat cerita pertama Anda!</p>
      <a href="<?= site_url('write') ?>" class="bg-accent text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition-colors inline-flex items-center gap-2">
        <span class="material-symbols-outlined text-base">edit</span>
        Tulis Cerita
      </a>
    </div>
  <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach($stories as $story): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden hover:shadow-md transition-shadow">
          <?php if($story['cover_image'] && file_exists($story['cover_image'])): ?>
            <img src="<?= base_url($story['cover_image']) ?>" alt="<?= $story['title'] ?>" class="w-full h-48 object-cover">
          <?php else: ?>
            <div class="w-full h-48 bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center">
              <span class="material-symbols-outlined text-6xl text-slate-400">menu_book</span>
            </div>
          <?php endif; ?>
          
          <div class="p-4">
            <h3 class="font-bold text-lg mb-2 line-clamp-1"><?= $story['title'] ?></h3>
            
            <!-- Tampilkan kedua status -->
            <div class="flex flex-wrap gap-2 mb-3">
              <!-- System Status (DRAFT/PUBLISHED) -->
              <span class="px-2 py-1 bg-<?= $story['system_badge']['color'] ?>-100 text-<?= $story['system_badge']['color'] ?>-800 rounded-full text-xs font-medium">
                <?= $story['system_badge']['text'] ?>
              </span>
              
              <!-- Publication Status (Ongoing/Completed/Hiatus) -->
              <span class="px-2 py-1 bg-<?= $story['publication_badge']['color'] ?>-100 text-<?= $story['publication_badge']['color'] ?>-800 rounded-full text-xs font-medium">
                <?= $story['publication_badge']['text'] ?>
              </span>
            </div>
            
            <p class="text-sm text-slate-600 line-clamp-2 mb-4"><?= $story['description'] ?></p>
            
            <div class="flex gap-2">
              <a href="<?= site_url('story/' . $story['id']) ?>" class="flex-1 text-center bg-accent/10 text-accent px-3 py-2 rounded-lg text-sm hover:bg-accent/20 transition-colors">
                Lihat
              </a>
              <a href="<?= site_url('story/edit/' . $story['id']) ?>" class="flex-1 text-center bg-slate-100 text-slate-700 px-3 py-2 rounded-lg text-sm hover:bg-slate-200 transition-colors">
                Edit
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<?= $this->endSection() ?>