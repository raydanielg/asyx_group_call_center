<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    protected $fillable = ['name', 'code', 'unit', 'direction', 'weight', 'applies_to', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'weight' => 'decimal:2'];
    }

    public function targets()
    {
        return $this->hasMany(KpiTarget::class);
    }
}
