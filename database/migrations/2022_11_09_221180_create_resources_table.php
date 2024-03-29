<?php

use App\Models\School;
use App\Models\TeacherSubject;
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
        Schema::create('resources', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('school_id');
            $table->foreign('school_id')->references('id')->on('schools');
            $table->foreignIdFor(TeacherSubject::class);
            $table->enum('term', [1, 2, 3]);
            $table->integer('week');
            $table->string('name');
            $table->longText('content');
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
        Schema::dropIfExists('topics');
    }
};
