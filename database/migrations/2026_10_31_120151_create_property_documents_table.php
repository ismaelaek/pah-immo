<?php

declare(strict_types=1);

use App\Models\Property;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Apimo API v3 `PropertyDocument` (items of `Property.documents`).
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('property_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Property::class)->constrained()->cascadeOnDelete();
            $table->string('apimo_id');
            $table->integer('owner')->index();
            $table->string('contact')->nullable();
            $table->string('name');
            $table->text('url');
            $table->text('download_url');
            $table->string('filesize');
            $table->string('type_id')->nullable();
            $table->string('internet');
            $table->string('child');
            $table->string('extranet');
            $table->string('private');
            $table->string('created_at');
            $table->string('updated_at');

            $table->unique(['property_id', 'apimo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_documents');
    }
};
