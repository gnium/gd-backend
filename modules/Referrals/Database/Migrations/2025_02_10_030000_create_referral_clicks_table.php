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
        Schema::create('referral_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referral_link_id')->constrained('referral_links')->onDelete('cascade'); // Refers to referral_links.id
            $table->string('code')->unique(); // Unique identifier for the click event
            $table->ipAddress('ip_address'); // IP from which the link was accessed
            $table->string('user_agent')->nullable(); // Browser and device info
            $table->timestamp('clicked_at')->useCurrent(); // Timestamp of click
            $table->boolean('action_completed')->default(false); // Whether the referral action was completed
            $table->timestamp('completed_at')->nullable(); // Timestamp when action was completed
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_clicks');
    }
};
