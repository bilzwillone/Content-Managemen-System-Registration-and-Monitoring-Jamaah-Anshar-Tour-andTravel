
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buat kolom hanya jika belum tersedia
        if (!Schema::hasColumn('jamaahs', 'total_bill')) {
            Schema::table('jamaahs', function (Blueprint $table) {
                $table->decimal('total_bill', 15, 2)->nullable();
            });
        }

        // Isi total tagihan berdasarkan harga paket
        $jamaahs = DB::table('jamaahs')
            ->whereNull('total_bill')
            ->whereNotNull('package_id')
            ->get(['id', 'package_id']);

        foreach ($jamaahs as $jamaah) {
            $price = DB::table('packages')
                ->where('id', $jamaah->package_id)
                ->value('price');

            DB::table('jamaahs')
                ->where('id', $jamaah->id)
                ->update([
                    'total_bill' => $price
                ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('jamaahs', 'total_bill')) {
            Schema::table('jamaahs', function (Blueprint $table) {
                $table->dropColumn('total_bill');
            });
        }
    }
};
