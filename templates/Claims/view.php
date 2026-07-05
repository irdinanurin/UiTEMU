<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Claim $claim
 */

$user = $this->request->getAttribute('identity');

if (!isset($role)) {
    if ($user) {
        if (method_exists($user, 'get')) {
            $role = $user->get('role');
        } else {
            $role = $user->role ?? null;
        }
    } else {
        $role = null;
    }
}

if (!isset($userId)) {
    if ($user) {
        if (method_exists($user, 'get')) {
            $userId = $user->get('id');
        } else {
            $userId = $user->id ?? null;
        }
    } else {
        $userId = null;
    }
}

$isAdmin = $role === 'admin';

if (!isset($isClaimant)) {
    $isClaimant = (int)$claim->user_id === (int)$userId;
}

if (!isset($isFinder)) {
    $isFinder = $claim->has('found_item') && (int)$claim->found_item->user_id === (int)$userId;
}

$status = strtolower((string)$claim->claim_status);

if ($status === 'pending') {
    $statusClass = 'status-pending';
} elseif ($status === 'approved') {
    $statusClass = 'status-approved';
} elseif ($status === 'rejected') {
    $statusClass = 'status-rejected';
} elseif ($status === 'completed') {
    $statusClass = 'status-completed';
} elseif ($status === 'disputed') {
    $statusClass = 'status-disputed';
} else {
    $statusClass = 'status-other';
}

$handoverStatus = strtolower((string)($claim->handover_status ?? 'Not Arranged'));

if ($handoverStatus === 'arranged') {
    $handoverStatusClass = 'status-approved';
} elseif ($handoverStatus === 'completed') {
    $handoverStatusClass = 'status-completed';
} elseif ($handoverStatus === 'disputed') {
    $handoverStatusClass = 'status-disputed';
} else {
    $handoverStatusClass = 'status-other';
}

$foundItemName = $claim->has('found_item') ? $claim->found_item->item_name : 'No Found Item';
$lostItemName = $claim->has('lost_item') ? $claim->lost_item->item_name : 'No Lost Item Linked';
$claimUserName = $claim->has('user') ? $claim->user->name : 'No User';
$finderName = ($claim->has('found_item') && $claim->found_item->has('user')) ? $claim->found_item->user->name : 'No Finder';

$imageExists = false;

if ($claim->has('found_item') && !empty($claim->found_item->image)) {
    $imagePath = WWW_ROOT . 'img' . DS . 'found_items' . DS . $claim->found_item->image;
    $imageExists = file_exists($imagePath);
}

$canFinderAct = $isFinder;
$canClaimantAct = $isClaimant;
$canReportProblem = $isFinder || $isClaimant;
?>

<style>
    .claim-view-page {
        max-width: 1250px;
        margin: 0 auto;
    }

    .claim-view-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        gap: 15px;
        flex-wrap: wrap;
    }

    .claim-view-header h2 {
        font-size: 38px;
        font-weight: 800;
        color: #111827;
        margin: 0;
    }

    .claim-view-header p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 15px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-link,
    .header-actions a,
    .header-actions form button {
        padding: 11px 16px;
        border-radius: 11px;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none;
        border: none;
        cursor: pointer;
        display: inline-block;
    }

    .back-btn {
        background: #e5e7eb;
        color: #374151;
    }

    .edit-btn {
        background: #f59e0b;
        color: white;
    }

    .delete-btn {
        background: #ef4444;
        color: white;
    }

    .approve-btn {
        background: #10b981;
        color: white;
    }

    .reject-btn {
        background: #ef4444;
        color: white;
    }

    .confirm-btn {
        background: #0ea5e9;
        color: white;
    }

    .received-btn {
        background: #22c55e;
        color: white;
    }

    .dispute-btn {
        background: #9333ea;
        color: white;
    }

    .claim-layout {
        display: grid;
        grid-template-columns: 370px 1fr;
        gap: 25px;
        align-items: start;
    }

    .preview-card,
    .details-card,
    .verification-card,
    .comparison-card,
    .action-card,
    .handover-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(15,23,42,0.08);
        overflow: hidden;
    }

    .preview-image {
        width: 100%;
        height: 235px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px;
    }

    .preview-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .no-image {
        color: #64748b;
        font-weight: 700;
    }

    .preview-info {
        padding: 22px;
    }

    .preview-info h3 {
        font-size: 25px;
        font-weight: 800;
        color: #111827;
        margin: 0 0 16px;
        text-transform: capitalize;
    }

    .details-card,
    .verification-card,
    .comparison-card,
    .action-card,
    .handover-card {
        padding: 24px;
    }

    .details-card h3,
    .verification-card h3,
    .comparison-card h3,
    .action-card h3,
    .handover-card h3 {
        font-size: 25px;
        font-weight: 800;
        color: #111827;
        margin: 0 0 8px;
    }

    .section-note {
        color: #64748b;
        margin-bottom: 20px;
        font-size: 14px;
        line-height: 1.6;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 12px;
        font-size: 15px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 10px;
    }

    .info-label {
        color: #64748b;
        font-weight: 800;
    }

    .info-value {
        color: #111827;
        text-align: right;
        font-weight: 700;
        max-width: 65%;
        word-break: break-word;
    }

    .status-badge {
        display: inline-block;
        padding: 7px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
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

    .status-completed {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-disputed {
        background: #f3e8ff;
        color: #6b21a8;
    }

    .status-other {
        background: #e0e7ff;
        color: #3730a3;
    }

    .main-stack {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .comparison-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .mini-card {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 18px;
    }

    .mini-card h4 {
        font-size: 18px;
        font-weight: 800;
        color: #111827;
        margin: 0 0 15px;
    }

    .proof-image {
        margin-top: 8px;
    }

    .proof-image img {
        max-width: 260px;
        height: auto;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 15px;
    }

    .action-buttons a,
    .action-buttons form button {
        padding: 12px 18px;
        border-radius: 12px;
        border: none;
        font-weight: 800;
        cursor: pointer;
        text-decoration: none;
    }

    .completed-message {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        padding: 16px;
        border-radius: 13px;
        font-weight: 800;
        color: #374151;
        line-height: 1.6;
    }

    .pickup-code-box {
        background: #0f172a;
        color: white;
        padding: 18px;
        border-radius: 16px;
        margin-bottom: 18px;
    }

    .pickup-code-box span {
        display: block;
        color: #cbd5e1;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .pickup-code-box strong {
        font-size: 28px;
        letter-spacing: 1px;
    }

    .approve-form {
        margin-top: 16px;
        display: grid;
        gap: 14px;
    }

    .approve-form label {
        display: block;
        margin-bottom: 7px;
        color: #334155;
        font-size: 14px;
        font-weight: 800;
    }

    .approve-form input,
    .approve-form textarea {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 14px;
        color: #0f172a;
        box-sizing: border-box;
    }

    .approve-form textarea {
        min-height: 90px;
        resize: vertical;
    }

    .approve-submit {
        width: fit-content;
        background: #10b981;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 18px;
        font-weight: 900;
        cursor: pointer;
    }

    @media (max-width: 1000px) {
        .claim-layout {
            grid-template-columns: 1fr;
        }

        .comparison-grid {
            grid-template-columns: 1fr;
        }

        .info-row {
            flex-direction: column;
        }

        .info-value {
            text-align: left;
            max-width: 100%;
        }
    }
</style>

<div class="claim-view-page">

    <div class="claim-view-header">
        <div>
            <h2>Claim #<?= h($claim->id) ?></h2>
            <p>Review claim verification, finder decision, and handover progress.</p>
        </div>

        <div class="header-actions">
            <?= $this->Html->link(
                'Back to Claims',
                ['action' => 'index'],
                ['class' => 'btn-link back-btn']
            ) ?>

            <?php if ($isAdmin): ?>
                <?= $this->Html->link(
                    'Edit Claim',
                    ['action' => 'edit', $claim->id],
                    ['class' => 'btn-link edit-btn']
                ) ?>

                <?= $this->Form->postLink(
                    'Delete Claim',
                    ['action' => 'delete', $claim->id],
                    [
                        'confirm' => __('Are you sure you want to delete claim # {0}?', $claim->id),
                        'class' => 'delete-btn'
                    ]
                ) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="claim-layout">

        <div class="preview-card">
            <div class="preview-image">
                <?php if ($claim->has('found_item') && !empty($claim->found_item->image) && $imageExists): ?>
                    <?= $this->Html->image('found_items/' . $claim->found_item->image, [
                        'alt' => $foundItemName
                    ]) ?>
                <?php else: ?>
                    <div class="no-image">No Image</div>
                <?php endif; ?>
            </div>

            <div class="preview-info">
                <h3><?= h($foundItemName) ?></h3>

                <div class="info-row">
                    <span class="info-label">Claim Status</span>
                    <span class="info-value">
                        <span class="status-badge <?= $statusClass ?>">
                            <?= h($claim->claim_status) ?>
                        </span>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Handover Status</span>
                    <span class="info-value">
                        <span class="status-badge <?= $handoverStatusClass ?>">
                            <?= h($claim->handover_status ?? 'Not Arranged') ?>
                        </span>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Claimant</span>
                    <span class="info-value"><?= h($claimUserName) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Finder</span>
                    <span class="info-value"><?= h($finderName) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Lost Item</span>
                    <span class="info-value"><?= h($lostItemName) ?></span>
                </div>
            </div>
        </div>

        <div class="main-stack">

            <div class="details-card">
                <h3>Claim Summary</h3>
                <p class="section-note">
                    Basic claim information for the finder, claimant, and admin.
                </p>

                <div class="info-row">
                    <span class="info-label">Claimant</span>
                    <span class="info-value"><?= h($claimUserName) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Finder</span>
                    <span class="info-value"><?= h($finderName) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Found Item</span>
                    <span class="info-value"><?= h($foundItemName) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Lost Item</span>
                    <span class="info-value"><?= h($lostItemName) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Created At</span>
                    <span class="info-value"><?= h($claim->created_at) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Updated At</span>
                    <span class="info-value"><?= h($claim->updated_at) ?></span>
                </div>
            </div>

            <?php if ($isAdmin || $isFinder || $isClaimant): ?>
                <div class="verification-card">
                    <h3>Claimant Verification Answer</h3>
                    <p class="section-note">
                        These are the answers submitted by the claimant to prove ownership.
                    </p>

                    <div class="info-row">
                        <span class="info-label">Claim Reason</span>
                        <span class="info-value"><?= h($claim->claim_reason ?: '-') ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Unique Item Details</span>
                        <span class="info-value"><?= h($claim->item_details ?: '-') ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Lost Location Answer</span>
                        <span class="info-value"><?= h($claim->lost_location ?: '-') ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Lost Date Answer</span>
                        <span class="info-value"><?= h($claim->lost_date ?: '-') ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Proof Image</span>
                        <span class="info-value">
                            <?php if (!empty($claim->proof_image)): ?>
                                <div class="proof-image">
                                    <?= $this->Html->image('proofs/' . $claim->proof_image) ?>
                                </div>
                            <?php else: ?>
                                No proof image uploaded
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($isAdmin || $isFinder): ?>
                <div class="comparison-card">
                    <h3>Finder Verification Comparison</h3>
                    <p class="section-note">
                        Finder or admin can compare the found item details with the claimant's lost item report.
                        Private details are not shown to unrelated users.
                    </p>

                    <div class="comparison-grid">

                        <div class="mini-card">
                            <h4>Found Item Report</h4>

                            <?php if ($claim->has('found_item')): ?>
                                <div class="info-row">
                                    <span class="info-label">Item Name</span>
                                    <span class="info-value"><?= h($claim->found_item->item_name) ?></span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Category</span>
                                    <span class="info-value"><?= h($claim->found_item->category) ?></span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Description</span>
                                    <span class="info-value"><?= h($claim->found_item->description) ?></span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Private Details</span>
                                    <span class="info-value"><?= h($claim->found_item->private_details) ?></span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Found Location</span>
                                    <span class="info-value"><?= h($claim->found_item->location) ?></span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Date Found</span>
                                    <span class="info-value"><?= h($claim->found_item->date_found) ?></span>
                                </div>
                            <?php else: ?>
                                <p>No found item linked.</p>
                            <?php endif; ?>
                        </div>

                        <div class="mini-card">
                            <h4>Student Lost Item Report</h4>

                            <?php if ($claim->has('lost_item')): ?>
                                <div class="info-row">
                                    <span class="info-label">Item Name</span>
                                    <span class="info-value"><?= h($claim->lost_item->item_name) ?></span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Category</span>
                                    <span class="info-value"><?= h($claim->lost_item->category) ?></span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Description</span>
                                    <span class="info-value"><?= h($claim->lost_item->description) ?></span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Private Details</span>
                                    <span class="info-value"><?= h($claim->lost_item->private_details) ?></span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Lost Location</span>
                                    <span class="info-value"><?= h($claim->lost_item->location) ?></span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Date Lost</span>
                                    <span class="info-value"><?= h($claim->lost_item->date_lost) ?></span>
                                </div>
                            <?php else: ?>
                                <p>No lost item linked.</p>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            <?php endif; ?>

            <?php if ($claim->claim_status === 'Pending' && $isFinder): ?>
                <div class="action-card">
                    <h3>Finder Decision</h3>
                    <p class="section-note">
                        If the claimant's answers match the item, accept the claim and arrange a safe public handover.
                    </p>

                    <?= $this->Form->create(null, [
                        'url' => ['action' => 'approve', $claim->id],
                        'class' => 'approve-form'
                    ]) ?>

                    <?= $this->Form->control('meeting_location', [
                        'label' => 'Meeting Location',
                        'placeholder' => 'Example: Library Lobby, Faculty Entrance, Café Area',
                        'required' => false
                    ]) ?>

                    <?= $this->Form->control('meeting_datetime', [
                        'label' => 'Meeting Date and Time',
                        'type' => 'datetime-local',
                        'required' => false
                    ]) ?>

                    <?= $this->Form->control('handover_notes', [
                        'label' => 'Handover Notes',
                        'type' => 'textarea',
                        'placeholder' => 'Example: Please bring your student ID and show pickup code.',
                        'required' => false
                    ]) ?>

                    <?= $this->Form->button('Accept Claim & Generate Pickup Code', [
                        'class' => 'approve-submit'
                    ]) ?>

                    <?= $this->Form->end() ?>

                    <div class="action-buttons">
                        <?= $this->Form->postLink(
                            'Reject Claim',
                            ['action' => 'reject', $claim->id],
                            [
                                'confirm' => 'Are you sure you want to reject this claim?',
                                'class' => 'reject-btn'
                            ]
                        ) ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (in_array($claim->claim_status, ['Approved', 'Completed', 'Disputed'])): ?>
                <div class="handover-card">
                    <h3>Handover Arrangement</h3>
                    <p class="section-note">
                        The pickup code is used to verify the face-to-face handover between finder and claimant.
                    </p>

                    <?php if (!empty($claim->pickup_code)): ?>
                        <div class="pickup-code-box">
                            <span>Pickup Code</span>
                            <strong><?= h($claim->pickup_code) ?></strong>
                        </div>
                    <?php endif; ?>

                    <div class="info-row">
                        <span class="info-label">Meeting Location</span>
                        <span class="info-value"><?= h($claim->meeting_location ?? '-') ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Meeting Date/Time</span>
                        <span class="info-value"><?= h($claim->meeting_datetime ?? '-') ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Handover Notes</span>
                        <span class="info-value"><?= h($claim->handover_notes ?? '-') ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Finder Confirmed</span>
                        <span class="info-value">
                            <?= (int)$claim->finder_confirmed === 1 ? 'Yes' : 'No' ?>
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Claimant Confirmed</span>
                        <span class="info-value">
                            <?= (int)$claim->claimant_confirmed === 1 ? 'Yes' : 'No' ?>
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Handed Over At</span>
                        <span class="info-value"><?= h($claim->handed_over_at ?? '-') ?></span>
                    </div>

                    <?php if ($claim->claim_status === 'Approved'): ?>
                        <div class="action-buttons">
                            <?php if ($canFinderAct && (int)$claim->finder_confirmed !== 1): ?>
                                <?= $this->Form->postLink(
                                    'Confirm Handed Over',
                                    ['action' => 'confirmHandedOver', $claim->id],
                                    [
                                        'confirm' => 'Confirm that you have handed over the item?',
                                        'class' => 'confirm-btn'
                                    ]
                                ) ?>
                            <?php endif; ?>

                            <?php if ($canClaimantAct && (int)$claim->claimant_confirmed !== 1): ?>
                                <?= $this->Form->postLink(
                                    'Confirm Received',
                                    ['action' => 'confirmReceived', $claim->id],
                                    [
                                        'confirm' => 'Confirm that you have received the item?',
                                        'class' => 'received-btn'
                                    ]
                                ) ?>
                            <?php endif; ?>

                            <?php if ($canReportProblem): ?>
                                <?= $this->Form->postLink(
                                    'Report Problem',
                                    ['action' => 'dispute', $claim->id],
                                    [
                                        'confirm' => 'Report a problem with this handover?',
                                        'class' => 'dispute-btn'
                                    ]
                                ) ?>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($claim->claim_status === 'Completed'): ?>
                        <div class="completed-message">
                            This claim has been completed. The item has been returned successfully.
                        </div>
                    <?php elseif ($claim->claim_status === 'Disputed'): ?>
                        <div class="completed-message">
                            This claim has been marked as having a problem. Admin should review this case.
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>

    </div>

</div>