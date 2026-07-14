<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayslipLine extends Model
{
    public $timestamps = false;

    protected $fillable = ['payslip_id', 'component_code', 'label', 'type', 'amount', 'sort_order'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2'];
    }

    public function payslip()
    {
        return $this->belongsTo(Payslip::class);
    }
}
