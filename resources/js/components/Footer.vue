<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useLocale } from '@/composables/useLocale';

const { localized, t } = useLocale();

const year = new Date().getFullYear();

const leftLinks = computed(() => [
    { label: t('nav.home'), href: '/' },
    { label: t('nav.about'), href: '/about' },
    { label: t('nav.properties'), href: '/properties' },
]);

const rightLinks = computed(() => [
    { label: t('nav.blog'), href: '/blog' },
    { label: t('nav.faq'), href: '/#faq' },
    { label: t('nav.contact'), href: '/contact' },
]);
</script>

<template>
    <footer class="bg-white">
        <div class="mx-auto max-w-[92rem] px-5 lg:px-12">
            <div
                class="relative flex flex-col items-center gap-8 border-b border-neutral-100 py-10 lg:flex-row lg:justify-between lg:py-12"
            >
                <nav
                    class="flex flex-wrap justify-center gap-x-8 gap-y-2 text-sm text-neutral-500 lg:justify-start"
                    :aria-label="t('nav.footer')"
                >
                    <Link
                        v-for="link in leftLinks"
                        :key="link.href"
                        :href="localized(link.href)"
                        prefetch
                        class="transition-colors hover:text-neutral-950"
                    >
                        {{ link.label }}
                    </Link>
                </nav>

                <Link
                    :href="localized('/')"
                    prefetch
                    class="lg:absolute lg:left-1/2 lg:-translate-x-1/2"
                >
                    <img
                        src="/assets/logo.png"
                        alt="PAH Immo"
                        class="h-14 w-auto object-contain lg:h-16"
                    />
                </Link>

                <nav
                    class="flex flex-wrap justify-center gap-x-8 gap-y-2 text-sm text-neutral-500 lg:justify-end"
                    :aria-label="t('nav.footer_secondary')"
                >
                    <Link
                        v-for="link in rightLinks"
                        :key="link.href"
                        :href="localized(link.href)"
                        prefetch
                        class="transition-colors hover:text-neutral-950"
                    >
                        {{ link.label }}
                    </Link>
                </nav>
            </div>

            <div
                class="flex flex-col items-center gap-4 py-8 text-sm text-neutral-400 sm:flex-row sm:justify-between"
            >
                <p>{{ t('footer.rights', { year }) }}</p>
                <div class="flex flex-wrap justify-center gap-x-6 gap-y-2">
                    <Link
                        :href="localized('/mentions-legales')"
                        prefetch
                        class="transition-colors hover:text-neutral-700"
                    >
                        {{ t('nav.legal') }}
                    </Link>
                    <Link
                        :href="localized('/confidentialite')"
                        prefetch
                        class="transition-colors hover:text-neutral-700"
                    >
                        {{ t('nav.privacy') }}
                    </Link>
                </div>
            </div>
        </div>
    </footer>
</template>
