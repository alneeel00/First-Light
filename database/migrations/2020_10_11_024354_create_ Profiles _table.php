<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('First_Name');
            $table->string('Last_Name', 50);
            $table->string('Street_Address', 20);
            $table->string('Postal', 30);
            $table->string('Country');
            $table->string('Email');
            $table->string('Phone');
            $table->timestamp('date');
            $table->string('Upload_Resume');
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
        Schema::dropIfExists('Profiles');
    }
}
