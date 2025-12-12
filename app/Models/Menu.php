<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['vendor_id', 'title', 'description', 'status', 'rejection_reason'];

    public function items()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function nutrition()
    {
        return $this->hasOne(NutritionValue::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function dailyMenus()
    {
        return $this->hasMany(DailyMenu::class);
    }
}
