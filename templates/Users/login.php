<?php
/**
 * @var \App\View\AppView $this
 */
?>

<style>
    body {
        margin: 0;
        padding: 0;
        background: #f3f4f6;
        font-family: Arial, sans-serif;
    }

    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 30px;
    }

    .auth-card {
        width: 100%;
        max-width: 1000px;
        min-height: 600px;
        background: #ffffff;
        border-radius: 25px;
        overflow: hidden;
        display: flex;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    }

    .auth-left {
    width: 45%;
    background: linear-gradient(180deg, #7b83eb, #8b93f7);
    color: #ffffff;
    padding: 40px 35px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
}

    .auth-logo {
    width: 400px;
    height: auto;
    display: block;
    object-fit: contain;
    margin: 0 auto 30px auto;
    background: transparent;
    padding: 0;
    border-radius: 0;
}

    .auth-left h2 {
        font-size: 38px;
        line-height: 1.25;
        margin: 0 0 15px 0;
        font-weight: bold;
    }

    .auth-left p {
        font-size: 16px;
        line-height: 1.6;
        margin: 0;
        opacity: 0.95;
    }

    .auth-right {
        width: 55%;
        padding: 55px 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: #ffffff;
    }

    .auth-right h3 {
        margin: 0 0 25px 0;
        font-size: 30px;
        color: #222;
        font-weight: bold;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        color: #666;
        font-weight: 600;
    }

    .form-group input {
        width: 100%;
        padding: 14px 16px;
        border: 1px solid #ddd;
        border-radius: 12px;
        font-size: 15px;
        background: #fafafa;
        box-sizing: border-box;
    }

    .form-group input:focus {
        outline: none;
        border-color: #7b83eb;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(123,131,235,0.15);
    }

    .auth-button {
        width: 100%;
        background: #5b5ef5;
        color: #fff;
        border: none;
        border-radius: 30px;
        padding: 14px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 10px;
        transition: 0.3s;
    }

    .auth-button:hover {
        background: #474be8;
    }

    .auth-footer {
        margin-top: 22px;
        text-align: center;
        font-size: 14px;
        color: #666;
    }

    .auth-footer a {
        color: #5b5ef5;
        text-decoration: none;
        font-weight: 600;
    }

    .auth-footer a:hover {
        text-decoration: underline;
    }

    @media (max-width: 900px) {
        .auth-card {
            flex-direction: column;
            max-width: 600px;
        }

        .auth-left,
        .auth-right {
            width: 100%;
        }

        .auth-left {
            text-align: center;
            align-items: center;
            padding: 35px 25px;
        }

        .auth-right {
            padding: 35px 25px;
        }

        .auth-left h2 {
            font-size: 30px;
        }
    }
</style>

<div class="auth-wrapper">
    <div class="auth-card">

        <div class="auth-left">
<img src="/img/uitemu-logo.png?v=1" alt="UiTEMU Logo" class="auth-logo">
            <h2>Welcome Back to UiTEMU.</h2>
            <p>
                Sign in to manage your lost reports, found reports, claims,
                and certificates in one place.
            </p>
        </div>

        <div class="auth-right">
            <h3>Sign In</h3>

            <?= $this->Form->create() ?>

            <div class="form-group">
                <?= $this->Form->control('email', [
                    'label' => 'Email Address',
                    'required' => true
                ]) ?>
            </div>

            <div class="form-group">
                <?= $this->Form->control('password', [
                    'label' => 'Password',
                    'required' => true
                ]) ?>
            </div>

            <button type="submit" class="auth-button">Login</button>

            <?= $this->Form->end() ?>

            <div class="auth-footer">
                Don’t have an account?
                <?= $this->Html->link('Register here', ['controller' => 'Users', 'action' => 'register']) ?>
            </div>
        </div>

    </div>
</div>