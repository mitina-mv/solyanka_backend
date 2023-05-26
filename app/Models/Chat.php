<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory;
    use SoftDeletes;

    const PATH_ICON = '/storage/app/public/chats/';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'icon',
        'user_id',
    ];
}
