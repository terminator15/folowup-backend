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
        $table->dropUnique('workspace_invitations_workspace_id_email_unique');
        $table->unique(['workspace_id', 'invited_user_id'], 'workspace_invitations_workspace_id_invited_user_unique');

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
