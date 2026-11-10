
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jamaahs', function (Blueprint $table) {
            $table->decimal('total_bill', 15, 2)
                ->nullable()
                ->after('package_id');
        });

        DB::table('jamaahs')
            ->join('packages', 'jamaahs.package_id', '=', 'packages.id')
            ->whereNull('jamaahs.total_bill')
            ->update([
                'jamaahs.total_bill' => DB::raw('packages.price'),
            ]);
    }

    public function down(): void
    {
        Schema::table('jamaahs', function (Blueprint $table) {
            $table->dropColumn('total_bill');
        });
    }
};
