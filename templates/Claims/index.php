<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\FoundItem> $foundItems
 */

$loginUser = $this->request->getAttribute('identity');

if (!isset($role)) {
    if ($loginUser && method_exists($loginUser, 'get')) {
        $role = $loginUser->get('role');
    } elseif ($loginUser) {
        $role = $loginUser->role ?? null;
    } else {
        $role = null;
    }
}

if (!isset($userId)) {
    if ($loginUser && method_exists($loginUser, 'get')) {
        $userId = $loginUser->get('id');
    } elseif ($loginUser) {
        $userId = $loginUser->id ?? null;
    } else {
        $userId = null;
    }
}

$isAdmin = $role === 'admin';
?>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        gap: 18px;
        flex-wrap: wrap;
    }

    .page-header h2 {
        font-size: 38px;
        font-weight: 800;
        color: #111827;
        margin: 0;
    }

    .page-header p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 15px;
    }

    .add-btn {
        background: #7c3aed;
        color: white;
        padding: 13px 20px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 800;
        box-shadow: 0 8px 22px rgba(124, 58, 237, 0.25);
    }

    .add-btn:hover {
        background: #6d28d9;
        color: white;
    }

    .claims-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 380px));
        gap: 25px;
        justify-content: start;
        align-items: start;
    }

    .claim-card {
        background: #ffffff;
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        transition: 0.25s;
        position: relative;
        width: 100%;
        max-width: 380px;
    }

    .claim-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.14);
    }

    .claim-number {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #7c3aed;
        color: #ffffff;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 900;
        z-index: 2;
        box-shadow: 0 5px 12px rgba(0,0,0,0.22);
    }

    .claim-image {
        width: 100%;
        height: 195px;
        background: #f8fafc;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        padding: 12px;
    }

    .claim-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .no-image {
        color: #64748b;
        font-size: 14px;
        font-weight: 700;
    }

    .claim-info {
        padding: 20px;
    }

    .claim-info h3 {
        font-size: 24px;
        font-weight: 800;
        color: #111827;
        margin: 0 0 14px;
        text-transform: capitalize;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 9px;
        font-size: 14px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 8px;
    }

    .info-label {
        color: #64748b;
        font-weight: 800;
    }

    .info-value {
        color: #111827;
        text-align: right;
        font-weight: 700;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 13px;
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

    .status-reserved {
        background: #ede9fe;
        color: #5b21b6;
    }

    .status-returned {
        background: #dcfce7;
        color: #166534;
    }

    .status-available {
        background: #dcfce7;
        color: #166534;
    }

    .status-none {
        background: #e5e7eb;
        color: #374151;
    }

    .claim-box {
        margin-top: 15px;
        padding: 15px;
        background: #f8fafc;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
    }

    .claim-box h4 {
        margin: 0 0 12px 0;
        font-size: 17px;
        color: #111827;
        font-weight: 900;
    }

    .role-note {
        margin-top: 10px;
        padding: 10px 12px;
        border-radius: 12px;
        background: #eef2ff;
        color: #3730a3;
        font-size: 13px;
        font-weight: 800;
    }

    .handover-box {
        margin-top: 12px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px;
    }

    .handover-box h5 {
        margin: 0 0 10px;
        font-size: 15px;
        font-weight: 900;
        color: #0f172a;
    }

    .card-actions {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 13px;
    }

    .card-actions a,
    .card-actions form button {
        padding: 9px 12px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 900;
        text-decoration: none;
        border: none;
        cursor: pointer;
        display: inline-block;
    }

    .view-btn {
        background: #2563eb;
        color: white;
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

    .empty-search-message {
        display: none;
        text-align: center;
        background: #ffffff;
        padding: 25px;
        border-radius: 15px;
        color: #64748b;
        font-weight: 700;
        margin-top: 20px;
    }

    .no-records-card {
        background: #ffffff;
        padding: 40px;
        border-radius: 22px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        color: #64748b;
        font-weight: 700;
    }

    @media (max-width: 700px) {
        .claims-grid {
            grid-template-columns: 1fr;
        }

        .claim-card {
            max-width: 100%;
        }

        .page-header h2 {
            font-size: 30px;
        }
    }
</style>

<div class="page-header">
    <div>
        <h2><?= __('Claims & Handover Management') ?></h2>
        <p>
            Finder reviews claims, claimant confirms received item, and admin monitors reported problems.
        </p>
    </div>

    <?= $this->Html->link(
        '+ New Claim',
        ['controller' => 'FoundItems', 'action' => 'index'],
        ['class' => 'add-btn']
    ) ?>
</div>

<?php ?>

<?php if (!empty($foundItems) && count($foundItems) > 0): ?>

    <div class="claims-grid" id="claimsGrid">

        <?php foreach ($foundItems as $foundItem): ?>

            <?php
            $foundStatus = strtolower((string)$foundItem->status);

            if ($foundStatus === 'available') {
                $foundStatusClass = 'status-available';
            } elseif ($foundStatus === 'reserved') {
                $foundStatusClass = 'status-reserved';
            } elseif ($foundStatus === 'returned') {
                $foundStatusClass = 'status-returned';
            } elseif ($foundStatus === 'claimed') {
                $foundStatusClass = 'status-rejected';
            } else {
                $foundStatusClass = 'status-none';
            }

            $imageExists = false;

            if (!empty($foundItem->image)) {
                $imagePath = WWW_ROOT . 'img' . DS . 'found_items' . DS . $foundItem->image;
                $imageExists = file_exists($imagePath);
            }

            $foundReporterName = $foundItem->has('user') ? $foundItem->user->name : 'No User';
            $isFinderForItem = (int)$foundItem->user_id === (int)$userId;

            $searchText = strtolower(
                $foundItem->item_name . ' ' .
                $foundItem->category . ' ' .
                $foundItem->location . ' ' .
                $foundItem->status . ' ' .
                $foundReporterName
            );

            if (!empty($foundItem->claims)) {
                foreach ($foundItem->claims as $claimSearch) {
                    $claimantName = $claimSearch->has('user') ? $claimSearch->user->name : '';
                    $lostItemName = $claimSearch->has('lost_item') ? $claimSearch->lost_item->item_name : '';

                    $searchText .= ' ' . strtolower(
                        $claimantName . ' ' .
                        $lostItemName . ' ' .
                        $claimSearch->claim_status . ' ' .
                        ($claimSearch->handover_status ?? '')
                    );
                }
            }
            ?>

            <div class="claim-card" data-search="<?= h($searchText) ?>">

                

                <div class="claim-image">
                    <?php if (!empty($foundItem->image) && $imageExists): ?>
                        <?= $this->Html->image('found_items/' . $foundItem->image, [
                            'alt' => $foundItem->item_name
                        ]) ?>
                    <?php else: ?>
                        <div class="no-image">No Image</div>
                    <?php endif; ?>
                </div>

                <div class="claim-info">

                    <h3><?= h($foundItem->item_name) ?></h3>

                    <div class="info-row">
                        <span class="info-label">Found Item ID</span>
                        <span class="info-value">#<?= h($foundItem->id) ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Found By</span>
                        <span class="info-value"><?= h($foundReporterName) ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Category</span>
                        <span class="info-value"><?= h($foundItem->category) ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Location</span>
                        <span class="info-value"><?= h($foundItem->location) ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Item Status</span>
                        <span class="info-value">
                            <span class="status-badge <?= $foundStatusClass ?>">
                                <?= h($foundItem->status) ?>
                            </span>
                        </span>
                    </div>

                    <?php if ($isFinderForItem): ?>
                        <div class="role-note">
                            You are the finder for this item. You can review claims submitted for it.
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($foundItem->claims)) : ?>

                        <?php foreach ($foundItem->claims as $claim): ?>

                            <?php
                            $claimStatus = strtolower((string)$claim->claim_status);

                            if ($claimStatus === 'pending') {
                                $claimStatusClass = 'status-pending';
                            } elseif ($claimStatus === 'approved') {
                                $claimStatusClass = 'status-approved';
                            } elseif ($claimStatus === 'rejected') {
                                $claimStatusClass = 'status-rejected';
                            } elseif ($claimStatus === 'completed') {
                                $claimStatusClass = 'status-completed';
                            } elseif ($claimStatus === 'disputed') {
                                $claimStatusClass = 'status-disputed';
                            } else {
                                $claimStatusClass = 'status-none';
                            }

                            $handoverStatus = strtolower((string)($claim->handover_status ?? 'Not Arranged'));

                            if ($handoverStatus === 'completed') {
                                $handoverStatusClass = 'status-completed';
                            } elseif ($handoverStatus === 'arranged') {
                                $handoverStatusClass = 'status-approved';
                            } elseif ($handoverStatus === 'disputed') {
                                $handoverStatusClass = 'status-disputed';
                            } else {
                                $handoverStatusClass = 'status-none';
                            }

                            $claimantName = $claim->has('user') ? $claim->user->name : 'No Claimant';
                            $lostItemName = $claim->has('lost_item') ? $claim->lost_item->item_name : 'No Lost Item Linked';
                            $isClaimantForClaim = (int)$claim->user_id === (int)$userId;
                            $canFinderAct = $isFinderForItem;
                            $canClaimantAct = $isClaimantForClaim;
                            $canReportProblem = $isFinderForItem || $isClaimantForClaim;
                            ?>

                            <div class="claim-box">
                                <h4>Claim Details</h4>

                                <div class="info-row">
                                    <span class="info-label">Claim ID</span>
                                    <span class="info-value">#<?= h($claim->id) ?></span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Claimed By</span>
                                    <span class="info-value"><?= h($claimantName) ?></span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Lost Item</span>
                                    <span class="info-value"><?= h($lostItemName) ?></span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Claim Status</span>
                                    <span class="info-value">
                                        <span class="status-badge <?= $claimStatusClass ?>">
                                            <?= h($claim->claim_status) ?>
                                        </span>
                                    </span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Handover</span>
                                    <span class="info-value">
                                        <span class="status-badge <?= $handoverStatusClass ?>">
                                            <?= h($claim->handover_status ?? 'Not Arranged') ?>
                                        </span>
                                    </span>
                                </div>

                                <?php if (!empty($claim->pickup_code)): ?>
                                    <div class="handover-box">
                                        <h5>Handover Info</h5>

                                        <div class="info-row">
                                            <span class="info-label">Pickup Code</span>
                                            <span class="info-value"><?= h($claim->pickup_code) ?></span>
                                        </div>

                                        <div class="info-row">
                                            <span class="info-label">Location</span>
                                            <span class="info-value"><?= h($claim->meeting_location ?? '-') ?></span>
                                        </div>

                                        <div class="info-row">
                                            <span class="info-label">Date/Time</span>
                                            <span class="info-value"><?= h($claim->meeting_datetime ?? '-') ?></span>
                                        </div>

                                        <div class="info-row">
                                            <span class="info-label">Finder Confirmed</span>
                                            <span class="info-value"><?= (int)$claim->finder_confirmed === 1 ? 'Yes' : 'No' ?></span>
                                        </div>

                                        <div class="info-row">
                                            <span class="info-label">Claimant Confirmed</span>
                                            <span class="info-value"><?= (int)$claim->claimant_confirmed === 1 ? 'Yes' : 'No' ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="card-actions">

                                    <?= $this->Html->link(
                                        'View',
                                        ['controller' => 'Claims', 'action' => 'view', $claim->id],
                                        ['class' => 'view-btn']
                                    ) ?>

                                    <?php if ($isAdmin): ?>
                                        <?= $this->Html->link(
                                            'Edit',
                                            ['controller' => 'Claims', 'action' => 'edit', $claim->id],
                                            ['class' => 'edit-btn']
                                        ) ?>

                                        <?= $this->Form->postLink(
                                            'Delete',
                                            ['controller' => 'Claims', 'action' => 'delete', $claim->id],
                                            [
                                                'confirm' => __('Are you sure you want to delete claim # {0}?', $claim->id),
                                                'class' => 'delete-btn'
                                            ]
                                        ) ?>
                                    <?php endif; ?>

                                    <?php if ($claimStatus === 'pending' && $canFinderAct): ?>

                                        <?= $this->Form->postLink(
                                            'Accept Claim',
                                            ['controller' => 'Claims', 'action' => 'approve', $claim->id],
                                            [
                                                'confirm' => 'Are you sure you want to accept this claim?',
                                                'class' => 'approve-btn'
                                            ]
                                        ) ?>

                                        <?= $this->Form->postLink(
                                            'Reject',
                                            ['controller' => 'Claims', 'action' => 'reject', $claim->id],
                                            [
                                                'confirm' => 'Are you sure you want to reject this claim?',
                                                'class' => 'reject-btn'
                                            ]
                                        ) ?>

                                    <?php endif; ?>

                                    <?php if ($claimStatus === 'approved'): ?>

                                        <?php if ($canFinderAct && (int)$claim->finder_confirmed !== 1): ?>
                                            <?= $this->Form->postLink(
                                                'Confirm Handed Over',
                                                ['controller' => 'Claims', 'action' => 'confirmHandedOver', $claim->id],
                                                [
                                                    'confirm' => 'Confirm that you have handed over this item?',
                                                    'class' => 'confirm-btn'
                                                ]
                                            ) ?>
                                        <?php endif; ?>

                                        <?php if ($canClaimantAct && (int)$claim->claimant_confirmed !== 1): ?>
                                            <?= $this->Form->postLink(
                                                'Confirm Received',
                                                ['controller' => 'Claims', 'action' => 'confirmReceived', $claim->id],
                                                [
                                                    'confirm' => 'Confirm that you have received this item?',
                                                    'class' => 'received-btn'
                                                ]
                                            ) ?>
                                        <?php endif; ?>

                                        <?php if ($canReportProblem): ?>
                                            <?= $this->Form->postLink(
                                                'Report Problem',
                                                ['controller' => 'Claims', 'action' => 'dispute', $claim->id],
                                                [
                                                    'confirm' => 'Report a problem with this handover?',
                                                    'class' => 'dispute-btn'
                                                ]
                                            ) ?>
                                        <?php endif; ?>

                                    <?php endif; ?>

                                </div>
                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="claim-box">
                            <h4>No Claim Yet</h4>

                            <div class="info-row">
                                <span class="info-label">Claim Status</span>
                                <span class="info-value">
                                    <span class="status-badge status-none">
                                        No claim submitted
                                    </span>
                                </span>
                            </div>
                        </div>

                    <?php endif; ?>

                </div>
            </div>

        <?php endforeach; ?>

    </div>

<?php else: ?>

    <div class="no-records-card">
        No claim records available yet.
    </div>

<?php endif; ?>

<div class="empty-search-message" id="emptySearchMessage">
    No claim records match your search.
</div>

<!-- live search removed for Claims page (filtering now only on Items page) -->