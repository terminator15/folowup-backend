<?php

// database/migrations/xxxx_xx_xx_create_lead_activities_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('lead_activities', function (Blueprint $table) {
            $table->id();

            // No foreign keys, only references by ID
            $table->unsignedBigInteger('lead_id')->index();
            $table->unsignedBigInteger('user_id')->index();

            $table->string('type');   // call, note, status_change, followup
            $table->json('meta')->nullable();

            $table->timestamps();

            // Optional performance index
            $table->index(['lead_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lead_activities');
    }
};

