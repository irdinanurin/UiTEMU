<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FoundItem $foundItem
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
$isOwner = (int)$foundItem->user_id === (int)$currentUserId;
$canManage = $isAdmin || $isOwner;

$dateFoundValue = '';
if (!empty($foundItem->date_found)) {
    $dateFoundValue = is_object($foundItem->date_found) && method_exists($foundItem->date_found, 'format')
        ? $foundItem->date_found->format('Y-m-d')
        : $foundItem->date_found;
}
?>

<style>
    .fi-page {
        display: grid;
        grid-template-columns: 1fr 260px;
        gap: 16px;
        align-items: start;
    }

    .fi-side-card {
        grid-column: 2;
    }

    .fi-main-card {
        grid-column: 1;
    }

    .fi-side-card,
    .fi-main-card,
    .fi-preview-card,
    .fi-form-card {
        background: #ffffff;
        border-radius: 22px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    .fi-side-card {
        padding: 22px;
    }

    .fi-side-card h4 {
        margin: 0 0 16px;
        font-size: 20px;
        color: #0f172a;
    }

    .fi-side-links {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .fi-side-links a,
    .fi-side-links button {
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

    .fi-link-blue { background: #2563eb; color: #fff; }
    .fi-link-gray { background: #e2e8f0; color: #0f172a; }
    .fi-link-red  { background: #ef4444; color: #fff; }

    .fi-side-links a:hover,
    .fi-side-links button:hover {
        transform: translateY(-1px);
        opacity: 0.95;
        color: inherit;
    }

    .fi-main-card {
        padding: 28px;
    }

    .fi-title {
        margin: 0 0 6px;
        font-size: 36px;
        font-weight: 800;
        color: #0f172a;
    }

    .fi-subtitle {
        margin: 0 0 24px;
        color: #64748b;
        font-size: 15px;
    }

    .fi-layout {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 24px;
        align-items: start;
    }

    .fi-preview-card {
        overflow: hidden;
    }

    .fi-preview-image {
        background: #f8fafc;
        height: 260px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid #e2e8f0;
    }

    .fi-preview-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 18px;
    }

    .fi-no-image {
        color: #94a3b8;
        font-weight: 700;
        font-size: 15px;
    }

    .fi-preview-body {
        padding: 20px;
    }

    .fi-preview-body h3 {
        margin: 0 0 14px;
        font-size: 28px;
        color: #0f172a;
        text-transform: capitalize;
    }

    .fi-preview-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .fi-preview-label {
        color: #64748b;
        font-weight: 700;
    }

    .fi-preview-value {
        color: #0f172a;
        font-weight: 600;
        text-align: right;
    }

    .fi-badge {
        display: inline-block;
        padding: 7px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
    }

    .fi-badge-available {
        background: #dcfce7;
        color: #166534;
    }

    .fi-badge-claimed {
        background: #fee2e2;
        color: #991b1b;
    }

    .fi-badge-other {
        background: #e0e7ff;
        color: #3730a3;
    }

    .fi-form-card {
        padding: 24px;
    }

    .fi-form-card h3 {
        margin: 0 0 18px;
        font-size: 28px;
        color: #0f172a;
    }

    .fi-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .fi-grid-full {
        grid-column: 1 / -1;
    }

    .fi-form-card .input,
    .fi-form-card .select,
    .fi-form-card .textarea,
    .fi-form-card .file {
        margin-bottom: 0;
    }

    .fi-form-card label {
        display: block;
        font-size: 14px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
    }

    .fi-form-card input[type="text"],
    .fi-form-card input[type="date"],
    .fi-form-card input[type="file"],
    .fi-form-card select,
    .fi-form-card textarea {
        width: 100%;
        border: 1px solid #dbe3ee;
        background: #f8fafc;
        border-radius: 14px;
        padding: 12px 14px;
        font-size: 15px;
        color: #0f172a;
        box-sizing: border-box;
    }

    .fi-form-card textarea {
        min-height: 120px;
        resize: vertical;
    }

    .fi-readonly-box {
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

    .fi-form-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 22px;
    }

    .fi-btn-save,
    .fi-btn-cancel {
        display: inline-block;
        padding: 12px 18px;
        border-radius: 12px;
        text-decoration: none;
        border: none;
        font-weight: 800;
        cursor: pointer;
        font-size: 14px;
    }

    .fi-btn-save {
        background: #2563eb;
        color: #fff;
    }

    .fi-btn-cancel {
        background: #e2e8f0;
        color: #0f172a;
    }

    @media (max-width: 1100px) {
        .fi-page {
            grid-template-columns: 1fr;
        }

        .fi-layout {
            grid-template-columns: 1fr;
        }

        .fi-side-card {
            position: static;
        }
    }

    @media (max-width: 640px) {
        .fi-grid {
            grid-template-columns: 1fr;
        }

        .fi-title {
            font-size: 28px;
        }
    }
</style>

<div class="fi-page">

    <aside class="fi-side-card">
        <h4><?= __('Actions') ?></h4>

        <div class="fi-side-links">
            <?= $this->Html->link(
                __('Back to Found Items'),
                ['action' => 'index'],
                ['class' => 'fi-link-gray']
            ) ?>

            <?= $this->Html->link(
                __('View Item'),
                ['action' => 'view', $foundItem->id],
                ['class' => 'fi-link-blue']
            ) ?>

            <?php if ($canManage): ?>
                <?= $this->Form->postLink(
                    __('Delete Item'),
                    ['action' => 'delete', $foundItem->id],
                    [
                        'confirm' => __('Are you sure you want to delete item # {0}?', $foundItem->id),
                        'class' => 'fi-link-red'
                    ]
                ) ?>
            <?php endif; ?>
        </div>
    </aside>

    <div class="fi-main-card">
        <h1 class="fi-title">Edit Found Item</h1>
        <p class="fi-subtitle">Update the found item information and keep the report details accurate.</p>

        <div class="fi-layout">

            <div class="fi-preview-card">
                <div class="fi-preview-image">
                    <?php if (!empty($foundItem->image)): ?>
                        <?= $this->Html->image('found_items/' . $foundItem->image, [
                            'alt' => $foundItem->item_name
                        ]) ?>
                    <?php else: ?>
                        <div class="fi-no-image">No Image Uploaded</div>
                    <?php endif; ?>
                </div>

                <div class="fi-preview-body">
                    <h3><?= h($foundItem->item_name ?: 'Unnamed Item') ?></h3>

                    <?php
                    $status = strtolower((string)$foundItem->status);
                    $statusClass = 'fi-badge-other';
                    if ($status === 'available') {
                        $statusClass = 'fi-badge-available';
                    } elseif ($status === 'claimed') {
                        $statusClass = 'fi-badge-claimed';
                    }
                    ?>

                    <div class="fi-preview-row">
                        <span class="fi-preview-label">Reporter</span>
                        <span class="fi-preview-value">
                            <?= $foundItem->has('user') ? h($foundItem->user->name) : ('User ID #' . h($foundItem->user_id)) ?>
                        </span>
                    </div>

                    <div class="fi-preview-row">
                        <span class="fi-preview-label">Category</span>
                        <span class="fi-preview-value"><?= h($foundItem->category) ?></span>
                    </div>

                    <div class="fi-preview-row">
                        <span class="fi-preview-label">Location</span>
                        <span class="fi-preview-value"><?= h($foundItem->location) ?></span>
                    </div>

                    <div class="fi-preview-row">
                        <span class="fi-preview-label">Date Found</span>
                        <span class="fi-preview-value"><?= h($foundItem->date_found) ?></span>
                    </div>

                    <div class="fi-preview-row">
                        <span class="fi-preview-label">Status</span>
                        <span class="fi-preview-value">
                            <span class="fi-badge <?= $statusClass ?>">
                                <?= h($foundItem->status ?: 'Unknown') ?>
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="fi-form-card">
                <h3>Item Details</h3>

                <?= $this->Form->create($foundItem, ['type' => 'file']) ?>

                <div class="fi-grid">

                    <div class="fi-grid-full">
                        <?php if ($isAdmin): ?>
                            <?= $this->Form->control('user_id', [
                                'label' => 'Reporter',
                                'options' => $users,
                                'empty' => false
                            ]) ?>
                        <?php else: ?>
                            <label>Reporter</label>
                            <div class="fi-readonly-box">
                                <?= $foundItem->has('user') ? h($foundItem->user->name) : ('User ID #' . h($foundItem->user_id)) ?>
                            </div>
                            <?= $this->Form->hidden('user_id', ['value' => $foundItem->user_id]) ?>
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

                    <div class="fi-grid-full">
                        <?= $this->Form->control('description', [
                            'type' => 'textarea',
                            'label' => 'Description'
                        ]) ?>
                    </div>

                    <div class="fi-grid-full">
                        <?= $this->Form->control('private_details', [
                            'type' => 'textarea',
                            'label' => 'Private Verification Details',
                            'placeholder' => 'Example: brand, defect, label, mark, item contents, hidden details'
                        ]) ?>
                    </div>

                    <div>
                        <?= $this->Form->control('location', [
                            'label' => 'Location'
                        ]) ?>
                    </div>

                    <div>
                        <?= $this->Form->control('date_found', [
                            'type' => 'date',
                            'label' => 'Date Found',
                            'value' => $dateFoundValue
                        ]) ?>
                    </div>

                    <div class="fi-grid-full">
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
                                    'Available' => 'Available',
                                    'Claimed' => 'Claimed'
                                ]
                            ]) ?>
                        <?php else: ?>
                            <label>Status</label>
                            <div class="fi-readonly-box"><?= h($foundItem->status) ?></div>
                            <?= $this->Form->hidden('status', ['value' => $foundItem->status]) ?>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="fi-form-actions">
                    <?= $this->Form->button(__('Save Changes'), ['class' => 'fi-btn-save']) ?>
                    <?= $this->Html->link(__('Cancel'), ['action' => 'view', $foundItem->id], ['class' => 'fi-btn-cancel']) ?>
                </div>

                <?= $this->Form->end() ?>
            </div>

        </div>
    </div>
</div>