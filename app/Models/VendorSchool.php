<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorSchool extends Model
{
    protected $table = 'vendor_schools';

    protected $fillable = ['vendor_id', 'school_id'];
}
