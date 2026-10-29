<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import PropertyCard from '@/components/PropertyCard.vue';
import type { Property } from '@/components/PropertyCard.vue';
import { useLocale } from '@/composables/useLocale';

const { localized, t } = useLocale();

defineProps<{
    properties: Property[];
}>();
</script>

<template>
    <section
        v-if="properties.length > 0"
        class="bg-white px-5 pb-20 sm:px-8 lg:px-12 lg:pb-28"
    >
        <div class="mx-auto max-w-[92rem]">
            <div
                class="mb-10 flex flex-col gap-6 lg:mb-14 lg:flex-row lg:items-end lg:justify-between"
            >
                <div class="max-w-xl">
                    <h2
                        class="text-3xl leading-tight font-semibold tracking-tight text-neutral-950 sm:text-4xl lg:text-5xl"
                    >
                        {{ t('featured.title') }}
                    </h2>
                    <p class="mt-4 text-sm leading-6 text-neutral-500">
                        {{ t('featured.intro') }}
                    </p>
                </div>

                <Link
                    :href="localized('/properties')"
                    prefetch
                    class="inline-flex w-fit shrink-0 cursor-pointer items-center gap-2 rounded-full bg-brand-red px-6 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90"
                >
                    {{ t('featured.see_all') }}
                    <span aria-hidden="true">→</span>
                </Link>
            </div>

            <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                <PropertyCard
                    v-for="property in properties"
                    :key="property.slug"
                    :property="property"
                />
            </div>
        </div>
    </section>
</template>
