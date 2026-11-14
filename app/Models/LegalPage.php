<?php

namespace App\Models;

use App\Models\Concerns\Localized;
use Illuminate\Database\Eloquent\Model;

class LegalPage extends Model
{
    use Localized;

    protected $guarded = [];
}
