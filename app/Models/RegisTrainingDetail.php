<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisTrainingDetail extends Model
{
    protected $table = 'regis_training_details';
    protected $primaryKey = 'id_regis_training_detail';
    public $timestamps = false; // Karena kamu pakai created_at dan updated_at manual pakai default CURRENT_TIMESTAMP

    protected $fillable = [
        'id_regis_training',
        'id_form',
        'value',
        'deleted',
        'deleted_by',
        'deleted_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relasi ke regis_training (banyak detail ke satu regis_training)
    public function regisTraining()
    {
        return $this->belongsTo(RegisTraining::class, 'id_regis_training', 'id_regis_training');
    }

    // Relasi ke form
    public function form()
    {
        return $this->belongsTo(Form::class, 'id_form', 'id_form');
    }

    // Relasi ke user yang menghapus
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id_user');
    }

    // Scope untuk data yang tidak terhapus (deleted = 'N')
    public function scopeNotDeleted($query)
    {
        return $query->where('deleted', 'N');
    }

    // Kamu bisa buat fungsi untuk soft delete custom
    public function softDelete($deletedByUserId)
    {
        $this->deleted = 'Y';
        $this->deleted_by = $deletedByUserId;
        $this->deleted_at = now();
        return $this->save();
    }
}
