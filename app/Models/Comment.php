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

    public function dailyMenu()
    {
        return $this->belongsTo(DailyMenu::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}