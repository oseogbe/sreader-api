<?php

use App\Models\Admin;
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
        Schema::create('copies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('level_id')->constrained();
            $table->foreignUuid('subject_id')->constrained();
            $table->foreignUuid('author')->constrained('admins');
            $table->enum('term', [1, 2, 3]);
            $table->integer('week');
            $table->string('topic');
            $table->longText('content');
            $table->enum('status', [1, 2, 3])->comment('1 - draft, 2 - published, 3 - archived');
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
        Schema::dropIfExists('copies');
    }
};
