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
        Schema::create('books', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('level_id')->constrained();
            $table->foreignUuid('subject_id')->constrained();
            $table->unique(['level_id', 'subject_id']);
            $table->string('title');
            $table->string('cover_path')->nullable();
            $table->double('cover_size', 10, 2)->nullable();
            $table->string('file_path');
            $table->double('file_size', 10, 2);
            $table->boolean('is_compulsory')->default(true); // isCompulsory applies to only SS students
            $table->enum('department', ['science', 'arts', 'commerce'])->nullable();    // JS does not have departments
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
        Schema::dropIfExists('books');
    }
};
