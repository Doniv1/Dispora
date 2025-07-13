<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVector extends Model
{
    protected $table = 'user_vectors';
    protected $primaryKey = 'id_user_vector';

    protected $fillable = [
        'id_user', 'id_vector', // Sesuaikan field
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class,'id_user','id_user');
    }

    public function vector()
    {
        return $this->belongsTo(Vector::class,'id_vector','id_vector');
    }
}
