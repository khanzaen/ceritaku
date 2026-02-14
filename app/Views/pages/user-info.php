<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-6xl mx-auto px-6 py-8">
    <!-- Breadcrumb -->
    <nav class="text-xs text-slate-500 mb-6">
        <a href="<?= base_url() ?>" class="hover:text-slate-900">Beranda</a>
        <span class="mx-2">/</span>
        <span class="text-slate-700">Profile Author</span>
    </nav>

    <!-- Author Header -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 md:p-8 mb-8">
        <div class="flex flex-col md:flex-row gap-6 md:gap-8 items-center md:items-start">
            <!-- Profile Photo -->
            <div class="flex-none">
                <div class="w-32 h-32 md:w-40 md:h-40 rounded-full bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center overflow-hidden border-4 border-white shadow-lg">
                    <?php if (!empty($author['profile_photo'])): ?>
                        <img src="<?= profile_url($author['profile_photo']) ?>" alt="<?= esc($author['name']) ?>" class="w-full h-full object-cover" />
                    <?php else: ?>
                        <?php
                        $names = explode(' ', trim($author['name']));
                        $initials = '';
                        if (count($names) >= 2) {
                            $initials = strtoupper(substr($names[0], 0, 1) . substr($names[count($names) - 1], 0, 1));
                        } else {
                            $initials = strtoupper(substr($author['name'], 0, 2));
                        }
                        ?>
                        <span class="text-4xl font-bold text-purple-600"><?= $initials ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Author Info -->
            <div class="flex-1 text-center md:text-left w-full">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center justify-center md:justify-start gap-2 mb-2">
                            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-slate-900"><?= esc($author['name']) ?></h1>
                            <?php if (!empty($author['is_verified']) && $author['is_verified']): ?>
                                <span class="material-symbols-outlined text-blue-500 text-xl md:text-2xl" title="Verified Author">verified</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-sm text-slate-500 mb-3">@<?= esc($author['username']) ?></p>
                        
                        <?php if (!empty($author['bio'])): ?>
                            <p class="text-slate-700 leading-relaxed mb-4 max-w-2xl"><?= nl2br(esc($author['bio'])) ?></p>
                        <?php else: ?>
                            <p class="text-slate-400 italic mb-4">Author belum menambahkan bio</p>
                        <?php endif; ?>

                        <div class="flex items-center justify-center md:justify-start gap-1 text-sm text-slate-500">
                            <span class="material-symbols-outlined text-sm">calendar_today</span>
                            <span>Bergabung sejak <?= date('F Y', strtotime($author['created_at'])) ?></span>
                        </div>
                    </div>

                    <!-- Action Buttons -->

                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mt-8 pt-6 border-t border-slate-200">
            <div class="text-center">
                <p class="text-2xl md:text-3xl font-bold text-slate-900"><?= number_format($total_stories ?? 0) ?></p>
                <p class="text-xs md:text-sm text-slate-600 mt-1">Cerita</p>
            </div>
            <div class="text-center">
                <p class="text-2xl md:text-3xl font-bold text-slate-900"><?= number_format($total_reads ?? 0) ?></p>
                <p class="text-xs md:text-sm text-slate-600 mt-1">Total Dibaca</p>
            </div>
        </div>
    </div>

    <!-- Menu Navigasi Daftar Cerita & Postingan -->
    <div class="mb-8">
        <div class="flex gap-2 border-b border-slate-200" id="nav-tabs">
            <button type="button" id="tab-stories" class="px-4 py-2 font-semibold text-slate-700 border-b-2 border-purple-600 bg-white focus:outline-none" onclick="showSection('stories')">Daftar Cerita</button>
            <button type="button" id="tab-posts" class="px-4 py-2 font-semibold text-slate-700 border-b-2 border-transparent bg-white focus:outline-none" onclick="showSection('posts')">Postingan</button>
        </div>
    </div>
    <div id="section-stories">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-slate-900">
                Cerita oleh <?= esc($author['name']) ?>
            </h2>
            <?php if (!empty($stories)): ?>
                <span class="text-sm text-slate-500"><?= count($stories) ?> cerita</span>
            <?php endif; ?>
        </div>
        <?php if (!empty($stories)): ?>
            <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 md:gap-4">
                <?php foreach($stories as $story): ?>
                    <article class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all group border border-slate-100">
                        <!-- Cover -->
                        <a href="<?= base_url('/story/' . $story['id']) ?>" class="block">
                            <div class="aspect-[2/3] overflow-hidden bg-slate-100 relative">
                                <?php if (!empty($story['cover_image'])): ?>
                                    <img src="<?= cover_url($story['cover_image']) ?>" alt="<?= esc($story['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-purple-200">
                                        <span class="material-symbols-outlined text-3xl md:text-5xl text-purple-400">menu_book</span>
                                    </div>
                                <?php endif; ?>
                                <!-- Status Badge -->
                                <?php if ($story['status'] !== 'PUBLISHED'): ?>
                                    <div class="absolute top-1.5 right-1.5">
                                        <span class="px-1.5 py-0.5 bg-black/70 backdrop-blur text-white text-[9px] font-semibold rounded">
                                            <?php 
                                                $statusLabel = [
                                                    'DRAFT' => 'Draft',
                                                    'PENDING_REVIEW' => 'Review',
                                                    'ARCHIVED' => 'Archived'
                                                ];
                                                echo $statusLabel[$story['status']] ?? $story['status'];
                                            ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </a>
                        <!-- Content -->
                        <div class="p-2 md:p-3">
                            <div class="mb-1.5">
                                <span class="text-[9px] md:text-[10px] font-bold uppercase tracking-wider text-purple-600 bg-purple-50 px-1.5 py-0.5 rounded">
                                    <?= esc(trim(explode(',', $story['genres'])[0])) ?>
                                </span>
                            </div>
                            <h3 class="text-xs md:text-sm font-bold text-slate-900 line-clamp-2 group-hover:text-accent transition-colors mb-1.5 leading-tight">
                                <a href="<?= base_url('/story/' . $story['id']) ?>">
                                    <?= esc($story['title']) ?>
                                </a>
                            </h3>
                            <!-- Story Stats -->
                            <div class="flex items-center gap-2 text-[10px] text-slate-500">
                                <?php if (!empty($story['total_views'])): ?>
                                    <span class="flex items-center gap-0.5">
                                        <span class="material-symbols-outlined text-xs">visibility</span>
                                        <?= number_format($story['total_views']) ?>
                                    </span>
                                <?php endif; ?>
                                <?php if (!empty($story['avg_rating'])): ?>
                                    <span class="flex items-center gap-0.5 text-amber-600">
                                        <span class="material-symbols-outlined text-xs">star</span>
                                        <?= number_format($story['avg_rating'], 1) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-slate-50 rounded-xl p-12 md:p-16 text-center border border-slate-200">
                <span class="material-symbols-outlined text-5xl md:text-6xl text-slate-300 block mb-4">library_books</span>
                <p class="text-slate-600 text-base md:text-lg font-medium mb-2">Belum Ada Cerita</p>
                <p class="text-slate-500 text-sm">Penulis ini belum mempublikasikan cerita apapun</p>
            </div>
        <?php endif; ?>
    </div>
    <!-- Section: Postingan User (include user-posts.php) -->
    <div id="section-posts" style="display:none">
        <?php include(APPPATH . 'Views/pages/user-posts.php'); ?>
    </div>
    <script>
    function showSection(section) {
        document.getElementById('section-stories').style.display = section === 'stories' ? '' : 'none';
        document.getElementById('section-posts').style.display = section === 'posts' ? '' : 'none';
        // Highlight tab aktif
        document.getElementById('tab-stories').classList.remove('border-purple-600');
        document.getElementById('tab-stories').classList.add('border-transparent');
        document.getElementById('tab-posts').classList.remove('border-purple-600');
        document.getElementById('tab-posts').classList.add('border-transparent');
        if (section === 'stories') {
            document.getElementById('tab-stories').classList.remove('border-transparent');
            document.getElementById('tab-stories').classList.add('border-purple-600');
        } else {
            document.getElementById('tab-posts').classList.remove('border-transparent');
            document.getElementById('tab-posts').classList.add('border-purple-600');
        }
    }
    // Set default tab
    showSection('stories');
    </script>
</div>

<?= $this->endSection() ?>
