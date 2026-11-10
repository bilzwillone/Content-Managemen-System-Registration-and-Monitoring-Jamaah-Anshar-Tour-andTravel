
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jamaahs', function (Blueprint $table) {
            // Informasi registrasi
            $table->string('branch_office')->nullable();
            $table->string('registration_number')->nullable()->unique();

            // Pilihan paket dan keberangkatan
            $table->string('umrah_package_type', 30)->nullable();
            $table->string('room_type', 20)->nullable();
            $table->date('planned_departure_date')->nullable();

            // Identitas pribadi
            $table->string('national_id', 30)->nullable();
            $table->string('birth_place', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('father_name')->nullable();

            // Informasi pribadi
            $table->string('gender', 20)->nullable();
            $table->string('marital_status', 30)->nullable();

            // Informasi kontak tambahan
            $table->string('home_phone', 30)->nullable();
            $table->string('emergency_contact_phone', 30)->nullable();

            // Riwayat dan latar belakang
            $table->string('umrah_experience', 30)->nullable();
            $table->string('education_level', 30)->nullable();
            $table->string('occupation', 30)->nullable();

            // Informasi kesehatan
            $table->text('medical_history')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('jamaahs', function (Blueprint $table) {
            $table->dropUnique(['registration_number']);

            $table->dropColumn([
                'branch_office',
                'registration_number',
                'umrah_package_type',
                'room_type',
                'planned_departure_date',
                'national_id',
                'birth_place',
                'birth_date',
                'father_name',
                'gender',
                'marital_status',
                'home_phone',
                'emergency_contact_phone',
                'umrah_experience',
                'education_level',
                'occupation',
                'medical_history',
            ]);
        });
    }
};
