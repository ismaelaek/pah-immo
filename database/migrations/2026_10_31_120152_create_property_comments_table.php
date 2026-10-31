<?php

declare(strict_types=1);

use App\Models\Property;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Apimo API v3 `PropertyComment` (items of `Property.comments`: descriptions per language).
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('property_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Property::class)->constrained()->cascadeOnDelete();
            $table->string('language');
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('hook')->nullable();
            $table->text('comment')->nullable();
            $table->text('comment_full')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_comments');
    }
};
