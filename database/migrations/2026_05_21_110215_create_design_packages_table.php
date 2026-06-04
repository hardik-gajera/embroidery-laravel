<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('design_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('number_of_design');
            $table->integer('time_period');
            $table->decimal('price', 10, 2);
            $table->enum('state', ['draft', 'confirm', 'finish'])->default('draft');
            $table->string('package_img')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('design_packages');
    }
};
