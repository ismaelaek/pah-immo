<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

export type BlogPost = {
    slug: string;
    title: string;
    description: string;
    author: string;
    publishedAt: string;
    webp: string;
    jpg: string;
    alt: string;
};

withDefaults(
    defineProps<{
        post: BlogPost;
        featured?: boolean;
    }>(),
    {
        featured: false,
    },
);

function authorInitials(name: string): string {
    return name
        .split(/\s+/)
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part.charAt(0).toUpperCase())
        .join('');
}

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
    <article
        class="flex overflow-hidden rounded-[1.75rem] bg-white"
        :class="
            featured
                ? 'flex-col shadow-[0_20px_60px_rgba(15,23,42,0.12)] lg:flex-row'
                : 'flex-col shadow-[0_12px_40px_rgba(15,23,42,0.06)]'
        "
    >
        <div
            class="relative overflow-hidden"
            :class="featured ? 'lg:w-[52%]' : ''"
        >
            <picture>
                <source :srcset="post.webp" type="image/webp" />
                <img
                    :src="post.jpg"
                    :alt="post.alt"
                    class="w-full object-cover transition-transform duration-500 ease-out hover:scale-105"
                    :class="
                        featured
                            ? 'aspect-[16/10] lg:aspect-auto lg:h-full lg:min-h-[22rem]'
                            : 'aspect-[16/10]'
                    "
                />
            </picture>
        </div>

        <div
            class="flex flex-1 flex-col gap-4"
            :class="
                featured
                    ? 'justify-center px-6 py-6 sm:px-8 sm:py-8 lg:px-10'
                    : 'px-5 py-5'
            "
        >
            <component
                :is="featured ? 'h2' : 'h3'"
                class="font-semibold tracking-tight text-neutral-950"
                :class="
                    featured
                        ? 'text-2xl leading-tight sm:text-3xl lg:text-4xl'
                        : 'text-2xl leading-tight'
                "
            >
                {{ post.title }}
            </component>

            <p
                class="text-sm leading-6 text-neutral-500"
                :class="featured ? 'max-w-xl sm:text-base sm:leading-7' : ''"
            >
                {{ post.description }}
            </p>

            <div
                class="mt-auto flex flex-wrap items-center justify-between gap-3 pt-1"
            >
                <div class="flex min-w-0 items-center gap-3">
                    <span
                        class="flex size-10 shrink-0 items-center justify-center rounded-full bg-brand-red text-xs font-semibold tracking-wide text-white"
                        aria-hidden="true"
                    >
                        {{ authorInitials(post.author) }}
                    </span>

                    <div class="min-w-0">
                        <p class="text-sm font-medium text-neutral-900">
                            {{ post.author }}
                        </p>
                        <time
                            class="text-sm text-neutral-500"
                            :datetime="post.publishedAt"
                        >
                            {{ formatRelativeDate(post.publishedAt) }}
                        </time>
                    </div>
                </div>

                <Link
                    :href="`/blog/${post.slug}`"
                    prefetch
                    class="inline-flex shrink-0 cursor-pointer items-center gap-1.5 rounded-full bg-brand-red px-5 py-2.5 text-sm font-semibold text-white transition-opacity hover:opacity-90"
                >
                    Lire plus
                    <span aria-hidden="true">→</span>
                </Link>
            </div>
        </div>
    </article>
</template>
