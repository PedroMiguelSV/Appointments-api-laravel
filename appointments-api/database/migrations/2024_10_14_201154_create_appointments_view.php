<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAppointmentsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW appointments_view AS
            SELECT 
                a.id AS appointment_id,  -- ID de la cita
                a.date, 
                a.time, 
                a.note,                  -- Nota de la cita
                c.id AS client_id,        -- ID del cliente
                c.name AS client_name, 
                c.phone AS client_phone, 
                GROUP_CONCAT(s.name SEPARATOR ', ') AS services
            FROM appointments a
            JOIN clients c ON a.client_id = c.id
            JOIN appointment_services aps ON a.id = aps.appointment_id
            JOIN services s ON aps.service_id = s.id
            GROUP BY a.id, a.date, a.time, a.note, c.id, c.name, c.phone
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS appointments_view");
    }
}
