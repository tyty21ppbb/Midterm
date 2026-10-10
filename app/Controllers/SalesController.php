<?php

namespace App\Controllers;

use App\Models\SaleModel;
use CodeIgniter\HTTP\RedirectResponse;

class SalesController extends BaseController
{
    /**
     * Display the sales history.
     */
    public function index()
    {
        $saleModel = new SaleModel();

        return view('sales/index', [
            'title' => 'Sales History',
            'sales' => $saleModel->getSalesHistory(),
        ]);
    }

    /**
     * Display the Record Sale form.
     */
    public function create()
    {
        $database = db_connect();

        $products = $database
            ->table('products')
            ->select('id, name, price, stock_quantity')
            ->where('is_active', 1)
            ->where('stock_quantity >', 0)
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();

        $customers = $database
            ->table('customers')
            ->select('id, full_name, email')
            ->where('is_active', 1)
            ->orderBy('full_name', 'ASC')
            ->get()
            ->getResultArray();

        return view('sales/form', [
            'title'     => 'Record Sale',
            'products'  => $products,
            'customers' => $customers,
        ]);
    }

    /**
     * Validate and record a sale.
     */
    public function store(): RedirectResponse
    {
        $rules = [
            'product_id' => [
                'label' => 'Product',
                'rules' => 'required|integer',
            ],
            'customer_id' => [
                'label' => 'Customer',
                'rules' => 'permit_empty|integer',
            ],
            'quantity' => [
                'label' => 'Quantity',
                'rules' => 'required|integer|greater_than[0]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $database = db_connect();

        $productId = (int) $this->request->getPost(
            'product_id'
        );

        $quantity = (int) $this->request->getPost(
            'quantity'
        );

        $submittedCustomerId = $this->request->getPost(
            'customer_id'
        );

        $customerId = (
            $submittedCustomerId === null
            || $submittedCustomerId === ''
        ) ? null : (int) $submittedCustomerId;

        /*
         * Always retrieve the current price and stock from the database.
         * Never trust a price or total submitted by the browser.
         */
        $product = $database
            ->table('products')
            ->where('id', $productId)
            ->where('is_active', 1)
            ->get()
            ->getRowArray();

        if ($product === null) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'The selected product is unavailable.'
                );
        }

        /*
         * Validate the optional customer against the database.
         */
        if ($customerId !== null) {
            $customerExists = $database
                ->table('customers')
                ->where('id', $customerId)
                ->where('is_active', 1)
                ->countAllResults();

            if ($customerExists === 0) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'The selected customer is unavailable.'
                    );
            }
        }

        $availableStock = (int) $product['stock_quantity'];

        if ($quantity > $availableStock) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Sale rejected. Only '
                    . $availableStock
                    . ' unit(s) of “'
                    . $product['name']
                    . '” are available.'
                );
        }

        $unitPrice = (float) $product['price'];
        $totalPrice = round($unitPrice * $quantity, 2);

        /*
         * Use a transaction so the stock update and sale insertion
         * either both succeed or both fail.
         */
        $database->transBegin();

        /*
         * The stock condition also protects against two simultaneous
         * sales attempting to purchase the same remaining stock.
         */
        $database
            ->table('products')
            ->where('id', $productId)
            ->where('stock_quantity >=', $quantity)
            ->set(
                'stock_quantity',
                'stock_quantity - ' . $quantity,
                false
            )
            ->update();

        if ($database->affectedRows() !== 1) {
            $database->transRollback();

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Sale rejected because the available stock changed. '
                    . 'Please try again.'
                );
        }

        $saleInserted = $database
            ->table('sales')
            ->insert([
                'product_id'  => $productId,
                'customer_id' => $customerId,
                'sold_by'     => (int) session()->get('user_id'),
                'quantity'    => $quantity,
                'total_price' => $totalPrice,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);

        if (
            ! $saleInserted
            || $database->transStatus() === false
        ) {
            $database->transRollback();

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'The sale could not be recorded. '
                    . 'No stock was deducted.'
                );
        }

        $database->transCommit();

        return redirect()
            ->to(site_url('sales'))
            ->with(
                'success',
                'Sale recorded successfully. Total: ₱'
                . number_format($totalPrice, 2)
            );
    }
}