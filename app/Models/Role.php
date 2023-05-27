<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    public $timestamps = false;

    const PATH_ICON='/storage/roles/';

    protected $fillable = [
        'name',
        'text',
        'user_id',
        'icon',
        'isChoice',
    ];
}
