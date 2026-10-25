<script setup lang="ts">
import { Head, Link, useHttp } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import type { BlogPost } from '@/components/BlogPostCard.vue';
import { useLocale } from '@/composables/useLocale';

const { locale, localized } = useLocale();
const http = useHttp();

const copy = computed(() => {
    const catalog = {
        fr: {
            missing: 'Article introuvable',
            back: 'Retour au journal',
            views: (count: number) => (count === 1 ? 'vue' : 'vues'),
        },
        en: {
            missing: 'Article not found',
            back: 'Back to the journal',
            views: (count: number) => (count === 1 ? 'view' : 'views'),
        },
        it: {
            missing: 'Articolo non trovato',
            back: 'Torna al journal',
            views: (count: number) =>
                count === 1 ? 'visualizzazione' : 'visualizzazioni',
        },
        es: {
            missing: 'Artículo no encontrado',
            back: 'Volver al diario',
            views: (count: number) => (count === 1 ? 'vista' : 'vistas'),
        },
    } as const;

    return catalog[locale.value as keyof typeof catalog] ?? catalog.fr;
});

const props = defineProps<{
    post: BlogPost | null;
}>();

const views = ref(props.post?.views ?? 0);

onMounted(() => {
    if (props.post === null) {
        return;
    }

    http.post(localized(`/blog/${props.post.slug}/view`), {
        onSuccess: () => {
            views.value += 1;
        },
    });
});

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
    <div class="bg-white">
        <Head v-if="post" :title="post.title">
            <meta
                head-key="description"
                name="description"
                :content="post.description"
            />
        </Head>
        <Head v-else :title="copy.missing" />

        <article
            v-if="post"
            class="mx-auto max-w-3xl px-5 py-8 sm:px-8 lg:px-12 lg:py-16"
        >
            <Link
                :href="localized('/blog')"
                prefetch
                class="text-sm font-medium text-brand-red transition-opacity hover:opacity-80"
            >
                ← {{ copy.back }}
            </Link>

            <h1
                class="mt-6 text-3xl font-semibold tracking-tight text-neutral-950 sm:text-4xl lg:text-5xl"
            >
                {{ post.title }}
            </h1>

            <div class="mt-4 flex items-center gap-3">
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
                <p class="text-sm text-neutral-500">
                    {{ post.author }}
                    <template v-if="post.publishedAt">
                        <span class="text-neutral-300">·</span>
                        <time :datetime="post.publishedAt">
                            {{ post.publishedAtFormatted }}
                        </time>
                    </template>
                    <span class="text-neutral-300">·</span>
                    {{ views }} {{ copy.views(views) }}
                </p>
            </div>

            <img
                v-if="post.image"
                :src="post.image"
                :alt="post.imageAlt"
                class="mt-10 aspect-[16/10] w-full rounded-[1.75rem] object-cover"
            />

            <div
                class="mt-10 space-y-4 text-base leading-7 text-neutral-600 [&_a]:text-brand-red [&_h2]:text-xl [&_h2]:font-semibold [&_h2]:text-neutral-950 [&_h3]:text-lg [&_h3]:font-semibold [&_h3]:text-neutral-950 [&_ol]:list-decimal [&_ol]:pl-5 [&_p]:mb-4 [&_ul]:list-disc [&_ul]:pl-5"
                v-html="post.body ?? post.description"
            />
        </article>

        <div v-else class="mx-auto max-w-3xl px-5 py-24 text-center sm:px-8">
            <h1 class="text-3xl font-semibold tracking-tight text-neutral-950">
                {{ copy.missing }}
            </h1>
            <Link
                :href="localized('/blog')"
                prefetch
                class="mt-6 inline-flex rounded-full bg-brand-red px-6 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90"
            >
                {{ copy.back }}
            </Link>
        </div>
    </div>
</template>
