<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $table = 'trainings';
    protected $primaryKey = 'id_training'; // Primary key

    protected $fillable = [
        'id_category', 'title', 'image','sort_description','description','status' // Sesuaikan field
    ];

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(Category::class,'id_category','id_category');
    }

    // public function trainingVectors()
    // {
    //     return $this->hasMany(TrainingVector::class,'id_training','id_training');
    // }

    public function registrations()
    {
        return $this->hasMany(RegisTraining::class,'id_training','id_training');
    }
}
