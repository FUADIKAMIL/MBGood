<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'company_name',
        'contact',
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
