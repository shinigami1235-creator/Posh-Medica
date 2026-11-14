<?php

namespace App\Models;

use App\Models\Concerns\Localized;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use Localized;

    protected $guarded = [];
    protected $casts = ['documents' => 'array'];
}
