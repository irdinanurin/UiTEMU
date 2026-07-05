<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Claim $claim
 * @var \App\Model\Entity\FoundItem $foundItem
 * @var array $lostItems
 */
?>

<style>
    .claim-page {
        max-width: 1100px;
        margin: 0 auto;
    }

    .claim-header {
        margin-bottom: 25px;
    }

    .claim-header h2 {
        font-size: 32px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 8px;
    }

    .claim-header p {
        color: #64748b;
        margin: 0;
    }

    .claim-wrapper {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 25px;
        align-items: start;
    }

    .found-preview,
    .claim-form-card {
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.07);
        overflow: hidden;
    }

    .found-image {
        width: 100%;
        height: 230px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px;
    }

    .found-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .no-image {
        color: #64748b;
        font-weight: 600;
    }

    .found-info {
        padding: 20px;
    }

    .found-info h3 {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 15px;
        text-transform: capitalize;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .info-label {
        color: #64748b;
        font-weight: 600;
    }

    .info-value {
        color: #111827;
        font-weight: 600;
        text-align: right;
    }

    .claim-form-card {
        padding: 25px;
    }

    .claim-form-card h3 {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 8px;
    }

    .form-note {
        color: #64748b;
        margin-bottom: 22px;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        font-weight: 700;
        color: #374151;
        margin-bottom: 8px;
        display: block;
    }

    .form-group select,
    .form-group input,
    .form-group textarea {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 15px;
        background: #f9fafb;
        box-sizing: border-box;
    }

    .form-group textarea {
        min-height: 120px;
        resize: vertical;
    }

    .form-group select:focus,
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #2563eb;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .alert-box {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        color: #9a3412;
        padding: 14px 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .button-row {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .submit-btn {
        background: #2563eb;
        color: white;
        border: none;
        padding: 13px 22px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .submit-btn:hover {
        background: #1d4ed8;
    }

    .back-btn {
        background: #e5e7eb;
        color: #374151;
        padding: 13px 22px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
    }

    .back-btn:hover {
        background: #d1d5db;
        color: #111827;
    }

    .report-lost-btn {
        display: inline-block;
        margin-top: 10px;
        background: #dc2626;
        color: white;
        padding: 10px 16px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
    }

    .report-lost-btn:hover {
        background: #b91c1c;
        color: white;
    }

    @media (max-width: 900px) {
        .claim-wrapper {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="claim-page">

    <div class="claim-header">
        <h2>Claim Found Item</h2>
        <p>Please provide details to help the admin verify that this item belongs to you.</p>
    </div>

    <div class="claim-wrapper">

        <div class="found-preview">

            <div class="found-image">
                <?php
                $imageExists = false;

                if (!empty($foundItem->image)) {
                    $imagePath = WWW_ROOT . 'img' . DS . 'found_items' . DS . $foundItem->image;
                    $imageExists = file_exists($imagePath);
                }
                ?>

                <?php if (!empty($foundItem->image) && $imageExists): ?>
                    <?= $this->Html->image('found_items/' . $foundItem->image, [
                        'alt' => $foundItem->item_name
                    ]) ?>
                <?php else: ?>
                    <div class="no-image">No Image</div>
                <?php endif; ?>
            </div>

            <div class="found-info">
                <h3><?= h($foundItem->item_name) ?></h3>

                <div class="info-row">
                    <span class="info-label">Category</span>
                    <span class="info-value"><?= h($foundItem->category) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Found Location</span>
                    <span class="info-value"><?= h($foundItem->location) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Date Found</span>
                    <span class="info-value"><?= h($foundItem->date_found) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value"><?= h($foundItem->status) ?></span>
                </div>
            </div>

        </div>

        <div class="claim-form-card">

            <h3>Ownership Verification</h3>
            <p class="form-note">
                Select your lost item report and describe unique details that only the real owner would know.
            </p>

            <?php if (empty($lostItems)): ?>

                <div class="alert-box">
                    You have not reported any lost item yet. Please report your lost item first before making a claim.
                    <br>
                    <?= $this->Html->link(
                        'Report Lost Item',
                        ['controller' => 'LostItems', 'action' => 'add'],
                        ['class' => 'report-lost-btn']
                    ) ?>
                </div>

            <?php else: ?>

                <?= $this->Form->create($claim) ?>

                <div class="form-group">
                    <?= $this->Form->control('lost_item_id', [
                        'label' => 'Which of your lost item reports matches this found item?',
                        'options' => $lostItems,
                        'empty' => 'Select your lost item report',
                        'required' => true
                    ]) ?>
                </div>

                <div class="form-group">
                    <?= $this->Form->control('claim_reason', [
                        'label' => 'Why do you think this item is yours?',
                        'type' => 'textarea',
                        'placeholder' => 'Example: I lost this item at the cafe and it looks exactly like mine.',
                        'required' => true
                    ]) ?>
                </div>

                <div class="form-group">
                    <?= $this->Form->control('item_details', [
                        'label' => 'Describe unique details about your item',
                        'type' => 'textarea',
                        'placeholder' => 'Example: brand, defect, label, mark, colour, pattern, special feature',
                        'required' => true
                    ]) ?>
                </div>

                <div class="form-group">
                    <?= $this->Form->control('lost_location', [
                        'label' => 'Where did you lose it?',
                        'placeholder' => 'Example: Cafe, library, classroom, parking area',
                        'required' => true
                    ]) ?>
                </div>

                <div class="form-group">
                    <?= $this->Form->control('lost_date', [
                        'label' => 'When did you lose it?',
                        'type' => 'date',
                        'required' => true
                    ]) ?>
                </div>

                <div class="button-row">
                    <?= $this->Form->button('Submit Claim', [
                        'class' => 'submit-btn'
                    ]) ?>

                    <?= $this->Html->link(
                        'Back to Found Items',
                        ['controller' => 'FoundItems', 'action' => 'index'],
                        ['class' => 'back-btn']
                    ) ?>
                </div>

                <?= $this->Form->end() ?>

            <?php endif; ?>

        </div>

    </div>

</div>