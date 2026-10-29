<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import type { BlogPost } from '@/components/BlogPostCard.vue';
import HomeBestValue from '@/components/HomeBestValue.vue';
import HomeFaq from '@/components/HomeFaq.vue';
import HomeFeaturedProperties from '@/components/HomeFeaturedProperties.vue';
import HomeHighlights from '@/components/HomeHighlights.vue';
import HomeLatestArticles from '@/components/HomeLatestArticles.vue';
import type { Property, PropertyOption } from '@/components/PropertyCard.vue';
import PropertySearchForm from '@/components/PropertySearchForm.vue';
import { useLocale } from '@/composables/useLocale';

defineOptions({
    layout: {
        overlayHeader: true,
    },
});

defineProps<{
    propertyTypes: PropertyOption[];
    cities: string[];
    featuredProperties: Property[];
    latestPosts: BlogPost[];
}>();

const { t } = useLocale();
</script>

<template>
    <div>
        <Head :title="t('home.title')">
            <meta
                head-key="description"
                name="description"
                :content="t('home.description')"
            />
        </Head>

        <section>
            <div class="relative isolate min-h-svh overflow-hidden">
                <picture>
                    <source
                        srcset="/assets/images/home-hero.webp"
                        type="image/webp"
                    />
                    <img
                        src="/assets/images/home-hero.jpg"
                        :alt="t('home.hero_alt')"
                        class="absolute inset-0 size-full object-cover"
                    />
                </picture>

                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/25 to-black/20"
                />

                <div
                    class="relative flex min-h-svh flex-col justify-end gap-8 px-5 pt-28 pb-5 sm:px-8 lg:gap-10 lg:px-12 lg:pb-8"
                >
                    <div class="max-w-3xl">
                        <h1
                            class="max-w-3xl text-4xl leading-[1.05] font-semibold tracking-tight text-white text-shadow-lg sm:text-5xl lg:text-7xl"
                        >
                            {{ t('home.hero_title') }}
                        </h1>
                    </div>

                    <PropertySearchForm
                        :property-types="propertyTypes"
                        :cities="cities"
                    />
                </div>
            </div>
        </section>

        <HomeHighlights />
        <HomeFeaturedProperties :properties="featuredProperties" />
        <HomeBestValue />
        <HomeLatestArticles :posts="latestPosts" />
        <HomeFaq />
    </div>
</template>
