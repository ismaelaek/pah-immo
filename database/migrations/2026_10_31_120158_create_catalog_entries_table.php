<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Apimo API v3 `CatalogEntry` items (GET /catalogs/{catalog_id}). `catalog` holds the `catalog_id` path parameter.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('catalog_entries', function (Blueprint $table) {
            $table->id();
            $table->string('catalog');
            $table->integer('apimo_id');
            $table->string('culture');
            $table->string('name');
            $table->string('name_plurial')->nullable();
            $table->string('value')->nullable();

            $table->unique(['catalog', 'apimo_id', 'culture']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_entries');
    }
};
