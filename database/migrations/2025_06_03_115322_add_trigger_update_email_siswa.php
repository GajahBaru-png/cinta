<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AddTriggerUpdateEmailSiswa extends Migration
{
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER update_email_user_from_siswa
            AFTER UPDATE ON siswa
            FOR EACH ROW
            BEGIN
                IF OLD.email != NEW.email THEN
                    UPDATE users SET email = NEW.email WHERE email = OLD.email;
                END IF;
            END
        ");
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_update_email_siswa');
    }
}

