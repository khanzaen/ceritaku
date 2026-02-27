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
    #user-slideover { width: min(480px, 100vw); }
    .so-tab-btn.active { background:#eef2ff; color:#4f46e5; font-weight:600; }
    .so-tab-btn { transition: all .2s; }
    .so-tab-panel { display:none; }
    .so-tab-panel.active { display:block; }
    .status-option { transition: all .15s ease; }
    .status-option:has(input:checked) { border-color: var(--sc); background: var(--sb); }
    .ver-toggle { position:relative; width:44px; height:24px; }
    .ver-toggle input { opacity:0; width:0; height:0; }
    .ver-slider { position:absolute; cursor:pointer; inset:0; background:#d1d5db; border-radius:999px; transition:.3s; }
    .ver-slider:before { content:''; position:absolute; height:18px; width:18px; left:3px; top:3px; background:white; border-radius:50%; transition:.3s; box-shadow:0 1px 3px rgba(0,0,0,.2); }
    input:checked + .ver-slider { background:#16a34a; }
    input:checked + .ver-slider:before { transform:translateX(20px); }
    @keyframes so-in  { from{transform:translateX(100%)} to{transform:translateX(0)} }
    @keyframes so-out { from{transform:translateX(0)} to{transform:translateX(100%)} }
    #user-slideover.entering { animation: so-in .28s cubic-bezier(.32,0,.67,0) forwards; }
    #user-slideover.leaving  { animation: so-out .25s cubic-bezier(.32,0,.67,0) forwards; }
    .pulse-bg { background: linear-gradient(90deg,#f3f4f6 25%,#e9eaec 50%,#f3f4f6 75%); background-size:200% 100%; animation: shimmer 1.4s infinite; }
    @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
    .action-btn { display:inline-flex; align-items:center; justify-content:center; width:30px; height:30px; border-radius:8px; transition:all .18s ease; border:none; cursor:pointer; }
    .action-btn.edit   { background:#eef2ff; color:#6366f1; }
    .action-btn.edit:hover   { background:#6366f1; color:#fff; box-shadow:0 4px 12px rgba(99,102,241,.35); }
    .action-btn.verify { background:#dcfce7; color:#16a34a; }
    .action-btn.verify:hover { background:#16a34a; color:#fff; box-shadow:0 4px 12px rgba(22,163,74,.35); }
    .action-btn.delete { background:#fee2e2; color:#dc2626; }
    .action-btn.delete:hover { background:#dc2626; color:#fff; box-shadow:0 4px 12px rgba(220,38,38,.35); }
</style>

<div id="user-slideover-backdrop" class="fixed inset-0 z-40 bg-black/25 backdrop-blur-[2px] hidden" onclick="closeSO()"></div>

<div id="user-slideover" class="fixed top-0 right-0 h-full z-50 bg-white shadow-2xl flex flex-col translate-x-full">

    <!-- Header -->
    <div class="flex-shrink-0 bg-white border-b border-gray-100">
        <div class="flex items-center justify-between px-5 py-3.5">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-white text-sm" style="font-variation-settings:'FILL' 1">manage_accounts</span>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800 leading-none">User Editor</p>
                    <p class="text-[10px] text-gray-400 mt-0.5" id="so-header-sub">Loading...</p>
                </div>
            </div>
            <button onclick="closeSO()" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
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
        <div class="flex gap-4">
            <div class="pulse-bg w-14 h-14 rounded-full flex-shrink-0"></div>
            <div class="flex-1 space-y-2.5 pt-1">
                <div class="pulse-bg h-4 rounded-lg w-3/4"></div>
                <div class="pulse-bg h-3 rounded-lg w-1/2"></div>
                <div class="flex gap-2 mt-2">
                    <div class="pulse-bg h-6 w-16 rounded-full"></div>
                    <div class="pulse-bg h-6 w-20 rounded-full"></div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <div class="pulse-bg h-16 rounded-xl"></div>
            <div class="pulse-bg h-16 rounded-xl"></div>
            <div class="pulse-bg h-16 rounded-xl"></div>
        </div>
        <div class="pulse-bg h-px w-full"></div>
        <div class="pulse-bg h-3 w-28 rounded-lg"></div>
        <div class="grid grid-cols-2 gap-3">
            <div class="pulse-bg h-14 rounded-xl"></div>
            <div class="pulse-bg h-14 rounded-xl"></div>
        </div>
    </div>

    <!-- Main content -->
    <div id="so-content" class="flex-1 overflow-hidden hidden flex-col">

        <!-- TAB: Details -->
        <div id="so-tab-details" class="so-tab-panel active flex-1 overflow-y-auto px-5 py-4 space-y-4">

            <!-- Avatar + name -->
            <div class="flex items-center gap-4">
                <div id="so-avatar" class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 overflow-hidden text-indigo-700 font-bold text-2xl">
                    <span class="material-symbols-outlined text-indigo-400 text-3xl">person</span>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 id="so-name" class="font-bold text-gray-900 text-sm"></h3>
                    <p id="so-username" class="text-xs text-gray-500 mt-0.5"></p>
                    <p id="so-email" class="text-xs text-gray-400 mt-0.5 truncate"></p>
                </div>
                <div class="flex flex-col items-end gap-1.5">
                    <span id="so-role-badge" class="px-2.5 py-0.5 rounded-full text-xs font-semibold"></span>
                    <span id="so-verified-badge" class="px-2.5 py-0.5 rounded-full text-xs font-medium"></span>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <span class="material-symbols-outlined text-indigo-500 text-lg block mb-0.5" style="font-variation-settings:'FILL' 1">menu_book</span>
                    <p class="text-lg font-bold text-gray-800 leading-none" id="so-stat-stories">0</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Stories</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <span class="material-symbols-outlined text-yellow-500 text-lg block mb-0.5" style="font-variation-settings:'FILL' 1">rate_review</span>
                    <p class="text-lg font-bold text-gray-800 leading-none" id="so-stat-reviews">0</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Reviews</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <span class="material-symbols-outlined text-green-500 text-lg block mb-0.5" style="font-variation-settings:'FILL' 1">calendar_today</span>
                    <p class="text-xs font-semibold text-gray-700 leading-none mt-1" id="so-stat-joined">-</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Joined</p>
                </div>
            </div>

            <!-- Bio -->
            <div id="so-bio-wrap" class="hidden bg-gray-50 rounded-xl px-4 py-3">
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Bio</p>
                <p id="so-bio" class="text-xs text-gray-600 leading-relaxed"></p>
            </div>

            <!-- Recent stories -->
            <div id="so-recent-stories" class="hidden">
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Recent Stories</p>
                <div id="so-stories-list" class="space-y-2"></div>
            </div>

        </div>

        <!-- TAB: Edit -->
        <div id="so-tab-edit" class="so-tab-panel flex-1 flex flex-col overflow-hidden">
            <form id="so-form" method="POST" action="" class="flex-1 flex flex-col overflow-hidden">
                <?= csrf_field() ?>

                <div class="flex-1 overflow-y-auto px-5 py-4 space-y-5">

                    <!-- Role picker -->
                    <div>
                        <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider block mb-2">User Role</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="status-option cursor-pointer border-2 border-gray-150 rounded-xl p-3 flex items-center gap-2.5 hover:border-blue-200" style="--sc:#93c5fd; --sb:#eff6ff;">
                                <input type="radio" name="role" value="USER" class="hidden so-role-radio">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-blue-500 text-base" style="font-variation-settings:'FILL' 1">person</span>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-700">User</p>
                                    <p class="text-[10px] text-gray-400">Normal member</p>
                                </div>
                            </label>
                            <label class="status-option cursor-pointer border-2 border-gray-150 rounded-xl p-3 flex items-center gap-2.5 hover:border-purple-200" style="--sc:#c4b5fd; --sb:#faf5ff;">
                                <input type="radio" name="role" value="ADMIN" class="hidden so-role-radio">
                                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-purple-600 text-base" style="font-variation-settings:'FILL' 1">admin_panel_settings</span>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-700">Admin</p>
                                    <p class="text-[10px] text-gray-400">Full access</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="h-px bg-gray-100"></div>

                    <!-- Verified toggle -->
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-700">Verified Status</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Tandai user sebagai terverifikasi</p>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span id="so-ver-label" class="text-xs text-gray-400 font-medium">Off</span>
                            <label class="ver-toggle">
                                <input type="checkbox" id="so-ver-toggle" onchange="syncVerified(this)">
                                <span class="ver-slider"></span>
                            </label>
                            <input type="radio" name="is_verified" value="1" id="so-ver-yes" class="hidden">
                            <input type="radio" name="is_verified" value="0" id="so-ver-no"  class="hidden" checked>
                        </div>
                    </div>

                    <!-- Change summary -->
                    <div id="so-change-summary" class="hidden bg-indigo-50 border border-indigo-100 rounded-xl px-4 py-3">
                        <p class="text-[10px] font-semibold text-indigo-500 uppercase tracking-wider mb-2">Pending Changes</p>
                        <div id="so-changes-list" class="space-y-1"></div>
                    </div>

                </div>

                <div id="so-self-warning" class="hidden mx-5 mb-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3">
                    <p class="text-xs text-amber-700 font-medium">⚠️ Tidak dapat mengubah akun sendiri</p>
                </div>

                <!-- Footer -->
                <div class="flex-shrink-0 px-5 py-4 border-t border-gray-100 bg-white flex gap-2.5">
                    <button type="button" onclick="closeSO()" class="px-4 py-2.5 text-sm rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition font-medium flex-1">Cancel</button>
                    <button type="submit" id="so-save-btn" class="px-4 py-2.5 text-sm rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition flex-[2] flex items-center justify-center gap-2">
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
            <p class="text-sm font-semibold text-gray-700">Failed to load user</p>
            <button onclick="closeSO()" class="mt-4 px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-600 transition">Close</button>
        </div>
    </div>
</div>

<script>
const USER_BASE  = '<?= base_url('/admin/users/') ?>';
const UPLOAD_URL = '<?= base_url('uploads/') ?>';
const CUR_USER   = <?= session()->get('user_id') ?>;

const avatarColors = ['#6366f1','#8b5cf6','#ec4899','#16a34a','#d97706','#0891b2'];

let _origRole = '', _origVerified = false, _isSelf = false;

function openSO(userId) {
    _isSelf = (userId == CUR_USER);
    const backdrop = document.getElementById('user-slideover-backdrop');
    const panel    = document.getElementById('user-slideover');
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

    fetch(`${USER_BASE}detail/${userId}`, { headers:{'X-Requested-With':'XMLHttpRequest'} })
        .then(r => { if (!r.ok) throw new Error(); return r.json(); })
        .then(u => {
            populateSO(u);
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
    const panel = document.getElementById('user-slideover');
    panel.classList.remove('entering');
    panel.classList.add('leaving');
    setTimeout(() => {
        panel.classList.add('translate-x-full');
        panel.classList.remove('leaving');
        document.getElementById('user-slideover-backdrop').classList.add('hidden');
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

function populateSO(u) {
    document.getElementById('so-header-sub').textContent = `ID #${u.id}${u.id == CUR_USER ? ' (You)' : ''}`;

    // Avatar
    const avatarEl = document.getElementById('so-avatar');
    const initial  = (u.name || 'U').charAt(0).toUpperCase();
    const color    = avatarColors[initial.charCodeAt(0) % avatarColors.length];
    if (u.profile_photo) {
        avatarEl.innerHTML = `<img src="${UPLOAD_URL}${u.profile_photo}" class="w-full h-full object-cover">`;
    } else {
        avatarEl.style.background = color + '22';
        avatarEl.style.color = color;
        avatarEl.innerHTML = `<span style="font-size:1.5rem;font-weight:700">${initial}</span>`;
    }

    document.getElementById('so-name').textContent = u.name || 'Unknown';
    document.getElementById('so-username').textContent = u.username ? `@${u.username}` : '';
    document.getElementById('so-email').textContent    = u.email || '';

    const role       = (u.role || 'USER').toUpperCase();
    const roleBadge  = document.getElementById('so-role-badge');
    roleBadge.textContent = role;
    roleBadge.className   = `px-2.5 py-0.5 rounded-full text-xs font-semibold ${role === 'ADMIN' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'}`;

    const isVer = parseInt(u.is_verified) === 1;
    const verBadge = document.getElementById('so-verified-badge');
    verBadge.textContent = isVer ? 'Verified' : 'Unverified';
    verBadge.className   = `px-2.5 py-0.5 rounded-full text-xs font-medium ${isVer ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'}`;

    document.getElementById('so-stat-stories').textContent = u.total_stories ?? 0;
    document.getElementById('so-stat-reviews').textContent = u.total_reviews ?? 0;
    const fmt = d => d ? new Date(d).toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'}) : '-';
    document.getElementById('so-stat-joined').textContent  = fmt(u.created_at);

    const bioWrap = document.getElementById('so-bio-wrap');
    if (u.bio) { document.getElementById('so-bio').textContent = u.bio; bioWrap.classList.remove('hidden'); }
    else bioWrap.classList.add('hidden');

    const storySection = document.getElementById('so-recent-stories');
    if (u.recent_stories && u.recent_stories.length > 0) {
        const statusCls = { PUBLISHED:'bg-green-100 text-green-700', DRAFT:'bg-gray-100 text-gray-500', ARCHIVED:'bg-red-100 text-red-600', PENDING_REVIEW:'bg-amber-100 text-amber-700' };
        document.getElementById('so-stories-list').innerHTML = u.recent_stories.map(s =>
            `<div class="flex items-center gap-2 bg-gray-50 rounded-xl px-3 py-2.5">
                <span class="material-symbols-outlined text-gray-400" style="font-size:16px">menu_book</span>
                <p class="text-xs font-medium text-gray-700 flex-1 truncate">${s.title}</p>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold ${statusCls[s.status] || 'bg-gray-100 text-gray-500'}">${s.status}</span>
            </div>`
        ).join('');
        storySection.classList.remove('hidden');
    } else storySection.classList.add('hidden');

    // Edit tab
    const isSelf = (u.id == CUR_USER);
    document.getElementById('so-self-warning').classList.toggle('hidden', !isSelf);
    document.getElementById('so-save-btn').disabled = isSelf;
    document.getElementById('so-save-btn').classList.toggle('opacity-50', isSelf);

    _origRole     = role;
    _origVerified = isVer;
    document.querySelectorAll('.so-role-radio').forEach(r => {
        r.checked = (r.value === _origRole);
        r.addEventListener('change', trackChanges);
    });
    document.getElementById('so-ver-toggle').checked = isVer;
    document.getElementById('so-ver-yes').checked    = isVer;
    document.getElementById('so-ver-no').checked     = !isVer;
    document.getElementById('so-ver-label').textContent = isVer ? 'On' : 'Off';
    document.getElementById('so-ver-toggle').addEventListener('change', trackChanges);

    document.getElementById('so-form').action = `${USER_BASE}update/${u.id}`;
    trackChanges();
}

function syncVerified(toggle) {
    document.getElementById('so-ver-yes').checked = toggle.checked;
    document.getElementById('so-ver-no').checked  = !toggle.checked;
    document.getElementById('so-ver-label').textContent = toggle.checked ? 'On' : 'Off';
    trackChanges();
}

function trackChanges() {
    const newRole = document.querySelector('.so-role-radio:checked')?.value || _origRole;
    const newVer  = document.getElementById('so-ver-toggle').checked;
    const changes = [];
    if (newRole !== _origRole) {
        changes.push(`Role: <b class="text-gray-700">${_origRole}</b> → <b class="text-indigo-700">${newRole}</b>`);
    }
    if (newVer !== _origVerified) {
        changes.push(`Verified: <b class="text-indigo-700">${newVer ? 'Yes ✓' : 'No'}</b>`);
    }
    const summary = document.getElementById('so-change-summary');
    const list    = document.getElementById('so-changes-list');
    if (changes.length) {
        list.innerHTML = changes.map(c => `<p class="text-[11px] text-indigo-700">${c}</p>`).join('');
        summary.classList.remove('hidden');
    } else summary.classList.add('hidden');
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
    verify: {
        title:'Verify User', body:'Akun user ini akan ditandai sebagai terverifikasi.',
        icon:'verified_user', iconBg:'bg-green-100', iconColor:'text-green-600',
        btnBg:'bg-green-600 hover:bg-green-700', btnLabel:'Yes, Verify',
        toast:['User berhasil diverifikasi.', 'success'],
    },
    delete: {
        title:'Delete User Permanently', body:'User ini beserta seluruh datanya akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.',
        icon:'delete_forever', iconBg:'bg-red-100', iconColor:'text-red-600',
        btnBg:'bg-red-600 hover:bg-red-700', btnLabel:'Yes, Delete',
        toast:['User dihapus.', 'error'],
    },
};

function openModal(form, type) {
    _pendingForm = form;
    const cfg = modalConfigs[type];
    document.getElementById('modal-title').textContent  = cfg.title;
    document.getElementById('modal-body').textContent   = cfg.body;
    document.getElementById('modal-icon').textContent   = cfg.icon;
    document.getElementById('modal-icon-wrap').className = `w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 ${cfg.iconBg}`;
    document.getElementById('modal-icon').className     = `material-symbols-outlined text-xl ${cfg.iconColor}`;
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
    $users       = $users ?? [];
    $statTotal   = count($users);
    $statAdmins  = count(array_filter($users, fn($u) => strtoupper($u['role'] ?? '') === 'ADMIN'));
    $statVerified= count(array_filter($users, fn($u) => !empty($u['is_verified'])));
    $statUnverif = $statTotal - $statVerified;
    $statAuthors = count(array_filter($users, fn($u) => ($u['total_stories'] ?? 0) > 0));
?>
<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-indigo-600 text-xl" style="font-variation-settings:'FILL' 1">group</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Total</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statTotal) ?></p></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-purple-600 text-xl" style="font-variation-settings:'FILL' 1">admin_panel_settings</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Admins</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statAdmins) ?></p></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-green-600 text-xl" style="font-variation-settings:'FILL' 1">verified_user</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Verified</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statVerified) ?></p></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-amber-600 text-xl" style="font-variation-settings:'FILL' 1">pending</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Unverified</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statUnverif) ?></p></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-blue-600 text-xl" style="font-variation-settings:'FILL' 1">edit_note</span>
        </div>
        <div><p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide leading-tight">Authors</p>
        <p class="text-xl font-bold text-gray-800"><?= number_format($statAuthors) ?></p></div>
    </div>
</div>

<!-- ═══════════════════ TABLE ═══════════════════ -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-indigo-500 text-xl">group</span>
            Users
        </h3>
        <div class="flex items-center gap-2 flex-wrap">
            <select id="filterRole" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50 text-gray-600">
                <option value="">All Roles</option>
                <option value="USER">User</option>
                <option value="ADMIN">Admin</option>
            </select>
            <select id="filterStatus" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50 text-gray-600">
                <option value="">All Status</option>
                <option value="1">Verified</option>
                <option value="0">Unverified</option>
            </select>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base">search</span>
                <input type="text" id="searchInput" placeholder="Search users..." class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 w-52">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="usersTable">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-medium w-10 text-gray-400">#</th>
                    <th class="px-4 py-3 text-left font-medium">User</th>
                    <th class="px-4 py-3 text-left font-medium">Email</th>
                    <th class="px-4 py-3 text-left font-medium">Role</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                    <th class="px-4 py-3 text-left font-medium">Stories</th>
                    <th class="px-4 py-3 text-left font-medium">Joined</th>
                    <th class="px-4 py-3 text-center font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (!empty($users)): ?>
                    <?php
                        $avatarColors2 = ['bg-indigo-100 text-indigo-700','bg-purple-100 text-purple-700','bg-pink-100 text-pink-700','bg-green-100 text-green-700','bg-amber-100 text-amber-700','bg-teal-100 text-teal-700'];
                    ?>
                    <?php foreach ($users as $i => $user): ?>
                        <?php
                            $role     = strtoupper($user['role'] ?? 'USER');
                            $verified = (int)($user['is_verified'] ?? 0);
                            $isSelf   = ($user['id'] == session()->get('user_id'));
                            $name     = $user['name'] ?? 'User';
                            $initial  = strtoupper(substr($name, 0, 1));
                            $colorCls = $avatarColors2[ord($initial) % count($avatarColors2)];
                        ?>
                        <tr class="hover:bg-gray-50/70 transition-colors"
                            data-role="<?= esc($role) ?>"
                            data-verified="<?= $verified ?>">

                            <td class="px-4 py-3.5 text-gray-300 text-xs font-medium"><?= $i + 1 ?></td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <?php if (!empty($user['profile_photo'])): ?>
                                        <img src="<?= base_url('uploads/' . $user['profile_photo']) ?>"
                                             class="w-8 h-8 rounded-full object-cover flex-shrink-0"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                        <div class="w-8 h-8 rounded-full <?= $colorCls ?> hidden items-center justify-center font-bold text-xs flex-shrink-0"><?= esc($initial) ?></div>
                                    <?php else: ?>
                                        <div class="w-8 h-8 rounded-full <?= $colorCls ?> flex items-center justify-center font-bold text-xs flex-shrink-0"><?= esc($initial) ?></div>
                                    <?php endif; ?>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-800 truncate max-w-[130px]" title="<?= esc($name) ?>"><?= esc($name) ?></p>
                                        <?php if (!empty($user['username'])): ?>
                                            <p class="text-xs text-gray-400">@<?= esc($user['username']) ?></p>
                                        <?php endif; ?>
                                        <?php if ($isSelf): ?>
                                            <p class="text-[10px] text-indigo-400 font-medium">You</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3.5">
                                <span class="text-sm text-gray-500 truncate max-w-[160px] block" title="<?= esc($user['email'] ?? '') ?>"><?= esc($user['email'] ?? '-') ?></span>
                            </td>

                            <td class="px-4 py-3.5">
                                <form action="<?= base_url('/admin/users/role/' . $user['id']) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <select name="role" onchange="showToast('Role diubah ke ' + this.value, 'info'); this.form.submit()"
                                        class="text-xs border border-gray-200 rounded-lg px-2 py-1 focus:outline-none focus:ring-1 focus:ring-indigo-300 <?= $role === 'ADMIN' ? 'text-purple-700 bg-purple-50 border-purple-200' : 'text-gray-600 bg-gray-50' ?>"
                                        <?= $isSelf ? 'disabled title="Tidak dapat mengubah role sendiri"' : '' ?>>
                                        <option value="USER"  <?= $role === 'USER'  ? 'selected' : '' ?>>USER</option>
                                        <option value="ADMIN" <?= $role === 'ADMIN' ? 'selected' : '' ?>>ADMIN</option>
                                    </select>
                                </form>
                            </td>

                            <td class="px-4 py-3.5">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold <?= $verified ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' ?>">
                                    <?= $verified ? 'Verified' : 'Unverified' ?>
                                </span>
                            </td>

                            <td class="px-4 py-3.5">
                                <span class="text-sm font-semibold text-gray-700"><?= number_format($user['total_stories'] ?? 0) ?></span>
                            </td>

                            <td class="px-4 py-3.5 text-xs text-gray-400 whitespace-nowrap">
                                <?= !empty($user['created_at']) ? date('d M Y', strtotime($user['created_at'])) : '—' ?>
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" title="Edit" class="action-btn edit" onclick="openSO(<?= $user['id'] ?>)">
                                        <span class="material-symbols-outlined" style="font-size:18px">open_in_new</span>
                                    </button>

                                    <?php if (!$verified && !$isSelf): ?>
                                        <form action="<?= base_url('/admin/users/verify/' . $user['id']) ?>" method="POST" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="button" title="Verify" class="action-btn verify" onclick="openModal(this.closest('form'), 'verify')">
                                                <span class="material-symbols-outlined" style="font-size:16px">verified_user</span>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if (!$isSelf): ?>
                                        <form action="<?= base_url('/admin/users/delete/' . $user['id']) ?>" method="POST" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="button" title="Delete" class="action-btn delete" onclick="openModal(this.closest('form'), 'delete')">
                                                <span class="material-symbols-outlined" style="font-size:16px">delete</span>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-[10px] text-gray-300 px-2">—</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="px-5 py-16 text-center">
                            <span class="material-symbols-outlined text-5xl text-gray-200 block mb-2">group</span>
                            <p class="text-gray-400 text-sm">No users found</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="px-6 py-3 bg-gray-50 text-xs text-gray-400 border-t border-gray-100 flex items-center justify-between">
        <span>Showing <span class="font-semibold text-gray-600" id="visibleCount"><?= count($users) ?></span> of <?= count($users) ?> users</span>
    </div>
</div>

<script>
function applyFilters() {
    const q      = document.getElementById('searchInput').value.toLowerCase();
    const role   = document.getElementById('filterRole').value;
    const status = document.getElementById('filterStatus').value;
    let visible  = 0;

    document.querySelectorAll('#usersTable tbody tr').forEach(row => {
        const text      = row.textContent.toLowerCase();
        const rowRole   = row.getAttribute('data-role') ?? '';
        const rowVerif  = row.getAttribute('data-verified') ?? '';
        const show = (!q || text.includes(q))
            && (!role   || rowRole === role)
            && (!status || rowVerif === status);
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    document.getElementById('visibleCount').textContent = visible;
}
['searchInput','filterRole','filterStatus'].forEach(id => {
    const el = document.getElementById(id);
    el.addEventListener(id === 'searchInput' ? 'input' : 'change', applyFilters);
});
</script>

<?= $this->endSection() ?>
