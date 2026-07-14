<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('unit', ['count', 'percent', 'seconds', 'score', 'currency'])->default('count');
            $table->enum('direction', ['higher_better', 'lower_better'])->default('higher_better');
            $table->decimal('weight', 5, 2)->default(1.00);
            $table->enum('applies_to', ['agent', 'team'])->default('agent');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('kpi_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kpi_id')->constrained()->cascadeOnDelete();
            $table->enum('scope', ['company', 'department', 'team', 'employee'])->default('company');
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            $table->smallInteger('period_year');
            $table->smallInteger('period_month')->nullable();
            $table->decimal('target_value', 14, 4)->default(0);
            $table->timestamps();
        });

        Schema::create('agent_daily_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->integer('total_calls')->default(0);
            $table->integer('answered_calls')->default(0);
            $table->integer('missed_calls')->default(0);
            $table->integer('outbound_calls')->default(0);
            $table->integer('talk_time_seconds')->default(0);
            $table->integer('hold_time_seconds')->default(0);
            $table->integer('wrap_time_seconds')->default(0);
            $table->integer('aht_seconds')->default(0);
            $table->integer('conversions')->default(0);
            $table->decimal('csat_score', 4, 2)->nullable();
            $table->enum('source', ['manual', 'csv_import'])->default('manual');
            $table->unique(['employee_id', 'date']);
            $table->timestamps();
        });

        Schema::create('performance_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->smallInteger('period_year');
            $table->smallInteger('period_month');
            $table->json('kpi_scores')->nullable();
            $table->decimal('weighted_score', 5, 2)->default(0);
            $table->integer('rank_in_team')->nullable();
            $table->integer('rank_in_company')->nullable();
            $table->enum('grade', ['A', 'B', 'C', 'D', 'E'])->nullable();
            $table->foreignId('evaluated_by')->constrained('users')->cascadeOnDelete();
            $table->text('comments')->nullable();
            $table->enum('status', ['draft', 'finalized'])->default('draft');
            $table->unique(['employee_id', 'period_year', 'period_month']);
            $table->timestamps();
        });

        Schema::create('quality_evaluation_forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->smallInteger('max_score')->default(100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('quality_form_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('quality_evaluation_forms')->cascadeOnDelete();
            $table->string('label');
            $table->decimal('weight', 5, 2)->default(1.00);
            $table->smallInteger('max_points')->default(10);
            $table->smallInteger('sort_order')->default(0);
        });

        Schema::create('quality_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('form_id')->constrained('quality_evaluation_forms')->cascadeOnDelete();
            $table->string('call_reference')->nullable();
            $table->timestamp('evaluated_at')->useCurrent();
            $table->foreignId('evaluator_user_id')->constrained('users')->cascadeOnDelete();
            $table->json('scores')->nullable();
            $table->decimal('total_score', 5, 2)->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->enum('outcome', ['pass', 'fail', 'coaching_required'])->default('pass');
            $table->text('summary')->nullable();
            $table->timestamps();
        });

        Schema::create('coaching_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quality_evaluation_id')->nullable()->constrained('quality_evaluations')->nullOnDelete();
            $table->text('note');
            $table->text('action_items')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->enum('status', ['open', 'done'])->default('open');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coaching_notes');
        Schema::dropIfExists('quality_evaluations');
        Schema::dropIfExists('quality_form_criteria');
        Schema::dropIfExists('quality_evaluation_forms');
        Schema::dropIfExists('performance_evaluations');
        Schema::dropIfExists('agent_daily_stats');
        Schema::dropIfExists('kpi_targets');
        Schema::dropIfExists('kpis');
    }
};
