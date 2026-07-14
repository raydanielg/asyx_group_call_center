<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->foreignId('shift_assignment_id')->nullable()->constrained('shift_assignments')->nullOnDelete();
            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->enum('status', ['present', 'absent', 'late', 'half_day', 'on_leave', 'holiday', 'off'])->default('present');
            $table->integer('late_minutes')->default(0);
            $table->integer('early_leave_minutes')->default(0);
            $table->integer('worked_minutes')->default(0);
            $table->integer('overtime_minutes')->default(0);
            $table->boolean('overtime_approved')->default(false);
            $table->enum('source', ['manual', 'bulk_import', 'correction'])->default('manual');
            $table->text('remarks')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->unique(['employee_id', 'date']);
            $table->timestamps();
        });

        Schema::create('attendance_corrections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_record_id')->constrained('attendance_records')->cascadeOnDelete();
            $table->string('field');
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();
            $table->text('reason');
            $table->foreignId('corrected_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('corrected_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_corrections');
        Schema::dropIfExists('attendance_records');
    }
};
