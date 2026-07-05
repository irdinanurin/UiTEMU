<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\LostItem> $lostItems
 */
?>

<?php
$loginUser = $this->request->getAttribute('identity');

$currentUserId = null;
$role = null;

if ($loginUser) {
    if (method_exists($loginUser, 'getIdentifier')) {
        $currentUserId = $loginUser->getIdentifier();
    } elseif (method_exists($loginUser, 'get')) {
        $currentUserId = $loginUser->get('id');
    } else {
        $currentUserId = $loginUser->id ?? null;
    }

    if (method_exists($loginUser, 'get')) {
        $role = $loginUser->get('role');
    } else {
        $role = $loginUser->role ?? null;
    }
}

if (!$currentUserId) {
    $sessionUser = $this->request->getSession()->read('Auth.User');

    if (!empty($sessionUser['id'])) {
        $currentUserId = $sessionUser['id'];
    }

    if (!empty($sessionUser['role'])) {
        $role = $sessionUser['role'];
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
    }

    .page-header h2 {
        font-size: 32px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .add-btn {
        background: #dc2626;
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        box-shadow: 0 5px 15px rgba(220, 38, 38, 0.25);
    }

    .add-btn:hover {
        background: #b91c1c;
        color: white;
    }

    .lost-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 300px));
        gap: 25px;
        justify-content: start;
        align-items: start;
    }

    .lost-card {
        background: #ffffff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
        transition: 0.3s;
        position: relative;
        width: 100%;
        max-width: 300px;
    }

    .lost-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
    }

    .item-number {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #dc2626;
        color: #ffffff;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        z-index: 2;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .lost-image {
        width: 100%;
        height: 190px;
        background: #f8fafc;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        padding: 10px;
    }

    .lost-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .no-image {
        color: #64748b;
        font-size: 14px;
        font-weight: 600;
    }

    .lost-info {
        padding: 18px;
    }

    .lost-info h3 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 12px;
        text-transform: capitalize;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .info-label {
        color: #64748b;
        font-weight: 600;
    }

    .info-value {
        color: #111827;
        text-align: right;
        font-weight: 500;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
    }

    .status-pending {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-found {
        background: #dcfce7;
        color: #166534;
    }

    .status-other {
        background: #e0e7ff;
        color: #3730a3;
    }

    .owner-badge {
        display: inline-block;
        background: #fee2e2;
        color: #991b1b;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .card-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 16px;
    }
    .card-actions { justify-content: flex-end; }

    .card-actions a,
    .card-actions form button {
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 13px;
        text-decoration: none;
        border: none;
        cursor: pointer;
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

    .empty-search-message {
        display: none;
        text-align: center;
        background: #ffffff;
        padding: 25px;
        border-radius: 15px;
        color: #64748b;
        font-weight: 600;
        margin-top: 20px;
    }

    .pagination-wrapper {
        margin-top: 30px;
        text-align: center;
    }

    .pagination-wrapper ul {
        justify-content: center;
    }
</style>

<div class="page-header">
    <h2><?= __('Lost Items') ?></h2>

    <?= $this->Html->link(
        '+ New Lost Item',
        ['action' => 'add'],
        ['class' => 'add-btn']
    ) ?>
</div>

<?php
?>

<div class="lost-grid" id="lostItemsGrid">

    <?php foreach ($lostItems as $lostItem): ?>

        <?php
        $status = strtolower((string)$lostItem->status);

        if ($status === 'pending') {
            $statusClass = 'status-pending';
        } elseif ($status === 'found' || $status === 'resolved') {
            $statusClass = 'status-found';
        } else {
            $statusClass = 'status-other';
        }

        $imageExists = false;

        if (!empty($lostItem->image)) {
            $imagePath = WWW_ROOT . 'img' . DS . 'lost_items' . DS . $lostItem->image;
            $imageExists = file_exists($imagePath);
        }

        $reporterName = $lostItem->has('user') ? $lostItem->user->name : 'No User';

        $isOwner = (int)$lostItem->user_id === (int)$currentUserId;
        $canModify = $isAdmin || $isOwner;

        $searchText = strtolower(
            $lostItem->item_name . ' ' .
            $lostItem->category . ' ' .
            $lostItem->location . ' ' .
            $lostItem->status . ' ' .
            $reporterName
        );
        ?>

        <div class="lost-card" data-search="<?= h($searchText) ?>">

            <div class="lost-image">
                <?php if (!empty($lostItem->image) && $imageExists): ?>
                    <?= $this->Html->image('lost_items/' . $lostItem->image, [
                        'alt' => $lostItem->item_name
                    ]) ?>
                <?php else: ?>
                    <div class="no-image">
                        No Image
                    </div>
                <?php endif; ?>
            </div>

            <div class="lost-info">

                <?php if ($isOwner && !$isAdmin): ?>
                    <span class="owner-badge">Your Report</span>
                <?php endif; ?>

                <h3><?= h($lostItem->item_name) ?></h3>

                <div class="info-row">
                    <span class="info-label">Reporter</span>
                    <span class="info-value">
                        <?= h($reporterName) ?>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Category</span>
                    <span class="info-value"><?= h($lostItem->category) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Location</span>
                    <span class="info-value"><?= h($lostItem->location) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Date Lost</span>
                    <span class="info-value"><?= h($lostItem->date_lost) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        <span class="status-badge <?= $statusClass ?>">
                            <?= h($lostItem->status) ?>
                        </span>
                    </span>
                </div>

                <div class="card-actions">

                    <?= $this->Html->link(
                        'View',
                        ['action' => 'view', $lostItem->id],
                        ['class' => 'view-btn']
                    ) ?>

                    <?php if ($canModify): ?>

                        <?= $this->Html->link(
                            'Edit',
                            ['action' => 'edit', $lostItem->id],
                            ['class' => 'edit-btn']
                        ) ?>

                        <?= $this->Form->postLink(
                            'Delete',
                            ['action' => 'delete', $lostItem->id],
                            [
                                'confirm' => __('Are you sure you want to delete # {0}?', $lostItem->id),
                                'class' => 'delete-btn'
                            ]
                        ) ?>

                    <?php endif; ?>

                </div>

            </div>
        </div>

    <?php endforeach; ?>

</div>

<div class="empty-search-message" id="emptySearchMessage">
    No lost items match your search.
</div>

<div class="pagination-wrapper">
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>

        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}')) ?></p>
    </div>
</div>

<!-- live search removed for LostItems page (filtering now only on Items page) -->