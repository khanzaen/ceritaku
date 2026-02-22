<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Users</p>
                <h3 class="text-3xl font-bold text-gray-900"><?= number_format($total_users ?? 0) ?></h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-blue-600 text-2xl">group</span>
            </div>
        </div>
    </div>
    
    <!-- Total Stories -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Stories</p>
                <h3 class="text-3xl font-bold text-gray-900"><?= number_format($total_stories ?? 0) ?></h3>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-purple-600 text-2xl">menu_book</span>
            </div>
        </div>
    </div>
    
    <!-- Published Stories -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Published</p>
                <h3 class="text-3xl font-bold text-green-600"><?= number_format($total_published ?? 0) ?></h3>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-green-600 text-2xl">check_circle</span>
            </div>
        </div>
    </div>
    
    <!-- Pending Review -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Pending Review</p>
                <h3 class="text-3xl font-bold text-orange-600"><?= number_format($total_pending ?? 0) ?></h3>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-orange-600 text-2xl">schedule</span>
            </div>
        </div>
    </div>
</div>

<!-- Statistik & Grafik -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Grafik Pertumbuhan User & Cerita -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Pertumbuhan User & Cerita</h3>
        <canvas id="growthChart" height="220"></canvas>
    </div>
    <!-- Grafik Aktivitas -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Aktivitas Mingguan</h3>
        <canvas id="activityChart" height="220"></canvas>
    </div>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Latest Stories -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">Latest Stories</h3>
        </div>
        <div class="p-6">
            <?php if (!empty($latest_stories)): ?>
                <div class="space-y-4">
                    <?php foreach($latest_stories as $story): ?>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-purple-600">menu_book</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 truncate"><?= esc($story['title']) ?></h4>
                                <p class="text-sm text-gray-500">
                                    <span class="px-2 py-0.5 bg-<?= $story['status'] == 'PUBLISHED' ? 'green' : ($story['status'] == 'PENDING_REVIEW' ? 'orange' : 'gray') ?>-100 text-<?= $story['status'] == 'PUBLISHED' ? 'green' : ($story['status'] == 'PENDING_REVIEW' ? 'orange' : 'gray') ?>-700 rounded text-xs">
                                        <?= $story['status'] ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">No stories yet</p>
            <?php endif; ?>
        </div>
    </div>
    <!-- Latest Users -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">Latest Users</h3>
        </div>
        <div class="p-6">
            <?php if (!empty($latest_users)): ?>
                <div class="space-y-4">
                    <?php foreach($latest_users as $user): ?>
                        <div class="flex items-center gap-4">
                            <?php if (!empty($user['profile_photo'])): ?>
                                <img src="<?= profile_url($user['profile_photo']) ?>" alt="Profile" class="w-12 h-12 rounded-full object-cover">
                            <?php else: ?>
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-blue-600 font-bold"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 truncate"><?= esc($user['name']) ?></h4>
                                <p class="text-sm text-gray-500"><?= esc($user['email']) ?></p>
                            </div>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">
                                <?= $user['role'] ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">No users yet</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Contoh data dummy, ganti dengan data dari backend jika perlu
const growthData = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
    datasets: [
        {
            label: 'User Baru',
            data: [12, 19, 15, 22, 18, 25, 30, 28, 24, 20, 18, 22],
            borderColor: '#6c5ce7',
            backgroundColor: 'rgba(108,92,231,0.1)',
            tension: 0.4,
        },
        {
            label: 'Cerita Baru',
            data: [8, 14, 10, 16, 12, 18, 22, 20, 17, 15, 13, 16],
            borderColor: '#00b894',
            backgroundColor: 'rgba(0,184,148,0.1)',
            tension: 0.4,
        }
    ]
};
const growthChart = new Chart(document.getElementById('growthChart'), {
    type: 'line',
    data: growthData,
    options: {
        responsive: true,
        plugins: { legend: { display: true } },
        scales: { y: { beginAtZero: true } }
    }
});

const activityData = {
    labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
    datasets: [
        {
            label: 'Views',
            data: [120, 150, 180, 200, 170, 220, 210],
            backgroundColor: '#6c5ce7',
        },
        {
            label: 'Likes',
            data: [30, 40, 35, 50, 45, 60, 55],
            backgroundColor: '#00b894',
        },
        {
            label: 'Comments',
            data: [10, 15, 12, 18, 14, 20, 17],
            backgroundColor: '#fdcb6e',
        }
    ]
};
const activityChart = new Chart(document.getElementById('activityChart'), {
    type: 'bar',
    data: activityData,
    options: {
        responsive: true,
        plugins: { legend: { display: true } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>

<?= $this->endSection() ?>
