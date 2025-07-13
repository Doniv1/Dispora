<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SesiDetail extends Model
{
    use HasFactory;

    protected $table = 'sesi_detail';
    protected $primaryKey = 'id_sesi_detail';
    public $timestamps = false;

    protected $fillable = [
        'id_sesi',
        'image'
    ];

    // Relasi ke report
    public function sesi()
    {
        return $this->belongsTo(Report::class, 'id_sesi', 'id_sesi');
    }
}
