<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['customer_id', 'design_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function design()
    {
        return $this->belongsTo(Design::class);
    }
}
