<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'menu_id',
        'user_name',
        'body'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}