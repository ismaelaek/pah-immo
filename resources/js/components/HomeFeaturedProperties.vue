<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const properties = [
    {
        title: 'Villa Horizon',
        price: '785 000 €',
        address: '14 rue des Tilleuls, Lyon',
        bedrooms: 5,
        bathrooms: 2,
        status: 'À vendre',
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
        status: 'À vendre',
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
        status: 'À vendre',
        webp: '/assets/images/highlights-2.webp',
        jpg: '/assets/images/highlights-2.jpg',
        alt: 'Villa blanche à vendre à Nice, sélectionnée par PAH Immo',
    },
    {
        title: 'Appartement Lumière',
        price: '495 000 €',
        address: '31 rue Saint-Honoré, Paris',
        bedrooms: 3,
        bathrooms: 2,
        status: 'À vendre',
        webp: '/assets/images/living-room.webp',
        jpg: '/assets/images/living-room.jpg',
        alt: 'Salon d’un appartement lumineux à vendre à Paris',
    },
    {
        title: 'Maison Saint-Michel',
        price: '540 000 €',
        address: '5 place Saint-Michel, Lyon',
        bedrooms: 4,
        bathrooms: 2,
        status: 'À vendre',
        webp: '/assets/images/kitchen.webp',
        jpg: '/assets/images/kitchen.jpg',
        alt: 'Cuisine d’une maison familiale à vendre à Lyon',
    },
    {
        title: 'Duplex des Jardins',
        price: '389 000 €',
        address: '17 cours de l’Intendance, Bordeaux',
        bedrooms: 3,
        bathrooms: 1,
        status: 'À vendre',
        webp: '/assets/images/bedroom.webp',
        jpg: '/assets/images/bedroom.jpg',
        alt: 'Chambre d’un duplex à vendre à Bordeaux, proposé par PAH Immo',
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
                    href="/properties"
                    prefetch
                    class="inline-flex w-fit shrink-0 cursor-pointer items-center gap-2 rounded-full bg-neutral-950 px-6 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90"
                >
                    Voir tous les biens
                    <span aria-hidden="true">→</span>
                </Link>
            </div>

            <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                <Link
                    v-for="property in properties"
                    :key="property.title"
                    href="/properties"
                    prefetch
                    class="group flex flex-col gap-4"
                >
                    <div
                        class="relative overflow-hidden rounded-[1.75rem] shadow-[0_12px_40px_rgba(15,23,42,0.06)] transition-shadow duration-300 group-hover:shadow-[0_22px_50px_rgba(15,23,42,0.14)]"
                    >
                        <picture>
                            <source
                                :srcset="property.webp"
                                type="image/webp"
                            />
                            <img
                                :src="property.jpg"
                                :alt="property.alt"
                                class="aspect-[4/3] w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                            />
                        </picture>

                        <span
                            class="absolute top-4 left-4 rounded-full bg-white px-4 py-1.5 text-sm font-medium text-neutral-900 shadow-sm"
                        >
                            {{ property.status }}
                        </span>
                    </div>

                    <div class="flex flex-col gap-2 px-1">
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
                                    <path
                                        d="M3 10.5V17h4.2v-3.2h5.6V17H17V10.5M2 11l8-7 8 7"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
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

                        <h3
                            class="text-xl font-semibold tracking-tight text-neutral-950 transition-colors group-hover:text-neutral-700"
                        >
                            {{ property.title }}
                        </h3>
                        <p class="text-sm text-neutral-500">
                            {{ property.price }}
                            <span class="text-neutral-300">–</span>
                            {{ property.address }}
                        </p>
                    </div>
                </Link>
            </div>
        </div>
    </section>
</template>
