<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id');
            $table->string('stripe_price_id', 255)
                ->nullable();
            $table->string('code', 50)
                ->nullable();
            $table->string('description', 100)
                ->nullable();
            $table->decimal('price_original', 10)
                ->nullable()
                ->comment('The original price');
            $table->decimal('price', 10)
                ->nullable()
                ->comment('The final price');
            $table->decimal('price_saved', 10)
                ->nullable();
            $table->boolean('is_recurring');
            $table->tinyInteger('period_in_months')
                ->nullable()
                ->comment('The total membership duration in months (including the extra months)');
            $table->unsignedTinyInteger('extra_months')
                ->default(0)
                ->comment('The additional months compared to the regular plan (e.t. 12 months)');
            $table->integer('student_limit')
                ->default(1);
            $table->tinyInteger('type')
                ->comment('1: for standard customers; 2: for homeschoolers; 3: for custom orders; 4: deprecated plans');
            $table->string('currency', 3)
                ->default('usd');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
