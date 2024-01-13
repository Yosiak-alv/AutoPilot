<?php

use App\Models\Car;
use App\Models\Image;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_image', function (Blueprint $table) {
           $table->foreignIdFor(Car::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
           $table->foreignIdFor(Image::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_image');
    }
};
