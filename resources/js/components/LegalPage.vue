<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

defineProps<{
    title: string;
    description: string;
}>();
</script>

<template>
    <div class="bg-white">
        <Head :title="title">
            <meta
                head-key="description"
                name="description"
                :content="description"
            />
        </Head>

        <article
            class="mx-auto max-w-3xl px-5 py-16 sm:px-8 lg:px-12 lg:py-24"
        >
            <p class="mb-3 text-sm font-medium text-brand-red">PAH Immo</p>
            <h1
                class="text-3xl font-semibold tracking-tight text-neutral-950 sm:text-4xl lg:text-5xl"
            >
                {{ title }}
            </h1>
            <p class="mt-5 text-sm leading-6 text-neutral-500">
                <slot name="intro" />
            </p>

            <div
                class="mt-12 flex flex-col gap-10 text-sm leading-6 text-neutral-600 [&_a]:font-medium [&_a]:text-brand-red [&_h2]:text-lg [&_h2]:font-semibold [&_h2]:tracking-tight [&_h2]:text-neutral-950 [&_li]:leading-6 [&_ul]:flex [&_ul]:list-disc [&_ul]:flex-col [&_ul]:gap-2 [&_ul]:pl-5"
            >
                <slot />
            </div>
        </article>
    </div>
</template>
