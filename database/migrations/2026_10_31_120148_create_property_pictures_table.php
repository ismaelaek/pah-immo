<?php

declare(strict_types=1);

use App\Models\Property;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Apimo API v3 `PropertyPicture` (items of `Property.pictures`).
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('property_pictures', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Property::class)->constrained()->cascadeOnDelete();
            $table->integer('apimo_id');
            $table->integer('type_id');
            $table->integer('rank');
            $table->text('url');
            $table->integer('width_max');
            $table->integer('height_max');
            $table->string('internet');
            $table->string('print');
            $table->boolean('panorama');
            $table->string('child');
            $table->string('reference')->nullable();
            $table->json('comments');
            $table->boolean('ai_generated')->nullable();

            $table->unique(['property_id', 'apimo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_pictures');
    }
};
