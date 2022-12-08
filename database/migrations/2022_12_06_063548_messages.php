<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
           $table->id();
           $table->uuid("uuid")->nullable();
           $table->string("destination");
           $table->text("text");
           $table->dateTime('timestamp', 3);
           $table->boolean("processed")->default(false)->nullable();
           $table->timestamp("processed_at")->nullable();
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
