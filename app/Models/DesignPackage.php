<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignPackage extends Model
{
    protected $fillable = ['name', 'number_of_design', 'time_period', 'price', 'state', 'package_img'];

    const STATES = [
        'draft' => 'Draft',
        'confirm' => 'Confirmed',
        'finish' => 'Finished',
    ];

    public function getStateLabelAttribute()
    {
        return self::STATES[$this->state] ?? $this->state;
    }
}
