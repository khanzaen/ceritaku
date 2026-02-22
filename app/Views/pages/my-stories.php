<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="max-w-6xl mx-auto px-6 py-10">
  <!-- Success message -->
  <?php if(session()->getFlashdata('success')): ?>
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
      <?= session()->getFlashdata('success') ?>
    </div>
  <?php endif; ?>

  <!-- Error message -->
  <?php if(session()->getFlashdata('error')): ?>
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
      <?= session()->getFlashdata('error') ?>
    </div>
  <?php endif; ?>

  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <h1 class="text-3xl font-bold text-primary">My Stories</h1>
    <a href="<?= site_url('write') ?>" class="bg-accent text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors inline-flex items-center gap-2">
      <span class="material-symbols-outlined text-base">add</span>
      Create New Story
    </a>
  </div>

  <?php if(empty($stories)): ?>
    <div class="text-center py-12 bg-white rounded-2xl border border-border">
      <span class="material-symbols-outlined text-6xl text-slate-300 mb-4 block">menu_book</span>
      <p class="text-slate-500 mb-4">You don't have any stories yet. Start creating your first story!</p>
      <a href="<?= site_url('write') ?>" class="bg-accent text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition-colors inline-flex items-center gap-2">
        <span class="material-symbols-outlined text-base">edit</span>
        Write Story
      </a>
    </div>
  <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach($stories as $story): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden hover:shadow-md transition-shadow">

          <!-- Cover Image -->
          <?php if($story['cover_image'] && file_exists(FCPATH . 'uploads/' . $story['cover_image'])): ?>
            <img src="<?= base_url('uploads/' . $story['cover_image']) ?>" alt="<?= esc($story['title']) ?>" class="w-full h-48 object-cover">
          <?php else: ?>
            <div class="w-full h-48 bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center">
              <span class="material-symbols-outlined text-6xl text-slate-400">menu_book</span>
            </div>
          <?php endif; ?>

          <div class="p-4">
            <h3 class="font-bold text-lg mb-2 line-clamp-1"><?= esc($story['title']) ?></h3>

            <!-- Badge Status -->
            <div class="flex flex-wrap gap-2 mb-3">
              <span class="px-2 py-1 bg-<?= $story['system_badge']['color'] ?>-100 text-<?= $story['system_badge']['color'] ?>-800 rounded-full text-xs font-medium">
                <?= $story['system_badge']['text'] ?>
              </span>
              <span class="px-2 py-1 bg-<?= $story['publication_badge']['color'] ?>-100 text-<?= $story['publication_badge']['color'] ?>-800 rounded-full text-xs font-medium">
                <?= $story['publication_badge']['text'] ?>
              </span>
            </div>

            <p class="text-sm text-slate-600 line-clamp-2 mb-4"><?= esc($story['description']) ?></p>

            <!-- Action Buttons -->
            <div class="flex gap-2">
              <a href="<?= site_url('story/' . $story['id']) ?>"
                 class="flex-1 text-center bg-accent/10 text-accent px-3 py-2 rounded-lg text-sm hover:bg-accent/20 transition-colors">
                Read
              </a>
              <a href="<?= site_url('story/edit/' . $story['id']) ?>"
                 class="flex-1 text-center bg-slate-100 text-slate-700 px-3 py-2 rounded-lg text-sm hover:bg-slate-200 transition-colors">
                Edit
              </a>
              <button onclick="confirmDelete(<?= $story['id'] ?>, '<?= esc($story['title'], 'js') ?>')"
                      class="flex-1 text-center bg-red-50 text-red-600 px-3 py-2 rounded-lg text-sm hover:bg-red-100 transition-colors">
                Delete
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-2xl p-6 max-w-sm w-full mx-4 shadow-xl">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
        <span class="material-symbols-outlined text-red-600">warning</span>
      </div>
      <h3 class="text-lg font-bold text-primary">Delete Story?</h3>
    </div>
    <p class="text-slate-600 text-sm mb-1">You are about to delete the story:</p>
    <p id="deleteStoryTitle" class="font-semibold text-primary mb-4 line-clamp-2"></p>
    <p class="text-slate-500 text-xs mb-6">This action cannot be undone. All chapters in this story will also be deleted.</p>
    <div class="flex gap-3">
      <button onclick="closeDeleteModal()"
              class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg text-sm hover:bg-slate-50 transition-colors">
        Cancel
      </button>
      <form id="deleteForm" method="post" class="flex-1">
        <?= csrf_field() ?>
        <button type="submit"
                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700 transition-colors">
          Yes, Delete
        </button>
      </form>
    </div>
  </div>
</div>

<script>
  function confirmDelete(id, title) {
    document.getElementById('deleteStoryTitle').textContent = title;
    document.getElementById('deleteForm').action = `<?= site_url('story/delete/') ?>${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
  }

  function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
  }

  // Close modal when clicking outside the modal area
  document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
  });
</script>

<?= $this->endSection() ?>