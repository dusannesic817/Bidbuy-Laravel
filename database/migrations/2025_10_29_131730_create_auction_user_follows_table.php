<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auction_user_follows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auction_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
           
            $table->unique(['user_id', 'auction_id']);

            $table->foreign('user_id','follow_user_follow_fk')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('auction_id','follow_auction_follow_fk')->references('id')->on('auctions')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_user_follows');
    }
};
