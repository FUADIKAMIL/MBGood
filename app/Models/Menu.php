<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
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

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
