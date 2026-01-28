<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    protected $fillable = [
        'jabatan',
    ];

    public function penerima(): HasMany
    {
        return $this->hasMany(Penerima::class);
    }
}