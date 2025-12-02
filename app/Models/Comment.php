<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'daily_menu_id',
        'user_name',
        'body',
        'parent_id',   // penting !
    ];

    public function dailyMenu()
    {
        return $this->belongsTo(DailyMenu::class);
    }

    // Komentar induk
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Balasan komentar
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
