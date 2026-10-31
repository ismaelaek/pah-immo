<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed, nextTick, ref, watch } from 'vue';
import PropertyCard from '@/components/PropertyCard.vue';
import type { Property } from '@/components/PropertyCard.vue';
import PropertyGallery from '@/components/PropertyGallery.vue';
import PropertyInquiryForm from '@/components/PropertyInquiryForm.vue';
import PropertyShowSkeleton from '@/components/PropertyShowSkeleton.vue';
import { useLocale } from '@/composables/useLocale';
import { usePropertyFormatting } from '@/composables/usePropertyFormatting';

defineOptions({
    layout: {
        darkNav: true,
    },
});

const props = withDefaults(
    defineProps<{
        property?: Property | null;
        similarProperties?: Property[];
        pending?: boolean;
    }>(),
    {
        property: null,
        similarProperties: () => [],
        pending: false,
    },
);

const { localized, t } = useLocale();
const { formatPrice, formatArea, formatAddress } = usePropertyFormatting();

const bodyCollapsedHeight = 360;
const promises = ['promise_reply', 'promise_visit', 'promise_support'] as const;

const body = ref<HTMLElement | null>(null);
const bodyIsLong = ref(false);
const bodyExpanded = ref(false);
const linkCopied = ref(false);

type KeyFact = {
    key: 'area' | 'bedrooms' | 'bathrooms' | 'type';
    value: string;
    label: string;
};

const keyFacts = computed<KeyFact[]>(() => {
    if (props.property === null) {
        return [];
    }

    const facts: (KeyFact | null)[] = [
        props.property.area === null
            ? null
            : {
                  key: 'area',
                  value: formatArea(props.property.area),
                  label: t('properties.area'),
              },
        props.property.bedrooms === null
            ? null
            : {
                  key: 'bedrooms',
                  value: String(props.property.bedrooms),
                  label: t('properties.bedrooms'),
              },
        props.property.bathrooms === null
            ? null
            : {
                  key: 'bathrooms',
                  value: String(props.property.bathrooms),
                  label: t('properties.bathrooms'),
              },
        {
            key: 'type',
            value: props.property.type.label,
            label: t('properties.type'),
        },
    ];

    return facts.filter((fact): fact is KeyFact => fact !== null);
});

const details = computed(() => {
    if (props.property === null) {
        return [];
    }

    return [
        { label: t('properties.reference'), value: props.property.reference },
        { label: t('properties.offer'), value: props.property.offerType.label },
        { label: t('properties.type'), value: props.property.type.label },
        { label: t('properties.price'), value: formatPrice(props.property) },
        { label: t('properties.city'), value: props.property.city },
        {
            label: t('properties.neighborhood'),
            value: props.property.neighborhood,
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

function scrollToContact(): void {
    document
        .getElementById('contact')
        ?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

async function share(): Promise<void> {
    if (props.property === null) {
        return;
    }

    const url = window.location.href;

    if (typeof navigator.share === 'function') {
        try {
            await navigator.share({ title: props.property.title, url });
        } catch {
            // The visitor closed the share sheet.
        }

        return;
    }

    await navigator.clipboard.writeText(url);
    linkCopied.value = true;
    setTimeout(() => (linkCopied.value = false), 2000);
}

watch(
    () => props.property?.slug,
    async () => {
        bodyExpanded.value = false;

        await nextTick();

        bodyIsLong.value =
            (body.value?.scrollHeight ?? 0) > bodyCollapsedHeight;
    },
    { immediate: true },
);
</script>

<template>
    <div class="bg-white pb-24 lg:pb-0">
        <Head v-if="property" :title="property.title">
            <meta
                head-key="description"
                name="description"
                :content="property.description"
            />
        </Head>
        <Head v-else-if="pending" :title="t('properties.title')" />
        <Head v-else :title="t('properties.missing')" />

        <PropertyShowSkeleton v-if="pending" />

        <template v-else-if="property">
            <section class="px-6 pt-4 sm:px-12 lg:px-20 lg:pt-8 xl:px-28">
                <div class="mx-auto max-w-[92rem]">
                    <div
                        class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between lg:gap-12"
                    >
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span
                                    class="rounded-full bg-brand-red px-4 py-1.5 text-xs font-semibold tracking-wide text-white uppercase"
                                >
                                    {{ property.offerType.label }}
                                </span>
                                <span
                                    class="rounded-full bg-neutral-950 px-4 py-1.5 text-xs font-semibold tracking-wide text-white uppercase"
                                >
                                    {{ property.type.label }}
                                </span>
                                <span
                                    class="rounded-full border border-neutral-200 px-4 py-1.5 text-xs font-medium text-neutral-500"
                                >
                                    {{
                                        t('properties.reference_short', {
                                            reference: property.reference,
                                        })
                                    }}
                                </span>
                            </div>

                            <h1
                                class="mt-5 max-w-4xl text-2xl leading-tight font-semibold tracking-tight text-balance text-neutral-950 sm:text-3xl lg:text-4xl"
                            >
                                {{ property.title }}
                            </h1>

                            <p
                                class="mt-4 inline-flex items-center gap-2 text-base text-neutral-500"
                            >
                                <svg
                                    class="size-5 shrink-0 text-brand-red"
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

                        <div class="flex shrink-0 items-center gap-3">
                            <p
                                class="text-3xl font-semibold tracking-tight text-neutral-950 sm:text-4xl"
                            >
                                {{ formatPrice(property) }}
                            </p>
                            <button
                                type="button"
                                class="flex size-12 items-center justify-center rounded-full border border-neutral-200 text-neutral-900 transition-colors hover:border-neutral-300 hover:bg-neutral-50"
                                :aria-label="t('properties.share')"
                                :title="
                                    linkCopied
                                        ? t('properties.link_copied')
                                        : t('properties.share')
                                "
                                @click="share"
                            >
                                <svg
                                    v-if="linkCopied"
                                    class="size-5 text-emerald-600"
                                    viewBox="0 0 20 20"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="m5 10.5 3 3 7-7"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    class="size-5"
                                    viewBox="0 0 20 20"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.6"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M10 3v9M6.5 6.5 10 3l3.5 3.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                    <path
                                        d="M5 10v5.5A1.5 1.5 0 0 0 6.5 17h7a1.5 1.5 0 0 0 1.5-1.5V10"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="mt-8 lg:mt-10">
                        <PropertyGallery
                            :images="property.images"
                            :alt="property.imageAlt"
                        />
                    </div>
                </div>
            </section>

            <section class="px-6 py-12 sm:px-12 lg:px-20 lg:py-20 xl:px-28">
                <div
                    class="mx-auto grid max-w-[92rem] gap-12 lg:grid-cols-[minmax(0,1fr)_23rem] lg:gap-16 xl:gap-24"
                >
                    <div class="flex min-w-0 flex-col gap-14 lg:gap-16">
                        <dl class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                            <div
                                v-for="fact in keyFacts"
                                :key="fact.key"
                                class="flex flex-col gap-4 rounded-3xl bg-[#F6F6F6] p-5"
                            >
                                <span
                                    class="flex size-11 items-center justify-center rounded-2xl bg-white text-brand-red shadow-sm"
                                    aria-hidden="true"
                                >
                                    <svg
                                        class="size-5"
                                        viewBox="0 0 20 20"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                    >
                                        <template v-if="fact.key === 'area'">
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
                                        </template>
                                        <template
                                            v-else-if="fact.key === 'bedrooms'"
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
                                        </template>
                                        <template
                                            v-else-if="fact.key === 'bathrooms'"
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
                                        </template>
                                        <template v-else>
                                            <path
                                                d="M3 9.5 10 4l7 5.5V16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5Z"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M8 17v-4.5h4V17"
                                                stroke-linejoin="round"
                                            />
                                        </template>
                                    </svg>
                                </span>
                                <div class="flex flex-col-reverse">
                                    <dt class="mt-0.5 text-sm text-neutral-500">
                                        {{ fact.label }}
                                    </dt>
                                    <dd
                                        class="truncate text-2xl font-semibold tracking-tight text-neutral-950"
                                    >
                                        {{ fact.value }}
                                    </dd>
                                </div>
                            </div>
                        </dl>

                        <div>
                            <h2
                                class="text-2xl font-semibold tracking-tight text-neutral-950 sm:text-3xl"
                            >
                                {{ t('properties.about') }}
                            </h2>
                            <p class="mt-6 text-lg leading-8 text-neutral-800">
                                {{ property.description }}
                            </p>

                            <div class="relative mt-6">
                                <div
                                    ref="body"
                                    class="overflow-hidden text-base leading-7 text-neutral-600 [&_a]:text-brand-red [&_a]:underline [&_h2]:mt-8 [&_h2]:mb-3 [&_h2]:text-xl [&_h2]:font-semibold [&_h2]:text-neutral-950 [&_h3]:mt-6 [&_h3]:mb-2 [&_h3]:text-lg [&_h3]:font-semibold [&_h3]:text-neutral-950 [&_ol]:list-decimal [&_ol]:pl-5 [&_p]:mb-4 [&_ul]:mb-4 [&_ul]:list-disc [&_ul]:pl-5"
                                    :style="
                                        bodyIsLong && !bodyExpanded
                                            ? {
                                                  maxHeight: `${bodyCollapsedHeight}px`,
                                              }
                                            : undefined
                                    "
                                    v-html="property.body ?? ''"
                                />
                                <div
                                    v-if="bodyIsLong && !bodyExpanded"
                                    class="pointer-events-none absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-white to-transparent"
                                />
                            </div>

                            <button
                                v-if="bodyIsLong"
                                type="button"
                                class="mt-4 inline-flex items-center gap-2 rounded-full border border-neutral-200 px-5 py-2.5 text-sm font-semibold text-neutral-900 transition-colors hover:border-neutral-300 hover:bg-neutral-50"
                                :aria-expanded="bodyExpanded"
                                @click="bodyExpanded = !bodyExpanded"
                            >
                                {{
                                    bodyExpanded
                                        ? t('properties.read_less')
                                        : t('properties.read_more')
                                }}
                                <svg
                                    class="size-4 transition-transform"
                                    :class="bodyExpanded ? 'rotate-180' : ''"
                                    viewBox="0 0 20 20"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M5 7.5 10 12.5 15 7.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </button>
                        </div>

                        <div v-if="property.equipment.length > 0">
                            <h2
                                class="text-2xl font-semibold tracking-tight text-neutral-950 sm:text-3xl"
                            >
                                {{ t('properties.equipment') }}
                            </h2>
                            <ul
                                class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-3"
                            >
                                <li
                                    v-for="equipment in property.equipment"
                                    :key="equipment.value"
                                    class="flex items-center gap-3 rounded-2xl border border-neutral-100 px-4 py-3.5 text-sm font-medium text-neutral-800"
                                >
                                    <span
                                        class="flex size-8 shrink-0 items-center justify-center rounded-full bg-brand-red/10 text-brand-red"
                                        aria-hidden="true"
                                    >
                                        <svg
                                            class="size-4"
                                            viewBox="0 0 20 20"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                d="m5.5 10.5 3 3 6-7"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </span>
                                    {{ equipment.label }}
                                </li>
                            </ul>
                        </div>

                        <div v-if="mapSrc">
                            <div
                                class="flex flex-wrap items-end justify-between gap-4"
                            >
                                <h2
                                    class="text-2xl font-semibold tracking-tight text-neutral-950 sm:text-3xl"
                                >
                                    {{ t('properties.location') }}
                                </h2>
                                <a
                                    v-if="mapUrl"
                                    :href="mapUrl"
                                    target="_blank"
                                    rel="noopener"
                                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand-red transition-opacity hover:opacity-80"
                                >
                                    {{ t('properties.view_on_map') }}
                                    <span aria-hidden="true">↗</span>
                                </a>
                            </div>
                            <div
                                class="relative mt-6 overflow-hidden rounded-[2rem] bg-neutral-100"
                            >
                                <iframe
                                    :src="mapSrc"
                                    :title="t('properties.location')"
                                    loading="lazy"
                                    class="h-80 w-full border-0 sm:h-96"
                                />
                                <p
                                    class="pointer-events-none absolute top-4 left-4 inline-flex max-w-[calc(100%-2rem)] items-center gap-2 rounded-full bg-white/95 px-4 py-2 text-sm font-semibold text-neutral-900 shadow-[0_8px_30px_rgba(15,23,42,0.15)] backdrop-blur"
                                >
                                    <span
                                        class="size-2 shrink-0 rounded-full bg-brand-red"
                                    />
                                    <span class="truncate">
                                        {{ formatAddress(property) }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div>
                            <h2
                                class="text-2xl font-semibold tracking-tight text-neutral-950 sm:text-3xl"
                            >
                                {{ t('properties.details') }}
                            </h2>
                            <dl
                                class="mt-6 grid border-t border-neutral-100 sm:grid-cols-2 sm:gap-x-12"
                            >
                                <div
                                    v-for="detail in details"
                                    :key="detail.label"
                                    class="flex items-center justify-between gap-4 border-b border-neutral-100 py-4 text-sm"
                                >
                                    <dt class="text-neutral-500">
                                        {{ detail.label }}
                                    </dt>
                                    <dd
                                        class="text-right font-semibold text-neutral-900"
                                    >
                                        {{ detail.value }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <aside class="hidden lg:block">
                        <div
                            class="sticky top-8 rounded-[2rem] bg-white p-7 shadow-[0_20px_60px_rgba(15,23,42,0.10)] ring-1 ring-neutral-100"
                        >
                            <p
                                class="text-xs font-semibold tracking-wide text-brand-red uppercase"
                            >
                                {{ property.offerType.label }}
                            </p>
                            <p
                                class="mt-2 text-4xl font-semibold tracking-tight text-neutral-950"
                            >
                                {{ formatPrice(property) }}
                            </p>
                            <p class="mt-2 text-sm text-neutral-500">
                                {{
                                    t('properties.reference_short', {
                                        reference: property.reference,
                                    })
                                }}
                            </p>

                            <div class="mt-6 flex flex-col gap-3">
                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center gap-2 rounded-full bg-brand-red px-6 py-4 text-sm font-semibold text-white transition-opacity hover:opacity-90"
                                    @click="scrollToContact"
                                >
                                    {{ t('properties.request_visit') }}
                                    <span aria-hidden="true">→</span>
                                </button>
                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center gap-2 rounded-full border border-neutral-200 px-6 py-3.5 text-sm font-semibold text-neutral-900 transition-colors hover:border-neutral-300 hover:bg-neutral-50"
                                    @click="share"
                                >
                                    {{
                                        linkCopied
                                            ? t('properties.link_copied')
                                            : t('properties.share')
                                    }}
                                </button>
                            </div>

                            <ul
                                class="mt-7 flex flex-col gap-3 border-t border-neutral-100 pt-6 text-sm text-neutral-600"
                            >
                                <li
                                    v-for="promise in promises"
                                    :key="promise"
                                    class="flex items-center gap-3"
                                >
                                    <svg
                                        class="size-4 shrink-0 text-emerald-600"
                                        viewBox="0 0 20 20"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        aria-hidden="true"
                                    >
                                        <path
                                            d="m5 10.5 3 3 7-7"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                    {{ t(`properties.${promise}`) }}
                                </li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </section>

            <section
                id="contact"
                class="scroll-mt-6 px-6 pb-16 sm:px-12 lg:px-20 lg:pb-24 xl:px-28"
            >
                <div
                    class="mx-auto grid max-w-[92rem] gap-2 overflow-hidden rounded-[2.5rem] bg-neutral-950 p-2 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.2fr)]"
                >
                    <div
                        class="flex flex-col justify-between gap-10 p-6 text-white sm:p-10 lg:p-14"
                    >
                        <div>
                            <p
                                class="text-xs font-semibold tracking-wide text-brand-red uppercase"
                            >
                                {{
                                    t('properties.reference_short', {
                                        reference: property.reference,
                                    })
                                }}
                            </p>
                            <h2
                                class="mt-4 text-3xl leading-tight font-semibold tracking-tight sm:text-4xl lg:text-5xl"
                            >
                                {{ t('properties.contact_title') }}
                            </h2>
                            <p
                                class="mt-5 max-w-md text-base leading-7 text-white/65"
                            >
                                {{
                                    t('properties.contact_body', {
                                        reference: property.reference,
                                    })
                                }}
                            </p>
                        </div>

                        <div
                            class="flex items-center gap-4 rounded-3xl bg-white/5 p-3 ring-1 ring-white/10"
                        >
                            <img
                                v-if="property.images[0]"
                                :src="property.images[0].small"
                                :alt="property.imageAlt"
                                loading="lazy"
                                class="size-20 shrink-0 rounded-2xl object-cover"
                            />
                            <div class="min-w-0">
                                <p class="truncate font-semibold">
                                    {{ property.title }}
                                </p>
                                <p class="mt-1 truncate text-sm text-white/60">
                                    {{ formatAddress(property) }}
                                </p>
                                <p
                                    class="mt-1 text-sm font-semibold text-white"
                                >
                                    {{ formatPrice(property) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2rem] bg-white p-6 sm:p-10">
                        <PropertyInquiryForm
                            :title="property.title"
                            :reference="property.reference"
                        />
                    </div>
                </div>
            </section>

            <section
                v-if="similarProperties.length > 0"
                class="bg-[#F8F8F8] px-6 py-16 sm:px-12 lg:px-20 lg:py-24 xl:px-28"
            >
                <div class="mx-auto max-w-[92rem]">
                    <div
                        class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between"
                    >
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
                        <Link
                            :href="localized('/properties')"
                            prefetch
                            class="inline-flex w-fit items-center gap-2 rounded-full border border-neutral-200 bg-white px-6 py-3 text-sm font-semibold text-neutral-900 transition-colors hover:border-neutral-300"
                        >
                            {{ t('featured.see_all') }}
                            <span aria-hidden="true">→</span>
                        </Link>
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

            <div
                class="fixed inset-x-0 bottom-0 z-30 border-t border-neutral-100 bg-white/90 px-6 py-3 backdrop-blur-xl sm:px-12 lg:hidden"
            >
                <div class="flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <p
                            class="text-xs font-semibold tracking-wide text-brand-red uppercase"
                        >
                            {{ property.offerType.label }}
                        </p>
                        <p
                            class="truncate text-lg font-semibold text-neutral-950"
                        >
                            {{ formatPrice(property) }}
                        </p>
                    </div>
                    <button
                        type="button"
                        class="shrink-0 rounded-full bg-brand-red px-6 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90"
                        @click="scrollToContact"
                    >
                        {{ t('properties.request_visit') }}
                    </button>
                </div>
            </div>
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
