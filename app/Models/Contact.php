<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    protected $table = 'contacts'; // Nama tabel di database
    protected $primaryKey = 'id_contact'; // Primary key

    protected $fillable = [
        'name','email','message'
    ];
}
