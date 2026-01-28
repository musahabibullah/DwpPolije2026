<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurusan extends Model
{
    protected $fillable = [
        'nama_jurusan',
        'meja_pengambilan'
    ];

    public function penerima(): HasMany
    {
        return $this->hasMany(Penerima::class);
    }
    // app/Models/Jurusan.php
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}