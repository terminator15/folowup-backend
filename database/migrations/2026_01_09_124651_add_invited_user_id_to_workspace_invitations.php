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
        Schema::table('workspace_invitations', function (Blueprint $table) {
        $table->unsignedBigInteger('invited_user_id')->after('workspace_id');

        $table->dropColumn('email');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workspace_invitations', function (Blueprint $table) {
            $table->unsignedBigInteger('email')->after('workspace_id');
            $table->dropColumn('invited_user_id');
        });
    }
};
