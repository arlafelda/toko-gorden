<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->unsignedBigInteger('sender_id');
            $table->string('sender_type'); // 'user' atau 'admin'
            $table->unsignedBigInteger('receiver_id');
            $table->string('receiver_type'); // 'user' atau 'admin'
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};