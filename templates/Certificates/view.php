<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Certificate $certificate
 */

$identity = $this->request->getAttribute('identity');
$sessionUser = $this->request->getSession()->read('Auth.User');

$currentUserId = null;
$currentUserRole = null;

if ($identity) {
    if (method_exists($identity, 'get')) {
        $currentUserId = $identity->get('id');
        $currentUserRole = $identity->get('role');
    } else {
        $currentUserId = $identity->id ?? null;
        $currentUserRole = $identity->role ?? null;
    }
}

if (!$currentUserId && !empty($sessionUser['id'])) {
    $currentUserId = $sessionUser['id'];
}

if (!$currentUserRole && !empty($sessionUser['role'])) {
    $currentUserRole = $sessionUser['role'];
}

$isAdmin = $currentUserRole === 'admin';
$isOwner = (int)$certificate->user_id === (int)$currentUserId;
$canView = $isAdmin || $isOwner;

$recipientName = $certificate->has('user') ? $certificate->user->name : 'No User';
$itemName = $certificate->has('found_item') ? $certificate->found_item->item_name : 'No Item Linked';

$status = strtolower((string)($certificate->status ?? 'Issued'));

if ($status === 'issued') {
    $statusClass = 'cv-status-issued';
} elseif ($status === 'draft') {
    $statusClass = 'cv-status-draft';
} else {
    $statusClass = 'cv-status-other';
}
?>

<style>
    .cv-page {
        display: grid;
        grid-template-columns: 1fr 260px;
        gap: 12px;
        align-items: start;
    }

    .cv-side-card {
        grid-column: 2;
        grid-row: 1;
        margin-top: 0;
    }

    .cv-main-card {
        grid-column: 1;
        grid-row: 1;
    }

    .cv-side-card,
    .cv-main-card,
    .cv-preview-card,
    .cv-info-card,
    .cv-section-card {
        background: #ffffff;
        border-radius: 22px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    .cv-side-card {
        padding: 22px;
    }

    .cv-side-card h4 {
        margin: 0 0 16px;
        font-size: 20px;
        color: #0f172a;
    }

    .cv-side-links {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .cv-side-links a,
    .cv-side-links button {
        display: block;
        width: 100%;
        text-align: center;
        padding: 12px 14px;
        border-radius: 12px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-weight: 700;
        font-size: 14px;
        transition: 0.2s ease;
    }

    .cv-link-blue { background: #2563eb; color: #fff; }
    .cv-link-green { background: #10b981; color: #fff; }
    .cv-link-amber { background: #f59e0b; color: #fff; }
    .cv-link-red { background: #ef4444; color: #fff; }
    .cv-link-gray { background: #e2e8f0; color: #0f172a; }

    .cv-side-links a:hover,
    .cv-side-links button:hover {
        transform: translateY(-1px);
        opacity: 0.96;
        color: inherit;
    }

    .cv-main-card {
        padding: 18px;
    }

    .cv-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        gap: 16px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .cv-title {
        margin: 0 0 6px;
        font-size: 38px;
        font-weight: 800;
        color: #0f172a;
    }

    .cv-subtitle {
        margin: 0;
        color: #64748b;
        font-size: 15px;
    }

    .cv-status {
        display: inline-block;
        padding: 8px 15px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
    }

    .cv-status-issued {
        background: #dcfce7;
        color: #166534;
    }

    .cv-status-draft {
        background: #fef3c7;
        color: #92400e;
    }

    .cv-status-other {
        background: #e0e7ff;
        color: #3730a3;
    }

    .cv-layout {
        display: grid;
        grid-template-columns: 420px 1fr;
        gap: 24px;
        align-items: start;
    }

    .cv-preview-card {
        padding: 24px;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
    }

    .cv-certificate-preview {
        border: 8px solid #1e3a5f;
        padding: 35px 25px;
        text-align: center;
        min-height: 330px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: #ffffff;
    }

    .cv-preview-small {
        text-align: right;
        font-size: 11px;
        color: #334155;
        margin-bottom: 24px;
        font-weight: 700;
    }

    .cv-preview-title {
        font-size: 22px;
        letter-spacing: 5px;
        font-weight: 800;
        color: #1e3a5f;
        margin-bottom: 28px;
    }

    .cv-preview-presented {
        font-size: 13px;
        color: #334155;
        margin-bottom: 15px;
    }

    .cv-preview-name {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 24px;
    }

    .cv-preview-text {
        font-size: 13px;
        color: #334155;
        line-height: 1.6;
        margin-bottom: 18px;
    }

    .cv-preview-item {
        border: 1px solid #cbd5e1;
        padding: 12px;
        font-size: 13px;
        margin: 0 auto 20px;
        width: 80%;
    }

    .cv-preview-date {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 28px;
    }

    .cv-preview-line {
        width: 150px;
        height: 1px;
        background: #0f172a;
        margin: 0 auto 8px;
    }

    .cv-preview-signature {
        font-size: 12px;
        color: #334155;
    }

    .cv-info-card {
        padding: 24px;
    }

    .cv-info-card h3 {
        margin: 0 0 18px;
        font-size: 28px;
        color: #0f172a;
    }

    .cv-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px 22px;
    }

    .cv-info-item {
        padding: 14px 16px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .cv-info-label {
        display: block;
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .cv-info-value {
        color: #0f172a;
        font-size: 16px;
        font-weight: 700;
        word-break: break-word;
    }

    .cv-actions-inline {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 22px;
    }

    .cv-actions-inline a,
    .cv-actions-inline button {
        padding: 11px 16px;
        border-radius: 12px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-weight: 800;
        font-size: 14px;
    }

    .cv-section-card {
        padding: 24px;
        margin-top: 24px;
    }

    .cv-section-card h4 {
        margin: 0 0 14px;
        font-size: 24px;
        color: #0f172a;
    }

    .cv-section-card p {
        margin: 0;
        color: #334155;
        font-size: 15px;
        line-height: 1.75;
    }

    .cv-empty {
        color: #64748b;
        font-weight: 600;
    }

    @media (max-width: 1100px) {
        .cv-page {
            grid-template-columns: 1fr;
        }

        .cv-layout {
            grid-template-columns: 1fr;
        }

        .cv-side-card {
            position: static;
        }
    }

    @media (max-width: 700px) {
        .cv-info-grid {
            grid-template-columns: 1fr;
        }

        .cv-title {
            font-size: 30px;
        }
    }
</style>

<div class="cv-page">

    <aside class="cv-side-card">
        <h4><?= __('Actions') ?></h4>

        <div class="cv-side-links">
            <?= $this->Html->link(
                __('Back to Certificates'),
                ['action' => 'index'],
                ['class' => 'cv-link-gray']
            ) ?>

            <?php if ($canView): ?>
                <?= $this->Html->link(
                    __('Download PDF'),
                    ['action' => 'downloadPdf', $certificate->id],
                    ['class' => 'cv-link-green']
                ) ?>
            <?php endif; ?>

            <?php if ($isAdmin): ?>
                <?= $this->Html->link(
                    __('Edit Certificate'),
                    ['action' => 'edit', $certificate->id],
                    ['class' => 'cv-link-amber']
                ) ?>

                <?= $this->Html->link(
                    __('New Certificate'),
                    ['action' => 'add'],
                    ['class' => 'cv-link-blue']
                ) ?>

                <?= $this->Form->postLink(
                    __('Delete Certificate'),
                    ['action' => 'delete', $certificate->id],
                    [
                        'confirm' => __('Are you sure you want to delete certificate # {0}?', $certificate->id),
                        'class' => 'cv-link-red'
                    ]
                ) ?>
            <?php endif; ?>
        </div>
    </aside>

    <div class="cv-main-card">

        <div class="cv-header">
            <div>
                <h1 class="cv-title">Certificate Details</h1>
                <p class="cv-subtitle">View certificate information and download the PDF copy.</p>
            </div>

            <span class="cv-status <?= $statusClass ?>">
                <?= h($certificate->status ?? 'Issued') ?>
            </span>
        </div>

        <div class="cv-layout">

            <div class="cv-preview-card">
                <div class="cv-certificate-preview">

                    <div class="cv-preview-small">
                        Certificate No: <?= h($certificate->certificate_no) ?>
                    </div>

                    <div class="cv-preview-title">
                        CERTIFICATE OF APPRECIATION
                    </div>

                    <div class="cv-preview-presented">
                        This certificate is proudly presented to
                    </div>

                    <div class="cv-preview-name">
                        <?= h($recipientName) ?>
                    </div>

                    <div class="cv-preview-text">
                        In recognition of honesty, responsibility, and contribution to the UiTEMU Lost & Found System.
                    </div>

                    <div class="cv-preview-item">
                        <strong>Reported Found Item:</strong><br>
                        <?= h($itemName) ?>
                    </div>

                    <div class="cv-preview-date">
                        Issued Date: <?= h($certificate->issue_date) ?>
                    </div>

                    <div class="cv-preview-line"></div>

                    <div class="cv-preview-signature">
                        Authorized Signature<br>
                        UiTEMU Lost & Found System
                    </div>

                </div>
            </div>

            <div class="cv-info-card">
                <h3>Certificate Information</h3>

                <div class="cv-info-grid">

                    <div class="cv-info-item">
                        <span class="cv-info-label">Recipient</span>
                        <span class="cv-info-value"><?= h($recipientName) ?></span>
                    </div>

                    <div class="cv-info-item">
                        <span class="cv-info-label">Certificate No</span>
                        <span class="cv-info-value"><?= h($certificate->certificate_no) ?></span>
                    </div>

                    <div class="cv-info-item">
                        <span class="cv-info-label">Reported Item</span>
                        <span class="cv-info-value"><?= h($itemName) ?></span>
                    </div>

                    <div class="cv-info-item">
                        <span class="cv-info-label">Issue Date</span>
                        <span class="cv-info-value"><?= h($certificate->issue_date) ?></span>
                    </div>

                    <div class="cv-info-item">
                        <span class="cv-info-label">Status</span>
                        <span class="cv-info-value">
                            <span class="cv-status <?= $statusClass ?>">
                                <?= h($certificate->status ?? 'Issued') ?>
                            </span>
                        </span>
                    </div>

                    <div class="cv-info-item">
                        <span class="cv-info-label">Certificate ID</span>
                        <span class="cv-info-value">#<?= h($certificate->id) ?></span>
                    </div>

                    <div class="cv-info-item">
                        <span class="cv-info-label">Created At</span>
                        <span class="cv-info-value"><?= h($certificate->created_at) ?></span>
                    </div>

                    <div class="cv-info-item">
                        <span class="cv-info-label">Updated At</span>
                        <span class="cv-info-value"><?= h($certificate->updated_at) ?></span>
                    </div>

                </div>

                <div class="cv-actions-inline">
                    <?php if ($canView): ?>
                        <?= $this->Html->link(
                            'Download PDF',
                            ['action' => 'downloadPdf', $certificate->id],
                            ['class' => 'cv-link-green']
                        ) ?>
                    <?php endif; ?>

                    <?php if ($isAdmin): ?>
                        <?= $this->Html->link(
                            'Edit',
                            ['action' => 'edit', $certificate->id],
                            ['class' => 'cv-link-amber']
                        ) ?>

                        <?= $this->Form->postLink(
                            'Delete',
                            ['action' => 'delete', $certificate->id],
                            [
                                'confirm' => __('Are you sure you want to delete certificate # {0}?', $certificate->id),
                                'class' => 'cv-link-red'
                            ]
                        ) ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <div class="cv-section-card">
            <h4>Description</h4>
            <p>
                <?= !empty($certificate->description)
                    ? nl2br(h($certificate->description))
                    : '<span class="cv-empty">No description provided.</span>'
                ?>
            </p>
        </div>

    </div>
</div>