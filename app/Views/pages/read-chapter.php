
<?php
// Jika request ingin JSON (misal: ?format=json atau Accept: application/json)
$isJson = false;
if ((isset($_GET['format']) && strtolower($_GET['format']) === 'json') ||
		(isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
		$isJson = true;
}
if ($isJson) {
		header('Content-Type: application/json');
		echo json_encode([
				'chapter' => $chapter,
				'prev_chapter' => $prev_chapter ?? null,
				'next_chapter' => $next_chapter ?? null,
				'comments' => $comments ?? [],
				'total_comments' => $total_comments ?? 0,
		], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
		return;
}
?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php // DEBUG SEMENTARA
// echo '<pre style="background:#fff;color:#000;padding:10px;border:2px solid red;">';
// var_dump($chapter);
// echo '</pre>';
?>
<div class="flex flex-col md:flex-row md:gap-8 max-w-5xl mx-auto py-8">
	<!-- Sidebar Daftar Isi -->
	<aside class="md:w-72 w-full mb-8 md:mb-0">
		<div class="bg-slate-50 border border-border rounded-lg p-4 sticky top-4 max-h-[70vh] overflow-y-auto">
			<h2 class="text-lg font-bold mb-3">Daftar Isi</h2>
			<?php if (!empty($all_chapters)): ?>
				<ol class="space-y-1">
					<?php foreach ($all_chapters as $c): ?>
						<li>
							<a href="<?= base_url('/read-chapter/' . $c['id']) ?>"
								 class="flex items-center gap-2 px-2 py-1 rounded transition-all <?= $c['id'] == $chapter['id'] ? 'bg-accent text-white font-bold shadow' : 'hover:bg-accent/10' ?>">
								<?php if ($c['id'] == $chapter['id']): ?>
									<span class="material-symbols-outlined text-base align-middle">chevron_right</span>
								<?php else: ?>
									<span class="w-4"></span>
								<?php endif; ?>
								<span>Bab <?= (int)$c['chapter_number'] ?>: <?= esc($c['title']) ?></span>
								<?php if (!empty($c['is_premium']) && $c['is_premium']): ?>
									<span class="ml-2 text-xs bg-yellow-300 text-yellow-900 px-1.5 py-0.5 rounded font-semibold">Premium</span>
								<?php endif; ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ol>
			<?php else: ?>
				<p class="text-slate-500 text-sm">Belum ada daftar bab.</p>
			<?php endif; ?>
		</div>
	</aside>
	<!-- Konten Chapter -->
	<div class="flex-1">
		<!-- Reader Controls Floating Panel Trigger -->
		<div class="flex justify-end mb-4">
			<button id="settings-btn" class="p-2.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-primary transition-all duration-200" title="Pengaturan Baca">
				<span class="material-symbols-outlined">tune</span>
			</button>
		</div>
		<!-- Floating Settings Panel -->
		<div id="settings-panel" class="hidden fixed top-24 right-4 z-50 w-80 max-h-[calc(100vh-100px)] overflow-y-auto">
			<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-xl p-6 space-y-6">
				<div class="flex items-center justify-between mb-4">
					<h4 class="font-bold text-lg text-primary dark:text-white">Pengaturan Baca</h4>
					<button id="close-settings" class="text-slate-500 hover:text-slate-700 dark:hover:text-white transition-colors">
						<span class="material-symbols-outlined">close</span>
					</button>
				</div>
				<!-- Font Size -->
				<div>
					<label class="text-sm font-semibold text-slate-700 dark:text-white block mb-3">Ukuran Font</label>
					<div class="flex items-center gap-4">
						<input id="font-size-slider" type="range" min="14" max="24" value="16" class="flex-1 slider h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-accent">
						<span id="font-size-value" class="text-sm font-semibold text-accent min-w-8">16px</span>
					</div>
				</div>
				<!-- Line Height -->
				<div>
					<label class="text-sm font-semibold text-slate-700 dark:text-white block mb-3">Jarak Baris</label>
					<select id="line-height" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg text-sm bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:border-accent">
						<option value="1.5">1.5 - Rapat</option>
						<option value="1.75">1.75 - Default</option>
						<option value="2">2.0 - Longgar</option>
						<option value="2.25">2.25 - Sangat Longgar</option>
					</select>
				</div>
				<!-- Font Type -->
				<div>
					<label class="text-sm font-semibold text-slate-700 dark:text-white block mb-3">Jenis Font</label>
					<div class="flex gap-2">
						<button id="font-sans" class="flex-1 font-btn px-4 py-2 rounded-lg border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-sans font-semibold transition-all hover:border-accent">Sans</button>
						<button id="font-serif" class="flex-1 font-btn px-4 py-2 rounded-lg border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-serif font-semibold transition-all hover:border-accent">Serif</button>
					</div>
				</div>
				<!-- Theme -->
				<div>
					<label class="text-sm font-semibold text-slate-700 dark:text-white block mb-3">Tema</label>
					<div class="flex gap-2">
						<button id="theme-light" class="flex-1 px-4 py-2 rounded-lg border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-semibold transition-all hover:border-accent flex items-center justify-center gap-2">
							<span class="material-symbols-outlined text-base">light_mode</span>
							<span>Terang</span>
						</button>
						<button id="theme-dark" class="flex-1 px-4 py-2 rounded-lg border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-semibold transition-all hover:border-accent flex items-center justify-center gap-2">
							<span class="material-symbols-outlined text-base">dark_mode</span>
							<span>Gelap</span>
						</button>
					</div>
				</div>
				<!-- Reset -->
				<button id="reset-btn" class="w-full px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-white font-semibold transition-all text-sm mt-2">
					Reset ke Default
				</button>
			</div>
		</div>
		<h1 class="text-2xl font-bold mb-2"><?= esc($chapter['title']) ?></h1>
		<p class="text-sm text-slate-500 mb-4">
			Bab <?= (int)$chapter['chapter_number'] ?> • <?= esc($chapter['story_title']) ?>
		</p>
		<article id="chapter-content" class="chapter-content-wrapper mb-10 leading-relaxed text-slate-800 text-base space-y-5 font-serif bg-white dark:bg-slate-900 transition-colors duration-300 rounded p-2">
			<?= nl2br(esc($chapter['content'])) ?>
		</article>
		<div class="flex justify-between mb-8">
			<?php if (!empty($prev_chapter)): ?>
				<a href="<?= base_url('/read-chapter/' . $prev_chapter['id']) ?>" class="text-accent hover:underline">&larr; Sebelumnya</a>
			<?php else: ?>
				<span></span>
			<?php endif; ?>
			<?php if (!empty($next_chapter)): ?>
				<a href="<?= base_url('/read-chapter/' . $next_chapter['id']) ?>" class="text-accent hover:underline">Selanjutnya &rarr;</a>
			<?php endif; ?>
		</div>
		<div class="mb-6">
			<h2 class="text-lg font-semibold mb-2">Komentar (<?= (int)($total_comments ?? 0) ?>)</h2>
			<?php if (!empty($comments)): ?>
				<ul class="space-y-4">
					<?php foreach ($comments as $comment): ?>
						<li class="border-b pb-2">
							<div class="text-sm font-bold text-slate-800"><?= esc($comment['username'] ?? 'Anonim') ?></div>
							<div class="text-xs text-slate-500 mb-1"><?= date('d M Y H:i', strtotime($comment['created_at'])) ?></div>
							<div class="text-slate-700 text-sm"><?= esc($comment['content']) ?></div>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else: ?>
				<p class="text-slate-500 text-sm">Belum ada komentar.</p>
			<?php endif; ?>
		</div>
	</div>
</div>
<?= $this->endSection() ?>

<?php $this->section('additionalScripts'); ?>
<script>
// --- Pengaturan Baca Floating Panel ala example ---
const settingsBtn = document.getElementById('settings-btn');
const settingsPanel = document.getElementById('settings-panel');
const closeSettings = document.getElementById('close-settings');
const fontSizeSlider = document.getElementById('font-size-slider');
const fontSizeValue = document.getElementById('font-size-value');
const lineHeightSelect = document.getElementById('line-height');
const fontSansBtn = document.getElementById('font-sans');
const fontSerifBtn = document.getElementById('font-serif');
const themeLightBtn = document.getElementById('theme-light');
const themeDarkBtn = document.getElementById('theme-dark');
const resetBtn = document.getElementById('reset-btn');
const chapterContent = document.getElementById('chapter-content');
const html = document.documentElement;

function loadSettings() {
	const settings = JSON.parse(localStorage.getItem('readingSettings')) || {
		fontSize: 16,
		lineHeight: 1.75,
		fontType: 'serif',
		theme: 'light'
	};
	applySettings(settings);
}
function applySettings(settings) {
	// Font size
	fontSizeSlider.value = settings.fontSize;
	fontSizeValue.textContent = settings.fontSize + 'px';
	chapterContent.style.fontSize = settings.fontSize + 'px';
	// Line height
	lineHeightSelect.value = settings.lineHeight;
	chapterContent.style.lineHeight = settings.lineHeight;
	// Font type
	chapterContent.classList.remove('font-serif', 'font-sans');
	chapterContent.classList.add(settings.fontType === 'sans' ? 'font-sans' : 'font-serif');
	fontSansBtn.classList.toggle('border-accent', settings.fontType === 'sans');
	fontSansBtn.classList.toggle('bg-accent/10', settings.fontType === 'sans');
	fontSerifBtn.classList.toggle('border-accent', settings.fontType === 'serif');
	fontSerifBtn.classList.toggle('bg-accent/10', settings.fontType === 'serif');
	// Theme
	if (settings.theme === 'dark') {
		html.classList.add('dark');
		themeDarkBtn.classList.add('border-accent', 'bg-accent/10');
		themeLightBtn.classList.remove('border-accent', 'bg-accent/10');
	} else {
		html.classList.remove('dark');
		themeLightBtn.classList.add('border-accent', 'bg-accent/10');
		themeDarkBtn.classList.remove('border-accent', 'bg-accent/10');
	}
}
function saveSettings() {
	const settings = {
		fontSize: parseInt(fontSizeSlider.value),
		lineHeight: parseFloat(lineHeightSelect.value),
		fontType: chapterContent.classList.contains('font-sans') ? 'sans' : 'serif',
		theme: html.classList.contains('dark') ? 'dark' : 'light'
	};
	localStorage.setItem('readingSettings', JSON.stringify(settings));
}
settingsBtn.addEventListener('click', () => {
	settingsPanel.classList.toggle('hidden');
});
closeSettings.addEventListener('click', () => {
	settingsPanel.classList.add('hidden');
});
fontSizeSlider.addEventListener('input', (e) => {
	fontSizeValue.textContent = e.target.value + 'px';
	chapterContent.style.fontSize = e.target.value + 'px';
	saveSettings();
});
lineHeightSelect.addEventListener('change', (e) => {
	chapterContent.style.lineHeight = e.target.value;
	saveSettings();
});
fontSansBtn.addEventListener('click', () => {
	chapterContent.classList.remove('font-serif');
	chapterContent.classList.add('font-sans');
	fontSansBtn.classList.add('border-accent', 'bg-accent/10');
	fontSerifBtn.classList.remove('border-accent', 'bg-accent/10');
	saveSettings();
});
fontSerifBtn.addEventListener('click', () => {
	chapterContent.classList.add('font-serif');
	chapterContent.classList.remove('font-sans');
	fontSerifBtn.classList.add('border-accent', 'bg-accent/10');
	fontSansBtn.classList.remove('border-accent', 'bg-accent/10');
	saveSettings();
});
themeLightBtn.addEventListener('click', () => {
	html.classList.remove('dark');
	themeLightBtn.classList.add('border-accent', 'bg-accent/10');
	themeDarkBtn.classList.remove('border-accent', 'bg-accent/10');
	saveSettings();
});
themeDarkBtn.addEventListener('click', () => {
	html.classList.add('dark');
	themeDarkBtn.classList.add('border-accent', 'bg-accent/10');
	themeLightBtn.classList.remove('border-accent', 'bg-accent/10');
	saveSettings();
});
resetBtn.addEventListener('click', () => {
	localStorage.removeItem('readingSettings');
	location.reload();
});
// Close settings panel when clicking outside
document.addEventListener('click', (e) => {
	if (!settingsPanel.contains(e.target) && !settingsBtn.contains(e.target)) {
		settingsPanel.classList.add('hidden');
	}
});
// Load settings on page load
loadSettings();
</script>
<?php $this->endSection(); ?>
