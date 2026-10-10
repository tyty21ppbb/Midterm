<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        $database = db_connect();

        $productCount = $database
            ->table('products')
            ->where('is_active', 1)
            ->countAllResults();

        $customerCount = $database
            ->table('customers')
            ->where('is_active', 1)
            ->countAllResults();

        $staffCount = $database
            ->table('users')
            ->where('is_active', 1)
            ->countAllResults();

        $saleCount = $database
            ->table('sales')
            ->countAllResults();

        $revenueRow = $database
            ->table('sales')
            ->selectSum('total_price', 'total_revenue')
            ->get()
            ->getRowArray();

        $totalRevenue = (float) (
            $revenueRow['total_revenue'] ?? 0
        );

        $lowStock = $database
            ->table('products')
            ->select(
                'id,
                name,
                price,
                stock_quantity'
            )
            ->where('is_active', 1)
            ->where('stock_quantity <=', 5)
            ->orderBy('stock_quantity', 'ASC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $recentSales = $database
            ->table('sales')
            ->select(
                'sales.id,
                sales.quantity,
                sales.total_price,
                sales.created_at,
                products.name AS product_name,
                customers.full_name AS customer_name,
                users.full_name AS staff_name'
            )
            ->join(
                'products',
                'products.id = sales.product_id'
            )
            ->join(
                'customers',
                'customers.id = sales.customer_id',
                'left'
            )
            ->join(
                'users',
                'users.id = sales.sold_by'
            )
            ->orderBy('sales.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return view('dashboard/index', [
            'title'         => 'Dashboard',
            'productCount'  => $productCount,
            'customerCount' => $customerCount,
            'staffCount'    => $staffCount,
            'saleCount'     => $saleCount,
            'totalRevenue'  => $totalRevenue,
            'lowStock'      => $lowStock,
            'recentSales'   => $recentSales,
        ]);
    }
}