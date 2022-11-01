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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('name');
            $table->string('surname');
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password')->nullable(true)->default(null);
            $table->string("age")->nullable(true)->default(null);
            $table->string("gender")->nullable(true)->default(null);
            $table->string("country")->nullable(true)->default(null);
            $table->text("image")->nullable(true)->default(null);
            $table->text("bio")->nullable(true)->default(null);
            $table->string("provider");
            $table->boolean("banned");
            $table->date("seen")->nullable(true)->default(null);
            $table->integer("role_id");
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
