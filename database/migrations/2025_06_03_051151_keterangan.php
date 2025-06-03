<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            DROP FUNCTION IF EXISTS ketStatusPKL;
        ');

        DB::unprepared('
            CREATE FUNCTION ketStatusPKL(status ENUM("no", "yes")) RETURNS VARCHAR(50)
            DETERMINISTIC
            BEGIN
                IF status = "no" THEN
                    RETURN "Belum diterima PKL";
                ELSEIF status = "yes" THEN
                    RETURN "Sudah diterima PKL";
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS ketStatusPKL');
    }
};
