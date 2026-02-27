<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="max-w-4xl mx-auto px-6 py-10">

  <!-- Flash messages — ditampilkan via Global Toast (lihat main.php), blok ini sebagai fallback jika JS mati -->
  <noscript>
  <?php if(session()->getFlashdata('error')): ?>
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
      <?= session()->getFlashdata('error') ?>
    </div>
  <?php endif; ?>
  <?php if(session()->getFlashdata('success') || session()->getFlashdata('review')): ?>
    <div class="mb-4 p-4 bg-green-100 border border-green-500 text-green-700 rounded-lg">
      <?= session()->getFlashdata('success') ?? session()->getFlashdata('review') ?>
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
  </noscript>

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

      <!-- Story Status (read-only — diatur oleh admin) -->
      <?php
        $storyStatusMap = [
          'DRAFT'          => ['label'=>'Draft',          'bg'=>'bg-gray-100',   'text'=>'text-gray-600',   'icon'=>'draft',        'desc'=>'Story is saved and not visible to readers.'],
          'PENDING_REVIEW' => ['label'=>'Pending Review','bg'=>'bg-amber-100',  'text'=>'text-amber-700',  'icon'=>'pending',      'desc'=>'Story is waiting for admin approval.'],
          'PUBLISHED'      => ['label'=>'Published',    'bg'=>'bg-green-100',  'text'=>'text-green-700',  'icon'=>'check_circle', 'desc'=>'Story is live and visible to all readers.'],
          'ARCHIVED'       => ['label'=>'Archived',     'bg'=>'bg-red-100',    'text'=>'text-red-600',    'icon'=>'archive',      'desc'=>'Story is hidden by admin.'],
        ];
        $stStat = $storyStatusMap[$story['status']] ?? $storyStatusMap['DRAFT'];
      ?>
      <div class="bg-white border border-border rounded-2xl p-4 shadow-sm">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Story Status</p>
        <div class="flex items-center gap-3">
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold <?= $stStat['bg'] ?> <?= $stStat['text'] ?>">
            <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1"><?= $stStat['icon'] ?></span>
            <?= $stStat['label'] ?>
          </span>
          <p class="text-xs text-slate-500"><?= $stStat['desc'] ?></p>
        </div>
        <?php if ($story['status'] === 'DRAFT'): ?>
        <p class="text-[11px] text-slate-400 mt-2">
          Want to publish? <a href="#" class="text-accent font-semibold hover:underline" onclick="switchTab('detail')">Go to Story Detail tab</a> and click <strong>Publish for Review</strong>.
        </p>
        <?php endif; ?>
      </div>

      <!-- Cover -->
      <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
        <label class="block text-sm font-bold text-primary mb-4">Cover Image</label>
        <?php if($story['cover_image'] && file_exists(FCPATH . 'uploads/' . $story['cover_image'])): ?>
          <div class="mb-4">
            <img src="<?= base_url('uploads/' . $story['cover_image']) ?>" alt="Current cover"
              class="w-32 h-40 object-cover rounded-lg border border-border">
            <p class="text-xs text-slate-500 mt-1">Current cover</p>
          </div>
        <?php else: ?>
          <p class="text-sm text-slate-500 mb-4">No cover yet</p>
        <?php endif; ?>
        <div id="cover-upload-area" class="border-2 border-dashed border-border rounded-lg p-8 text-center hover:border-accent transition-colors cursor-pointer">
          <span id="cover-icon" class="material-symbols-outlined text-4xl text-slate-400 mb-2 block">upload</span>
          <p id="cover-label-text" class="text-sm text-slate-600 mb-1">Click to upload a new cover (optional)</p>
          <p id="cover-sub-text" class="text-xs text-slate-500">PNG, JPG up to 5MB</p>
          <input type="file" name="cover" accept="image/*" id="cover-upload" class="hidden" />
        </div>
      </div>

      <!-- Publication Status — HANYA tampil jika cerita sudah PUBLISHED -->
      <?php if ($story['status'] === 'PUBLISHED'): ?>
      <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
        <label class="block text-sm font-bold text-primary mb-1">Publication Status</label>
        <p class="text-xs text-slate-500 mb-4">
          Indicates whether the story is ongoing, completed, or on hiatus.
        </p>
        <div class="flex flex-wrap gap-4">
          <?php foreach(['Ongoing','Completed','On Hiatus'] as $pubOpt): ?>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="publication_status" value="<?= $pubOpt ?>"
                <?= (old('publication_status', $story['publication_status']) == $pubOpt) ? 'checked' : '' ?>
                class="w-4 h-4 text-accent focus:ring-2 focus:ring-accent/30" />
              <span class="text-sm text-slate-700"><?= $pubOpt ?></span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>
      <?php else: ?>
      <!-- Info: publication_status belum relevan sebelum cerita diterbitkan -->
      <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3">
        <span class="material-symbols-outlined text-amber-500 text-lg flex-shrink-0 mt-0.5">info</span>
        <div>
          <p class="text-sm font-semibold text-amber-800">Publication Status Not Available</p>
          <p class="text-xs text-amber-600 mt-0.5">
            Publication status (Ongoing/Completed/On Hiatus) can only be set after the story is published by admin.
            <?php if ($story['status'] === 'DRAFT'): ?>
              Your story is still a draft — submit for review to get it published.
            <?php elseif ($story['status'] === 'PENDING_REVIEW'): ?>
              Your story is waiting for admin review.
            <?php endif; ?>
          </p>
        </div>
      </div>
      <?php endif; ?>

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

    <!-- Publish / Re-submit for Review -->
    <?php if ($story['status'] !== 'ARCHIVED'): ?>
    <div class="mt-4 pt-4 border-t border-border">

      <!-- Hidden form — di-submit oleh modal konfirmasi -->
      <form id="submitReviewForm" action="<?= site_url('story/' . $story['id'] . '/submit-review') ?>" method="post">
        <?= csrf_field() ?>
      </form>

      <?php if ($story['status'] === 'DRAFT'): ?>
        <button type="button" onclick="openPublishModal('draft')"
          class="w-full px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-all inline-flex items-center justify-center gap-2">
          <span class="material-symbols-outlined text-base">send</span>
          Publish for Review
        </button>
        <p class="text-xs text-slate-400 mt-2 text-center">Story will be sent to admin for verification before being published.</p>

      <?php elseif ($story['status'] === 'PENDING_REVIEW'): ?>
        <div class="w-full px-6 py-3 bg-amber-50 border border-amber-300 text-amber-700 rounded-lg font-semibold inline-flex items-center justify-center gap-2">
          <span class="material-symbols-outlined text-base">pending</span>
          Awaiting Admin Review
        </div>
        <button type="button" onclick="openPublishModal('resubmit')"
          class="mt-2 w-full px-6 py-3 bg-white border-2 border-amber-400 text-amber-700 rounded-lg font-semibold hover:bg-amber-50 transition-all inline-flex items-center justify-center gap-2 text-sm">
          <span class="material-symbols-outlined text-base">refresh</span>
          Resubmit for Review
        </button>

      <?php elseif ($story['status'] === 'PUBLISHED'): ?>
        <button type="button" onclick="openPublishModal('update')"
          class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-all inline-flex items-center justify-center gap-2">
          <span class="material-symbols-outlined text-base">update</span>
          Update for Review
        </button>
        <p class="text-xs text-slate-400 mt-2 text-center">Changes will be reviewed by admin before being displayed publicly.</p>
      <?php endif; ?>

    </div>
    <?php endif; ?>
  </div>


  <!-- ============================================================ -->
  <!-- MODAL: KONFIRMASI PUBLISH FOR REVIEW                         -->
  <!-- ============================================================ -->
  <?php
    $draftCount   = count(array_filter($chapters ?? [], fn($c) => $c['status'] === 'DRAFT'));
    $pubCount     = count(array_filter($chapters ?? [], fn($c) => $c['status'] === 'PUBLISHED'));
    $pendingCount = count(array_filter($chapters ?? [], fn($c) => $c['status'] === 'PENDING_REVIEW'));
    $hasCoverFile = ($story['cover_image'] && file_exists(FCPATH . 'uploads/' . $story['cover_image']));
  ?>
  <div id="publishReviewModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
    role="dialog" aria-modal="true" aria-labelledby="prm-title">

    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closePublishModal()"></div>

    <!-- Card -->
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-8
                transform transition-all duration-300 scale-95 opacity-0" id="publishModalCard">

      <!-- Icon header -->
      <div class="flex flex-col items-center text-center mb-6">
        <div id="prm-icon-wrap" class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4">
          <span id="prm-icon" class="material-symbols-outlined text-3xl" style="font-variation-settings:'FILL' 1"></span>
        </div>
        <h2 id="prm-title" class="text-xl font-bold text-primary"></h2>
        <p id="prm-subtitle" class="text-sm text-slate-500 mt-1 leading-relaxed"></p>
      </div>

      <!-- Checklist validasi -->
      <div class="mb-5 space-y-2.5" id="prm-checklist"></div>

      <!-- Story info summary -->
      <div class="bg-slate-50 rounded-2xl p-4 mb-5 flex items-center gap-3">
        <?php if($hasCoverFile): ?>
          <img src="<?= base_url('uploads/' . $story['cover_image']) ?>" class="w-12 h-16 object-cover rounded-lg flex-shrink-0">
        <?php else: ?>
          <div class="w-12 h-16 bg-slate-200 rounded-lg flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-slate-400 text-xl">auto_stories</span>
          </div>
        <?php endif; ?>
        <div class="min-w-0">
          <p class="font-bold text-primary text-sm truncate"><?= esc($story['title']) ?></p>
          <p class="text-xs text-slate-500 mt-0.5"><?= count($chapters ?? []) ?> chapter &bull; <?= esc($story['genres'] ?: '—') ?></p>
          <div class="flex flex-wrap items-center gap-1 mt-1.5">
            <?php if($draftCount > 0): ?>
              <span class="text-[10px] bg-yellow-100 text-yellow-700 font-semibold px-2 py-0.5 rounded-full"><?= $draftCount ?> draft</span>
            <?php endif; ?>
            <?php if($pubCount > 0): ?>
              <span class="text-[10px] bg-green-100 text-green-700 font-semibold px-2 py-0.5 rounded-full"><?= $pubCount ?> published</span>
            <?php endif; ?>
            <?php if($pendingCount > 0): ?>
              <span class="text-[10px] bg-amber-100 text-amber-700 font-semibold px-2 py-0.5 rounded-full"><?= $pendingCount ?> pending</span>
            <?php endif; ?>
            <?php if(empty($chapters)): ?>
              <span class="text-[10px] bg-red-100 text-red-600 font-semibold px-2 py-0.5 rounded-full">No chapters yet</span>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Warning area (muncul jika ada persyaratan wajib yang belum terpenuhi) -->
      <div id="prm-warning" class="hidden mb-5 p-3 bg-red-50 border border-red-200 rounded-xl items-start gap-2">
        <span class="material-symbols-outlined text-red-500 text-base flex-shrink-0 mt-0.5" style="font-variation-settings:'FILL' 1">error</span>
        <p id="prm-warning-text" class="text-xs text-red-700 leading-relaxed font-medium"></p>
      </div>

      <!-- Action buttons -->
      <div class="flex gap-3 mt-1">
        <button type="button" onclick="closePublishModal()"
          class="flex-1 px-5 py-3 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-all text-sm">
          Cancel
        </button>
        <button type="button" id="prm-confirm-btn" onclick="submitPublishForm()"
          class="flex-1 px-5 py-3 rounded-xl font-semibold transition-all text-sm text-white inline-flex items-center justify-center gap-2">
          <span class="material-symbols-outlined text-base" id="prm-confirm-icon"></span>
          <span id="prm-confirm-label"></span>
        </button>
      </div>
    </div>
  </div>

  <script>
  // ── Data dari PHP ──────────────────────────────────────────────────────
  const _prmData = {
    chapterCount : <?= count($chapters ?? []) ?>,
    hasCover     : <?= $hasCoverFile ? 'true' : 'false' ?>,
    hasGenre     : <?= (!empty(trim($story['genres']))) ? 'true' : 'false' ?>,
    hasDesc      : <?= (!empty(trim($story['description']))) ? 'true' : 'false' ?>,
  };

  // ── Konfigurasi per mode ───────────────────────────────────────────────
  const PRM_CFG = {
    draft: {
      iconWrap : 'bg-green-100', icon: 'send', iconColor: 'text-green-600',
      title    : 'Publish for Review',
      subtitle : 'Cerita & semua chapter akan dikirim ke admin untuk ditinjau sebelum diterbitkan.',
      btnBg    : 'bg-green-600 hover:bg-green-700',
      btnIcon  : 'send', btnLabel: 'Yes, Submit for Review',
      checks: [
        { key:'hasDesc',        label:'Story description is filled in',     req: true  },
        { key:'hasGenre',       label:'At least 1 genre selected',    req: true  },
        { key:'hasCover',       label:'Story cover has been uploaded',      req: false },
        { key:'chapterOk',      label:'At least 1 chapter created',   req: true  },
      ]
    },
    resubmit: {
      iconWrap : 'bg-amber-100', icon: 'refresh', iconColor: 'text-amber-600',
      title    : 'Resubmit for Review',
      subtitle : 'Admin akan memeriksa ulang versi terbaru cerita & semua chapter kamu.',
      btnBg    : 'bg-amber-500 hover:bg-amber-600',
      btnIcon  : 'refresh', btnLabel: 'Yes, Resubmit',
      checks: [
        { key:'hasDesc',        label:'Story description is filled in',     req: true  },
        { key:'hasGenre',       label:'At least 1 genre selected',    req: true  },
        { key:'chapterOk',      label:'At least 1 chapter exists',      req: true  },
      ]
    },
    update: {
      iconWrap : 'bg-blue-100', icon: 'update', iconColor: 'text-blue-600',
      title    : 'Update for Review',
      subtitle : 'Perubahan terbaru akan direview admin sebelum tampil ke publik.',
      btnBg    : 'bg-blue-600 hover:bg-blue-700',
      btnIcon  : 'update', btnLabel: 'Yes, Submit Update',
      checks: [
        { key:'hasDesc',        label:'Story description is filled in',     req: true  },
        { key:'hasGenre',       label:'At least 1 genre selected',    req: true  },
        { key:'chapterOk',      label:'At least 1 chapter exists',      req: true  },
      ]
    },
  };

  function evalPrmCheck(key) {
    if (key === 'chapterOk') return _prmData.chapterCount > 0;
    return !!_prmData[key];
  }

  function openPublishModal(mode) {
    const cfg = PRM_CFG[mode];
    if (!cfg) return;

    // Header
    const iconWrap = document.getElementById('prm-icon-wrap');
    iconWrap.className = `w-16 h-16 rounded-2xl flex items-center justify-center mb-4 ${cfg.iconWrap}`;
    const icon = document.getElementById('prm-icon');
    icon.className = `material-symbols-outlined text-3xl ${cfg.iconColor}`;
    icon.textContent = cfg.icon;
    document.getElementById('prm-title').textContent    = cfg.title;
    document.getElementById('prm-subtitle').textContent = cfg.subtitle;

    // Checklist
    const list = document.getElementById('prm-checklist');
    list.innerHTML = '';
    let hasBlocker = false;

    cfg.checks.forEach(item => {
      const passed = evalPrmCheck(item.key);
      if (item.req && !passed) hasBlocker = true;

      const colors = passed
        ? { bg:'bg-green-50', icon:'check_circle', ic:'text-green-500', tx:'text-green-700' }
        : item.req
          ? { bg:'bg-red-50',   icon:'cancel',        ic:'text-red-500',   tx:'text-red-700'   }
          : { bg:'bg-amber-50', icon:'warning',        ic:'text-amber-500', tx:'text-amber-700' };

      const div = document.createElement('div');
      div.className = `flex items-center gap-3 px-4 py-3 rounded-xl ${colors.bg}`;
      div.innerHTML = `
        <span class="material-symbols-outlined text-lg flex-shrink-0 ${colors.ic}"
          style="font-variation-settings:'FILL' 1">${colors.icon}</span>
        <span class="text-sm font-medium ${colors.tx} flex-1">${item.label}</span>
        ${!item.req ? '<span class="text-[10px] text-slate-400 font-normal flex-shrink-0">optional</span>' : ''}
      `;
      list.appendChild(div);
    });

    // Warning
    const warnEl  = document.getElementById('prm-warning');
    const btn     = document.getElementById('prm-confirm-btn');
    if (hasBlocker) {
      warnEl.classList.remove('hidden');
      warnEl.classList.add('flex');
      document.getElementById('prm-warning-text').textContent =
        'Complete all required items above before submitting for review.';
      btn.disabled  = true;
      btn.className = 'flex-1 px-5 py-3 rounded-xl font-semibold text-sm text-white/70 inline-flex items-center justify-center gap-2 bg-slate-300 cursor-not-allowed';
    } else {
      warnEl.classList.add('hidden');
      warnEl.classList.remove('flex');
      btn.disabled  = false;
      btn.className = `flex-1 px-5 py-3 rounded-xl font-semibold transition-all text-sm text-white inline-flex items-center justify-center gap-2 ${cfg.btnBg}`;
    }

    document.getElementById('prm-confirm-icon').textContent  = cfg.btnIcon;
    document.getElementById('prm-confirm-label').textContent = cfg.btnLabel;

    // Tampilkan
    const modal = document.getElementById('publishReviewModal');
    const card  = document.getElementById('publishModalCard');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => requestAnimationFrame(() => {
      card.classList.remove('scale-95', 'opacity-0');
      card.classList.add('scale-100', 'opacity-100');
    }));
  }

  function closePublishModal() {
    const card  = document.getElementById('publishModalCard');
    const modal = document.getElementById('publishReviewModal');
    card.classList.remove('scale-100', 'opacity-100');
    card.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
      modal.classList.add('hidden');
      document.body.style.overflow = '';
    }, 280);
  }

  function submitPublishForm() {
    document.getElementById('submitReviewForm').submit();
  }

  document.addEventListener('keydown', e => { if (e.key === 'Escape') closePublishModal(); });
  </script>

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

    <!-- Info: publish chapter melalui story -->
    <?php if ($story['status'] !== 'PUBLISHED'): ?>
    <div class="mb-4 p-3 bg-violet-50 border border-violet-200 rounded-xl flex items-start gap-3">
      <span class="material-symbols-outlined text-violet-500 text-lg flex-shrink-0 mt-0.5">auto_awesome</span>
      <p class="text-xs text-violet-700 leading-relaxed">
        <strong>How to publish chapters:</strong> Click the
        <button onclick="switchTab('detail')" class="text-violet-600 font-bold underline hover:no-underline">Publish for Review</button>
        di tab <strong>Story Detail</strong> — semua chapter akan otomatis ikut dikirim untuk review bersama cerita.
      </p>
    </div>
    <?php endif; ?>

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
                  <?php elseif($ch['status'] === 'PENDING_REVIEW'): ?>
                    <span class="text-xs bg-amber-100 text-amber-700 font-semibold px-2 py-0.5 rounded-full">Pending Review</span>
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
                onsubmit="return confirm('Hapus chapter \'<?= esc($ch['title']) ?>\'? This action cannot be undone.')">
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
        <p class="text-slate-500 font-medium mb-1">No chapters yet</p>
        <p class="text-slate-400 text-sm mb-4">Start writing your first chapter</p>
        <a href="<?= site_url('story/' . $story['id'] . '/chapter/create') ?>"
          class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent text-white rounded-lg text-sm font-semibold hover:bg-purple-700 transition-all">
          <span class="material-symbols-outlined text-base">add</span>
          Write First Chapter
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
      document.getElementById('cover-sub-text').textContent = 'Click to change image';
    }
  });
</script>

<?= $this->endSection() ?>