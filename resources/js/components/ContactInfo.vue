<script setup lang="ts">
import { computed } from 'vue';
import { useLocale } from '@/composables/useLocale';

const { t } = useLocale();

const items = computed(() => [
    {
        icon: 'pin',
        title: t('contact.info.address_title'),
        body: t('contact.info.address_body'),
        href: null,
        link: null,
    },
    {
        icon: 'mail',
        title: t('contact.info.email_title'),
        body: 'contact@pah-immo.fr',
        href: 'mailto:contact@pah-immo.fr',
        link: null,
    },
    {
        icon: 'phone',
        title: t('contact.info.phone_title'),
        body: t('contact.info.phone_body'),
        href: 'tel:+33186260000',
        link: '+33 1 86 26 00 00',
    },
]);
</script>

<template>
    <section class="bg-[#F6F6F6] px-5 py-20 sm:px-8 lg:px-12 lg:py-28">
        <div class="mx-auto max-w-[92rem]">
            <div class="grid gap-10 lg:grid-cols-3 lg:gap-16">
                <article
                    v-for="item in items"
                    :key="item.title"
                    class="flex gap-5"
                >
                    <span
                        class="flex size-12 shrink-0 items-center justify-center rounded-full bg-white text-brand-red shadow-[0_8px_24px_rgba(15,23,42,0.06)]"
                        aria-hidden="true"
                    >
                        <svg
                            v-if="item.icon === 'pin'"
                            class="size-5"
                            viewBox="0 0 20 20"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                        >
                            <path
                                d="M10 17s6-5.1 6-9.2A6 6 0 1 0 4 7.8C4 11.9 10 17 10 17Z"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <circle cx="10" cy="7.8" r="1.8" />
                        </svg>
                        <svg
                            v-else-if="item.icon === 'mail'"
                            class="size-5"
                            viewBox="0 0 20 20"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                        >
                            <rect x="3" y="5" width="14" height="10" rx="2" />
                            <path
                                d="m4 6.5 6 4.2 6-4.2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                        <svg
                            v-else
                            class="size-5"
                            viewBox="0 0 20 20"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                        >
                            <path
                                d="M4.2 3.8h2.6l1.1 3-1.7 1.4a11 11 0 0 0 5.6 5.6l1.4-1.7 3 1.1v2.6c0 .7-.6 1.3-1.3 1.3C8.4 16.1 3.9 11.6 3.9 5.1c0-.7.6-1.3 1.3-1.3Z"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </span>

                    <div class="flex flex-col gap-1.5">
                        <h2
                            class="text-lg font-semibold tracking-tight text-neutral-950"
                        >
                            {{ item.title }}
                        </h2>
                        <a
                            v-if="item.href && !item.link"
                            :href="item.href"
                            class="text-sm leading-6 text-neutral-500 transition-colors hover:text-brand-red"
                        >
                            {{ item.body }}
                        </a>
                        <p v-else class="text-sm leading-6 text-neutral-500">
                            {{ item.body }}
                        </p>
                        <a
                            v-if="item.link && item.href"
                            :href="item.href"
                            class="mt-1 inline-flex items-center gap-2 text-sm font-medium text-brand-red transition-opacity hover:opacity-80"
                        >
                            {{ item.link }}
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </section>
</template>
