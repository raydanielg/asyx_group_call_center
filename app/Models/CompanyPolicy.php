<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPolicy extends Model
{
    protected $fillable = ['title', 'category', 'body', 'version', 'effective_from', 'is_active'];

    protected function casts(): array
    {
        return ['effective_from' => 'date', 'is_active' => 'boolean'];
    }
}
