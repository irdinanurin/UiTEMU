<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\FoundItem> $foundItems
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
        background: #2563eb;
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        box-shadow: 0 5px 15px rgba(37, 99, 235, 0.25);
    }

    .add-btn:hover {
        background: #1d4ed8;
        color: white;
    }

    .found-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 300px));
        gap: 25px;
        justify-content: start;
        align-items: start;
    }

    .found-card {
        background: #ffffff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
        transition: 0.3s;
        position: relative;
        width: 100%;
        max-width: 300px;
    }

    .found-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
    }

    .item-number {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #2563eb;
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

    .found-image {
        width: 100%;
        height: 190px;
        background: #f8fafc;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        padding: 10px;
    }

    .found-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .no-image {
        color: #64748b;
        font-size: 14px;
        font-weight: 600;
    }

    .found-info {
        padding: 18px;
    }

    .found-info h3 {
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

    .status-available {
        background: #dcfce7;
        color: #166534;
    }

    .status-claimed {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-other {
        background: #e0e7ff;
        color: #3730a3;
    }

    .owner-badge {
        display: inline-block;
        background: #e0f2fe;
        color: #075985;
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

    .claim-btn {
        background: #10b981;
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
    <h2><?= __('Found Items') ?></h2>

    <?= $this->Html->link(
        '+ New Found Item',
        ['action' => 'add'],
        ['class' => 'add-btn']
    ) ?>
</div>

<?php
?>

<div class="found-grid" id="foundItemsGrid">

    <?php foreach ($foundItems as $foundItem): ?>

        <?php
        $status = strtolower((string)$foundItem->status);

        if ($status === 'available') {
            $statusClass = 'status-available';
        } elseif ($status === 'claimed') {
            $statusClass = 'status-claimed';
        } else {
            $statusClass = 'status-other';
        }

        $imageExists = false;

        if (!empty($foundItem->image)) {
            $imagePath = WWW_ROOT . 'img' . DS . 'found_items' . DS . $foundItem->image;
            $imageExists = file_exists($imagePath);
        }

        $reporterName = $foundItem->has('user') ? $foundItem->user->name : 'No User';

        $isOwner = (int)$foundItem->user_id === (int)$currentUserId;
        $canModify = $isAdmin || $isOwner;
        $canClaim = !$isAdmin && !$isOwner && $status === 'available';

        $searchText = strtolower(
            $foundItem->item_name . ' ' .
            $foundItem->category . ' ' .
            $foundItem->location . ' ' .
            $foundItem->status . ' ' .
            $reporterName
        );
        ?>

        <div class="found-card" data-search="<?= h($searchText) ?>">

            <div class="found-image">
                <?php if (!empty($foundItem->image) && $imageExists): ?>
                    <?= $this->Html->image('found_items/' . $foundItem->image, [
                        'alt' => $foundItem->item_name
                    ]) ?>
                <?php else: ?>
                    <div class="no-image">
                        No Image
                    </div>
                <?php endif; ?>
            </div>

            <div class="found-info">

                <?php if ($isOwner && !$isAdmin): ?>
                    <span class="owner-badge">Your Report</span>
                <?php endif; ?>

                <h3><?= h($foundItem->item_name) ?></h3>

                <div class="info-row">
                    <span class="info-label">Reporter</span>
                    <span class="info-value">
                        <?= h($reporterName) ?>
                    </span>
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
                    <span class="info-label">Date Found</span>
                    <span class="info-value"><?= h($foundItem->date_found) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        <span class="status-badge <?= $statusClass ?>">
                            <?= h($foundItem->status) ?>
                        </span>
                    </span>
                </div>

                <div class="card-actions">

                    <?= $this->Html->link(
                        'View',
                        ['action' => 'view', $foundItem->id],
                        ['class' => 'view-btn']
                    ) ?>

                    <?php if ($canModify): ?>

                        <?= $this->Html->link(
                            'Edit',
                            ['action' => 'edit', $foundItem->id],
                            ['class' => 'edit-btn']
                        ) ?>

                        <?= $this->Form->postLink(
                            'Delete',
                            ['action' => 'delete', $foundItem->id],
                            [
                                'confirm' => __('Are you sure you want to delete # {0}?', $foundItem->id),
                                'class' => 'delete-btn'
                            ]
                        ) ?>

                    <?php endif; ?>

                    <?php if ($canClaim): ?>
                        <?= $this->Html->link(
                            'Claim Item',
                            ['controller' => 'Claims', 'action' => 'add', $foundItem->id],
                            ['class' => 'claim-btn']
                        ) ?>
                    <?php endif; ?>

                </div>

            </div>
        </div>

    <?php endforeach; ?>

</div>

<div class="empty-search-message" id="emptySearchMessage">
    No found items match your search.
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

<!-- live search removed for FoundItems page (filtering now only on Items page) -->