<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryComponent extends Model
{
    protected $fillable = ['name', 'code', 'type', 'calc_type', 'value', 'is_taxable', 'is_statutory', 'is_active'];

    protected function casts(): array
    {
        return ['is_taxable' => 'boolean', 'is_statutory' => 'boolean', 'is_active' => 'boolean', 'value' => 'decimal:4'];
    }
}
