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
         Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('short_description', 100);
            $table->text('description');
            $table->decimal('started_price', 10,2);
            $table->enum('condition', ['Novo', 'Polovno', 'Kao Novo'])->default('Novo');
            $table->dateTime('expiry_time');
            $table->boolean('status');
            $table->timestamps();

            $table->foreign('user_id', 'auction_user_user_fk')->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdte('cascade');

            $table->foreign('category_id', 'auction_category_category_fk')->references('id')->on('categories')
            ->onUpdte('cascade')
            ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
