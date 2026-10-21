<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import HomeHighlights from '@/components/HomeHighlights.vue';

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
}>();

const featuredTypes = ['Maison', 'Appartement', 'Villa'] as const;
const filters = ['Ville', 'Maison', 'Résidentiel', 'Appartement'] as const;
const prices = [
    'Tous les prix',
    'Moins de 250 000 €',
    '250 – 500 000 €',
    '500 000 – 1 M €',
    'Plus de 1 M €',
];
const locations = [
    'Toute la France',
    'Paris',
    'Lyon',
    'Bordeaux',
    'Nice',
];
const rooms = [
    'Studio',
    '1 chambre',
    '2 chambres',
    '3 chambres',
    '4 chambres et plus',
];

const selectedType = ref<(typeof featuredTypes)[number]>('Maison');
const selectedFilter = ref<(typeof filters)[number]>('Ville');
const propertyType = ref('');
const price = ref('');
const location = ref('');
const roomCount = ref('2 chambres');

function searchProperties(): void {
    // Search will be wired to listings in a later page.
}
</script>

<template>
    <div>
        <Head title="Agence immobilière">
            <meta
                head-key="description"
                name="description"
                content="PAH Immo, agence immobilière : vente, achat et location de villas, maisons et appartements. Trouvez le bien idéal avec un accompagnement personnalisé."
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
                        alt="Villa contemporaine proposée par PAH Immo, agence immobilière"
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
                        <div class="mb-6 flex flex-wrap gap-2">
                            <button
                                v-for="type in featuredTypes"
                                :key="type"
                                type="button"
                                class="cursor-pointer rounded-full bg-white px-4 py-2 text-sm font-medium text-neutral-900 shadow-sm"
                                :class="
                                    selectedType === type
                                        ? 'bg-white'
                                        : 'bg-white/90'
                                "
                                @click="selectedType = type"
                            >
                                {{ type }}
                            </button>
                        </div>

                        <h1
                            class="max-w-3xl text-4xl leading-[1.05] font-semibold tracking-tight text-white text-shadow-lg sm:text-5xl lg:text-7xl"
                        >
                            Trouvez le bien immobilier qui construit votre
                            avenir.
                        </h1>
                    </div>

                    <form
                        class="rounded-[1.75rem] bg-white p-5 shadow-[0_20px_60px_rgba(15,23,42,0.12)] sm:p-7 lg:rounded-[2rem] lg:p-8"
                        @submit.prevent="searchProperties"
                    >
                        <h2
                            class="mb-6 text-2xl font-semibold tracking-tight text-neutral-950 lg:text-3xl"
                        >
                            Trouvez le bien idéal
                        </h2>

                        <div
                            class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4"
                        >
                            <label class="relative flex flex-col gap-2">
                                <span
                                    class="text-sm font-medium text-neutral-800"
                                >
                                    Type de bien
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
                                        Tous les types
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
                                    Prix
                                </span>
                                <select
                                    v-model="price"
                                    name="price"
                                    class="h-12 appearance-none rounded-full bg-[#F3F3F3] px-5 pr-12 text-sm text-neutral-500 outline-none focus:ring-2 focus:ring-neutral-200"
                                >
                                    <option disabled value="">
                                        Tous les prix
                                    </option>
                                    <option
                                        v-for="option in prices"
                                        :key="option"
                                        :value="option"
                                    >
                                        {{ option }}
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
                                    Ville
                                </span>
                                <select
                                    v-model="location"
                                    name="location"
                                    class="h-12 appearance-none rounded-full bg-[#F3F3F3] px-5 pr-12 text-sm text-neutral-500 outline-none focus:ring-2 focus:ring-neutral-200"
                                >
                                    <option disabled value="">
                                        Toutes les villes
                                    </option>
                                    <option
                                        v-for="option in locations"
                                        :key="option"
                                        :value="option"
                                    >
                                        {{ option }}
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
                                    Pièces
                                </span>
                                <select
                                    v-model="roomCount"
                                    name="rooms"
                                    class="h-12 appearance-none rounded-full bg-[#F3F3F3] px-5 pr-12 text-sm text-neutral-900 outline-none focus:ring-2 focus:ring-neutral-200"
                                >
                                    <option
                                        v-for="option in rooms"
                                        :key="option"
                                        :value="option"
                                    >
                                        {{ option }}
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
                        </div>

                        <div
                            class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between"
                        >
                            <div
                                class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm"
                            >
                                <span class="font-medium text-neutral-800">
                                    Filtrer :
                                </span>
                                <button
                                    v-for="filter in filters"
                                    :key="filter"
                                    type="button"
                                    class="cursor-pointer font-medium transition-colors"
                                    :class="
                                        selectedFilter === filter
                                            ? 'text-neutral-950'
                                            : 'text-neutral-400 hover:text-neutral-700'
                                    "
                                    @click="selectedFilter = filter"
                                >
                                    {{ filter }}
                                </button>
                            </div>

                            <button
                                type="submit"
                                class="cursor-pointer rounded-full bg-neutral-950 px-7 py-3.5 text-sm font-semibold text-white transition-opacity hover:opacity-90"
                            >
                                Rechercher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <HomeHighlights />
    </div>
</template>
