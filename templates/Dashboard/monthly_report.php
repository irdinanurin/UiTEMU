<?php
/**
 * @var \App\View\AppView $this
 */
?>

<style>
    .report-view-page {
        display: grid;
        grid-template-columns: 1fr 270px;
        gap: 24px;
        align-items: start;
    }

    .report-main-card,
    .report-side-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 12px 35px rgba(15, 23, 42, 0.08);
        padding: 26px;
    }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .report-header h2 {
        font-size: 42px;
        font-weight: 900;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.8px;
    }

    .report-header p {
        margin: 8px 0 0;
        color: #64748b;
        font-size: 16px;
    }

    .status-badge {
        display: inline-block;
        padding: 10px 18px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 900;
        background: #dcfce7;
        color: #166534;
    }

    .report-content-grid {
        display: grid;
        grid-template-columns: 1fr 1.05fr;
        gap: 24px;
        align-items: start;
    }

    .preview-card,
    .info-card,
    .table-card {
        background: #ffffff;
        border-radius: 22px;
        box-shadow: 0 8px 25px rgba(15, 23, 42, 0.06);
        padding: 24px;
        border: 1px solid #f1f5f9;
    }

    .preview-card h3,
    .info-card h3,
    .table-card h3,
    .report-side-card h3 {
        margin: 0 0 18px;
        font-size: 26px;
        font-weight: 900;
        color: #0f172a;
    }

    .chart-box {
        height: 360px;
        position: relative;
        padding: 10px;
        border-radius: 18px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .info-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 18px;
        min-height: 78px;
    }

    .info-label {
        display: block;
        color: #64748b;
        font-size: 13px;
        font-weight: 900;
        margin-bottom: 8px;
    }

    .info-value {
        color: #0f172a;
        font-size: 20px;
        font-weight: 900;
        word-break: break-word;
    }

    .summary-lost .info-value {
        color: #dc2626;
    }

    .summary-found .info-value {
        color: #16a34a;
    }

    .summary-claims .info-value {
        color: #7c3aed;
    }

    .summary-cert .info-value {
        color: #334155;
    }

    .side-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .side-btn,
    .filter-btn {
        width: 100%;
        border: none;
        border-radius: 14px;
        padding: 14px 18px;
        font-weight: 900;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
        display: block;
        font-size: 15px;
    }

    .back-btn {
        background: #e2e8f0;
        color: #0f172a;
    }

    .filter-btn {
        background: #2563eb;
        color: #ffffff;
    }

    .filter-btn:hover,
    .back-btn:hover {
        opacity: 0.9;
        color: inherit;
    }

    .filter-form {
        display: flex;
        flex-direction: column;
        gap: 14px;
        margin-bottom: 18px;
    }

    .filter-form label {
        font-size: 14px;
        font-weight: 900;
        color: #334155;
        margin-bottom: 7px;
        display: block;
    }

    .filter-form select {
        width: 100%;
        height: 48px;
        border: 1px solid #cbd5e1;
        border-radius: 13px;
        padding: 0 13px;
        font-size: 15px;
        background: #ffffff;
        box-sizing: border-box;
    }

    .table-card {
        margin-top: 26px;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
        overflow: hidden;
    }

    .report-table th {
        background: #f1f5f9;
        color: #334155;
        padding: 14px;
        text-align: left;
        font-size: 14px;
        font-weight: 900;
    }

    .report-table td {
        padding: 14px;
        border-bottom: 1px solid #e5e7eb;
        color: #0f172a;
        font-size: 14px;
        font-weight: 700;
    }

    .type-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .type-lost {
        background: #fee2e2;
        color: #991b1b;
    }

    .type-found {
        background: #dcfce7;
        color: #166534;
    }

    .type-claim {
        background: #ede9fe;
        color: #5b21b6;
    }

    .type-cert {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .empty-box {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px;
        color: #64748b;
        font-weight: 800;
    }

    .side-note {
        margin-top: 18px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px;
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
        font-weight: 700;
    }

    @media (max-width: 1200px) {
        .report-view-page {
            grid-template-columns: 1fr;
        }

        .report-content-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {
        .report-header h2 {
            font-size: 32px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .table-card {
            overflow-x: auto;
        }
    }
</style>

<div class="report-view-page">

    <div class="report-main-card">

        <div class="report-header">
            <div>
                <h2>Monthly Report</h2>
                <p>
                    View UiTEMU lost and found item activity by selected month.
                </p>
            </div>

            <span class="status-badge">
                <?= h($monthName) ?> <?= h($selectedYear) ?>
            </span>
        </div>

        <div class="report-content-grid">

            <div class="preview-card">
                <h3>Report Chart</h3>

                <div class="chart-box">
                    <canvas id="monthlyReportChart"></canvas>
                </div>
            </div>

            <div class="info-card">
                <h3>Report Information</h3>

                <div class="info-grid">

                    <div class="info-box">
                        <span class="info-label">Selected Month</span>
                        <span class="info-value"><?= h($monthName) ?></span>
                    </div>

                    <div class="info-box">
                        <span class="info-label">Selected Year</span>
                        <span class="info-value"><?= h($selectedYear) ?></span>
                    </div>

                    <div class="info-box summary-lost">
                        <span class="info-label">Lost Reports</span>
                        <span class="info-value"><?= h($lostCount) ?></span>
                    </div>

                    <div class="info-box summary-found">
                        <span class="info-label">Found Reports</span>
                        <span class="info-value"><?= h($foundCount) ?></span>
                    </div>

                </div>
            </div>

        </div>

        <div class="table-card">
            <h3>Report Details</h3>

            <?php if (
                $selectedLostItems->count() > 0 ||
                $selectedFoundItems->count() > 0
            ): ?>

                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Record</th>
                            <th>Reporter / User</th>
                            <th>Location / Related Item</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($selectedLostItems as $item): ?>
                            <tr>
                                <td><span class="type-badge type-lost">Lost</span></td>
                                <td><?= h($item->item_name) ?></td>
                                <td><?= h($item->user->name ?? 'N/A') ?></td>
                                <td><?= h($item->location ?? '-') ?></td>
                                <td><?= h($item->created_at) ?></td>
                                <td><?= h($item->status ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <?php foreach ($selectedFoundItems as $item): ?>
                            <tr>
                                <td><span class="type-badge type-found">Found</span></td>
                                <td><?= h($item->item_name) ?></td>
                                <td><?= h($item->user->name ?? 'N/A') ?></td>
                                <td><?= h($item->location ?? '-') ?></td>
                                <td><?= h($item->created_at) ?></td>
                                <td><?= h($item->status ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>

            <?php else: ?>

                <div class="empty-box">
                    No records found for <?= h($monthName) ?> <?= h($selectedYear) ?>.
                </div>

            <?php endif; ?>
        </div>

    </div>

    <div class="report-side-card">
        <h3>Actions</h3>

        <div class="side-actions">

            <?= $this->Html->link(
                'Back to Dashboard',
                ['controller' => 'Dashboard', 'action' => 'index'],
                ['class' => 'side-btn back-btn']
            ) ?>

            <?= $this->Form->create(null, [
                'type' => 'get',
                'class' => 'filter-form',
                'url' => ['controller' => 'Dashboard', 'action' => 'monthlyReport']
            ]) ?>

                <div>
                    <label>Choose Month</label>
                    <?= $this->Form->select('month', [
                        1 => 'January',
                        2 => 'February',
                        3 => 'March',
                        4 => 'April',
                        5 => 'May',
                        6 => 'June',
                        7 => 'July',
                        8 => 'August',
                        9 => 'September',
                        10 => 'October',
                        11 => 'November',
                        12 => 'December',
                    ], [
                        'value' => $selectedMonth
                    ]) ?>
                </div>

                <div>
                    <label>Choose Year</label>
                    <?= $this->Form->select('year', [
                        date('Y') - 3 => date('Y') - 3,
                        date('Y') - 2 => date('Y') - 2,
                        date('Y') - 1 => date('Y') - 1,
                        date('Y') => date('Y'),
                        date('Y') + 1 => date('Y') + 1,
                    ], [
                        'value' => $selectedYear
                    ]) ?>
                </div>

                <?= $this->Form->button('View Report', ['class' => 'filter-btn']) ?>

            <?= $this->Form->end() ?>

        </div>

        <div class="side-note">
            Select a month and year to filter the report. The chart still shows all months in the selected year, while the table shows details for the selected month.
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('monthlyReportChart');

    if (!ctx || typeof Chart === 'undefined') {
        return;
    }

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [
                {
                    label: 'Lost Reports',
                    data: <?= json_encode($monthlyLostCounts) ?>,
                    backgroundColor: '#dc2626',
                    borderRadius: 10
                },
                {
                    label: 'Found Reports',
                    data: <?= json_encode($monthlyFoundCounts) ?>,
                    backgroundColor: '#16a34a',
                    borderRadius: 10
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
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
});
</script>