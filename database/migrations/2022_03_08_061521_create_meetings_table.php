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


        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('zoom_meet_id');
            $table->string('host_id');
            $table->string('host_email');
            $table->string('topic');
            $table->string('type');
            $table->string('status');
            $table->longText('start_url');
            $table->longText('join_url');
            $table->string('password');
            $table->string('encrypted_password');
            $table->string('h323_password');
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
        Schema::dropIfExists('meetings');
    }
};
