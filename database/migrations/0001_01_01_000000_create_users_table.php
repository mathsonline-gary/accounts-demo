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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('brand_id');
            $table->unsignedTinyInteger('role_id')
                ->comment('1: Admin, 2: Student, 3: Teacher, 4: Customer');
            $table->string('google_id')->nullable();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 255)->nullable()->unique();
            $table->string('username', 50)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->ipAddress('ip_address')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // Indexes
            $table->index('brand_id');
            $table->index('role_id');
            $table->index('email');
            $table->index('username');
            $table->index('first_name');
            $table->index('last_name');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
    }
};
