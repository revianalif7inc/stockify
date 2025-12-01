<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToStockMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('stock_movements', 'status')) {
            Schema::table('stock_movements', function (Blueprint $table) {
                $table->string('status')->default('pending')->after('notes');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('stock_movements', 'status')) {
            Schema::table('stock_movements', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
}
