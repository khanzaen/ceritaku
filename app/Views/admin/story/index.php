<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<!-- ═══════════════════ TOAST CONTAINER ═══════════════════ -->
<div id="toast-container" class="fixed top-6 right-6 z-50 flex flex-col gap-2 pointer-events-none"></div>

<style>
@keyframes toast-in {
    0%   { opacity: 0; transform: translateX(110%); }
    100% { opacity: 1; transform: translateX(0); }
}
@keyframes toast-out {
    0%   { opacity: 1; transform: translateX(0); }
    100% { opacity: 0; transform: translateX(110%); }
}
.toast {
    pointer-events: all;
    animation: toast-in 0.35s cubic-bezier(.4,0,.2,1) forwards;
}
.toast.hiding {
    animation: toast-out 0.3s cubic-bezier(.4,0,.2,1) forwards;
}
</style>

<script>
function showToast(message, type = 'success') {
    const cfg = {
        success: { bg: 'bg-green-50',  border: 'border-green-200',  text: 'text-green-700',  icon: 'check_circle',  iconColor: 'text-green-500'  },
        error:   { bg: 'bg-red-50',    border: 'border-red-200',    text: 'text-red-700',    icon: 'error',         iconColor: 'text-red-500'    },
        warning: { bg: 'bg-amber-50',  border: 'border-amber-200',  text: 'text-amber-700',  icon: 'warning',       iconColor: 'text-amber-500'  },
        info:    { bg: 'bg-indigo-50', border: 'border-indigo-200', text: 'text-indigo-700', icon: 'info',          iconColor: 'text-indigo-500' },
    };
    const c = cfg[type] ?? cfg.success;
    const toast = document.createElement('div');
    toast.className = `toast ${c.bg} ${c.border} border rounded-xl shadow-lg px-4 py-3 flex items-center gap-3 min-w-[260px] max-w-xs`;
    toast.innerHTML = `
        <span class="material-symbols-outlined ${c.iconColor} text-xl flex-shrink-0" style="font-variation-settings:'FILL' 1">${c.icon}</span>
        <p class="${c.text} text-sm font-medium flex-1 leading-snug">${message}</p>
        <button onclick="dismissToast(this.parentElement)" class="${c.text} opacity-40 hover:opacity-80 transition text-lg leading-none flex-shrink-0">&times;</button>
    `;
    document.getElementById('toast-container').appendChild(toast);
    setTimeout(() => dismissToast(toast), 3500);
}

function dismissToast(el) {
    if (!el || el.classList.contains('hiding')) return;
    el.classList.add('hiding');
    setTimeout(() => el.remove(), 300);
}

<?php if (session()->getFlashdata('success')): ?>
window.addEventListener('DOMContentLoaded', () => {
    showToast(<?= json_encode(session()->getFlashdata('success')) ?>, 'success');
});
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
window.addEventListener('DOMContentLoaded', () => {
    showToast(<?= json_encode(session()->getFlashdata('error')) ?>, 'error');
});
<?php endif; ?>
<?php if (session()->getFlashdata('warning')): ?>
window.addEventListener('DOMContentLoaded', () => {
    showToast(<?= json_encode(session()->getFlashdata('warning')) ?>, 'warning');
});
<?php endif; ?>
</script>

<!-- ═══════════════════ SLIDE-OVER UPDATE PANEL ═══════════════════ -->
<style>
    #story-slideover { width: min(480px, 100vw); }
    .so-tab-btn.active { background:#eef2ff; color:#4f46e5; font-weight:600; }
    .so-tab-btn { transition: all .2s; }
    .so-tab-panel { display:none; }
    .so-tab-panel.active { display:block; }
    .status-option { transition: all .15s ease; }
    .status-option:has(input:checked) { border-color: var(--sc); background: var(--sb); }
    .feat-toggle { position:relative; width:44px; height:24px; }
    .feat-toggle input { opacity:0; width:0; height:0; }
    .feat-slider { position:absolute; cursor:pointer; inset:0; background:#d1d5db; border-radius:999px; transition:.3s; }
    .feat-slider:before { content:''; position:absolute; height:18px; width:18px; left:3px; top:3px; background:white; border-radius:50%; transition:.3s; box-shadow:0 1px 3px rgba(0,0,0,.2); }
    input:checked + .feat-slider { background:#6366f1; }
    input:checked + .feat-slider:before { transform:translateX(20px); }
    @keyframes so-in { from { transform:translateX(100%); } to { transform:translateX(0); } }
    @keyframes so-out { from { transform:translateX(0); } to { transform:translateX(100%); } }
    #story-slideover.entering { animation: so-in .28s cubic-bezier(.32,0,.67,0) forwards; }
    #story-slideover.leaving  { animation: so-out .25s cubic-bezier(.32,0,.67,0) forwards; }
    .pulse-bg { background: linear-gradient(90deg, #f3f4f6 25%, #e9eaec 50%, #f3f4f6 75%); background-size:200% 100%; animation: shimmer 1.4s infinite; }
    @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

    /* ── Colored Action Buttons ── */
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 8px;
        transition: all .18s ease;
        border: none;
        cursor: pointer;
    }
    /* Edit — Indigo */
    .action-btn.edit {
        background: #eef2ff;
        color: #6366f1;
    }
    .action-btn.edit:hover {
        background: #6366f1;
        color: #fff;
        box-shadow: 0 4px 12px rgba(99,102,241,.35);
    }
    /* Approve — Green */
    .action-btn.approve {
        background: #dcfce7;
        color: #16a34a;
    }
    .action-btn.approve:hover {
        background: #16a34a;
        color: #fff;
        box-shadow: 0 4px 12px rgba(22,163,74,.35);
    }
    /* Archive — Amber */
    .action-btn.archive {
        background: #fef9c3;
        color: #d97706;
    }
    .action-btn.archive:hover {
        background: #d97706;
        color: #fff;
        box-shadow: 0 4px 12px rgba(217,119,6,.35);
    }
    /* Delete — Red */
    .action-btn.delete {
        background: #fee2e2;
        color: #dc2626;
    }
    .action-btn.delete:hover {
        background: #dc2626;
        color: #fff;
        box-shadow: 0 4px 12px rgba(220,38,38,.35);
    }
</style>

<div id="story-slideover-backdrop"
     class="fixed inset-0 z-40 bg-black/25 backdrop-blur-[2px] hidden"
     onclick="closeSlideOver()"></div>

<div id="story-slideover"
     class="fixed top-0 right-0 h-full z-50 bg-white shadow-2xl flex flex-col translate-x-full">

    <!-- ── Header ── -->
    <div class="flex-shrink-0 bg-white border-b border-gray-100">
        <div class="flex items-center justify-between px-5 py-3.5">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-white text-sm" style="font-variation-settings:'FILL' 1">edit_note</span>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800 leading-none">Story Editor</p>
                    <p class="text-[10px] text-gray-400 mt-0.5" id="so-header-sub">Loading...</p>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <a id="so-view-link" href="#" target="_blank"
                   class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-indigo-600 transition" title="View story page">
                    <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                </a>
                <button onclick="closeSlideOver()"
                        class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        </div>
        <!-- Tabs -->
        <div class="flex px-5 gap-1 pb-2" id="so-tabs" style="display:none">
            <button class="so-tab-btn active px-3 py-1.5 rounded-lg text-xs text-gray-500" onclick="switchTab('details')">
                <span class="material-symbols-outlined text-sm align-middle mr-1" style="vertical-align:-3px">info</span>Details
            </button>
            <button class="so-tab-btn px-3 py-1.5 rounded-lg text-xs text-gray-500" onclick="switchTab('edit')">
                <span class="material-symbols-outlined text-sm align-middle mr-1" style="vertical-align:-3px">tune</span>Edit
            </button>
        </div>
    </div>

    <!-- ── Loading skeleton ── -->
    <div id="slideover-loading" class="flex-1 overflow-y-auto p-5 space-y-4">
        <div class="flex gap-4">
            <div class="pulse-bg w-[88px] h-[124px] rounded-2xl flex-shrink-0"></div>
            <div class="flex-1 space-y-2.5 pt-1">
                <div class="pulse-bg h-4 rounded-lg w-4/5"></div>
                <div class="pulse-bg h-3 rounded-lg w-1/2"></div>
                <div class="pulse-bg h-3 rounded-lg w-2/3"></div>
                <div class="flex gap-2 mt-3">
                    <div class="pulse-bg h-6 w-16 rounded-full"></div>
                    <div class="pulse-bg h-6 w-20 rounded-full"></div>
                </div>
            </div>
        </div>
        <div class="pulse-bg h-14 rounded-xl w-full"></div>
        <div class="grid grid-cols-3 gap-3">
            <div class="pulse-bg h-16 rounded-xl"></div>
            <div class="pulse-bg h-16 rounded-xl"></div>
            <div class="pulse-bg h-16 rounded-xl"></div>
        </div>
        <div class="pulse-bg h-px w-full"></div>
        <div class="pulse-bg h-3 w-24 rounded-lg"></div>
        <div class="pulse-bg h-12 rounded-xl w-full"></div>
        <div class="pulse-bg h-3 w-28 rounded-lg"></div>
        <div class="grid grid-cols-2 gap-3">
            <div class="pulse-bg h-14 rounded-xl"></div>
            <div class="pulse-bg h-14 rounded-xl"></div>
        </div>
    </div>

    <!-- ── Main content ── -->
    <div id="slideover-content" class="flex-1 overflow-hidden hidden flex-col">

        <!-- TAB: Details -->
        <div id="so-tab-details" class="so-tab-panel active flex-1 overflow-y-auto">
            <div class="relative">
                <div id="so-banner" class="h-24 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500"></div>
                <div class="px-5 pb-0">
                    <div class="flex gap-4 -mt-10">
                        <div class="flex-shrink-0 relative">
                            <img id="so-cover" src="" alt="Cover"
                                 class="w-[72px] h-[100px] object-cover rounded-2xl shadow-lg border-2 border-white hidden">
                            <div id="so-cover-placeholder"
                                 class="w-[72px] h-[100px] bg-white rounded-2xl shadow-lg border-2 border-white flex items-center justify-center">
                                <span class="material-symbols-outlined text-purple-300 text-3xl">menu_book</span>
                            </div>
                            <div id="so-feat-badge"
                                 class="hidden absolute -top-1.5 -right-1.5 w-5 h-5 bg-yellow-400 rounded-full flex items-center justify-center shadow">
                                <span class="material-symbols-outlined text-white text-xs" style="font-variation-settings:'FILL' 1;font-size:12px">star</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0 pt-11">
                            <h3 id="so-title" class="font-bold text-gray-900 text-sm leading-snug line-clamp-2"></h3>
                            <p id="so-author" class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs text-gray-400" style="font-size:13px">person</span>
                                <span id="so-author-name"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-5 pt-3 flex flex-wrap gap-1.5" id="so-badges-row">
                <span id="so-status-badge" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold"></span>
                <span id="so-genre-badge" class="hidden px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-600"></span>
                <span id="so-pub-status-badge" class="hidden px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-50 text-sky-600"></span>
                <span id="so-featured-badge-row" class="hidden px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-600">⭐ Featured</span>
            </div>

            <div id="so-desc-wrap" class="hidden mx-5 mt-3 bg-gray-50 rounded-xl px-4 py-3">
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Synopsis</p>
                <p id="so-desc" class="text-xs text-gray-600 leading-relaxed"></p>
            </div>

            <div class="grid grid-cols-3 gap-3 px-5 mt-4">
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <span class="material-symbols-outlined text-blue-500 text-lg block mb-0.5" style="font-variation-settings:'FILL' 1">article</span>
                    <p class="text-lg font-bold text-gray-800 leading-none" id="so-stat-chapters">0</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Chapters</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <span class="material-symbols-outlined text-green-500 text-lg block mb-0.5" style="font-variation-settings:'FILL' 1">calendar_today</span>
                    <p class="text-xs font-semibold text-gray-700 leading-none mt-1" id="so-stat-created">-</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Created</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <span class="material-symbols-outlined text-amber-500 text-lg block mb-0.5" style="font-variation-settings:'FILL' 1">update</span>
                    <p class="text-xs font-semibold text-gray-700 leading-none mt-1" id="so-stat-updated">-</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Updated</p>
                </div>
            </div>

            <div id="so-author-card" class="hidden mx-5 mt-4 border border-gray-100 rounded-xl p-3 flex items-center gap-3">
                <div id="so-author-avatar"
                     class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                    <span class="material-symbols-outlined text-indigo-400 text-lg">person</span>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-gray-800" id="so-author-name-card"></p>
                    <p class="text-[10px] text-gray-400 truncate" id="so-author-email-card"></p>
                </div>
                <div class="ml-auto flex-shrink-0">
                    <span class="text-[10px] text-gray-400 bg-gray-50 px-2 py-0.5 rounded-full">Author</span>
                </div>
            </div>

            <div class="pb-5"></div>
        </div>

        <!-- TAB: Edit -->
        <div id="so-tab-edit" class="so-tab-panel flex-1 flex flex-col overflow-hidden">
            <form id="so-form" method="POST" action="" class="flex-1 flex flex-col overflow-hidden">
                <?= csrf_field() ?>

                <div class="flex-1 overflow-y-auto px-5 py-4 space-y-5">

                    <!-- Status picker -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Story Status</label>
                            <span id="so-status-hint-pill"
                                  class="text-[10px] px-2 py-0.5 rounded-full font-medium bg-gray-100 text-gray-500"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="status-option cursor-pointer border-2 border-gray-150 rounded-xl p-3 flex items-center gap-2.5 hover:border-amber-200"
                                   style="--sc:#fcd34d; --sb:#fffbeb;">
                                <input type="radio" name="status" value="PENDING_REVIEW" class="hidden so-status-radio">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-amber-500 text-base" style="font-variation-settings:'FILL' 1">pending</span>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-700">Pending</p>
                                    <p class="text-[10px] text-gray-400">In review</p>
                                </div>
                            </label>
                            <label class="status-option cursor-pointer border-2 border-gray-150 rounded-xl p-3 flex items-center gap-2.5 hover:border-green-200"
                                   style="--sc:#6ee7b7; --sb:#ecfdf5;">
                                <input type="radio" name="status" value="PUBLISHED" class="hidden so-status-radio">
                                <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-green-600 text-base" style="font-variation-settings:'FILL' 1">check_circle</span>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-700">Published</p>
                                    <p class="text-[10px] text-gray-400">Live & visible</p>
                                </div>
                            </label>
                            <label class="status-option cursor-pointer border-2 border-gray-150 rounded-xl p-3 flex items-center gap-2.5 hover:border-red-200"
                                   style="--sc:#fca5a5; --sb:#fff5f5;">
                                <input type="radio" name="status" value="ARCHIVED" class="hidden so-status-radio">
                                <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-red-500 text-base" style="font-variation-settings:'FILL' 1">archive</span>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-700">Archived</p>
                                    <p class="text-[10px] text-gray-400">Hidden away</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="h-px bg-gray-100"></div>

                    <!-- Featured toggle -->
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-700">Featured Story</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Display on the homepage featured section</p>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span id="so-feat-label" class="text-xs text-gray-400 font-medium">Off</span>
                            <label class="feat-toggle">
                                <input type="checkbox" id="so-feat-toggle" onchange="syncFeatured(this)">
                                <span class="feat-slider"></span>
                            </label>
                            <input type="radio" name="is_featured" value="1" id="so-feat-yes" class="hidden">
                            <input type="radio" name="is_featured" value="0" id="so-feat-no"  class="hidden" checked>
                        </div>
                    </div>

                    <!-- Change summary -->
                    <div id="so-change-summary" class="hidden bg-indigo-50 border border-indigo-100 rounded-xl px-4 py-3">
                        <p class="text-[10px] font-semibold text-indigo-500 uppercase tracking-wider mb-2">Pending Changes</p>
                        <div id="so-changes-list" class="space-y-1"></div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="flex-shrink-0 px-5 py-4 border-t border-gray-100 bg-white flex gap-2.5">
                    <button type="button" onclick="closeSlideOver()"
                            class="px-4 py-2.5 text-sm rounded-xl border border-gray-200 text-gray-600
                                   hover:bg-gray-50 transition font-medium flex-1">
                        Cancel
                    </button>
                    <button type="submit" id="so-save-btn"
                            class="px-4 py-2.5 text-sm rounded-xl bg-indigo-600 hover:bg-indigo-700
                                   text-white font-semibold transition flex-[2] flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-base" style="font-variation-settings:'FILL' 1">save</span>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- ── Error state ── -->
    <div id="slideover-error" class="flex-1 hidden items-center justify-center px-6">
        <div class="text-center">
            <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <span class="material-symbols-outlined text-red-400 text-3xl">wifi_off</span>
            </div>
            <p class="text-sm font-semibold text-gray-700">Failed to load story</p>
            <p class="text-xs text-gray-400 mt-1">Check your connection and try again.</p>
            <button onclick="closeSlideOver()"
                    class="mt-4 px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-600 transition">
                Close
            </button>
        </div>
    </div>
</div>

<script>
const SO_BASE    = '<?= base_url('/admin/stories/') ?>';
const UPLOAD_URL = '<?= base_url('uploads/') ?>';
const STORY_URL  = '<?= base_url('/story/') ?>';

const STATUS_CFG = {
    DRAFT:          { label:'Draft',          cls:'bg-gray-100 text-gray-500',    icon:'draft'        },
    PENDING_REVIEW: { label:'Pending Review', cls:'bg-amber-100 text-amber-700',  icon:'pending'      },
    PUBLISHED:      { label:'Published',      cls:'bg-green-100 text-green-700',  icon:'check_circle' },
    ARCHIVED:       { label:'Archived',       cls:'bg-red-100 text-red-600',      icon:'archive'      },
};
const STATUS_HINTS = {
    DRAFT:          'Saved, not visible to readers.',
    PENDING_REVIEW: 'Waiting for admin approval.',
    PUBLISHED:      'Live — visible to all readers.',
    ARCHIVED:       'Hidden from all readers.',
};

let _origStatus = '', _origFeatured = false, _origPubStatus = '';

function openSlideOver(storyId) {
    const backdrop = document.getElementById('story-slideover-backdrop');
    const panel    = document.getElementById('story-slideover');
    const loading  = document.getElementById('slideover-loading');
    const content  = document.getElementById('slideover-content');
    const errDiv   = document.getElementById('slideover-error');

    backdrop.classList.remove('hidden');
    panel.classList.remove('translate-x-full');
    panel.classList.remove('leaving');
    panel.classList.add('entering');
    document.body.style.overflow = 'hidden';

    loading.classList.remove('hidden');
    content.classList.add('hidden'); content.classList.remove('flex');
    errDiv.classList.add('hidden');  errDiv.classList.remove('flex');
    document.getElementById('so-tabs').style.display = 'none';
    document.getElementById('so-header-sub').textContent = 'Loading...';

    switchTab('details');

    fetch(`${SO_BASE}detail/${storyId}`, { headers:{'X-Requested-With':'XMLHttpRequest'} })
        .then(r => { if (!r.ok) throw new Error(); return r.json(); })
        .then(story => {
            populateSlideOver(story);
            loading.classList.add('hidden');
            content.classList.remove('hidden');
            content.classList.add('flex');
            document.getElementById('so-tabs').style.display = 'flex';
        })
        .catch(() => {
            loading.classList.add('hidden');
            errDiv.classList.remove('hidden');
            errDiv.classList.add('flex');
        });
}

function closeSlideOver() {
    const panel    = document.getElementById('story-slideover');
    const backdrop = document.getElementById('story-slideover-backdrop');
    panel.classList.remove('entering');
    panel.classList.add('leaving');
    setTimeout(() => {
        panel.classList.add('translate-x-full');
        panel.classList.remove('leaving');
        backdrop.classList.add('hidden');
        document.body.style.overflow = '';
    }, 240);
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSlideOver(); });

function switchTab(tab) {
    document.querySelectorAll('.so-tab-btn').forEach((b, i) => {
        const t = i === 0 ? 'details' : 'edit';
        b.classList.toggle('active', t === tab);
    });
    document.querySelectorAll('.so-tab-panel').forEach(p => {
        p.classList.toggle('active', p.id === `so-tab-${tab}`);
    });
}

function populateSlideOver(story) {
    document.getElementById('so-header-sub').textContent = story.title ? `ID #${story.id}` : 'Story';
    document.getElementById('so-view-link').href = `${STORY_URL}${story.id}`;

    const cover = document.getElementById('so-cover');
    const ph    = document.getElementById('so-cover-placeholder');
    if (story.cover_image) {
        cover.src = UPLOAD_URL + story.cover_image;
        cover.classList.remove('hidden'); ph.classList.add('hidden');
    } else {
        cover.classList.add('hidden'); ph.classList.remove('hidden');
    }

    const isFeat = parseInt(story.is_featured) === 1;
    document.getElementById('so-feat-badge').classList.toggle('hidden', !isFeat);

    document.getElementById('so-title').textContent = story.title || 'Untitled';
    document.getElementById('so-author-name').textContent = story.author_name || '-';

    const cfg = STATUS_CFG[story.status] || STATUS_CFG.DRAFT;
    const statusBadge = document.getElementById('so-status-badge');
    statusBadge.className = `inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold ${cfg.cls}`;
    statusBadge.innerHTML = `<span class="material-symbols-outlined text-xs" style="font-variation-settings:'FILL' 1;font-size:12px">${cfg.icon}</span>${cfg.label}`;

    const genreBadge = document.getElementById('so-genre-badge');
    if (story.genres) { genreBadge.textContent = story.genres; genreBadge.classList.remove('hidden'); }
    else genreBadge.classList.add('hidden');

    const pubBadge = document.getElementById('so-pub-status-badge');
    if (story.publication_status) { pubBadge.textContent = story.publication_status; pubBadge.classList.remove('hidden'); }
    else pubBadge.classList.add('hidden');

    document.getElementById('so-featured-badge-row').classList.toggle('hidden', !isFeat);

    const descText = story.description || story.synopsis || '';
    const descWrap = document.getElementById('so-desc-wrap');
    if (descText) { document.getElementById('so-desc').textContent = descText; descWrap.classList.remove('hidden'); }
    else descWrap.classList.add('hidden');

    document.getElementById('so-stat-chapters').textContent = story.total_chapters ?? 0;
    const fmt = d => d ? new Date(d).toLocaleDateString('en-GB', {day:'2-digit', month:'short', year:'numeric'}) : '-';
    document.getElementById('so-stat-created').textContent = fmt(story.created_at);
    document.getElementById('so-stat-updated').textContent = fmt(story.updated_at);

    const authorCard = document.getElementById('so-author-card');
    if (story.author_name) {
        document.getElementById('so-author-name-card').textContent  = story.author_name;
        document.getElementById('so-author-email-card').textContent = story.author_email || 'No email on record';
        const avatar = document.getElementById('so-author-avatar');
        if (story.author_photo) {
            avatar.innerHTML = `<img src="${UPLOAD_URL}${story.author_photo}" class="w-full h-full object-cover rounded-full">`;
        }
        authorCard.classList.remove('hidden');
    }

    _origStatus = story.status || 'DRAFT';
    document.querySelectorAll('.so-status-radio').forEach(r => {
        r.checked = (r.value === _origStatus);
    });
    updateStatusHint(_origStatus);
    document.querySelectorAll('.so-status-radio').forEach(r => {
        r.addEventListener('change', () => { updateStatusHint(r.value); trackChanges(); });
    });

    _origFeatured = isFeat;
    document.getElementById('so-feat-toggle').checked = isFeat;
    document.getElementById('so-feat-yes').checked    = isFeat;
    document.getElementById('so-feat-no').checked     = !isFeat;
    document.getElementById('so-feat-label').textContent = isFeat ? 'On' : 'Off';
    document.getElementById('so-feat-toggle').addEventListener('change', trackChanges);

    _origPubStatus = story.publication_status || '';
    document.querySelectorAll('.so-pub-radio').forEach(r => {
        r.checked = (r.value === _origPubStatus);
        r.addEventListener('change', trackChanges);
    });

    document.getElementById('so-form').action = `${SO_BASE}update/${story.id}`;

    trackChanges();
}

function updateStatusHint(val) {
    const pill = document.getElementById('so-status-hint-pill');
    pill.textContent = STATUS_HINTS[val] || '';
    const clsMap = {
        DRAFT:'bg-gray-100 text-gray-500', PENDING_REVIEW:'bg-amber-100 text-amber-700',
        PUBLISHED:'bg-green-100 text-green-700', ARCHIVED:'bg-red-100 text-red-600'
    };
    pill.className = `text-[10px] px-2 py-0.5 rounded-full font-medium ${clsMap[val] || 'bg-gray-100 text-gray-500'}`;
}

function syncFeatured(toggle) {
    const on = toggle.checked;
    document.getElementById('so-feat-yes').checked = on;
    document.getElementById('so-feat-no').checked  = !on;
    document.getElementById('so-feat-label').textContent = on ? 'On' : 'Off';
    trackChanges();
}

function trackChanges() {
    const newStatus = document.querySelector('.so-status-radio:checked')?.value || _origStatus;
    const newFeat   = document.getElementById('so-feat-toggle').checked;
    const newPub    = document.querySelector('.so-pub-radio:checked')?.value || '';

    const changes = [];
    if (newStatus !== _origStatus) {
        const oldL = STATUS_CFG[_origStatus]?.label || _origStatus;
        const newL = STATUS_CFG[newStatus]?.label  || newStatus;
        changes.push(`Status: <b class="text-gray-700">${oldL}</b> → <b class="text-indigo-700">${newL}</b>`);
    }
    if (newFeat !== _origFeatured) {
        changes.push(`Featured: <b class="text-indigo-700">${newFeat ? 'Yes ⭐' : 'No'}</b>`);
    }
    if (newPub && newPub !== _origPubStatus) {
        changes.push(`Publication: <b class="text-indigo-700">${newPub}</b>`);
    }

    const summary = document.getElementById('so-change-summary');
    const list    = document.getElementById('so-changes-list');
    if (changes.length) {
        list.innerHTML = changes.map(c => `<p class="text-[11px] text-indigo-700">${c}</p>`).join('');
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
    approve: {
        title:     'Publish Story',
        body:      'This story will be published and become visible to all readers immediately.',
        icon:      'check_circle',
        iconBg:    'bg-green-100',
        iconColor: 'text-green-600',
        btnBg:     'bg-green-600 hover:bg-green-700',
        btnLabel:  'Yes, Publish',
        toast:     ['Story published successfully.', 'success'],
    },
    archive: {
        title:     'Archive Story',
        body:      'This story will be hidden from all readers. You can restore it later.',
        icon:      'archive',
        iconBg:    'bg-amber-100',
        iconColor: 'text-amber-600',
        btnBg:     'bg-amber-500 hover:bg-amber-600',
        btnLabel:  'Yes, Archive',
        toast:     ['Story archived.', 'warning'],
    },
    delete: {
        title:     'Delete Story Permanently',
        body:      'This will permanently delete the story and all its chapters. This action cannot be undone.',
        icon:      'delete_forever',
        iconBg:    'bg-red-100',
        iconColor: 'text-red-600',
        btnBg:     'bg-red-600 hover:bg-red-700',
        btnLabel:  'Yes, Delete',
        toast:     ['Story deleted permanently.', 'error'],
    },
};

function openModal(form, type) {
    _pendingForm = form;
    const cfg = modalConfigs[type];
    document.getElementById('modal-title').textContent   = cfg.title;
    document.getElementById('modal-body').textContent    = cfg.body;
    document.getElementById('modal-icon').textContent    = cfg.icon;
    document.getElementById('modal-icon-wrap').className = `w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 ${cfg.iconBg}`;
    document.getElementById('modal-icon').className      = `material-symbols-outlined text-xl ${cfg.iconColor}`;
    const btn = document.getElementById('modal-confirm-btn');
    btn.textContent = cfg.btnLabel;
    btn.className   = `px-4 py-2 text-sm rounded-xl font-semibold text-white transition ${cfg.btnBg}`;
    btn.onclick     = () => {
        const t = cfg.toast;
        closeModal();
        showToast(t[0], t[1]);
        setTimeout(() => form.submit(), 350);
    };
    const modal = document.getElementById('confirm-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('confirm-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    _pendingForm = null;
}

document.getElementById('confirm-modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

<!-- ═══════════════════ STATS CARDS ═══════════════════ -->
<?php
    $stories       = $stories ?? [];
    $statTotal     = count($stories);
    $statPublished = count(array_filter($stories, fn($s) => ($s['status'] ?? '') === 'PUBLISHED'));
    $statPending   = count(array_filter($stories, fn($s) => ($s['status'] ?? '') === 'PENDING_REVIEW'));
    $statDraft     = count(array_filter($stories, fn($s) => ($s['status'] ?? '') === 'DRAFT'));
    $statArchived  = count(array_filter($stories, fn($s) => ($s['status'] ?? '') === 'ARCHIVED'));
    $statFeatured  = count(array_filter($stories, fn($s) => !empty($s['is_featured'])));
?>
<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-purple-600 text-xl" style="font-variation-settings:'FILL' 1">menu_book</span>
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
        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-amber-600 text-xl" style="font-variation-settings:'FILL' 1">pending</span>
        </div>
        <div>
            <p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Pending</p>
            <p class="text-xl font-bold text-gray-800"><?= number_format($statPending) ?></p>
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
        <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-indigo-600 text-xl" style="font-variation-settings:'FILL' 1">star</span>
        </div>
        <div>
            <p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Featured</p>
            <p class="text-xl font-bold text-gray-800"><?= number_format($statFeatured) ?></p>
        </div>
    </div>

</div>

<!-- ═══════════════════ MAIN TABLE ═══════════════════ -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2 flex-shrink-0">
            <span class="material-symbols-outlined text-purple-500 text-xl" style="font-variation-settings:'FILL' 1">menu_book</span>
            Stories
        </h3>

        <div class="flex items-center gap-2 flex-wrap">
            <select id="filterStatus"
                class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50 text-gray-600">
                <option value="">All Status</option>
                <option value="PUBLISHED">Published</option>
                <option value="PENDING_REVIEW">Pending Review</option>
                <option value="DRAFT">Draft</option>
                <option value="ARCHIVED">Archived</option>
            </select>

            <select id="filterPublication"
                class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50 text-gray-600">
                <option value="">All Publication</option>
                <option value="Ongoing">Ongoing</option>
                <option value="Completed">Completed</option>
                <option value="On Hiatus">On Hiatus</option>
            </select>

            <select id="filterFeatured"
                class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50 text-gray-600">
                <option value="">All Featured</option>
                <option value="1">Featured</option>
                <option value="0">Not Featured</option>
            </select>

            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base">search</span>
                <input type="text" id="searchInput" placeholder="Search stories..."
                    class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 w-52">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="storiesTable">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-medium w-10 text-gray-400">#</th>
                    <th class="px-4 py-3 text-left font-medium">Story</th>
                    <th class="px-4 py-3 text-left font-medium">Author</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                    <th class="px-4 py-3 text-left font-medium">Publication</th>
                    <th class="px-4 py-3 text-left font-medium">Featured</th>
                    <th class="px-4 py-3 text-left font-medium">Date</th>
                    <th class="px-4 py-3 text-center font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (!empty($stories)): ?>
                    <?php foreach ($stories as $i => $story): ?>
                        <?php
                            $status      = $story['status'] ?? 'DRAFT';
                            $isFeatured  = !empty($story['is_featured']) ? 1 : 0;
                            $isPublished = ($status === 'PUBLISHED') ? 1 : 0;
                            $badgeMap    = [
                                'PUBLISHED'      => 'bg-green-100 text-green-700',
                                'PENDING_REVIEW' => 'bg-amber-100 text-amber-700',
                                'DRAFT'          => 'bg-gray-100 text-gray-500',
                                'ARCHIVED'       => 'bg-red-100 text-red-600',
                            ];
                            $badge       = $badgeMap[$status] ?? 'bg-gray-100 text-gray-500';
                            $statusLabel = ucfirst(strtolower(str_replace('_', ' ', $status)));
                        ?>
                        <tr class="hover:bg-gray-50/70 transition-colors"
                            data-status="<?= esc($status) ?>"
                            data-featured="<?= $isFeatured ?>"
                            data-publication="<?= $isPublished ?>">

                            <!-- # -->
                            <td class="px-4 py-3.5 text-gray-300 text-xs font-medium"><?= $i + 1 ?></td>

                            <!-- Story -->
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <?php if (!empty($story['cover_image'])): ?>
                                        <img src="<?= base_url('uploads/' . $story['cover_image']) ?>"
                                             class="w-8 h-11 object-cover rounded-lg flex-shrink-0 shadow-sm"
                                             onerror="this.style.display='none'">
                                    <?php else: ?>
                                        <div class="w-8 h-11 bg-purple-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <span class="material-symbols-outlined text-purple-300 text-base">menu_book</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="min-w-0">
                                        <a href="<?= base_url('/admin/stories/detail/' . $story['id']) ?>"
                                           class="font-medium text-gray-800 hover:text-indigo-600 transition text-sm leading-snug line-clamp-1 block"
                                           title="<?= esc($story['title']) ?>" target="_blank">
                                            <?= esc($story['title']) ?>
                                        </a>
                                        <?php if (!empty($story['genres'])): ?>
                                            <p class="text-xs text-gray-400 mt-0.5 truncate max-w-[160px]"><?= esc($story['genres']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            <!-- Author -->
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <?php if (!empty($story['author_photo'])): ?>
                                        <img src="<?= base_url('uploads/' . ltrim($story['author_photo'], '/')) ?>"
                                             alt="" class="w-6 h-6 rounded-full object-cover border border-gray-100 flex-shrink-0">
                                    <?php else: ?>
                                        <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                            <span class="material-symbols-outlined text-indigo-400" style="font-size:14px">person</span>
                                        </div>
                                    <?php endif; ?>
                                    <span class="text-sm text-gray-600 truncate max-w-[120px]"><?= esc($story['author_name'] ?? '-') ?></span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold <?= $badge ?>">
                                    <?= esc($statusLabel) ?>
                                </span>
                            </td>

                            <!-- Publication -->
                            <td class="px-4 py-3.5">
                                <?php
                                    $pubStatus = $story['publication_status'] ?? '';
                                    $pubMap = [
                                        'Ongoing'    => ['bg' => 'bg-blue-50',   'text' => 'text-blue-600'],
                                        'Completed'  => ['bg' => 'bg-green-50',  'text' => 'text-green-600'],
                                        'On Hiatus'  => ['bg' => 'bg-amber-50',  'text' => 'text-amber-600'],
                                    ];
                                    $pubStyle = $pubMap[$pubStatus] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-400'];
                                ?>
                                <?php if ($pubStatus): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium <?= $pubStyle['bg'] ?> <?= $pubStyle['text'] ?>">
                                        <?= esc($pubStatus) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-300 text-xs">—</span>
                                <?php endif; ?>
                            </td>

                            <!-- Featured -->
                            <td class="px-4 py-3.5">
                                <form action="<?= base_url('/admin/stories/toggle-featured/' . $story['id']) ?>" method="POST" class="inline">
                                    <?= csrf_field() ?>
                                    <select name="is_featured"
                                        class="border border-gray-200 rounded-lg px-2 py-1 text-xs cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-300 <?= $isFeatured ? 'bg-indigo-50 text-indigo-700 font-semibold border-indigo-200' : 'bg-gray-50 text-gray-500' ?>"
                                        onchange="
                                            const label = this.options[this.selectedIndex].text;
                                            showToast('Featured status updated to ' + label + '.', 'info');
                                            this.form.submit();
                                        ">
                                        <option value="1" <?= $isFeatured ? 'selected' : '' ?>>Featured</option>
                                        <option value="0" <?= !$isFeatured ? 'selected' : '' ?>>Not Featured</option>
                                    </select>
                                </form>
                            </td>

                            <!-- Date -->
                            <td class="px-4 py-3.5 text-xs text-gray-400 whitespace-nowrap">
                                <?= !empty($story['created_at']) ? date('d M Y', strtotime($story['created_at'])) : '—' ?>
                            </td>

                            <!-- Actions — COLORED ICONS -->
                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">

                                    <!-- Edit: Indigo (external link icon) -->
                                    <button type="button"
                                        title="Edit"
                                        class="action-btn edit"
                                        onclick="openSlideOver(<?= $story['id'] ?>)">
                                        <span class="material-symbols-outlined" style="font-size:18px; color:#6366f1;">open_in_new</span>
                                    </button>

                                    <?php if ($status === 'PENDING_REVIEW'): ?>
                                        <!-- Approve: Green -->
                                        <form action="<?= base_url('/admin/stories/approve/' . $story['id']) ?>" method="POST" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="button" title="Approve & Publish"
                                                class="action-btn approve"
                                                onclick="openModal(this.closest('form'), 'approve')">
                                                <span class="material-symbols-outlined" style="font-size:16px">check_circle</span>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($status !== 'ARCHIVED'): ?>
                                        <!-- Archive: Amber -->
                                        <form action="<?= base_url('/admin/stories/archive/' . $story['id']) ?>" method="POST" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="button" title="Archive"
                                                class="action-btn archive"
                                                onclick="openModal(this.closest('form'), 'archive')">
                                                <span class="material-symbols-outlined" style="font-size:16px">archive</span>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <!-- Delete: Red -->
                                    <form action="<?= base_url('/admin/stories/delete/' . $story['id']) ?>" method="POST" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="button" title="Delete"
                                            class="action-btn delete"
                                            onclick="openModal(this.closest('form'), 'delete')">
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
                            <span class="material-symbols-outlined text-5xl text-gray-200 block mb-2">menu_book</span>
                            <p class="text-gray-400 text-sm">No stories found</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="px-6 py-3 bg-gray-50 text-xs text-gray-400 border-t border-gray-100 flex items-center justify-between">
        <span>Showing <span class="font-semibold text-gray-600" id="visibleCount"><?= count($stories) ?></span> of <?= count($stories) ?> stories</span>
    </div>
</div>

<script>
    function applyFilters() {
        const q           = document.getElementById('searchInput').value.toLowerCase();
        const status      = document.getElementById('filterStatus').value;
        const publication = document.getElementById('filterPublication').value;
        const featured    = document.getElementById('filterFeatured').value;
        let visible       = 0;

        document.querySelectorAll('#storiesTable tbody tr').forEach(row => {
            const text           = row.textContent.toLowerCase();
            const rowStatus      = row.getAttribute('data-status')      ?? '';
            const rowFeatured    = row.getAttribute('data-featured')    ?? '';
            const rowPubStatus   = row.querySelector('td:nth-child(5) span')?.textContent.trim() ?? '';

            // Publication filter: match against publication status text
            const matchPublication = !publication || rowPubStatus === publication;
            // Featured filter: match against data-featured attribute
            const matchFeatured    = !featured    || rowFeatured === featured;
            // Status filter: match against data-status attribute
            const matchStatus      = !status      || rowStatus === status;
            // Search filter: match against row text
            const matchText        = !q           || text.includes(q);

            const show = matchText && matchStatus && matchPublication && matchFeatured;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        document.getElementById('visibleCount').textContent = visible;
    }

    document.getElementById('searchInput').addEventListener('input', applyFilters);
    document.getElementById('filterStatus').addEventListener('change', applyFilters);
    document.getElementById('filterPublication').addEventListener('change', applyFilters);
    document.getElementById('filterFeatured').addEventListener('change', applyFilters);
</script>

<?= $this->endSection() ?>