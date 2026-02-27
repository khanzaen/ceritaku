<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<!-- ═══════════════════ TOAST ═══════════════════ -->
<div id="toast-container" class="fixed top-6 right-6 z-50 flex flex-col gap-2 pointer-events-none"></div>
<style>
@keyframes toast-in  { 0%{opacity:0;transform:translateX(110%)} 100%{opacity:1;transform:translateX(0)} }
@keyframes toast-out { 0%{opacity:1;transform:translateX(0)} 100%{opacity:0;transform:translateX(110%)} }
.toast { pointer-events:all; animation:toast-in .35s cubic-bezier(.4,0,.2,1) forwards; }
.toast.hiding { animation:toast-out .3s cubic-bezier(.4,0,.2,1) forwards; }
</style>
<script>
function showToast(message, type='success') {
    const cfg = {
        success:{bg:'bg-green-50',border:'border-green-200',text:'text-green-700',icon:'check_circle',iconColor:'text-green-500'},
        error:  {bg:'bg-red-50',border:'border-red-200',text:'text-red-700',icon:'error',iconColor:'text-red-500'},
        warning:{bg:'bg-amber-50',border:'border-amber-200',text:'text-amber-700',icon:'warning',iconColor:'text-amber-500'},
        info:   {bg:'bg-indigo-50',border:'border-indigo-200',text:'text-indigo-700',icon:'info',iconColor:'text-indigo-500'},
    };
    const c = cfg[type] ?? cfg.success;
    const toast = document.createElement('div');
    toast.className = `toast ${c.bg} ${c.border} border rounded-xl shadow-lg px-4 py-3 flex items-center gap-3 min-w-[260px] max-w-xs`;
    toast.innerHTML = `<span class="material-symbols-outlined ${c.iconColor} text-xl flex-shrink-0" style="font-variation-settings:'FILL' 1">${c.icon}</span><p class="${c.text} text-sm font-medium flex-1 leading-snug">${message}</p><button onclick="dismissToast(this.parentElement)" class="${c.text} opacity-40 hover:opacity-80 transition text-lg leading-none flex-shrink-0">&times;</button>`;
    document.getElementById('toast-container').appendChild(toast);
    setTimeout(() => dismissToast(toast), 3500);
}
function dismissToast(el) {
    if (!el || el.classList.contains('hiding')) return;
    el.classList.add('hiding');
    setTimeout(() => el.remove(), 300);
}
<?php if (session()->getFlashdata('success')): ?>
window.addEventListener('DOMContentLoaded', () => showToast(<?= json_encode(session()->getFlashdata('success')) ?>, 'success'));
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
window.addEventListener('DOMContentLoaded', () => showToast(<?= json_encode(session()->getFlashdata('error')) ?>, 'error'));
<?php endif; ?>
</script>

<!-- ═══════════════════ SLIDE-OVER ═══════════════════ -->
<style>
    #lib-slideover { width: min(460px, 100vw); }
    @keyframes so-in  { from{transform:translateX(100%)} to{transform:translateX(0)} }
    @keyframes so-out { from{transform:translateX(0)} to{transform:translateX(100%)} }
    #lib-slideover.entering { animation:so-in .28s cubic-bezier(.32,0,.67,0) forwards; }
    #lib-slideover.leaving  { animation:so-out .25s cubic-bezier(.32,0,.67,0) forwards; }
    .pulse-bg { background:linear-gradient(90deg,#f3f4f6 25%,#e9eaec 50%,#f3f4f6 75%); background-size:200% 100%; animation:shimmer 1.4s infinite; }
    @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
    .action-btn { display:inline-flex; align-items:center; justify-content:center; width:30px; height:30px; border-radius:8px; transition:all .18s ease; border:none; cursor:pointer; }
    .action-btn.view   { background:#e0f2fe; color:#0284c7; }
    .action-btn.view:hover   { background:#0284c7; color:#fff; box-shadow:0 4px 12px rgba(2,132,199,.35); }
    .action-btn.delete { background:#fee2e2; color:#dc2626; }
    .action-btn.delete:hover { background:#dc2626; color:#fff; box-shadow:0 4px 12px rgba(220,38,38,.35); }
    /* Progress bar */
    .progress-bar { height:6px; border-radius:999px; background:#e5e7eb; overflow:hidden; }
    .progress-fill { height:100%; border-radius:999px; transition: width .5s ease; }
</style>

<div id="lib-slideover-backdrop" class="fixed inset-0 z-40 bg-black/25 backdrop-blur-[2px] hidden" onclick="closeSO()"></div>

<div id="lib-slideover" class="fixed top-0 right-0 h-full z-50 bg-white shadow-2xl flex flex-col translate-x-full">

    <!-- Header -->
    <div class="flex-shrink-0 bg-white border-b border-gray-100">
        <div class="flex items-center justify-between px-5 py-3.5">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-sky-600 flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-white text-sm" style="font-variation-settings:'FILL' 1">bookmarks</span>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800 leading-none">Library Entry</p>
                    <p class="text-[10px] text-gray-400 mt-0.5" id="so-header-sub">Loading...</p>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <a id="so-story-link" href="#" target="_blank" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-sky-600 transition" title="View story">
                    <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                </a>
                <button onclick="closeSO()" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Loading skeleton -->
    <div id="so-loading" class="flex-1 overflow-y-auto p-5 space-y-4">
        <div class="flex gap-4">
            <div class="pulse-bg w-12 h-16 rounded-xl flex-shrink-0"></div>
            <div class="flex-1 space-y-2 pt-1">
                <div class="pulse-bg h-4 rounded-lg w-3/4"></div>
                <div class="pulse-bg h-3 rounded-lg w-1/2"></div>
            </div>
        </div>
        <div class="pulse-bg h-4 rounded-full w-full"></div>
        <div class="grid grid-cols-3 gap-3">
            <div class="pulse-bg h-16 rounded-xl"></div>
            <div class="pulse-bg h-16 rounded-xl"></div>
            <div class="pulse-bg h-16 rounded-xl"></div>
        </div>
        <div class="pulse-bg h-16 rounded-xl"></div>
    </div>

    <!-- Main content -->
    <div id="so-content" class="flex-1 overflow-hidden hidden flex-col">
        <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4">

            <!-- Story + user -->
            <div class="flex gap-3">
                <div id="so-story-cover" class="w-12 h-16 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0 overflow-hidden shadow-sm">
                    <span class="material-symbols-outlined text-purple-300">menu_book</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p id="so-story-title" class="font-semibold text-gray-800 text-sm leading-snug truncate"></p>
                    <p id="so-author-name" class="text-xs text-gray-500 mt-0.5"></p>
                    <span id="so-reading-badge" class="mt-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold"></span>
                </div>
            </div>

            <!-- Progress bar -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Reading Progress</p>
                    <p class="text-xs font-bold text-sky-600" id="so-progress-pct">0%</p>
                </div>
                <div class="progress-bar">
                    <div id="so-progress-fill" class="progress-fill bg-sky-400" style="width:0%"></div>
                </div>
                <p class="text-[10px] text-gray-400 mt-1" id="so-progress-text"></p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <span class="material-symbols-outlined text-sky-500 text-lg block mb-0.5" style="font-variation-settings:'FILL' 1">format_list_numbered</span>
                    <p class="text-lg font-bold text-gray-800 leading-none" id="so-stat-progress">0</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Ch. Read</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <span class="material-symbols-outlined text-indigo-500 text-lg block mb-0.5" style="font-variation-settings:'FILL' 1">article</span>
                    <p class="text-lg font-bold text-gray-800 leading-none" id="so-stat-total">0</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Total Ch.</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <span class="material-symbols-outlined text-green-500 text-lg block mb-0.5" style="font-variation-settings:'FILL' 1">calendar_today</span>
                    <p class="text-xs font-semibold text-gray-700 leading-none mt-1" id="so-stat-added">-</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Added</p>
                </div>
            </div>

            <!-- User card -->
            <div class="border border-gray-100 rounded-xl p-3 flex items-center gap-3">
                <div id="so-user-avatar" class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 overflow-hidden font-bold text-indigo-700">
                    <span class="material-symbols-outlined text-indigo-400">person</span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold text-gray-800" id="so-user-name"></p>
                    <p class="text-[10px] text-gray-400 truncate" id="so-user-email"></p>
                </div>
                <span class="text-[10px] text-gray-400 bg-gray-50 px-2 py-0.5 rounded-full flex-shrink-0">Reader</span>
            </div>

        </div>

        <!-- Footer -->
        <div class="flex-shrink-0 px-5 py-4 border-t border-gray-100 bg-white flex gap-2.5">
            <button type="button" onclick="closeSO()" class="px-4 py-2.5 text-sm rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition font-medium flex-1">Close</button>
            <form id="so-delete-form" method="POST" action="" class="flex-1">
                <?= csrf_field() ?>
                <button type="button" onclick="openModal(document.getElementById('so-delete-form'), 'delete')"
                    class="w-full px-4 py-2.5 text-sm rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold transition flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-base" style="font-variation-settings:'FILL' 1">delete</span>Remove Entry
                </button>
            </form>
        </div>
    </div>

    <!-- Error state -->
    <div id="so-error" class="flex-1 hidden items-center justify-center px-6">
        <div class="text-center">
            <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <span class="material-symbols-outlined text-red-400 text-3xl">wifi_off</span>
            </div>
            <p class="text-sm font-semibold text-gray-700">Failed to load entry</p>
            <button onclick="closeSO()" class="mt-4 px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-600 transition">Close</button>
        </div>
    </div>
</div>

<script>
const LIB_BASE   = '<?= base_url('/admin/library/') ?>';
const UPLOAD_URL = '<?= base_url('uploads/') ?>';
const STORY_URL  = '<?= base_url('/story/') ?>';

function openSO(libId) {
    const backdrop = document.getElementById('lib-slideover-backdrop');
    const panel    = document.getElementById('lib-slideover');
    backdrop.classList.remove('hidden');
    panel.classList.remove('translate-x-full','leaving');
    panel.classList.add('entering');
    document.body.style.overflow = 'hidden';

    document.getElementById('so-loading').classList.remove('hidden');
    document.getElementById('so-content').classList.add('hidden');
    document.getElementById('so-content').classList.remove('flex');
    document.getElementById('so-error').classList.add('hidden');
    document.getElementById('so-error').classList.remove('flex');
    document.getElementById('so-header-sub').textContent = 'Loading...';

    fetch(`${LIB_BASE}detail/${libId}`, { headers:{'X-Requested-With':'XMLHttpRequest'} })
        .then(r => { if (!r.ok) throw new Error(); return r.json(); })
        .then(entry => {
            populateSO(entry);
            document.getElementById('so-loading').classList.add('hidden');
            document.getElementById('so-content').classList.remove('hidden');
            document.getElementById('so-content').classList.add('flex');
        })
        .catch(() => {
            document.getElementById('so-loading').classList.add('hidden');
            document.getElementById('so-error').classList.remove('hidden');
            document.getElementById('so-error').classList.add('flex');
        });
}

function closeSO() {
    const panel = document.getElementById('lib-slideover');
    panel.classList.remove('entering');
    panel.classList.add('leaving');
    setTimeout(() => {
        panel.classList.add('translate-x-full');
        panel.classList.remove('leaving');
        document.getElementById('lib-slideover-backdrop').classList.add('hidden');
        document.body.style.overflow = '';
    }, 240);
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSO(); });

function populateSO(e) {
    document.getElementById('so-header-sub').textContent = `Entry #${e.id}`;
    document.getElementById('so-story-link').href = `${STORY_URL}${e.story_id}`;

    const cover = document.getElementById('so-story-cover');
    if (e.story_cover) cover.innerHTML = `<img src="${UPLOAD_URL}${e.story_cover}" class="w-full h-full object-cover">`;

    document.getElementById('so-story-title').textContent = e.story_title || '-';
    document.getElementById('so-author-name').textContent  = e.author_name ? `by ${e.author_name}` : '';

    const isReading = parseInt(e.is_reading) === 1;
    const status    = e.status || (isReading ? 'reading' : 'finished');
    const badge     = document.getElementById('so-reading-badge');
    if (status === 'reading') {
        badge.className = 'mt-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-100 text-sky-700';
        badge.innerHTML = '<span class="material-symbols-outlined" style="font-size:13px;font-variation-settings:\'FILL\' 1">auto_stories</span>Reading';
    } else {
        badge.className = 'mt-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700';
        badge.innerHTML = '<span class="material-symbols-outlined" style="font-size:13px;font-variation-settings:\'FILL\' 1">check_circle</span>Finished';
    }

    const pct = parseInt(e.progress_percent) || 0;
    document.getElementById('so-progress-pct').textContent  = pct + '%';
    document.getElementById('so-progress-fill').style.width = pct + '%';
    document.getElementById('so-progress-fill').className   = `progress-fill ${pct === 100 ? 'bg-green-400' : 'bg-sky-400'}`;
    const prog = parseInt(e.progress) || 0;
    const tot  = parseInt(e.total_chapters) || 0;
    document.getElementById('so-progress-text').textContent = `Chapter ${prog} dari ${tot} chapter`;

    document.getElementById('so-stat-progress').textContent = prog;
    document.getElementById('so-stat-total').textContent    = tot;

    const fmt = d => d ? new Date(d).toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'}) : '-';
    document.getElementById('so-stat-added').textContent = fmt(e.added_at || e.created_at);

    const avatarEl = document.getElementById('so-user-avatar');
    const initial  = (e.user_name || 'U').charAt(0).toUpperCase();
    if (e.user_photo) {
        avatarEl.innerHTML = `<img src="${UPLOAD_URL}${e.user_photo}" class="w-full h-full object-cover">`;
    } else {
        avatarEl.innerHTML = `<span style="font-weight:700">${initial}</span>`;
    }
    document.getElementById('so-user-name').textContent  = e.user_name  || '-';
    document.getElementById('so-user-email').textContent = e.user_email || '-';

    document.getElementById('so-delete-form').action = `${LIB_BASE}delete/${e.id}`;
}
</script>

<!-- ═══════════════════ CONFIRM MODAL ═══════════════════ -->
<div id="confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6">
        <div class="flex items-start gap-3 mb-5">
            <div id="modal-icon-wrap" class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                <span id="modal-icon" class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' 1"></span>
            </div>
            <div class="flex-1">
                <p id="modal-title" class="font-semibold text-gray-800 text-base"></p>
                <p id="modal-body"  class="text-sm text-gray-500 mt-1 leading-relaxed"></p>
            </div>
        </div>
        <div class="flex justify-end gap-2">
            <button onclick="closeModal()" class="px-4 py-2 text-sm rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition">Cancel</button>
            <button id="modal-confirm-btn" class="px-4 py-2 text-sm rounded-xl font-semibold text-white transition"></button>
        </div>
    </div>
</div>
<script>
let _pendingForm = null;
const modalConfigs = {
    delete: { title:'Remove Library Entry', body:'Entry ini akan dihapus dari library user. Story masih tetap ada.', icon:'delete_forever', iconBg:'bg-red-100', iconColor:'text-red-600', btnBg:'bg-red-600 hover:bg-red-700', btnLabel:'Yes, Remove', toast:['Library entry dihapus.','error'] },
};
function openModal(form, type) {
    _pendingForm = form;
    const cfg = modalConfigs[type];
    document.getElementById('modal-title').textContent = cfg.title;
    document.getElementById('modal-body').textContent  = cfg.body;
    document.getElementById('modal-icon').textContent  = cfg.icon;
    document.getElementById('modal-icon-wrap').className = `w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 ${cfg.iconBg}`;
    document.getElementById('modal-icon').className = `material-symbols-outlined text-xl ${cfg.iconColor}`;
    const btn = document.getElementById('modal-confirm-btn');
    btn.textContent = cfg.btnLabel;
    btn.className   = `px-4 py-2 text-sm rounded-xl font-semibold text-white transition ${cfg.btnBg}`;
    btn.onclick     = () => { closeModal(); showToast(cfg.toast[0], cfg.toast[1]); setTimeout(() => form.submit(), 350); };
    document.getElementById('confirm-modal').classList.remove('hidden');
    document.getElementById('confirm-modal').classList.add('flex');
}
function closeModal() {
    document.getElementById('confirm-modal').classList.add('hidden');
    document.getElementById('confirm-modal').classList.remove('flex');
}
document.getElementById('confirm-modal').addEventListener('click', function(e) { if (e.target === this) closeModal(); });
</script>

<!-- ═══════════════════ STATS CARDS ═══════════════════ -->
<?php
    $libraries   = $libraries ?? [];
    $statTotal   = count($libraries);
    $statReading = count(array_filter($libraries, fn($l) => ($l['is_reading'] ?? 0) == 1 || ($l['status'] ?? '') === 'reading'));
    $statDone    = $statTotal - $statReading;
    $storyIds    = array_unique(array_column($libraries, 'story_id'));
    $userIds     = array_unique(array_column($libraries, 'user_id'));
    $statUniqStories = count($storyIds);
    $statUniqUsers   = count($userIds);
?>
<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-sky-600 text-xl" style="font-variation-settings:'FILL' 1">bookmarks</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Total Entries</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statTotal) ?></p></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-blue-600 text-xl" style="font-variation-settings:'FILL' 1">auto_stories</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Reading</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statReading) ?></p></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-green-600 text-xl" style="font-variation-settings:'FILL' 1">check_circle</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Finished</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statDone) ?></p></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-purple-600 text-xl" style="font-variation-settings:'FILL' 1">menu_book</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Unique Stories</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statUniqStories) ?></p></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-indigo-600 text-xl" style="font-variation-settings:'FILL' 1">group</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Unique Readers</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statUniqUsers) ?></p></div>
    </div>
</div>

<!-- ═══════════════════ TABLE ═══════════════════ -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-sky-500 text-xl">bookmarks</span>
            User Library
        </h3>
        <div class="flex items-center gap-2 flex-wrap">
            <select id="filterStatus" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300 bg-gray-50 text-gray-600">
                <option value="">All Status</option>
                <option value="1">Reading</option>
                <option value="0">Finished</option>
            </select>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base">search</span>
                <input type="text" id="searchInput" placeholder="Search library..." class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-300 w-52">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="libraryTable">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-medium w-10 text-gray-400">#</th>
                    <th class="px-4 py-3 text-left font-medium">User</th>
                    <th class="px-4 py-3 text-left font-medium">Story</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                    <th class="px-4 py-3 text-left font-medium">Progress</th>
                    <th class="px-4 py-3 text-left font-medium">Added</th>
                    <th class="px-4 py-3 text-center font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (!empty($libraries)): ?>
                    <?php foreach ($libraries as $i => $lib): ?>
                        <?php
                            $isReading = (int)($lib['is_reading'] ?? 1);
                            $status    = $lib['status'] ?? ($isReading ? 'reading' : 'finished');
                            $progress  = (int)($lib['progress'] ?? 0);
                            $totalCh   = (int)($lib['total_chapters'] ?? 0);
                            $pct       = $totalCh > 0 ? min(100, round($progress / $totalCh * 100)) : 0;
                        ?>
                        <tr class="hover:bg-gray-50/70 transition-colors" data-reading="<?= $isReading ?>">

                            <td class="px-4 py-3.5 text-gray-300 text-xs font-medium"><?= $i + 1 ?></td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <?php if (!empty($lib['user_photo'])): ?>
                                        <img src="<?= base_url('uploads/' . $lib['user_photo']) ?>" alt="" class="w-7 h-7 rounded-full object-cover border border-gray-100 flex-shrink-0">
                                    <?php else: ?>
                                        <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs flex-shrink-0">
                                            <?= strtoupper(substr($lib['user_name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <span class="font-medium text-gray-800 truncate max-w-[110px]"><?= esc($lib['user_name'] ?? '-') ?></span>
                                </div>
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <?php if (!empty($lib['story_cover'])): ?>
                                        <img src="<?= base_url('uploads/' . $lib['story_cover']) ?>" alt="" class="w-6 h-8 rounded object-cover flex-shrink-0">
                                    <?php endif; ?>
                                    <a href="<?= base_url('/story/' . ($lib['story_id'] ?? '')) ?>" target="_blank"
                                       class="text-sm text-gray-700 hover:text-sky-600 transition truncate max-w-[130px] block font-medium">
                                        <?= esc($lib['story_title'] ?? '-') ?>
                                    </a>
                                </div>
                            </td>

                            <td class="px-4 py-3.5">
                                <?php if ($status === 'reading'): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-sky-100 text-sky-700">
                                        <span class="material-symbols-outlined" style="font-size:12px;font-variation-settings:'FILL' 1">auto_stories</span>Reading
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-100 text-green-700">
                                        <span class="material-symbols-outlined" style="font-size:12px;font-variation-settings:'FILL' 1">check_circle</span>Finished
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="px-4 py-3.5 min-w-[140px]">
                                <div class="flex items-center gap-2">
                                    <div class="progress-bar flex-1">
                                        <div class="progress-fill <?= $pct === 100 ? 'bg-green-400' : 'bg-sky-400' ?>" style="width:<?= $pct ?>%"></div>
                                    </div>
                                    <span class="text-xs font-medium text-gray-500 w-9 text-right flex-shrink-0"><?= $pct ?>%</span>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-0.5">Ch.<?= $progress ?>/<?= $totalCh ?></p>
                            </td>

                            <td class="px-4 py-3.5 text-xs text-gray-400 whitespace-nowrap">
                                <?= !empty($lib['added_at']) ? date('d M Y', strtotime($lib['added_at'])) : '—' ?>
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" title="View Detail" class="action-btn view" onclick="openSO(<?= $lib['id'] ?>)">
                                        <span class="material-symbols-outlined" style="font-size:18px">open_in_new</span>
                                    </button>
                                    <form action="<?= base_url('/admin/library/delete/' . $lib['id']) ?>" method="POST" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="button" title="Remove" class="action-btn delete" onclick="openModal(this.closest('form'), 'delete')">
                                            <span class="material-symbols-outlined" style="font-size:16px">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <span class="material-symbols-outlined text-5xl text-gray-200 block mb-2">bookmarks</span>
                            <p class="text-gray-400 text-sm">No library entries found</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="px-6 py-3 bg-gray-50 text-xs text-gray-400 border-t border-gray-100 flex items-center justify-between">
        <span>Showing <span class="font-semibold text-gray-600" id="visibleCount"><?= count($libraries) ?></span> of <?= count($libraries) ?> entries</span>
    </div>
</div>

<script>
function applyFilters() {
    const q      = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('filterStatus').value;
    let visible  = 0;
    document.querySelectorAll('#libraryTable tbody tr').forEach(row => {
        const reading = row.getAttribute('data-reading') ?? '1';
        const show = (!q || row.textContent.toLowerCase().includes(q))
            && (!status || reading === status);
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    document.getElementById('visibleCount').textContent = visible;
}
['searchInput','filterStatus'].forEach(id => {
    document.getElementById(id).addEventListener(id === 'searchInput' ? 'input' : 'change', applyFilters);
});
</script>

<?= $this->endSection() ?>
