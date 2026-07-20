<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Certificate $certificate
 * @var \Cake\Collection\CollectionInterface|array $users
 * @var \Cake\Collection\CollectionInterface|array $foundItems
 */
?>

<style>
    .cert-edit-page {
        max-width: 1200px;
        margin: 0 auto;
    }

    .cert-edit-header {
        margin-bottom: 24px;
    }

    .cert-edit-header h1 {
        margin: 0 0 8px;
        font-size: 32px;
        font-weight: 700;
        color: #111827;
    }

    .cert-edit-header p {
        margin: 0;
        color: #64748b;
        font-size: 15px;
    }

    .cert-edit-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 16px;
    }

    .cert-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 14px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: 0.2s ease;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.10);
    }

    .cert-btn:hover {
        transform: translateY(-2px);
        color: inherit;
    }

    .cert-btn-dark {
        background: #111827;
        color: #fff;
    }

    .cert-btn-blue {
        background: #2563eb;
        color: #fff;
    }

    .cert-btn-green {
        background: #0f766e;
        color: #fff;
    }

    .cert-btn-red {
        background: #ef4444;
        color: #fff;
    }

    .cert-edit-layout {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 24px;
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
        background: linear-gradient(180deg, #eff6ff, #ffffff);
    }

    .info-icon {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        background: #2563eb;
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
        border-color: #2563eb;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .button-row {
        display: flex;
        gap: 12px;
        margin-top: 10px;
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

    @media (max-width: 900px) {
        .cert-edit-layout {
            grid-template-columns: 1fr;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="cert-edit-page">

    <div class="cert-edit-header">
        <div>
            <h1>Edit Certificate</h1>
            <p>Update certificate details and recipient information.</p>
        </div>

        <div class="cert-edit-actions">
            <?= $this->Html->link('← Back to Certificates', ['action' => 'index'], ['class' => 'cert-btn cert-btn-dark']) ?>

            <?= $this->Html->link('View Certificate', ['action' => 'view', $certificate->id], ['class' => 'cert-btn cert-btn-blue']) ?>

            <?= $this->Html->link('Download PDF', ['action' => 'downloadPdf', $certificate->id], ['class' => 'cert-btn cert-btn-green']) ?>

            <?= $this->Form->postLink(
                'Delete',
                ['action' => 'delete', $certificate->id],
                [
                    'confirm' => __('Are you sure you want to delete certificate # {0}?', $certificate->id),
                    'class' => 'cert-btn cert-btn-red'
                ]
            ) ?>
        </div>
    </div>

    <div class="cert-edit-layout">
        <div class="info-card">
            <div class="info-icon">
                <i class="fa fa-certificate"></i>
            </div>

            <h3>Certificate Editor</h3>
            <p>
                Update the recipient, certificate reference, and supporting text before downloading the PDF.
            </p>

            <ul class="info-list">
                <li>Choose the correct recipient user.</li>
                <li>Keep the certificate number consistent and unique.</li>
                <li>Set the issue date and title for the final document.</li>
            </ul>
        </div>

        <div class="form-card">
            <h3>Certificate Details</h3>
            <p class="form-note">Edit the certificate information below.</p>

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
                <?= $this->Form->button('Save Certificate', ['class' => 'submit-btn']) ?>
                <?= $this->Html->link('Cancel', ['action' => 'view', $certificate->id], ['class' => 'back-btn']) ?>
            </div>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>