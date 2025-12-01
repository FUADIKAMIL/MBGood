<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyMenu extends Model
{
    protected $fillable = ['menu_id', 'school_id', 'date'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
