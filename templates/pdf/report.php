<?php
/**
 * @var string $reportTitle
 * @var bool $isAdmin
 * @var array $overviewLabels
 * @var array $overviewCounts
 * @var array $categoryLabels
 * @var array $categoryCounts
 * @var array $claimStatusLabels
 * @var array $claimStatusCounts
 * @var array $foundStatusLabels
 * @var array $foundStatusCounts
 * @var array $monthlyLabels
 * @var array $monthlyLostCounts
 * @var array $monthlyFoundCounts
 * @var array $monthlyTotals
 */
?>

<style>
    @page {
        size: A4 portrait;
        margin: 14mm;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        font-family: DejaVu Sans, sans-serif;
        color: #0f172a;
        background: #ffffff;
    }

    .report-page {
        width: 100%;
        padding: 18px;
    }

    .report-top {
        display: grid;
        grid-template-columns: 1.4fr 0.9fr;
        gap: 18px;
        margin-bottom: 20px;
    }

    .hero-card,
    .highlight-card,
    .panel-card,
    .table-card {
        border-radius: 24px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.06);
    }

    .hero-card {
        padding: 24px;
        background: linear-gradient(180deg, #eff6ff 0%, #ffffff 100%);
        display: grid;
        gap: 18px;
    }

    .hero-title {
        margin: 0;
        font-size: 28px;
        font-weight: 900;
        color: #0f172a;
    }

    .hero-subtitle {
        margin: 0;
        color: #475569;
        font-size: 13px;
        line-height: 1.7;
    }

    .metric-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .metric-card {
        padding: 16px;
        border-radius: 18px;
        background: #f8fafc;
        border: 1px solid #dbeafe;
    }

    .metric-card strong {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .metric-card span {
        display: block;
        font-size: 24px;
        font-weight: 900;
        color: #0f172a;
    }

    .highlight-card {
        padding: 22px;
        display: grid;
        gap: 14px;
    }

    .highlight-card h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 900;
        color: #0f172a;
    }

    .highlight-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 10px;
    }

    .highlight-list li {
        position: relative;
        padding-left: 18px;
        color: #475569;
        font-size: 12px;
        line-height: 1.6;
    }

    .highlight-list li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 7px;
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #2563eb;
    }

    .section-title {
        margin: 28px 0 14px;
        font-size: 16px;
        font-weight: 900;
        color: #0f172a;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: 1.4fr 0.9fr;
        gap: 18px;
    }

    .panel-card {
        padding: 20px;
    }

    .panel-card h5 {
        margin: 0 0 14px;
        font-size: 15px;
        font-weight: 900;
        color: #0f172a;
    }

    .chart-legend {
        display: grid;
        gap: 10px;
        margin-top: 14px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #475569;
        font-size: 12px;
    }

    .legend-swatch {
        width: 12px;
        height: 12px;
        border-radius: 4px;
    }

    .donut-svg,
    .line-svg {
        display: block;
        margin: 0 auto;
    }

    .donut-svg {
        width: 160px;
        height: 160px;
    }

    .donut-center {
        fill: #ffffff;
    }

    .line-svg {
        width: 100%;
        height: 220px;
    }

    .line-grid {
        stroke: #e2e8f0;
        stroke-width: 1;
    }

    .line-path-lost {
        fill: none;
        stroke: #ef4444;
        stroke-width: 3;
        stroke-linejoin: round;
        stroke-linecap: round;
    }

    .line-path-found {
        fill: none;
        stroke: #10b981;
        stroke-width: 3;
        stroke-linejoin: round;
        stroke-linecap: round;
    }

    .dot {
        stroke: #ffffff;
        stroke-width: 2;
    }

    .line-label {
        font-size: 10px;
        fill: #475569;
    }

    .table-card {
        padding: 20px;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        margin-top: 10px;
    }

    .report-table th,
    .report-table td {
        padding: 12px 14px;
        border: 1px solid #e2e8f0;
        text-align: left;
    }

    .report-table th {
        background: #eff6ff;
        color: #0f172a;
        font-weight: 800;
    }

    .report-table tbody tr:nth-child(odd) {
        background: #f8fafc;
    }

    .footer {
        margin-top: 24px;
        text-align: center;
        font-size: 11px;
        color: #64748b;
    }
</style>

<?php
    $summaryHighlights = [];
    foreach ($overviewLabels as $index => $label) {
        $summaryHighlights[] = sprintf('%s: %s', $label, $overviewCounts[$index] ?? 0);
    }

    $categoryTotal = max(1, array_sum($categoryCounts));
    $claimTotal = max(1, array_sum($claimStatusCounts));
    $foundTotal = max(1, array_sum($foundStatusCounts));

    $monthlyMax = max(1, max(array_merge($monthlyLostCounts, $monthlyFoundCounts)));
    $chartWidth = 520;
    $chartHeight = 200;
    $pointCount = max(1, count($monthlyLabels));
    $pointSpacing = $pointCount > 1 ? ($chartWidth - 40) / ($pointCount - 1) : 0;

    $lostPoints = [];
    $foundPoints = [];
    foreach ($monthlyLabels as $index => $label) {
        $x = 20 + ($pointSpacing * $index);
        $lostY = $chartHeight - 20 - (($monthlyLostCounts[$index] ?? 0) / $monthlyMax) * ($chartHeight - 40);
        $foundY = $chartHeight - 20 - (($monthlyFoundCounts[$index] ?? 0) / $monthlyMax) * ($chartHeight - 40);
        $lostPoints[] = sprintf('%1$.2F,%2$.2F', $x, $lostY);
        $foundPoints[] = sprintf('%1$.2F,%2$.2F', $x, $foundY);
    }

    $lostPath = implode(' ', array_map(fn($point, $i) => ($i === 0 ? 'M ' : 'L ') . $point, $lostPoints, array_keys($lostPoints)));
    $foundPath = implode(' ', array_map(fn($point, $i) => ($i === 0 ? 'M ' : 'L ') . $point, $foundPoints, array_keys($foundPoints)));

    $chartColors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
?>

<div class="report-page">
    <div class="report-top">
        <div class="hero-card">
            <h1 class="hero-title"><?= h($reportTitle) ?></h1>
            <p class="hero-subtitle">A polished monthly report for UiTEMU. This version mirrors your sample with a clean summary hero section, trend chart, distribution visuals, and a data table.</p>
            <div class="metric-grid">
                <?php foreach ($overviewLabels as $index => $label): ?>
                    <div class="metric-card">
                        <strong><?= h($label) ?></strong>
                        <span><?= h($overviewCounts[$index] ?? 0) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="highlight-card">
            <h3>Report Summary</h3>
            <ul class="highlight-list">
                <?php foreach ($summaryHighlights as $highlight): ?>
                    <li><?= h($highlight) ?></li>
                <?php endforeach; ?>
                <li>Top category: <?= h($categoryLabels[0] ?? 'No Data') ?>.</li>
                <li>Primary claim status: <?= h($claimStatusLabels[0] ?? 'No Data') ?>.</li>
                <li>Found item status leader: <?= h($foundStatusLabels[0] ?? 'No Data') ?>.</li>
            </ul>
        </div>
    </div>

    <div class="section-title">Charts & Trends</div>
    <div class="charts-grid">
        <div class="panel-card">
            <h5>Monthly Lost / Found Trend</h5>
            <svg class="line-svg" viewBox="0 0 560 220" xmlns="http://www.w3.org/2000/svg">
                <?php for ($i = 0; $i <= 4; $i++): ?>
                    <line x1="20" y1="<?= 20 + $i * 40 ?>" x2="540" y2="<?= 20 + $i * 40 ?>" class="line-grid" />
                <?php endfor; ?>
                <polyline points="<?= $lostPath ?>" class="line-path-lost" />
                <polyline points="<?= $foundPath ?>" class="line-path-found" />
                <?php foreach ($lostPoints as $point): ?>
                    <?php list($px, $py) = explode(',', $point); ?>
                    <circle cx="<?= $px ?>" cy="<?= $py ?>" r="4" fill="#ef4444" class="dot" />
                <?php endforeach; ?>
                <?php foreach ($foundPoints as $point): ?>
                    <?php list($px, $py) = explode(',', $point); ?>
                    <circle cx="<?= $px ?>" cy="<?= $py ?>" r="4" fill="#10b981" class="dot" />
                <?php endforeach; ?>
                <?php foreach ($monthlyLabels as $index => $label): ?>
                    <?php $x = 20 + ($pointSpacing * $index); ?>
                    <text x="<?= $x ?>" y="214" class="line-label" text-anchor="middle"><?= h($label) ?></text>
                <?php endforeach; ?>
            </svg>
            <div class="chart-legend">
                <div class="legend-item"><span class="legend-swatch" style="background: #ef4444;"></span>Lost Reports</div>
                <div class="legend-item"><span class="legend-swatch" style="background: #10b981;"></span>Found Reports</div>
            </div>
        </div>

        <div class="panel-card">
            <h5>Category Distribution</h5>
            <svg class="donut-svg" viewBox="0 0 124 124" xmlns="http://www.w3.org/2000/svg">
                <?php
                    $categoryAngle = 0;
                    foreach ($categoryLabels as $index => $label) {
                        $value = $categoryCounts[$index] ?? 0;
                        $percent = ($categoryTotal > 0 ? ($value / $categoryTotal) : 0) * 100;
                        $angle = $percent * 3.6;
                        $radius = 50;
                        $cx = 62;
                        $cy = 62;
                        $startX = $cx + $radius * cos(deg2rad($categoryAngle - 90));
                        $startY = $cy + $radius * sin(deg2rad($categoryAngle - 90));
                        $endAngle = $categoryAngle + $angle;
                        $endX = $cx + $radius * cos(deg2rad($endAngle - 90));
                        $endY = $cy + $radius * sin(deg2rad($endAngle - 90));
                        $largeArc = $angle > 180 ? 1 : 0;
                        $path = $angle > 0 ? sprintf('M %1$F %2$F A %3$F %3$F 0 %4$d 1 %5$F %6$F L %7$F %8$F Z', $startX, $startY, $radius, $largeArc, $endX, $endY, $cx, $cy) : '';
                ?>
                    <?php if ($path): ?>
                        <path d="<?= $path ?>" fill="<?= $chartColors[$index % count($chartColors)] ?>"></path>
                    <?php endif; ?>
                    <?php $categoryAngle += $angle; ?>
                <?php } ?>
                <circle cx="62" cy="62" r="34" class="donut-center" />
            </svg>
            <div class="chart-legend">
                <?php foreach ($categoryLabels as $index => $label): ?>
                    <div class="legend-item">
                        <span class="legend-swatch" style="background: <?= $chartColors[$index % count($chartColors)] ?>;"></span>
                        <?= h($label) ?> (<?= h($categoryCounts[$index] ?? 0) ?>)
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="section-title">Status Distribution</div>
    <div class="charts-grid">
        <div class="panel-card">
            <h5>Claim Status</h5>
            <svg class="donut-svg" viewBox="0 0 124 124" xmlns="http://www.w3.org/2000/svg">
                <?php
                    $claimAngle = 0;
                    foreach ($claimStatusLabels as $index => $label) {
                        $value = $claimStatusCounts[$index] ?? 0;
                        $percent = ($claimTotal > 0 ? ($value / $claimTotal) : 0) * 100;
                        $angle = $percent * 3.6;
                        $radius = 50;
                        $cx = 62;
                        $cy = 62;
                        $startX = $cx + $radius * cos(deg2rad($claimAngle - 90));
                        $startY = $cy + $radius * sin(deg2rad($claimAngle - 90));
                        $endAngle = $claimAngle + $angle;
                        $endX = $cx + $radius * cos(deg2rad($endAngle - 90));
                        $endY = $cy + $radius * sin(deg2rad($endAngle - 90));
                        $largeArc = $angle > 180 ? 1 : 0;
                        $path = $angle > 0 ? sprintf('M %1$F %2$F A %3$F %3$F 0 %4$d 1 %5$F %6$F L %7$F %8$F Z', $startX, $startY, $radius, $largeArc, $endX, $endY, $cx, $cy) : '';
                ?>
                    <?php if ($path): ?>
                        <path d="<?= $path ?>" fill="<?= $chartColors[$index % count($chartColors)] ?>"></path>
                    <?php endif; ?>
                    <?php $claimAngle += $angle; ?>
                <?php } ?>
                <circle cx="62" cy="62" r="34" class="donut-center" />
            </svg>
            <div class="chart-legend">
                <?php foreach ($claimStatusLabels as $index => $label): ?>
                    <div class="legend-item">
                        <span class="legend-swatch" style="background: <?= $chartColors[$index % count($chartColors)] ?>;"></span>
                        <?= h($label) ?> (<?= h($claimStatusCounts[$index] ?? 0) ?>)
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="panel-card">
            <h5>Found Item Status</h5>
            <svg class="donut-svg" viewBox="0 0 124 124" xmlns="http://www.w3.org/2000/svg">
                <?php
                    $foundAngle = 0;
                    foreach ($foundStatusLabels as $index => $label) {
                        $value = $foundStatusCounts[$index] ?? 0;
                        $percent = ($foundTotal > 0 ? ($value / $foundTotal) : 0) * 100;
                        $angle = $percent * 3.6;
                        $radius = 50;
                        $cx = 62;
                        $cy = 62;
                        $startX = $cx + $radius * cos(deg2rad($foundAngle - 90));
                        $startY = $cy + $radius * sin(deg2rad($foundAngle - 90));
                        $endAngle = $foundAngle + $angle;
                        $endX = $cx + $radius * cos(deg2rad($endAngle - 90));
                        $endY = $cy + $radius * sin(deg2rad($endAngle - 90));
                        $largeArc = $angle > 180 ? 1 : 0;
                        $path = $angle > 0 ? sprintf('M %1$F %2$F A %3$F %3$F 0 %4$d 1 %5$F %6$F L %7$F %8$F Z', $startX, $startY, $radius, $largeArc, $endX, $endY, $cx, $cy) : '';
                ?>
                    <?php if ($path): ?>
                        <path d="<?= $path ?>" fill="<?= $chartColors[$index % count($chartColors)] ?>"></path>
                    <?php endif; ?>
                    <?php $foundAngle += $angle; ?>
                <?php } ?>
                <circle cx="62" cy="62" r="34" class="donut-center" />
            </svg>
            <div class="chart-legend">
                <?php foreach ($foundStatusLabels as $index => $label): ?>
                    <div class="legend-item">
                        <span class="legend-swatch" style="background: <?= $chartColors[$index % count($chartColors)] ?>;"></span>
                        <?= h($label) ?> (<?= h($foundStatusCounts[$index] ?? 0) ?>)
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="section-title">Monthly Totals</div>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Lost Reports</th>
                    <th>Found Reports</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($monthlyTotals as $month => $values): ?>
                    <tr>
                        <td><?= h($month) ?></td>
                        <td><?= h($values['lost']) ?></td>
                        <td><?= h($values['found']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        Generated for <?= $isAdmin ? 'Admin' : 'User' ?> on <?= date('d F Y') ?>.
    </div>
</div>
