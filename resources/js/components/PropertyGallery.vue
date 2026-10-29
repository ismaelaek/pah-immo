<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';
import type { PropertyImage } from '@/components/PropertyCard.vue';
import { useLocale } from '@/composables/useLocale';

const props = defineProps<{
    images: PropertyImage[];
    alt: string;
}>();

const { t } = useLocale();
const start = ref(0);
const lightboxIndex = ref(0);
const lightboxOpen = ref(false);

const total = computed(() => props.images.length);

const firstImage = computed(() => props.images[start.value] ?? null);
const secondImage = computed(() => {
    if (total.value < 2) {
        return null;
    }

    return props.images[(start.value + 1) % total.value] ?? null;
});

function go(step: number): void {
    if (total.value === 0) {
        return;
    }

    start.value = (start.value + step + total.value) % total.value;
}

function openLightbox(index: number): void {
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
    if (total.value === 0) {
        return;
    }

    lightboxIndex.value =
        (lightboxIndex.value + step + total.value) % total.value;
}

function onKeydown(event: KeyboardEvent): void {
    if (!lightboxOpen.value) {
        return;
    }

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

onMounted(() => window.addEventListener('keydown', onKeydown));
onUnmounted(() => window.removeEventListener('keydown', onKeydown));
</script>

<template>
    <div>
        <section class="relative bg-neutral-100">
            <div
                v-if="firstImage"
                class="grid"
                :class="secondImage ? 'lg:grid-cols-2' : ''"
            >
                <button
                    type="button"
                    class="group relative block h-[18rem] overflow-hidden sm:h-[24rem] lg:h-[32rem]"
                    :aria-label="
                        t('properties.open_photo', { number: start + 1 })
                    "
                    @click="openLightbox(start)"
                >
                    <img
                        :src="firstImage.large"
                        :alt="`${alt} – ${start + 1}`"
                        class="size-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.03]"
                    />
                </button>

                <button
                    v-if="secondImage"
                    type="button"
                    class="group relative hidden h-[32rem] overflow-hidden lg:block"
                    :aria-label="
                        t('properties.open_photo', {
                            number: ((start + 1) % total) + 1,
                        })
                    "
                    @click="openLightbox((start + 1) % total)"
                >
                    <img
                        :src="secondImage.large"
                        :alt="`${alt} – ${((start + 1) % total) + 1}`"
                        class="size-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.03]"
                    />
                </button>
            </div>
            <div
                v-else
                class="h-[18rem] bg-neutral-100 sm:h-[24rem] lg:h-[32rem]"
            />

            <template v-if="total > 1">
                <button
                    type="button"
                    class="absolute top-1/2 left-4 z-10 flex size-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-neutral-900 shadow-md transition-colors hover:bg-neutral-50 sm:left-6"
                    :aria-label="t('properties.gallery_prev')"
                    @click="go(-1)"
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
                    class="absolute top-1/2 right-4 z-10 flex size-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-neutral-900 shadow-md transition-colors hover:bg-neutral-50 sm:right-6"
                    :aria-label="t('properties.gallery_next')"
                    @click="go(1)"
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

            <p
                v-if="total > 0"
                class="absolute right-4 bottom-4 rounded-full bg-black/55 px-3 py-1.5 text-xs font-medium text-white sm:right-6 sm:bottom-6"
            >
                {{ start + 1 }} / {{ total }}
            </p>
        </section>

        <Teleport to="body">
            <div
                v-if="lightboxOpen && firstImage"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-5"
                role="dialog"
                aria-modal="true"
                :aria-label="t('properties.gallery')"
                @click.self="closeLightbox"
            >
                <button
                    type="button"
                    class="absolute top-5 right-5 flex size-11 items-center justify-center rounded-full bg-white text-neutral-900 shadow-md transition-colors hover:bg-neutral-50"
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

                <button
                    v-if="total > 1"
                    type="button"
                    class="absolute top-1/2 left-4 flex size-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-neutral-900 shadow-md transition-colors hover:bg-neutral-50 sm:left-6"
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

                <img
                    :src="images[lightboxIndex]?.large"
                    :alt="`${alt} – ${lightboxIndex + 1}`"
                    class="max-h-[85vh] max-w-full object-contain"
                />

                <button
                    v-if="total > 1"
                    type="button"
                    class="absolute top-1/2 right-4 flex size-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-neutral-900 shadow-md transition-colors hover:bg-neutral-50 sm:right-6"
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
            </div>
        </Teleport>
    </div>
</template>
