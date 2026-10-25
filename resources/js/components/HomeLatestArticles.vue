<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import BlogPostCard from '@/components/BlogPostCard.vue';
import { useLocale } from '@/composables/useLocale';

const { localized } = useLocale();
import type { BlogPost } from '@/components/BlogPostCard.vue';

defineProps<{
    posts: BlogPost[];
}>();
</script>

<template>
    <section class="bg-white px-5 pb-20 sm:px-8 lg:px-12 lg:pb-28">
        <div class="mx-auto max-w-[92rem]">
            <div
                class="mb-10 flex flex-col gap-6 lg:mb-14 lg:flex-row lg:items-end lg:justify-between"
            >
                <div class="max-w-xl">
                    <h2
                        class="text-3xl leading-tight font-semibold tracking-tight text-neutral-950 sm:text-4xl lg:text-5xl"
                    >
                        Les derniers articles
                    </h2>
                    <p class="mt-4 text-sm leading-6 text-neutral-500">
                        Conseils, tendances et coulisses de l’agence pour vendre,
                        acheter ou louer en toute confiance.
                    </p>
                </div>

                <Link
                    :href="localized('/blog')"
                    prefetch
                    class="inline-flex w-fit shrink-0 cursor-pointer items-center gap-2 rounded-full bg-brand-red px-6 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90"
                >
                    Voir tous les articles
                    <span aria-hidden="true">→</span>
                </Link>
            </div>

            <div
                v-if="posts.length > 0"
                class="grid gap-8 md:grid-cols-2 xl:grid-cols-3"
            >
                <BlogPostCard
                    v-for="post in posts"
                    :key="post.slug"
                    :post="post"
                />
            </div>
            <p v-else class="text-sm leading-6 text-neutral-500">
                Aucun article pour le moment.
            </p>
        </div>
    </section>
</template>
