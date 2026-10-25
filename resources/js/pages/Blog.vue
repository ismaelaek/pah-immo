<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import BlogPostCard from '@/components/BlogPostCard.vue';
import type { BlogPost } from '@/components/BlogPostCard.vue';
import { useLocale } from '@/composables/useLocale';

defineOptions({
    layout: {
        overlayHeader: true,
    },
});

defineProps<{
    posts: BlogPost[];
}>();

const { t } = useLocale();
</script>

<template>
    <div>
        <Head :title="t('blog.title')">
            <meta
                head-key="description"
                name="description"
                :content="t('blog.description')"
            />
        </Head>

        <section>
            <div class="relative isolate overflow-hidden">
                <picture>
                    <source
                        srcset="/assets/images/hero-blog.webp"
                        type="image/webp"
                    />
                    <img
                        src="/assets/images/hero-blog.jpg"
                        :alt="t('blog.hero_alt')"
                        class="absolute inset-0 size-full object-cover"
                    />
                </picture>

                <div class="absolute inset-0 bg-black/40" />

                <div
                    class="relative flex min-h-80 items-center justify-center px-5 pt-28 pb-16 sm:min-h-96 sm:px-8 lg:min-h-[28rem] lg:px-12"
                >
                    <div class="mx-auto max-w-3xl text-center">
                        <h1
                            class="text-4xl leading-[1.05] font-semibold tracking-tight text-white text-shadow-lg sm:text-5xl lg:text-6xl"
                        >
                            {{ t('blog.hero_title') }}
                        </h1>
                        <p
                            class="mx-auto mt-5 max-w-xl text-sm leading-6 text-white/80 sm:text-base sm:leading-7"
                        >
                            {{ t('blog.description') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-white px-5 py-16 sm:px-8 lg:px-12 lg:py-24">
            <div class="mx-auto max-w-[92rem]">
                <div class="mb-10 max-w-xl lg:mb-14">
                    <h2
                        class="text-3xl leading-tight font-semibold tracking-tight text-neutral-950 sm:text-4xl lg:text-5xl"
                    >
                        {{ t('blog.latest') }}
                    </h2>
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
                    {{ t('blog.empty') }}
                </p>
            </div>
        </section>
    </div>
</template>
