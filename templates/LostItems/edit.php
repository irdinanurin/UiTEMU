<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LostItem $lostItem
 * @var \Cake\Collection\CollectionInterface|string[] $users
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

$dateLostValue = '';
if (!empty($lostItem->date_lost)) {
    $dateLostValue = is_object($lostItem->date_lost) && method_exists($lostItem->date_lost, 'format')
        ? $lostItem->date_lost->format('Y-m-d')
        : $lostItem->date_lost;
}
?>

<style>
    .le-page {
        display: grid;
        grid-template-columns: 1fr 260px;
        gap: 16px;
        align-items: start;
    }

    .le-side-card {
        grid-column: 2;
    }

    .le-main-card {
        grid-column: 1;
    }

    .le-side-card,
    .le-main-card,
    .le-preview-card,
    .le-form-card {
        background: #ffffff;
        border-radius: 22px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    .le-side-card {
        padding: 22px;
    }

    .le-side-card h4 {
        margin: 0 0 16px;
        font-size: 20px;
        color: #0f172a;
    }

    .le-side-links {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .le-side-links a,
    .le-side-links button {
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

    .le-link-red { background: #dc2626; color: #fff; }
    .le-link-amber { background: #f59e0b; color: #fff; }
    .le-link-gray { background: #e2e8f0; color: #0f172a; }
    .le-link-blue { background: #2563eb; color: #fff; }

    .le-side-links a:hover,
    .le-side-links button:hover {
        transform: translateY(-1px);
        opacity: 0.96;
        color: inherit;
    }

    .le-main-card {
        padding: 28px;
    }

    .le-title {
        margin: 0 0 6px;
        font-size: 36px;
        font-weight: 800;
        color: #0f172a;
    }

    .le-subtitle {
        margin: 0 0 24px;
        color: #64748b;
        font-size: 15px;
    }

    .le-layout {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 24px;
        align-items: start;
    }

    .le-preview-card {
        overflow: hidden;
    }

    .le-preview-image {
        background: #f8fafc;
        height: 260px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid #e2e8f0;
    }

    .le-preview-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 18px;
    }

    .le-no-image {
        color: #94a3b8;
        font-weight: 700;
        font-size: 15px;
    }

    .le-preview-body {
        padding: 20px;
    }

    .le-preview-body h3 {
        margin: 0 0 14px;
        font-size: 28px;
        color: #0f172a;
        text-transform: capitalize;
    }

    .le-preview-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .le-preview-label {
        color: #64748b;
        font-weight: 700;
    }

    .le-preview-value {
        color: #0f172a;
        font-weight: 600;
        text-align: right;
    }

    .le-badge {
        display: inline-block;
        padding: 7px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
    }

    .le-badge-pending {
        background: #fee2e2;
        color: #991b1b;
    }

    .le-badge-found {
        background: #dcfce7;
        color: #166534;
    }

    .le-badge-other {
        background: #e0e7ff;
        color: #3730a3;
    }

    .le-form-card {
        padding: 24px;
    }

    .le-form-card h3 {
        margin: 0 0 18px;
        font-size: 28px;
        color: #0f172a;
    }

    .le-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .le-grid-full {
        grid-column: 1 / -1;
    }

    .le-form-card .input,
    .le-form-card .select,
    .le-form-card .textarea,
    .le-form-card .file {
        margin-bottom: 0;
    }

    .le-form-card label {
        display: block;
        font-size: 14px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
    }

    .le-form-card input[type="text"],
    .le-form-card input[type="date"],
    .le-form-card input[type="file"],
    .le-form-card select,
    .le-form-card textarea {
        width: 100%;
        border: 1px solid #dbe3ee;
        background: #f8fafc;
        border-radius: 14px;
        padding: 12px 14px;
        font-size: 15px;
        color: #0f172a;
        box-sizing: border-box;
    }

    .le-form-card textarea {
        min-height: 120px;
        resize: vertical;
    }

    .le-readonly-box {
        width: 100%;
        border: 1px solid #dbe3ee;
        background: #f8fafc;
        border-radius: 14px;
        padding: 12px 14px;
        font-size: 15px;
        color: #0f172a;
        box-sizing: border-box;
        min-height: 48px;
        display: flex;
        align-items: center;
    }

    .le-form-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 22px;
    }

    .le-btn-save,
    .le-btn-cancel {
        display: inline-block;
        padding: 12px 18px;
        border-radius: 12px;
        text-decoration: none;
        border: none;
        font-weight: 800;
        cursor: pointer;
        font-size: 14px;
    }

    .le-btn-save {
        background: #dc2626;
        color: #fff;
    }

    .le-btn-cancel {
        background: #e2e8f0;
        color: #0f172a;
    }

    @media (max-width: 1100px) {
        .le-page {
            grid-template-columns: 1fr;
        }

        .le-layout {
            grid-template-columns: 1fr;
        }

        .le-side-card {
            position: static;
        }
    }

    @media (max-width: 640px) {
        .le-grid {
            grid-template-columns: 1fr;
        }

        .le-title {
            font-size: 28px;
        }
    }
</style>

<div class="le-page">

    <aside class="le-side-card">
        <h4><?= __('Actions') ?></h4>

        <div class="le-side-links">
            <?= $this->Html->link(
                __('Back to Lost Items'),
                ['action' => 'index'],
                ['class' => 'le-link-gray']
            ) ?>

            <?= $this->Html->link(
                __('View Item'),
                ['action' => 'view', $lostItem->id],
                ['class' => 'le-link-blue']
            ) ?>

            <?php if ($canManage): ?>
                <?= $this->Form->postLink(
                    __('Delete Item'),
                    ['action' => 'delete', $lostItem->id],
                    [
                        'confirm' => __('Are you sure you want to delete lost item # {0}?', $lostItem->id),
                        'class' => 'le-link-red'
                    ]
                ) ?>
            <?php endif; ?>
        </div>
    </aside>

    <div class="le-main-card">
        <h1 class="le-title">Edit Lost Item</h1>
        <p class="le-subtitle">Update the lost item information and keep the report details accurate.</p>

        <div class="le-layout">

            <div class="le-preview-card">
                <div class="le-preview-image">
                    <?php if (!empty($lostItem->image)): ?>
                        <?= $this->Html->image('lost_items/' . $lostItem->image, [
                            'alt' => $lostItem->item_name
                        ]) ?>
                    <?php else: ?>
                        <div class="le-no-image">No Image Uploaded</div>
                    <?php endif; ?>
                </div>

                <div class="le-preview-body">
                    <h3><?= h($lostItem->item_name ?: 'Unnamed Item') ?></h3>

                    <?php
                    $status = strtolower((string)$lostItem->status);
                    $statusClass = 'le-badge-other';
                    if ($status === 'pending') {
                        $statusClass = 'le-badge-pending';
                    } elseif ($status === 'found' || $status === 'resolved') {
                        $statusClass = 'le-badge-found';
                    }
                    ?>

                    <div class="le-preview-row">
                        <span class="le-preview-label">Reporter</span>
                        <span class="le-preview-value">
                            <?= $lostItem->has('user') ? h($lostItem->user->name) : ('User ID #' . h($lostItem->user_id)) ?>
                        </span>
                    </div>

                    <div class="le-preview-row">
                        <span class="le-preview-label">Category</span>
                        <span class="le-preview-value"><?= h($lostItem->category) ?></span>
                    </div>

                    <div class="le-preview-row">
                        <span class="le-preview-label">Location</span>
                        <span class="le-preview-value"><?= h($lostItem->location) ?></span>
                    </div>

                    <div class="le-preview-row">
                        <span class="le-preview-label">Date Lost</span>
                        <span class="le-preview-value"><?= h($lostItem->date_lost) ?></span>
                    </div>

                    <div class="le-preview-row">
                        <span class="le-preview-label">Status</span>
                        <span class="le-preview-value">
                            <span class="le-badge <?= $statusClass ?>">
                                <?= h($lostItem->status ?: 'Unknown') ?>
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="le-form-card">
                <h3>Item Details</h3>

                <?= $this->Form->create($lostItem, ['type' => 'file']) ?>

                <div class="le-grid">

                    <div class="le-grid-full">
                        <?php if ($isAdmin): ?>
                            <?= $this->Form->control('user_id', [
                                'label' => 'Reporter',
                                'options' => $users,
                                'empty' => false
                            ]) ?>
                        <?php else: ?>
                            <label>Reporter</label>
                            <div class="le-readonly-box">
                                <?= $lostItem->has('user') ? h($lostItem->user->name) : ('User ID #' . h($lostItem->user_id)) ?>
                            </div>
                            <?= $this->Form->hidden('user_id', ['value' => $lostItem->user_id]) ?>
                        <?php endif; ?>
                    </div>

                    <div>
                        <?= $this->Form->control('item_name', [
                            'label' => 'Item Name'
                        ]) ?>
                    </div>

                    <div>
                        <?= $this->Form->control('category', [
                            'label' => 'Category'
                        ]) ?>
                    </div>

                    <div class="le-grid-full">
                        <?= $this->Form->control('description', [
                            'type' => 'textarea',
                            'label' => 'Description'
                        ]) ?>
                    </div>

                    <div class="le-grid-full">
                        <?= $this->Form->control('private_details', [
                            'type' => 'textarea',
                            'label' => 'Private Verification Details',
                            'placeholder' => 'Example: lock screen image, sticker, defect, hidden mark, special feature'
                        ]) ?>
                    </div>

                    <div>
                        <?= $this->Form->control('location', [
                            'label' => 'Location'
                        ]) ?>
                    </div>

                    <div>
                        <?= $this->Form->control('date_lost', [
                            'type' => 'date',
                            'label' => 'Date Lost',
                            'value' => $dateLostValue
                        ]) ?>
                    </div>

                    <div class="le-grid-full">
                        <?= $this->Form->control('image', [
                            'type' => 'file',
                            'label' => 'Replace Image',
                            'required' => false
                        ]) ?>
                    </div>

                    <div>
                        <?php if ($isAdmin): ?>
                            <?= $this->Form->control('status', [
                                'label' => 'Status',
                                'options' => [
                                    'Pending' => 'Pending',
                                    'Found' => 'Found',
                                    'Resolved' => 'Resolved'
                                ]
                            ]) ?>
                        <?php else: ?>
                            <label>Status</label>
                            <div class="le-readonly-box"><?= h($lostItem->status) ?></div>
                            <?= $this->Form->hidden('status', ['value' => $lostItem->status]) ?>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="le-form-actions">
                    <?= $this->Form->button(__('Save Changes'), ['class' => 'le-btn-save']) ?>
                    <?= $this->Html->link(__('Cancel'), ['action' => 'view', $lostItem->id], ['class' => 'le-btn-cancel']) ?>
                </div>

                <?= $this->Form->end() ?>
            </div>

        </div>
    </div>
</div>