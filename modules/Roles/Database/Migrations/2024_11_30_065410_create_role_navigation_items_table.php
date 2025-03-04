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
        Schema::create('role_navigation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade'); // Role ID
            $table->string('navigation_item'); // Unique navigation key (e.g., 'dashboard', 'users', 'settings')
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_navigation_items');
    }
    /**
     * Define a one-to-many relationship with RoleNavigationItem.
     */
    public function navigationItems(): HasMany
    {
        return $this->hasMany(RoleNavigationItem::class);
    }
};
