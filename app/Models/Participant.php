<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'whatsapp',
        'ticket_code',
        'golongan_darah',
        'session',
        'umur',
        'umur_valid',
        'sehat',
        'is_sent',
        'keterangan',
    ];

    protected $casts = [
        'umur_valid' => 'boolean',
        'sehat' => 'boolean',
        'is_sent' => 'boolean',
    ];
}
