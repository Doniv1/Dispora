<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisTraining extends Model
{
    protected $table = 'regis_trainings';
    protected $primaryKey = 'id_regis_training'; // Primary key

    protected $fillable = [
        'id_training', 'id_user','approved','status', // Sesuaikan dengan struktur tabel
    ];

    public $timestamps = false;

    public function training()
    {
        return $this->belongsTo(Training::class,'id_training','id_training');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'id_user','id_user');
    }
}
