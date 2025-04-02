<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordering extends Model
{
    protected $table = 'orderings';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];
}
