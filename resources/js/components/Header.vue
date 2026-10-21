<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = withDefaults(
    defineProps<{
        overlay?: boolean;
    }>(),
    {
        overlay: false,
    },
);

const page = usePage();
const menuOpen = ref(false);
const currentUrl = computed(() => page.url.split('?')[0] ?? '/');
const overlay = computed(
    () => props.overlay || ['/', '/contact'].includes(currentUrl.value),
);
const logoSrc = computed(() =>
    overlay.value && !menuOpen.value
        ? '/assets/logo-dark.png'
        : '/assets/logo.png',
);

const links = [
    { label: 'Accueil', href: '/' },
    { label: 'Biens', href: '/properties' },
    { label: 'À propos', href: '/about' },
    { label: 'Blog', href: '/blog' },
] as const;

function isActive(href: string): boolean {
    if (href === '/') {
        return currentUrl.value === '/';
    }

    return currentUrl.value.startsWith(href);
}
</script>

<template>
    <header
        class="z-40"
        :class="overlay ? 'absolute inset-x-0 top-0' : 'relative bg-white'"
    >
        <div
            class="relative z-50 mx-auto flex max-w-[92rem] items-center justify-between gap-4 px-5 py-5 lg:px-10"
        >
            <Link href="/" class="relative z-10 shrink-0" prefetch>
                <img
                    :src="logoSrc"
                    alt="PAH Immo"
                    class="h-12 w-auto object-contain lg:h-14"
                />
            </Link>

            <nav
                class="absolute left-1/2 hidden -translate-x-1/2 rounded-full px-2 py-1.5 lg:flex"
                :class="
                    overlay
                        ? 'border border-white/50 bg-white/40 shadow-[0_8px_30px_rgba(15,23,42,0.08)] backdrop-blur-xl'
                        : 'bg-white shadow-[0_8px_30px_rgba(15,23,42,0.08)]'
                "
                aria-label="Primary"
            >
                <Link
                    v-for="link in links"
                    :key="link.href"
                    :href="link.href"
                    prefetch
                    class="cursor-pointer rounded-full px-5 py-2.5 text-sm font-medium transition-colors"
                    :class="
                        isActive(link.href)
                            ? 'bg-white text-neutral-900 shadow-sm'
                            : 'text-neutral-900 hover:bg-white/50'
                    "
                >
                    {{ link.label }}
                </Link>
            </nav>

            <div class="flex items-center gap-3">
                <Link
                    href="/contact"
                    prefetch
                    class="cursor-pointer rounded-full bg-neutral-950 px-5 py-2.5 text-sm font-semibold text-white transition-opacity hover:opacity-90"
                >
                    Contact
                </Link>

                <button
                    type="button"
                    class="flex size-10 items-center justify-center rounded-full border lg:hidden"
                    :class="
                        overlay && !menuOpen
                            ? 'border-white/70 text-white'
                            : 'border-neutral-200 text-neutral-900'
                    "
                    :aria-expanded="menuOpen"
                    aria-label="Toggle menu"
                    @click="menuOpen = !menuOpen"
                >
                    <svg
                        class="size-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            v-if="!menuOpen"
                            d="M4 7h16M4 12h16M4 17h16"
                            stroke-linecap="round"
                        />
                        <path
                            v-else
                            d="M6 6l12 12M18 6L6 18"
                            stroke-linecap="round"
                        />
                    </svg>
                </button>
            </div>
        </div>

        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="-translate-y-full opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="-translate-y-full opacity-0"
        >
            <div
                v-if="menuOpen"
                class="fixed inset-x-0 top-0 z-40 min-h-svh bg-white/95 pt-24 backdrop-blur-xl lg:hidden"
            >
                <nav class="flex flex-col gap-2 px-5 py-6" aria-label="Mobile">
                    <Link
                        v-for="link in links"
                        :key="link.href"
                        :href="link.href"
                        class="cursor-pointer rounded-full px-5 py-3.5 text-base font-medium text-neutral-900 transition-colors"
                        :class="
                            isActive(link.href)
                                ? 'bg-brand-red/10 text-brand-red'
                                : 'hover:bg-neutral-50'
                        "
                        @click="menuOpen = false"
                    >
                        {{ link.label }}
                    </Link>
                </nav>
            </div>
        </Transition>
    </header>
</template>
