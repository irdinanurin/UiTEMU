<?php
$certificateTitle = !empty($certificate->title)
    ? $certificate->title
    : 'Certificate of Appreciation';

$studentName = $certificate->has('user')
    ? $certificate->user->name
    : 'Student Name Not Found';

$certificateNo = !empty($certificate->certificate_no)
    ? $certificate->certificate_no
    : 'N/A';

if (!empty($certificate->issue_date)) {
    if (method_exists($certificate->issue_date, 'format')) {
        $issuedDate = $certificate->issue_date->format('d F Y');
    } else {
        $issuedDate = date('d F Y', strtotime((string)$certificate->issue_date));
    }
} else {
    $issuedDate = 'N/A';
}

$itemName = $certificate->has('found_item')
    ? $certificate->found_item->item_name
    : null;
?>

<style>
    @page {
        size: A4 landscape;
        margin: 0;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        font-family: DejaVu Sans, sans-serif;
        background: #ffffff;
    }

    .certificate {
        position: fixed;
        top: 8mm;
        left: 8mm;
        right: 8mm;
        bottom: 8mm;
        border: 3mm solid #1f4e79;
        padding: 15mm;
        text-align: center;
        overflow: hidden;
        box-sizing: border-box;
    }

    .cert-no {
        text-align: right;
        font-size: 10px;
        color: #000000;
        margin-bottom: 8mm;
    }

    .main-title {
        font-size: 28px;
        color: #1f4e79;
        text-transform: uppercase;
        letter-spacing: 3px;
        font-weight: bold;
        margin-bottom: 8mm;
    }

    .presented-text {
        font-size: 13px;
        margin-bottom: 5mm;
    }

    .student-name {
        font-size: 23px;
        font-weight: bold;
        margin-bottom: 6mm;
        color: #000000;
    }

    .description {
        font-size: 12px;
        line-height: 1.5;
        width: 80%;
        margin: 0 auto 6mm auto;
        color: #000000;
    }

    .item-box {
        width: 65%;
        margin: 5mm auto;
        padding: 4mm;
        border: 1px solid #999999;
        font-size: 12px;
    }

    .issued-date {
        font-size: 12px;
        font-weight: bold;
        margin-top: 5mm;
    }

    .signature {
        margin-top: 13mm;
        width: 60mm;
        margin-left: auto;
        margin-right: auto;
        border-top: 1px solid #000000;
        padding-top: 3mm;
        font-size: 11px;
    }

    .footer {
        margin-top: 6mm;
        font-size: 10px;
        color: #333333;
        line-height: 1.4;
    }
</style>

<div class="certificate">

    <div class="cert-no">
        Certificate No: <?= h($certificateNo) ?>
    </div>

    <div class="main-title">
        <?= h($certificateTitle) ?>
    </div>

    <div class="presented-text">
        This certificate is proudly presented to
    </div>

    <div class="student-name">
        <?= h($studentName) ?>
    </div>

    <div class="description">
        In recognition of honesty, responsibility, and contribution
        to the UiTEMU Lost & Found System.
    </div>

    <?php if (!empty($itemName)): ?>
        <div class="item-box">
            <strong>Reported Found Item:</strong><br>
            <?= h($itemName) ?>
        </div>
    <?php endif; ?>

    <div class="issued-date">
        Issued Date: <?= h($issuedDate) ?>
    </div>

    <div class="signature">
        Authorized Signature
    </div>

    <div class="footer">
        UiTEMU Lost & Found System<br>
        UiTM Puncak Perdana
    </div>

</div>