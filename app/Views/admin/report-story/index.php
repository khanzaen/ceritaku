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
    #report-slideover { width: min(500px, 100vw); }
    .so-tab-btn.active { background:#fef3c7; color:#b45309; font-weight:600; }
    .so-tab-btn { transition:all .2s; }
    .so-tab-panel { display:none; }
    .so-tab-panel.active { display:block; }
    .status-option { transition:all .15s ease; }
    .status-option:has(input:checked) { border-color:var(--sc); background:var(--sb); }
    @keyframes so-in  { from{transform:translateX(100%)} to{transform:translateX(0)} }
    @keyframes so-out { from{transform:translateX(0)} to{transform:translateX(100%)} }
    #report-slideover.entering { animation:so-in .28s cubic-bezier(.32,0,.67,0) forwards; }
    #report-slideover.leaving  { animation:so-out .25s cubic-bezier(.32,0,.67,0) forwards; }
    .pulse-bg { background:linear-gradient(90deg,#f3f4f6 25%,#e9eaec 50%,#f3f4f6 75%); background-size:200% 100%; animation:shimmer 1.4s infinite; }
    @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
    .action-btn { display:inline-flex; align-items:center; justify-content:center; width:30px; height:30px; border-radius:8px; transition:all .18s ease; border:none; cursor:pointer; }
    .action-btn.view    { background:#fef3c7; color:#b45309; }
    .action-btn.view:hover    { background:#b45309; color:#fff; box-shadow:0 4px 12px rgba(180,83,9,.35); }
    .action-btn.resolve { background:#dcfce7; color:#16a34a; }
    .action-btn.resolve:hover { background:#16a34a; color:#fff; box-shadow:0 4px 12px rgba(22,163,74,.35); }
    .action-btn.dismiss { background:#f3f4f6; color:#6b7280; }
    .action-btn.dismiss:hover { background:#6b7280; color:#fff; box-shadow:0 4px 12px rgba(107,114,128,.35); }
    .action-btn.delete  { background:#fee2e2; color:#dc2626; }
    .action-btn.delete:hover  { background:#dc2626; color:#fff; box-shadow:0 4px 12px rgba(220,38,38,.35); }
</style>

<div id="report-slideover-backdrop" class="fixed inset-0 z-40 bg-black/25 backdrop-blur-[2px] hidden" onclick="closeSO()"></div>

<div id="report-slideover" class="fixed top-0 right-0 h-full z-50 bg-white shadow-2xl flex flex-col translate-x-full">

    <!-- Header -->
    <div class="flex-shrink-0 bg-white border-b border-gray-100">
        <div class="flex items-center justify-between px-5 py-3.5">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-orange-500 flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-white text-sm" style="font-variation-settings:'FILL' 1">flag</span>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800 leading-none">Report Detail</p>
                    <p class="text-[10px] text-gray-400 mt-0.5" id="so-header-sub">Loading...</p>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <a id="so-story-link" href="#" target="_blank" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-orange-600 transition" title="View story">
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
            <button class="so-tab-btn px-3 py-1.5 rounded-lg text-xs text-gray-500" onclick="switchTab('action')">
                <span class="material-symbols-outlined text-sm align-middle mr-1" style="vertical-align:-3px">gavel</span>Action
            </button>
        </div>
    </div>

    <!-- Loading skeleton -->
    <div id="so-loading" class="flex-1 overflow-y-auto p-5 space-y-4">
        <div class="pulse-bg h-5 rounded-lg w-3/4"></div>
        <div class="pulse-bg h-3 rounded-lg w-1/2"></div>
        <div class="pulse-bg h-16 rounded-xl w-full"></div>
        <div class="grid grid-cols-2 gap-3">
            <div class="pulse-bg h-16 rounded-xl"></div>
            <div class="pulse-bg h-16 rounded-xl"></div>
        </div>
        <div class="pulse-bg h-20 rounded-xl w-full"></div>
        <div class="pulse-bg h-12 rounded-xl w-full"></div>
    </div>

    <!-- Main content -->
    <div id="so-content" class="flex-1 overflow-hidden hidden flex-col">

        <!-- TAB: Details -->
        <div id="so-tab-details" class="so-tab-panel active flex-1 overflow-y-auto px-5 py-4 space-y-4">

            <!-- Status + reason -->
            <div class="flex items-start gap-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span id="so-status-badge" class="px-2.5 py-1 rounded-full text-xs font-semibold"></span>
                        <span id="so-reason-badge" class="px-2.5 py-1 rounded-full text-xs font-medium bg-orange-50 text-orange-700"></span>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1.5" id="so-date"></p>
                </div>
            </div>

            <!-- Description -->
            <div id="so-desc-wrap" class="hidden bg-orange-50 border border-orange-100 rounded-xl px-4 py-3">
                <p class="text-[10px] font-semibold text-orange-500 uppercase tracking-wider mb-1.5">Description</p>
                <p id="so-desc" class="text-sm text-gray-700 leading-relaxed"></p>
            </div>

            <!-- Evidence image -->
            <div id="so-evidence-wrap" class="hidden">
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Evidence</p>
                <img id="so-evidence-img" src="" alt="Evidence" class="rounded-xl w-full max-h-52 object-cover border border-gray-100 shadow-sm">
            </div>

            <!-- Reporter card -->
            <div class="border border-gray-100 rounded-xl p-3">
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Reporter</p>
                <div class="flex items-center gap-3">
                    <div id="so-reporter-avatar" class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 overflow-hidden font-bold text-indigo-700">
                        <span class="material-symbols-outlined text-indigo-400">person</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-gray-800" id="so-reporter-name"></p>
                        <p class="text-[10px] text-gray-400 truncate" id="so-reporter-email"></p>
                    </div>
                </div>
            </div>

            <!-- Reported story card -->
            <div class="border border-gray-100 rounded-xl p-3">
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Reported Story</p>
                <div class="flex items-center gap-3">
                    <div id="so-story-cover" class="w-10 h-14 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0 overflow-hidden">
                        <span class="material-symbols-outlined text-purple-300">menu_book</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-semibold text-gray-800 truncate" id="so-story-title"></p>
                        <p class="text-[10px] text-gray-500 mt-0.5">by <span id="so-author-name"></span></p>
                        <span id="so-story-status-badge" class="mt-1 inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold"></span>
                    </div>
                </div>
            </div>

            <!-- Admin note (read-only) -->
            <div id="so-note-wrap" class="hidden bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
                <p class="text-[10px] font-semibold text-blue-500 uppercase tracking-wider mb-1.5">Admin Note</p>
                <p id="so-admin-note" class="text-xs text-gray-700 leading-relaxed"></p>
            </div>

        </div>

        <!-- TAB: Action -->
        <div id="so-tab-action" class="so-tab-panel flex-1 flex flex-col overflow-hidden">
            <form id="so-form" method="POST" action="" class="flex-1 flex flex-col overflow-hidden">
                <?= csrf_field() ?>

                <div class="flex-1 overflow-y-auto px-5 py-4 space-y-5">

                    <!-- Status picker -->
                    <div>
                        <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider block mb-2">Update Status</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="status-option cursor-pointer border-2 border-gray-150 rounded-xl p-3 flex items-center gap-2.5 hover:border-amber-200" style="--sc:#fcd34d;--sb:#fffbeb;">
                                <input type="radio" name="status" value="pending" class="hidden so-status-radio">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-amber-500 text-base" style="font-variation-settings:'FILL' 1">pending</span>
                                </div>
                                <div><p class="text-xs font-semibold text-gray-700">Pending</p><p class="text-[10px] text-gray-400">Belum ditinjau</p></div>
                            </label>
                            <label class="status-option cursor-pointer border-2 border-gray-150 rounded-xl p-3 flex items-center gap-2.5 hover:border-blue-200" style="--sc:#93c5fd;--sb:#eff6ff;">
                                <input type="radio" name="status" value="reviewed" class="hidden so-status-radio">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-blue-500 text-base" style="font-variation-settings:'FILL' 1">manage_search</span>
                                </div>
                                <div><p class="text-xs font-semibold text-gray-700">Reviewed</p><p class="text-[10px] text-gray-400">Sedang ditinjau</p></div>
                            </label>
                            <label class="status-option cursor-pointer border-2 border-gray-150 rounded-xl p-3 flex items-center gap-2.5 hover:border-green-200" style="--sc:#6ee7b7;--sb:#ecfdf5;">
                                <input type="radio" name="status" value="resolved" class="hidden so-status-radio">
                                <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-green-600 text-base" style="font-variation-settings:'FILL' 1">check_circle</span>
                                </div>
                                <div><p class="text-xs font-semibold text-gray-700">Resolved</p><p class="text-[10px] text-gray-400">Sudah ditangani</p></div>
                            </label>
                            <label class="status-option cursor-pointer border-2 border-gray-150 rounded-xl p-3 flex items-center gap-2.5 hover:border-gray-200" style="--sc:#d1d5db;--sb:#f9fafb;">
                                <input type="radio" name="status" value="dismissed" class="hidden so-status-radio">
                                <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-gray-500 text-base" style="font-variation-settings:'FILL' 1">cancel</span>
                                </div>
                                <div><p class="text-xs font-semibold text-gray-700">Dismissed</p><p class="text-[10px] text-gray-400">Ditolak/diabaikan</p></div>
                            </label>
                        </div>
                    </div>

                    <div class="h-px bg-gray-100"></div>

                    <!-- Admin note -->
                    <div>
                        <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider block mb-2">Admin Note <span class="text-gray-400 font-normal normal-case">(opsional)</span></label>
                        <textarea name="admin_note" id="so-note-input" rows="4"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none"
                            placeholder="Catatan internal untuk laporan ini..."></textarea>
                    </div>

                </div>

                <div class="flex-shrink-0 px-5 py-4 border-t border-gray-100 bg-white flex gap-2.5">
                    <button type="button" onclick="closeSO()" class="px-4 py-2.5 text-sm rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition font-medium flex-1">Cancel</button>
                    <button type="submit" class="px-4 py-2.5 text-sm rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-semibold transition flex-[2] flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-base" style="font-variation-settings:'FILL' 1">save</span>Save Action
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
            <p class="text-sm font-semibold text-gray-700">Failed to load report</p>
            <button onclick="closeSO()" class="mt-4 px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-600 transition">Close</button>
        </div>
    </div>
</div>

<script>
const RPT_BASE   = '<?= base_url('/admin/reports/') ?>';
const UPLOAD_URL = '<?= base_url('uploads/') ?>';
const STORY_URL  = '<?= base_url('/story/') ?>';

const STATUS_CFG = {
    pending:   { label:'Pending',   cls:'bg-amber-100 text-amber-700',  icon:'pending'        },
    reviewed:  { label:'Reviewed',  cls:'bg-blue-100 text-blue-700',    icon:'manage_search'  },
    resolved:  { label:'Resolved',  cls:'bg-green-100 text-green-700',  icon:'check_circle'   },
    dismissed: { label:'Dismissed', cls:'bg-gray-100 text-gray-500',    icon:'cancel'         },
};

function openSO(reportId) {
    const backdrop = document.getElementById('report-slideover-backdrop');
    const panel    = document.getElementById('report-slideover');
    backdrop.classList.remove('hidden');
    panel.classList.remove('translate-x-full','leaving');
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

    fetch(`${RPT_BASE}detail/${reportId}`, { headers:{'X-Requested-With':'XMLHttpRequest'} })
        .then(r => { if (!r.ok) throw new Error(); return r.json(); })
        .then(rp => {
            populateSO(rp);
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
    const panel = document.getElementById('report-slideover');
    panel.classList.remove('entering');
    panel.classList.add('leaving');
    setTimeout(() => {
        panel.classList.add('translate-x-full');
        panel.classList.remove('leaving');
        document.getElementById('report-slideover-backdrop').classList.add('hidden');
        document.body.style.overflow = '';
    }, 240);
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSO(); });

function switchTab(tab) {
    document.querySelectorAll('.so-tab-btn').forEach((b, i) => {
        b.classList.toggle('active', (i === 0 ? 'details' : 'action') === tab);
    });
    document.querySelectorAll('.so-tab-panel').forEach(p => {
        p.classList.toggle('active', p.id === `so-tab-${tab}`);
    });
}

function populateSO(rp) {
    document.getElementById('so-header-sub').textContent = `Report #${rp.id}`;

    const cfg = STATUS_CFG[rp.status] || STATUS_CFG.pending;
    const statusBadge = document.getElementById('so-status-badge');
    statusBadge.textContent = cfg.label;
    statusBadge.className   = `px-2.5 py-1 rounded-full text-xs font-semibold ${cfg.cls}`;

    document.getElementById('so-reason-badge').textContent = rp.report_reason || '-';

    const fmt = d => d ? new Date(d).toLocaleDateString('id-ID', {day:'2-digit',month:'long',year:'numeric',hour:'2-digit',minute:'2-digit'}) : '-';
    document.getElementById('so-date').textContent = 'Dilaporkan pada: ' + fmt(rp.created_at);

    const descWrap = document.getElementById('so-desc-wrap');
    if (rp.description) {
        document.getElementById('so-desc').textContent = rp.description;
        descWrap.classList.remove('hidden');
    } else descWrap.classList.add('hidden');

    const evidenceWrap = document.getElementById('so-evidence-wrap');
    if (rp.evidence_image) {
        document.getElementById('so-evidence-img').src = UPLOAD_URL + rp.evidence_image;
        evidenceWrap.classList.remove('hidden');
    } else evidenceWrap.classList.add('hidden');

    // Reporter
    const reporterAvatar = document.getElementById('so-reporter-avatar');
    const initial = (rp.reporter_name || 'U').charAt(0).toUpperCase();
    if (rp.reporter_photo) {
        reporterAvatar.innerHTML = `<img src="${UPLOAD_URL}${rp.reporter_photo}" class="w-full h-full object-cover">`;
    } else {
        reporterAvatar.innerHTML = `<span style="font-weight:700">${initial}</span>`;
    }
    document.getElementById('so-reporter-name').textContent  = rp.reporter_name  || '-';
    document.getElementById('so-reporter-email').textContent = rp.reporter_email || '-';

    // Story
    document.getElementById('so-story-link').href = `${STORY_URL}${rp.story_id}`;
    document.getElementById('so-story-title').textContent  = rp.story_title  || '-';
    document.getElementById('so-author-name').textContent  = rp.author_name  || '-';
    const storyCover = document.getElementById('so-story-cover');
    if (rp.story_cover) {
        storyCover.innerHTML = `<img src="${UPLOAD_URL}${rp.story_cover}" class="w-full h-full object-cover">`;
    }
    const storyStatusMap = { PUBLISHED:'bg-green-100 text-green-700', DRAFT:'bg-gray-100 text-gray-500', ARCHIVED:'bg-red-100 text-red-600', PENDING_REVIEW:'bg-amber-100 text-amber-700' };
    const ssb = document.getElementById('so-story-status-badge');
    ssb.textContent = rp.story_status || '-';
    ssb.className   = `mt-1 inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold ${storyStatusMap[rp.story_status] || 'bg-gray-100 text-gray-500'}`;

    // Admin note
    const noteWrap = document.getElementById('so-note-wrap');
    if (rp.admin_note) {
        document.getElementById('so-admin-note').textContent = rp.admin_note;
        noteWrap.classList.remove('hidden');
    } else noteWrap.classList.add('hidden');

    // Action tab: pre-fill form
    document.querySelectorAll('.so-status-radio').forEach(r => { r.checked = (r.value === rp.status); });
    document.getElementById('so-note-input').value = rp.admin_note || '';
    document.getElementById('so-form').action = `${RPT_BASE}update/${rp.id}`;
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
    resolve:  { title:'Resolve Report',  body:'Report ini akan ditandai sebagai sudah ditangani.', icon:'check_circle', iconBg:'bg-green-100', iconColor:'text-green-600', btnBg:'bg-green-600 hover:bg-green-700', btnLabel:'Yes, Resolve', toast:['Report di-resolve.', 'success'] },
    dismiss:  { title:'Dismiss Report',  body:'Report ini akan diabaikan dan ditandai sebagai dismissed.', icon:'cancel', iconBg:'bg-gray-100', iconColor:'text-gray-600', btnBg:'bg-gray-600 hover:bg-gray-700', btnLabel:'Yes, Dismiss', toast:['Report di-dismiss.', 'warning'] },
    delete:   { title:'Delete Report',   body:'Report ini akan dihapus secara permanen.', icon:'delete_forever', iconBg:'bg-red-100', iconColor:'text-red-600', btnBg:'bg-red-600 hover:bg-red-700', btnLabel:'Yes, Delete', toast:['Report dihapus.', 'error'] },
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
}
document.getElementById('confirm-modal').addEventListener('click', function(e) { if (e.target === this) closeModal(); });
</script>

<!-- ═══════════════════ STATS CARDS ═══════════════════ -->
<?php
    $reports       = $reports ?? [];
    $statTotal     = count($reports);
    $statPending   = count(array_filter($reports, fn($r) => ($r['status'] ?? '') === 'pending'));
    $statReviewed  = count(array_filter($reports, fn($r) => ($r['status'] ?? '') === 'reviewed'));
    $statResolved  = count(array_filter($reports, fn($r) => ($r['status'] ?? '') === 'resolved'));
    $statDismissed = count(array_filter($reports, fn($r) => ($r['status'] ?? '') === 'dismissed'));
?>
<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-orange-600 text-xl" style="font-variation-settings:'FILL' 1">flag</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Total</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statTotal) ?></p></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-amber-600 text-xl" style="font-variation-settings:'FILL' 1">pending</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Pending</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statPending) ?></p></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-blue-600 text-xl" style="font-variation-settings:'FILL' 1">manage_search</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Reviewed</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statReviewed) ?></p></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-green-600 text-xl" style="font-variation-settings:'FILL' 1">check_circle</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Resolved</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statResolved) ?></p></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-gray-500 text-xl" style="font-variation-settings:'FILL' 1">cancel</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Dismissed</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statDismissed) ?></p></div>
    </div>
</div>

<!-- ═══════════════════ TABLE ═══════════════════ -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-orange-500 text-xl">flag</span>
            Reports
        </h3>
        <div class="flex items-center gap-2 flex-wrap">
            <select id="filterStatus" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300 bg-gray-50 text-gray-600">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="reviewed">Reviewed</option>
                <option value="resolved">Resolved</option>
                <option value="dismissed">Dismissed</option>
            </select>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base">search</span>
                <input type="text" id="searchInput" placeholder="Search reports..." class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-300 w-52">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="reportsTable">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-medium w-10 text-gray-400">#</th>
                    <th class="px-4 py-3 text-left font-medium">Reporter</th>
                    <th class="px-4 py-3 text-left font-medium">Story</th>
                    <th class="px-4 py-3 text-left font-medium">Reason</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                    <th class="px-4 py-3 text-left font-medium">Date</th>
                    <th class="px-4 py-3 text-center font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (!empty($reports)): ?>
                    <?php foreach ($reports as $i => $rp): ?>
                        <?php
                            $status   = $rp['status'] ?? 'pending';
                            $badgeMap = [
                                'pending'   => 'bg-amber-100 text-amber-700',
                                'reviewed'  => 'bg-blue-100 text-blue-700',
                                'resolved'  => 'bg-green-100 text-green-700',
                                'dismissed' => 'bg-gray-100 text-gray-500',
                            ];
                            $badge = $badgeMap[$status] ?? 'bg-gray-100 text-gray-500';
                        ?>
                        <tr class="hover:bg-gray-50/70 transition-colors" data-status="<?= esc($status) ?>">
                            <td class="px-4 py-3.5 text-gray-300 text-xs font-medium"><?= $i + 1 ?></td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <?php if (!empty($rp['reporter_photo'])): ?>
                                        <img src="<?= base_url('uploads/' . $rp['reporter_photo']) ?>" alt="" class="w-7 h-7 rounded-full object-cover border border-gray-100 flex-shrink-0">
                                    <?php else: ?>
                                        <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs flex-shrink-0">
                                            <?= strtoupper(substr($rp['reporter_name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <span class="font-medium text-gray-800 truncate max-w-[110px]"><?= esc($rp['reporter_name'] ?? '-') ?></span>
                                </div>
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <?php if (!empty($rp['story_cover'])): ?>
                                        <img src="<?= base_url('uploads/' . $rp['story_cover']) ?>" alt="" class="w-6 h-8 rounded object-cover flex-shrink-0">
                                    <?php endif; ?>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate max-w-[130px]"><?= esc($rp['story_title'] ?? '-') ?></p>
                                        <p class="text-[10px] text-gray-400">by <?= esc($rp['author_name'] ?? '-') ?></p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3.5">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-orange-50 text-orange-700">
                                    <?= esc($rp['report_reason'] ?? '-') ?>
                                </span>
                            </td>

                            <td class="px-4 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold <?= $badge ?>">
                                    <?= ucfirst(esc($status)) ?>
                                </span>
                            </td>

                            <td class="px-4 py-3.5 text-xs text-gray-400 whitespace-nowrap">
                                <?= !empty($rp['created_at']) ? date('d M Y', strtotime($rp['created_at'])) : '—' ?>
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" title="View & Edit" class="action-btn view" onclick="openSO(<?= $rp['id'] ?>)">
                                        <span class="material-symbols-outlined" style="font-size:18px">open_in_new</span>
                                    </button>

                                    <?php if ($status === 'pending' || $status === 'reviewed'): ?>
                                        <form action="<?= base_url('/admin/reports/resolve/' . $rp['id']) ?>" method="POST" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="button" title="Resolve" class="action-btn resolve" onclick="openModal(this.closest('form'), 'resolve')">
                                                <span class="material-symbols-outlined" style="font-size:16px">check_circle</span>
                                            </button>
                                        </form>
                                        <form action="<?= base_url('/admin/reports/dismiss/' . $rp['id']) ?>" method="POST" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="button" title="Dismiss" class="action-btn dismiss" onclick="openModal(this.closest('form'), 'dismiss')">
                                                <span class="material-symbols-outlined" style="font-size:16px">cancel</span>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <form action="<?= base_url('/admin/reports/delete/' . $rp['id']) ?>" method="POST" class="inline">
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
                            <span class="material-symbols-outlined text-5xl text-gray-200 block mb-2">flag</span>
                            <p class="text-gray-400 text-sm">No reports found</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="px-6 py-3 bg-gray-50 text-xs text-gray-400 border-t border-gray-100 flex items-center justify-between">
        <span>Showing <span class="font-semibold text-gray-600" id="visibleCount"><?= count($reports) ?></span> of <?= count($reports) ?> reports</span>
    </div>
</div>

<script>
function applyFilters() {
    const q      = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('filterStatus').value;
    let visible  = 0;
    document.querySelectorAll('#reportsTable tbody tr').forEach(row => {
        const show = (!q || row.textContent.toLowerCase().includes(q))
            && (!status || (row.getAttribute('data-status') ?? '') === status);
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
