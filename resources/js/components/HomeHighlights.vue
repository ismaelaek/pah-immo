<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useLocale } from '@/composables/useLocale';

const { localized, t, trans } = useLocale();

const slideSources = [
    {
        webp: '/assets/images/highlights-1.webp',
        jpg: '/assets/images/highlights-1.jpg',
    },
    {
        webp: '/assets/images/living-room.webp',
        jpg: '/assets/images/living-room.jpg',
    },
    {
        webp: '/assets/images/kitchen.webp',
        jpg: '/assets/images/kitchen.jpg',
    },
    {
        webp: '/assets/images/bedroom.webp',
        jpg: '/assets/images/bedroom.jpg',
    },
] as const;

const slides = computed(() => {
    const alts = trans<string[]>('highlights.slides') ?? [];

    return slideSources.map((slide, index) => ({
        ...slide,
        alt: alts[index] ?? '',
    }));
});

const currentSlide = ref(0);
const activeSlide = computed(() => slides.value[currentSlide.value] ?? slides.value[0]);

function goToSlide(index: number): void {
    const total = slides.value.length;
    currentSlide.value = ((index % total) + total) % total;
}

const stats = computed(() => {
    const labels = trans<string[]>('highlights.stats') ?? [];

    return [
        { value: '100%', label: labels[0] ?? '' },
        { value: '3', label: labels[1] ?? '' },
        { value: '4', label: labels[2] ?? '' },
        { value: '5★', label: labels[3] ?? '' },
    ];
});
</script>

<template>
    <section class="bg-white px-5 py-20 sm:px-8 lg:px-12 lg:py-28">
        <div class="mx-auto max-w-[92rem]">
            <h2
                class="mb-10 max-w-lg text-3xl leading-tight font-semibold tracking-tight text-neutral-950 sm:text-4xl lg:mb-14"
            >
                {{ t('highlights.title') }}
            </h2>

            <div
                class="mb-16 grid items-start gap-8 lg:grid-cols-12 lg:gap-10"
            >
                <div class="relative lg:col-span-6">
                    <div class="overflow-hidden rounded-[2rem]">
                        <picture>
                            <source
                                :srcset="activeSlide.webp"
                                type="image/webp"
                            />
                            <img
                                :src="activeSlide.jpg"
                                :alt="activeSlide.alt"
                                class="aspect-[4/3] w-full object-cover"
                            />
                        </picture>
                    </div>

                    <div
                        class="absolute inset-x-5 bottom-5 flex items-center justify-between"
                    >
                        <div
                            class="flex gap-2 rounded-full bg-white px-3 py-2 shadow-md"
                        >
                            <button
                                v-for="(slide, index) in slides"
                                :key="slide.jpg"
                                type="button"
                                class="size-2.5 rounded-full transition-colors"
                                :class="
                                    currentSlide === index
                                        ? 'bg-brand-red'
                                        : 'bg-neutral-300'
                                "
                                :aria-label="t('highlights.slide', { number: index + 1 })"
                                @click="goToSlide(index)"
                            />
                        </div>

                        <div class="flex gap-2">
                            <button
                                type="button"
                                class="flex size-11 items-center justify-center rounded-full bg-white text-neutral-900 shadow-md transition-colors hover:bg-neutral-50"
                                :aria-label="t('highlights.prev')"
                                @click="goToSlide(currentSlide - 1)"
                            >
                                <svg
                                    class="size-4"
                                    viewBox="0 0 20 20"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        d="M12.5 5 7.5 10l5 5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </button>
                            <button
                                type="button"
                                class="flex size-11 items-center justify-center rounded-full bg-white text-neutral-900 shadow-md transition-colors hover:bg-neutral-50"
                                :aria-label="t('highlights.next')"
                                @click="goToSlide(currentSlide + 1)"
                            >
                                <svg
                                    class="size-4"
                                    viewBox="0 0 20 20"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        d="m7.5 5 5 5-5 5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    class="flex flex-col justify-center gap-6 lg:col-span-3 lg:pt-4"
                >
                    <h3
                        class="text-3xl leading-tight font-semibold tracking-tight text-neutral-950"
                    >
                        {{ t('highlights.subtitle') }}
                    </h3>
                    <p class="text-sm leading-6 text-neutral-500">
                        {{ t('highlights.body') }}
                    </p>
                    <Link
                        :href="localized('/about')"
                        prefetch
                        class="inline-flex w-fit cursor-pointer rounded-full border border-neutral-200 px-6 py-2.5 text-sm font-medium text-neutral-900 transition-colors hover:bg-neutral-50"
                    >
                        {{ t('highlights.more') }}
                    </Link>
                    <p class="text-sm leading-6 text-neutral-500">
                        {{ t('highlights.aside') }}
                    </p>
                </div>

                <div class="flex flex-col gap-5 lg:col-span-3">
                    <div class="overflow-hidden rounded-[2rem]">
                        <picture>
                            <source
                                srcset="/assets/images/highlights-2.webp"
                                type="image/webp"
                            />
                            <img
                                src="/assets/images/highlights-2.jpg"
                                :alt="t('highlights.side_alt')"
                                class="aspect-[4/3] w-full object-cover"
                            />
                        </picture>
                    </div>
                    <p
                        class="text-center text-lg font-medium tracking-tight text-brand-red"
                    >
                        {{ t('highlights.from_price') }}
                    </p>
                    <Link
                        :href="localized('/properties')"
                        prefetch
                        class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-full bg-brand-red px-6 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90"
                    >
                        {{ t('highlights.see_properties') }}
                        <span aria-hidden="true">→</span>
                    </Link>
                </div>
            </div>

            <dl
                class="grid gap-8 border-t border-neutral-100 pt-10 sm:grid-cols-2 lg:grid-cols-4 lg:gap-0"
            >
                <div
                    v-for="(stat, index) in stats"
                    :key="stat.label"
                    class="text-center lg:px-8"
                    :class="index > 0 ? 'lg:border-l lg:border-neutral-200' : ''"
                >
                    <dt
                        class="text-4xl font-semibold tracking-tight text-brand-red lg:text-5xl"
                    >
                        {{ stat.value }}
                    </dt>
                    <dd class="mt-2 text-sm text-neutral-500">
                        {{ stat.label }}
                    </dd>
                </div>
            </dl>
        </div>
    </section>
</template>
