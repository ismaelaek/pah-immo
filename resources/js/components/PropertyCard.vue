<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useLocale } from '@/composables/useLocale';
import { usePropertyFormatting } from '@/composables/usePropertyFormatting';

export type PropertyOption = {
    value: string;
    label: string;
};

export type PropertyImage = {
    small: string;
    medium: string;
    large: string;
};

export type Property = {
    slug: string;
    reference: string;
    title: string;
    description: string;
    body: string | null;
    type: PropertyOption;
    offerType: PropertyOption;
    price: number;
    area: number;
    bedrooms: number | null;
    bathrooms: number | null;
    neighborhood: string | null;
    city: string;
    latitude: number | null;
    longitude: number | null;
    equipment: PropertyOption[];
    images: PropertyImage[];
    image: string | null;
    imageAlt: string;
};

const props = defineProps<{
    property: Property;
}>();

const { localized, t, tc } = useLocale();
const { formatPrice, formatAddress } = usePropertyFormatting();

const href = computed(() => localized(`/properties/${props.property.slug}`));
</script>

<template>
    <article
        class="flex flex-col overflow-hidden rounded-[1.75rem] bg-white shadow-[0_12px_40px_rgba(15,23,42,0.06)]"
    >
        <Link
            :href="href"
            prefetch
            class="group relative block overflow-hidden"
        >
            <img
                v-if="property.image"
                :src="property.image"
                :alt="property.imageAlt"
                loading="lazy"
                class="aspect-[16/10] w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
            />
            <div v-else class="aspect-[16/10] w-full bg-neutral-100" />

            <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                <span
                    class="rounded-full bg-brand-red px-4 py-1.5 text-sm font-medium text-white shadow-sm"
                >
                    {{ property.offerType.label }}
                </span>
                <span
                    class="rounded-full bg-white px-4 py-1.5 text-sm font-medium text-neutral-900 shadow-sm"
                >
                    {{ property.type.label }}
                </span>
            </div>
        </Link>

        <div class="flex flex-1 flex-col gap-3 px-5 py-5">
            <h3 class="text-2xl font-semibold tracking-tight text-neutral-950">
                {{ property.title }}
            </h3>

            <p class="inline-flex items-start gap-1.5 text-sm text-neutral-500">
                <svg
                    class="mt-0.5 size-4 shrink-0"
                    viewBox="0 0 20 20"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.6"
                    aria-hidden="true"
                >
                    <path
                        d="M10 17s6-5.1 6-9.2A6 6 0 1 0 4 7.8C4 11.9 10 17 10 17Z"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                    <circle cx="10" cy="7.8" r="1.8" />
                </svg>
                {{ formatAddress(property) }}
            </p>

            <p
                class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-neutral-500"
            >
                <span class="inline-flex items-center gap-1.5">
                    <svg
                        class="size-4"
                        viewBox="0 0 20 20"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                        aria-hidden="true"
                    >
                        <rect x="3.5" y="3.5" width="13" height="13" rx="1.5" />
                        <path d="M7 3.5v13M3.5 8h13" stroke-linecap="round" />
                    </svg>
                    {{ property.area }}&nbsp;m²
                </span>
                <template v-if="property.bedrooms !== null">
                    <span class="text-neutral-300">·</span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg
                            class="size-4"
                            viewBox="0 0 20 20"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                            aria-hidden="true"
                        >
                            <path
                                d="M2.5 16.5v-5.2h15v5.2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <path
                                d="M2.5 11.3V9.2A2.2 2.2 0 0 1 4.7 7h4.1A2.2 2.2 0 0 1 11 9.2v2.1"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <path
                                d="M2.5 16.5H17.5M4 16.5v1.2M16 16.5v1.2"
                                stroke-linecap="round"
                            />
                        </svg>
                        {{ tc('property.bedrooms', property.bedrooms) }}
                    </span>
                </template>
                <template v-if="property.bathrooms !== null">
                    <span class="text-neutral-300">·</span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg
                            class="size-4"
                            viewBox="0 0 20 20"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                            aria-hidden="true"
                        >
                            <path
                                d="M4 11h12v3.5A1.5 1.5 0 0 1 14.5 16h-9A1.5 1.5 0 0 1 4 14.5V11Z"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <path
                                d="M4 11V8.5A2.5 2.5 0 0 1 6.5 6H9"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <path
                                d="M13 6.5v2M16 9V7.5"
                                stroke-linecap="round"
                            />
                        </svg>
                        {{ tc('property.bathrooms', property.bathrooms) }}
                    </span>
                </template>
            </p>

            <div class="mt-auto flex items-center justify-between gap-3">
                <p class="text-lg font-semibold text-brand-red sm:text-xl">
                    {{ formatPrice(property) }}
                </p>
                <Link
                    :href="href"
                    prefetch
                    class="inline-flex shrink-0 cursor-pointer items-center gap-1.5 rounded-full bg-brand-red px-5 py-2.5 text-sm font-semibold text-white transition-opacity hover:opacity-90"
                >
                    {{ t('featured.see_property') }}
                    <span aria-hidden="true">→</span>
                </Link>
            </div>
        </div>
    </article>
</template>
