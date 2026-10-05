<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQadUserRolesTable extends Migration
{
    public function up()
    {
        Schema::create('qad_user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->enum('role', ['admin', 'viewer'])->default('viewer');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('qad_user_roles');
    }
}