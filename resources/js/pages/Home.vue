<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import type { BlogPost } from '@/components/BlogPostCard.vue';
import HomeBestValue from '@/components/HomeBestValue.vue';
import HomeFaq from '@/components/HomeFaq.vue';
import HomeFeaturedProperties from '@/components/HomeFeaturedProperties.vue';
import HomeHighlights from '@/components/HomeHighlights.vue';
import HomeLatestArticles from '@/components/HomeLatestArticles.vue';
import { useLocale } from '@/composables/useLocale';

defineOptions({
    layout: {
        overlayHeader: true,
    },
});

defineProps<{
    propertyTypes: {
        value: string;
        label: string;
    }[];
    latestPosts: BlogPost[];
}>();

const { t } = useLocale();

const priceKeys = ['all', 'under_250', '250_500', '500_1m', 'over_1m'] as const;
const locationKeys = ['all', 'paris', 'lyon', 'bordeaux', 'nice'] as const;
const roomKeys = ['studio', '1', '2', '3', '4'] as const;

const prices = computed(() =>
    priceKeys.map((value) => ({ value, label: t(`home.prices.${value}`) })),
);
const locations = computed(() =>
    locationKeys.map((value) => ({ value, label: t(`home.locations.${value}`) })),
);
const rooms = computed(() =>
    roomKeys.map((value) => ({ value, label: t(`home.room_options.${value}`) })),
);

const propertyType = ref('');
const price = ref('');
const location = ref('');
const roomCount = ref('2');

function searchProperties(): void {
    // Search will be wired to listings in a later page.
}
</script>

<template>
    <div>
        <Head :title="t('home.title')">
            <meta
                head-key="description"
                name="description"
                :content="t('home.description')"
            />
        </Head>

        <section>
            <div class="relative isolate min-h-svh overflow-hidden">
                <picture>
                    <source
                        srcset="/assets/images/home-hero.webp"
                        type="image/webp"
                    />
                    <img
                        src="/assets/images/home-hero.jpg"
                        :alt="t('home.hero_alt')"
                        class="absolute inset-0 size-full object-cover"
                    />
                </picture>

                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/25 to-black/20"
                />

                <div
                    class="relative flex min-h-svh flex-col justify-end gap-8 px-5 pt-28 pb-5 sm:px-8 lg:gap-10 lg:px-12 lg:pb-8"
                >
                    <div class="max-w-3xl">
                        <h1
                            class="max-w-3xl text-4xl leading-[1.05] font-semibold tracking-tight text-white text-shadow-lg sm:text-5xl lg:text-7xl"
                        >
                            {{ t('home.hero_title') }}
                        </h1>
                    </div>

                    <form
                        class="rounded-[1.75rem] bg-white p-5 shadow-[0_20px_60px_rgba(15,23,42,0.12)] sm:p-7 lg:rounded-[2rem] lg:p-8"
                        @submit.prevent="searchProperties"
                    >
                        <h2
                            class="mb-6 text-2xl font-semibold tracking-tight text-neutral-950 lg:text-3xl"
                        >
                            {{ t('home.search_title') }}
                        </h2>

                        <div
                            class="grid items-end gap-4 sm:grid-cols-2 xl:grid-cols-[repeat(4,minmax(0,1fr))_auto]"
                        >
                            <label class="relative flex flex-col gap-2">
                                <span
                                    class="text-sm font-medium text-neutral-800"
                                >
                                    {{ t('home.type') }}
                                </span>
                                <select
                                    v-model="propertyType"
                                    name="property_type"
                                    class="h-12 appearance-none rounded-full bg-[#F3F3F3] px-5 pr-12 text-sm outline-none focus:ring-2 focus:ring-neutral-200"
                                    :class="
                                        propertyType
                                            ? 'text-neutral-900'
                                            : 'text-neutral-500'
                                    "
                                >
                                    <option disabled value="">
                                        {{ t('home.all_types') }}
                                    </option>
                                    <option
                                        v-for="type in propertyTypes"
                                        :key="type.value"
                                        :value="type.value"
                                    >
                                        {{ type.label }}
                                    </option>
                                </select>
                                <svg
                                    class="pointer-events-none absolute right-5 bottom-4 size-4 text-neutral-400"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M5.2 7.5a.75.75 0 0 1 1.06 0L10 11.24l3.74-3.74a.75.75 0 1 1 1.06 1.06l-4.27 4.27a.75.75 0 0 1-1.06 0L5.2 8.56a.75.75 0 0 1 0-1.06Z"
                                    />
                                </svg>
                            </label>

                            <label class="relative flex flex-col gap-2">
                                <span
                                    class="text-sm font-medium text-neutral-800"
                                >
                                    {{ t('home.price') }}
                                </span>
                                <select
                                    v-model="price"
                                    name="price"
                                    class="h-12 appearance-none rounded-full bg-[#F3F3F3] px-5 pr-12 text-sm text-neutral-500 outline-none focus:ring-2 focus:ring-neutral-200"
                                >
                                    <option disabled value="">
                                        {{ t('home.prices.all') }}
                                    </option>
                                    <option
                                        v-for="option in prices"
                                        :key="option.value"
                                        :value="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select>
                                <svg
                                    class="pointer-events-none absolute right-5 bottom-4 size-4 text-neutral-400"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M5.2 7.5a.75.75 0 0 1 1.06 0L10 11.24l3.74-3.74a.75.75 0 1 1 1.06 1.06l-4.27 4.27a.75.75 0 0 1-1.06 0L5.2 8.56a.75.75 0 0 1 0-1.06Z"
                                    />
                                </svg>
                            </label>

                            <label class="relative flex flex-col gap-2">
                                <span
                                    class="text-sm font-medium text-neutral-800"
                                >
                                    {{ t('home.city') }}
                                </span>
                                <select
                                    v-model="location"
                                    name="location"
                                    class="h-12 appearance-none rounded-full bg-[#F3F3F3] px-5 pr-12 text-sm text-neutral-500 outline-none focus:ring-2 focus:ring-neutral-200"
                                >
                                    <option disabled value="">
                                        {{ t('home.all_cities') }}
                                    </option>
                                    <option
                                        v-for="option in locations"
                                        :key="option.value"
                                        :value="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select>
                                <svg
                                    class="pointer-events-none absolute right-5 bottom-4 size-4 text-neutral-400"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M5.2 7.5a.75.75 0 0 1 1.06 0L10 11.24l3.74-3.74a.75.75 0 1 1 1.06 1.06l-4.27 4.27a.75.75 0 0 1-1.06 0L5.2 8.56a.75.75 0 0 1 0-1.06Z"
                                    />
                                </svg>
                            </label>

                            <label class="relative flex flex-col gap-2">
                                <span
                                    class="text-sm font-medium text-neutral-800"
                                >
                                    {{ t('home.rooms') }}
                                </span>
                                <select
                                    v-model="roomCount"
                                    name="rooms"
                                    class="h-12 appearance-none rounded-full bg-[#F3F3F3] px-5 pr-12 text-sm text-neutral-900 outline-none focus:ring-2 focus:ring-neutral-200"
                                >
                                    <option
                                        v-for="option in rooms"
                                        :key="option.value"
                                        :value="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select>
                                <svg
                                    class="pointer-events-none absolute right-5 bottom-4 size-4 text-neutral-400"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M5.2 7.5a.75.75 0 0 1 1.06 0L10 11.24l3.74-3.74a.75.75 0 1 1 1.06 1.06l-4.27 4.27a.75.75 0 0 1-1.06 0L5.2 8.56a.75.75 0 0 1 0-1.06Z"
                                    />
                                </svg>
                            </label>

                            <button
                                type="submit"
                                class="h-12 cursor-pointer justify-self-end rounded-full bg-brand-red px-7 text-sm font-semibold whitespace-nowrap text-white transition-opacity hover:opacity-90"
                            >
                                {{ t('home.search') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <HomeHighlights />
        <HomeFeaturedProperties />
        <HomeBestValue />
        <HomeLatestArticles :posts="latestPosts" />
        <HomeFaq />
    </div>
</template>
