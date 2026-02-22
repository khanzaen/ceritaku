<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<main class="max-w-6xl mx-auto px-6 py-10">
	<h1 class="text-3xl font-bold text-primary mb-8">All stories</h1>
	<?php if (!empty($stories) && is_array($stories)): ?>
		<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
			<?php foreach ($stories as $story): ?>
				<a href="<?= base_url('/story/' . $story['id']) ?>" class="block">
					<article class="p-5 bg-white border border-border rounded-2xl shadow-sm hover:shadow-md transition-shadow flex gap-4">
						<div class="w-20 flex-none">
							<div class="aspect-[2/3] bg-slate-100 rounded-lg overflow-hidden book-card-shadow">
								<?php if (!empty($story['cover_image'])): ?>
									<img src="<?= cover_url($story['cover_image']) ?>" alt="<?= esc($story['title']) ?>" class="w-full h-full object-cover" />
								<?php else: ?>
									<div class="w-full h-full bg-gradient-to-br from-purple-200 to-purple-100 flex items-center justify-center">
										<span class="material-symbols-outlined text-purple-400 text-[24px]">auto_stories</span>
									</div>
								<?php endif; ?>
							</div>
						</div>
						<div class="flex flex-col">
							<div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
								<span class="px-2 py-0.5 bg-slate-100 rounded uppercase tracking-widest font-bold"><?= esc(trim(explode(', ', $story['genres'] ?? 'Fiction')[0])) ?></span>
							</div>
							<h3 class="text-lg font-bold text-primary leading-tight line-clamp-2"><?= esc($story['title']) ?></h3>
							<p class="text-xs text-slate-500 mb-2">
								<span class="text-amber-600 font-semibold"><?= number_format($story['avg_rating'] ?? 0, 1) ?></span>
								<span class="text-slate-400">| <?= number_format($story['total_views'] ?? 0) ?> reads</span>
							</p>
							<p class="text-xs text-slate-500 mb-2">by <?= esc($story['author_name'] ?? 'Unknown') ?></p>
							<p class="text-sm text-slate-600 line-clamp-3 italic"><?= esc(substr($story['description'] ?? '', 0, 100)) ?>...</p>
						</div>
					</article>
				</a>
			<?php endforeach; ?>
		</div>
	<?php else: ?>
		<div class="text-center py-12 bg-slate-50 rounded-xl border border-border">
			<span class="material-symbols-outlined text-5xl text-slate-300 mb-4 inline-block">search</span>
			<p class="text-slate-600 font-medium">Belum ada cerita yang tersedia.</p>
		</div>
	<?php endif; ?>
</main>

<?= $this->endSection() ?>
