<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Apimo API v3 `Agency` response schema (GET /agencies, GET /agencies/{agency_id}).
 *
 * Nullable `company`, `brand`, `city` and `district` objects are flattened to `{object}_{field}` columns.
 * Excluded: `users`, `sectors`, `parameters` (private) and `providers`.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->string('apimo_id')->unique();
            $table->string('reference')->nullable();
            $table->boolean('active');
            $table->string('name');
            $table->string('company_id')->nullable();
            $table->string('company_name')->nullable();
            $table->string('brand_id')->nullable();
            $table->string('brand_name')->nullable();
            $table->json('networks');
            $table->string('address')->nullable();
            $table->string('address_more')->nullable();
            $table->string('city_id')->nullable();
            $table->string('city_name')->nullable();
            $table->string('city_zipcode')->nullable();
            $table->string('district_id')->nullable();
            $table->string('district_name')->nullable();
            $table->string('country');
            $table->string('region')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->text('url')->nullable();
            $table->text('logo');
            $table->text('logo_svg')->nullable();
            $table->json('pictures');
            $table->text('picture')->nullable();
            $table->integer('quality');
            $table->string('currency');
            $table->text('timetable')->nullable();
            $table->json('rates');
            $table->json('partners');
            $table->json('stories');
            $table->string('subscription');
            $table->string('created_at');
            $table->string('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agencies');
    }
};
