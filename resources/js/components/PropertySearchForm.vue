<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { reactive, toRef } from 'vue';
import type { PropertyOption } from '@/components/PropertyCard.vue';
import { useLocale } from '@/composables/useLocale';
import {
    emptyPropertyFilters,
    filledPropertyFilters,
    usePropertySearchFields,
} from '@/composables/usePropertySearchFields';
import type { PropertyFilters } from '@/composables/usePropertySearchFields';

const props = withDefaults(
    defineProps<{
        propertyTypes: PropertyOption[];
        cities: string[];
        filters?: PropertyFilters;
        preserveScroll?: boolean;
    }>(),
    {
        filters: emptyPropertyFilters,
        preserveScroll: false,
    },
);

const { localized, t } = useLocale();
const { fields } = usePropertySearchFields(
    toRef(props, 'propertyTypes'),
    toRef(props, 'cities'),
);

const form = reactive<PropertyFilters>({ ...props.filters });

function searchProperties(): void {
    router.get(localized('/properties'), filledPropertyFilters(form), {
        preserveScroll: props.preserveScroll,
    });
}
</script>

<template>
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
            <label
                v-for="field in fields"
                :key="field.name"
                class="relative flex flex-col gap-2"
            >
                <span class="text-sm font-medium text-neutral-800">
                    {{ field.label }}
                </span>
                <select
                    v-model="form[field.name]"
                    :name="field.name"
                    class="h-12 appearance-none rounded-full bg-[#F3F3F3] px-5 pr-12 text-sm outline-none focus:ring-2 focus:ring-neutral-200"
                    :class="
                        form[field.name]
                            ? 'text-neutral-900'
                            : 'text-neutral-500'
                    "
                >
                    <option value="">
                        {{ field.placeholder }}
                    </option>
                    <option
                        v-for="option in field.options"
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
</template>
