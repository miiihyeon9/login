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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('phone_number');
            $table->integer('birth');
            $table->string('question');
            $table->string('question_answer');
            // $table->char('del_flg',1)->default('0');
            $table->timestamp('email_verified_at')->nullable(); // email 인증 시각
            $table->rememberToken(); // 로그인 유지하기 기능
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
        Schema::dropIfExists('users');
    }
};