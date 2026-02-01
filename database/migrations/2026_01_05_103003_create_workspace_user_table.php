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
        Schema::create('workspace_user', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('workspace_id');
        $table->unsignedBigInteger('user_id');

        $table->string('role'); // manager | member
        $table->string('designation')->nullable();
        $table->timestamp('joined_at')->useCurrent();
        $table->string('status')->default('active');

        $table->unique(['workspace_id', 'user_id']);

        $table->index('workspace_id');
        $table->index('user_id');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_user');
    }
};
