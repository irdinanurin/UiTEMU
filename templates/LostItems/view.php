<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LostItem $lostItem
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
$isOwner = (int)$lostItem->user_id === (int)$currentUserId;
$canManage = $isAdmin || $isOwner;

$status = strtolower((string)$lostItem->status);

$statusClass = 'lv-badge-other';

if ($status === 'pending') {
    $statusClass = 'lv-badge-pending';
} elseif ($status === 'found' || $status === 'resolved') {
    $statusClass = 'lv-badge-found';
}
?>

<style>
    .lv-page {
        display: grid;
        grid-template-columns: 1fr 260px;
        gap: 12px;
        align-items: start;
    }

    .lv-side-card {
        grid-column: 2;
        grid-row: 1;
        margin-top: 0;
    }

    .lv-main-card {
        grid-column: 1;
        grid-row: 1;
    }

    .lv-side-card,
    .lv-main-card,
    .lv-image-card,
    .lv-info-card,
    .lv-section-card {
        background: #ffffff;
        border-radius: 22px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    .lv-side-card {
        padding: 22px;
    }

    .lv-side-card h4 {
        margin: 0 0 16px;
        font-size: 20px;
        color: #0f172a;
    }

    .lv-side-links {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .lv-side-links a,
    .lv-side-links button {
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

    .lv-link-red { background: #dc2626; color: #fff; }
    .lv-link-amber { background: #f59e0b; color: #fff; }
    .lv-link-gray { background: #e2e8f0; color: #0f172a; }
    .lv-link-blue { background: #2563eb; color: #fff; }

    .lv-side-links a:hover,
    .lv-side-links button:hover {
        transform: translateY(-1px);
        opacity: 0.96;
        color: inherit;
    }

    .lv-main-card {
        padding: 18px;
    }

    .lv-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        gap: 16px;
        margin-bottom: 16px;
    }

    .lv-title {
        margin: 0 0 6px;
        font-size: 38px;
        font-weight: 800;
        color: #0f172a;
        text-transform: capitalize;
    }

    .lv-subtitle {
        margin: 0;
        color: #64748b;
        font-size: 15px;
    }

    .lv-badge {
        display: inline-block;
        padding: 8px 15px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
    }

    .lv-badge-pending {
        background: #fee2e2;
        color: #991b1b;
    }

    .lv-badge-found {
        background: #dcfce7;
        color: #166534;
    }

    .lv-badge-other {
        background: #e0e7ff;
        color: #3730a3;
    }

    .lv-top-grid {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    .lv-image-card {
        overflow: hidden;
    }

    .lv-image-wrap {
        background: #f8fafc;
        height: 360px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lv-image-wrap img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 18px;
    }

    .lv-no-image {
        color: #94a3b8;
        font-weight: 700;
        font-size: 15px;
    }

    .lv-image-footer {
        padding: 18px 20px;
        border-top: 1px solid #e2e8f0;
        color: #64748b;
        font-size: 14px;
        font-weight: 600;
    }

    .lv-info-card {
        padding: 24px;
    }

    .lv-info-card h3 {
        margin: 0 0 18px;
        font-size: 28px;
        color: #0f172a;
    }

    .lv-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px 22px;
    }

    .lv-info-item {
        padding: 14px 16px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .lv-info-label {
        display: block;
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .lv-info-value {
        color: #0f172a;
        font-size: 16px;
        font-weight: 700;
        word-break: break-word;
    }

    .lv-actions-inline {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 22px;
    }

    .lv-actions-inline a,
    .lv-actions-inline button {
        padding: 11px 16px;
        border-radius: 12px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-weight: 800;
        font-size: 14px;
    }

    .lv-section-card {
        padding: 24px;
        margin-bottom: 24px;
    }

    .lv-section-card h4 {
        margin: 0 0 14px;
        font-size: 24px;
        color: #0f172a;
    }

    .lv-section-card p {
        margin: 0;
        color: #334155;
        font-size: 15px;
        line-height: 1.75;
    }

    .lv-empty {
        color: #64748b;
        font-weight: 600;
    }

    @media (max-width: 1100px) {
        .lv-page {
            grid-template-columns: 1fr;
        }

        .lv-top-grid {
            grid-template-columns: 1fr;
        }

        .lv-side-card {
            position: static;
        }
    }

    @media (max-width: 700px) {
        .lv-info-grid {
            grid-template-columns: 1fr;
        }

        .lv-title {
            font-size: 30px;
        }

        .lv-header {
            flex-direction: column;
        }
    }
</style>

<div class="lv-page">

    <aside class="lv-side-card">
        <h4><?= __('Actions') ?></h4>

        <div class="lv-side-links">
            <?= $this->Html->link(
                __('Back to Lost Items'),
                ['action' => 'index'],
                ['class' => 'lv-link-gray']
            ) ?>

            <?= $this->Html->link(
                __('New Lost Item'),
                ['action' => 'add'],
                ['class' => 'lv-link-red']
            ) ?>

            <?php if ($canManage): ?>
                <?= $this->Html->link(
                    __('Edit Item'),
                    ['action' => 'edit', $lostItem->id],
                    ['class' => 'lv-link-amber']
                ) ?>

                <?= $this->Form->postLink(
                    __('Delete Item'),
                    ['action' => 'delete', $lostItem->id],
                    [
                        'confirm' => __('Are you sure you want to delete lost item # {0}?', $lostItem->id),
                        'class' => 'lv-link-red'
                    ]
                ) ?>
            <?php endif; ?>
        </div>
    </aside>

    <div class="lv-main-card">

        <div class="lv-header">
            <div>
                <h1 class="lv-title"><?= h($lostItem->item_name) ?></h1>
                <p class="lv-subtitle">View the full details of this lost item report.</p>
            </div>

            <span class="lv-badge <?= $statusClass ?>">
                <?= h($lostItem->status ?: 'Unknown') ?>
            </span>
        </div>

        <div class="lv-top-grid">

            <div class="lv-image-card">
                <div class="lv-image-wrap">
                    <?php if (!empty($lostItem->image)): ?>
                        <?= $this->Html->image('lost_items/' . $lostItem->image, [
                            'alt' => $lostItem->item_name
                        ]) ?>
                    <?php else: ?>
                        <div class="lv-no-image">No Image Uploaded</div>
                    <?php endif; ?>
                </div>

                <div class="lv-image-footer">
                    Lost Item ID #<?= h($lostItem->id) ?>
                </div>
            </div>

            <div class="lv-info-card">
                <h3>Item Information</h3>

                <div class="lv-info-grid">
                    <div class="lv-info-item">
                        <span class="lv-info-label">Reporter</span>
                        <span class="lv-info-value">
                            <?= $lostItem->has('user') ? h($lostItem->user->name) : ('User ID #' . h($lostItem->user_id)) ?>
                        </span>
                    </div>

                    <div class="lv-info-item">
                        <span class="lv-info-label">Category</span>
                        <span class="lv-info-value"><?= h($lostItem->category) ?></span>
                    </div>

                    <div class="lv-info-item">
                        <span class="lv-info-label">Location</span>
                        <span class="lv-info-value"><?= h($lostItem->location) ?></span>
                    </div>

                    <div class="lv-info-item">
                        <span class="lv-info-label">Date Lost</span>
                        <span class="lv-info-value"><?= h($lostItem->date_lost) ?></span>
                    </div>

                    <div class="lv-info-item">
                        <span class="lv-info-label">Created At</span>
                        <span class="lv-info-value"><?= h($lostItem->created_at) ?></span>
                    </div>

                    <div class="lv-info-item">
                        <span class="lv-info-label">Updated At</span>
                        <span class="lv-info-value"><?= h($lostItem->updated_at) ?></span>
                    </div>
                </div>

                <?php if ($canManage): ?>
                    <div class="lv-actions-inline">
                        <?= $this->Html->link(
                            'Edit',
                            ['action' => 'edit', $lostItem->id],
                            ['class' => 'lv-link-amber']
                        ) ?>

                        <?= $this->Form->postLink(
                            'Delete',
                            ['action' => 'delete', $lostItem->id],
                            [
                                'confirm' => __('Are you sure you want to delete lost item # {0}?', $lostItem->id),
                                'class' => 'lv-link-red'
                            ]
                        ) ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <div class="lv-section-card">
            <h4>Description</h4>
            <p>
                <?= !empty($lostItem->description)
                    ? nl2br(h($lostItem->description))
                    : '<span class="lv-empty">No description provided.</span>'
                ?>
            </p>
        </div>

        <?php if ($canManage): ?>
            <div class="lv-section-card">
                <h4>Private Verification Details</h4>
                <p>
                    <?= !empty($lostItem->private_details)
                        ? nl2br(h($lostItem->private_details))
                        : '<span class="lv-empty">No private verification details provided.</span>'
                    ?>
                </p>
            </div>
        <?php endif; ?>

    </div>
</div>