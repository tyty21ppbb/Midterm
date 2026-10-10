<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php $sales = $sales ?? []; ?>

<div class="page-heading">
    <div>
        <h1>Sales History</h1>

        <p>
            Review all completed point-of-sale transactions.
        </p>
    </div>

    <a
        class="button button-primary"
        href="<?= site_url('sales/create') ?>"
    >
        + Record Sale
    </a>
</div>

<section class="card">
    <?php if (empty($sales)): ?>
        <div class="empty-state">
            <div class="empty-icon">₱</div>

            <h4>No sales recorded yet</h4>

            <p>
                Completed transactions will appear here.
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
                        <th>Sale ID</th>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Staff</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($sales as $sale): ?>
                        <tr>
                            <td>
                                #<?= esc((string) $sale['id']) ?>
                            </td>

                            <td>
                                <?= esc($sale['product_name']) ?>
                            </td>

                            <td>
                                <?= esc(
                                    $sale['customer_name']
                                    ?? 'Walk-in Customer'
                                ) ?>
                            </td>

                            <td>
                                <?= esc($sale['staff_name']) ?>
                            </td>

                            <td>
                                <?= esc(
                                    (string) $sale['quantity']
                                ) ?>
                            </td>

                            <td>
                                <strong>
                                    ₱<?= number_format(
                                        (float) $sale['total_price'],
                                        2
                                    ) ?>
                                </strong>
                            </td>

                            <td>
                                <?= esc(
                                    date(
                                        'M j, Y g:i A',
                                        strtotime(
                                            $sale['created_at']
                                        )
                                    )
                                ) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?= $this->endSection() ?>