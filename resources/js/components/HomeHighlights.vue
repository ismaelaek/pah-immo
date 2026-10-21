<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const slides = [
    {
        webp: '/assets/images/highlights-1.webp',
        jpg: '/assets/images/highlights-1.jpg',
        alt: 'Villa contemporaine avec piscine proposée à la vente par PAH Immo',
    },
    {
        webp: '/assets/images/living-room.webp',
        jpg: '/assets/images/living-room.jpg',
        alt: 'Salon lumineux d’un bien immobilier à vendre ou à louer',
    },
    {
        webp: '/assets/images/kitchen.webp',
        jpg: '/assets/images/kitchen.jpg',
        alt: 'Cuisine moderne d’une maison accompagnée par PAH Immo',
    },
    {
        webp: '/assets/images/bedroom.webp',
        jpg: '/assets/images/bedroom.jpg',
        alt: 'Chambre contemporaine dans un bien sélectionné par PAH Immo',
    },
] as const;

const currentSlide = ref(0);
const activeSlide = computed(() => slides[currentSlide.value] ?? slides[0]);

function goToSlide(index: number): void {
    const total = slides.length;
    currentSlide.value = ((index % total) + total) % total;
}

const stats = [
    { value: '100%', label: 'Suivi personnalisé' },
    { value: '3', label: 'Services : vente, achat, location' },
    { value: '4', label: 'Villes d’intervention' },
    { value: '5★', label: 'Satisfaction client' },
] as const;
</script>

<template>
    <section class="bg-white px-5 py-20 sm:px-8 lg:px-12 lg:py-28">
        <div class="mx-auto max-w-[92rem]">
            <h2
                class="mb-10 max-w-lg text-3xl leading-tight font-semibold tracking-tight text-neutral-950 sm:text-4xl lg:mb-14"
            >
                Prêt à changer de bien&nbsp;?
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
                                :aria-label="`Voir la photo ${index + 1}`"
                                @click="goToSlide(index)"
                            />
                        </div>

                        <div class="flex gap-2">
                            <button
                                type="button"
                                class="flex size-11 items-center justify-center rounded-full bg-white text-neutral-900 shadow-md transition-colors hover:bg-neutral-50"
                                aria-label="Photo précédente"
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
                                aria-label="Photo suivante"
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
                        Un bon bien, même compact, change tout.
                    </h3>
                    <p class="text-sm leading-6 text-neutral-500">
                        PAH Immo, agence immobilière, vous accompagne pour
                        vendre votre maison au juste prix, acheter un
                        appartement ou une villa, et trouver une location
                        adaptée à votre projet.
                    </p>
                    <Link
                        href="/about"
                        prefetch
                        class="inline-flex w-fit cursor-pointer rounded-full border border-neutral-200 px-6 py-2.5 text-sm font-medium text-neutral-900 transition-colors hover:bg-neutral-50"
                    >
                        En savoir plus
                    </Link>
                    <p class="text-sm leading-6 text-neutral-500">
                        Nos conseillers sélectionnent des biens à vendre ou à
                        louer et restent à vos côtés jusqu’à la signature.
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
                                alt="Villa blanche avec piscine à vendre accompagnée par PAH Immo"
                                class="aspect-[4/3] w-full object-cover"
                            />
                        </picture>
                    </div>
                    <p
                        class="text-center text-lg font-medium tracking-tight text-brand-red"
                    >
                        À partir de 250 000&nbsp;€
                    </p>
                    <Link
                        href="/properties"
                        prefetch
                        class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-full bg-brand-red px-6 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90"
                    >
                        Voir les biens
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
