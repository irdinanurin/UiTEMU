<?php
/**
 * @var \App\View\AppView $this
 * @var array $items
 */
?>

<style>
    .items-page {
        width: 100%;
    }

    .items-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .items-title-group h2 {
        font-size: 42px;
        font-weight: 900;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.8px;
    }

    .items-title-group p {
        margin: 8px 0 0;
        color: #64748b;
        font-size: 16px;
        font-weight: 500;
    }

    .header-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }

    .add-btn {
        color: #ffffff;
        padding: 14px 20px;
        border-radius: 14px;
        font-weight: 800;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 50px;
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.22);
        transition: 0.2s ease;
        white-space: nowrap;
    }

    .add-btn:hover {
        transform: translateY(-3px);
        color: #ffffff;
        box-shadow: 0 14px 28px rgba(37, 99, 235, 0.28);
    }

    .add-lost-btn {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        box-shadow: 0 10px 24px rgba(239, 68, 68, 0.22);
    }

    .add-found-btn {
        background: linear-gradient(135deg, #06b6d4 0%, #2563eb 100%);
    }

    .items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 28px;
        align-items: start;
    }

    .item-card {
        background: #ffffff;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        transition: 0.25s ease;
        position: relative;
        border: 1px solid rgba(226, 232, 240, 0.7);
    }

    .item-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 38px rgba(15, 23, 42, 0.14);
    }

    .type-ribbon {
        position: absolute;
        top: 14px;
        left: 14px;
        z-index: 2;
        padding: 7px 13px;
        border-radius: 999px;
        color: #ffffff;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        box-shadow: 0 6px 15px rgba(15, 23, 42, 0.18);
    }

    .type-lost {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .type-found {
        background: linear-gradient(135deg, #06b6d4, #2563eb);
    }

    .item-image {
        width: 100%;
        height: 210px;
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        padding: 16px;
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .no-image {
        width: 100%;
        height: 100%;
        border: 2px dashed #cbd5e1;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-size: 14px;
        font-weight: 800;
        background: #ffffff;
    }

    .item-info {
        padding: 22px;
    }

    .item-info h3 {
        font-size: 25px;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 18px;
        text-transform: capitalize;
        line-height: 1.2;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 11px;
        font-size: 14px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 9px;
    }

    .info-label {
        color: #64748b;
        font-weight: 800;
    }

    .info-value {
        color: #111827;
        text-align: right;
        font-weight: 800;
        max-width: 60%;
        word-break: break-word;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 13px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-found,
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

    .card-actions {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 20px;
    }

    .view-btn {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        padding: 11px 17px;
        border-radius: 11px;
        text-decoration: none;
        font-weight: 800;
        box-shadow: 0 8px 18px rgba(37, 99, 235, 0.2);
        transition: 0.2s ease;
    }

    .view-btn:hover {
        transform: translateY(-2px);
        color: white;
    }

    .empty-search-message {
        display: none;
        text-align: center;
        background: #ffffff;
        padding: 30px;
        border-radius: 18px;
        color: #64748b;
        font-weight: 800;
        margin-top: 24px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    .empty-items {
        background: #ffffff;
        padding: 45px;
        border-radius: 24px;
        text-align: center;
        color: #64748b;
        font-weight: 800;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    @media (max-width: 768px) {
        .items-page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .items-title-group h2 {
            font-size: 34px;
        }

        .header-actions {
            width: 100%;
        }

        .add-btn {
            width: 100%;
        }

        .items-grid {
            grid-template-columns: 1fr;
        }

        .info-row {
            flex-direction: column;
            gap: 4px;
        }

        .info-value {
            max-width: 100%;
            text-align: left;
        }
    }
</style>

<div class="items-page">

    <div class="items-page-header">
        <div class="items-title-group">
            <h2>Items</h2>
            <p>View all lost and found item reports in one unified page.</p>
        </div>

        <div class="header-actions">
            <?= $this->Html->link(
                '+ Report Lost Item',
                ['controller' => 'LostItems', 'action' => 'add'],
                ['class' => 'add-btn add-lost-btn']
            ) ?>

            <?= $this->Html->link(
                '+ Report Found Item',
                ['controller' => 'FoundItems', 'action' => 'add'],
                ['class' => 'add-btn add-found-btn']
            ) ?>
        </div>
    </div>

    <?php if (!empty($items)): ?>

        <div class="items-grid" id="itemsGrid">

            <?php foreach ($items as $it): ?>
                <?php
                    $type = strtolower((string)$it['type']);
                    $status = strtolower((string)$it['status']);

                    if ($status === 'pending') {
                        $statusClass = 'status-pending';
                    } elseif ($status === 'found' || $status === 'resolved') {
                        $statusClass = 'status-found';
                    } elseif ($status === 'available') {
                        $statusClass = 'status-available';
                    } elseif ($status === 'claimed') {
                        $statusClass = 'status-claimed';
                    } else {
                        $statusClass = 'status-other';
                    }

                    $typeClass = $type === 'lost' ? 'type-lost' : 'type-found';

                    $imageExists = false;

                    if (!empty($it['image'])) {
                        $imagePath = WWW_ROOT . 'img' . DS . ($type === 'lost' ? 'lost_items' : 'found_items') . DS . $it['image'];
                        $imageExists = file_exists($imagePath);
                    }

                    $searchText = strtolower(
                        $it['item_name'] . ' ' .
                        $it['category'] . ' ' .
                        $it['location'] . ' ' .
                        $it['status'] . ' ' .
                        $it['reporter'] . ' ' .
                        $type
                    );

                    $viewUrl = $type === 'lost'
                        ? ['controller' => 'LostItems', 'action' => 'view', $it['id']]
                        : ['controller' => 'FoundItems', 'action' => 'view', $it['id']];
                ?>

                <div class="item-card" data-search="<?= h($searchText) ?>" data-type="<?= h($type) ?>">

                    <div class="type-ribbon <?= $typeClass ?>">
                        <?= h($type) ?>
                    </div>

                    <div class="item-image">
                        <?php if (!empty($it['image']) && $imageExists): ?>
                            <?= $this->Html->image(
                                ($type === 'lost' ? 'lost_items/' : 'found_items/') . $it['image'],
                                ['alt' => $it['item_name']]
                            ) ?>
                        <?php else: ?>
                            <div class="no-image">No Image</div>
                        <?php endif; ?>
                    </div>

                    <div class="item-info">
                        <h3><?= h($it['item_name']) ?></h3>

                        <div class="info-row">
                            <span class="info-label">Type</span>
                            <span class="info-value"><?= h(ucfirst($type)) ?></span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Reporter</span>
                            <span class="info-value"><?= h($it['reporter']) ?></span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Category</span>
                            <span class="info-value"><?= h($it['category']) ?></span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Location</span>
                            <span class="info-value"><?= h($it['location']) ?></span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Date</span>
                            <span class="info-value"><?= h($it['date']) ?></span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <span class="info-value">
                                <span class="status-badge <?= $statusClass ?>">
                                    <?= h($it['status']) ?>
                                </span>
                            </span>
                        </div>

                        <div class="card-actions">
                            <?= $this->Html->link('View Details', $viewUrl, ['class' => 'view-btn']) ?>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <div class="empty-items">
            No item reports available yet.
        </div>

    <?php endif; ?>

    <div class="empty-search-message" id="emptySearchMessage">
        No items match your search.
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('liveSearch');
    const scopeSelect = document.getElementById('searchScope');
    const cards = document.querySelectorAll('.item-card');
    const emptyMessage = document.getElementById('emptySearchMessage');

    function filterItems() {
        const value = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const scope = scopeSelect ? scopeSelect.value : 'all';
        let visibleCount = 0;

        cards.forEach(function (card) {
            const searchText = card.getAttribute('data-search') || '';
            const type = card.getAttribute('data-type') || '';

            const matchesScope = scope === 'all' || scope === type;
            const matchesSearch = value === '' || searchText.includes(value);

            if (matchesScope && matchesSearch) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (emptyMessage) {
            emptyMessage.style.display = visibleCount === 0 && cards.length > 0 ? 'block' : 'none';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterItems);
        searchInput.addEventListener('keyup', filterItems);
    }

    if (scopeSelect) {
        scopeSelect.addEventListener('change', filterItems);
    }

    filterItems();
});
</script>