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
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // Unique identifier for the role
            $table->string('name')->unique(); // System name of the role (e.g., 'admin', 'customer')
            $table->string('display_name')->unique(); // Display name for the role in Spanish
            $table->timestamps(); // created_at and updated_at
            $table->softDeletes(); // Soft delete field
            $table->userTracking(); // created_by and updated_by
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
