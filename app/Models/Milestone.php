<?php

namespace App\Models;

use App\Models\Concerns\Localized;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use Localized;

    protected $guarded = [];
    protected $casts = ['is_published' => 'boolean'];
}
