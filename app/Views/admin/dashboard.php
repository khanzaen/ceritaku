<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 mb-8">

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-indigo-600 text-2xl">group</span>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Users</p>
            <p class="text-2xl font-bold text-gray-800"><?= number_format($total_users ?? 0) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-purple-600 text-2xl">menu_book</span>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Stories</p>
            <p class="text-2xl font-bold text-gray-800"><?= number_format($total_stories ?? 0) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-green-600 text-2xl">check_circle</span>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Published</p>
            <p class="text-2xl font-bold text-gray-800"><?= number_format($total_published ?? 0) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-amber-600 text-2xl">pending</span>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Pending Review</p>
            <p class="text-2xl font-bold text-gray-800"><?= number_format($total_pending ?? 0) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-red-600 text-2xl">flag</span>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Reports</p>
            <p class="text-2xl font-bold text-gray-800"><?= number_format($total_reports ?? 0) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-rose-600 text-2xl">auto_stories</span>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Chapters</p>
            <p class="text-2xl font-bold text-gray-800"><?= number_format($total_chapters ?? 0) ?></p>
        </div>
    </div>

</div>

<!-- Charts Row 1 -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    <!-- User Growth (Line Chart) - spans 2 cols -->
    <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-gray-800">User Registrations</h3>
                <p class="text-xs text-gray-400 mt-0.5">Last 6 months</p>
            </div>
            <span class="material-symbols-outlined text-indigo-400 text-2xl">trending_up</span>
        </div>
        <canvas id="userGrowthChart" height="110"></canvas>
    </div>

    <!-- Stories by Status (Donut) -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-gray-800">Story Status</h3>
                <p class="text-xs text-gray-400 mt-0.5">All stories</p>
            </div>
            <span class="material-symbols-outlined text-purple-400 text-2xl">donut_large</span>
        </div>
        <canvas id="storyStatusChart" height="160"></canvas>
        <div id="statusLegend" class="flex flex-wrap gap-2 mt-4 justify-center text-xs"></div>
    </div>

</div>

<!-- Charts Row 2 -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    <!-- Stories by Genre (Horizontal Bar) -->
    <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-gray-800">Stories by Genre</h3>
                <p class="text-xs text-gray-400 mt-0.5">Published stories only</p>
            </div>
            <span class="material-symbols-outlined text-emerald-400 text-2xl">bar_chart</span>
        </div>
        <canvas id="genreChart" height="130"></canvas>
    </div>

    <!-- Top Stories by Chapters (Bar) -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-gray-800">Top Stories</h3>
                <p class="text-xs text-gray-400 mt-0.5">By chapter count</p>
            </div>
            <span class="material-symbols-outlined text-amber-400 text-2xl">emoji_events</span>
        </div>
        <canvas id="topStoriesChart" height="200"></canvas>
    </div>

</div>

<!-- Charts Row 3 + Recent Activity -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    <!-- Report Activity (Line) -->
    <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-gray-800">Report Activity</h3>
                <p class="text-xs text-gray-400 mt-0.5">Last 6 months</p>
            </div>
            <span class="material-symbols-outlined text-red-400 text-2xl">flag</span>
        </div>
        <canvas id="reportGrowthChart" height="110"></canvas>
    </div>

    <!-- Latest Reports -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-gray-800">Recent Reports</h3>
            <a href="<?= base_url('/admin/report-stories') ?>" class="text-xs text-indigo-500 hover:underline">View all</a>
        </div>
        <ul class="space-y-3">
            <?php if (!empty($latest_reports)): ?>
                <?php foreach ($latest_reports as $r): ?>
                <li class="flex gap-3 items-start">
                    <!-- Story Cover -->
                    <div class="w-8 h-11 rounded overflow-hidden bg-slate-100 flex-shrink-0">
                        <?php if (!empty($r['story_cover'])): ?>
                            <img src="<?= cover_url($r['story_cover']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full bg-purple-100 flex items-center justify-center">
                                <span class="material-symbols-outlined text-purple-400 text-sm">menu_book</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <!-- User Photo -->
                            <div class="w-4 h-4 rounded-full overflow-hidden bg-indigo-100 flex-shrink-0 flex items-center justify-center">
                                <?php if (!empty($r['user_photo'])): ?>
                                    <img src="<?= profile_url($r['user_photo']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="text-[8px] font-bold text-indigo-600"><?= strtoupper(substr($r['user_name'] ?? 'A', 0, 1)) ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="text-[10px] text-gray-500 truncate"><?= esc($r['user_name'] ?? 'Anon') ?></p>
                        </div>
                        <p class="text-xs font-semibold text-gray-800 truncate"><?= esc($r['story_title']) ?></p>
                        <p class="text-[10px] text-gray-500 truncate"><?= esc($r['report_reason']) ?></p>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[9px] px-1.5 py-0.5 rounded-full font-semibold
                                <?= $r['status'] === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' ?>">
                                <?= ucfirst($r['status']) ?>
                            </span>
                            <span class="text-[9px] text-gray-400"><?= date('d M Y', strtotime($r['created_at'])) ?></span>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="text-xs text-gray-400 text-center py-4">No reports yet.</li>
            <?php endif; ?>
        </ul>
    </div>

</div>

<!-- Recent Stories & Users -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    <!-- Latest Stories -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-gray-800">Latest Stories</h3>
            <a href="<?= base_url('/admin/stories') ?>" class="text-xs text-indigo-500 hover:underline">View all</a>
        </div>
        <ul class="divide-y divide-gray-50">
            <?php if (!empty($latest_stories)): ?>
                <?php foreach ($latest_stories as $s): ?>
                <li class="py-2.5 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-purple-500 text-base">menu_book</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate"><?= esc($s['title']) ?></p>
                        <p class="text-xs text-gray-400"><?= date('d M Y', strtotime($s['created_at'])) ?></p>
                    </div>
                    <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold
                        <?= $s['status'] === 'PUBLISHED' ? 'bg-green-100 text-green-700' :
                           ($s['status'] === 'PENDING_REVIEW' ? 'bg-amber-100 text-amber-700' :
                           ($s['status'] === 'DRAFT' ? 'bg-gray-100 text-gray-600' : 'bg-rose-100 text-rose-700')) ?>">
                        <?= esc($s['status']) ?>
                    </span>
                </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="text-xs text-gray-400 text-center py-4">No stories yet.</li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Latest Users -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-gray-800">Latest Users</h3>
            <a href="<?= base_url('/admin/users') ?>" class="text-xs text-indigo-500 hover:underline">View all</a>
        </div>
        <ul class="divide-y divide-gray-50">
            <?php if (!empty($latest_users)): ?>
                <?php foreach ($latest_users as $u): ?>
                <li class="py-2.5 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-600 flex-shrink-0">
                        <?= strtoupper(substr($u['name'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate"><?= esc($u['name']) ?></p>
                        <p class="text-xs text-gray-400"><?= esc($u['email']) ?></p>
                    </div>
                    <p class="text-[10px] text-gray-400 flex-shrink-0"><?= date('d M Y', strtotime($u['created_at'])) ?></p>
                </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="text-xs text-gray-400 text-center py-4">No users yet.</li>
            <?php endif; ?>
        </ul>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
// ── Data from PHP ──────────────────────────────────────────
const userGrowthData    = <?= $chart_user_growth ?? '[]' ?>;
const storiesStatusData = <?= $chart_stories_status ?? '[]' ?>;
const genreData         = <?= $chart_stories_genre ?? '[]' ?>;
const reportGrowthData  = <?= $chart_report_growth ?? '[]' ?>;
const topStoriesData    = <?= $chart_top_stories ?? '[]' ?>;

// ── Palette ────────────────────────────────────────────────
const palette = ['#6366f1','#8b5cf6','#06b6d4','#10b981','#f59e0b','#ef4444','#ec4899','#84cc16'];

const gridColor = 'rgba(0,0,0,0.04)';
const baseFont  = { family: 'Inter, system-ui, sans-serif', size: 11 };
Chart.defaults.font = baseFont;
Chart.defaults.color = '#9ca3af';

// Helper — fill months with 0 if missing
function fillMonths(data, key='month', val='total') {
    return { labels: data.map(d => d[key]), values: data.map(d => parseInt(d[val])) };
}

// ── 1. User Growth (Line) ──────────────────────────────────
(function() {
    const { labels, values } = fillMonths(userGrowthData);
    new Chart(document.getElementById('userGrowthChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'New Users',
                data: values,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#6366f1',
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
            scales: {
                x: { grid: { color: gridColor } },
                y: { grid: { color: gridColor }, beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });
})();

// ── 2. Story Status (Doughnut) ─────────────────────────────
(function() {
    const statusColors = {
        'PUBLISHED':     '#10b981',
        'DRAFT':         '#9ca3af',
        'PENDING_REVIEW':'#f59e0b',
        'ARCHIVED':      '#ef4444',
    };
    const labels = storiesStatusData.map(d => d.status);
    const values = storiesStatusData.map(d => parseInt(d.total));
    const colors = labels.map(l => statusColors[l] || '#6366f1');

    new Chart(document.getElementById('storyStatusChart'), {
        type: 'doughnut',
        data: { labels, datasets: [{ data: values, backgroundColor: colors, borderWidth: 2, borderColor: '#fff', hoverOffset: 6 }] },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed}` } }
            }
        }
    });

    // Custom legend
    const legend = document.getElementById('statusLegend');
    labels.forEach((l, i) => {
        legend.innerHTML += `<span class="flex items-center gap-1"><span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:${colors[i]}"></span>${l} (${values[i]})</span>`;
    });
})();

// ── 3. Genre (Horizontal Bar) ──────────────────────────────
(function() {
    const labels = genreData.map(d => d.genres);
    const values = genreData.map(d => parseInt(d.total));
    new Chart(document.getElementById('genreChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Stories',
                data: values,
                backgroundColor: palette,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: gridColor }, beginAtZero: true, ticks: { precision: 0 } },
                y: { grid: { display: false } }
            }
        }
    });
})();

// ── 4. Top Stories by Chapters (Bar) ──────────────────────
(function() {
    const labels = topStoriesData.map(d => d.title.length > 20 ? d.title.slice(0,18)+'…' : d.title);
    const values = topStoriesData.map(d => parseInt(d.chapter_count));
    new Chart(document.getElementById('topStoriesChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Chapters',
                data: values,
                backgroundColor: palette.slice(0,5),
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                y: { grid: { color: gridColor }, beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });
})();

// ── 5. Report Growth (Line) ────────────────────────────────
(function() {
    const { labels, values } = fillMonths(reportGrowthData);
    new Chart(document.getElementById('reportGrowthChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Reports',
                data: values,
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239,68,68,0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#ef4444',
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
            scales: {
                x: { grid: { color: gridColor } },
                y: { grid: { color: gridColor }, beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });
})();
</script>

<?= $this->endSection() ?>