<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useLocale } from '@/composables/useLocale';

const { localized } = useLocale();

const deliveryStageLabels = {
    'off-plan': 'Sur plan',
    'under-construction': 'En construction',
    ready: 'Prêt à livrer',
} as const;

const properties = [
    {
        title: 'Villa Horizon',
        price: '785 000 €',
        address: '14 rue des Tilleuls, Lyon',
        bedrooms: 5,
        bathrooms: 2,
        surface: 220,
        type: 'Villa',
        status: 'À vendre',
        stage: 'off-plan',
        webp: '/assets/images/home-hero.webp',
        jpg: '/assets/images/home-hero.jpg',
        alt: 'Villa contemporaine à vendre à Lyon, proposée par PAH Immo',
    },
    {
        title: 'Maison du Parc',
        price: '620 000 €',
        address: '8 avenue Victor Hugo, Bordeaux',
        bedrooms: 4,
        bathrooms: 2,
        surface: 165,
        type: 'Maison',
        status: 'À vendre',
        stage: 'under-construction',
        webp: '/assets/images/highlights-1.webp',
        jpg: '/assets/images/highlights-1.jpg',
        alt: 'Maison avec piscine à vendre à Bordeaux, accompagnée par PAH Immo',
    },
    {
        title: 'Villa Côte Sud',
        price: '1 150 000 €',
        address: '22 promenade des Pins, Nice',
        bedrooms: 5,
        bathrooms: 3,
        surface: 280,
        type: 'Villa',
        status: 'À vendre',
        stage: 'ready',
        webp: '/assets/images/highlights-2.webp',
        jpg: '/assets/images/highlights-2.jpg',
        alt: 'Villa blanche à vendre à Nice, sélectionnée par PAH Immo',
    },
    {
        title: 'Appartement Lumière',
        price: '2 450 € / mois',
        address: '31 rue Saint-Honoré, Paris',
        bedrooms: 3,
        bathrooms: 2,
        surface: 92,
        type: 'Appartement',
        status: 'À louer',
        stage: 'ready',
        webp: '/assets/images/living-room.webp',
        jpg: '/assets/images/living-room.jpg',
        alt: 'Salon d’un appartement lumineux à louer à Paris',
    },
    {
        title: 'Maison Saint-Michel',
        price: '540 000 €',
        address: '5 place Saint-Michel, Lyon',
        bedrooms: 4,
        bathrooms: 2,
        surface: 148,
        type: 'Maison',
        status: 'À vendre',
        stage: 'under-construction',
        webp: '/assets/images/kitchen.webp',
        jpg: '/assets/images/kitchen.jpg',
        alt: 'Cuisine d’une maison familiale à vendre à Lyon',
    },
    {
        title: 'Duplex des Jardins',
        price: '1 390 € / mois',
        address: '17 cours de l’Intendance, Bordeaux',
        bedrooms: 3,
        bathrooms: 1,
        surface: 78,
        type: 'Duplex',
        status: 'À louer',
        stage: 'ready',
        webp: '/assets/images/bedroom.webp',
        jpg: '/assets/images/bedroom.jpg',
        alt: 'Chambre d’un duplex à louer à Bordeaux, proposé par PAH Immo',
    },
] as const;
</script>

<template>
    <section class="bg-white px-5 pb-20 sm:px-8 lg:px-12 lg:pb-28">
        <div class="mx-auto max-w-[92rem]">
            <div
                class="mb-10 flex flex-col gap-6 lg:mb-14 lg:flex-row lg:items-end lg:justify-between"
            >
                <div class="max-w-xl">
                    <h2
                        class="text-3xl leading-tight font-semibold tracking-tight text-neutral-950 sm:text-4xl lg:text-5xl"
                    >
                        Découvrez nos biens d’exception
                    </h2>
                    <p class="mt-4 text-sm leading-6 text-neutral-500">
                        Chaque annonce met en avant un bien unique : emplacement
                        recherché, prestations soignées et un accompagnement PAH
                        Immo pour vendre, acheter ou louer en toute confiance.
                    </p>
                </div>

                <Link
                    :href="localized('/properties')"
                    prefetch
                    class="inline-flex w-fit shrink-0 cursor-pointer items-center gap-2 rounded-full bg-brand-red px-6 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90"
                >
                    Voir tous les biens
                    <span aria-hidden="true">→</span>
                </Link>
            </div>

            <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                <article
                    v-for="property in properties"
                    :key="property.title"
                    class="flex flex-col overflow-hidden rounded-[1.75rem] bg-white shadow-[0_12px_40px_rgba(15,23,42,0.06)]"
                >
                    <Link
                        :href="localized('/properties')"
                        prefetch
                        class="group relative block overflow-hidden"
                    >
                        <picture>
                            <source
                                :srcset="property.webp"
                                type="image/webp"
                            />
                            <img
                                :src="property.jpg"
                                :alt="property.alt"
                                class="aspect-[16/10] w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                            />
                        </picture>

                        <div
                            class="absolute top-4 left-4 flex flex-wrap gap-2"
                        >
                            <span
                                class="rounded-full bg-brand-red px-4 py-1.5 text-sm font-medium text-white shadow-sm"
                            >
                                {{ property.status }}
                            </span>
                            <span
                                class="rounded-full bg-white px-4 py-1.5 text-sm font-medium text-neutral-900 shadow-sm"
                            >
                                {{ property.type }}
                            </span>
                        </div>
                    </Link>

                    <div class="flex flex-col gap-3 px-5 py-5">
                        <h3
                            class="text-2xl font-semibold tracking-tight text-neutral-950"
                        >
                            {{ property.title }}
                        </h3>

                        <p
                            class="inline-flex items-start gap-1.5 text-sm text-neutral-500"
                        >
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
                            {{ property.address }}
                        </p>

                        <p
                            class="inline-flex items-center gap-1.5 text-sm text-neutral-500"
                        >
                            <svg
                                v-if="property.stage === 'off-plan'"
                                class="size-4 shrink-0"
                                viewBox="0 0 20 20"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.6"
                                aria-hidden="true"
                            >
                                <path
                                    d="M5 16.5V4.5h7l3 3v9H5Z"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M12 4.5v3h3M7.5 10h5M7.5 13h3"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                            <svg
                                v-else-if="
                                    property.stage === 'under-construction'
                                "
                                class="size-4 shrink-0"
                                viewBox="0 0 20 20"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.6"
                                aria-hidden="true"
                            >
                                <path
                                    d="M4 16.5h4M6 16.5V6.5h9"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M6 8.5h10M13.5 8.5v4h3"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                            <svg
                                v-else
                                class="size-4 shrink-0"
                                viewBox="0 0 20 20"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.6"
                                aria-hidden="true"
                            >
                                <circle cx="10" cy="10" r="6.5" />
                                <path
                                    d="m7.2 10.2 2 2 3.6-4"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                            {{ deliveryStageLabels[property.stage] }}
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
                                {{ property.surface }}&nbsp;m²
                            </span>
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
                                {{ property.bedrooms }} chambres
                            </span>
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
                                {{ property.bathrooms }}
                                {{
                                    property.bathrooms > 1
                                        ? 'salles de bain'
                                        : 'salle de bain'
                                }}
                            </span>
                        </p>

                        <div class="flex items-center justify-between gap-3">
                            <p
                                class="text-lg font-semibold text-brand-red sm:text-xl"
                            >
                                {{ property.price }}
                            </p>
                            <Link
                                :href="localized('/properties')"
                                prefetch
                                class="inline-flex shrink-0 cursor-pointer items-center gap-1.5 rounded-full bg-brand-red px-5 py-2.5 text-sm font-semibold text-white transition-opacity hover:opacity-90"
                            >
                                Voir le bien
                                <span aria-hidden="true">→</span>
                            </Link>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>
</template>
