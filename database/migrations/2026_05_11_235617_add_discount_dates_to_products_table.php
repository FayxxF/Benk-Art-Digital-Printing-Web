<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Nullable means you can leave it blank if the discount is permanent
            $table->dateTime('discount_start_date')->nullable()->after('discount_percentage');
            $table->dateTime('discount_end_date')->nullable()->after('discount_start_date');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['discount_start_date', 'discount_end_date']);
        });
    }
};
