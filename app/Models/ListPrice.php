<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ListPrice extends Model
{
    use HasFactory;

    protected $table = 'list_price';
    protected $primaryKey = 'id_list_price';
    public $timestamps = false;

    protected $fillable = [
        'price',
        'status',
        'created_at',
        'updated_at'
    ];
}
