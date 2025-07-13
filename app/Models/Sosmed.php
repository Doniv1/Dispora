<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sosmed extends Model
{
    use HasFactory;

    protected $table = 'sosmeds'; // Nama tabel di database
    protected $primaryKey = 'id_sosmed'; // Primary key

    protected $fillable = [
        'icon','name'
    ];

    public function sosmedSetting()
    {
        return $this->hasOne(SosmedSetting::class, 'id_sosmed', 'id_sosmed');
    }

}
