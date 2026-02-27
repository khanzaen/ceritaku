<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<!-- ═══════════════════ TOAST ═══════════════════ -->
<div id="toast-container" class="fixed top-6 right-6 z-50 flex flex-col gap-2 pointer-events-none"></div>
<style>
@keyframes toast-in  { 0% { opacity:0; transform:translateX(110%); } 100% { opacity:1; transform:translateX(0); } }
@keyframes toast-out { 0% { opacity:1; transform:translateX(0); }   100% { opacity:0; transform:translateX(110%); } }
.toast         { pointer-events:all; animation: toast-in  0.35s cubic-bezier(.4,0,.2,1) forwards; }
.toast.hiding  { animation: toast-out 0.3s  cubic-bezier(.4,0,.2,1) forwards; }
</style>
<script>
function showToast(message, type = 'success', detail = '') {
    const cfg = {
        success: { bg:'bg-green-50',  border:'border-green-200',  text:'text-green-700',  icon:'check_circle', iconColor:'text-green-500'  },
        error:   { bg:'bg-red-50',    border:'border-red-200',    text:'text-red-700',    icon:'error',        iconColor:'text-red-500'    },
        warning: { bg:'bg-amber-50',  border:'border-amber-200',  text:'text-amber-700',  icon:'warning',      iconColor:'text-amber-500'  },
        info:    { bg:'bg-indigo-50', border:'border-indigo-200', text:'text-indigo-700', icon:'info',         iconColor:'text-indigo-500' },
    };
    const c = cfg[type] ?? cfg.success;
    const el = document.createElement('div');
    el.className = `toast ${c.bg} ${c.border} border rounded-xl shadow-lg px-4 py-3 flex items-start gap-3 min-w-[280px] max-w-sm`;
    el.innerHTML = `
        <span class="material-symbols-outlined ${c.iconColor} text-xl flex-shrink-0 mt-0.5" style="font-variation-settings:'FILL' 1">${c.icon}</span>
        <div class="flex-1 min-w-0">
            <p class="${c.text} text-sm font-semibold leading-snug">${message}</p>
            ${detail ? `<p class="${c.text} text-xs opacity-70 mt-0.5">${detail}</p>` : ''}
        </div>
        <button onclick="dismissToast(this.parentElement)" class="${c.text} opacity-50 hover:opacity-100 transition text-lg leading-none flex-shrink-0 mt-0.5">&times;</button>`;
    document.getElementById('toast-container').appendChild(el);
    setTimeout(() => dismissToast(el), 4000);
}
function dismissToast(el) {
    if (!el || el.classList.contains('hiding')) return;
    el.classList.add('hiding');
    setTimeout(() => el.remove(), 300);
}
<?php if (session()->getFlashdata('success')): ?>
window.addEventListener('DOMContentLoaded', () => showToast(<?= json_encode(session()->getFlashdata('success')) ?>, 'success', 'The action was completed successfully.'));
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
window.addEventListener('DOMContentLoaded', () => showToast(<?= json_encode(session()->getFlashdata('error')) ?>, 'error', 'Something went wrong. Please try again.'));
<?php endif; ?>
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
const modalConfigs = {
    approve: { title:'Publish Story', body:'This story will be published and visible to all readers immediately.', icon:'check_circle', iconBg:'bg-green-100', iconColor:'text-green-600', btnBg:'bg-green-600 hover:bg-green-700', btnLabel:'Yes, Publish',  toast:['Story published.','success','The story is now live.'] },
    archive: { title:'Archive Story',  body:'This story will be hidden from readers. You can restore it later.', icon:'archive',       iconBg:'bg-amber-100', iconColor:'text-amber-600', btnBg:'bg-amber-500 hover:bg-amber-600', btnLabel:'Yes, Archive',  toast:['Story archived.','warning','The story is now hidden.'] },
    delete:  { title:'Delete Story',   body:'This will permanently delete the story and ALL its chapters. Cannot be undone.', icon:'delete_forever', iconBg:'bg-red-100', iconColor:'text-red-600', btnBg:'bg-red-600 hover:bg-red-700', btnLabel:'Yes, Delete', toast:['Story deleted.','error','Removed permanently.'] },
};
function openModal(form, type) {
    const cfg = modalConfigs[type];
    document.getElementById('modal-title').textContent   = cfg.title;
    document.getElementById('modal-body').textContent    = cfg.body;
    document.getElementById('modal-icon').textContent    = cfg.icon;
    document.getElementById('modal-icon-wrap').className = `w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 ${cfg.iconBg}`;
    document.getElementById('modal-icon').className      = `material-symbols-outlined text-xl ${cfg.iconColor}`;
    const btn = document.getElementById('modal-confirm-btn');
    btn.textContent = cfg.btnLabel;
    btn.className   = `px-4 py-2 text-sm rounded-xl font-semibold text-white transition ${cfg.btnBg}`;
    btn.onclick     = () => { const t=cfg.toast; closeModal(); showToast(t[0],t[1],t[2]); setTimeout(()=>form.submit(),350); };
    const modal = document.getElementById('confirm-modal');
    modal.classList.remove('hidden'); modal.classList.add('flex');
}
function closeModal() {
    const modal = document.getElementById('confirm-modal');
    modal.classList.add('hidden'); modal.classList.remove('flex');
}
document.getElementById('confirm-modal').addEventListener('click', function(e){ if(e.target===this) closeModal(); });
</script>

<?php
    $story  = $story  ?? [];
    $status = $story['status'] ?? 'DRAFT';
    $isFeatured = !empty($story['is_featured']);
    $badgeMap = [
        'PUBLISHED'      => 'bg-green-100 text-green-700',
        'PENDING_REVIEW' => 'bg-amber-100 text-amber-700',
        'DRAFT'          => 'bg-gray-100 text-gray-500',
        'ARCHIVED'       => 'bg-red-100 text-red-600',
    ];
    $badge       = $badgeMap[$status] ?? 'bg-gray-100 text-gray-500';
    $statusLabel = ucfirst(strtolower(str_replace('_', ' ', $status)));
?>

<!-- Back Button -->
<div class="mb-5">
    <a href="<?= base_url('/admin/stories') ?>"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 transition font-medium">
        <span class="material-symbols-outlined text-base">arrow_back</span>
        Back to Stories
    </a>
</div>

<!-- ═══════════════════ STORY HEADER CARD ═══════════════════ -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
    <div class="flex flex-col sm:flex-row gap-6 p-6">

        <!-- Cover -->
        <div class="flex-shrink-0">
            <?php if (!empty($story['cover_image'])): ?>
                <img src="<?= base_url('uploads/' . $story['cover_image']) ?>"
                     class="w-28 h-40 object-cover rounded-xl shadow-md"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                <div style="display:none" class="w-28 h-40 bg-purple-50 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-purple-300 text-4xl">menu_book</span>
                </div>
            <?php else: ?>
                <div class="w-28 h-40 bg-purple-50 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-purple-300 text-4xl">menu_book</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Info -->
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-3 flex-wrap mb-2">
                <h1 class="text-xl font-bold text-gray-800 leading-snug"><?= esc($story['title'] ?? 'Untitled') ?></h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold flex-shrink-0 <?= $badge ?>"><?= esc($statusLabel) ?></span>
            </div>

            <p class="text-sm text-gray-500 mb-3">
                by <span class="font-medium text-gray-700"><?= esc($story['author_name'] ?? '-') ?></span>
            </p>

            <?php if (!empty($story['synopsis'])): ?>
                <p class="text-sm text-gray-600 leading-relaxed line-clamp-3 mb-4"><?= esc($story['synopsis']) ?></p>
            <?php endif; ?>

            <!-- Meta chips -->
            <div class="flex flex-wrap gap-2 text-xs mb-4">
                <?php if (!empty($story['genres'])): ?>
                    <span class="px-2.5 py-1 bg-purple-50 text-purple-600 rounded-lg font-medium"><?= esc($story['genres']) ?></span>
                <?php endif; ?>
                <?php if ($isFeatured): ?>
                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-600 rounded-lg font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1">star</span> Featured
                    </span>
                <?php endif; ?>
                <span class="px-2.5 py-1 bg-gray-50 text-gray-500 rounded-lg">
                    Created: <?= !empty($story['created_at']) ? date('d M Y', strtotime($story['created_at'])) : '-' ?>
                </span>
                <?php if (!empty($story['updated_at'])): ?>
                    <span class="px-2.5 py-1 bg-gray-50 text-gray-500 rounded-lg">
                        Updated: <?= date('d M Y', strtotime($story['updated_at'])) ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Action buttons -->
            <div class="flex flex-wrap gap-2">
                <?php if ($status === 'PENDING_REVIEW'): ?>
                    <form action="<?= base_url('/admin/stories/approve/' . ($story['id'] ?? 0)) ?>" method="POST" class="inline">
                        <?= csrf_field() ?>
                        <button type="button"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold bg-green-600 text-white hover:bg-green-700 transition"
                            onclick="openModal(this.closest('form'), 'approve')">
                            <span class="material-symbols-outlined text-base">check_circle</span> Publish
                        </button>
                    </form>
                <?php endif; ?>

                <?php if ($status !== 'ARCHIVED'): ?>
                    <form action="<?= base_url('/admin/stories/archive/' . ($story['id'] ?? 0)) ?>" method="POST" class="inline">
                        <?= csrf_field() ?>
                        <button type="button"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold bg-amber-100 text-amber-700 hover:bg-amber-200 transition"
                            onclick="openModal(this.closest('form'), 'archive')">
                            <span class="material-symbols-outlined text-base">archive</span> Archive
                        </button>
                    </form>
                <?php endif; ?>

                <!-- Toggle Featured -->
                <form action="<?= base_url('/admin/stories/toggle-featured/' . ($story['id'] ?? 0)) ?>" method="POST" class="inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="is_featured" value="<?= $isFeatured ? 0 : 1 ?>">
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold transition
                               <?= $isFeatured ? 'bg-indigo-100 text-indigo-700 hover:bg-indigo-200' : 'bg-gray-100 text-gray-500 hover:bg-indigo-100 hover:text-indigo-700' ?>">
                        <span class="material-symbols-outlined text-base" style="font-variation-settings:'FILL' <?= $isFeatured ? 1 : 0 ?>">star</span>
                        <?= $isFeatured ? 'Unfeature' : 'Set Featured' ?>
                    </button>
                </form>

                <form action="<?= base_url('/admin/stories/delete/' . ($story['id'] ?? 0)) ?>" method="POST" class="inline">
                    <?= csrf_field() ?>
                    <button type="button"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold bg-red-50 text-red-600 hover:bg-red-100 transition"
                        onclick="openModal(this.closest('form'), 'delete')">
                        <span class="material-symbols-outlined text-base">delete</span> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════ CHAPTERS LIST ═══════════════════ -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-500 text-xl">article</span>
            Chapters
            <span class="ml-1 px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 text-xs font-semibold"><?= count($chapters ?? []) ?></span>
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left font-medium w-10">#</th>
                    <th class="px-5 py-3 text-left font-medium">Chapter Title</th>
                    <th class="px-5 py-3 text-left font-medium">Date</th>
                    <th class="px-5 py-3 text-center font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (!empty($chapters)): ?>
                    <?php foreach ($chapters as $i => $chapter): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 text-gray-400"><?= $i + 1 ?></td>
                            <td class="px-5 py-3 font-medium text-gray-800">
                                <span class="truncate max-w-[320px] block" title="<?= esc($chapter['title']) ?>"><?= esc($chapter['title']) ?></span>
                            </td>
                            <td class="px-5 py-3 text-gray-400 text-xs whitespace-nowrap">
                                <?= !empty($chapter['created_at']) ? date('d M Y', strtotime($chapter['created_at'])) : '-' ?>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-center">
                                    <form action="<?= base_url('/admin/chapters/delete/' . $chapter['id']) ?>" method="POST" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" title="Delete chapter"
                                            class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 transition"
                                            onclick="return confirm('Delete this chapter? This cannot be undone.')">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center">
                            <span class="material-symbols-outlined text-4xl text-gray-200 block mb-2">article</span>
                            <p class="text-gray-400 text-sm">No chapters yet</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>