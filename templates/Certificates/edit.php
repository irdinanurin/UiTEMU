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
        padding: 10px 0 35px;
    }

    .cert-edit-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 26px;
    }

    .cert-edit-header h1 {
        margin: 0 0 8px;
        font-size: 52px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -1px;
    }

    .cert-edit-header p {
        margin: 0;
        font-size: 20px;
        color: #64748b;
    }

    .cert-edit-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .cert-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 13px 20px;
        border-radius: 14px;
        text-decoration: none;
        font-weight: 800;
        font-size: 16px;
        border: none;
        cursor: pointer;
        transition: 0.2s ease;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
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
        background: #10b981;
        color: #fff;
    }

    .cert-btn-red {
        background: #ef4444;
        color: #fff;
    }

    .cert-edit-layout {
        display: grid;
        grid-template-columns: 0.9fr 1.5fr;
        gap: 24px;
        align-items: start;
    }

    .cert-preview-card,
    .cert-form-card,
    .cert-help-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        padding: 28px;
    }

    .cert-preview-box {
        border: 7px solid #1e3a5f;
        padding: 34px 24px;
        text-align: center;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        min-height: 300px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .cert-preview-code {
        text-align: right;
        font-size: 13px;
        font-weight: 800;
        color: #334155;
        margin-bottom: 20px;
    }

    .cert-preview-title {
        font-size: 24px;
        font-weight: 900;
        letter-spacing: 4px;
        color: #1e3a5f;
        margin-bottom: 24px;
    }

    .cert-preview-small {
        color: #64748b;
        font-size: 15px;
        margin-bottom: 12px;
    }

    .cert-preview-name {
        font-size: 30px;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 20px;
    }

    .cert-preview-text {
        color: #334155;
        font-size: 15px;
        line-height: 1.6;
        margin-bottom: 18px;
    }

    .cert-preview-date {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
    }

    .cert-help-card {
        margin-top: 20px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
    }

    .cert-help-card strong {
        display: block;
        color: #1d4ed8;
        font-size: 18px;
        margin-bottom: 8px;
    }

    .cert-help-card p {
        margin: 0;
        color: #334155;
        font-size: 15px;
        line-height: 1.6;
    }

    .cert-form-card h2 {
        margin: 0 0 8px;
        font-size: 34px;
        font-weight: 800;
        color: #0f172a;
    }

    .cert-form-card .subtitle {
        margin: 0 0 24px;
        color: #64748b;
        font-size: 17px;
    }

    .cert-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 20px;
    }

    .cert-form-grid .full {
        grid-column: 1 / -1;
    }

    .cert-edit-page label {
        display: block;
        margin-bottom: 8px;
        color: #334155;
        font-size: 15px;
        font-weight: 800;
    }

    .cert-edit-page input,
    .cert-edit-page select,
    .cert-edit-page textarea {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 14px;
        padding: 14px 16px;
        font-size: 16px;
        color: #0f172a;
        background: #fff;
        box-sizing: border-box;
        transition: 0.2s ease;
    }

    .cert-edit-page textarea {
        min-height: 120px;
        resize: vertical;
    }

    .cert-edit-page input:focus,
    .cert-edit-page select:focus,
    .cert-edit-page textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
    }

    .cert-submit-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 26px;
    }

    .cert-submit {
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 14px;
        padding: 14px 24px;
        font-size: 17px;
        font-weight: 900;
        cursor: pointer;
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.22);
    }

    .cert-cancel {
        background: #e2e8f0;
        color: #0f172a;
        text-decoration: none;
        border-radius: 14px;
        padding: 14px 22px;
        font-size: 17px;
        font-weight: 900;
    }

    @media (max-width: 1100px) {
        .cert-edit-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .cert-edit-header h1 {
            font-size: 38px;
        }

        .cert-form-grid {
            grid-template-columns: 1fr;
        }

        .cert-edit-actions {
            width: 100%;
            flex-direction: column;
        }

        .cert-btn {
            width: 100%;
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

        <div>
            <div class="cert-preview-card">
                <div class="cert-preview-box">
                    <div class="cert-preview-code">
                        <?= h($certificate->certificate_no) ?>
                    </div>

                    <div class="cert-preview-title">
                        CERTIFICATE
                    </div>

                    <div class="cert-preview-small">
                        Certificate issued to
                    </div>

                    <div class="cert-preview-name">
                        <?= $certificate->has('user') ? h($certificate->user->name) : 'Selected User' ?>
                    </div>

                    <div class="cert-preview-text">
                        This certificate is given as appreciation for contributing to the UiTEMU Lost & Found System.
                    </div>

                    <div class="cert-preview-date">
                        Issue Date: <?= h($certificate->issue_date) ?>
                    </div>
                </div>
            </div>

            <div class="cert-help-card">
                <strong>Certificate Tip</strong>
                <p>
                    Use this page to update the certificate recipient, certificate number, issue date,
                    and certificate details before downloading the PDF.
                </p>
            </div>
        </div>

        <div class="cert-form-card">
            <h2>Certificate Details</h2>
            <p class="subtitle">Edit the certificate information below.</p>

            <?= $this->Form->create($certificate) ?>

            <div class="cert-form-grid">

                <div>
                    <?= $this->Form->control('user_id', [
                        'label' => 'Recipient User',
                        'options' => $users ?? [],
                        'empty' => 'Select User',
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>

                <?php if (isset($foundItems)): ?>
                    <div>
                        <?= $this->Form->control('found_item_id', [
                            'label' => 'Found Item',
                            'options' => $foundItems,
                            'empty' => 'Select Found Item',
                            'templates' => ['inputContainer' => '{{content}}']
                        ]) ?>
                    </div>
                <?php endif; ?>

                <div>
                    <?= $this->Form->control('certificate_no', [
                        'label' => 'Certificate Number',
                        'placeholder' => 'Example: CERT-20260619-001',
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>

                <div>
                    <?= $this->Form->control('issue_date', [
                        'label' => 'Issue Date',
                        'type' => 'date',
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>

                <div class="full">
                    <?= $this->Form->control('title', [
                        'label' => 'Certificate Title',
                        'placeholder' => 'Certificate of Appreciation',
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>

                <div class="full">
                    <?= $this->Form->control('description', [
                        'label' => 'Description',
                        'type' => 'textarea',
                        'placeholder' => 'Write certificate description here...',
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>
                </div>

                <div>
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

            <div class="cert-submit-row">
                <?= $this->Form->button('Save Certificate', ['class' => 'cert-submit']) ?>

                <?= $this->Html->link('Cancel', ['action' => 'view', $certificate->id], ['class' => 'cert-cancel']) ?>
            </div>

            <?= $this->Form->end() ?>
        </div>

    </div>
</div>