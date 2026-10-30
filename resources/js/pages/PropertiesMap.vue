<script setup lang="ts">
import { Head, InfiniteScroll, Link, router } from '@inertiajs/vue3';
import { computed, nextTick, onBeforeUnmount, ref } from 'vue';
import type { Property, PropertyOption } from '@/components/PropertyCard.vue';
import PropertyCardCompact from '@/components/PropertyCardCompact.vue';
import PropertyCardCompactSkeleton from '@/components/PropertyCardCompactSkeleton.vue';
import PropertyMap from '@/components/PropertyMap.vue';
import type { MapBounds } from '@/components/PropertyMap.vue';
import PropertySearchBar from '@/components/PropertySearchBar.vue';
import { useLocale } from '@/composables/useLocale';
import {
    filledPropertyFilters,
    propertySearchQuery,
} from '@/composables/usePropertySearchFields';
import type { PropertyFilters } from '@/composables/usePropertySearchFields';

defineOptions({
    layout: {
        bare: true,
    },
});

const props = defineProps<{
    properties: {
        data: Property[];
    };
    total: number;
    bounds: MapBounds | null;
    filters: PropertyFilters;
    propertyTypes: PropertyOption[];
    cities: string[];
}>();

const { localized, t, tc } = useLocale();

const searchDelay = 400;
const skeletonCount = 4;
const nextPageSkeletonCount = 2;

const list = ref<HTMLElement | null>(null);
const propertyMap = ref<InstanceType<typeof PropertyMap> | null>(null);
const hoveredSlug = ref<string | null>(null);
const selectedSlug = ref<string | null>(null);
const loading = ref(false);
const searchAsMapMoves = ref(true);
const pendingBounds = ref<MapBounds | null>(null);

let searchTimer: ReturnType<typeof setTimeout> | undefined;

const activeSlug = computed(() => hoveredSlug.value ?? selectedSlug.value);

const listUrl = computed(() => {
    const query = new URLSearchParams(filledPropertyFilters(props.filters));

    return localized(`/properties${query.size > 0 ? `?${query}` : ''}`);
});

function serializeBounds(bounds: MapBounds): string {
    return [bounds.south, bounds.west, bounds.north, bounds.east]
        .map((coordinate) => coordinate.toFixed(5))
        .join(',');
}

function visit(
    query: Record<string, string>,
    { fitResults }: { fitResults: boolean },
): void {
    router.get(localized('/properties/map'), query, {
        only: ['properties', 'total', 'bounds', 'filters'],
        reset: ['properties'],
        preserveState: true,
        preserveScroll: true,
        replace: true,
        showProgress: false,
        onStart: () => {
            loading.value = true;
        },
        onSuccess: () => {
            list.value?.scrollTo({ top: 0 });

            if (fitResults) {
                void nextTick(() => propertyMap.value?.fitToProperties());
            }
        },
        onFinish: () => {
            loading.value = false;
        },
    });
}

function searchArea(): void {
    clearTimeout(searchTimer);

    if (pendingBounds.value === null) {
        return;
    }

    const bounds = serializeBounds(pendingBounds.value);

    pendingBounds.value = null;

    visit(
        { ...filledPropertyFilters(props.filters), bounds },
        { fitResults: false },
    );
}

function searchFilters(filters: PropertyFilters): void {
    clearTimeout(searchTimer);
    pendingBounds.value = null;
    selectedSlug.value = null;

    visit(propertySearchQuery(filters), { fitResults: true });
}

function onMapMoved(bounds: MapBounds): void {
    pendingBounds.value = bounds;

    if (!searchAsMapMoves.value) {
        return;
    }

    clearTimeout(searchTimer);
    searchTimer = setTimeout(searchArea, searchDelay);
}

async function selectFromMap(slug: string): Promise<void> {
    selectedSlug.value = slug;

    await nextTick();

    list.value
        ?.querySelector(`[data-slug="${CSS.escape(slug)}"]`)
        ?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function selectFromList(slug: string): void {
    selectedSlug.value = slug;
    propertyMap.value?.openPropertyPopup(slug);
}

onBeforeUnmount(() => clearTimeout(searchTimer));
</script>

<template>
    <div class="flex min-h-svh flex-col lg:h-svh lg:overflow-hidden">
        <Head :title="t('properties.map_title')">
            <meta
                head-key="description"
                name="description"
                :content="t('properties.map_description')"
            />
        </Head>

        <header
            class="relative z-20 flex shrink-0 flex-col gap-4 px-5 py-5 lg:flex-row lg:items-center lg:gap-8 lg:px-10"
        >
            <Link :href="localized('/')" class="shrink-0" prefetch>
                <img
                    src="/assets/logo.png"
                    alt="PAH Immo"
                    class="h-14 w-auto object-contain lg:h-16"
                />
            </Link>
            <div class="w-full lg:mx-auto lg:max-w-5xl lg:flex-1">
                <PropertySearchBar
                    :property-types="propertyTypes"
                    :cities="cities"
                    :filters="filters"
                    :loading="loading"
                    @search="searchFilters"
                />
            </div>
        </header>

        <section class="flex flex-1 flex-col lg:grid lg:min-h-0 lg:grid-cols-5">
            <div
                ref="list"
                class="order-last px-5 py-8 sm:px-8 lg:order-first lg:col-span-2 lg:overflow-y-auto lg:px-10 lg:py-10"
                :aria-busy="loading"
            >
                <div class="flex items-center justify-between gap-4">
                    <h1
                        class="text-xl leading-tight font-semibold tracking-tight text-neutral-950 sm:text-2xl"
                    >
                        {{ t('properties.map_title') }}
                    </h1>
                    <Link
                        :href="listUrl"
                        prefetch
                        class="inline-flex shrink-0 items-center gap-2 rounded-full border border-neutral-200 px-4 py-2 text-sm font-semibold text-neutral-900 transition-colors hover:border-neutral-300 hover:bg-neutral-50"
                    >
                        <span aria-hidden="true">←</span>
                        {{ t('properties.back') }}
                    </Link>
                </div>
                <p class="mt-3 h-6 text-sm leading-6 text-neutral-500">
                    <span
                        v-if="loading"
                        class="inline-block h-4 w-40 animate-pulse rounded-full bg-neutral-200 align-middle"
                    />
                    <template v-else>
                        {{ tc('properties.map_results', total) }}
                    </template>
                </p>

                <div v-if="loading" class="mt-8 flex flex-col gap-4">
                    <PropertyCardCompactSkeleton
                        v-for="index in skeletonCount"
                        :key="index"
                    />
                </div>

                <InfiniteScroll
                    v-else-if="properties.data.length > 0"
                    data="properties"
                    preserve-url
                    :buffer="400"
                    class="mt-8 flex flex-col gap-4"
                >
                    <div
                        v-for="property in properties.data"
                        :key="property.slug"
                        :data-slug="property.slug"
                        @mouseenter="hoveredSlug = property.slug"
                        @mouseleave="hoveredSlug = null"
                        @focusin="hoveredSlug = property.slug"
                        @focusout="hoveredSlug = null"
                    >
                        <PropertyCardCompact
                            :property="property"
                            :active="activeSlug === property.slug"
                            @select="selectFromList(property.slug)"
                        />
                    </div>

                    <template #loading>
                        <div class="flex flex-col gap-4" role="status">
                            <PropertyCardCompactSkeleton
                                v-for="index in nextPageSkeletonCount"
                                :key="index"
                            />
                        </div>
                    </template>
                </InfiniteScroll>

                <p v-else class="mt-8 text-sm leading-6 text-neutral-500">
                    {{ t('properties.map_empty') }}
                </p>
            </div>

            <div
                class="h-[45svh] px-3 pb-3 lg:col-span-3 lg:h-full lg:pr-4 lg:pb-4 lg:pl-0"
            >
                <PropertyMap
                    ref="propertyMap"
                    :properties="properties.data"
                    :initial-bounds="bounds"
                    :active-slug="activeSlug"
                    :loading="loading"
                    @select="selectFromMap"
                    @moved="onMapMoved"
                >
                    <div
                        class="absolute bottom-6 left-1/2 z-[1000] flex -translate-x-1/2 flex-col items-center gap-3"
                    >
                        <button
                            v-if="
                                !searchAsMapMoves && pendingBounds && !loading
                            "
                            type="button"
                            class="rounded-full bg-neutral-950 px-5 py-2.5 text-sm font-semibold whitespace-nowrap text-white shadow-[0_12px_40px_rgba(15,23,42,0.25)] transition-opacity hover:opacity-90"
                            @click="searchArea"
                        >
                            {{ t('properties.map_search_area') }}
                        </button>
                        <label
                            class="inline-flex cursor-pointer items-center gap-2.5 rounded-full bg-white px-4 py-2 text-sm font-medium whitespace-nowrap text-neutral-900 shadow-[0_12px_40px_rgba(15,23,42,0.18)]"
                        >
                            <input
                                v-model="searchAsMapMoves"
                                type="checkbox"
                                class="size-4 accent-brand-red"
                            />
                            {{ t('properties.map_search_as_move') }}
                        </label>
                    </div>
                </PropertyMap>
            </div>
        </section>
    </div>
</template>
