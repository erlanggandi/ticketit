<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlaSetting extends Model
{
    protected $fillable = ['priority', 'duration_hours'];

    protected function casts(): array
    {
        return [
            'duration_hours' => 'integer',
        ];
    }
}
