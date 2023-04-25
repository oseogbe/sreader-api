<?php

use App\Models\Level;
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
        Schema::create('students', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->foreignIdFor(Level::class)->nullable();
            $table->string('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('parents');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('middlename')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_number')->unique()->nullable();
            $table->string('password');
            $table->string('profile_pic')->nullable();
            $table->string('status')->default('inactive');
            $table->rememberToken();
            $table->dateTime('activated_at')->nullable();
            $table->dateTime('deactivated_at')->nullable();
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
        Schema::dropIfExists('students');
    }
};
