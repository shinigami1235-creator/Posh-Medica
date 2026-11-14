<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $guarded = [];
    protected $casts = ['consented_at' => 'datetime', 'read_at' => 'datetime'];
}
