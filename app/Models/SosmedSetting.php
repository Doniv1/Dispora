<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SosmedSetting extends Model
{
    protected $table = 'sosmed_setting';
    protected $primaryKey = 'id_sosmed_setting';
    public $timestamps = false;

    protected $fillable = [
        'id_setting',
        'id_sosmed',
        'name',
        'url',
    ];

    // Relasi ke Setting
    public function setting(): BelongsTo
    {
        return $this->belongsTo(Setting::class, 'id_setting', 'id_setting');
    }

    // Relasi ke Sosmed
    public function sosmed(): BelongsTo
    {
        return $this->belongsTo(Sosmed::class, 'id_sosmed', 'id_sosmed');
    }
}
