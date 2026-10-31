<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Replaces the original `properties` table with the Apimo API v3 `Property` response schema
 * (GET /agencies/{agency_id}/properties).
 *
 * Nested scalar objects (`agreement`, `region`, `city`, `district`, `area`, `plot`, `price`, `residence`, `view`,
 * `construction`, `floor`, `heating`, `water`, `style`, `boat`, `life_annuity`, `auction`, `shop`) are flattened
 * to `{object}_{field}` columns. Arrays of scalars or objects without their own table are stored as JSON.
 * Private-scope (`properties_private`), diffusion (`exchanges`, `options`) and CRM (`referrals`) fields are excluded.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('properties');

        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->integer('apimo_id');
            $table->string('reference')->nullable();
            $table->integer('agency');
            $table->integer('quality');
            $table->integer('agency_co_id')->nullable();
            $table->integer('brand')->nullable();
            $table->string('sector')->nullable();
            $table->json('user')->nullable();
            $table->integer('step')->index();
            $table->integer('status')->index();
            $table->integer('group');
            $table->integer('parent')->nullable()->index();
            $table->integer('category')->index();
            $table->integer('subcategory')->nullable();
            $table->string('name')->nullable();
            $table->integer('type')->index();
            $table->integer('subtype');

            $table->integer('agreement_type')->nullable();
            $table->string('agreement_reference')->nullable();
            $table->date('agreement_start_at')->nullable();
            $table->date('agreement_end_at')->nullable();

            $table->string('block_name')->nullable();
            $table->string('lot_reference')->nullable();
            $table->string('cadastre_reference')->nullable();
            $table->string('stairs_reference')->nullable();
            $table->string('address')->nullable();
            $table->string('address_more')->nullable();
            $table->boolean('publish_address');
            $table->string('country');

            $table->integer('region_id')->nullable();
            $table->string('region_name')->nullable();
            $table->integer('city_id')->nullable()->index();
            $table->string('city_name')->nullable();
            $table->string('city_zipcode')->nullable();
            $table->integer('district_id')->nullable();
            $table->string('district_name')->nullable();

            $table->double('longitude');
            $table->double('latitude');
            $table->integer('radius');
            $table->integer('altitude');

            $table->integer('area_unit');
            $table->double('area_value')->nullable();
            $table->double('area_total')->nullable();
            $table->double('area_weighted')->nullable();

            $table->string('plot_net_floor')->nullable();
            $table->integer('plot_land_type')->nullable();
            $table->string('plot_width')->nullable();
            $table->string('plot_height')->nullable();
            $table->boolean('plot_serviced_plot');

            $table->double('rooms');
            $table->integer('bedrooms');
            $table->integer('sleeps');

            $table->double('price_value')->nullable();
            $table->double('price_max')->nullable();
            $table->double('price_fees')->nullable();
            $table->integer('price_unit')->nullable();
            $table->integer('price_period')->nullable();
            $table->boolean('price_hide');
            $table->double('price_inventory')->nullable();
            $table->double('price_deposit')->nullable();
            $table->string('price_currency');
            $table->double('price_commission')->nullable();
            $table->double('price_transfer_tax')->nullable();
            $table->double('price_contribution')->nullable();
            $table->double('price_pension')->nullable();
            $table->double('price_tenant')->nullable();
            $table->boolean('price_vat')->nullable();
            $table->double('price_original_value')->nullable();
            $table->string('price_original_currency')->nullable();
            $table->double('price_commission_owner')->nullable();
            $table->double('price_commission_customer')->nullable();
            $table->double('price_sold')->nullable();
            $table->date('price_sold_at')->nullable();

            $table->json('rates');

            $table->integer('residence_id')->nullable();
            $table->integer('residence_type')->nullable();
            $table->double('residence_fees')->nullable();
            $table->integer('residence_period')->nullable();
            $table->integer('residence_lots')->nullable();

            $table->integer('view_type')->nullable();
            $table->json('view_landscape');

            $table->json('construction_method')->nullable();
            $table->string('construction_year')->nullable();
            $table->string('construction_step')->nullable();
            $table->string('construction_renovation_year')->nullable();
            $table->string('construction_renovation_cost')->nullable();

            $table->integer('floor_type')->nullable();
            $table->string('floor_value')->nullable();
            $table->string('floor_levels')->nullable();
            $table->string('floor_floors')->nullable();

            $table->integer('heating_device')->nullable();
            $table->json('heating_devices')->nullable();
            $table->integer('heating_access')->nullable();
            $table->integer('heating_type')->nullable();
            $table->json('heating_types')->nullable();

            $table->integer('water_hot_device')->nullable();
            $table->integer('water_hot_access')->nullable();
            $table->integer('water_waste')->nullable();

            $table->integer('condition')->nullable();
            $table->integer('standing')->nullable();
            $table->string('style_name')->nullable();
            $table->string('twinned')->nullable();
            $table->string('facades')->nullable();
            $table->string('length')->nullable();
            $table->string('height')->nullable();
            $table->text('url')->nullable();
            $table->string('availability')->nullable();
            $table->date('available_at')->nullable();
            $table->date('delivered_at')->nullable();

            $table->json('activities');
            $table->json('orientations');
            $table->json('tags');
            $table->json('tags_customized');
            $table->json('financial');

            $table->double('boat_length')->nullable();
            $table->double('boat_beam')->nullable();
            $table->double('boat_draft')->nullable();
            $table->integer('boat_guests')->nullable();
            $table->integer('boat_crew')->nullable();
            $table->double('boat_cruising_speed')->nullable();
            $table->integer('boat_builder_id')->nullable();
            $table->string('boat_builder_name')->nullable();

            $table->double('life_annuity_length')->nullable();
            $table->json('life_annuity_occupants')->nullable();
            $table->double('life_annuity_market_value')->nullable();
            $table->double('life_annuity_contribution')->nullable();
            $table->double('life_annuity_pension')->nullable();

            $table->string('auction_start_at')->nullable();
            $table->string('auction_end_at')->nullable();
            $table->double('auction_bid_min')->nullable();

            $table->double('shop_area')->nullable();
            $table->double('shop_window_width')->nullable();
            $table->double('shop_window_height')->nullable();

            $table->string('created_at');
            $table->string('created_by');
            $table->string('updated_at');
            $table->string('updated_by');

            $table->unique(['agency', 'apimo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');

        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('slug')->unique();
            $table->string('reference')->unique();
            $table->json('description');
            $table->json('body');
            $table->string('type')->index();
            $table->string('offer_type')->index();
            $table->unsignedBigInteger('price');
            $table->unsignedInteger('area');
            $table->unsignedTinyInteger('bedrooms')->nullable();
            $table->unsignedTinyInteger('bathrooms')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city')->index();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('equipment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
