<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const year = new Date().getFullYear();

const leftLinks = [
    { label: 'Accueil', href: '/' },
    { label: 'À propos', href: '/about' },
    { label: 'Biens', href: '/properties' },
] as const;

const rightLinks = [
    { label: 'Blog', href: '/blog' },
    { label: 'FAQ', href: '/#faq' },
    { label: 'Contact', href: '/contact' },
] as const;
</script>

<template>
    <footer class="bg-white">
        <div class="mx-auto max-w-[92rem] px-5 lg:px-12">
            <div
                class="relative flex flex-col items-center gap-8 border-b border-neutral-100 py-10 lg:flex-row lg:justify-between lg:py-12"
            >
                <nav
                    class="flex flex-wrap justify-center gap-x-8 gap-y-2 text-sm text-neutral-500 lg:justify-start"
                    aria-label="Pied de page"
                >
                    <Link
                        v-for="link in leftLinks"
                        :key="link.href"
                        :href="link.href"
                        prefetch
                        class="transition-colors hover:text-neutral-950"
                    >
                        {{ link.label }}
                    </Link>
                </nav>

                <Link
                    href="/"
                    prefetch
                    class="lg:absolute lg:left-1/2 lg:-translate-x-1/2"
                >
                    <img
                        src="/assets/logo.png"
                        alt="PAH Immo"
                        class="h-10 w-auto object-contain lg:h-12"
                    />
                </Link>

                <nav
                    class="flex flex-wrap justify-center gap-x-8 gap-y-2 text-sm text-neutral-500 lg:justify-end"
                    aria-label="Pied de page secondaire"
                >
                    <Link
                        v-for="link in rightLinks"
                        :key="link.href"
                        :href="link.href"
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
                <p>© {{ year }} PAH Immo. Tous droits réservés.</p>
                <div class="flex flex-wrap justify-center gap-x-6 gap-y-2">
                    <Link
                        href="/mentions-legales"
                        prefetch
                        class="transition-colors hover:text-neutral-700"
                    >
                        Mentions légales
                    </Link>
                    <Link
                        href="/confidentialite"
                        prefetch
                        class="transition-colors hover:text-neutral-700"
                    >
                        Politique de confidentialité
                    </Link>
                </div>
            </div>
        </div>
    </footer>
</template>
