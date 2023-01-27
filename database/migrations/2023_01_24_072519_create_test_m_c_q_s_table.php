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
        Schema::create('test_m_c_q_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained();
            $table->tinyText('question');
            $table->jsonb('options');
            $table->enum('correct_option', [1, 2, 3, 4, 5]);
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
        Schema::dropIfExists('test_m_c_q_s');
    }
};
