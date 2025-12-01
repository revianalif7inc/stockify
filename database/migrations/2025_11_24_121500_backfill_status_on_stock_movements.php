<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BackfillStatusOnStockMovements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('stock_movements', 'status')) {
            DB::table('stock_movements')
                ->whereNull('status')
                ->orWhere('status', '')
                ->update(['status' => 'pending']);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No-op: we won't revert backfill to avoid data loss.
    }
}
