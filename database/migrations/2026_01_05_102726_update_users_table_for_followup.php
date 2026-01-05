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
    Schema::table('users', function (Blueprint $table) {
        $table->string('phone')->nullable()->after('email');
        $table->string('google_id')->nullable()->after('phone');
        $table->timestamp('password_set_at')->nullable()->after('password');
        $table->timestamp('registered_at')->nullable()->after('remember_token');
        $table->timestamp('last_login_at')->nullable()->after('registered_at');
        $table->boolean('is_active')->default(true)->after('last_login_at');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'phone',
            'google_id',
            'password_set_at',
            'registered_at',
            'last_login_at',
            'is_active'
        ]);
    });
}
};
