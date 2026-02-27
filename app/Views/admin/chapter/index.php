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
        success: {bg:'bg-green-50',border:'border-green-200',text:'text-green-700',icon:'check_circle',iconColor:'text-green-500'},
        error:   {bg:'bg-red-50',border:'border-red-200',text:'text-red-700',icon:'error',iconColor:'text-red-500'},
        warning: {bg:'bg-amber-50',border:'border-amber-200',text:'text-amber-700',icon:'warning',iconColor:'text-amber-500'},
        info:    {bg:'bg-indigo-50',border:'border-indigo-200',text:'text-indigo-700',icon:'info',iconColor:'text-indigo-500'},
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
    #chapter-slideover { width: min(480px, 100vw); }
    .so-tab-btn.active { background:#eef2ff; color:#4f46e5; font-weight:600; }
    .so-tab-btn { transition: all .2s; }
    .so-tab-panel { display:none; }
    .so-tab-panel.active { display:block; }
    .status-option { transition: all .15s ease; }
    .status-option:has(input:checked) { border-color: var(--sc); background: var(--sb); }
    .prem-toggle { position:relative; width:44px; height:24px; }
    .prem-toggle input { opacity:0; width:0; height:0; }
    .prem-slider { position:absolute; cursor:pointer; inset:0; background:#d1d5db; border-radius:999px; transition:.3s; }
    .prem-slider:before { content:''; position:absolute; height:18px; width:18px; left:3px; top:3px; background:white; border-radius:50%; transition:.3s; box-shadow:0 1px 3px rgba(0,0,0,.2); }
    input:checked + .prem-slider { background:#6366f1; }
    input:checked + .prem-slider:before { transform:translateX(20px); }
    @keyframes so-in  { from{transform:translateX(100%)} to{transform:translateX(0)} }
    @keyframes so-out { from{transform:translateX(0)} to{transform:translateX(100%)} }
    #chapter-slideover.entering { animation: so-in .28s cubic-bezier(.32,0,.67,0) forwards; }
    #chapter-slideover.leaving  { animation: so-out .25s cubic-bezier(.32,0,.67,0) forwards; }
    .pulse-bg { background: linear-gradient(90deg,#f3f4f6 25%,#e9eaec 50%,#f3f4f6 75%); background-size:200% 100%; animation: shimmer 1.4s infinite; }
    @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
    .action-btn { display:inline-flex; align-items:center; justify-content:center; width:30px; height:30px; border-radius:8px; transition:all .18s ease; border:none; cursor:pointer; }
    .action-btn.edit    { background:#eef2ff; color:#6366f1; }
    .action-btn.edit:hover    { background:#6366f1; color:#fff; box-shadow:0 4px 12px rgba(99,102,241,.35); }
    .action-btn.publish { background:#dcfce7; color:#16a34a; }
    .action-btn.publish:hover { background:#16a34a; color:#fff; box-shadow:0 4px 12px rgba(22,163,74,.35); }
    .action-btn.archive { background:#fef9c3; color:#d97706; }
    .action-btn.archive:hover { background:#d97706; color:#fff; box-shadow:0 4px 12px rgba(217,119,6,.35); }
    .action-btn.delete  { background:#fee2e2; color:#dc2626; }
    .action-btn.delete:hover  { background:#dc2626; color:#fff; box-shadow:0 4px 12px rgba(220,38,38,.35); }
</style>

<div id="chapter-slideover-backdrop" class="fixed inset-0 z-40 bg-black/25 backdrop-blur-[2px] hidden" onclick="closeSO()"></div>

<div id="chapter-slideover" class="fixed top-0 right-0 h-full z-50 bg-white shadow-2xl flex flex-col translate-x-full">

    <!-- Header -->
    <div class="flex-shrink-0 bg-white border-b border-gray-100">
        <div class="flex items-center justify-between px-5 py-3.5">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-white text-sm" style="font-variation-settings:'FILL' 1">article</span>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800 leading-none">Chapter Editor</p>
                    <p class="text-[10px] text-gray-400 mt-0.5" id="so-header-sub">Loading...</p>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <a id="so-story-link" href="#" target="_blank" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-blue-600 transition" title="View story">
                    <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                </a>
                <button onclick="closeSO()" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        </div>
        <div class="flex px-5 gap-1 pb-2" id="so-tabs" style="display:none">
            <button class="so-tab-btn active px-3 py-1.5 rounded-lg text-xs text-gray-500" onclick="switchTab('details')">
                <span class="material-symbols-outlined text-sm align-middle mr-1" style="vertical-align:-3px">info</span>Details
            </button>
            <button class="so-tab-btn px-3 py-1.5 rounded-lg text-xs text-gray-500" onclick="switchTab('edit')">
                <span class="material-symbols-outlined text-sm align-middle mr-1" style="vertical-align:-3px">tune</span>Edit
            </button>
        </div>
    </div>

    <!-- Loading skeleton -->
    <div id="so-loading" class="flex-1 overflow-y-auto p-5 space-y-4">
        <div class="pulse-bg h-5 rounded-lg w-3/4"></div>
        <div class="pulse-bg h-3 rounded-lg w-1/2"></div>
        <div class="grid grid-cols-3 gap-3 mt-3">
            <div class="pulse-bg h-16 rounded-xl"></div>
            <div class="pulse-bg h-16 rounded-xl"></div>
            <div class="pulse-bg h-16 rounded-xl"></div>
        </div>
        <div class="pulse-bg h-px w-full mt-4"></div>
        <div class="pulse-bg h-24 rounded-xl w-full"></div>
        <div class="pulse-bg h-3 w-24 rounded-lg"></div>
        <div class="grid grid-cols-2 gap-3">
            <div class="pulse-bg h-14 rounded-xl"></div>
            <div class="pulse-bg h-14 rounded-xl"></div>
        </div>
    </div>

    <!-- Main content -->
    <div id="so-content" class="flex-1 overflow-hidden hidden flex-col">

        <!-- TAB: Details -->
        <div id="so-tab-details" class="so-tab-panel active flex-1 overflow-y-auto px-5 py-4 space-y-4">

            <div>
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Chapter</p>
                        <h3 id="so-title" class="font-bold text-gray-900 text-sm leading-snug"></h3>
                        <p id="so-story-name" class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-gray-400" style="font-size:13px">menu_book</span>
                            <span></span>
                        </p>
                    </div>
                    <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                        <span id="so-status-badge" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold"></span>
                        <span id="so-prem-badge" class="hidden px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-600">⭐ Premium</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <span class="material-symbols-outlined text-blue-500 text-lg block mb-0.5" style="font-variation-settings:'FILL' 1">format_list_numbered</span>
                    <p class="text-lg font-bold text-gray-800 leading-none" id="so-chapter-num">-</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Chapter #</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <span class="material-symbols-outlined text-green-500 text-lg block mb-0.5" style="font-variation-settings:'FILL' 1">text_fields</span>
                    <p class="text-lg font-bold text-gray-800 leading-none" id="so-word-count">-</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Words</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <span class="material-symbols-outlined text-amber-500 text-lg block mb-0.5" style="font-variation-settings:'FILL' 1">calendar_today</span>
                    <p class="text-xs font-semibold text-gray-700 leading-none mt-1" id="so-created">-</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Created</p>
                </div>
            </div>

            <div id="so-preview-wrap" class="hidden bg-gray-50 rounded-xl px-4 py-3">
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Content Preview</p>
                <p id="so-preview" class="text-xs text-gray-600 leading-relaxed"></p>
                <p class="text-[10px] text-gray-400 mt-2 italic">...showing first 300 characters</p>
            </div>

            <div id="so-author-card" class="hidden border border-gray-100 rounded-xl p-3 flex items-center gap-3">
                <div id="so-author-avatar" class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                    <span class="material-symbols-outlined text-indigo-400 text-lg">person</span>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-gray-800" id="so-author-name"></p>
                    <p class="text-[10px] text-gray-400 truncate" id="so-author-email"></p>
                </div>
                <div class="ml-auto flex-shrink-0">
                    <span class="text-[10px] text-gray-400 bg-gray-50 px-2 py-0.5 rounded-full">Author</span>
                </div>
            </div>

        </div>

        <!-- TAB: Edit -->
        <div id="so-tab-edit" class="so-tab-panel flex-1 flex flex-col overflow-hidden">
            <form id="so-form" method="POST" action="" class="flex-1 flex flex-col overflow-hidden">
                <?= csrf_field() ?>

                <div class="flex-1 overflow-y-auto px-5 py-4 space-y-5">

                    <!-- Status picker -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Chapter Status</label>
                            <span id="so-status-hint" class="text-[10px] px-2 py-0.5 rounded-full font-medium bg-gray-100 text-gray-500"></span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="status-option cursor-pointer border-2 border-gray-150 rounded-xl p-3 flex flex-col items-center gap-1.5 hover:border-gray-200" style="--sc:#d1d5db; --sb:#f9fafb;">
                                <input type="radio" name="status" value="DRAFT" class="hidden so-status-radio">
                                <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-500 text-base" style="font-variation-settings:'FILL' 1">draft</span>
                                </div>
                                <p class="text-xs font-semibold text-gray-700">Draft</p>
                            </label>
                            <label class="status-option cursor-pointer border-2 border-gray-150 rounded-xl p-3 flex flex-col items-center gap-1.5 hover:border-green-200" style="--sc:#6ee7b7; --sb:#ecfdf5;">
                                <input type="radio" name="status" value="PUBLISHED" class="hidden so-status-radio">
                                <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-green-600 text-base" style="font-variation-settings:'FILL' 1">check_circle</span>
                                </div>
                                <p class="text-xs font-semibold text-gray-700">Published</p>
                            </label>
                            <label class="status-option cursor-pointer border-2 border-gray-150 rounded-xl p-3 flex flex-col items-center gap-1.5 hover:border-red-200" style="--sc:#fca5a5; --sb:#fff5f5;">
                                <input type="radio" name="status" value="ARCHIVED" class="hidden so-status-radio">
                                <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-red-500 text-base" style="font-variation-settings:'FILL' 1">archive</span>
                                </div>
                                <p class="text-xs font-semibold text-gray-700">Archived</p>
                            </label>
                        </div>
                    </div>

                    <div class="h-px bg-gray-100"></div>

                    <!-- Premium toggle -->
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-700">Premium Chapter</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Hanya dapat diakses oleh subscriber premium</p>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span id="so-prem-label" class="text-xs text-gray-400 font-medium">Off</span>
                            <label class="prem-toggle">
                                <input type="checkbox" id="so-prem-toggle" onchange="syncPremium(this)">
                                <span class="prem-slider"></span>
                            </label>
                            <input type="radio" name="is_premium" value="1" id="so-prem-yes" class="hidden">
                            <input type="radio" name="is_premium" value="0" id="so-prem-no" class="hidden" checked>
                        </div>
                    </div>

                    <!-- Change summary -->
                    <div id="so-change-summary" class="hidden bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
                        <p class="text-[10px] font-semibold text-blue-500 uppercase tracking-wider mb-2">Pending Changes</p>
                        <div id="so-changes-list" class="space-y-1"></div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="flex-shrink-0 px-5 py-4 border-t border-gray-100 bg-white flex gap-2.5">
                    <button type="button" onclick="closeSO()" class="px-4 py-2.5 text-sm rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition font-medium flex-1">Cancel</button>
                    <button type="submit" class="px-4 py-2.5 text-sm rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition flex-[2] flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-base" style="font-variation-settings:'FILL' 1">save</span>Save Changes
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Error state -->
    <div id="so-error" class="flex-1 hidden items-center justify-center px-6">
        <div class="text-center">
            <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <span class="material-symbols-outlined text-red-400 text-3xl">wifi_off</span>
            </div>
            <p class="text-sm font-semibold text-gray-700">Failed to load chapter</p>
            <p class="text-xs text-gray-400 mt-1">Check your connection and try again.</p>
            <button onclick="closeSO()" class="mt-4 px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-600 transition">Close</button>
        </div>
    </div>
</div>

<script>
const CH_BASE    = '<?= base_url('/admin/chapters/') ?>';
const UPLOAD_URL = '<?= base_url('uploads/') ?>';
const STORY_URL  = '<?= base_url('/story/') ?>';

const STATUS_CFG = {
    DRAFT:     { label:'Draft',     cls:'bg-gray-100 text-gray-500',   icon:'draft'        },
    PUBLISHED: { label:'Published', cls:'bg-green-100 text-green-700', icon:'check_circle' },
    ARCHIVED:  { label:'Archived',  cls:'bg-red-100 text-red-600',     icon:'archive'      },
};
const STATUS_HINTS = {
    DRAFT:     'Tersimpan, belum tampil ke pembaca.',
    PUBLISHED: 'Live — dapat dibaca pembaca.',
    ARCHIVED:  'Disembunyikan dari semua pembaca.',
};

let _origStatus = '', _origPremium = false;

function openSO(chapterId) {
    const backdrop = document.getElementById('chapter-slideover-backdrop');
    const panel    = document.getElementById('chapter-slideover');
    backdrop.classList.remove('hidden');
    panel.classList.remove('translate-x-full', 'leaving');
    panel.classList.add('entering');
    document.body.style.overflow = 'hidden';

    document.getElementById('so-loading').classList.remove('hidden');
    document.getElementById('so-content').classList.add('hidden');
    document.getElementById('so-content').classList.remove('flex');
    document.getElementById('so-error').classList.add('hidden');
    document.getElementById('so-error').classList.remove('flex');
    document.getElementById('so-tabs').style.display = 'none';
    document.getElementById('so-header-sub').textContent = 'Loading...';
    switchTab('details');

    fetch(`${CH_BASE}detail/${chapterId}`, { headers: {'X-Requested-With':'XMLHttpRequest'} })
        .then(r => { if (!r.ok) throw new Error(); return r.json(); })
        .then(ch => {
            populateSO(ch);
            document.getElementById('so-loading').classList.add('hidden');
            document.getElementById('so-content').classList.remove('hidden');
            document.getElementById('so-content').classList.add('flex');
            document.getElementById('so-tabs').style.display = 'flex';
        })
        .catch(() => {
            document.getElementById('so-loading').classList.add('hidden');
            document.getElementById('so-error').classList.remove('hidden');
            document.getElementById('so-error').classList.add('flex');
        });
}

function closeSO() {
    const panel = document.getElementById('chapter-slideover');
    panel.classList.remove('entering');
    panel.classList.add('leaving');
    setTimeout(() => {
        panel.classList.add('translate-x-full');
        panel.classList.remove('leaving');
        document.getElementById('chapter-slideover-backdrop').classList.add('hidden');
        document.body.style.overflow = '';
    }, 240);
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSO(); });

function switchTab(tab) {
    document.querySelectorAll('.so-tab-btn').forEach((b, i) => {
        b.classList.toggle('active', (i === 0 ? 'details' : 'edit') === tab);
    });
    document.querySelectorAll('.so-tab-panel').forEach(p => {
        p.classList.toggle('active', p.id === `so-tab-${tab}`);
    });
}

function populateSO(ch) {
    document.getElementById('so-header-sub').textContent = `ID #${ch.id}`;
    document.getElementById('so-story-link').href = `${STORY_URL}${ch.story_id}`;
    document.getElementById('so-title').textContent = ch.title || 'Untitled Chapter';
    document.getElementById('so-story-name').querySelector('span').textContent = ch.story_title || '-';
    document.getElementById('so-chapter-num').textContent = ch.chapter_number ?? '-';
    document.getElementById('so-word-count').textContent = ch.word_count ? ch.word_count.toLocaleString() : '-';

    const fmt = d => d ? new Date(d).toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'}) : '-';
    document.getElementById('so-created').textContent = fmt(ch.created_at);

    const cfg = STATUS_CFG[ch.status] || STATUS_CFG.DRAFT;
    const statusBadge = document.getElementById('so-status-badge');
    statusBadge.className = `inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold ${cfg.cls}`;
    statusBadge.innerHTML = `<span class="material-symbols-outlined text-xs" style="font-variation-settings:'FILL' 1;font-size:12px">${cfg.icon}</span>${cfg.label}`;

    const isPremium = parseInt(ch.is_premium) === 1;
    document.getElementById('so-prem-badge').classList.toggle('hidden', !isPremium);

    const previewWrap = document.getElementById('so-preview-wrap');
    if (ch.content_preview) {
        document.getElementById('so-preview').textContent = ch.content_preview;
        previewWrap.classList.remove('hidden');
    } else previewWrap.classList.add('hidden');

    const authorCard = document.getElementById('so-author-card');
    if (ch.author_name) {
        document.getElementById('so-author-name').textContent = ch.author_name;
        document.getElementById('so-author-email').textContent = ch.author_email || 'No email';
        const avatar = document.getElementById('so-author-avatar');
        if (ch.author_photo) {
            avatar.innerHTML = `<img src="${UPLOAD_URL}${ch.author_photo}" class="w-full h-full object-cover rounded-full">`;
        }
        authorCard.classList.remove('hidden');
    }

    _origStatus  = ch.status || 'DRAFT';
    _origPremium = isPremium;
    document.querySelectorAll('.so-status-radio').forEach(r => {
        r.checked = (r.value === _origStatus);
        r.addEventListener('change', () => { updateStatusHint(r.value); trackChanges(); });
    });
    updateStatusHint(_origStatus);

    document.getElementById('so-prem-toggle').checked = isPremium;
    document.getElementById('so-prem-yes').checked = isPremium;
    document.getElementById('so-prem-no').checked  = !isPremium;
    document.getElementById('so-prem-label').textContent = isPremium ? 'On' : 'Off';
    document.getElementById('so-prem-toggle').addEventListener('change', trackChanges);

    document.getElementById('so-form').action = `${CH_BASE}update/${ch.id}`;
    trackChanges();
}

function updateStatusHint(val) {
    const pill = document.getElementById('so-status-hint');
    pill.textContent = STATUS_HINTS[val] || '';
    const m = { DRAFT:'bg-gray-100 text-gray-500', PUBLISHED:'bg-green-100 text-green-700', ARCHIVED:'bg-red-100 text-red-600' };
    pill.className = `text-[10px] px-2 py-0.5 rounded-full font-medium ${m[val] || 'bg-gray-100 text-gray-500'}`;
}

function syncPremium(toggle) {
    document.getElementById('so-prem-yes').checked = toggle.checked;
    document.getElementById('so-prem-no').checked  = !toggle.checked;
    document.getElementById('so-prem-label').textContent = toggle.checked ? 'On' : 'Off';
    trackChanges();
}

function trackChanges() {
    const newStatus  = document.querySelector('.so-status-radio:checked')?.value || _origStatus;
    const newPremium = document.getElementById('so-prem-toggle').checked;
    const changes = [];
    if (newStatus !== _origStatus) {
        changes.push(`Status: <b class="text-gray-700">${STATUS_CFG[_origStatus]?.label || _origStatus}</b> → <b class="text-blue-700">${STATUS_CFG[newStatus]?.label || newStatus}</b>`);
    }
    if (newPremium !== _origPremium) {
        changes.push(`Premium: <b class="text-blue-700">${newPremium ? 'Yes ⭐' : 'No'}</b>`);
    }
    const summary = document.getElementById('so-change-summary');
    const list    = document.getElementById('so-changes-list');
    if (changes.length) {
        list.innerHTML = changes.map(c => `<p class="text-[11px] text-blue-700">${c}</p>`).join('');
        summary.classList.remove('hidden');
    } else {
        summary.classList.add('hidden');
    }
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
    publish: {
        title:'Publish Chapter', body:'Chapter ini akan dipublikasikan dan dapat dibaca oleh semua pembaca.',
        icon:'check_circle', iconBg:'bg-green-100', iconColor:'text-green-600',
        btnBg:'bg-green-600 hover:bg-green-700', btnLabel:'Yes, Publish',
        toast:['Chapter dipublikasikan.', 'success'],
    },
    archive: {
        title:'Archive Chapter', body:'Chapter ini akan disembunyikan dari pembaca. Bisa dipulihkan nanti.',
        icon:'archive', iconBg:'bg-amber-100', iconColor:'text-amber-600',
        btnBg:'bg-amber-500 hover:bg-amber-600', btnLabel:'Yes, Archive',
        toast:['Chapter diarsipkan.', 'warning'],
    },
    delete: {
        title:'Delete Chapter Permanently', body:'Chapter ini akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.',
        icon:'delete_forever', iconBg:'bg-red-100', iconColor:'text-red-600',
        btnBg:'bg-red-600 hover:bg-red-700', btnLabel:'Yes, Delete',
        toast:['Chapter dihapus.', 'error'],
    },
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
    const modal = document.getElementById('confirm-modal');
    modal.classList.remove('hidden'); modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('confirm-modal');
    modal.classList.add('hidden'); modal.classList.remove('flex');
    _pendingForm = null;
}
document.getElementById('confirm-modal').addEventListener('click', function(e) { if (e.target === this) closeModal(); });
</script>

<!-- ═══════════════════ STATS CARDS ═══════════════════ -->
<?php
    $chapters      = $chapters ?? [];
    $statTotal     = count($chapters);
    $statPublished = count(array_filter($chapters, fn($c) => ($c['status'] ?? '') === 'PUBLISHED'));
    $statDraft     = count(array_filter($chapters, fn($c) => ($c['status'] ?? '') === 'DRAFT'));
    $statArchived  = count(array_filter($chapters, fn($c) => ($c['status'] ?? '') === 'ARCHIVED'));
    $statPremium   = count(array_filter($chapters, fn($c) => !empty($c['is_premium'])));
?>
<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-blue-600 text-xl" style="font-variation-settings:'FILL' 1">article</span>
        </div>
        <div>
            <p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Total</p>
            <p class="text-xl font-bold text-gray-800"><?= number_format($statTotal) ?></p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-green-600 text-xl" style="font-variation-settings:'FILL' 1">check_circle</span>
        </div>
        <div>
            <p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Published</p>
            <p class="text-xl font-bold text-gray-800"><?= number_format($statPublished) ?></p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-gray-500 text-xl" style="font-variation-settings:'FILL' 1">draft</span>
        </div>
        <div>
            <p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Draft</p>
            <p class="text-xl font-bold text-gray-800"><?= number_format($statDraft) ?></p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-red-500 text-xl" style="font-variation-settings:'FILL' 1">archive</span>
        </div>
        <div>
            <p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Archived</p>
            <p class="text-xl font-bold text-gray-800"><?= number_format($statArchived) ?></p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-yellow-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-yellow-600 text-xl" style="font-variation-settings:'FILL' 1">star</span>
        </div>
        <div>
            <p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Premium</p>
            <p class="text-xl font-bold text-gray-800"><?= number_format($statPremium) ?></p>
        </div>
    </div>
</div>

<!-- ═══════════════════ TABLE ═══════════════════ -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-500 text-xl" style="font-variation-settings:'FILL' 1">article</span>
            Chapters
        </h3>
        <div class="flex items-center gap-2 flex-wrap">
            <select id="filterStatus" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-gray-50 text-gray-600">
                <option value="">All Status</option>
                <option value="PUBLISHED">Published</option>
                <option value="DRAFT">Draft</option>
                <option value="ARCHIVED">Archived</option>
            </select>
            <select id="filterPremium" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-gray-50 text-gray-600">
                <option value="">All Chapters</option>
                <option value="1">Premium</option>
                <option value="0">Free</option>
            </select>
            <select id="filterAuthor" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-gray-50 text-gray-600">
                <option value="">All Authors</option>
                <?php
                    $authors = array_unique(array_filter(array_column($chapters, 'author_name')));
                    sort($authors);
                    foreach ($authors as $author): ?>
                    <option value="<?= esc($author) ?>"><?= esc($author) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base">search</span>
                <input type="text" id="searchInput" placeholder="Search chapters..." class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300 w-52">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="chaptersTable">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-medium w-10 text-gray-400">#</th>
                    <th class="px-4 py-3 text-left font-medium">Chapter</th>
                    <th class="px-4 py-3 text-left font-medium">Story</th>
                    <th class="px-4 py-3 text-left font-medium">Author</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                    <th class="px-4 py-3 text-left font-medium">Type</th>
                    <th class="px-4 py-3 text-left font-medium">Date</th>
                    <th class="px-4 py-3 text-center font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (!empty($chapters)): ?>
                    <?php foreach ($chapters as $i => $ch): ?>
                        <?php
                            $status     = $ch['status'] ?? 'DRAFT';
                            $isPremium  = !empty($ch['is_premium']) ? 1 : 0;
                            $badgeMap   = [
                                'PUBLISHED' => 'bg-green-100 text-green-700',
                                'DRAFT'     => 'bg-gray-100 text-gray-500',
                                'ARCHIVED'  => 'bg-red-100 text-red-600',
                            ];
                            $badge      = $badgeMap[$status] ?? 'bg-gray-100 text-gray-500';
                            $statusLabel = ucfirst(strtolower($status));
                        ?>
                        <tr class="hover:bg-gray-50/70 transition-colors"
                            data-status="<?= esc($status) ?>"
                            data-premium="<?= $isPremium ?>"
                            data-author="<?= esc(strtolower($ch['author_name'] ?? '')) ?>">

                            <td class="px-4 py-3.5 text-gray-300 text-xs font-medium"><?= $i + 1 ?></td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <span class="text-xs font-bold text-blue-500"><?= $ch['chapter_number'] ?? '?' ?></span>
                                    </div>
                                    <p class="font-medium text-gray-800 truncate max-w-[180px]" title="<?= esc($ch['title']) ?>"><?= esc($ch['title']) ?></p>
                                </div>
                            </td>

                            <td class="px-4 py-3.5">
                                <a href="<?= base_url('/story/' . ($ch['story_id'] ?? '')) ?>" target="_blank"
                                   class="text-sm text-gray-600 hover:text-blue-600 transition truncate max-w-[150px] block" title="<?= esc($ch['story_title'] ?? '') ?>">
                                    <?= esc($ch['story_title'] ?? '-') ?>
                                </a>
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <?php if (!empty($ch['author_photo'])): ?>
                                        <img src="<?= base_url('uploads/' . $ch['author_photo']) ?>" alt="" class="w-6 h-6 rounded-full object-cover border border-gray-100 flex-shrink-0">
                                    <?php else: ?>
                                        <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                            <span class="material-symbols-outlined text-indigo-400" style="font-size:14px">person</span>
                                        </div>
                                    <?php endif; ?>
                                    <span class="text-sm text-gray-600 truncate max-w-[110px]"><?= esc($ch['author_name'] ?? '-') ?></span>
                                </div>
                            </td>

                            <td class="px-4 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold <?= $badge ?>">
                                    <?= esc($statusLabel) ?>
                                </span>
                            </td>

                            <td class="px-4 py-3.5">
                                <?php if ($isPremium): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-yellow-50 text-yellow-700">
                                        <span class="material-symbols-outlined" style="font-size:12px;font-variation-settings:'FILL' 1">star</span>Premium
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-50 text-gray-500">Free</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-4 py-3.5 text-xs text-gray-400 whitespace-nowrap">
                                <?= !empty($ch['created_at']) ? date('d M Y', strtotime($ch['created_at'])) : '—' ?>
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" title="Edit" class="action-btn edit" onclick="openSO(<?= $ch['id'] ?>)">
                                        <span class="material-symbols-outlined" style="font-size:18px">open_in_new</span>
                                    </button>

                                    <?php if ($status !== 'PUBLISHED'): ?>
                                        <form action="<?= base_url('/admin/chapters/publish/' . $ch['id']) ?>" method="POST" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="button" title="Publish" class="action-btn publish" onclick="openModal(this.closest('form'), 'publish')">
                                                <span class="material-symbols-outlined" style="font-size:16px">check_circle</span>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($status !== 'ARCHIVED'): ?>
                                        <form action="<?= base_url('/admin/chapters/archive/' . $ch['id']) ?>" method="POST" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="button" title="Archive" class="action-btn archive" onclick="openModal(this.closest('form'), 'archive')">
                                                <span class="material-symbols-outlined" style="font-size:16px">archive</span>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <form action="<?= base_url('/admin/chapters/delete/' . $ch['id']) ?>" method="POST" class="inline">
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
                        <td colspan="8" class="px-5 py-16 text-center">
                            <span class="material-symbols-outlined text-5xl text-gray-200 block mb-2">article</span>
                            <p class="text-gray-400 text-sm">No chapters found</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="px-6 py-3 bg-gray-50 text-xs text-gray-400 border-t border-gray-100 flex items-center justify-between">
        <span>Showing <span class="font-semibold text-gray-600" id="visibleCount"><?= count($chapters) ?></span> of <?= count($chapters) ?> chapters</span>
    </div>
</div>

<script>
function applyFilters() {
    const q       = document.getElementById('searchInput').value.toLowerCase();
    const status  = document.getElementById('filterStatus').value;
    const premium = document.getElementById('filterPremium').value;
    const author  = document.getElementById('filterAuthor').value;
    let visible   = 0;

    document.querySelectorAll('#chaptersTable tbody tr').forEach(row => {
        const text      = row.textContent.toLowerCase();
        const rowStatus = row.getAttribute('data-status') ?? '';
        const rowPrem   = row.getAttribute('data-premium') ?? '';
        const rowAuthor = row.getAttribute('data-author') ?? '';

        const show = (!q || text.includes(q))
            && (!status  || rowStatus === status)
            && (!premium || rowPrem === premium)
            && (!author  || rowAuthor === author.toLowerCase());

        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    document.getElementById('visibleCount').textContent = visible;
}

['searchInput','filterStatus','filterPremium','filterAuthor'].forEach(id => {
    const el = document.getElementById(id);
    el.addEventListener(id === 'searchInput' ? 'input' : 'change', applyFilters);
});
</script>

<?= $this->endSection() ?>
