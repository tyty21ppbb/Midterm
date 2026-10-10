<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table            = 'sales';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'product_id',
        'customer_id',
        'sold_by',
        'quantity',
        'total_price',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function getSalesHistory(): array
    {
        return $this
            ->select(
                'sales.id,
                sales.product_id,
                sales.customer_id,
                sales.sold_by,
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
            ->findAll();
    }
}