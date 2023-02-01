<?php

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
            $table->id();
            $table->uuidMorphs('testable');     // App\Models\Book or App\Models\Resource
            $table->foreignUuid('teacher_id')->nullable();      // null if test is created by app admin
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
