<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola semua akun pengguna platform</p>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
    <?php
    $roles  = ['USER', 'ADMIN', 'WRITER'];
    $roleCounts = [];
    foreach ($users ?? [] as $u) { $roleCounts[$u['role']] = ($roleCounts[$u['role']] ?? 0) + 1; }
    $roleColors = ['USER' => 'blue', 'ADMIN' => 'purple', 'WRITER' => 'green'];
    $textColors = ['blue' => 'text-blue-700', 'purple' => 'text-purple-700', 'green' => 'text-green-700'];
    ?>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 mb-1">Total Users</p>
        <p class="text-2xl font-bold text-gray-800"><?= count($users ?? []) ?></p>
    </div>
    <?php foreach ($roles as $role): ?>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 mb-1"><?= $role ?></p>
        <p class="text-2xl font-bold <?= $textColors[$roleColors[$role]] ?? 'text-gray-700' ?>">
            <?= $roleCounts[$role] ?? 0 ?>
        </p>
    </div>
    <?php endforeach; ?>
</div>

<!-- Filter + Search -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-6 flex flex-wrap gap-3 items-center">
    <div class="relative flex-1 min-w-[200px]">
        <input type="text" onkeyup="searchTable('userTable', this.value)"
            placeholder="Cari nama / email..."
            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
        <span class="material-symbols-outlined absolute left-2.5 top-2.5 text-gray-400 text-base">search</span>
    </div>
    <select onchange="filterByRole(this.value)" class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-purple-400">
        <option value="">Semua Role</option>
        <option value="USER">User</option>
        <option value="ADMIN">Admin</option>
        <option value="WRITER">Writer</option>
    </select>
</div>

<!-- Table -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800">Daftar Pengguna</h3>
    </div>

    <div class="overflow-x-auto">
        <table id="userTable" class="w-full text-sm">
            <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left">Pengguna</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Role</th>
                    <th class="px-4 py-3 text-left">Bergabung</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-gray-50 transition user-row" data-role="<?= esc($user['role']) ?>">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <?php if (!empty($user['profile_photo'])): ?>
                                    <img src="<?= base_url('uploads/' . $user['profile_photo']) ?>"
                                        class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                                <?php else: ?>
                                    <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-indigo-600 font-bold text-sm"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <p class="font-semibold text-gray-900"><?= esc($user['name']) ?></p>
                                    <?php if ($user['id'] == session()->get('user_id')): ?>
                                        <span class="text-[10px] text-purple-500 font-medium">— kamu</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600"><?= esc($user['email']) ?></td>
                        <td class="px-4 py-3">
                            <?php
                            $roleClasses = ['ADMIN' => 'bg-purple-100 text-purple-700', 'WRITER' => 'bg-green-100 text-green-700', 'USER' => 'bg-blue-100 text-blue-700'];
                            $rc = $roleClasses[$user['role']] ?? 'bg-gray-100 text-gray-600';
                            ?>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold <?= $rc ?>">
                                <?= esc($user['role']) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs"><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1">
                                <!-- Ganti Role -->
                                <?php if ($user['id'] != session()->get('user_id')): ?>
                                <div class="relative" x-data="{ open: false }">
                                    <form action="<?= base_url('/admin/users/change-role/' . $user['id']) ?>" method="POST" class="flex items-center gap-1">
                                        <?= csrf_field() ?>
                                        <select name="role" class="text-xs border border-gray-200 rounded-lg px-2 py-1 focus:outline-none focus:ring-1 focus:ring-purple-400">
                                            <option value="USER"   <?= $user['role'] === 'USER'   ? 'selected' : '' ?>>User</option>
                                            <option value="WRITER" <?= $user['role'] === 'WRITER' ? 'selected' : '' ?>>Writer</option>
                                            <option value="ADMIN"  <?= $user['role'] === 'ADMIN'  ? 'selected' : '' ?>>Admin</option>
                                        </select>
                                        <button type="submit" class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition" title="Simpan Role">
                                            <span class="material-symbols-outlined text-base">save</span>
                                        </button>
                                    </form>
                                </div>

                                <!-- Hapus User -->
                                <form action="<?= base_url('/admin/users/delete/' . $user['id']) ?>" method="POST" class="inline"
                                    onsubmit="return confirm('Hapus user <?= esc(addslashes($user['name'])) ?>? Tindakan tidak dapat dibatalkan.')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </form>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400 italic px-2">Akun kamu</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-gray-400">
                            <span class="material-symbols-outlined text-5xl block mb-2">group</span>
                            Belum ada pengguna
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function searchTable(tableId, query) {
    document.querySelectorAll('#' + tableId + ' tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(query.toLowerCase()) ? '' : 'none';
    });
}

function filterByRole(role) {
    document.querySelectorAll('#userTable tbody tr.user-row').forEach(row => {
        row.style.display = (!role || row.dataset.role === role) ? '' : 'none';
    });
}
</script>

<?= $this->endSection() ?>
