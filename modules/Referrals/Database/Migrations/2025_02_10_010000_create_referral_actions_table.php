<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('referral_actions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique(); // Internal name (e.g., 'register', 'buy_domain')
            $table->string('display_name', 100); // Human-readable name
            $table->string('path_template', 255); // Dynamic path template (e.g., "/register/{{referrer_code}}")
            $table->decimal('reward_amount', 10, 2)->nullable(); // Optional reward for the action
            $table->string('currency', 10)->nullable(); // Currency for the reward (e.g., "USD")
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_actions');
    }
};
