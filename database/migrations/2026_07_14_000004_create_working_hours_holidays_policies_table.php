<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('working_hour_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('hours_per_day', 4, 2)->default(8.00);
            $table->smallInteger('days_per_week')->default(5);
            $table->enum('week_start', ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'])->default('mon');
            $table->smallInteger('grace_minutes')->default(10);
            $table->integer('overtime_after_minutes')->default(480);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->boolean('is_recurring')->default(false);
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('company_policies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('category', ['hr', 'conduct', 'leave', 'payroll', 'other'])->default('hr');
            $table->text('body')->nullable();
            $table->smallInteger('version')->default(1);
            $table->date('effective_from')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_policies');
        Schema::dropIfExists('holidays');
        Schema::dropIfExists('working_hour_policies');
    }
};
