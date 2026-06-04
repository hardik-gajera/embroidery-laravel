<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPackagePurchase extends Model
{
    protected $fillable = ['customer_id', 'package_id', 'total', 'downloaded', 'start_date', 'end_date'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function package()
    {
        return $this->belongsTo(DesignPackage::class, 'package_id');
    }

    public function isActive()
    {
        return $this->end_date && $this->end_date->isFuture() && $this->downloaded < $this->total;
    }
}
