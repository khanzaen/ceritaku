<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Story | CeritaKu</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; background: #fdfdfd; color: #2d333a; }
    h1,h2,h3,h4 { font-family: 'Lora', serif; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
  </style>
</head>
<body>
  <main class="max-w-4xl mx-auto px-6 py-10">
    <div class="mb-8">
      <a href="write.php" class="inline-flex items-center gap-1 text-sm text-slate-600 hover:text-accent transition-colors mb-4">
        <span class="material-symbols-outlined text-base">arrow_back</span>
        Back
      </a>
      <h1 class="text-3xl md:text-4xl font-bold text-primary mb-2">Create Your Story</h1>
      <p class="text-slate-600">Fill in the details below to start sharing your story with the world.</p>
    </div>
    <form action="story_create.php" method="post" enctype="multipart/form-data" class="space-y-6">
      <!-- Story Title -->
      <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
        <label for="title" class="block text-sm font-bold text-primary mb-2">Story Title *</label>
        <input type="text" id="title" name="title" placeholder="Enter your story title" class="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-accent/30 focus-border-accent outline-none transition-all text-sm" required maxlength="120" />
        <p class="text-xs text-slate-500 mt-2">Choose a captivating title that represents your story.</p>
      </div>
      <!-- Story Description -->
      <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
        <label for="synopsis" class="block text-sm font-bold text-primary mb-2">Story Description *</label>
        <textarea id="synopsis" name="synopsis" rows="4" placeholder="Write a brief description of your story..." class="w-full px-4 py-3 border border-border rounded-lg focus-ring-2 focus-ring-accent/30 focus-border-accent outline-none transition-all text-sm resize-none" required maxlength="1000"></textarea>
        <p class="text-xs text-slate-500 mt-2">A compelling synopsis helps readers discover your story.</p>
      </div>
      <!-- Genre -->
      <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
        <h3 class="text-sm font-bold text-primary mb-4">Genre</h3>
        <div class="space-y-4">
          <div>
            <label class="block text-xs font-semibold text-slate-600 mb-3">Select Genres (Multiple) *</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
              <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="genre[]" value="romance" class="w-4 h-4 text-accent rounded focus:ring-2 focus-ring-accent/30" /><span class="text-sm text-slate-700">Romance</span></label>
              <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="genre[]" value="mystery" class="w-4 h-4 text-accent rounded focus:ring-2 focus-ring-accent/30" /><span class="text-sm text-slate-700">Mystery</span></label>
              <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="genre[]" value="fantasy" class="w-4 h-4 text-accent rounded focus:ring-2 focus-ring-accent/30" /><span class="text-sm text-slate-700">Fantasy</span></label>
              <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="genre[]" value="drama" class="w-4 h-4 text-accent rounded focus:ring-2 focus-ring-accent/30" /><span class="text-sm text-slate-700">Drama</span></label>
              <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="genre[]" value="sci-fi" class="w-4 h-4 text-accent rounded focus:ring-2 focus-ring-accent/30" /><span class="text-sm text-slate-700">Sci-Fi</span></label>
              <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="genre[]" value="thriller" class="w-4 h-4 text-accent rounded focus:ring-2 focus-ring-accent/30" /><span class="text-sm text-slate-700">Thriller</span></label>
              <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="genre[]" value="comedy" class="w-4 h-4 text-accent rounded focus:ring-2 focus-ring-accent/30" /><span class="text-sm text-slate-700">Comedy</span></label>
              <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="genre[]" value="politics" class="w-4 h-4 text-accent rounded focus:ring-2 focus-ring-accent/30" /><span class="text-sm text-slate-700">Politics</span></label>
              <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="genre[]" value="history" class="w-4 h-4 text-accent rounded focus:ring-2 focus-ring-accent/30" /><span class="text-sm text-slate-700">History</span></label>
              <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="genre[]" value="adventure" class="w-4 h-4 text-accent rounded focus:ring-2 focus-ring-accent/30" /><span class="text-sm text-slate-700">Adventure</span></label>
              <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="genre[]" value="horror" class="w-4 h-4 text-accent rounded focus:ring-2 focus-ring-accent/30" /><span class="text-sm text-slate-700">Horror</span></label>
              <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="genre[]" value="paranormal" class="w-4 h-4 text-accent rounded focus:ring-2 focus-ring-accent/30" /><span class="text-sm text-slate-700">Paranormal</span></label>
              <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="genre[]" value="supernatural" class="w-4 h-4 text-accent rounded focus:ring-2 focus-ring-accent/30" /><span class="text-sm text-slate-700">Supernatural</span></label>
            </div>
            <p class="text-xs text-slate-500 mt-2">Select at least one genre for your story.</p>
          </div>
        </div>
      </div>
      <!-- Cover Image -->
      <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
        <label class="block text-sm font-bold text-primary mb-2">Story Cover</label>
        <div id="cover-upload-area" class="border-2 border-dashed border-border rounded-lg p-8 text-center hover:border-accent transition-colors cursor-pointer">
          <span class="material-symbols-outlined text-4xl text-slate-400 mb-2 block">upload</span>
          <p class="text-sm text-slate-600 mb-1">Click to upload or drag and drop</p>
          <p class="text-xs text-slate-500">PNG, JPG up to 5MB (Recommended: 800x1200px)</p>
          <input type="file" name="cover" accept="image/*" class="hidden" id="cover-upload" />
        </div>
      </div>
      <!-- Story Status -->
      <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
        <label class="block text-sm font-bold text-primary mb-4">Story Status</label>
        <div class="flex flex-wrap gap-3">
          <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="status" value="ongoing" checked class="w-4 h-4 text-accent focus:ring-2 focus-ring-accent/30" /><span class="text-sm text-slate-700">Ongoing</span></label>
          <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="status" value="completed" class="w-4 h-4 text-accent focus:ring-2 focus-ring-accent/30" /><span class="text-sm text-slate-700">Completed</span></label>
          <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="status" value="hiatus" class="w-4 h-4 text-accent focus:ring-2 focus-ring-accent/30" /><span class="text-sm text-slate-700">On Hiatus</span></label>
        </div>
      </div>
      <!-- First Chapter -->
      <div class="bg-gradient-to-br from-purple-50 to-white border border-border rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-sm font-bold text-primary">First Chapter (Optional)</h3>
          <span class="text-xs text-slate-500">You can add more chapters after publishing</span>
        </div>
        <div class="space-y-4">
          <div>
            <label for="chapter-title" class="block text-xs font-semibold text-slate-600 mb-2">Chapter Title</label>
            <input type="text" id="chapter-title" name="chapter-title" placeholder="e.g., Chapter 1: The Beginning" class="w-full px-4 py-3 border border-border rounded-lg focus-ring-2 focus-ring-accent/30 focus-border-accent outline-none transition-all text-sm" />
          </div>
          <div>
            <label for="chapter-content" class="block text-xs font-semibold text-slate-600 mb-2">Chapter Content</label>
            <textarea id="chapter-content" name="chapter-content" rows="10" placeholder="Start writing your first chapter here..." class="w-full px-4 py-3 border border-border rounded-lg focus-ring-2 focus-ring-accent/30 focus-border-accent outline-none transition-all text-sm resize-none font-serif leading-relaxed"></textarea>
            <div class="flex items-center justify-between mt-2">
              <p class="text-xs text-slate-500">Minimum 500 words recommended</p>
              <span class="text-xs text-slate-500" id="word-count">0 words</span>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <input type="checkbox" id="author-note" name="author-note" class="w-4 h-4 text-accent rounded focus:ring-2 focus-ring-accent/30" />
            <label for="author-note" class="text-xs text-slate-600">Add author's note at the end of this chapter</label>
          </div>
          <div id="author-note-field" class="hidden">
            <textarea name="author-note-field" placeholder="Write a note to your readers..." rows="3" class="w-full px-4 py-3 border border-border rounded-lg focus-ring-2 focus-border-accent outline-none transition-all text-sm resize-none"></textarea>
          </div>
        </div>
      </div>
      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-3 pt-4">
        <button type="button" class="flex-1 px-6 py-3 bg-white border-2 border-slate-300 text-slate-700 rounded-lg font-semibold hover:bg-slate-50 transition-all inline-flex items-center justify-center gap-2">
          <span class="material-symbols-outlined text-base">visibility</span>
          Save as Draft
        </button>
        <button type="submit" class="flex-1 px-6 py-3 bg-accent text-white rounded-lg font-semibold hover:bg-purple-700 transition-all inline-flex items-center justify-center gap-2">
          <span class="material-symbols-outlined text-base">publish</span>
          Publish
        </button>
      </div>
      <p class="text-xs text-slate-500 text-center">By creating a story, you agree to our <a href="#" class="text-accent hover:underline">Terms of Service</a> and <a href="#" class="text-accent hover:underline">Content Guidelines</a>.</p>
    </form>
  </main>
  <script>
    // Cover upload handling
    const coverUploadArea = document.getElementById('cover-upload-area');
    const coverInput = document.getElementById('cover-upload');
    coverUploadArea.addEventListener('click', () => { coverInput.click(); });
    coverInput.addEventListener('change', (e) => {
      if (e.target.files.length > 0) {
        const fileName = e.target.files[0].name;
        coverUploadArea.innerHTML = `
          <span class="material-symbols-outlined text-4xl text-accent mb-2 block">check_circle</span>
          <p class="text-sm text-accent font-semibold mb-1">${fileName}</p>
          <p class="text-xs text-slate-500">Click to change image</p>
        `;
      }
    });
    // Word count for chapter content
    const chapterContent = document.getElementById('chapter-content');
    const wordCount = document.getElementById('word-count');
    if (chapterContent && wordCount) {
      chapterContent.addEventListener('input', () => {
        const text = chapterContent.value.trim();
        const words = text ? text.split(/\s+/).length : 0;
        wordCount.textContent = words + ' words';
      });
    }
    // Author note toggle
    const authorNoteCheckbox = document.getElementById('author-note');
    const authorNoteField = document.getElementById('author-note-field');
    if (authorNoteCheckbox && authorNoteField) {
      authorNoteCheckbox.addEventListener('change', () => {
        authorNoteField.style.display = authorNoteCheckbox.checked ? 'block' : 'none';
      });
    }
  </script>
</body>
</html>
