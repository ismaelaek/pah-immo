<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { blogPosts } from '@/data/blogPosts';

const props = defineProps<{
    slug: string;
}>();

const post = blogPosts.find((entry) => entry.slug === props.slug);

function formatRelativeDate(isoDate: string): string {
    const date = new Date(isoDate);
    const diffInSeconds = Math.round((date.getTime() - Date.now()) / 1000);
    const abs = Math.abs(diffInSeconds);

    const divisions: [Intl.RelativeTimeFormatUnit, number][] = [
        ['year', 60 * 60 * 24 * 365],
        ['month', 60 * 60 * 24 * 30],
        ['week', 60 * 60 * 24 * 7],
        ['day', 60 * 60 * 24],
        ['hour', 60 * 60],
        ['minute', 60],
        ['second', 1],
    ];

    const formatter = new Intl.RelativeTimeFormat('fr', { numeric: 'auto' });

    for (const [unit, seconds] of divisions) {
        if (abs >= seconds || unit === 'second') {
            return formatter.format(Math.round(diffInSeconds / seconds), unit);
        }
    }

    return formatter.format(0, 'second');
}
</script>

<template>
    <div class="bg-white">
        <Head v-if="post" :title="post.title">
            <meta
                head-key="description"
                name="description"
                :content="post.description"
            />
        </Head>
        <Head v-else title="Article introuvable" />

        <article
            v-if="post"
            class="mx-auto max-w-3xl px-5 py-16 sm:px-8 lg:px-12 lg:py-24"
        >
            <Link
                href="/blog"
                prefetch
                class="text-sm font-medium text-brand-red transition-opacity hover:opacity-80"
            >
                ← Retour au journal
            </Link>

            <h1
                class="mt-6 text-3xl font-semibold tracking-tight text-neutral-950 sm:text-4xl lg:text-5xl"
            >
                {{ post.title }}
            </h1>

            <p class="mt-4 text-sm text-neutral-500">
                {{ post.author }}
                <span class="text-neutral-300">·</span>
                <time :datetime="post.publishedAt">
                    {{ formatRelativeDate(post.publishedAt) }}
                </time>
            </p>

            <picture>
                <source :srcset="post.webp" type="image/webp" />
                <img
                    :src="post.jpg"
                    :alt="post.alt"
                    class="mt-10 aspect-[16/10] w-full rounded-[1.75rem] object-cover"
                />
            </picture>

            <p class="mt-10 text-base leading-7 text-neutral-600">
                {{ post.description }}
            </p>
        </article>

        <div v-else class="mx-auto max-w-3xl px-5 py-24 text-center sm:px-8">
            <h1 class="text-3xl font-semibold tracking-tight text-neutral-950">
                Article introuvable
            </h1>
            <Link
                href="/blog"
                prefetch
                class="mt-6 inline-flex rounded-full bg-brand-red px-6 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90"
            >
                Retour au journal
            </Link>
        </div>
    </div>
</template>
