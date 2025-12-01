<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = ['name', 'region', 'address'];

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'vendor_schools');
    }

    public function dailyMenus()
    {
        return $this->hasMany(DailyMenu::class);
    }
}
