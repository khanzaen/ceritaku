<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="max-w-4xl mx-auto px-6 py-10">

    <!-- Flash messages -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('errors')): ?>
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <ul class="list-disc list-inside">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="mb-8">
        <a href="<?= site_url('my-stories') ?>" class="inline-flex items-center gap-1 text-sm text-slate-600 hover:text-accent transition-colors mb-4">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Back to My Stories
        </a>
        <h1 class="text-3xl md:text-4xl font-bold text-primary mb-2">Edit Story</h1>
        <p class="text-slate-600">Update your story details below.</p>
    </div>

    <form action="<?= site_url('story/update/' . $story['id']) ?>" method="post" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>

        <!-- Container for chapter delete IDs (populated by JS) -->
        <div id="delete-ids-container"></div>

        <!-- Story Detail (single card) -->
        <div class="bg-white border border-border rounded-2xl p-6 shadow-sm space-y-6">
            <h3 class="text-sm font-bold text-primary">Story Details</h3>

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-bold text-primary mb-2">Story Title *</label>
                <input type="text" id="title" name="title"
                       value="<?= old('title', $story['title']) ?>"
                       placeholder="Enter your story title"
                       class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm"
                       required maxlength="120" />
            </div>

            <!-- Description -->
            <div>
                <label for="synopsis" class="block text-sm font-bold text-primary mb-2">Story Description *</label>
                <textarea id="synopsis" name="synopsis" rows="4"
                          placeholder="Write a brief description of your story..."
                          class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm resize-none"
                          required maxlength="1000"><?= old('synopsis', $story['description']) ?></textarea>
            </div>

            <!-- Genre -->
            <div>
                <label class="block text-sm font-bold text-primary mb-3">Genre *</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <?php
                    $genres    = ['Romance','Mystery','Fantasy','Drama','Sci-Fi','Thriller','Comedy','Politics','History','Adventure','Horror','Paranormal','Supernatural'];
                    $oldGenres = old('genre', $story['genres_array'] ?? []);
                    $oldGenres = array_map('strtolower', (array)$oldGenres);
                    foreach($genres as $genre):
                        $genreLower = strtolower($genre);
                    ?>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="genre[]" value="<?= $genreLower ?>"
                                   <?= in_array($genreLower, $oldGenres) ? 'checked' : '' ?>
                                   class="w-4 h-4 text-accent rounded focus:ring-2 focus:ring-accent/30" />
                            <span class="text-sm text-slate-700"><?= $genre ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Cover -->
            <div>
                <label class="block text-sm font-bold text-primary mb-3">Cover Image</label>
                <div class="flex gap-4 items-start">
                    <?php if($story['cover_image'] && file_exists(FCPATH . 'uploads/' . $story['cover_image'])): ?>
                        <img id="cover-preview-img"
                             src="<?= base_url('uploads/' . $story['cover_image']) ?>"
                             alt="Current cover"
                             class="w-24 h-32 object-cover rounded-lg border border-border flex-shrink-0">
                    <?php else: ?>
                        <div class="w-24 h-32 rounded-lg border border-border bg-slate-50 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-slate-300 text-3xl">image</span>
                        </div>
                    <?php endif; ?>
                    <div class="flex-1">
                        <div id="cover-upload-area" class="border-2 border-dashed border-border rounded-lg p-5 text-center hover:border-accent transition-colors cursor-pointer">
                            <span id="cover-icon" class="material-symbols-outlined text-3xl text-slate-400 mb-1 block">upload</span>
                            <p id="cover-label-text" class="text-sm text-slate-600 mb-1">Click to upload new cover</p>
                            <p id="cover-sub-text" class="text-xs text-slate-500">PNG, JPG up to 5MB</p>
                            <input type="file" name="cover" accept="image/*" id="cover-upload" class="hidden" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Publication Status -->
            <div>
                <label class="block text-sm font-bold text-primary mb-3">Publication Status *</label>
                <div class="flex flex-wrap gap-3">
                    <?php foreach(['Ongoing','Completed','On Hiatus'] as $status): ?>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="publication_status" value="<?= $status ?>"
                                   <?= (old('publication_status', $story['publication_status']) == $status) ? 'checked' : '' ?>
                                   class="w-4 h-4 text-accent focus:ring-2 focus:ring-accent/30" />
                            <span class="text-sm text-slate-700"><?= $status ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Chapters CRUD Section -->
        <div class="bg-gradient-to-br from-purple-50 to-white border border-border rounded-2xl p-6 shadow-sm mt-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-primary">Chapters</h3>
                <span class="text-xs text-slate-500">Add, edit, or remove chapters for this story</span>
            </div>

            <!-- Published Chapters (editable cards) -->
            <?php if (!empty($published_chapters)): ?>
                <?php foreach ($published_chapters as $pidx => $pub): ?>
                    <?php $idx = 'pub_' . $pidx; ?>
                    <div class="chapter-block border border-border rounded-xl bg-white overflow-hidden mb-2" data-chapter-id="<?= $pub['id'] ?>">

                        <!-- Hidden fields -->
                        <input type="hidden" name="existing_chapter_id[<?= $idx ?>]"     value="<?= $pub['id'] ?>" />
                        <input type="hidden" name="existing_chapter_status[<?= $idx ?>]" value="PUBLISHED" class="status-hidden" />

                        <!-- Card header -->
                        <div class="chapter-card-header flex items-center gap-3 px-4 py-3 cursor-pointer select-none" onclick="toggleChapter(this)">
                            <div class="w-7 h-7 rounded-full bg-accent flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-[11px] font-bold"><?= $pub['chapter_number'] ?></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-primary truncate chapter-title-display">
                                    <?= esc($pub['title']) ?>
                                </p>
                                <p class="text-[10px] text-slate-400">
                                    🟢 Published · Updated <?= date('d M Y', strtotime($pub['updated_at'])) ?>
                                </p>
                            </div>
                            <div class="flex items-center gap-1 flex-shrink-0">
                                <label class="flex items-center gap-1 cursor-pointer text-[10px] text-slate-400 mr-1" onclick="event.stopPropagation()">
                                    <input type="checkbox" class="w-3 h-3 rounded publish-toggle" checked />
                                    Publish
                                </label>
                                <button type="button"
                                        class="edit-chapter-btn w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-100 transition-colors text-slate-400 hover:text-accent"
                                        onclick="event.stopPropagation(); toggleChapter(this.closest('.chapter-block').querySelector('.chapter-card-header'))"
                                        title="Edit chapter">
                                    <span class="material-symbols-outlined" style="font-size:18px">edit</span>
                                </button>
                                <button type="button"
                                        class="delete-chapter-btn w-8 h-8 rounded-lg flex items-center justify-center hover:bg-red-50 transition-colors text-slate-400 hover:text-red-500"
                                        onclick="event.stopPropagation()"
                                        title="Delete chapter">
                                    <span class="material-symbols-outlined" style="font-size:18px">delete</span>
                                </button>
                                <span class="material-symbols-outlined text-slate-300 chevron-icon transition-transform" style="font-size:18px">expand_more</span>
                            </div>
                        </div>

                        <!-- Card body -->
                        <div class="chapter-card-body hidden border-t border-slate-100 px-4 pb-4 pt-3">
                            <label class="block text-xs font-semibold text-slate-600 mb-2">Chapter Title</label>
                            <input type="text" name="existing_chapter_title[<?= $idx ?>]"
                                   value="<?= esc($pub['title']) ?>"
                                   placeholder="Chapter title..."
                                   class="chapter-title-input w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm" />

                            <label class="block text-xs font-semibold text-slate-600 mb-2 mt-4">Chapter Content</label>
                            <textarea name="existing_chapter_content[<?= $idx ?>]" rows="10"
                                      placeholder="Start writing your chapter here..."
                                      class="chapter-content-ta w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm resize-none font-serif leading-relaxed"><?= esc($pub['content']) ?></textarea>
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-xs text-slate-500 word-count">0 words</span>
                                <button type="button"
                                        class="publish-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all bg-green-100 text-green-700 hover:bg-red-50 hover:text-red-600"
                                        data-published="1"
                                        onclick="togglePublishBtn(this)">
                                    <span class="material-symbols-outlined text-sm">check_circle</span>
                                    Published · click to unpublish
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Draft Chapters -->
            <div id="chapters-list" class="space-y-2">
                <?php if (!empty($chapters)): ?>
                    <?php foreach ($chapters as $idx => $chapter): ?>
                        <div class="chapter-block border border-border rounded-xl bg-white overflow-hidden" data-chapter-id="<?= $chapter['id'] ?>">

                            <input type="hidden" name="existing_chapter_id[<?= $idx ?>]"     value="<?= $chapter['id'] ?>" />
                            <input type="hidden" name="existing_chapter_status[<?= $idx ?>]" value="<?= $chapter['status'] ?>" class="status-hidden" />

                            <div class="chapter-card-header flex items-center gap-3 px-4 py-3 cursor-pointer select-none" onclick="toggleChapter(this)">
                                <div class="w-7 h-7 rounded-full bg-accent flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-[11px] font-bold"><?= $chapter['chapter_number'] ?></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-primary truncate chapter-title-display">
                                        <?= esc($chapter['title']) ?: 'Untitled Chapter' ?>
                                    </p>
                                    <p class="text-[10px] text-slate-400">
                                        <?= $chapter['status'] === 'PUBLISHED' ? '🟢 Published' : '⚪ Draft' ?>
                                        · <span class="word-count-display">— words</span>
                                    </p>
                                </div>
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <label class="flex items-center gap-1 cursor-pointer text-[10px] text-slate-400 mr-1" onclick="event.stopPropagation()">
                                        <input type="checkbox" class="w-3 h-3 rounded publish-toggle"
                                               <?= $chapter['status'] === 'PUBLISHED' ? 'checked' : '' ?> />
                                        Publish
                                    </label>
                                    <button type="button"
                                            class="edit-chapter-btn w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-100 transition-colors text-slate-400 hover:text-accent"
                                            onclick="event.stopPropagation(); toggleChapter(this.closest('.chapter-block').querySelector('.chapter-card-header'))"
                                            title="Edit chapter">
                                        <span class="material-symbols-outlined" style="font-size:18px">edit</span>
                                    </button>
                                    <button type="button"
                                            class="delete-chapter-btn w-8 h-8 rounded-lg flex items-center justify-center hover:bg-red-50 transition-colors text-slate-400 hover:text-red-500"
                                            onclick="event.stopPropagation()"
                                            title="Delete chapter">
                                        <span class="material-symbols-outlined" style="font-size:18px">delete</span>
                                    </button>
                                    <span class="material-symbols-outlined text-slate-300 chevron-icon transition-transform" style="font-size:18px">expand_more</span>
                                </div>
                            </div>

                            <div class="chapter-card-body hidden border-t border-slate-100 px-4 pb-4 pt-3">
                                <label class="block text-xs font-semibold text-slate-600 mb-2">Chapter Title</label>
                                <input type="text" name="existing_chapter_title[<?= $idx ?>]"
                                       value="<?= esc($chapter['title']) ?>"
                                       placeholder="e.g., Chapter <?= $idx+1 ?>: The Adventure Continues"
                                       class="chapter-title-input w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm" />

                                <label class="block text-xs font-semibold text-slate-600 mb-2 mt-4">Chapter Content</label>
                                <textarea name="existing_chapter_content[<?= $idx ?>]" rows="10"
                                          placeholder="Start writing your chapter here..."
                                          class="chapter-content-ta w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm resize-none font-serif leading-relaxed"><?= esc($chapter['content']) ?></textarea>
                                <div class="flex items-center justify-between mt-3">
                                    <span class="text-xs text-slate-500 word-count">0 words</span>
                                    <button type="button"
                                            class="publish-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all <?= $chapter['status'] === 'PUBLISHED' ? 'bg-green-100 text-green-700 hover:bg-red-50 hover:text-red-600' : 'bg-slate-100 text-slate-600 hover:bg-green-100 hover:text-green-700' ?>"
                                            data-published="<?= $chapter['status'] === 'PUBLISHED' ? '1' : '0' ?>"
                                            onclick="togglePublishBtn(this)">
                                        <span class="material-symbols-outlined text-sm"><?= $chapter['status'] === 'PUBLISHED' ? 'check_circle' : 'publish' ?></span>
                                        <?= $chapter['status'] === 'PUBLISHED' ? 'Published · click to unpublish' : 'Publish this chapter' ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php elseif(empty($published_chapters)): ?>
                    <p class="text-xs text-slate-400 py-2">No draft chapters yet. Add your first chapter below.</p>
                <?php endif; ?>
            </div>

            <!-- New chapters added via JS -->
            <div id="new-chapters-list" class="space-y-2 mt-2"></div>

            <button type="button" id="add-chapter-btn"
                    class="mt-4 px-4 py-2 bg-slate-900 text-white rounded-lg font-semibold hover:bg-slate-800 transition-all inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-base">add</span>
                Add Chapter
            </button>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 pt-4">
            <a href="<?= site_url('my-stories') ?>"
               class="flex-1 px-6 py-3 bg-white border-2 border-slate-300 text-slate-700 rounded-lg font-semibold hover:bg-slate-50 transition-all inline-flex items-center justify-center gap-2">
                Cancel
            </a>
            <button type="submit"
                    class="flex-1 px-6 py-3 bg-accent text-white rounded-lg font-semibold hover:bg-purple-700 transition-all inline-flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-base">save</span>
                Update Story
            </button>
        </div>
    </form>
</main>

<script>
// ── Cover upload ──────────────────────────────────────────────────────────
document.getElementById('cover-upload-area').addEventListener('click', () => {
    document.getElementById('cover-upload').click();
});

document.getElementById('cover-upload').addEventListener('change', (e) => {
    if (!e.target.files.length) return;
    const file    = e.target.files[0];
    const icon    = document.getElementById('cover-icon');
    const label   = document.getElementById('cover-label-text');
    const subText = document.getElementById('cover-sub-text');
    icon.textContent = 'check_circle';
    icon.classList.replace('text-slate-400', 'text-accent');
    label.textContent = file.name;
    label.classList.add('text-accent', 'font-semibold');
    label.classList.remove('text-slate-600');
    subText.textContent = 'Click to change image';
    const preview = document.getElementById('cover-preview-img');
    if (preview) {
        const reader = new FileReader();
        reader.onload = ev => { preview.src = ev.target.result; };
        reader.readAsDataURL(file);
    }
});

// ── FIX 1: toggleChapter was missing its function declaration ─────────────
function toggleChapter(header) {
    const block   = header.closest('.chapter-block');
    const body    = block.querySelector('.chapter-card-body');
    const chevron = header.querySelector('.chevron-icon');
    const isOpen  = !body.classList.contains('hidden');
    body.classList.toggle('hidden', isOpen);
    if (chevron) chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
}

// ── Toggle publish button state ───────────────────────────────────────────
function togglePublishBtn(btn) {
    const block       = btn.closest('.chapter-block');
    const hidden      = block.querySelector('.status-hidden');
    const toggle      = block.querySelector('.publish-toggle');
    const headerSub   = block.querySelector('.chapter-card-header p:last-of-type');
    const isPublished = btn.dataset.published === '1';

    if (isPublished) {
        // → unpublish
        btn.dataset.published = '0';
        btn.className = 'publish-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all bg-slate-100 text-slate-600 hover:bg-green-100 hover:text-green-700';
        btn.innerHTML = '<span class="material-symbols-outlined text-sm">publish</span> Publish this chapter';
        if (hidden)    hidden.value   = 'DRAFT';
        if (toggle)    toggle.checked = false;
        if (headerSub) headerSub.innerHTML = headerSub.innerHTML.replace('🟢 Published', '⚪ Draft');
    } else {
        // → publish
        btn.dataset.published = '1';
        btn.className = 'publish-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all bg-green-100 text-green-700 hover:bg-red-50 hover:text-red-600';
        btn.innerHTML = '<span class="material-symbols-outlined text-sm">check_circle</span> Published · click to unpublish';
        if (hidden)    hidden.value   = 'PUBLISHED';
        if (toggle)    toggle.checked = true;
        if (headerSub) headerSub.innerHTML = headerSub.innerHTML.replace('⚪ Draft', '🟢 Published');
    }
}

// ── Word count ────────────────────────────────────────────────────────────
function initWordCount(block) {
    const ta      = block.querySelector('.chapter-content-ta');
    const countSp = block.querySelector('.word-count');
    const dispSp  = block.querySelector('.word-count-display');
    if (!ta) return;
    const update = () => {
        const n = ta.value.trim() ? ta.value.trim().split(/\s+/).length : 0;
        if (countSp) countSp.textContent = n + ' words';
        if (dispSp)  dispSp.textContent  = n + ' words';
    };
    ta.addEventListener('input', update);
    update();
}

// ── Sync title input → header display ────────────────────────────────────
function initTitleSync(block) {
    const input   = block.querySelector('.chapter-title-input');
    const display = block.querySelector('.chapter-title-display');
    if (!input || !display) return;
    input.addEventListener('input', () => {
        display.textContent = input.value.trim() || 'Untitled Chapter';
    });
}

// ── Publish toggle (checkbox in header) ──────────────────────────────────
function initPublishToggle(block) {
    const toggle = block.querySelector('.publish-toggle');
    const hidden = block.querySelector('.status-hidden');
    const dispSp = block.querySelector('.word-count-display');
    if (!toggle) return;
    toggle.addEventListener('change', () => {
        const newStatus = toggle.checked ? 'PUBLISHED' : 'DRAFT';
        if (hidden) hidden.value = newStatus;

        // Sync the publish button inside the card body
        const publishBtn = block.querySelector('.publish-btn');
        if (publishBtn) {
            publishBtn.dataset.published = toggle.checked ? '1' : '0';
            if (toggle.checked) {
                publishBtn.className = 'publish-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all bg-green-100 text-green-700 hover:bg-red-50 hover:text-red-600';
                publishBtn.innerHTML = '<span class="material-symbols-outlined text-sm">check_circle</span> Published · click to unpublish';
            } else {
                publishBtn.className = 'publish-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all bg-slate-100 text-slate-600 hover:bg-green-100 hover:text-green-700';
                publishBtn.innerHTML = '<span class="material-symbols-outlined text-sm">publish</span> Publish this chapter';
            }
        }

        // Update status text in header
        const statusEl = block.querySelector('.chapter-card-header p:last-of-type');
        if (statusEl) {
            const words = dispSp ? dispSp.textContent : '0 words';
            statusEl.innerHTML = (toggle.checked ? '🟢 Published' : '⚪ Draft')
                + ' · <span class="word-count-display">' + words + '</span>';
        }
    });
}

// ── Delete existing chapter ───────────────────────────────────────────────
const deleteContainer = document.getElementById('delete-ids-container');

function initDeleteBtn(block) {
    const btn = block.querySelector('.delete-chapter-btn');
    if (!btn) return;
    btn.addEventListener('click', () => {
        const cid = block.dataset.chapterId;
        if (cid) {
            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = 'delete_chapter_ids[]';
            inp.value = cid;
            deleteContainer.appendChild(inp);
        }
        block.style.transition = 'opacity 0.25s';
        block.style.opacity    = '0';
        setTimeout(() => block.remove(), 250);
    });
}

// Init all existing chapter blocks
document.querySelectorAll('.chapter-block').forEach(block => {
    initWordCount(block);
    initTitleSync(block);
    initPublishToggle(block);
    initDeleteBtn(block);
});

// ── Add new chapter ───────────────────────────────────────────────────────
let newIdx = 0;
const newList = document.getElementById('new-chapters-list');

document.getElementById('add-chapter-btn').addEventListener('click', () => {
    const i   = newIdx++;
    const num = document.querySelectorAll('.chapter-block').length + 1;

    const block = document.createElement('div');
    block.className = 'chapter-block border border-border rounded-xl bg-white overflow-hidden';
    // FIX 2: removed duplicate class 'chapter-content-ta' from card-body div
    // FIX 3: publish toggle in header now uses initPublishToggle via class 'publish-toggle'
    //         and hidden field has class 'status-hidden' so togglePublishBtn can find it
    block.innerHTML = `
        <div class="chapter-card-header flex items-center gap-3 px-4 py-3 cursor-pointer select-none" onclick="toggleChapter(this)">
            <div class="w-7 h-7 rounded-full bg-accent flex items-center justify-center flex-shrink-0">
                <span class="text-white text-[11px] font-bold">${num}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-primary truncate chapter-title-display">Untitled Chapter</p>
                <p class="text-[10px] text-slate-400">⚪ Draft · <span class="word-count-display">0 words</span></p>
            </div>
            <div class="flex items-center gap-1 flex-shrink-0">
                <label class="flex items-center gap-1 cursor-pointer text-[10px] text-slate-400 mr-1" onclick="event.stopPropagation()">
                    <input type="checkbox" class="w-3 h-3 rounded publish-toggle" />
                    Publish
                </label>
                <input type="hidden" name="new_chapter_status[${i}]" value="DRAFT" class="status-hidden" />
                <button type="button"
                        class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-100 transition-colors text-slate-400 hover:text-accent"
                        onclick="event.stopPropagation(); toggleChapter(this.closest('.chapter-block').querySelector('.chapter-card-header'))"
                        title="Edit">
                    <span class="material-symbols-outlined" style="font-size:18px">edit</span>
                </button>
                <button type="button"
                        class="remove-new-btn w-8 h-8 rounded-lg flex items-center justify-center hover:bg-red-50 transition-colors text-slate-400 hover:text-red-500"
                        onclick="event.stopPropagation()"
                        title="Remove">
                    <span class="material-symbols-outlined" style="font-size:18px">delete</span>
                </button>
                <span class="material-symbols-outlined text-slate-300 chevron-icon transition-transform" style="font-size:18px;transform:rotate(180deg)">expand_more</span>
            </div>
        </div>
        <div class="chapter-card-body border-t border-slate-100 px-4 pb-4 pt-3">
            <label class="block text-xs font-semibold text-slate-600 mb-2">Chapter Title</label>
            <input type="text" name="new_chapter_title[${i}]"
                   placeholder="e.g., Chapter ${num}: New Adventure"
                   class="chapter-title-input w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm" />
            <label class="block text-xs font-semibold text-slate-600 mb-2 mt-4">Chapter Content</label>
            <textarea name="new_chapter_content[${i}]" rows="10"
                      placeholder="Start writing your chapter here..."
                      class="chapter-content-ta w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition-all text-sm resize-none font-serif leading-relaxed"></textarea>
            <div class="flex items-center justify-between mt-3">
                <span class="text-xs text-slate-500 word-count">0 words</span>
                <button type="button"
                        class="publish-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all bg-slate-100 text-slate-600 hover:bg-green-100 hover:text-green-700"
                        data-published="0"
                        onclick="togglePublishBtn(this)">
                    <span class="material-symbols-outlined text-sm">publish</span>
                    Publish this chapter
                </button>
            </div>
        </div>
    `;

    newList.appendChild(block);

    // FIX 4: run all init functions on new block so toggle/wordcount/delete all work
    block.querySelector('.remove-new-btn').addEventListener('click', () => {
        block.style.transition = 'opacity 0.25s';
        block.style.opacity    = '0';
        setTimeout(() => block.remove(), 250);
    });
    initWordCount(block);
    initTitleSync(block);
    initPublishToggle(block);   // was missing for new chapters

    block.querySelector('.chapter-title-input').focus();
});
</script>

<?= $this->endSection() ?>