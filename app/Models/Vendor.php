<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'company_name',
        'contact',
        'user_id',
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schools()
    {
        return $this->belongsToMany(School::class, 'vendor_schools');
    }
}
