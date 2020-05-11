<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersAdminData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            User::create([
                'name' => env('ADMIN_DEFAULT_NAME', 'Administrator'),
                'email' => env('ADMIN_DEFAULT_EMAIL', 'admin@user.com'),
                'password' => bcrypt(env('ADMIN_DEFAULT_PASSWORD',
                    '131078')),
                'role' => User::ROLE_ADMIN
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $user = User::where('email', env('ADMIN_DEFAULT_EMAIL',
                'admin@user.com'))->first();
            if($user instanceof User){
                $user->delete();
            }
        });
    }
}
