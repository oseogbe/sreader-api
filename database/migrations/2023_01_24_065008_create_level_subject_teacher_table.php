<?php

use App\Models\LevelSubject;
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
        Schema::create('level_subject_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('school_id')->constrained();
            $table->foreignId('level_subject_id');
            $table->foreignUuid('teacher_id')->constrained();
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
        Schema::dropIfExists('level_subject_teacher');
    }
};
