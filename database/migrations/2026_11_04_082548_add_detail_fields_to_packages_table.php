
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->json('facilities')->nullable()->after('description');
            $table->string('hotel_makkah')->nullable()->after('hotel');
            $table->string('hotel_madinah')->nullable()->after('hotel_makkah');
            $table->text('flight_note')->nullable()->after('hotel_madinah');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'facilities',
                'hotel_makkah',
                'hotel_madinah',
                'flight_note',
            ]);
        });
    }
};
