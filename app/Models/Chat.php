<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    const PATH_ICON = '/storage/app/public/chats/';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'icon',
        'user_id',
    ];
}
