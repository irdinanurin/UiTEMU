<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
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
$userCount = is_countable($users) ? count($users) : 0;
?>

<style>
    .users-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .users-page-header h1 {
        margin: 0;
        font-size: 42px;
        font-weight: 800;
        color: #0f172a;
    }

    .users-page-header p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 15px;
    }

    .users-header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .users-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 16px;
        border-radius: 13px;
        text-decoration: none;
        font-weight: 800;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .users-btn:hover {
        transform: translateY(-1px);
        color: inherit;
    }

    .users-btn-primary {
        background: #2563eb;
        color: #ffffff;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.22);
    }

    .users-btn-dark {
        background: #111827;
        color: #ffffff;
    }

    .users-summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }

    .users-summary-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 22px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        border: 1px solid #eef2f7;
    }

    .users-summary-card span {
        display: block;
        color: #64748b;
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .users-summary-card strong {
        display: block;
        color: #0f172a;
        font-size: 34px;
        font-weight: 800;
    }

    .users-toolbar {
        background: #ffffff;
        border-radius: 20px;
        padding: 18px;
        margin-bottom: 22px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.07);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .users-search {
        width: 100%;
        max-width: 380px;
        padding: 13px 15px;
        border-radius: 13px;
        border: 1px solid #cbd5e1;
        font-size: 15px;
        outline: none;
    }

    .users-search:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .users-table-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 12px 35px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }

    .users-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 950px;
    }

    .users-table thead th {
        background: #f8fafc;
        color: #334155;
        font-size: 14px;
        font-weight: 800;
        padding: 18px 16px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .users-table tbody td {
        padding: 18px 16px;
        border-bottom: 1px solid #eef2f7;
        vertical-align: middle;
        color: #0f172a;
        font-size: 14px;
    }

    .users-table tbody tr:hover {
        background: #f8fbff;
    }

    .users-no-badge {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #2563eb;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        box-shadow: 0 6px 14px rgba(37, 99, 235, 0.25);
    }

    .users-name {
        font-weight: 800;
        color: #0f172a;
    }

    .users-email {
        color: #475569;
        font-weight: 600;
    }

    .role-badge {
        display: inline-block;
        padding: 7px 13px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
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

    .role-other {
        background: #e0e7ff;
        color: #3730a3;
    }

    .current-user-badge {
        display: inline-block;
        margin-left: 8px;
        padding: 5px 10px;
        border-radius: 999px;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 800;
    }

    .users-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    /* ensure action buttons in table cells align to the right */
    .users-table tbody td > .users-actions {
        display: flex;
        justify-content: flex-end;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 9px 12px;
        border-radius: 10px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 13px;
        font-weight: 800;
        transition: 0.2s ease;
    }

    .action-btn:hover {
        transform: translateY(-1px);
        color: inherit;
    }

    .btn-view {
        background: #2563eb;
        color: #ffffff;
    }

    .btn-edit {
        background: #f59e0b;
        color: #ffffff;
    }

    .btn-delete {
        background: #ef4444;
        color: #ffffff;
    }

    .users-empty {
        padding: 60px 24px;
        text-align: center;
        color: #64748b;
    }

    .users-empty h3 {
        margin: 0 0 8px;
        font-size: 26px;
        color: #0f172a;
    }

    @media (max-width: 800px) {
        .users-page-header h1 {
            font-size: 34px;
        }

        .users-toolbar {
            align-items: stretch;
        }

        .users-search {
            max-width: none;
        }
    }
</style>

<div class="users-page-header">
    <div>
        <h1>Users Management</h1>
        <p>View and manage registered users in the UiTEMU Lost & Found System.</p>
    </div>

    <div class="users-header-actions">
        <?= $this->Html->link(
            '← Back to Dashboard',
            ['controller' => 'Dashboard', 'action' => 'index'],
            ['class' => 'users-btn users-btn-dark']
        ) ?>

        <?php if ($isAdmin): ?>
            <?= $this->Html->link(
                '+ New User',
                ['action' => 'add'],
                ['class' => 'users-btn users-btn-primary']
            ) ?>
        <?php endif; ?>
    </div>
</div>

<div class="users-summary-grid">
    <div class="users-summary-card">
        <span>Total Users Displayed</span>
        <strong><?= h($userCount) ?></strong>
    </div>

    <div class="users-summary-card">
        <span>Current Section</span>
        <strong>Users</strong>
    </div>
</div>

<div class="users-toolbar">
    <span style="color:#64748b;font-weight:700;">
        <?= h($userCount) ?> user record(s)
    </span>
</div>

<div class="users-table-card">
    <?php if (!empty($users) && count($users) > 0): ?>
        <div class="users-table-wrap">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($users as $user): ?>
                        <?php
                            $role = strtolower((string)$user->role);

                            if ($role === 'admin') {
                                $roleClass = 'role-admin';
                            } elseif ($role === 'user') {
                                $roleClass = 'role-user';
                            } else {
                                $roleClass = 'role-other';
                            }

                            $isCurrentUser = (int)$user->id === (int)$currentUserId;

                            $searchText = strtolower(
                                ($user->name ?? '') . ' ' .
                                ($user->email ?? '') . ' ' .
                                ($user->role ?? '')
                            );
                        ?>

                        <tr class="user-row" data-search="<?= h($searchText) ?>">
                            <td>
                                <span class="users-no-badge"><?= $no++ ?></span>
                            </td>

                            <td>
                                <span class="users-name">
                                    <?= h($user->name) ?>
                                </span>

                                <?php if ($isCurrentUser): ?>
                                    <span class="current-user-badge">You</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <span class="users-email"><?= h($user->email) ?></span>
                            </td>

                            <td>
                                <span class="role-badge <?= $roleClass ?>">
                                    <?= h($user->role) ?>
                                </span>
                            </td>

                            <td>
                                <?= h($user->created_at) ?>
                            </td>

                            <td>
                                <?= h($user->updated_at) ?>
                            </td>

                            <td>
                                <div class="users-actions">
                                    <?= $this->Html->link(
                                        'View',
                                        ['action' => 'view', $user->id],
                                        ['class' => 'action-btn btn-view']
                                    ) ?>

                                    <?php if ($isAdmin): ?>
                                        <?= $this->Html->link(
                                            'Edit',
                                            ['action' => 'edit', $user->id],
                                            ['class' => 'action-btn btn-edit']
                                        ) ?>

                                        <?php if (!$isCurrentUser): ?>
                                            <?= $this->Form->postLink(
                                                'Delete',
                                                ['action' => 'delete', $user->id],
                                                [
                                                    'confirm' => __('Are you sure you want to delete user # {0}?', $user->id),
                                                    'class' => 'action-btn btn-delete'
                                                ]
                                            ) ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="users-empty">
            <h3>No Users Found</h3>
            <p>There are no registered users yet.</p>
        </div>
    <?php endif; ?>
</div>

<!-- user search removed; filtering is available only on Items page -->