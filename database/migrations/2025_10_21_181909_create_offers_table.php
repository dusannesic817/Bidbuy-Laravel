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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auction_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('price',10,2);
            $table->timestamps();

            $table->foreign('auction_id', 'offer_auction_auction_fk')->references('id')->on('auctions')
            ->onUpdte('cascade')
            ->onDelete('cascade');

            $table->foreign('user_id', 'user_offer_user_fk')->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdte('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
