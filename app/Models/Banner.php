<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';
    protected $primaryKey = 'id_banner'; // Primary key

    protected $fillable = [
        'title', 'image','description','status' // Sesuaikan field
    ];

    public $timestamps = false;
}
