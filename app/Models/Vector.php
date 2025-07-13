<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vector extends Model
{
    protected $table = 'vectors';
    protected $primaryKey = 'id_vector';

    protected $fillable = [
        'name','status' // Contoh field
    ];

    public $timestamps = false;

    public function trainingVectors()
    {
        return $this->hasMany(TrainingVector::class,'id_vector','id_vector');
    }
}
