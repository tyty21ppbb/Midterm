<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= esc($title ?? 'Staff Login') ?> | Complete POS
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 20px;
            font-family: Arial, Helvetica, sans-serif;
            color: #172033;
            background:
                linear-gradient(
                    135deg,
                    #172554,
                    #2563eb
                );
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 32px;
            border-radius: 16px;
            background: #ffffff;
            box-shadow:
                0 24px 60px
                rgba(2, 6, 23, 0.35);
        }

        .login-card h1 {
            margin: 0 0 8px;
            color: #172554;
            text-align: center;
        }

        .subtitle {
            margin: 0 0 24px;
            color: #64748b;
            text-align: center;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: 700;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 16px;
        }

        input:focus {
            border-color: #2563eb;
            outline: 2px solid rgba(37, 99, 235, 0.15);
        }

        button {
            width: 100%;
            padding: 12px;
            border: 0;
            border-radius: 7px;
            color: #ffffff;
            background: #2563eb;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .alert {
            margin-bottom: 18px;
            padding: 12px;
            border-radius: 7px;
        }

        .alert-success {
            color: #166534;
            background: #dcfce7;
        }

        .alert-error {
            color: #991b1b;
            background: #fee2e2;
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
        }

        .demo-account {
            margin-top: 20px;
            padding: 12px;
            border-radius: 7px;
            color: #475569;
            background: #f1f5f9;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>

<body>
    <main class="login-card">
        <h1>Complete POS</h1>

        <p class="subtitle">
            Sign in using your staff account
        </p>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php
        $errors = session()->getFlashdata('errors');
        ?>

        <?php if (! empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form
            method="post"
            action="<?= site_url('login') ?>"
        >
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="username">
                    Username
                </label>

                <input
                    id="username"
                    type="text"
                    name="username"
                    maxlength="50"
                    value="<?= esc(old('username') ?? '') ?>"
                    autocomplete="username"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">
                    Password
                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit">
                Log In
            </button>
        </form>

        <div class="demo-account">
            <strong>Development account</strong><br>
            Username: admin<br>
            Password: Admin123!
        </div>
    </main>
</body>
</html>