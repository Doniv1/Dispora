<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingVector extends Model
{
    protected $table = 'training_vectors';
    protected $primaryKey = 'id_training_vector';

    protected $fillable = [
        'id_training', 'id_vector', // Sesuaikan field
    ];

    public $timestamps = false;

    public function training()
    {
        return $this->belongsTo(Training::class,'id_training','id_training');
    }

    public function vector()
    {
        return $this->belongsTo(Vector::class,'id_vector','id_vector');
    }
}
