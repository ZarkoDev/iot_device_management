<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domain\Alerting\Enums\AlertType;
use App\Domain\Alerting\Enums\AlertSeverity;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->foreignId('sensor_data_id')->nullable();
            $table->string('alert_type')->default(AlertType::TEMPERATURE_WARNING->value);
            $table->string('severity')->default(AlertSeverity::WARNING->value);
            $table->decimal('temperature', 5, 2)->nullable();
            $table->decimal('threshold_min', 5, 2)->nullable();
            $table->decimal('threshold_max', 5, 2)->nullable();
            $table->integer('timeout_minutes')->nullable();
            $table->text('message');
            $table->string('condition_name')->nullable();
            $table->text('condition_description')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['device_id']);
            $table->index(['device_id', 'alert_type']);
            $table->index('alert_type');
            $table->index('severity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
