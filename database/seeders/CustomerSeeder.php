<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Rahul Sharma', 'email' => 'rahul@example.com', 'mobile_no' => '9876543210', 'downloaded_design' => 15, 'total_design' => 25, 'password' => 'password'],
            ['name' => 'Priya Patel', 'email' => 'priya@example.com', 'mobile_no' => '9876543211', 'downloaded_design' => 22, 'total_design' => 30, 'password' => 'password'],
            ['name' => 'Amit Kumar', 'email' => 'amit@example.com', 'mobile_no' => '9876543212', 'downloaded_design' => 8, 'total_design' => 18, 'password' => 'password'],
            ['name' => 'Sneha Reddy', 'email' => 'sneha@example.com', 'mobile_no' => '9876543213', 'downloaded_design' => 30, 'total_design' => 45, 'password' => 'password'],
            ['name' => 'Vikram Singh', 'email' => 'vikram@example.com', 'mobile_no' => '9876543214', 'downloaded_design' => 12, 'total_design' => 20, 'password' => 'password'],
            ['name' => 'Anita Desai', 'email' => 'anita@example.com', 'mobile_no' => '9876543215', 'downloaded_design' => 5, 'total_design' => 10, 'password' => 'password'],
            ['name' => 'Karan Mehta', 'email' => 'karan@example.com', 'mobile_no' => '9876543216', 'downloaded_design' => 18, 'total_design' => 35, 'password' => 'password'],
            ['name' => 'Deepa Nair', 'email' => 'deepa@example.com', 'mobile_no' => '9876543217', 'downloaded_design' => 25, 'total_design' => 40, 'password' => 'password'],
            ['name' => 'Suresh Gupta', 'email' => 'suresh@example.com', 'mobile_no' => '9876543218', 'downloaded_design' => 10, 'total_design' => 15, 'password' => 'password'],
            ['name' => 'Meera Joshi', 'email' => 'meera@example.com', 'mobile_no' => '9876543219', 'downloaded_design' => 20, 'total_design' => 28, 'password' => 'password'],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
