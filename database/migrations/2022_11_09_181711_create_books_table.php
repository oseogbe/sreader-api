<?php

use App\Models\Level;
use App\Models\Subject;
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
            $table->string('id')->primary();
            $table->foreignIdFor(Level::class);
            $table->string('subject_id');
            $table->foreign('subject_id')->references('id')->on('subjects');
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
