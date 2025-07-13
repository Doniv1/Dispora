<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    // Nama tabel
    protected $table = 'forms';

    // Primary key custom
    protected $primaryKey = 'id_form';

    // Tidak menggunakan timestamps default Laravel (created_at, updated_at)
    public $timestamps = false;

    // Fillable fields
    protected $fillable = [
        'field',
        'type',
        'urutan',
        'created_at',
        'updated_at',
        'deleted',
        'deleted_by',
        'deleted_at',
    ];

    // Jika ingin menggunakan soft deletes manual
    protected $dates = ['deleted_at'];

    // Relasi ke user yang menghapus
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id_user');
    }
}
