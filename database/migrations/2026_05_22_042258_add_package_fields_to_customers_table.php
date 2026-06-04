<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('package_id')->nullable()->constrained('design_packages')->nullOnDelete();
            $table->date('package_start_date')->nullable();
            $table->date('package_end_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropColumn(['package_id', 'package_start_date', 'package_end_date']);
        });
    }
};
