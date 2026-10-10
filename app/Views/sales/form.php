<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php

$products  = $products ?? [];
$customers = $customers ?? [];

?>

<div class="page-heading">
    <div>
        <h1>Record Sale</h1>

        <p>
            Select a product, an optional customer,
            and the quantity sold.
        </p>
    </div>

    <a
        class="button"
        href="<?= site_url('sales') ?>"
    >
        Sales History
    </a>
</div>

<?php if (empty($products)): ?>
    <div class="alert alert-error">
        No active products with available stock were found.
        Add or restock a product before recording a sale.
    </div>
<?php endif; ?>

<form
    class="card management-form"
    method="post"
    action="<?= site_url('sales') ?>"
>
    <?= csrf_field() ?>

    <div class="form-group">
        <label for="product_id">
            Product
        </label>

        <select
            id="product_id"
            name="product_id"
            required
            <?= empty($products) ? 'disabled' : '' ?>
        >
            <option value="">
                Select a product
            </option>

            <?php foreach ($products as $product): ?>
                <option
                    value="<?= esc((string) $product['id']) ?>"
                    data-price="<?= esc(
                        (string) $product['price']
                    ) ?>"
                    <?=
                        (string) old('product_id')
                        === (string) $product['id']
                            ? 'selected'
                            : ''
                    ?>
                >
                    <?= esc($product['name']) ?>
                    —
                    ₱<?= number_format(
                        (float) $product['price'],
                        2
                    ) ?>
                    (<?= esc(
                        (string) $product['stock_quantity']
                    ) ?> available)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="customer_id">
            Customer
        </label>

        <select
            id="customer_id"
            name="customer_id"
        >
            <option value="">
                Walk-in Customer
            </option>

            <?php foreach ($customers as $customer): ?>
                <option
                    value="<?= esc((string) $customer['id']) ?>"
                    <?=
                        (string) old('customer_id')
                        === (string) $customer['id']
                            ? 'selected'
                            : ''
                    ?>
                >
                    <?= esc($customer['full_name']) ?>
                    — <?= esc($customer['email']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <small>
            Selecting a customer is optional.
        </small>
    </div>

    <div class="form-group">
        <label for="quantity">
            Quantity
        </label>

        <input
            id="quantity"
            type="number"
            name="quantity"
            min="1"
            step="1"
            value="<?= esc((string) (old('quantity') ?? 1)) ?>"
            required
        >
    </div>

    <div class="sale-summary">
        <span>Estimated total</span>
        <strong id="estimated-total">₱0.00</strong>

        <small>
            The final total is calculated securely
            by the server.
        </small>
    </div>

    <div class="form-actions">
        <button
            class="button button-primary"
            type="submit"
            <?= empty($products) ? 'disabled' : '' ?>
        >
            Complete Sale
        </button>

        <a
            class="button"
            href="<?= site_url('dashboard') ?>"
        >
            Cancel
        </a>
    </div>
</form>

<script>
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const totalOutput = document.getElementById('estimated-total');

    function updateEstimatedTotal() {
        const selectedOption =
            productSelect.options[productSelect.selectedIndex];

        const price =
            Number(selectedOption?.dataset?.price || 0);

        const quantity =
            Math.max(Number(quantityInput.value || 0), 0);

        const total = price * quantity;

        totalOutput.textContent = new Intl.NumberFormat(
            'en-PH',
            {
                style: 'currency',
                currency: 'PHP'
            }
        ).format(total);
    }

    productSelect.addEventListener(
        'change',
        updateEstimatedTotal
    );

    quantityInput.addEventListener(
        'input',
        updateEstimatedTotal
    );

    updateEstimatedTotal();
</script>

<?= $this->endSection() ?>