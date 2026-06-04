<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('designs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('design_code')->nullable();
            $table->string('emb_file');
            $table->string('file_name')->nullable();
            $table->integer('stitches')->default(0);
            $table->string('height')->nullable();
            $table->string('width')->nullable();
            $table->string('area')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('needle_color')->nullable();
            $table->string('design_format')->default('emb');
            $table->string('design_img')->nullable();
            $table->decimal('design_price', 10, 2)->default(225.00);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('designs');
    }
};
