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
            $table->string('position');
            $table->string('email')->unique();
            $table->string('password' );
            $table->string('api_token')->unique();
            $table->string('confirm_email_token')->unique()->nullable();
            $table->string('reset_password_token')->unique()->nullable();
            $table->string('role')->default(User::ROLE_EMPLOYEE);
            $table->string('auth_status')->default(User::AUTH_STATUS_PENDING);
            $table->string('presence_status')->default(User::PRESENCE_STATUS_WORK);
            $table->string('work_type')->default(User::WORK_TYPE_OFFICE);
            $table->boolean('is_email_confirmed')->default(false);
            $table->boolean('is_online')->default(false);
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
