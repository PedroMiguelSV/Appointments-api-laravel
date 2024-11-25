<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'note',
        'date',
        'startTime',
        'endTime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class); 
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'appointment_services'); 
    }
}