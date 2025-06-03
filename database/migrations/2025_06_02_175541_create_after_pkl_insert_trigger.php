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
            CREATE TRIGGER `after_pkl_insert_update_siswa_status`
            AFTER INSERT ON `pkl`
            FOR EACH ROW
            BEGIN
                UPDATE `siswa`
                SET `status_pkl` = 1
                WHERE `id` = NEW.siswa_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS `after_pkl_insert_update_siswa_status`');
    }
};