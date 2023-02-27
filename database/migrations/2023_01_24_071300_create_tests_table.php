<?php

use App\Models\Teacher;
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
        Schema::create('tests', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->morphs('testable');     // App\Models\Book or App\Models\Resource
            $table->string('teacher_id')->nullable();      // null if test is created by app admin
            $table->foreign('teacher_id')->references('id')->on('teachers');
            $table->enum('term', [1, 2, 3]);
            $table->integer('week')->nullable();
            $table->enum('type', ['weekly', 'standard', 'speed']);
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
        Schema::dropIfExists('tests');
    }
};
