<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */

$currentUser = $this->request->getAttribute('identity');
$isOwnAccount = $currentUser && ((int)$currentUser->id === (int)$user->id);
?>

<style>
    .user-edit-page {
        padding: 10px 0 30px;
    }

    .user-edit-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 24px;
    }

    .user-edit-title h1 {
        font-size: 56px;
        line-height: 1.05;
        margin: 0 0 8px;
        color: #0f172a;
        font-weight: 800;
        letter-spacing: -1px;
    }

    .user-edit-title p {
        margin: 0;
        font-size: 24px;
        color: #64748b;
        font-weight: 500;
    }

    .user-edit-actions {
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
        font-size: 18px;
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

    .btn-red {
        background: #ef4444;
        color: #fff;
    }

    .user-edit-layout {
        display: grid;
        grid-template-columns: 0.9fr 1.6fr;
        gap: 24px;
    }

    .profile-card,
    .form-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        padding: 28px;
    }

    .profile-top {
        display: flex;
        align-items: center;
        gap: 18px;
        margin-bottom: 24px;
    }

    .avatar-circle {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563eb, #60a5fa);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
        font-weight: 800;
        flex-shrink: 0;
        text-transform: uppercase;
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.25);
    }

    .profile-top h2 {
        margin: 0 0 8px;
        font-size: 34px;
        line-height: 1.1;
        color: #0f172a;
        font-weight: 800;
        word-break: break-word;
    }

    .profile-top p {
        margin: 0;
        color: #64748b;
        font-size: 18px;
        word-break: break-word;
    }

    .role-badge {
        display: inline-block;
        margin-top: 10px;
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 14px;
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

    .mini-section-title {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 18px;
    }

    .info-box {
        display: grid;
        gap: 14px;
    }

    .info-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px 18px;
    }

    .info-label {
        display: block;
        font-size: 14px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 6px;
    }

    .info-value {
        display: block;
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        word-break: break-word;
    }

    .form-card h3 {
        font-size: 34px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 8px;
    }

    .form-card .form-subtitle {
        margin: 0 0 24px;
        color: #64748b;
        font-size: 18px;
    }

    .user-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 20px;
    }

    .user-form-grid .full-width {
        grid-column: 1 / -1;
    }

    .user-edit-page label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
        color: #334155;
        font-size: 16px;
    }

    .user-edit-page input,
    .user-edit-page select,
    .user-edit-page textarea {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 14px;
        padding: 14px 16px;
        font-size: 16px;
        color: #0f172a;
        background: #fff;
        box-sizing: border-box;
        transition: all 0.2s ease;
        box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.02);
    }

    .user-edit-page input:focus,
    .user-edit-page select:focus,
    .user-edit-page textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
    }

    .field-note {
        margin-top: 8px;
        font-size: 14px;
        color: #64748b;
    }

    .submit-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 26px;
    }

    .submit-btn {
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 14px;
        padding: 14px 24px;
        font-size: 17px;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.22);
        transition: all 0.2s ease;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        opacity: 0.95;
    }

    .cancel-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #e2e8f0;
        color: #0f172a;
        text-decoration: none;
        border-radius: 14px;
        padding: 14px 22px;
        font-size: 17px;
        font-weight: 800;
    }

    .help-card {
        margin-top: 20px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 18px;
        padding: 18px 20px;
    }

    .help-card strong {
        display: block;
        color: #1d4ed8;
        font-size: 17px;
        margin-bottom: 8px;
    }

    .help-card p {
        margin: 0;
        color: #334155;
        font-size: 15px;
        line-height: 1.6;
    }

    @media (max-width: 1100px) {
        .user-edit-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .user-edit-title h1 {
            font-size: 38px;
        }

        .user-edit-title p {
            font-size: 18px;
        }

        .user-form-grid {
            grid-template-columns: 1fr;
        }

        .user-edit-actions {
            width: 100%;
            flex-direction: column;
        }

        .action-btn {
            width: 100%;
        }

        .profile-top {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="user-edit-page">

    <div class="user-edit-header">
        <div class="user-edit-title">
            <h1>Edit User</h1>
            <p>Update user account information in the UiTEMU system.</p>
        </div>

        <div class="user-edit-actions">
            <?= $this->Html->link('← Back to Users', ['action' => 'index'], ['class' => 'action-btn btn-dark']) ?>
            <?= $this->Html->link('View User', ['action' => 'view', $user->id], ['class' => 'action-btn btn-blue']) ?>

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
        </div>
    </div>

    <div class="user-edit-layout">

        <div>
            <div class="profile-card">
                <div class="profile-top">
                    <div class="avatar-circle">
                        <?= h(strtoupper(substr((string)$user->name, 0, 1))) ?>
                    </div>

                    <div>
                        <h2><?= h($user->name) ?></h2>
                        <p><?= h($user->email) ?></p>

                        <span class="role-badge <?= $user->role === 'admin' ? 'role-admin' : 'role-user' ?>">
                            <?= h($user->role) ?>
                        </span>
                    </div>
                </div>

                <h3 class="mini-section-title">User Information</h3>

                <div class="info-box">
                    <div class="info-item">
                        <span class="info-label">User ID</span>
                        <span class="info-value"><?= h($user->id) ?></span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Created At</span>
                        <span class="info-value" style="font-size: 17px;">
                            <?= !empty($user->created_at) ? h($user->created_at) : '-' ?>
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Updated At</span>
                        <span class="info-value" style="font-size: 17px;">
                            <?= !empty($user->updated_at) ? h($user->updated_at) : '-' ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="help-card">
                <strong>Editing Tip</strong>
                <p>
                    Leave the password field empty if you do not want to change the user's password.
                    Only update it when a new password is needed.
                </p>
            </div>
        </div>

        <div class="form-card">
            <h3>User Details</h3>
            <p class="form-subtitle">Edit the account details below and save your changes.</p>

            <?= $this->Form->create($user) ?>

            <div class="user-form-grid">
                <div>
                    <?=
                        $this->Form->control('name', [
                            'label' => 'Full Name',
                            'placeholder' => 'Enter full name',
                            'templates' => ['inputContainer' => '{{content}}']
                        ])
                    ?>
                </div>

                <div>
                    <?=
                        $this->Form->control('email', [
                            'label' => 'Email Address',
                            'placeholder' => 'Enter email address',
                            'templates' => ['inputContainer' => '{{content}}']
                        ])
                    ?>
                </div>

                <div class="full-width">
                    <?=
                        $this->Form->control('password', [
                            'label' => 'New Password',
                            'type' => 'password',
                            'value' => '',
                            'required' => false,
                            'placeholder' => 'Leave blank if no change',
                            'templates' => ['inputContainer' => '{{content}}']
                        ])
                    ?>
                    <div class="field-note">For security, the current password is not shown.</div>
                </div>

                <div>
                    <?=
                        $this->Form->control('role', [
                            'label' => 'User Role',
                            'type' => 'select',
                            'options' => [
                                'user' => 'User',
                                'admin' => 'Admin'
                            ],
                            'templates' => ['inputContainer' => '{{content}}']
                        ])
                    ?>
                </div>
            </div>

            <div class="submit-row">
                <?= $this->Form->button('Save Changes', ['class' => 'submit-btn']) ?>
                <?= $this->Html->link('Cancel', ['action' => 'view', $user->id], ['class' => 'cancel-btn']) ?>
            </div>

            <?= $this->Form->end() ?>
        </div>

    </div>
</div>