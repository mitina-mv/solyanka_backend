<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory;
    use SoftDeletes;

    const PATH_ICON = '/storage/chats/';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'icon',
        'user_id',
        'role_id',
    ];

    public function history()
    {
        return $this->hasMany(Request::class, 'chat_id', 'id')->get();
    }
}
