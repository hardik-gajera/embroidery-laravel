<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;

class FixCustomerMobileNumbers extends Command
{
    protected $signature = 'customers:fix-mobile';
    protected $description = 'Add +91 prefix to customer mobile numbers that are missing it';

    public function handle()
    {
        $customers = Customer::where('mobile_no', 'not like', '+91%')
            ->orWhere('mobile_no', 'REGEXP', '[^0-9+]')
            ->get();

        $count = 0;
        foreach ($customers as $customer) {
            $mobile = preg_replace('/[^0-9+]/', '', $customer->mobile_no);

            if (str_starts_with($mobile, '+91')) {
                $customer->mobile_no = $mobile;
            } elseif (strlen($mobile) == 12 && str_starts_with($mobile, '91')) {
                $customer->mobile_no = '+' . $mobile;
            } else {
                $customer->mobile_no = '+91' . $mobile;
            }

            $customer->save();
            $count++;
        }

        $this->info("Fixed {$count} customer mobile numbers.");
    }
}
