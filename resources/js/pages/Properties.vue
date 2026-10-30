<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, onUnmounted, ref } from 'vue';
import PropertyCard from '@/components/PropertyCard.vue';
import PropertyCardSkeleton from '@/components/PropertyCardSkeleton.vue';
import type { Property, PropertyOption } from '@/components/PropertyCard.vue';
import PropertySearchForm from '@/components/PropertySearchForm.vue';
import { useLocale } from '@/composables/useLocale';
import { filledPropertyFilters } from '@/composables/usePropertySearchFields';
import type { PropertyFilters } from '@/composables/usePropertySearchFields';

defineOptions({
    layout: {
        darkNav: true,
    },
});

const props = defineProps<{
    properties: {
        data: Property[];
        total: number;
        prev_page_url: string | null;
        next_page_url: string | null;
    };
    filters: PropertyFilters;
    propertyTypes: PropertyOption[];
    cities: string[];
}>();

const { localized, t, tc } = useLocale();

const skeletonCount = 6;
const loading = ref(false);

/**
 * Show skeleton cards while a search or another results page loads.
 */
const stopListeningToStart = router.on('start', (event) => {
    loading.value =
        event.detail.visit.url.pathname === localized('/properties');
});
const stopListeningToFinish = router.on('finish', () => {
    loading.value = false;
});

onUnmounted(() => {
    stopListeningToStart();
    stopListeningToFinish();
});

const hasFilters = computed(() =>
    Object.values(props.filters).some((value) => value !== ''),
);

const mapUrl = computed(() => {
    const query = new URLSearchParams(filledPropertyFilters(props.filters));

    return localized(`/properties/map${query.size > 0 ? `?${query}` : ''}`);
});
</script>

<template>
    <div>
        <Head :title="t('properties.title')">
            <meta
                head-key="description"
                name="description"
                :content="t('properties.description')"
            />
        </Head>

        <section
            class="bg-white px-6 pt-6 pb-20 sm:px-12 lg:px-20 lg:pt-10 lg:pb-28 xl:px-28"
        >
            <div class="mx-auto max-w-[92rem]">
                <h1 class="sr-only">{{ t('properties.title') }}</h1>

                <PropertySearchForm
                    :property-types="propertyTypes"
                    :cities="cities"
                    :filters="filters"
                    preserve-scroll
                />

                <div
                    class="mt-14 mb-10 flex flex-wrap items-end justify-between gap-4 lg:mt-20 lg:mb-14"
                >
                    <h2
                        class="text-3xl leading-tight font-semibold tracking-tight text-neutral-950 sm:text-4xl lg:text-5xl"
                    >
                        <span
                            v-if="loading"
                            class="inline-block h-10 w-56 animate-pulse rounded-2xl bg-neutral-200 align-middle sm:h-12 lg:h-14"
                        />
                        <template v-else>
                            {{ tc('properties.results', properties.total) }}
                        </template>
                    </h2>
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-3">
                        <Link
                            v-if="hasFilters"
                            :href="localized('/properties?reset=1')"
                            preserve-scroll
                            class="text-sm font-medium text-brand-red transition-opacity hover:opacity-80"
                        >
                            {{ t('properties.reset') }}
                        </Link>
                        <Link
                            :href="mapUrl"
                            prefetch
                            class="inline-flex items-center gap-2 rounded-full bg-neutral-950 px-6 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90"
                        >
                            <svg
                                class="size-4 shrink-0"
                                viewBox="0 0 20 20"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.6"
                                aria-hidden="true"
                            >
                                <path
                                    d="M7.5 3.5 2.5 5.5v11l5-2 5 2 5-2v-11l-5 2-5-2Z"
                                    stroke-linejoin="round"
                                />
                                <path d="M7.5 3.5v11M12.5 5.5v11" />
                            </svg>
                            {{ t('properties.map_cta') }}
                        </Link>
                    </div>
                </div>

                <div
                    v-if="loading"
                    class="grid gap-8 md:grid-cols-2 xl:grid-cols-3"
                    role="status"
                    :aria-label="t('properties.loading')"
                >
                    <PropertyCardSkeleton
                        v-for="index in skeletonCount"
                        :key="index"
                    />
                </div>
                <div
                    v-else-if="properties.data.length > 0"
                    class="grid gap-8 md:grid-cols-2 xl:grid-cols-3"
                >
                    <PropertyCard
                        v-for="property in properties.data"
                        :key="property.slug"
                        :property="property"
                    />
                </div>
                <p v-else class="text-sm leading-6 text-neutral-500">
                    {{ t('properties.empty') }}
                </p>

                <nav
                    v-if="properties.prev_page_url || properties.next_page_url"
                    class="mt-14 flex items-center justify-between gap-4"
                >
                    <Link
                        v-if="properties.prev_page_url"
                        :href="properties.prev_page_url"
                        prefetch
                        class="inline-flex items-center gap-2 rounded-full bg-[#F3F3F3] px-6 py-3 text-sm font-semibold text-neutral-900 transition-opacity hover:opacity-80"
                    >
                        <span aria-hidden="true">←</span>
                        {{ t('properties.previous') }}
                    </Link>
                    <span v-else />
                    <Link
                        v-if="properties.next_page_url"
                        :href="properties.next_page_url"
                        prefetch
                        class="inline-flex items-center gap-2 rounded-full bg-brand-red px-6 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90"
                    >
                        {{ t('properties.next') }}
                        <span aria-hidden="true">→</span>
                    </Link>
                </nav>
            </div>
        </section>
    </div>
</template>
