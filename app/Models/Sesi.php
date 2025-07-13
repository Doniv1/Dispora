<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sesi extends Model
{
    use HasFactory;

    protected $table = 'sesi';
    protected $primaryKey = 'id_sesi';
    public $timestamps = false;

    protected $fillable = [
        'id_sesi',
        'start_date',
        'end_date',
        'status',
        'created_at',
        'updated_at'
    ];


    // Relasi ke report_details
    public function details()
    {
        return $this->hasMany(SesiDetail::class, 'id_sesi', 'id_sesi');
    }
}
