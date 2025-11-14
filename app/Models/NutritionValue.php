<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NutritionValue extends Model
{
    protected $fillable = [
        'menu_id',
        'calories',
        'protein',
        'fat',
        'carbs',
        'vitamins',
    ];

    protected $casts = [
        'vitamins' => 'array',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
