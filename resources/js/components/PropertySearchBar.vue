<script setup lang="ts">
import { reactive, toRef, watch } from 'vue';
import type { PropertyOption } from '@/components/PropertyCard.vue';
import { useLocale } from '@/composables/useLocale';
import { usePropertySearchFields } from '@/composables/usePropertySearchFields';
import type { PropertyFilters } from '@/composables/usePropertySearchFields';

const props = withDefaults(
    defineProps<{
        propertyTypes: PropertyOption[];
        cities: string[];
        filters: PropertyFilters;
        loading?: boolean;
    }>(),
    {
        loading: false,
    },
);

const emit = defineEmits<{
    search: [filters: PropertyFilters];
}>();

const { t } = useLocale();
const { fields } = usePropertySearchFields(
    toRef(props, 'propertyTypes'),
    toRef(props, 'cities'),
);

const form = reactive<PropertyFilters>({ ...props.filters });

watch(
    () => props.filters,
    (filters) => Object.assign(form, filters),
);
</script>

<template>
    <form
        class="grid w-full grid-cols-2 gap-1 rounded-[1.75rem] bg-white p-2 shadow-[0_8px_30px_rgba(15,23,42,0.08)] ring-1 ring-neutral-100 lg:flex lg:items-center lg:gap-0 lg:rounded-full lg:py-1.5 lg:pr-1.5 lg:pl-2"
        role="search"
        @submit.prevent="emit('search', { ...form })"
    >
        <template v-for="(field, index) in fields" :key="field.name">
            <span
                v-if="index > 0"
                class="hidden h-8 w-px shrink-0 bg-neutral-200 lg:block"
                aria-hidden="true"
            />
            <label
                class="relative flex min-w-0 flex-1 flex-col rounded-full px-4 py-1.5 transition-colors hover:bg-[#F3F3F3]"
            >
                <span
                    class="text-[0.6875rem] font-semibold tracking-wide text-neutral-900 uppercase"
                >
                    {{ field.label }}
                </span>
                <select
                    v-model="form[field.name]"
                    :name="field.name"
                    class="w-full cursor-pointer appearance-none truncate bg-transparent pr-5 text-sm outline-none"
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
                    class="pointer-events-none absolute right-4 bottom-2.5 size-3.5 text-neutral-400"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                >
                    <path
                        d="M5.2 7.5a.75.75 0 0 1 1.06 0L10 11.24l3.74-3.74a.75.75 0 1 1 1.06 1.06l-4.27 4.27a.75.75 0 0 1-1.06 0L5.2 8.56a.75.75 0 0 1 0-1.06Z"
                    />
                </svg>
            </label>
        </template>

        <button
            type="submit"
            class="col-span-2 flex h-11 shrink-0 cursor-pointer items-center justify-center gap-2 rounded-full bg-brand-red px-5 text-sm font-semibold text-white transition-opacity hover:opacity-90 disabled:opacity-70 lg:ml-1.5 lg:size-12 lg:px-0 xl:w-auto xl:px-6"
            :disabled="loading"
            :aria-label="t('home.search')"
        >
            <svg
                class="size-4 shrink-0"
                :class="loading ? 'animate-pulse' : ''"
                viewBox="0 0 20 20"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                aria-hidden="true"
            >
                <circle cx="9" cy="9" r="5.5" />
                <path d="m13.2 13.2 3.3 3.3" stroke-linecap="round" />
            </svg>
            <span class="lg:hidden xl:inline">{{ t('home.search') }}</span>
        </button>
    </form>
</template>
