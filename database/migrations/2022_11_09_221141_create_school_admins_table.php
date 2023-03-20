<?php

use App\Models\School;
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
        Schema::create('school_admins', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('school_id');
            $table->foreign('school_id')->references('id')->on('schools');
            $table->boolean('is_pcp')->default(false);
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('phone_number')->nullable();
            $table->string('password');
            $table->string('profile_pic')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_admins');
    }
};
