<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
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
            // company = authentication melalui API perusahaan
            // external = authentication lokal aplikasi
            $table->enum('auth_type', ['company', 'external']);
            // Hanya diisi untuk user internal perusahaan
            $table->string('nik')->nullable()->unique();
            $table->string('name');
            // Dipakai untuk external user
            $table->string('email')->nullable()->unique();
            // Hanya digunakan untuk external authentication.
            // Password internal tidak disimpan di aplikasi.
            $table->string('password')->nullable();
            $table->enum('role', ['admin', 'viewer']);
            $table->boolean('is_active')->default(true);
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
}
