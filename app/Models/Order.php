<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['customer_id', 'design_id', 'package_id', 'amount', 'razorpay_order_id', 'razorpay_payment_id', 'status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function design()
    {
        return $this->belongsTo(Design::class);
    }

    public function package()
    {
        return $this->belongsTo(DesignPackage::class, 'package_id');
    }
}
