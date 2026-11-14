<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageText extends Model
{
    protected $guarded = [];
    protected $casts = ['is_html' => 'boolean'];
}
