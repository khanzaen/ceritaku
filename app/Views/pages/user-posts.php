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
                <div class="bg-white border border-slate-100 rounded-lg p-4 shadow-sm mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <?php
                        // Ambil foto profil dari data user di post jika ada, fallback ke author
                        $profilePhoto = $post['profile_photo'] ?? $author['profile_photo'] ?? null;
                        $profileName = $post['user_name'] ?? $author['name'];
                        ?>
                        <?php if (!empty($profilePhoto)): ?>
                            <img src="<?= profile_url($profilePhoto) ?>" alt="<?= esc($profileName) ?>" class="w-8 h-8 rounded-full object-cover border border-slate-200" />
                        <?php else: ?>
                            <span class="material-symbols-outlined text-purple-500">person</span>
                        <?php endif; ?>
                        <span class="font-semibold text-slate-900 text-sm"><?= esc($profileName) ?></span>
                        <span class="text-xs text-slate-400 ml-2"><?= date('d M Y H:i', strtotime($post['created_at'])) ?></span>
                    </div>
                    <div class="text-slate-800 text-sm mb-2">
                        <?= nl2br(esc($post['content'])) ?>
                    </div>

                    <?php
                    $commentModel = new \App\Models\UserPostCommentModel();
                    $totalComments = $commentModel->getTotalCommentsByPost($post['id']);
                    ?>
                    <div class="flex items-center gap-2 mt-3 text-xs text-slate-500">
                        <span class="material-symbols-outlined text-base align-middle">chat_bubble</span>
                        <span><?= $totalComments ?></span>
                    </div>
                    <style>
                    .like-btn:hover span {
                        color: #a855f7 !important;
                    }
                    .like-btn:active span {
                        color: #9333ea !important;
                    }
                    </style>
                    <script>
                    document.querySelectorAll('.like-form').forEach(function(form) {
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            var postId = form.getAttribute('data-post-id');
                            var formData = new FormData(form);
                            fetch('/user-post/like', {
                                method: 'POST',
                                body: formData,
                                headers: {'X-Requested-With': 'XMLHttpRequest'}
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    var btn = form.querySelector('.like-btn span');
                                    var count = form.querySelector('.like-count');
                                    if (data.liked) {
                                        btn.style.color = '#a855f7';
                                        count.textContent = parseInt(count.textContent) + 1;
                                    } else {
                                        btn.style.color = '#64748b';
                                        count.textContent = Math.max(0, parseInt(count.textContent) - 1);
                                    }
                                }
                            });
                        });
                    });
                    </script>
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
