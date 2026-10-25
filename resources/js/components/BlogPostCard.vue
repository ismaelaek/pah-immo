<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useLocale } from '@/composables/useLocale';

const { localized } = useLocale();

export type BlogPost = {
    slug: string;
    title: string;
    description: string;
    body?: string;
    author: string;
    authorAvatar: string | null;
    publishedAt: string | null;
    publishedAtFormatted: string | null;
    image: string | null;
    imageAlt: string;
    views: number;
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
            <img
                v-if="post.image"
                :src="post.image"
                :alt="post.imageAlt"
                class="w-full object-cover transition-transform duration-500 ease-out hover:scale-105"
                :class="
                    featured
                        ? 'aspect-[16/10] lg:aspect-auto lg:h-full lg:min-h-[22rem]'
                        : 'aspect-[16/10]'
                "
            />
            <div
                v-else
                class="bg-neutral-100"
                :class="
                    featured
                        ? 'aspect-[16/10] lg:aspect-auto lg:h-full lg:min-h-[22rem]'
                        : 'aspect-[16/10]'
                "
            />
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
                    <img
                        v-if="post.authorAvatar"
                        :src="post.authorAvatar"
                        :alt="post.author"
                        class="size-10 shrink-0 rounded-full object-cover"
                    />
                    <span
                        v-else
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
                            v-if="post.publishedAt"
                            class="text-sm text-neutral-500"
                            :datetime="post.publishedAt"
                        >
                            {{ post.publishedAtFormatted }}
                        </time>
                    </div>
                </div>

                <Link
                    :href="localized(`/blog/${post.slug}`)"
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
