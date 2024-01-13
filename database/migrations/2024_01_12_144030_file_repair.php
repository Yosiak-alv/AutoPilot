<?php

use App\Models\File;
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
        Schema::create('file_repair', function (Blueprint $table) {
           $table->foreignIdFor(Repair::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
           $table->foreignIdFor(File::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_repair');
    }
};
