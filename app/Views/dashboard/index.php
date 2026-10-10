<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php

// Safe default values prevent undefined-variable errors.
$productCount  = $productCount ?? 0;
$customerCount = $customerCount ?? 0;
$staffCount    = $staffCount ?? 0;
$saleCount     = $saleCount ?? 0;

// Supports both the old and updated controller variable names.
$totalRevenue = $totalRevenue ?? $revenue ?? 0;

$lowStock    = $lowStock ?? [];
$recentSales = $recentSales ?? [];

?>

<section class="welcome-panel">
    <div>
        <span class="eyebrow">
            POINT-OF-SALE OVERVIEW
        </span>

        <h1>
            Welcome back,
            <?= esc(
                (string) session()->get('full_name')
            ) ?>!
        </h1>

        <p>
            Monitor products, customers, staff, inventory,
            and sales from one dashboard.
        </p>
    </div>

    <a
        class="button button-light"
        href="<?= site_url('sales/create') ?>"
    >
        Record a Sale
    </a>
</section>

<section class="statistics-grid">
    <article class="statistic-card">
        <div class="statistic-icon blue">
            P
        </div>

        <div>
            <span>Active Products</span>

            <strong>
                <?= esc((string) $productCount) ?>
            </strong>
        </div>
    </article>

    <article class="statistic-card">
        <div class="statistic-icon purple">
            C
        </div>

        <div>
            <span>Customers</span>

            <strong>
                <?= esc((string) $customerCount) ?>
            </strong>
        </div>
    </article>

    <article class="statistic-card">
        <div class="statistic-icon orange">
            S
        </div>

        <div>
            <span>Active Staff</span>

            <strong>
                <?= esc((string) $staffCount) ?>
            </strong>
        </div>
    </article>

    <article class="statistic-card">
        <div class="statistic-icon green">
            ₱
        </div>

        <div>
            <span>Total Revenue</span>

            <strong>
                ₱<?= number_format(
                    (float) $totalRevenue,
                    2
                ) ?>
            </strong>
        </div>
    </article>
</section>

<section class="dashboard-grid">
    <article class="card">
        <div class="card-header">
            <div>
                <h3>Recent Sales</h3>

                <p>
                    <?= esc((string) $saleCount) ?>
                    total transactions
                </p>
            </div>

            <a href="<?= site_url('sales') ?>">
                View all
            </a>
        </div>

        <?php if (empty($recentSales)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    ₱
                </div>

                <h4>No sales recorded yet</h4>

                <p>
                    Your latest transactions will appear here.
                </p>

                <a
                    class="button button-primary"
                    href="<?= site_url('sales/create') ?>"
                >
                    Record First Sale
                </a>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Staff</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($recentSales as $sale): ?>
                            <tr>
                                <td>
                                    <?= esc(
                                        $sale['product_name']
                                        ?? 'Unknown Product'
                                    ) ?>
                                </td>

                                <td>
                                    <?= esc(
                                        $sale['customer_name']
                                        ?? 'Walk-in Customer'
                                    ) ?>
                                </td>

                                <td>
                                    <?= esc(
                                        $sale['staff_name']
                                        ?? 'Unknown Staff'
                                    ) ?>
                                </td>

                                <td>
                                    <?= esc(
                                        (string) (
                                            $sale['quantity']
                                            ?? 0
                                        )
                                    ) ?>
                                </td>

                                <td>
                                    ₱<?= number_format(
                                        (float) (
                                            $sale['total_price']
                                            ?? 0
                                        ),
                                        2
                                    ) ?>
                                </td>

                                <td>
                                    <?php if (
                                        ! empty($sale['created_at'])
                                    ): ?>
                                        <?= esc(
                                            date(
                                                'M j, Y g:i A',
                                                strtotime(
                                                    $sale['created_at']
                                                )
                                            )
                                        ) ?>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </article>

    <article class="card">
        <div class="card-header">
            <div>
                <h3>Low Stock</h3>

                <p>
                    Products with five units or fewer
                </p>
            </div>

            <a href="<?= site_url('products') ?>">
                Products
            </a>
        </div>

        <?php if (empty($lowStock)): ?>
            <div class="empty-state compact">
                <div class="empty-icon success-icon">
                    ✓
                </div>

                <h4>Inventory looks good</h4>

                <p>
                    No products currently have low stock.
                </p>
            </div>
        <?php else: ?>
            <div class="stock-list">
                <?php foreach ($lowStock as $product): ?>
                    <div class="stock-item">
                        <div>
                            <strong>
                                <?= esc(
                                    $product['name']
                                    ?? 'Unnamed Product'
                                ) ?>
                            </strong>

                            <small>
                                ₱<?= number_format(
                                    (float) (
                                        $product['price']
                                        ?? 0
                                    ),
                                    2
                                ) ?>
                            </small>
                        </div>

                        <span class="stock-badge">
                            <?= esc(
                                (string) (
                                    $product['stock_quantity']
                                    ?? 0
                                )
                            ) ?>
                            left
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </article>
</section>

<section class="card">
    <div class="card-header">
        <div>
            <h3>Quick Actions</h3>

            <p>
                Frequently used management pages
            </p>
        </div>
    </div>

    <div class="quick-actions">
        <a href="<?= site_url('products/create') ?>">
            <span>+</span>

            <strong>Add Product</strong>

            <small>
                Create a new inventory item
            </small>
        </a>

        <a href="<?= site_url('customers/create') ?>">
            <span>C</span>

            <strong>Add Customer</strong>

            <small>
                Register a new customer
            </small>
        </a>

        <a href="<?= site_url('users/create') ?>">
            <span>S</span>

            <strong>Add Staff</strong>

            <small>
                Create a staff account
            </small>
        </a>

        <a href="<?= site_url('sales/create') ?>">
            <span>₱</span>

            <strong>Record Sale</strong>

            <small>
                Process a new transaction
            </small>
        </a>
    </div>
</section>

<?= $this->endSection() ?>