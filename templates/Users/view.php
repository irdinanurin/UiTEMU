<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */

$currentUser = $this->request->getAttribute('identity');
$isAdmin = $currentUser && $currentUser->role === 'admin';

$lostCount = !empty($user->lost_items) ? count($user->lost_items) : 0;
$foundCount = !empty($user->found_items) ? count($user->found_items) : 0;
$claimCount = !empty($user->claims) ? count($user->claims) : 0;
$certificateCount = !empty($user->certificates) ? count($user->certificates) : 0;

$isOwnAccount = $currentUser && ((int)$currentUser->id === (int)$user->id);
?>

<style>
    .users-view-page {
        padding: 10px 0 30px;
    }

    .users-view-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 24px;
    }

    .users-view-title h1 {
        font-size: 56px;
        line-height: 1.05;
        margin: 0 0 8px;
        color: #0f172a;
        font-weight: 800;
        letter-spacing: -1px;
    }

    .users-view-title p {
        margin: 0;
        font-size: 25px;
        color: #64748b;
        font-weight: 500;
    }

    .users-view-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 14px 22px;
        border-radius: 14px;
        text-decoration: none;
        font-weight: 700;
        font-size: 20px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
    }

    .action-btn:hover {
        transform: translateY(-2px);
        opacity: 0.95;
    }

    .btn-dark {
        background: #0f172a;
        color: #fff;
    }

    .btn-blue {
        background: #2563eb;
        color: #fff;
    }

    .btn-amber {
        background: #f59e0b;
        color: #fff;
    }

    .btn-red {
        background: #ef4444;
        color: #fff;
    }

    .user-top-grid {
        display: grid;
        grid-template-columns: 1.1fr 1.4fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    .user-card,
    .info-card,
    .summary-card,
    .related-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        padding: 28px;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .avatar-circle {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563eb, #60a5fa);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 38px;
        font-weight: 800;
        flex-shrink: 0;
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.25);
        text-transform: uppercase;
    }

    .user-profile h2 {
        margin: 0 0 8px;
        font-size: 42px;
        color: #0f172a;
        font-weight: 800;
        line-height: 1.1;
    }

    .user-email {
        margin: 0 0 14px;
        color: #64748b;
        font-size: 22px;
        word-break: break-word;
    }

    .role-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 16px;
        font-weight: 800;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    .role-admin {
        background: #fee2e2;
        color: #b91c1c;
    }

    .role-user {
        background: #dcfce7;
        color: #166534;
    }

    .section-title {
        font-size: 28px;
        color: #0f172a;
        font-weight: 800;
        margin: 0 0 20px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .info-item {
        background: #f8fafc;
        border-radius: 18px;
        padding: 18px 20px;
        border: 1px solid #e2e8f0;
    }

    .info-label {
        display: block;
        font-size: 16px;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .info-value {
        font-size: 26px;
        color: #0f172a;
        font-weight: 800;
        word-break: break-word;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .summary-box {
        border-radius: 22px;
        color: #fff;
        padding: 24px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.10);
    }

    .summary-box h4 {
        margin: 0 0 12px;
        font-size: 21px;
        font-weight: 700;
    }

    .summary-box .summary-number {
        font-size: 44px;
        font-weight: 900;
        line-height: 1;
    }

    .summary-lost {
        background: linear-gradient(135deg, #ef4444, #f87171);
    }

    .summary-found {
        background: linear-gradient(135deg, #22c55e, #4ade80);
    }

    .summary-claims {
        background: linear-gradient(135deg, #a855f7, #c084fc);
    }

    .summary-certs {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 24px;
    }

    .related-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .related-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 16px 18px;
    }

    .related-item strong {
        display: block;
        font-size: 20px;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .related-meta {
        font-size: 16px;
        color: #64748b;
        line-height: 1.6;
    }

    .empty-state {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 18px;
        padding: 28px;
        text-align: center;
        color: #64748b;
        font-size: 18px;
        font-weight: 600;
    }

    .mini-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 800;
        margin-top: 6px;
    }

    .badge-pending {
        background: #fef3c7;
        color: #b45309;
    }

    .badge-approved,
    .badge-found,
    .badge-available {
        background: #dcfce7;
        color: #166534;
    }

    .badge-rejected,
    .badge-claimed {
        background: #fee2e2;
        color: #b91c1c;
    }

    @media (max-width: 1100px) {
        .user-top-grid,
        .related-grid,
        .summary-grid {
            grid-template-columns: 1fr;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .users-view-title h1 {
            font-size: 38px;
        }

        .users-view-title p {
            font-size: 18px;
        }

        .user-profile {
            flex-direction: column;
            align-items: flex-start;
        }

        .user-profile h2 {
            font-size: 32px;
        }

        .action-btn {
            width: 100%;
        }

        .users-view-actions {
            width: 100%;
            flex-direction: column;
        }
    }
</style>

<div class="users-view-page">

    <div class="users-view-header">
        <div class="users-view-title">
            <h1>User Profile</h1>
            <p>View registered user details and activity in the UiTEMU system.</p>
        </div>

        <div class="users-view-actions">
            <?= $this->Html->link('← Back to Users', ['action' => 'index'], ['class' => 'action-btn btn-dark']) ?>

            <?php if ($isAdmin): ?>
                <?= $this->Html->link('Edit User', ['action' => 'edit', $user->id], ['class' => 'action-btn btn-amber']) ?>

                <?php if (!$isOwnAccount): ?>
                    <?= $this->Form->postLink(
                        'Delete User',
                        ['action' => 'delete', $user->id],
                        [
                            'confirm' => __('Are you sure you want to delete user # {0}?', $user->id),
                            'class' => 'action-btn btn-red'
                        ]
                    ) ?>
                <?php endif; ?>

                <?= $this->Html->link('+ New User', ['action' => 'add'], ['class' => 'action-btn btn-blue']) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="user-top-grid">
        <div class="user-card">
            <div class="user-profile">
                <div class="avatar-circle">
                    <?= h(strtoupper(substr((string)$user->name, 0, 1))) ?>
                </div>

                <div>
                    <h2><?= h($user->name) ?></h2>
                    <p class="user-email"><?= h($user->email) ?></p>

                    <span class="role-badge <?= $user->role === 'admin' ? 'role-admin' : 'role-user' ?>">
                        <?= h($user->role) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="info-card">
            <h3 class="section-title">Account Information</h3>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">User ID</span>
                    <span class="info-value"><?= h($user->id) ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">Role</span>
                    <span class="info-value"><?= h(ucfirst((string)$user->role)) ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">Created At</span>
                    <span class="info-value" style="font-size:20px;">
                        <?= !empty($user->created_at) ? h($user->created_at) : '-' ?>
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">Updated At</span>
                    <span class="info-value" style="font-size:20px;">
                        <?= !empty($user->updated_at) ? h($user->updated_at) : '-' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="summary-grid">
        <div class="summary-box summary-lost">
            <h4>Lost Items</h4>
            <div class="summary-number"><?= $lostCount ?></div>
        </div>

        <div class="summary-box summary-found">
            <h4>Found Items</h4>
            <div class="summary-number"><?= $foundCount ?></div>
        </div>

        <div class="summary-box summary-claims">
            <h4>Claims</h4>
            <div class="summary-number"><?= $claimCount ?></div>
        </div>

        <div class="summary-box summary-certs">
            <h4>Certificates</h4>
            <div class="summary-number"><?= $certificateCount ?></div>
        </div>
    </div>

    <div class="related-grid">
        <div class="related-card">
            <h3 class="section-title">Recent Lost Items</h3>

            <?php if (!empty($user->lost_items)): ?>
                <div class="related-list">
                    <?php foreach (array_slice($user->lost_items, 0, 5) as $lostItem): ?>
                        <div class="related-item">
                            <strong><?= h($lostItem->item_name) ?></strong>
                            <div class="related-meta">
                                Category: <?= h($lostItem->category ?? '-') ?><br>
                                Location: <?= h($lostItem->location ?? '-') ?><br>
                                Date Lost: <?= h($lostItem->date_lost ?? '-') ?>
                            </div>
                            <span class="mini-badge <?= strtolower((string)$lostItem->status) === 'found' ? 'badge-found' : 'badge-pending' ?>">
                                <?= h($lostItem->status ?? 'Pending') ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">No lost items reported by this user.</div>
            <?php endif; ?>
        </div>

        <div class="related-card">
            <h3 class="section-title">Recent Found Items</h3>

            <?php if (!empty($user->found_items)): ?>
                <div class="related-list">
                    <?php foreach (array_slice($user->found_items, 0, 5) as $foundItem): ?>
                        <div class="related-item">
                            <strong><?= h($foundItem->item_name) ?></strong>
                            <div class="related-meta">
                                Category: <?= h($foundItem->category ?? '-') ?><br>
                                Location: <?= h($foundItem->location ?? '-') ?><br>
                                Date Found: <?= h($foundItem->date_found ?? '-') ?>
                            </div>
                            <span class="mini-badge <?= strtolower((string)$foundItem->status) === 'claimed' ? 'badge-claimed' : 'badge-available' ?>">
                                <?= h($foundItem->status ?? 'Available') ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">No found items reported by this user.</div>
            <?php endif; ?>
        </div>

        <div class="related-card">
            <h3 class="section-title">Recent Claims</h3>

            <?php if (!empty($user->claims)): ?>
                <div class="related-list">
                    <?php foreach (array_slice($user->claims, 0, 5) as $claim): ?>
                        <div class="related-item">
                            <strong>Claim #<?= h($claim->id) ?></strong>
                            <div class="related-meta">
                                Found Item ID: <?= h($claim->found_item_id ?? '-') ?><br>
                                Lost Item ID: <?= h($claim->lost_item_id ?? '-') ?><br>
                                Created: <?= h($claim->created_at ?? '-') ?>
                            </div>

                            <?php
                                $status = strtolower((string)($claim->claim_status ?? 'pending'));
                                $statusClass = 'badge-pending';

                                if ($status === 'approved') {
                                    $statusClass = 'badge-approved';
                                } elseif ($status === 'rejected') {
                                    $statusClass = 'badge-rejected';
                                }
                            ?>
                            <span class="mini-badge <?= $statusClass ?>">
                                <?= h($claim->claim_status ?? 'Pending') ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">No claims submitted by this user.</div>
            <?php endif; ?>
        </div>

        <div class="related-card">
            <h3 class="section-title">Certificates</h3>

            <?php if (!empty($user->certificates)): ?>
                <div class="related-list">
                    <?php foreach (array_slice($user->certificates, 0, 5) as $certificate): ?>
                        <div class="related-item">
                            <strong><?= h($certificate->certificate_no ?? 'Certificate') ?></strong>
                            <div class="related-meta">
                                Issue Date: <?= h($certificate->issue_date ?? '-') ?><br>
                                Created: <?= h($certificate->created_at ?? '-') ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">No certificates generated for this user yet.</div>
            <?php endif; ?>
        </div>
    </div>

</div>