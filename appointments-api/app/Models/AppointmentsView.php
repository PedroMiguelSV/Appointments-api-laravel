<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentsView extends Model
{
    use HasFactory;

    protected $table = 'appointments_view';

    public $timestamps = false;

    protected $primaryKey = null;
    public $incrementing = false;
}
