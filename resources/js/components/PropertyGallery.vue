<script setup lang="ts">
import { computed, nextTick, onUnmounted, ref, watch } from 'vue';
import type { PropertyImage } from '@/components/PropertyCard.vue';
import { useLocale } from '@/composables/useLocale';

const props = defineProps<{
    images: PropertyImage[];
    alt: string;
}>();

const { t } = useLocale();

const lightboxOpen = ref(false);
const lightboxIndex = ref(0);
const carouselIndex = ref(0);
const carousel = ref<HTMLElement | null>(null);
const thumbnails = ref<HTMLElement | null>(null);

const total = computed(() => props.images.length);
const tiles = computed(() => props.images.slice(1, 5));

function photoAlt(index: number): string {
    return `${props.alt} – ${index + 1}`;
}

function openLightbox(index = 0): void {
    if (total.value === 0) {
        return;
    }

    lightboxIndex.value = index;
    lightboxOpen.value = true;
}

function closeLightbox(): void {
    lightboxOpen.value = false;
}

function lightboxGo(step: number): void {
    lightboxIndex.value =
        (lightboxIndex.value + step + total.value) % total.value;
}

function onCarouselScroll(): void {
    const element = carousel.value;

    if (element === null || element.clientWidth === 0) {
        return;
    }

    carouselIndex.value = Math.round(element.scrollLeft / element.clientWidth);
}

function onKeydown(event: KeyboardEvent): void {
    if (event.key === 'Escape') {
        closeLightbox();
    }

    if (event.key === 'ArrowLeft') {
        lightboxGo(-1);
    }

    if (event.key === 'ArrowRight') {
        lightboxGo(1);
    }
}

watch(lightboxOpen, (open) => {
    document.documentElement.style.overflow = open ? 'hidden' : '';

    if (open) {
        window.addEventListener('keydown', onKeydown);
    } else {
        window.removeEventListener('keydown', onKeydown);
    }
});

watch(lightboxIndex, async (index) => {
    await nextTick();

    thumbnails.value
        ?.querySelector<HTMLElement>(`[data-index="${index}"]`)
        ?.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
            inline: 'center',
        });
});

onUnmounted(() => {
    document.documentElement.style.overflow = '';
    window.removeEventListener('keydown', onKeydown);
});
</script>

<template>
    <div>
        <div
            v-if="total === 0"
            class="aspect-[16/9] rounded-[2rem] bg-neutral-100"
        />

        <template v-else>
            <!-- Mobile: swipeable carousel -->
            <div class="relative -mx-6 sm:-mx-12 lg:hidden">
                <div
                    ref="carousel"
                    class="flex snap-x snap-mandatory [scrollbar-width:none] overflow-x-auto [&::-webkit-scrollbar]:hidden"
                    @scroll.passive="onCarouselScroll"
                >
                    <button
                        v-for="(image, index) in images"
                        :key="image.large"
                        type="button"
                        class="w-full shrink-0 snap-center"
                        :aria-label="
                            t('properties.open_photo', { number: index + 1 })
                        "
                        @click="openLightbox(index)"
                    >
                        <img
                            :src="image.large"
                            :alt="photoAlt(index)"
                            :loading="index === 0 ? 'eager' : 'lazy'"
                            class="aspect-[4/3] w-full object-cover"
                        />
                    </button>
                </div>
                <p
                    class="pointer-events-none absolute right-4 bottom-4 rounded-full bg-black/60 px-3 py-1.5 text-xs font-semibold text-white backdrop-blur"
                >
                    {{ carouselIndex + 1 }} / {{ total }}
                </p>
            </div>

            <!-- Desktop: bento grid -->
            <div
                class="relative hidden h-[34rem] gap-2 overflow-hidden rounded-[2rem] lg:grid"
                :class="
                    tiles.length === 0
                        ? 'grid-cols-1'
                        : tiles.length === 1
                          ? 'grid-cols-2'
                          : 'grid-cols-4 grid-rows-2'
                "
            >
                <button
                    type="button"
                    class="group relative overflow-hidden"
                    :class="tiles.length > 1 ? 'col-span-2 row-span-2' : ''"
                    :aria-label="t('properties.open_photo', { number: 1 })"
                    @click="openLightbox(0)"
                >
                    <img
                        :src="images[0]?.large"
                        :alt="photoAlt(0)"
                        class="size-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.03]"
                    />
                    <span
                        class="absolute inset-0 bg-black/0 transition-colors duration-300 group-hover:bg-black/10"
                    />
                </button>

                <button
                    v-for="(image, index) in tiles"
                    :key="image.large"
                    type="button"
                    class="group relative overflow-hidden"
                    :class="
                        tiles.length === 2 ||
                        (tiles.length === 3 && index === 0)
                            ? 'col-span-2'
                            : ''
                    "
                    :aria-label="
                        t('properties.open_photo', { number: index + 2 })
                    "
                    @click="openLightbox(index + 1)"
                >
                    <img
                        :src="image.medium"
                        :alt="photoAlt(index + 1)"
                        loading="lazy"
                        class="size-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.05]"
                    />
                    <span
                        class="absolute inset-0 bg-black/0 transition-colors duration-300 group-hover:bg-black/10"
                    />
                </button>

                <button
                    v-if="total > 1"
                    type="button"
                    class="absolute right-5 bottom-5 inline-flex items-center gap-2 rounded-full bg-white/95 px-4 py-2.5 text-sm font-semibold text-neutral-950 shadow-[0_8px_30px_rgba(15,23,42,0.18)] backdrop-blur transition-transform hover:scale-[1.03]"
                    @click="openLightbox(0)"
                >
                    <svg
                        class="size-4"
                        viewBox="0 0 20 20"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                        aria-hidden="true"
                    >
                        <rect x="3" y="3" width="6" height="6" rx="1.2" />
                        <rect x="11" y="3" width="6" height="6" rx="1.2" />
                        <rect x="3" y="11" width="6" height="6" rx="1.2" />
                        <rect x="11" y="11" width="6" height="6" rx="1.2" />
                    </svg>
                    {{ t('properties.show_all_photos', { count: total }) }}
                </button>
            </div>
        </template>

        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="opacity-0"
                leave-active-class="transition duration-200 ease-in"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="lightboxOpen"
                    class="fixed inset-0 z-[60] flex flex-col bg-neutral-950/95 backdrop-blur-sm"
                    role="dialog"
                    aria-modal="true"
                    :aria-label="t('properties.gallery')"
                >
                    <div
                        class="flex items-center justify-between px-5 py-4 text-white sm:px-8"
                    >
                        <p class="text-sm font-medium text-white/70">
                            {{ lightboxIndex + 1 }} / {{ total }}
                        </p>
                        <button
                            type="button"
                            class="flex size-11 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20"
                            :aria-label="t('properties.gallery_close')"
                            @click="closeLightbox"
                        >
                            <svg
                                class="size-4"
                                viewBox="0 0 20 20"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <path
                                    d="M6 6l8 8M14 6l-8 8"
                                    stroke-linecap="round"
                                />
                            </svg>
                        </button>
                    </div>

                    <div
                        class="relative flex min-h-0 flex-1 items-center justify-center px-5 sm:px-20"
                        @click.self="closeLightbox"
                    >
                        <Transition
                            mode="out-in"
                            enter-active-class="transition duration-200 ease-out"
                            enter-from-class="scale-[0.98] opacity-0"
                            leave-active-class="transition duration-150 ease-in"
                            leave-to-class="opacity-0"
                        >
                            <img
                                :key="lightboxIndex"
                                :src="images[lightboxIndex]?.large"
                                :alt="photoAlt(lightboxIndex)"
                                class="max-h-full max-w-full rounded-2xl object-contain"
                            />
                        </Transition>

                        <template v-if="total > 1">
                            <button
                                type="button"
                                class="absolute top-1/2 left-3 flex size-12 -translate-y-1/2 items-center justify-center rounded-full bg-white text-neutral-950 shadow-lg transition-transform hover:scale-105 sm:left-6"
                                :aria-label="t('properties.gallery_prev')"
                                @click="lightboxGo(-1)"
                            >
                                <svg
                                    class="size-4"
                                    viewBox="0 0 20 20"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    aria-hidden="true"
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
                                class="absolute top-1/2 right-3 flex size-12 -translate-y-1/2 items-center justify-center rounded-full bg-white text-neutral-950 shadow-lg transition-transform hover:scale-105 sm:right-6"
                                :aria-label="t('properties.gallery_next')"
                                @click="lightboxGo(1)"
                            >
                                <svg
                                    class="size-4"
                                    viewBox="0 0 20 20"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="m7.5 5 5 5-5 5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </button>
                        </template>
                    </div>

                    <div
                        v-if="total > 1"
                        ref="thumbnails"
                        class="flex [scrollbar-width:none] justify-start gap-2 overflow-x-auto px-5 py-5 sm:justify-center sm:px-8 [&::-webkit-scrollbar]:hidden"
                    >
                        <button
                            v-for="(image, index) in images"
                            :key="image.small"
                            type="button"
                            :data-index="index"
                            class="shrink-0 overflow-hidden rounded-xl ring-2 transition-all"
                            :class="
                                index === lightboxIndex
                                    ? 'opacity-100 ring-white'
                                    : 'opacity-50 ring-transparent hover:opacity-80'
                            "
                            :aria-label="
                                t('properties.open_photo', {
                                    number: index + 1,
                                })
                            "
                            :aria-current="
                                index === lightboxIndex ? 'true' : undefined
                            "
                            @click="lightboxIndex = index"
                        >
                            <img
                                :src="image.small"
                                alt=""
                                loading="lazy"
                                class="h-14 w-20 object-cover sm:h-16 sm:w-24"
                            />
                        </button>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>
