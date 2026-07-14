<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_positions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('position_id')->nullable()->constrained('positions')->nullOnDelete();
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->smallInteger('openings')->default(1);
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'intern'])->default('full_time');
            $table->decimal('salary_range_min', 12, 2)->nullable();
            $table->decimal('salary_range_max', 12, 2)->nullable();
            $table->enum('status', ['draft', 'open', 'on_hold', 'closed'])->default('draft');
            $table->timestamp('opened_at')->nullable();
            $table->date('closes_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_position_id')->constrained('cascadeOnDelete');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('cv_path')->nullable();
            $table->text('cover_letter')->nullable();
            $table->enum('source', ['referral', 'walk_in', 'agency', 'online', 'other'])->default('online');
            $table->enum('stage', ['applied', 'screening', 'interview', 'offer', 'hired', 'rejected'])->default('applied');
            $table->text('rejected_reason')->nullable();
            $table->smallInteger('rating')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('cascadeOnDelete');
            $table->tinyInteger('round')->default(1);
            $table->enum('type', ['phone', 'onsite', 'video', 'assessment'])->default('phone');
            $table->timestamp('scheduled_at')->nullable();
            $table->integer('duration_minutes')->default(30);
            $table->foreignId('interviewer_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('location')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->smallInteger('score')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();
        });

        Schema::create('onboarding_checklists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('onboarding_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_id')->constrained('onboarding_checklists')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->smallInteger('sort_order')->default(0);
        });

        Schema::create('employee_onboardings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('cascadeOnDelete');
            $table->foreignId('checklist_id')->constrained('onboarding_checklists')->cascadeOnDelete();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
            $table->timestamps();
        });

        Schema::create('employee_onboarding_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('onboarding_id')->constrained('employee_onboardings')->cascadeOnDelete();
            $table->string('task_title');
            $table->boolean('is_done')->default(false);
            $table->timestamp('done_at')->nullable();
            $table->foreignId('done_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_onboarding_tasks');
        Schema::dropIfExists('employee_onboardings');
        Schema::dropIfExists('onboarding_tasks');
        Schema::dropIfExists('onboarding_checklists');
        Schema::dropIfExists('interviews');
        Schema::dropIfExists('applicants');
        Schema::dropIfExists('job_positions');
    }
};
