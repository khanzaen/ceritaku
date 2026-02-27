<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto px-6 py-10">

    <!-- Header -->
    <div class="flex items-center gap-3 mb-8">
        <a href="<?= base_url('/profile') ?>"
           class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors">
            <span class="material-symbols-outlined text-base text-slate-600">arrow_back</span>
        </a>
        <div>
            <p class="text-xs font-semibold tracking-widest text-accent uppercase">Account</p>
            <h1 class="text-2xl font-bold text-slate-900 leading-none">Edit Profile</h1>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-6 text-sm">
            <span class="material-symbols-outlined text-base">check_circle</span>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-6 text-sm">
            <span class="material-symbols-outlined text-base">error</span>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-6 text-sm">
            <p class="font-semibold mb-1 flex items-center gap-2">
                <span class="material-symbols-outlined text-base">error</span>
                Please fix the following errors:
            </p>
            <ul class="list-disc list-inside space-y-0.5 pl-1">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/profile/update') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- Profile Photo -->
        <div class="bg-white border border-slate-100 rounded-2xl p-6 mb-5 shadow-sm">
            <h2 class="text-sm font-bold text-slate-700 mb-4">Profile Photo</h2>

            <div class="flex items-center gap-5">
                <!-- Preview -->
                <div class="relative flex-none">
                    <div id="photo-preview"
                         class="w-24 h-24 rounded-full overflow-hidden bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center border-4 border-white shadow-md">
                        <?php if (!empty($user['profile_photo'])): ?>
                            <img id="preview-img"
                                 src="<?= profile_url($user['profile_photo']) ?>"
                                 alt="<?= esc($user['name']) ?>"
                                 class="w-full h-full object-cover" />
                        <?php else: ?>
                            <?php
                                $names = explode(' ', trim($user['name']));
                                $initials = count($names) >= 2
                                    ? strtoupper(substr($names[0], 0, 1) . substr($names[count($names)-1], 0, 1))
                                    : strtoupper(substr($user['name'], 0, 2));
                            ?>
                            <span id="preview-initials" class="text-2xl font-bold text-purple-600"><?= $initials ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Upload -->
                <div class="flex-1">
                    <label for="profile_photo"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer transition-all">
                        <span class="material-symbols-outlined text-base">upload</span>
                        Change Photo
                    </label>
                    <input type="file" id="profile_photo" name="profile_photo"
                           accept="image/jpeg,image/png,image/jpg"
                           class="hidden" onchange="previewPhoto(this)" />
                    <p class="text-xs text-slate-400 mt-2">JPG or PNG, max 2MB</p>
                </div>
            </div>
        </div>

        <!-- Personal Info -->
        <div class="bg-white border border-slate-100 rounded-2xl p-6 mb-5 shadow-sm">
            <h2 class="text-sm font-bold text-slate-700 mb-4">Personal Information</h2>

            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                           value="<?= esc(old('name', $user['name'])) ?>"
                           placeholder="Your full name"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all" />
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Username
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm">@</span>
                        <input type="text" id="username" name="username"
                               value="<?= esc(old('username', $user['username'])) ?>"
                               placeholder="username"
                               class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all" />
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Letters and numbers only, no spaces</p>
                </div>

                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Bio
                    </label>
                    <textarea id="bio" name="bio" rows="4"
                              maxlength="500"
                              placeholder="Tell us a little about yourself..."
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all resize-none"
                              oninput="updateCharCount(this)"><?= esc(old('bio', $user['bio'] ?? '')) ?></textarea>
                    <div class="flex justify-end mt-1">
                        <span id="char-count" class="text-xs text-slate-400">
                            <?= strlen($user['bio'] ?? '') ?>/500
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between gap-3">
            <a href="<?= base_url('/profile') ?>"
               class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-all">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-accent text-white text-sm font-semibold hover:bg-purple-700 transition-all shadow-md shadow-purple-200 flex items-center gap-2">
                <span class="material-symbols-outlined text-base">save</span>
                Save Changes
            </button>
        </div>

    </form>
</div>

<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must not exceed 2MB');
            input.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photo-preview');
            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" alt="Preview" />`;
        };
        reader.readAsDataURL(file);
    }
}

function updateCharCount(textarea) {
    document.getElementById('char-count').textContent = textarea.value.length + '/500';
}
</script>

<?= $this->endSection() ?>