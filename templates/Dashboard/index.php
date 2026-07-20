<?php
/**
 * @var \App\View\AppView $this
 */
?>

<style>
    .dashboard-compact {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .dash-header {
        margin-bottom: 28px;
    }

    .dash-title {
        font-size: 42px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 8px;
        letter-spacing: 1px;
    }

    .dash-welcome {
        font-size: 24px;
        color: #111827;
        margin: 0;
        font-weight: 700;
    }

    .dash-subtitle {
        color: #64748b;
        margin-top: 8px;
        font-size: 15px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        color: white;
        padding: 24px;
        border-radius: 20px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
        position: relative;
        overflow: hidden;
    }

    .stat-card::after {
        content: "";
        position: absolute;
        right: -30px;
        top: -30px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255,255,255,0.18);
    }

    .stat-card h4 {
        margin: 0 0 15px;
        font-size: 18px;
        font-weight: 700;
        opacity: 0.95;
    }

    .stat-card h2 {
        margin: 0;
        font-size: 38px;
        font-weight: 800;
    }

    .stat-blue { background: linear-gradient(135deg, #2563eb, #60a5fa); }
    .stat-red { background: linear-gradient(135deg, #dc2626, #f87171); }
    .stat-green { background: linear-gradient(135deg, #16a34a, #4ade80); }
    .stat-purple { background: linear-gradient(135deg, #7c3aed, #c084fc); }
    .stat-orange { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
    .stat-slate { background: linear-gradient(135deg, #334155, #64748b); }

    .quick-section {
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 16px;
    }

    .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .quick-actions a {
        padding: 12px 18px;
        border-radius: 14px;
        text-decoration: none;
        font-weight: 800;
        color: white;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.10);
        transition: 0.2s ease;
    }

    .quick-actions a:hover {
        transform: translateY(-2px);
        color: white;
    }

    .action-dark { background: #111827; }
    .action-blue { background: #2563eb; }
    .action-red { background: #dc2626; }
    .action-green { background: #16a34a; }
    .action-purple { background: #7c3aed; }
    .action-orange { background: #f59e0b; }
    .action-slate { background: #334155; }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 24px;
        margin-bottom: 30px;
    }

    .panel {
        background: #ffffff;
        border-radius: 22px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        padding: 24px;
    }

    .panel h3 {
        margin: 0 0 18px;
        font-size: 24px;
        color: #0f172a;
        font-weight: 800;
    }

    .claim-list,
    .leader-list,
    .recent-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .claim-item,
    .leader-item,
    .recent-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px 16px;
    }

    .claim-main,
    .leader-main,
    .recent-main {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .item-title {
        color: #0f172a;
        font-weight: 800;
        font-size: 15px;
    }

    .item-subtitle {
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .badge-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-approved {
        background: #dcfce7;
        color: #166534;
    }

    .badge-count {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .small-action {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 10px;
        background: #2563eb;
        color: #ffffff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 800;
    }

    .small-action:hover {
        color: white;
        background: #1d4ed8;
    }

    .chart-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 30px;
    }

    .chart-card {
        background: #ffffff;
        border-radius: 22px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        padding: 24px;
        min-height: 340px;
    }

    .chart-card h3 {
        margin: 0;
        font-size: 22px;
        color: #0f172a;
        font-weight: 800;
    }

    .chart-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .chart-report-btn {
        display: inline-flex;
        align-items: center;
        padding: 10px 14px;
        border-radius: 12px;
        background: #334155;
        color: #ffffff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 800;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.10);
        transition: 0.2s ease;
    }

    .chart-report-btn:hover {
        background: #0f172a;
        color: #ffffff;
        transform: translateY(-2px);
    }

    .chart-box {
        height: 260px;
        position: relative;
    }

    .recent-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 30px;
    }

    .empty-text {
        color: #64748b;
        font-weight: 600;
        background: #f8fafc;
        border-radius: 14px;
        padding: 16px;
    }

    @media (max-width: 1100px) {
        .dashboard-grid,
        .chart-grid,
        .recent-grid {
            grid-template-columns: 1fr;
        }

        .dash-title {
            font-size: 34px;
        }
    }
</style>

<div class="dashboard-compact">

<div class="dash-header">
    <h1 class="dash-title">UiTEMU Dashboard</h1>
    <h2 class="dash-welcome">Welcome <?= h($userName) ?></h2>
    <p class="dash-subtitle">
        Here is your UiTEMU Lost & Found activity overview.
    </p>
</div>

<div class="stats-grid">

    <?php if ($isAdmin): ?>
        <div class="stat-card stat-blue">
            <h4>Total Users</h4>
            <h2><?= h($totalUsers) ?></h2>
        </div>

        <div class="stat-card stat-red">
            <h4>Lost Items</h4>
            <h2><?= h($totalLostItems) ?></h2>
        </div>

        <div class="stat-card stat-green">
            <h4>Found Items</h4>
            <h2><?= h($totalFoundItems) ?></h2>
        </div>

        <div class="stat-card stat-purple">
            <h4>Total Claims</h4>
            <h2><?= h($totalClaims) ?></h2>
        </div>

        <div class="stat-card stat-orange">
            <h4>Pending Claims</h4>
            <h2><?= h($pendingClaims) ?></h2>
        </div>

        <div class="stat-card stat-slate">
            <h4>Certificates</h4>
            <h2><?= h($totalCertificates) ?></h2>
        </div>
    <?php else: ?>
        <div class="stat-card stat-red">
            <h4>My Lost Reports</h4>
            <h2><?= h($totalLostItems) ?></h2>
        </div>

        <div class="stat-card stat-green">
            <h4>My Found Reports</h4>
            <h2><?= h($totalFoundItems) ?></h2>
        </div>

        <div class="stat-card stat-purple">
            <h4>My Claims</h4>
            <h2><?= h($totalClaims) ?></h2>
        </div>

        <div class="stat-card stat-orange">
            <h4>Pending Claims</h4>
            <h2><?= h($pendingClaims) ?></h2>
        </div>

        <div class="stat-card stat-blue">
            <h4>My Certificates</h4>
            <h2><?= h($totalCertificates) ?></h2>
        </div>
    <?php endif; ?>

</div>

<div class="quick-section">
    <h3 class="section-title"><?= $isAdmin ? 'Admin Menu' : 'Student Menu' ?></h3>

    <div class="quick-actions">
        <?php if ($isAdmin): ?>
            <?= $this->Html->link('➕ Add Lost Item', ['controller' => 'LostItems', 'action' => 'add'], ['class' => 'action-red']) ?>
            <?= $this->Html->link('➕ Add Found Item', ['controller' => 'FoundItems', 'action' => 'add'], ['class' => 'action-green']) ?>
            <?= $this->Html->link('📋 Manage Claims', ['controller' => 'Claims', 'action' => 'index'], ['class' => 'action-purple']) ?>
            <?= $this->Html->link('🎖 Certificates', ['controller' => 'Certificates', 'action' => 'index'], ['class' => 'action-blue']) ?>
            <?= $this->Html->link('👥 Manage Users', ['controller' => 'Users', 'action' => 'index'], ['class' => 'action-dark']) ?>
            <?= $this->Html->link('📊 View Monthly Report', ['controller' => 'Dashboard', 'action' => 'monthlyReport'], ['class' => 'action-slate']) ?>
        <?php else: ?>
            <?= $this->Html->link('➕ Report Lost Item', ['controller' => 'LostItems', 'action' => 'add'], ['class' => 'action-red']) ?>
            <?= $this->Html->link('➕ Report Found Item', ['controller' => 'FoundItems', 'action' => 'add'], ['class' => 'action-green']) ?>
            <?= $this->Html->link('🔍 Browse Found Items', ['controller' => 'FoundItems', 'action' => 'index'], ['class' => 'action-blue']) ?>
            <?= $this->Html->link('📋 My Claims', ['controller' => 'Claims', 'action' => 'index'], ['class' => 'action-purple']) ?>
            <?= $this->Html->link('🎖 My Certificates', ['controller' => 'Certificates', 'action' => 'index'], ['class' => 'action-orange']) ?>
        <?php endif; ?>
    </div>
</div>

<div class="dashboard-grid">

    <div class="panel">
        <h3><?= $isAdmin ? 'Pending Claims Requiring Action' : 'My Recent Claims' ?></h3>

        <?php if (!empty($dashboardClaims) && count($dashboardClaims) > 0): ?>
            <div class="claim-list">
                <?php foreach ($dashboardClaims as $claim): ?>
                    <?php
                    $claimStatus = strtolower((string)$claim->claim_status);
                    $claimBadge = $claimStatus === 'approved' ? 'badge-approved' : 'badge-pending';

                    $claimItem = $claim->has('found_item') ? $claim->found_item->item_name : 'No Found Item';
                    $claimUser = $claim->has('user') ? $claim->user->name : 'No User';
                    ?>
                    <div class="claim-item">
                        <div class="claim-main">
                            <span class="item-title"><?= h($claimItem) ?></span>
                            <span class="item-subtitle">
                                Claim #<?= h($claim->id) ?> by <?= h($claimUser) ?>
                            </span>
                        </div>

                        <span class="badge <?= $claimBadge ?>">
                            <?= h($claim->claim_status) ?>
                        </span>

                        <?= $this->Html->link(
                            'View',
                            ['controller' => 'Claims', 'action' => 'view', $claim->id],
                            ['class' => 'small-action']
                        ) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-text">
                <?= $isAdmin ? 'No pending claims at the moment.' : 'You have not submitted any claims yet.' ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="panel">
        <h3>Top 10 Helpful Users</h3>

        <?php if (!empty($topFinders) && count($topFinders) > 0): ?>
            <div class="leader-list">
                <?php $rank = 1; ?>
                <?php foreach ($topFinders as $finder): ?>
                    <div class="leader-item">
                        <div class="leader-main">
                            <span class="item-title">
                                #<?= $rank++ ?> <?= h($finder->name) ?>
                            </span>
                            <span class="item-subtitle">
                                Found item reports submitted
                            </span>
                        </div>

                        <span class="badge badge-count">
                            <?= h($finder->found_count) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-text">No leaderboard data yet.</div>
        <?php endif; ?>
    </div>

</div>

<div class="chart-grid">

    <div class="chart-card">
        <div class="chart-card-header">
            <h3><?= $isAdmin ? 'System Overview' : 'My Activity Overview' ?></h3>
        </div>
        <div class="chart-box">
            <canvas id="overviewChart"></canvas>
        </div>
    </div>

    <?php if ($isAdmin): ?>
        <div class="chart-card">
            <div class="chart-card-header">
                <h3>Monthly Report Summary</h3>

                <?= $this->Html->link(
                    'Open Report',
                    ['controller' => 'Dashboard', 'action' => 'monthlyReport'],
                    ['class' => 'chart-report-btn']
                ) ?>
            </div>

            <div class="chart-box">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    <?php endif; ?>

    <div class="chart-card">
        <div class="chart-card-header">
            <h3>Claim Status</h3>
        </div>
        <div class="chart-box">
            <canvas id="claimStatusChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-card-header">
            <h3>Found Item Categories</h3>
        </div>
        <div class="chart-box">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-card-header">
            <h3>Found Item Status</h3>
        </div>
        <div class="chart-box">
            <canvas id="foundStatusChart"></canvas>
        </div>
    </div>

</div>

<div class="recent-grid">

    <div class="panel">
        <h3><?= $isAdmin ? 'Recent Lost Items' : 'My Recent Lost Reports' ?></h3>

        <?php if (!empty($recentLostItems) && count($recentLostItems) > 0): ?>
            <div class="recent-list">
                <?php foreach ($recentLostItems as $lostItem): ?>
                    <div class="recent-item">
                        <div class="recent-main">
                            <span class="item-title"><?= h($lostItem->item_name) ?></span>
                            <span class="item-subtitle">
                                <?= h($lostItem->category) ?> • <?= h($lostItem->location) ?>
                            </span>
                        </div>

                        <?= $this->Html->link(
                            'View',
                            ['controller' => 'LostItems', 'action' => 'view', $lostItem->id],
                            ['class' => 'small-action']
                        ) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-text">No lost item reports yet.</div>
        <?php endif; ?>
    </div>

    <div class="panel">
        <h3><?= $isAdmin ? 'Recent Found Items' : 'My Recent Found Reports' ?></h3>

        <?php if (!empty($recentFoundItems) && count($recentFoundItems) > 0): ?>
            <div class="recent-list">
                <?php foreach ($recentFoundItems as $foundItem): ?>
                    <div class="recent-item">
                        <div class="recent-main">
                            <span class="item-title"><?= h($foundItem->item_name) ?></span>
                            <span class="item-subtitle">
                                <?= h($foundItem->category) ?> • <?= h($foundItem->location) ?>
                            </span>
                        </div>

                        <?= $this->Html->link(
                            'View',
                            ['controller' => 'FoundItems', 'action' => 'view', $foundItem->id],
                            ['class' => 'small-action']
                        ) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-text">No found item reports yet.</div>
        <?php endif; ?>
    </div>

</div>

<script>
const overviewLabels = <?= json_encode($overviewLabels) ?>;
const overviewCounts = <?= json_encode($overviewCounts) ?>;

const categoryLabels = <?= json_encode($categoryLabels) ?>;
const categoryCounts = <?= json_encode($categoryCounts) ?>;

const claimStatusLabels = <?= json_encode($claimStatusLabels) ?>;
const claimStatusCounts = <?= json_encode($claimStatusCounts) ?>;

const foundStatusLabels = <?= json_encode($foundStatusLabels) ?>;
const foundStatusCounts = <?= json_encode($foundStatusCounts) ?>;

const monthlyLabels = <?= json_encode($monthlyLabels) ?>;
const monthlyLostCounts = <?= json_encode($monthlyLostCounts) ?>;
const monthlyFoundCounts = <?= json_encode($monthlyFoundCounts) ?>;

new Chart(document.getElementById('overviewChart'), {
    type: 'bar',
    data: {
        labels: overviewLabels,
        datasets: [{
            label: 'Count',
            data: overviewCounts,
            backgroundColor: ['#2563eb', '#dc2626', '#16a34a', '#7c3aed', '#f59e0b', '#334155'],
            borderRadius: 10
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});

new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: monthlyLabels,
        datasets: [
            {
                label: 'Lost Reports',
                data: monthlyLostCounts,
                backgroundColor: '#dc2626',
                borderRadius: 10
            },
            {
                label: 'Found Reports',
                data: monthlyFoundCounts,
                backgroundColor: '#16a34a',
                borderRadius: 10
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top' }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});

new Chart(document.getElementById('claimStatusChart'), {
    type: 'doughnut',
    data: {
        labels: claimStatusLabels,
        datasets: [{
            data: claimStatusCounts,
            backgroundColor: ['#f59e0b', '#16a34a', '#dc2626', '#7c3aed', '#64748b']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

new Chart(document.getElementById('categoryChart'), {
    type: 'pie',
    data: {
        labels: categoryLabels,
        datasets: [{
            data: categoryCounts,
            backgroundColor: ['#2563eb', '#16a34a', '#f59e0b', '#dc2626', '#7c3aed', '#64748b']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

new Chart(document.getElementById('foundStatusChart'), {
    type: 'bar',
    data: {
        labels: foundStatusLabels,
        datasets: [{
            label: 'Found Item Status',
            data: foundStatusCounts,
            backgroundColor: ['#16a34a', '#dc2626', '#2563eb', '#f59e0b'],
            borderRadius: 10
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});
</script>

</div>