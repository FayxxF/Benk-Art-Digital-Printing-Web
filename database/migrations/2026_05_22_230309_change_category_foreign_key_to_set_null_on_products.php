<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // 1. Drop the old aggressive rule
            $table->dropForeign(['category_id']);
            
            // 2. Make sure the column is allowed to be empty (null)
            $table->unsignedBigInteger('category_id')->nullable()->change();

            // 3. Add the new, safe rule!
            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->onDelete('set null'); 
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            
            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->onDelete('cascade');
        });
    }
};