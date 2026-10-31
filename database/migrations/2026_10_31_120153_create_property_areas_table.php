<?php

declare(strict_types=1);

use App\Models\Property;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Apimo API v3 `PropertyArea` (items of `Property.areas`: rooms). `floor`, `lot` and `beds` objects are flattened.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('property_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Property::class)->constrained()->cascadeOnDelete();
            $table->integer('type');
            $table->integer('number');
            $table->double('area')->nullable();
            $table->integer('flooring')->nullable();
            $table->double('ceiling_height')->nullable();
            $table->integer('floor_type')->nullable();
            $table->integer('floor_value')->nullable();
            $table->json('orientations');
            $table->json('comments');
            $table->integer('lot_type')->nullable();
            $table->integer('lot_rank')->nullable();
            $table->json('lot_name');
            $table->integer('beds_single')->nullable();
            $table->integer('beds_double')->nullable();
            $table->double('price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_areas');
    }
};
