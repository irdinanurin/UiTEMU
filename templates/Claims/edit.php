<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Claim $claim
 * @var \Cake\Collection\CollectionInterface|array $users
 * @var \Cake\Collection\CollectionInterface|array $foundItems
 * @var \Cake\Collection\CollectionInterface|array $lostItems
 */
?>

<style>
    .claim-edit-page {
        padding: 10px 0 35px;
    }

    .claim-edit-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 26px;
    }

    .claim-edit-header h1 {
        margin: 0 0 8px;
        font-size: 52px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -1px;
    }

    .claim-edit-header p {
        margin: 0;
        font-size: 20px;
        color: #64748b;
    }

    .claim-edit-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .claim-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 13px 20px;
        border-radius: 14px;
        text-decoration: none;
        font-weight: 800;
        font-size: 16px;
        border: none;
        cursor: pointer;
        transition: 0.2s ease;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
    }

    .claim-btn:hover {
        transform: translateY(-2px);
        color: inherit;
    }

    .claim-btn-dark {
        background: #111827;
        color: #fff;
    }

    .claim-btn-blue {
        background: #2563eb;
        color: #fff;
    }

    .claim-btn-red {
        background: #ef4444;
        color: #fff;
    }

    .claim-edit-layout {
        display: grid;
        grid-template-columns: 0.9fr 1.5fr;
        gap: 24px;
        align-items: start;
    }

    .claim-summary-card,
    .claim-form-card,
    .claim-help-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        padding: 28px;
    }

    .claim-summary-card h2,
    .claim-form-card h2 {
        margin: 0 0 8px;
        font-size: 34px;
        font-weight: 800;
        color: #0f172a;
    }

    .claim-summary-card p,
    .claim-form-card .subtitle {
        margin: 0 0 24px;
        color: #64748b;
        font-size: 17px;
    }

    .claim-status-badge {
        display: inline-block;
        padding: 9px 18px;
        border-radius: 999px;
        font-size: 15px;
        font-weight: 900;
        text-transform: uppercase;
        margin-bottom: 22px;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-approved {
        background: #dcfce7;
        color: #166534;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .claim-info-list {
        display: grid;
        gap: 14px;
    }

    .claim-info-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px 18px;
    }

    .claim-info-label {
        display: block;
        font-size: 14px;
        font-weight: 800;
        color: #64748b;
        margin-bottom: 6px;
    }

    .claim-info-value {
        display: block;
        color: #0f172a;
        font-size: 19px;
        font-weight: 900;
        word-break: break-word;
    }

    .claim-help-card {
        margin-top: 20px;
        background: #faf5ff;
        border: 1px solid #e9d5ff;
    }

    .claim-help-card strong {
        display: block;
        color: #7c3aed;
        font-size: 18px;
        margin-bottom: 8px;
    }

    .claim-help-card p {
        margin: 0;
        color: #334155;
        font-size: 15px;
        line-height: 1.6;
    }

    .claim-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 20px;
    }

    .claim-form-grid .full {
        grid-column: 1 / -1;
    }

    .claim-edit-page label {
        display: block;
        margin-bottom: 8px;
        color: #334155;
        font-size: 15px;
        font-weight: 800;
    }

    .claim-edit-page input,
    .claim-edit-page select,
    .claim-edit-page textarea {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 14px;
        padding: 14px 16px;
        font-size: 16px;
        color: #0f172a;
        background: #fff;
        box-sizing: border-box;
        transition: 0.2s ease;
    }

    .claim-edit-page textarea {
        min-height: 115px;
        resize: vertical;
    }

    .claim-edit-page input:focus,
    .claim-edit-page select:focus,
    .claim-edit-page textarea:focus {
        outline: none;
        border-color: #7c3aed;
        box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.12);
    }

    .claim-submit-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 26px;
    }

    .claim-submit {
        background: #7c3aed;
        color: #fff;
        border: none;
        border-radius: 14px;
        padding: 14px 24px;
        font-size: 17px;
        font-weight: 900;
        cursor: pointer;
        box-shadow: 0 10px 24px rgba(124, 58, 237, 0.22);
    }

    .claim-cancel {
        background: #e2e8f0;
        color: #0f172a;
        text-decoration: none;
        border-radius: 14px;
        padding: 14px 22px;
        font-size: 17px;
        font-weight: 900;
    }

    @media (max-width: 1100px) {
        .claim-edit-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .claim-edit-header h1 {
            font-size: 38px;
        }

        .claim-form-grid {
            grid-template-columns: 1fr;
        }

        .claim-edit-actions {
            width: 100%;
            flex-direction: column;
        }

        .claim-btn {
            width: 100%;
        }
    }
</style>

<?php
$status = strtolower((string)($claim->claim_status ?? 'Pending'));

if ($status === 'approved') {
    $statusClass = 'status-approved';
} elseif ($status === 'rejected') {
    $statusClass = 'status-rejected';
} else {
    $statusClass = 'status-pending';
}
?>

<div class="claim-edit-page">

    <div class="claim-edit-header">
        <div>
            <h1>Edit Claim</h1>
            <p>Update claim details and verification status.</p>
        </div>

        <div class="claim-edit-actions">
            <?= $this->Html->link('← Back to Claims', ['action' => 'index'], ['class' => 'claim-btn claim-btn-dark']) ?>

            <?= $this->Html->link('View Claim', ['action' => 'view', $claim->id], ['class' => 'claim-btn claim-btn-blue']) ?>

            <?= $this->Form->postLink(
                'Delete',
                ['action' => 'delete', $claim->id],
                [
                    'confirm' => __('Are you sure you want to delete claim # {0}?', $claim->id),
                    'class' => 'claim-btn claim-btn-red'
                ]
            ) ?>
        </div>
    </div>

    <div class="claim-edit-layout">

        <div>
            <div class="claim-summary-card">
                <h2>Claim Summary</h2>
                <p>Current claim information overview.</p>

                <span class="claim-status-badge <?= $statusClass ?>">
                    <?= h($claim->claim_status ?? 'Pending') ?>
                </span>

                <div class="claim-info-list">
                    <div class="claim-info-item">
                        <span class="claim-info-label">Claim ID</span>
                        <span class="claim-info-value">#<?= h($claim->id) ?></span>
                    </div>

                    <div class="claim-info-item">
                        <span class="claim-info-label">User ID</span>
                        <span class="claim-info-value"><?= h($claim->user_id ?? '-') ?></span>
                    </div>

                    <div class="claim-info-item">
                        <span class="claim-info-label">Found Item ID</span>
                        <span class="claim-info-value"><?= h($claim->found_item_id ?? '-') ?></span>
                    </div>

                    <div class="claim-info-item">
                        <span class="claim-info-label">Lost Item ID</span>
                        <span class="claim-info-value"><?= h($claim->lost_item_id ?? '-') ?></span>
                    </div>

                    <div class="claim-info-item">
                        <span class="claim-info-label">Created At</span>
                        <span class="claim-info-value" style="font-size:16px;">
                            <?= h($claim->created_at ?? '-') ?>
                        </span>
                    </div>

                    <div class="claim-info-item">
                        <span class="claim-info-label">Updated At</span>
                        <span class="claim-info-value" style="font-size:16px;">
                            <?= h($claim->updated_at ?? '-') ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="claim-help-card">
                <strong>Admin Verification Tip</strong>
                <p>
                    Use this page to update the claim status after checking the student's verification answers
                    and matching them with the found item report.
                </p>
            </div>
        </div>

        <div class="claim-form-card">
            <h2>Claim Details</h2>
            <p class="subtitle">Edit the claim information below.</p>

            <?= $this->Form->create($claim) ?>

            <div class="claim-form-grid">

                <div>
                    <?= $this->Form->control('user_id', [
                        'label' => 'Claimant User',
                        'options' => $users ?? [],
                        'empty' => 'Select User',
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>

                <div>
                    <?= $this->Form->control('found_item_id', [
                        'label' => 'Found Item',
                        'options' => $foundItems ?? [],
                        'empty' => 'Select Found Item',
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>

                <?php if (isset($lostItems)): ?>
                    <div>
                        <?= $this->Form->control('lost_item_id', [
                            'label' => 'Lost Item Report',
                            'options' => $lostItems,
                            'empty' => 'Select Lost Item',
                            'templates' => ['inputContainer' => '{{content}}']
                        ]) ?>
                    </div>
                <?php endif; ?>

                <div>
                    <?= $this->Form->control('claim_status', [
                        'label' => 'Claim Status',
                        'type' => 'select',
                        'options' => [
                            'Pending' => 'Pending',
                            'Approved' => 'Approved',
                            'Rejected' => 'Rejected'
                        ],
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>

                <div class="full">
                    <?= $this->Form->control('claim_reason', [
                        'label' => 'Claim Reason',
                        'type' => 'textarea',
                        'placeholder' => 'Reason why the student claims this item...',
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>

                <div class="full">
                    <?= $this->Form->control('item_details', [
                        'label' => 'Unique Item Details',
                        'type' => 'textarea',
                        'placeholder' => 'Specific details that prove ownership...',
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>

                <div>
                    <?= $this->Form->control('lost_location', [
                        'label' => 'Lost Location',
                        'placeholder' => 'Where was the item lost?',
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>

                <div>
                    <?= $this->Form->control('lost_date', [
                        'label' => 'Lost Date',
                        'type' => 'date',
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>

                <div class="full">
                    <?= $this->Form->control('admin_notes', [
                        'label' => 'Admin Notes',
                        'type' => 'textarea',
                        'placeholder' => 'Write admin verification notes here...',
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>

            </div>

            <div class="claim-submit-row">
                <?= $this->Form->button('Save Claim', ['class' => 'claim-submit']) ?>

                <?= $this->Html->link('Cancel', ['action' => 'view', $claim->id], ['class' => 'claim-cancel']) ?>
            </div>

            <?= $this->Form->end() ?>
        </div>

    </div>
</div>