<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebPhone extends Model
{
    protected $table = 'web_phone';
    protected $primaryKey = 'id_web_phone';
    public $timestamps = false;

    protected $fillable = [
        'id_setting',
        'name',
        'phone',
    ];

    // Relasi ke Setting
    public function setting(): BelongsTo
    {
        return $this->belongsTo(Setting::class, 'id_setting', 'id_setting');
    }
}
