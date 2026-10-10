<?php

$currentPath = trim(service('uri')->getPath(), '/');

function navigationActive(
    string $currentPath,
    string $section
): string {
    return (
        $currentPath === $section
        || str_starts_with($currentPath, $section . '/')
    ) ? 'active' : '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= esc($title ?? 'Complete POS') ?> | Complete POS
    </title>

    <link
        rel="stylesheet"
        href="<?= base_url('assets/app.css') ?>"
    >
</head>

<body>
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">POS</div>

            <div>
                <strong>Complete POS</strong>
                <small>Management System</small>
            </div>
        </div>

        <nav class="sidebar-navigation">
            <a
                class="<?= navigationActive(
                    $currentPath,
                    'dashboard'
                ) ?>"
                href="<?= site_url('dashboard') ?>"
            >
                <span class="nav-icon">⌂</span>
                Dashboard
            </a>

            <a
                class="<?= navigationActive(
                    $currentPath,
                    'products'
                ) ?>"
                href="<?= site_url('products') ?>"
            >
                <span class="nav-icon">□</span>
                Products
            </a>

            <a
                class="<?= navigationActive(
                    $currentPath,
                    'customers'
                ) ?>"
                href="<?= site_url('customers') ?>"
            >
                <span class="nav-icon">♙</span>
                Customers
            </a>

            <a
                class="<?= navigationActive(
                    $currentPath,
                    'users'
                ) ?>"
                href="<?= site_url('users') ?>"
            >
                <span class="nav-icon">♚</span>
                Staff
            </a>

            <a
                class="<?= navigationActive(
                    $currentPath,
                    'sales/create'
                ) ?>"
                href="<?= site_url('sales/create') ?>"
            >
                <span class="nav-icon">＋</span>
                Record Sale
            </a>

            <a
                class="<?=
                    $currentPath === 'sales'
                        ? 'active'
                        : ''
                ?>"
                href="<?= site_url('sales') ?>"
            >
                <span class="nav-icon">≡</span>
                Sales History
            </a>
        </nav>

        <div class="sidebar-user">
            <div class="user-avatar">
                <?=
                    esc(
                        strtoupper(
                            substr(
                                (string) session()->get('full_name'),
                                0,
                                1
                            )
                        )
                    )
                ?>
            </div>

            <div class="user-information">
                <strong>
                    <?= esc(
                        (string) session()->get('full_name')
                    ) ?>
                </strong>

                <small>
                    <?= esc(
                        (string) session()->get('username')
                    ) ?>
                </small>
            </div>

            <form
                method="post"
                action="<?= site_url('logout') ?>"
            >
                <?= csrf_field() ?>

                <button
                    class="logout-button"
                    type="submit"
                    title="Log out"
                >
                    ↪
                </button>
            </form>
        </div>
    </aside>

    <div class="application">
        <header class="topbar">
            <div>
                <h2><?= esc($title ?? 'Complete POS') ?></h2>

                <p>
                    <?= esc(date('l, F j, Y')) ?>
                </p>
            </div>

            <a
                class="button button-primary"
                href="<?= site_url('sales/create') ?>"
            >
                + New Sale
            </a>
        </header>

        <main class="content">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= esc(
                        session()->getFlashdata('success')
                    ) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error">
                    <?= esc(
                        session()->getFlashdata('error')
                    ) ?>
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

            <?= $this->renderSection('content') ?>
        </main>
    </div>
</body>
</html>