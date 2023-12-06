<?php

use App\Models\Branch;
use App\Models\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('image',255);
            $table->string('plates');
            $table->string('VIN');
            $table->double('current_mileage');
            $table->year('year');
            $table->foreignIdFor(Model::class)->nullable(false)->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignIdFor(Branch::class)->nullable(false)->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
