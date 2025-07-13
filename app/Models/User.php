<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

  protected $table = 'users'; // Nama tabel

  protected $primaryKey = 'id_user'; // Primary key

  public $timestamps = false; // Karena tidak pakai default timestamps Laravel

  protected $fillable = ['email', 'name', 'phone', 'role', 'id_vector', 'id_category', 'id_riwayat_pelatihan', 'born_date', 'education_status', 'gender', 'image', 'password', 'status', 'reason', 'blocked_date', 'blocked_by', 'created_by', 'created_at', 'updated_at', 'deleted', 'deleted_by', 'deleted_date'];

  protected $hidden = ['password'];

  public function setPasswordAttribute($password)
  {
    $this->attributes['password'] = Hash::make($password);
  }

  public function userVectors()
  {
    return $this->hasMany(UserVector::class, 'id_user', 'id_user');
  }

  public function vector()
  {
    return $this->belongsTo(Vector::class, 'id_vector', 'id_vector');
  }
  public function category()
  {
    return $this->belongsTo(Category::class, 'id_category', 'id_category');
  }
  public function riwayatPelatihan()
  {
    return $this->belongsTo(Training::class, 'id_riwayat_pelatihan', 'id_training');
  }
}
