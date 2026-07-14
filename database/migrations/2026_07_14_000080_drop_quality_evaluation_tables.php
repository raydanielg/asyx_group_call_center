<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('coaching_notes');
        Schema::dropIfExists('quality_evaluations');
        Schema::dropIfExists('quality_form_criteria');
        Schema::dropIfExists('quality_evaluation_forms');
    }

    public function down(): void
    {
        // Tables cannot be restored without data loss
    }
};
