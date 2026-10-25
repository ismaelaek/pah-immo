<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useLocale } from '@/composables/useLocale';

const { localized, t } = useLocale();
</script>

<template>
    <section class="bg-white px-5 pb-20 sm:px-8 lg:px-12 lg:pb-28">
        <div class="mx-auto max-w-[92rem]">
            <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
                <picture>
                    <source
                        srcset="/assets/images/dream-house.webp"
                        type="image/webp"
                    />
                    <img
                        src="/assets/images/dream-house.jpg"
                        :alt="t('best_value.alt')"
                        class="w-full"
                        width="1400"
                        height="700"
                    />
                </picture>

                <div class="flex flex-col items-start gap-6">
                    <h2
                        class="max-w-lg text-3xl leading-tight font-semibold tracking-tight text-neutral-950 sm:text-4xl lg:text-5xl"
                    >
                        {{ t('best_value.title') }}
                    </h2>
                    <p class="max-w-md text-sm leading-6 text-neutral-500">
                        {{ t('best_value.body') }}
                    </p>
                    <Link
                        :href="localized('/properties')"
                        prefetch
                        class="inline-flex cursor-pointer items-center gap-2 rounded-full bg-brand-red px-6 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90"
                    >
                        {{ t('best_value.cta') }}
                        <span aria-hidden="true">→</span>
                    </Link>
                </div>
            </div>
        </div>
    </section>
</template>
