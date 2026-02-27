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
    #comment-slideover { width: min(460px, 100vw); }
    @keyframes so-in  { from{transform:translateX(100%)} to{transform:translateX(0)} }
    @keyframes so-out { from{transform:translateX(0)} to{transform:translateX(100%)} }
    #comment-slideover.entering { animation:so-in .28s cubic-bezier(.32,0,.67,0) forwards; }
    #comment-slideover.leaving  { animation:so-out .25s cubic-bezier(.32,0,.67,0) forwards; }
    .pulse-bg { background:linear-gradient(90deg,#f3f4f6 25%,#e9eaec 50%,#f3f4f6 75%); background-size:200% 100%; animation:shimmer 1.4s infinite; }
    @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
    .action-btn { display:inline-flex; align-items:center; justify-content:center; width:30px; height:30px; border-radius:8px; transition:all .18s ease; border:none; cursor:pointer; }
    .action-btn.view   { background:#e0e7ff; color:#6366f1; }
    .action-btn.view:hover   { background:#6366f1; color:#fff; box-shadow:0 4px 12px rgba(99,102,241,.35); }
    .action-btn.delete { background:#fee2e2; color:#dc2626; }
    .action-btn.delete:hover { background:#dc2626; color:#fff; box-shadow:0 4px 12px rgba(220,38,38,.35); }
</style>

<div id="comment-slideover-backdrop" class="fixed inset-0 z-40 bg-black/25 backdrop-blur-[2px] hidden" onclick="closeSO()"></div>

<div id="comment-slideover" class="fixed top-0 right-0 h-full z-50 bg-white shadow-2xl flex flex-col translate-x-full">

    <!-- Header -->
    <div class="flex-shrink-0 bg-white border-b border-gray-100">
        <div class="flex items-center justify-between px-5 py-3.5">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-violet-600 flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-white text-sm" style="font-variation-settings:'FILL' 1">chat_bubble</span>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800 leading-none">Comment Detail</p>
                    <p class="text-[10px] text-gray-400 mt-0.5" id="so-header-sub">Loading...</p>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <a id="so-chapter-link" href="#" target="_blank" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-violet-600 transition" title="View chapter">
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
        <div class="flex gap-3">
            <div class="pulse-bg w-9 h-9 rounded-full flex-shrink-0"></div>
            <div class="flex-1 space-y-2">
                <div class="pulse-bg h-3 rounded-lg w-2/3"></div>
                <div class="pulse-bg h-3 rounded-lg w-1/3"></div>
            </div>
        </div>
        <div class="pulse-bg h-24 rounded-xl w-full"></div>
        <div class="pulse-bg h-16 rounded-xl w-full"></div>
        <div class="pulse-bg h-16 rounded-xl w-full"></div>
    </div>

    <!-- Main content -->
    <div id="so-content" class="flex-1 overflow-hidden hidden flex-col">
        <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4">

            <!-- Commenter -->
            <div class="flex items-center gap-3">
                <div id="so-avatar" class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 overflow-hidden font-bold text-indigo-700">
                    <span class="material-symbols-outlined text-indigo-400">person</span>
                </div>
                <div class="min-w-0 flex-1">
                    <p id="so-user-name" class="font-semibold text-gray-800 text-sm"></p>
                    <p id="so-user-email" class="text-xs text-gray-400 mt-0.5 truncate"></p>
                </div>
                <p id="so-date" class="text-[10px] text-gray-400 flex-shrink-0"></p>
            </div>

            <!-- Comment bubble -->
            <div class="bg-violet-50 border border-violet-100 rounded-2xl rounded-tl-sm px-4 py-3">
                <p id="so-comment-text" class="text-sm text-gray-700 leading-relaxed"></p>
            </div>

            <!-- Chapter card -->
            <div class="border border-gray-100 rounded-xl p-3">
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Chapter</p>
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <span id="so-ch-num" class="text-xs font-bold text-blue-500"></span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p id="so-chapter-title" class="text-xs font-semibold text-gray-800 truncate"></p>
                        <p id="so-story-title" class="text-[10px] text-gray-500 mt-0.5 truncate"></p>
                    </div>
                </div>
            </div>

            <!-- Author info -->
            <div class="border border-gray-100 rounded-xl p-3 flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-purple-400" style="font-size:14px">edit_note</span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] text-gray-400">Story Author</p>
                    <p id="so-author-name" class="text-xs font-semibold text-gray-800"></p>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="flex-shrink-0 px-5 py-4 border-t border-gray-100 bg-white flex gap-2.5">
            <button type="button" onclick="closeSO()" class="px-4 py-2.5 text-sm rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition font-medium flex-1">Close</button>
            <form id="so-delete-form" method="POST" action="" class="flex-1">
                <?= csrf_field() ?>
                <button type="button" onclick="openModal(document.getElementById('so-delete-form'), 'delete')"
                    class="w-full px-4 py-2.5 text-sm rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold transition flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-base" style="font-variation-settings:'FILL' 1">delete</span>Delete Comment
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
            <p class="text-sm font-semibold text-gray-700">Failed to load comment</p>
            <button onclick="closeSO()" class="mt-4 px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-600 transition">Close</button>
        </div>
    </div>
</div>

<script>
const CMT_BASE   = '<?= base_url('/admin/comments/') ?>';
const UPLOAD_URL = '<?= base_url('uploads/') ?>';
const CHAPTER_URL= '<?= base_url('/chapter/') ?>';

function openSO(cmtId) {
    const backdrop = document.getElementById('comment-slideover-backdrop');
    const panel    = document.getElementById('comment-slideover');
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

    fetch(`${CMT_BASE}detail/${cmtId}`, { headers:{'X-Requested-With':'XMLHttpRequest'} })
        .then(r => { if (!r.ok) throw new Error(); return r.json(); })
        .then(c => {
            populateSO(c);
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
    const panel = document.getElementById('comment-slideover');
    panel.classList.remove('entering');
    panel.classList.add('leaving');
    setTimeout(() => {
        panel.classList.add('translate-x-full');
        panel.classList.remove('leaving');
        document.getElementById('comment-slideover-backdrop').classList.add('hidden');
        document.body.style.overflow = '';
    }, 240);
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSO(); });

function populateSO(c) {
    document.getElementById('so-header-sub').textContent = `Comment #${c.id}`;
    document.getElementById('so-chapter-link').href = `${CHAPTER_URL}${c.chapter_id}`;

    const avatarEl = document.getElementById('so-avatar');
    const initial  = (c.user_name || 'U').charAt(0).toUpperCase();
    if (c.user_photo) {
        avatarEl.innerHTML = `<img src="${UPLOAD_URL}${c.user_photo}" class="w-full h-full object-cover">`;
    } else {
        avatarEl.innerHTML = `<span style="font-weight:700">${initial}</span>`;
    }

    document.getElementById('so-user-name').textContent  = c.user_name  || '-';
    document.getElementById('so-user-email').textContent = c.user_email || '-';

    const fmt = d => d ? new Date(d).toLocaleDateString('id-ID', {day:'2-digit',month:'short',year:'numeric'}) : '-';
    document.getElementById('so-date').textContent = fmt(c.created_at);

    document.getElementById('so-comment-text').textContent = c.comment || '-';

    document.getElementById('so-ch-num').textContent       = '#' + (c.chapter_number || '?');
    document.getElementById('so-chapter-title').textContent = c.chapter_title || '-';
    document.getElementById('so-story-title').textContent   = c.story_title   || '-';
    document.getElementById('so-author-name').textContent   = c.author_name   || '-';

    document.getElementById('so-delete-form').action = `${CMT_BASE}delete/${c.id}`;
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
    delete: { title:'Delete Comment', body:'Comment ini akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.', icon:'delete_forever', iconBg:'bg-red-100', iconColor:'text-red-600', btnBg:'bg-red-600 hover:bg-red-700', btnLabel:'Yes, Delete', toast:['Comment dihapus.','error'] },
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
    $comments    = $comments ?? [];
    $statTotal   = count($comments);
    $storyIds    = array_unique(array_filter(array_column($comments, 'story_id')));
    $userIds     = array_unique(array_filter(array_column($comments, 'user_id')));
    $chapterIds  = array_unique(array_filter(array_column($comments, 'chapter_id')));
    $statStories = count($storyIds);
    $statUsers   = count($userIds);
    $statChapters= count($chapterIds);
    // Latest 24h
    $stat24h = count(array_filter($comments, function($c) {
        return !empty($c['created_at']) && strtotime($c['created_at']) > (time() - 86400);
    }));
?>
<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-violet-600 text-xl" style="font-variation-settings:'FILL' 1">chat_bubble</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Total</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statTotal) ?></p></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-green-600 text-xl" style="font-variation-settings:'FILL' 1">schedule</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Last 24h</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($stat24h) ?></p></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-indigo-600 text-xl" style="font-variation-settings:'FILL' 1">group</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Commenters</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statUsers) ?></p></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-blue-600 text-xl" style="font-variation-settings:'FILL' 1">article</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Chapters</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statChapters) ?></p></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-purple-600 text-xl" style="font-variation-settings:'FILL' 1">menu_book</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Stories</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statStories) ?></p></div>
    </div>
</div>

<!-- ═══════════════════ TABLE ═══════════════════ -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-violet-500 text-xl">chat_bubble</span>
            Comments
        </h3>
        <div class="flex items-center gap-2 flex-wrap">
            <select id="filterStory" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-violet-300 bg-gray-50 text-gray-600">
                <option value="">All Stories</option>
                <?php
                    $uniqueStories = [];
                    foreach ($comments as $c) {
                        $sid = $c['story_id'] ?? null;
                        if ($sid && !isset($uniqueStories[$sid])) {
                            $uniqueStories[$sid] = $c['story_title'] ?? 'Story #'.$sid;
                        }
                    }
                    asort($uniqueStories);
                    foreach ($uniqueStories as $sid => $stitle): ?>
                    <option value="<?= esc($sid) ?>"><?= esc($stitle) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base">search</span>
                <input type="text" id="searchInput" placeholder="Search comments..." class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-300 w-52">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="commentsTable">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-medium w-10 text-gray-400">#</th>
                    <th class="px-4 py-3 text-left font-medium">User</th>
                    <th class="px-4 py-3 text-left font-medium">Comment</th>
                    <th class="px-4 py-3 text-left font-medium">Chapter</th>
                    <th class="px-4 py-3 text-left font-medium">Story</th>
                    <th class="px-4 py-3 text-left font-medium">Date</th>
                    <th class="px-4 py-3 text-center font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $i => $cmt): ?>
                        <tr class="hover:bg-gray-50/70 transition-colors" data-story="<?= esc($cmt['story_id'] ?? '') ?>">

                            <td class="px-4 py-3.5 text-gray-300 text-xs font-medium"><?= $i + 1 ?></td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <?php if (!empty($cmt['user_photo'])): ?>
                                        <img src="<?= base_url('uploads/' . $cmt['user_photo']) ?>" alt="" class="w-7 h-7 rounded-full object-cover border border-gray-100 flex-shrink-0">
                                    <?php else: ?>
                                        <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs flex-shrink-0">
                                            <?= strtoupper(substr($cmt['user_name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <span class="font-medium text-gray-800 truncate max-w-[100px]"><?= esc($cmt['user_name'] ?? '-') ?></span>
                                </div>
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="bg-gray-50 rounded-lg px-3 py-2 max-w-[220px]">
                                    <p class="text-xs text-gray-600 line-clamp-2" title="<?= esc($cmt['comment'] ?? '') ?>">
                                        <?= esc($cmt['comment'] ?? '-') ?>
                                    </p>
                                </div>
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-6 h-6 rounded bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <span class="text-[9px] font-bold text-blue-500"><?= $cmt['chapter_number'] ?? '?' ?></span>
                                    </div>
                                    <span class="text-xs text-gray-600 truncate max-w-[120px]"><?= esc($cmt['chapter_title'] ?? '-') ?></span>
                                </div>
                            </td>

                            <td class="px-4 py-3.5">
                                <a href="<?= base_url('/story/' . ($cmt['story_id'] ?? '')) ?>" target="_blank"
                                   class="text-xs text-gray-600 hover:text-violet-600 transition truncate max-w-[120px] block">
                                    <?= esc($cmt['story_title'] ?? '-') ?>
                                </a>
                            </td>

                            <td class="px-4 py-3.5 text-xs text-gray-400 whitespace-nowrap">
                                <?= !empty($cmt['created_at']) ? date('d M Y', strtotime($cmt['created_at'])) : '—' ?>
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" title="View Detail" class="action-btn view" onclick="openSO(<?= $cmt['id'] ?>)">
                                        <span class="material-symbols-outlined" style="font-size:18px">open_in_new</span>
                                    </button>
                                    <form action="<?= base_url('/admin/comments/delete/' . $cmt['id']) ?>" method="POST" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="button" title="Delete" class="action-btn delete" onclick="openModal(this.closest('form'), 'delete')">
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
                            <span class="material-symbols-outlined text-5xl text-gray-200 block mb-2">chat_bubble</span>
                            <p class="text-gray-400 text-sm">No comments found</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="px-6 py-3 bg-gray-50 text-xs text-gray-400 border-t border-gray-100 flex items-center justify-between">
        <span>Showing <span class="font-semibold text-gray-600" id="visibleCount"><?= count($comments) ?></span> of <?= count($comments) ?> comments</span>
    </div>
</div>

<script>
function applyFilters() {
    const q     = document.getElementById('searchInput').value.toLowerCase();
    const story = document.getElementById('filterStory').value;
    let visible = 0;
    document.querySelectorAll('#commentsTable tbody tr').forEach(row => {
        const show = (!q || row.textContent.toLowerCase().includes(q))
            && (!story || (row.getAttribute('data-story') ?? '') === story);
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    document.getElementById('visibleCount').textContent = visible;
}
['searchInput','filterStory'].forEach(id => {
    document.getElementById(id).addEventListener(id === 'searchInput' ? 'input' : 'change', applyFilters);
});
</script>

<?= $this->endSection() ?>
