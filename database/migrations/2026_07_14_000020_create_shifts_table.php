<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('crosses_midnight')->default(false);
            $table->smallInteger('break_minutes')->default(60);
            $table->string('color', 7)->default('#0D3E63');
            $table->boolean('is_night_shift')->default(false);
            $table->decimal('night_allowance', 12, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('shift_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shift_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['assigned', 'swapped', 'cancelled'])->default('assigned');
            $table->foreignId('assigned_by')->constrained('users')->cascadeOnDelete();
            $table->unique(['employee_id', 'date']);
            $table->timestamps();
        });

        Schema::create('shift_rotations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('pattern');
            $table->smallInteger('cycle_days')->default(7);
            $table->enum('applies_to', ['team', 'department', 'employees'])->default('department');
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->date('starts_on');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_rotations');
        Schema::dropIfExists('shift_assignments');
        Schema::dropIfExists('shifts');
    }
};
