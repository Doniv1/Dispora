<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id_category'; // Primary key

    protected $fillable = [
        'name', 'status'// Tambahkan sesuai dengan kolom sebenarnya
    ];

    public $timestamps = false;

    public function trainings()
    {
        return $this->hasMany(Training::class,'id_category','id_category');
    }
}
