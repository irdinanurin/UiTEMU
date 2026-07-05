<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LostItem $lostItem
 */
?>

<style>
    .lost-add-page {
        max-width: 1100px;
        margin: 0 auto;
    }

    .lost-header {
        margin-bottom: 25px;
    }

    .lost-header h2 {
        font-size: 32px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 8px;
    }

    .lost-header p {
        color: #64748b;
        margin: 0;
        font-size: 15px;
    }

    .lost-form-wrapper {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 25px;
        align-items: start;
    }

    .info-card,
    .form-card {
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.07);
        overflow: hidden;
    }

    .info-card {
        padding: 28px;
        background: linear-gradient(180deg, #fee2e2, #ffffff);
    }

    .info-icon {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        background: #dc2626;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 20px;
    }

    .info-card h3 {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 12px;
    }

    .info-card p {
        color: #4b5563;
        font-size: 15px;
        line-height: 1.6;
        margin-bottom: 16px;
    }

    .info-list {
        margin: 0;
        padding-left: 18px;
        color: #374151;
        font-size: 14px;
        line-height: 1.7;
    }

    .form-card {
        padding: 28px;
    }

    .form-card h3 {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 8px;
    }

    .form-note {
        color: #64748b;
        margin-bottom: 24px;
        font-size: 14px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-group label {
        font-weight: 700;
        color: #374151;
        margin-bottom: 8px;
        display: block;
    }

    .form-group input,
    .form-group select,
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
        min-height: 110px;
        resize: vertical;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #dc2626;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15);
    }

    .button-row {
        display: flex;
        gap: 12px;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .submit-btn {
        background: #dc2626;
        color: white;
        border: none;
        padding: 13px 22px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .submit-btn:hover {
        background: #b91c1c;
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

    @media (max-width: 900px) {
        .lost-form-wrapper {
            grid-template-columns: 1fr;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="lost-add-page">

    <div class="lost-header">
        <h2>Report Lost Item</h2>
        <p>Fill in the details carefully so the admin can match your lost item with any reported found item.</p>
    </div>

    <div class="lost-form-wrapper">

        <div class="info-card">
            <div class="info-icon">
                <i class="fa fa-search"></i>
            </div>

            <h3>Lost Something?</h3>

            <p>
                Submit your lost item report with clear details. This helps UiTEMU verify ownership when someone finds your item.
            </p>

            <ul class="info-list">
                <li>Use a clear item name.</li>
                <li>Add the location where you lost it.</li>
                <li>Include private details only the owner knows.</li>
                <li>Upload an image if you have one.</li>
            </ul>
        </div>

        <div class="form-card">

            <h3>Lost Item Details</h3>
            <p class="form-note">
                Private verification details will help admin confirm that the item really belongs to you.
            </p>

            <?= $this->Form->create($lostItem, ['type' => 'file']) ?>

            <div class="form-grid">

                <div class="form-group">
                    <?= $this->Form->control('item_name', [
                        'label' => 'Item Name',
                        'placeholder' => 'Example: iPhone 15, wallet, bottle',
                        'required' => true
                    ]) ?>
                </div>

                <div class="form-group">
                    <?= $this->Form->control('category', [
                        'label' => 'Category',
                        'placeholder' => 'Example: electronics, personal belonging',
                        'required' => true
                    ]) ?>
                </div>

                <div class="form-group">
                    <?= $this->Form->control('location', [
                        'label' => 'Lost Location',
                        'placeholder' => 'Example: cafe, library, lab 7',
                        'required' => true
                    ]) ?>
                </div>

                <div class="form-group">
                    <?= $this->Form->control('date_lost', [
                        'label' => 'Date Lost',
                        'type' => 'date',
                        'required' => true
                    ]) ?>
                </div>

                <div class="form-group full">
                    <?= $this->Form->control('description', [
                        'label' => 'Description',
                        'type' => 'textarea',
                        'placeholder' => 'Describe the item generally. Example: pink phone, black wallet, silver laptop.',
                        'required' => true
                    ]) ?>
                </div>

                <div class="form-group full">
                    <?= $this->Form->control('private_details', [
                        'label' => 'Private Verification Details',
                        'type' => 'textarea',
                        'placeholder' => 'Example: lock screen picture, sticker, scratch, hidden mark, special feature.',
                        'required' => true
                    ]) ?>
                </div>

                <div class="form-group full">
                    <?= $this->Form->control('image', [
                        'label' => 'Upload Image',
                        'type' => 'file'
                    ]) ?>
                </div>

                <?= $this->Form->hidden('status', ['value' => 'Pending']) ?>

            </div>

            <div class="button-row">
                <?= $this->Form->button('Submit Lost Item', [
                    'class' => 'submit-btn'
                ]) ?>

                <?= $this->Html->link(
                    'Back to Lost Items',
                    ['action' => 'index'],
                    ['class' => 'back-btn']
                ) ?>
            </div>

            <?= $this->Form->end() ?>

        </div>

    </div>

</div>