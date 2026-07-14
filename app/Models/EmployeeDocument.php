<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    protected $fillable = ['employee_id', 'category', 'title', 'file_path', 'file_size', 'mime_type', 'uploaded_by', 'expires_at'];

    protected function casts(): array
    {
        return ['expires_at' => 'date'];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
