<?php

use App\Models\Repair;
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
        Schema::create('repair_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Repair::class)->nullable(false)->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name');
            $table->text('description');
            $table->double('price');
            $table->timestamps();
            $table->index(['repair_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_details');
    }
};
