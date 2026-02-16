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
        Schema::create('leads', function (Blueprint $table) {
    $table->id();

    $table->unsignedBigInteger('workspace_id');
    $table->unsignedBigInteger('owner_id'); // user id

    $table->string('title')->nullable();
    $table->string('name');
    $table->string('phone');
    $table->string('email')->nullable();

    $table->string('lead_type');
    $table->string('status')->default('new');

    $table->decimal('deal_value', 15, 2)->nullable();
    $table->string('currency')->default('INR');
    $table->string('source')->nullable();

    $table->timestamp('next_followup_at')->nullable();
    $table->timestamps();

    $table->index(['workspace_id', 'owner_id']);
    $table->index(['lead_type', 'deal_value']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
