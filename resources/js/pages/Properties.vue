<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import PropertyCard from '@/components/PropertyCard.vue';
import type { Property, PropertyOption } from '@/components/PropertyCard.vue';
import PropertySearchForm from '@/components/PropertySearchForm.vue';
import type { PropertyFilters } from '@/composables/usePropertySearchFields';
import { useLocale } from '@/composables/useLocale';

defineOptions({
    layout: {
        overlayHeader: true,
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

const hasFilters = computed(() =>
    Object.values(props.filters).some((value) => value !== ''),
);
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

        <section>
            <div class="relative isolate overflow-hidden bg-neutral-900">
                <picture>
                    <source
                        srcset="/assets/images/hero-properties.webp"
                        type="image/webp"
                    />
                    <img
                        src="/assets/images/hero-properties.jpg"
                        :alt="t('properties.hero_alt')"
                        class="absolute inset-0 size-full object-cover"
                    />
                </picture>

                <div class="absolute inset-0 bg-black/40" />

                <div
                    class="relative flex min-h-80 items-center justify-center px-5 pt-28 pb-16 sm:min-h-96 sm:px-8 lg:min-h-[28rem] lg:px-12"
                >
                    <div class="mx-auto max-w-3xl text-center">
                        <h1
                            class="text-4xl leading-[1.05] font-semibold tracking-tight text-white text-shadow-lg sm:text-5xl lg:text-6xl"
                        >
                            {{ t('properties.hero_title') }}
                        </h1>
                        <p
                            class="mx-auto mt-5 max-w-xl text-sm leading-6 text-white/80 sm:text-base sm:leading-7"
                        >
                            {{ t('properties.description') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section
            class="bg-white px-6 py-20 sm:px-12 lg:px-20 lg:py-28 xl:px-28"
        >
            <div class="mx-auto max-w-[92rem]">
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
                        {{ tc('properties.results', properties.total) }}
                    </h2>
                    <Link
                        v-if="hasFilters"
                        :href="localized('/properties')"
                        preserve-scroll
                        class="text-sm font-medium text-brand-red transition-opacity hover:opacity-80"
                    >
                        {{ t('properties.reset') }}
                    </Link>
                </div>

                <div
                    v-if="properties.data.length > 0"
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
