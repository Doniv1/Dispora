<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebEmail extends Model
{
    protected $table = 'web_email';
    protected $primaryKey = 'id_web_email';
    public $timestamps = false;

    protected $fillable = [
        'id_setting',
        'email',
    ];

    // Relasi ke Setting
    public function setting(): BelongsTo
    {
        return $this->belongsTo(Setting::class, 'id_setting', 'id_setting');
    }
}
