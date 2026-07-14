<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['name', 'date', 'is_recurring', 'branch_id'];

    protected function casts(): array
    {
        return ['date' => 'date', 'is_recurring' => 'boolean'];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
