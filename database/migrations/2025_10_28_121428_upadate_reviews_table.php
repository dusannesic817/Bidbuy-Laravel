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
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('reviewer_id')->nullable()->after('user_id');

            $table->foreign('reviewer_id', 'review_reviewer_user_fk')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::table('reviews', function (Blueprint $table) {
           
            $table->dropForeign('review_reviewer_user_fk');

            $table->dropColumn('reviewer_id');
        });
    }
};
