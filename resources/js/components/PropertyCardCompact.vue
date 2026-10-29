<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { Property } from '@/components/PropertyCard.vue';
import { useLocale } from '@/composables/useLocale';
import { usePropertyFormatting } from '@/composables/usePropertyFormatting';

withDefaults(
    defineProps<{
        property: Property;
        active?: boolean;
    }>(),
    {
        active: false,
    },
);

const emit = defineEmits<{
    select: [];
}>();

const { localized, t, tc } = useLocale();
const { formatPrice, formatAddress } = usePropertyFormatting();
</script>

<template>
    <article
        class="group flex cursor-pointer overflow-hidden rounded-[1.25rem] bg-white shadow-[0_12px_40px_rgba(15,23,42,0.06)] ring-2 transition-shadow outline-none focus-visible:ring-neutral-950"
        :class="active ? 'ring-brand-red' : 'ring-transparent'"
        tabindex="0"
        :aria-current="active ? 'true' : undefined"
        @click="emit('select')"
        @keydown.enter.self.prevent="emit('select')"
        @keydown.space.self.prevent="emit('select')"
    >
        <div class="relative w-32 shrink-0 overflow-hidden sm:w-40">
            <img
                v-if="property.image"
                :src="property.image"
                :alt="property.imageAlt"
                loading="lazy"
                class="absolute inset-0 size-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
            />
            <div v-else class="absolute inset-0 bg-neutral-100" />
            <span
                class="absolute top-2 left-2 rounded-full bg-brand-red px-2.5 py-1 text-xs font-medium text-white shadow-sm"
            >
                {{ property.offerType.label }}
            </span>
        </div>

        <div class="flex min-w-0 flex-1 flex-col gap-1.5 px-4 py-4">
            <p class="text-xs font-medium text-neutral-500">
                {{ property.type.label }}
            </p>
            <h3
                class="truncate text-base font-semibold tracking-tight text-neutral-950"
            >
                {{ property.title }}
            </h3>
            <p
                class="inline-flex min-w-0 items-center gap-1 text-xs text-neutral-500"
            >
                <svg
                    class="size-3.5 shrink-0"
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
                <span class="truncate">{{ formatAddress(property) }}</span>
            </p>
            <p class="text-xs text-neutral-500">
                {{ property.area }}&nbsp;m²
                <template v-if="property.bedrooms !== null">
                    <span class="text-neutral-300">·</span>
                    {{ tc('property.bedrooms', property.bedrooms) }}
                </template>
            </p>
            <div class="mt-auto flex items-center justify-between gap-3 pt-1">
                <p class="text-base font-semibold text-brand-red">
                    {{ formatPrice(property) }}
                </p>
                <Link
                    :href="localized(`/properties/${property.slug}`)"
                    prefetch
                    class="inline-flex shrink-0 items-center gap-1 rounded-full bg-brand-red px-3.5 py-1.5 text-xs font-semibold text-white transition-opacity hover:opacity-90"
                    @click.stop
                >
                    {{ t('featured.see_property') }}
                    <span aria-hidden="true">→</span>
                </Link>
            </div>
        </div>
    </article>
</template>
