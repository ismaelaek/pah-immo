<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import PropertyCard from '@/components/PropertyCard.vue';
import type { Property } from '@/components/PropertyCard.vue';
import PropertyGallery from '@/components/PropertyGallery.vue';
import PropertyInquiryForm from '@/components/PropertyInquiryForm.vue';
import { useLocale } from '@/composables/useLocale';
import { usePropertyFormatting } from '@/composables/usePropertyFormatting';

const props = withDefaults(
    defineProps<{
        property: Property | null;
        similarProperties?: Property[];
    }>(),
    {
        similarProperties: () => [],
    },
);

const { localized, t, tc } = useLocale();
const { formatPrice, formatAddress } = usePropertyFormatting();

const details = computed(() => {
    if (props.property === null) {
        return [];
    }

    return [
        {
            key: 'reference',
            label: t('properties.reference'),
            value: props.property.reference,
            icon: 'reference',
        },
        {
            key: 'offer',
            label: t('properties.offer'),
            value: props.property.offerType.label,
            icon: 'offer',
        },
        {
            key: 'type',
            label: t('properties.type'),
            value: props.property.type.label,
            icon: 'type',
        },
        {
            key: 'area',
            label: t('properties.area'),
            value: `${props.property.area} m²`,
            icon: 'area',
        },
        {
            key: 'bedrooms',
            label: t('properties.bedrooms'),
            value:
                props.property.bedrooms === null
                    ? null
                    : tc('property.bedrooms', props.property.bedrooms),
            icon: 'bedrooms',
        },
        {
            key: 'bathrooms',
            label: t('properties.bathrooms'),
            value:
                props.property.bathrooms === null
                    ? null
                    : tc('property.bathrooms', props.property.bathrooms),
            icon: 'bathrooms',
        },
        {
            key: 'city',
            label: t('properties.city'),
            value: props.property.city,
            icon: 'city',
        },
        {
            key: 'neighborhood',
            label: t('properties.neighborhood'),
            value: props.property.neighborhood,
            icon: 'neighborhood',
        },
    ].filter((detail) => detail.value !== null && detail.value !== '');
});

const mapSrc = computed(() => {
    if (props.property?.latitude == null || props.property.longitude == null) {
        return null;
    }

    const { latitude, longitude } = props.property;
    const pad = 0.012;

    return `https://www.openstreetmap.org/export/embed.html?bbox=${longitude - pad}%2C${latitude - pad}%2C${longitude + pad}%2C${latitude + pad}&layer=mapnik&marker=${latitude}%2C${longitude}`;
});

const mapUrl = computed(() => {
    if (props.property?.latitude == null || props.property.longitude == null) {
        return null;
    }

    const { latitude, longitude } = props.property;

    return `https://www.openstreetmap.org/?mlat=${latitude}&mlon=${longitude}#map=16/${latitude}/${longitude}`;
});
</script>

<template>
    <div class="bg-white">
        <Head v-if="property" :title="property.title">
            <meta
                head-key="description"
                name="description"
                :content="property.description"
            />
        </Head>
        <Head v-else :title="t('properties.missing')" />

        <template v-if="property">
            <PropertyGallery
                :images="property.images"
                :alt="property.imageAlt"
            />

            <section class="px-5 py-10 sm:px-8 lg:px-12 lg:py-16">
                <div class="mx-auto max-w-[92rem]">
                    <Link
                        :href="localized('/properties')"
                        prefetch
                        class="text-sm font-medium text-brand-red transition-opacity hover:opacity-80"
                    >
                        ← {{ t('properties.back') }}
                    </Link>

                    <div
                        class="mt-6 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between"
                    >
                        <div class="min-w-0">
                            <div class="flex flex-wrap gap-2">
                                <span
                                    class="rounded-full bg-brand-red px-4 py-1.5 text-sm font-medium text-white shadow-sm"
                                >
                                    {{ property.offerType.label }}
                                </span>
                                <span
                                    class="rounded-full bg-[#F3F3F3] px-4 py-1.5 text-sm font-medium text-neutral-900"
                                >
                                    {{ property.type.label }}
                                </span>
                            </div>

                            <h1
                                class="mt-4 text-3xl leading-[1.1] font-semibold tracking-tight text-neutral-950 sm:text-4xl lg:text-5xl"
                            >
                                {{ property.title }}
                            </h1>

                            <p
                                class="mt-3 inline-flex items-center gap-1.5 text-sm text-neutral-500 sm:text-base"
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
                                        d="M10 17s6-5.1 6-9.2A6 6 0 1 0 4 7.8C4 11.9 10 17 10 17Z"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                    <circle cx="10" cy="7.8" r="1.8" />
                                </svg>
                                {{ formatAddress(property) }}
                            </p>
                        </div>

                        <p
                            class="text-3xl font-semibold tracking-tight text-brand-red sm:text-4xl"
                        >
                            {{ formatPrice(property) }}
                        </p>
                    </div>

                    <div
                        class="mt-10 grid gap-10 lg:mt-14 lg:grid-cols-[minmax(0,1fr)_24rem] lg:items-start lg:gap-16"
                    >
                        <div class="flex flex-col gap-12">
                            <div>
                                <h2
                                    class="text-2xl font-semibold tracking-tight text-neutral-950 sm:text-3xl"
                                >
                                    {{ t('properties.details') }}
                                </h2>
                                <dl
                                    class="mt-6 grid gap-3 sm:grid-cols-2"
                                >
                                    <div
                                        v-for="detail in details"
                                        :key="detail.key"
                                        class="flex items-center gap-4 rounded-[1.25rem] bg-[#F3F3F3] px-5 py-4"
                                    >
                                        <span
                                            class="flex size-11 shrink-0 items-center justify-center rounded-full bg-white text-brand-red shadow-sm"
                                            aria-hidden="true"
                                        >
                                            <svg
                                                v-if="detail.icon === 'area'"
                                                class="size-5"
                                                viewBox="0 0 20 20"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.6"
                                            >
                                                <rect
                                                    x="3.5"
                                                    y="3.5"
                                                    width="13"
                                                    height="13"
                                                    rx="1.5"
                                                />
                                                <path
                                                    d="M7 3.5v13M3.5 8h13"
                                                    stroke-linecap="round"
                                                />
                                            </svg>
                                            <svg
                                                v-else-if="
                                                    detail.icon === 'bedrooms'
                                                "
                                                class="size-5"
                                                viewBox="0 0 20 20"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.6"
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
                                            <svg
                                                v-else-if="
                                                    detail.icon === 'bathrooms'
                                                "
                                                class="size-5"
                                                viewBox="0 0 20 20"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.6"
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
                                            <svg
                                                v-else-if="
                                                    detail.icon === 'city' ||
                                                    detail.icon ===
                                                        'neighborhood'
                                                "
                                                class="size-5"
                                                viewBox="0 0 20 20"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.6"
                                            >
                                                <path
                                                    d="M10 17s6-5.1 6-9.2A6 6 0 1 0 4 7.8C4 11.9 10 17 10 17Z"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                                <circle cx="10" cy="7.8" r="1.8" />
                                            </svg>
                                            <svg
                                                v-else
                                                class="size-5"
                                                viewBox="0 0 20 20"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.6"
                                            >
                                                <path
                                                    d="M4 7.2 10 4l6 3.2v8.6L10 19l-6-3.2V7.2Z"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                                <path
                                                    d="M10 10.2v8.6M4 7.2l6 3 6-3"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                            </svg>
                                        </span>
                                        <div class="min-w-0">
                                            <dt class="text-xs text-neutral-500">
                                                {{ detail.label }}
                                            </dt>
                                            <dd
                                                class="truncate text-sm font-semibold text-neutral-950"
                                            >
                                                {{ detail.value }}
                                            </dd>
                                        </div>
                                    </div>
                                </dl>
                            </div>

                            <div>
                                <h2
                                    class="text-2xl font-semibold tracking-tight text-neutral-950 sm:text-3xl"
                                >
                                    {{ t('properties.about') }}
                                </h2>
                                <div
                                    class="mt-6 space-y-4 text-base leading-7 text-neutral-600 [&_a]:text-brand-red [&_h2]:text-xl [&_h2]:font-semibold [&_h2]:text-neutral-950 [&_h3]:text-lg [&_h3]:font-semibold [&_h3]:text-neutral-950 [&_ol]:list-decimal [&_ol]:pl-5 [&_p]:mb-4 [&_ul]:list-disc [&_ul]:pl-5"
                                    v-html="
                                        property.body ?? property.description
                                    "
                                />
                            </div>

                            <div v-if="property.equipment.length > 0">
                                <h2
                                    class="text-2xl font-semibold tracking-tight text-neutral-950 sm:text-3xl"
                                >
                                    {{ t('properties.equipment') }}
                                </h2>
                                <ul class="mt-6 flex flex-wrap gap-2">
                                    <li
                                        v-for="equipment in property.equipment"
                                        :key="equipment.value"
                                        class="inline-flex items-center gap-1.5 rounded-full bg-[#F3F3F3] px-4 py-2 text-sm font-medium text-neutral-800"
                                    >
                                        <svg
                                            class="size-4 text-brand-red"
                                            viewBox="0 0 20 20"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.6"
                                            aria-hidden="true"
                                        >
                                            <path
                                                d="m5.5 10.5 3 3 6-7"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                        {{ equipment.label }}
                                    </li>
                                </ul>
                            </div>

                            <div v-if="mapSrc">
                                <h2
                                    class="text-2xl font-semibold tracking-tight text-neutral-950 sm:text-3xl"
                                >
                                    {{ t('properties.location') }}
                                </h2>
                                <div
                                    class="mt-6 overflow-hidden rounded-[1.75rem] bg-neutral-100"
                                >
                                    <iframe
                                        :src="mapSrc"
                                        :title="t('properties.location')"
                                        class="h-80 w-full border-0"
                                        loading="lazy"
                                    />
                                </div>
                                <a
                                    v-if="mapUrl"
                                    :href="mapUrl"
                                    target="_blank"
                                    rel="noopener"
                                    class="mt-4 inline-flex items-center gap-1.5 text-sm font-medium text-brand-red transition-opacity hover:opacity-80"
                                >
                                    {{ t('properties.view_on_map') }}
                                    <span aria-hidden="true">↗</span>
                                </a>
                            </div>
                        </div>

                        <aside class="lg:sticky lg:top-8">
                            <PropertyInquiryForm
                                :title="property.title"
                                :reference="property.reference"
                            />
                        </aside>
                    </div>
                </div>
            </section>

            <section
                v-if="similarProperties.length > 0"
                class="bg-[#F8F8F8] px-5 py-16 sm:px-8 lg:px-12 lg:py-24"
            >
                <div class="mx-auto max-w-[92rem]">
                    <div class="max-w-xl">
                        <h2
                            class="text-3xl leading-tight font-semibold tracking-tight text-neutral-950 sm:text-4xl"
                        >
                            {{ t('properties.similar') }}
                        </h2>
                        <p class="mt-4 text-sm leading-6 text-neutral-500">
                            {{ t('properties.similar_intro') }}
                        </p>
                    </div>

                    <div class="mt-10 grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                        <PropertyCard
                            v-for="similar in similarProperties"
                            :key="similar.slug"
                            :property="similar"
                        />
                    </div>
                </div>
            </section>
        </template>

        <div
            v-else
            class="mx-auto max-w-3xl px-5 pt-16 pb-24 text-center sm:px-8"
        >
            <h1 class="text-3xl font-semibold tracking-tight text-neutral-950">
                {{ t('properties.missing') }}
            </h1>
            <Link
                :href="localized('/properties')"
                prefetch
                class="mt-6 inline-flex rounded-full bg-brand-red px-6 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90"
            >
                {{ t('properties.back') }}
            </Link>
        </div>
    </div>
</template>
