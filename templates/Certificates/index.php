<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Certificate> $certificates
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

$page = (int)$this->Paginator->param('page');
$perPage = (int)$this->Paginator->param('perPage');
$rowNumber = (($page > 0 ? $page : 1) - 1) * ($perPage > 0 ? $perPage : 20) + 1;
?>

<style>
    .cert-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }

    .cert-page-header h1 {
        margin: 0;
        font-size: 42px;
        font-weight: 800;
        color: #0f172a;
    }

    .cert-page-header p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 15px;
    }

    .cert-add-btn {
        background: #2563eb;
        color: #fff;
        padding: 14px 20px;
        border-radius: 14px;
        text-decoration: none;
        font-weight: 800;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.22);
        white-space: nowrap;
        transition: 0.2s ease;
    }

    .cert-add-btn:hover {
        background: #1d4ed8;
        color: #fff;
        transform: translateY(-1px);
    }

    .cert-table-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 12px 35px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }

    .cert-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .cert-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 980px;
    }

    .cert-table thead th {
        background: #f8fafc;
        color: #334155;
        font-size: 14px;
        font-weight: 800;
        padding: 18px 16px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .cert-table tbody td {
        padding: 18px 16px;
        border-bottom: 1px solid #eef2f7;
        vertical-align: middle;
        color: #0f172a;
        font-size: 14px;
    }

    .cert-table tbody tr:hover {
        background: #f8fbff;
    }

    .cert-no-col {
        width: 70px;
    }

    .cert-no-badge {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #2563eb;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        box-shadow: 0 6px 14px rgba(37, 99, 235, 0.25);
    }

    .cert-user-name,
    .cert-item-name {
        font-weight: 700;
        color: #0f172a;
    }

    .cert-code {
        font-weight: 800;
        color: #1d4ed8;
    }

    .cert-date {
        color: #475569;
        white-space: nowrap;
    }

    .cert-status-badge {
        display: inline-block;
        padding: 7px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .cert-status-issued {
        background: #dcfce7;
        color: #166534;
    }

    .cert-status-draft {
        background: #fef3c7;
        color: #92400e;
    }

    .cert-status-other {
        background: #e0e7ff;
        color: #3730a3;
    }

    .cert-actions {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
    }

    .cert-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 9px 13px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 800;
        border: none;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .cert-btn:hover {
        transform: translateY(-1px);
        opacity: 0.96;
        color: inherit;
    }

    .cert-btn-view {
        background: #2563eb;
        color: #fff;
    }

    .cert-btn-download {
        background: #10b981;
        color: #fff;
    }

    .cert-btn-edit {
        background: #f59e0b;
        color: #fff;
    }

    .cert-btn-delete {
        background: #ef4444;
        color: #fff;
    }

    .cert-empty-state {
        padding: 60px 24px;
        text-align: center;
        color: #64748b;
    }

    .cert-empty-state h3 {
        margin: 0 0 10px;
        font-size: 26px;
        color: #0f172a;
    }

    .cert-empty-state p {
        margin: 0;
        font-size: 15px;
    }

    .cert-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 18px 22px;
        background: #ffffff;
        border-top: 1px solid #eef2f7;
        flex-wrap: wrap;
    }

    .cert-pagination ul {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .cert-pagination li a,
    .cert-pagination li span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0 12px;
        border-radius: 10px;
        text-decoration: none;
        background: #f1f5f9;
        color: #334155;
        font-weight: 700;
    }

    .cert-pagination .active a,
    .cert-pagination .active span {
        background: #2563eb;
        color: #fff;
    }

    .cert-pagination .disabled span {
        opacity: 0.5;
    }

    .cert-counter {
        color: #64748b;
        font-size: 14px;
        font-weight: 600;
    }

    @media (max-width: 900px) {
        .cert-page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .cert-page-header h1 {
            font-size: 34px;
        }
    }
</style>

<div class="cert-page-header">
    <div>
        <h1><?= __('Certificates') ?></h1>
        <p>Manage and view generated appreciation certificates.</p>
    </div>

    <?php if ($isAdmin): ?>
        <?= $this->Html->link(
            '+ New Certificate',
            ['action' => 'add'],
            ['class' => 'cert-add-btn']
        ) ?>
    <?php endif; ?>
</div>

<div class="cert-table-card">
    <?php if (!empty($certificates) && count($certificates) > 0): ?>
        <div class="cert-table-wrap">
            <table class="cert-table">
                <thead>
                    <tr>
                        <th class="cert-no-col">No</th>
                        <th>User</th>
                        <th>Certificate No</th>
                        <th>Reported Item</th>
                        <th>Issue Date</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($certificates as $certificate): ?>
                        <?php
                            $status = strtolower((string)($certificate->status ?? 'issued'));

                            if ($status === 'issued') {
                                $statusClass = 'cert-status-issued';
                            } elseif ($status === 'draft') {
                                $statusClass = 'cert-status-draft';
                            } else {
                                $statusClass = 'cert-status-other';
                            }

                            $canView = $isAdmin || ((int)$certificate->user_id === (int)$currentUserId);
                        ?>
                        <tr>
                            <td>
                                <span class="cert-no-badge"><?= $rowNumber++ ?></span>
                            </td>

                            <td>
                                <span class="cert-user-name">
                                    <?= $certificate->has('user') ? h($certificate->user->name) : 'No User' ?>
                                </span>
                            </td>

                            <td>
                                <span class="cert-code"><?= h($certificate->certificate_no) ?></span>
                            </td>

                            <td>
                                <span class="cert-item-name">
                                    <?= $certificate->has('found_item') ? h($certificate->found_item->item_name) : 'No Item Linked' ?>
                                </span>
                            </td>

                            <td class="cert-date">
                                <?= h($certificate->issue_date) ?>
                            </td>

                            <td>
                                <span class="cert-status-badge <?= $statusClass ?>">
                                    <?= h($certificate->status ?? 'Issued') ?>
                                </span>
                            </td>

                            <td class="cert-date">
                                <?= h($certificate->created_at) ?>
                            </td>

                            <td class="cert-date">
                                <?= h($certificate->updated_at) ?>
                            </td>

                            <td>
                                <div class="cert-actions">
                                    <?php if ($canView): ?>
                                        <?= $this->Html->link(
                                            'View',
                                            ['action' => 'view', $certificate->id],
                                            ['class' => 'cert-btn cert-btn-view']
                                        ) ?>

                                        <?= $this->Html->link(
                                            'Download PDF',
                                            ['action' => 'downloadPdf', $certificate->id],
                                            ['class' => 'cert-btn cert-btn-download']
                                        ) ?>
                                    <?php endif; ?>

                                    <?php if ($isAdmin): ?>
                                        <?= $this->Html->link(
                                            'Edit',
                                            ['action' => 'edit', $certificate->id],
                                            ['class' => 'cert-btn cert-btn-edit']
                                        ) ?>

                                        <?= $this->Form->postLink(
                                            'Delete',
                                            ['action' => 'delete', $certificate->id],
                                            [
                                                'confirm' => __('Are you sure you want to delete certificate # {0}?', $certificate->id),
                                                'class' => 'cert-btn cert-btn-delete'
                                            ]
                                        ) ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="cert-footer">
            <div class="cert-pagination">
                <ul class="pagination">
                    <?= $this->Paginator->first('<<') ?>
                    <?= $this->Paginator->prev('<') ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next('>') ?>
                    <?= $this->Paginator->last('>>') ?>
                </ul>
            </div>

            <div class="cert-counter">
                <?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?>
            </div>
        </div>
    <?php else: ?>
        <div class="cert-empty-state">
            <h3>No Certificates Yet</h3>
            <p>There are no certificates available at the moment.</p>
        </div>
    <?php endif; ?>
</div>