<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{

    protected $table = 'subscribe'; // Nama tabel di database
    protected $primaryKey = 'id_subscribe'; // Primary key

    protected $fillable = [
        'email'
    ];
}
