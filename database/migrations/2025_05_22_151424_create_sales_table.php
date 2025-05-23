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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('country');
            $table->date('event_date');
            $table->string('order_id')->unique();
            $table->string('website');
            $table->string('publisher_name');
            $table->unsignedBigInteger('publisher_id');
            $table->decimal('sale_amount', 10, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Add index for publisher_id for better query performance
            $table->index('publisher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
