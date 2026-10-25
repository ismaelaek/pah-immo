<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import { useLocale } from '@/composables/useLocale';

const props = withDefaults(
    defineProps<{
        overlay?: boolean;
    }>(),
    {
        overlay: false,
    },
);

const { locale, locales, switchUrl, t } = useLocale();
const root = ref<HTMLDetailsElement | null>(null);

function closeOnOutsideClick(event: PointerEvent): void {
    const target = event.target;

    if (!(target instanceof Node) || root.value === null || !root.value.open) {
        return;
    }

    if (!root.value.contains(target)) {
        root.value.open = false;
    }
}

onMounted(() => {
    document.addEventListener('pointerdown', closeOnOutsideClick);
});

onUnmounted(() => {
    document.removeEventListener('pointerdown', closeOnOutsideClick);
});
</script>

<template>
    <details ref="root" :key="locale" class="relative">
        <summary
            class="flex cursor-pointer list-none items-center gap-1.5 rounded-full border px-3.5 py-2.5 text-sm font-medium uppercase transition-colors [&::-webkit-details-marker]:hidden"
            :class="
                props.overlay
                    ? 'border-white/70 text-white hover:border-white hover:bg-white/15'
                    : 'border-neutral-200 text-neutral-900 hover:border-neutral-300 hover:bg-neutral-50'
            "
            :aria-label="t('nav.language')"
        >
            {{ locale }}
            <svg
                class="size-3.5"
                viewBox="0 0 20 20"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                aria-hidden="true"
            >
                <path d="M5 7.5 10 12.5 15 7.5" stroke-linecap="round" />
            </svg>
        </summary>

        <div
            class="absolute right-0 z-50 mt-2 min-w-40 rounded-2xl bg-white py-1.5 shadow-[0_12px_40px_rgba(15,23,42,0.12)]"
        >
            <Link
                v-for="option in locales"
                :key="option.value"
                :href="switchUrl(option.value)"
                class="block px-4 py-2 text-sm transition-colors"
                :class="
                    option.value === locale
                        ? 'font-semibold text-brand-red'
                        : 'text-neutral-700 hover:bg-neutral-50'
                "
            >
                {{ option.label }}
            </Link>
        </div>
    </details>
</template>
