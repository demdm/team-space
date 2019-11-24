<?php

use App\User;
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
            $table->string('id')->unique();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('password' )->nullable();
            $table->string('api_token')->unique()->nullable();
            $table->string('set_password_token')->unique()->nullable();
            $table->string('auth_status')->default(User::AUTH_STATUS_ANONYMOUS);
            $table->boolean('is_online')->default(false);
            $table->boolean('is_used')->default(true);
            $table->timestamp('online_at')->default(null)->nullable();
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
