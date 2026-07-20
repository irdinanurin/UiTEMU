<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Certificate $certificate
 * @var \Cake\Collection\CollectionInterface|string[] $users
 */
?>

<style>
    .cert-add-page {
        max-width: 1100px;
        margin: 0 auto;
    }

    .cert-header {
        margin-bottom: 25px;
    }

    .cert-header h2 {
        font-size: 32px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 8px;
    }

    .cert-header p {
        color: #64748b;
        margin: 0;
        font-size: 15px;
    }

    .cert-form-wrapper {
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
        background: linear-gradient(180deg, #ecfeff, #ffffff);
    }

    .info-icon {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        background: #0f766e;
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
        border-color: #0f766e;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.15);
    }

    .button-row {
        display: flex;
        gap: 12px;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .submit-btn {
        background: #0f766e;
        color: white;
        border: none;
        padding: 13px 22px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 8px 20px rgba(15, 118, 110, 0.15);
    }

    .submit-btn:hover {
        background: #0d5c57;
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
        .cert-form-wrapper {
            grid-template-columns: 1fr;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="cert-add-page">
    <div class="cert-header">
        <h2>Create Certificate</h2>
        <p>Issue a new appreciation certificate for a student or a verified report contributor.</p>
    </div>

    <div class="cert-form-wrapper">
        <div class="info-card">
            <div class="info-icon">
                <i class="fa fa-certificate"></i>
            </div>

            <h3>Certificate Details</h3>
            <p>
                Create a certificate with the right recipient, reference item, and supporting text for the final PDF.
            </p>

            <ul class="info-list">
                <li>Choose the correct recipient user.</li>
                <li>Use a unique certificate number.</li>
                <li>Provide a clear title and description.</li>
            </ul>
        </div>

        <div class="form-card">
            <h3>New Certificate</h3>
            <p class="form-note">Fill in the details below to prepare the certificate.</p>

            <?= $this->Form->create($certificate) ?>

            <div class="form-grid">
                <div class="form-group">
                    <?= $this->Form->control('user_id', [
                        'label' => 'Recipient User',
                        'options' => $users ?? [],
                        'empty' => 'Select User',
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>

                <?php if (isset($foundItems)): ?>
                    <div class="form-group">
                        <?= $this->Form->control('found_item_id', [
                            'label' => 'Found Item',
                            'options' => $foundItems,
                            'empty' => 'Select Found Item',
                            'templates' => ['inputContainer' => '{{content}}']
                        ]) ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <?= $this->Form->control('certificate_no', [
                        'label' => 'Certificate Number',
                        'placeholder' => 'Example: CERT-20260619-001',
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>

                <div class="form-group">
                    <?= $this->Form->control('issue_date', [
                        'label' => 'Issue Date',
                        'type' => 'date',
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>

                <div class="form-group full">
                    <?= $this->Form->control('title', [
                        'label' => 'Certificate Title',
                        'placeholder' => 'Certificate of Appreciation',
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>

                <div class="form-group full">
                    <?= $this->Form->control('description', [
                        'label' => 'Description',
                        'type' => 'textarea',
                        'placeholder' => 'Write certificate description here...',
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>

                <div class="form-group">
                    <?= $this->Form->control('status', [
                        'label' => 'Status',
                        'type' => 'select',
                        'options' => [
                            'Issued' => 'Issued',
                            'Draft' => 'Draft',
                            'Cancelled' => 'Cancelled'
                        ],
                        'empty' => 'Select Status',
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>
            </div>

            <div class="button-row">
                <?= $this->Form->button(__('Create Certificate'), ['class' => 'submit-btn']) ?>
                <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'back-btn']) ?>
            </div>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
