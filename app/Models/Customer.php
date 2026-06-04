<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = ['name', 'email', 'mobile_no', 'downloaded_design', 'total_design', 'password', 'package_id', 'package_start_date', 'package_end_date'];

    protected $casts = [
        'package_start_date' => 'date',
        'package_end_date' => 'date',
    ];

    public function package()
    {
        return $this->belongsTo(DesignPackage::class, 'package_id');
    }

    public function packagePurchases()
    {
        return $this->hasMany(CustomerPackagePurchase::class);
    }

    public function activePackagePurchase()
    {
        return $this->hasOne(CustomerPackagePurchase::class)
            ->where('end_date', '>=', now())
            ->where('downloaded', '<', \DB::raw('total'))
            ->latest('start_date');
    }

    protected $hidden = ['password'];

    public function hasActivePackage()
    {
        return $this->package_id && $this->package_end_date && $this->package_end_date->isFuture();
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
