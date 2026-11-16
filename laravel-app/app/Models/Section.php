<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['key', 'label', 'enabled', 'display_order'];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
        ];
    }
}
