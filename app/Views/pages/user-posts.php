<?php
// Tampilan daftar postingan milik user/author saja
?>
<div class="mb-8">
    <div class="space-y-4">
        <?php
        $myPosts = array_filter($posts ?? [], function($p) use ($author) {
            return $p['user_id'] == $author['id'];
        });
        ?>
        <?php if (!empty($myPosts)): ?>
            <?php foreach($myPosts as $post): ?>
                <div class="bg-white border border-slate-100 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center gap-2 mb-2">
                        <?php if (!empty($author['profile_photo'])): ?>
                            <img src="<?= profile_url($author['profile_photo']) ?>" alt="<?= esc($author['name']) ?>" class="w-8 h-8 rounded-full object-cover border border-slate-200" />
                        <?php else: ?>
                            <span class="material-symbols-outlined text-purple-500">person</span>
                        <?php endif; ?>
                        <span class="font-semibold text-slate-900 text-sm"><?= esc($author['name']) ?></span>
                        <span class="text-xs text-slate-400 ml-2"><?= date('d M Y H:i', strtotime($post['created_at'])) ?></span>
                    </div>
                    <div class="text-slate-800 text-sm mb-2">
                        <?= nl2br(esc($post['content'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bg-slate-50 rounded-xl p-8 text-center border border-slate-200">
                <span class="material-symbols-outlined text-4xl text-slate-300 block mb-2">chat</span>
                <p class="text-slate-600 text-base font-medium mb-1">Belum ada postingan</p>
                <p class="text-slate-500 text-sm">User ini belum membuat postingan apapun</p>
            </div>
        <?php endif; ?>
    </div>
</div>
