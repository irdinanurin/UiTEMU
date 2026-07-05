<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FoundItem $foundItem
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
$isOwner = (int)$foundItem->user_id === (int)$currentUserId;
$canManage = $isAdmin || $isOwner;

$status = strtolower((string)$foundItem->status);
$statusClass = 'fv-badge-other';
if ($status === 'available') {
    $statusClass = 'fv-badge-available';
} elseif ($status === 'claimed') {
    $statusClass = 'fv-badge-claimed';
}
?>

<style>
    .fv-page {
        display: grid;
        grid-template-columns: 1fr 260px;
        gap: 12px;
        align-items: start;
    }

    .fv-side-card {
        grid-column: 2;
        grid-row: 1;
        margin-top: 0;
    }

    .fv-main-card {
        grid-column: 1;
        grid-row: 1;
    }

    .fv-side-card,
    .fv-main-card,
    .fv-image-card,
    .fv-info-card,
    .fv-section-card,
    .fv-claim-card {
        background: #ffffff;
        border-radius: 22px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    .fv-side-card {
        padding: 22px;
    }

    .fv-side-card h4 {
        margin: 0 0 16px;
        font-size: 20px;
        color: #0f172a;
    }

    .fv-side-links {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .fv-side-links a,
    .fv-side-links button {
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

    .fv-link-blue { background: #2563eb; color: #fff; }
    .fv-link-green { background: #10b981; color: #fff; }
    .fv-link-gray { background: #e2e8f0; color: #0f172a; }
    .fv-link-red  { background: #ef4444; color: #fff; }
    .fv-link-amber { background: #f59e0b; color: #fff; }

    .fv-side-links a:hover,
    .fv-side-links button:hover {
        transform: translateY(-1px);
        opacity: 0.96;
        color: inherit;
    }

    .fv-main-card {
        padding: 18px;
    }

    .fv-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        gap: 16px;
        margin-bottom: 16px;
    }

    .fv-title {
        margin: 0 0 6px;
        font-size: 38px;
        font-weight: 800;
        color: #0f172a;
        text-transform: capitalize;
    }

    .fv-subtitle {
        margin: 0;
        color: #64748b;
        font-size: 15px;
    }

    .fv-badge {
        display: inline-block;
        padding: 8px 15px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
    }

    .fv-badge-available {
        background: #dcfce7;
        color: #166534;
    }

    .fv-badge-claimed {
        background: #fee2e2;
        color: #991b1b;
    }

    .fv-badge-other {
        background: #e0e7ff;
        color: #3730a3;
    }

    .fv-top-grid {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    .fv-image-card {
        overflow: hidden;
    }

    .fv-image-wrap {
        background: #f8fafc;
        height: 360px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .fv-image-wrap img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 18px;
    }

    .fv-no-image {
        color: #94a3b8;
        font-weight: 700;
        font-size: 15px;
    }

    .fv-image-footer {
        padding: 18px 20px;
        border-top: 1px solid #e2e8f0;
        color: #64748b;
        font-size: 14px;
        font-weight: 600;
    }

    .fv-info-card {
        padding: 24px;
    }

    .fv-info-card h3 {
        margin: 0 0 18px;
        font-size: 28px;
        color: #0f172a;
    }

    .fv-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px 22px;
    }

    .fv-info-item {
        padding: 14px 16px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .fv-info-label {
        display: block;
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .fv-info-value {
        color: #0f172a;
        font-size: 16px;
        font-weight: 700;
        word-break: break-word;
    }

    .fv-actions-inline {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 22px;
    }

    .fv-actions-inline a,
    .fv-actions-inline button {
        padding: 11px 16px;
        border-radius: 12px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-weight: 800;
        font-size: 14px;
    }

    .fv-section-card {
        padding: 24px;
        margin-bottom: 24px;
    }

    .fv-section-card h4 {
        margin: 0 0 14px;
        font-size: 24px;
        color: #0f172a;
    }

    .fv-section-card p {
        margin: 0;
        color: #334155;
        font-size: 15px;
        line-height: 1.75;
    }

    .fv-claims-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 18px;
        margin-top: 16px;
    }

    .fv-claim-card {
        padding: 20px;
        border: 1px solid #e2e8f0;
    }

    .fv-claim-card h5 {
        margin: 0 0 14px;
        font-size: 20px;
        color: #0f172a;
    }

    .fv-claim-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .fv-claim-label {
        color: #64748b;
        font-weight: 700;
    }

    .fv-claim-value {
        color: #0f172a;
        font-weight: 700;
        text-align: right;
    }

    .fv-claim-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
    }

    .fv-claim-actions a,
    .fv-claim-actions button {
        padding: 10px 14px;
        border-radius: 10px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-weight: 800;
        font-size: 13px;
    }

    .fv-empty {
        color: #64748b;
        font-weight: 600;
    }

    @media (max-width: 1100px) {
        .fv-page {
            grid-template-columns: 1fr;
        }

        .fv-top-grid {
            grid-template-columns: 1fr;
        }

        .fv-side-card {
            position: static;
        }
    }

    @media (max-width: 700px) {
        .fv-info-grid {
            grid-template-columns: 1fr;
        }

        .fv-title {
            font-size: 30px;
        }

        .fv-header {
            flex-direction: column;
        }
    }
</style>

<div class="fv-page">

    <aside class="fv-side-card">
        <h4><?= __('Actions') ?></h4>

        <div class="fv-side-links">
            <?= $this->Html->link(
                __('Back to Found Items'),
                ['action' => 'index'],
                ['class' => 'fv-link-gray']
            ) ?>

            <?= $this->Html->link(
                __('New Found Item'),
                ['action' => 'add'],
                ['class' => 'fv-link-blue']
            ) ?>

            <?php if (!$canManage && strtolower((string)$foundItem->status) === 'available'): ?>
                <?= $this->Html->link(
                    __('Claim This Item'),
                    ['controller' => 'Claims', 'action' => 'add', $foundItem->id],
                    ['class' => 'fv-link-green']
                ) ?>
            <?php endif; ?>

            <?php if ($canManage): ?>
                <?= $this->Html->link(
                    __('Edit Item'),
                    ['action' => 'edit', $foundItem->id],
                    ['class' => 'fv-link-amber']
                ) ?>

                <?= $this->Form->postLink(
                    __('Delete Item'),
                    ['action' => 'delete', $foundItem->id],
                    [
                        'confirm' => __('Are you sure you want to delete item # {0}?', $foundItem->id),
                        'class' => 'fv-link-red'
                    ]
                ) ?>
            <?php endif; ?>
        </div>
    </aside>

    <div class="fv-main-card">

        <div class="fv-header">
            <div>
                <h1 class="fv-title"><?= h($foundItem->item_name) ?></h1>
                <p class="fv-subtitle">View the full details of this found item report.</p>
            </div>

            <span class="fv-badge <?= $statusClass ?>">
                <?= h($foundItem->status ?: 'Unknown') ?>
            </span>
        </div>

        <div class="fv-top-grid">

            <div class="fv-image-card">
                <div class="fv-image-wrap">
                    <?php if (!empty($foundItem->image)): ?>
                        <?= $this->Html->image('found_items/' . $foundItem->image, [
                            'alt' => $foundItem->item_name
                        ]) ?>
                    <?php else: ?>
                        <div class="fv-no-image">No Image Uploaded</div>
                    <?php endif; ?>
                </div>

                <div class="fv-image-footer">
                    Found Item ID #<?= h($foundItem->id) ?>
                </div>
            </div>

            <div class="fv-info-card">
                <h3>Item Information</h3>

                <div class="fv-info-grid">
                    <div class="fv-info-item">
                        <span class="fv-info-label">Reporter</span>
                        <span class="fv-info-value">
                            <?= $foundItem->has('user') ? h($foundItem->user->name) : ('User ID #' . h($foundItem->user_id)) ?>
                        </span>
                    </div>

                    <div class="fv-info-item">
                        <span class="fv-info-label">Category</span>
                        <span class="fv-info-value"><?= h($foundItem->category) ?></span>
                    </div>

                    <div class="fv-info-item">
                        <span class="fv-info-label">Location</span>
                        <span class="fv-info-value"><?= h($foundItem->location) ?></span>
                    </div>

                    <div class="fv-info-item">
                        <span class="fv-info-label">Date Found</span>
                        <span class="fv-info-value"><?= h($foundItem->date_found) ?></span>
                    </div>

                    <div class="fv-info-item">
                        <span class="fv-info-label">Created At</span>
                        <span class="fv-info-value"><?= h($foundItem->created_at) ?></span>
                    </div>

                    <div class="fv-info-item">
                        <span class="fv-info-label">Updated At</span>
                        <span class="fv-info-value"><?= h($foundItem->updated_at) ?></span>
                    </div>
                </div>

                <div class="fv-actions-inline">
                    <?php if (!$canManage && strtolower((string)$foundItem->status) === 'available'): ?>
                        <?= $this->Html->link(
                            'Claim Item',
                            ['controller' => 'Claims', 'action' => 'add', $foundItem->id],
                            ['class' => 'fv-link-green']
                        ) ?>
                    <?php endif; ?>

                    <?php if ($canManage): ?>
                        <?= $this->Html->link(
                            'Edit',
                            ['action' => 'edit', $foundItem->id],
                            ['class' => 'fv-link-amber']
                        ) ?>

                        <?= $this->Form->postLink(
                            'Delete',
                            ['action' => 'delete', $foundItem->id],
                            [
                                'confirm' => __('Are you sure you want to delete item # {0}?', $foundItem->id),
                                'class' => 'fv-link-red'
                            ]
                        ) ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <div class="fv-section-card">
            <h4>Description</h4>
            <p>
                <?= !empty($foundItem->description) ? nl2br(h($foundItem->description)) : '<span class="fv-empty">No description provided.</span>' ?>
            </p>
        </div>

        <?php if ($canManage): ?>
            <div class="fv-section-card">
                <h4>Private Verification Details</h4>
                <p>
                    <?= !empty($foundItem->private_details) ? nl2br(h($foundItem->private_details)) : '<span class="fv-empty">No private details provided.</span>' ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if ($isAdmin): ?>
            <div class="fv-section-card">
                <h4>Related Claims</h4>

                <?php if (!empty($foundItem->claims)): ?>
                    <div class="fv-claims-grid">
                        <?php foreach ($foundItem->claims as $claim): ?>

                            <?php
                            $claimStatus = strtolower((string)$claim->claim_status);
                            $claimBadgeClass = 'fv-badge-other';
                            if ($claimStatus === 'approved') {
                                $claimBadgeClass = 'fv-badge-available';
                            } elseif ($claimStatus === 'pending') {
                                $claimBadgeClass = 'fv-badge-other';
                            } elseif ($claimStatus === 'rejected') {
                                $claimBadgeClass = 'fv-badge-claimed';
                            }
                            ?>

                            <div class="fv-claim-card">
                                <h5>Claim #<?= h($claim->id) ?></h5>

                                <div class="fv-claim-row">
                                    <span class="fv-claim-label">Claimant</span>
                                    <span class="fv-claim-value">
                                        <?php if ($claim->has('user')): ?>
                                            <?= h($claim->user->name) ?>
                                        <?php else: ?>
                                            <?= !empty($claim->user_id) ? 'User ID #' . h($claim->user_id) : 'No User' ?>
                                        <?php endif; ?>
                                    </span>
                                </div>

                                <div class="fv-claim-row">
                                    <span class="fv-claim-label">Lost Item</span>
                                    <span class="fv-claim-value">
                                        <?php if ($claim->has('lost_item')): ?>
                                            <?= h($claim->lost_item->item_name) ?>
                                        <?php else: ?>
                                            <?= !empty($claim->lost_item_id) ? 'Lost Item #' . h($claim->lost_item_id) : 'No Lost Item Linked' ?>
                                        <?php endif; ?>
                                    </span>
                                </div>

                                <div class="fv-claim-row">
                                    <span class="fv-claim-label">Status</span>
                                    <span class="fv-claim-value">
                                        <span class="fv-badge <?= $claimBadgeClass ?>">
                                            <?= h($claim->claim_status) ?>
                                        </span>
                                    </span>
                                </div>

                                <div class="fv-claim-row">
                                    <span class="fv-claim-label">Created</span>
                                    <span class="fv-claim-value"><?= h($claim->created_at) ?></span>
                                </div>

                                <div class="fv-claim-actions">
                                    <?= $this->Html->link(
                                        'View',
                                        ['controller' => 'Claims', 'action' => 'view', $claim->id],
                                        ['class' => 'fv-link-blue']
                                    ) ?>

                                    <?php if ($claim->claim_status === 'Pending'): ?>
                                        <?= $this->Form->postLink(
                                            'Approve',
                                            ['controller' => 'Claims', 'action' => 'approve', $claim->id],
                                            [
                                                'confirm' => 'Are you sure you want to approve this claim?',
                                                'class' => 'fv-link-green'
                                            ]
                                        ) ?>

                                        <?= $this->Form->postLink(
                                            'Reject',
                                            ['controller' => 'Claims', 'action' => 'reject', $claim->id],
                                            [
                                                'confirm' => 'Are you sure you want to reject this claim?',
                                                'class' => 'fv-link-red'
                                            ]
                                        ) ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="fv-empty">No claims have been submitted for this item yet.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>