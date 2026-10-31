<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useLocale } from '@/composables/useLocale';

type Expertise = {
    title: string;
    body: string;
};

defineOptions({
    layout: {
        darkNav: true,
    },
});

const { localized, t, trans } = useLocale();

const phone = { display: '06 89 08 09 79', href: 'tel:+33689080979' };
const email = 'pah.immo0683@gmail.com';

const facts = computed(() => trans<string[]>('about.facts') ?? []);
const expertise = computed(() => trans<Expertise[]>('about.expertise') ?? []);
const founderParagraphs = computed(
    () => trans<string[]>('about.founder_paragraphs') ?? [],
);
const founderPoints = computed(
    () => trans<string[]>('about.founder_points') ?? [],
);
</script>

<template>
    <div>
        <Head :title="t('about.title')">
            <meta
                head-key="description"
                name="description"
                :content="t('about.description')"
            />
        </Head>

        <!-- Hero -->
        <section class="px-5 pt-8 pb-16 sm:px-8 lg:px-12 lg:pt-12 lg:pb-20">
            <div
                class="mx-auto grid max-w-[92rem] items-center gap-12 lg:grid-cols-12 lg:gap-14"
            >
                <div class="flex flex-col items-start gap-6 lg:col-span-7">
                    <p
                        class="inline-flex items-center gap-2 rounded-full bg-[#F3F3F3] px-4 py-1.5 text-sm font-medium text-neutral-700"
                    >
                        <span
                            class="size-2 rounded-full bg-brand-red"
                            aria-hidden="true"
                        />
                        {{ t('about.eyebrow') }}
                    </p>

                    <h1
                        class="max-w-3xl text-3xl leading-tight font-semibold tracking-tight text-balance text-neutral-950 sm:text-4xl lg:text-5xl"
                    >
                        {{ t('about.hero_title') }}
                    </h1>

                    <p class="max-w-xl text-base leading-7 text-neutral-500">
                        {{ t('about.hero_body') }}
                    </p>

                    <ul class="flex flex-wrap gap-2">
                        <li
                            v-for="fact in facts"
                            :key="fact"
                            class="rounded-full border border-neutral-200 px-4 py-2 text-sm font-medium text-neutral-800"
                        >
                            {{ fact }}
                        </li>
                    </ul>

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a
                            href="#rendez-vous"
                            class="inline-flex items-center gap-2 rounded-full bg-brand-red px-6 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90"
                        >
                            {{ t('about.badge_title') }}
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>

                <div class="relative w-full max-w-lg lg:col-span-5 lg:ml-auto">
                    <div
                        class="absolute -top-4 -right-4 hidden size-24 rounded-full bg-brand lg:block"
                        aria-hidden="true"
                    />
                    <picture
                        class="relative block overflow-hidden rounded-[2rem]"
                    >
                        <source
                            srcset="/assets/images/living-room.webp"
                            type="image/webp"
                        />
                        <img
                            src="/assets/images/living-room.jpg"
                            :alt="t('about.hero_alt')"
                            class="aspect-[3/2] w-full object-cover"
                        />
                    </picture>

                    <div
                        class="absolute -bottom-5 left-5 flex max-w-xs items-center gap-3 rounded-[1.25rem] bg-white p-3 pr-5 shadow-[0_20px_60px_rgba(15,23,42,0.14)] sm:left-6"
                    >
                        <span
                            class="flex size-10 shrink-0 items-center justify-center rounded-full bg-brand-red text-white"
                            aria-hidden="true"
                        >
                            <svg
                                class="size-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.6"
                            >
                                <rect
                                    x="3.5"
                                    y="5"
                                    width="17"
                                    height="15.5"
                                    rx="2.5"
                                />
                                <path
                                    d="M3.5 10h17M8 3v4M16 3v4"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="m9.5 15 1.8 1.8 3.4-3.6"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-neutral-950">
                                {{ t('about.badge_title') }}
                            </p>
                            <p class="text-xs leading-5 text-neutral-500">
                                {{ t('about.badge_body') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Expertise -->
        <section class="bg-[#F6F6F6] px-5 py-20 sm:px-8 lg:px-12 lg:py-28">
            <div class="mx-auto max-w-[92rem]">
                <h2
                    class="mb-10 max-w-lg text-3xl leading-tight font-semibold tracking-tight text-neutral-950 sm:text-4xl lg:mb-14"
                >
                    {{ t('about.expertise_title') }}
                </h2>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 lg:gap-6">
                    <article
                        v-for="(item, index) in expertise"
                        :key="item.title"
                        class="group flex flex-col gap-10 rounded-[1.75rem] bg-white p-6 transition-transform duration-300 hover:-translate-y-1 lg:p-7"
                    >
                        <span
                            class="text-sm font-semibold text-brand-red tabular-nums"
                        >
                            {{ String(index + 1).padStart(2, '0') }}
                        </span>
                        <div class="mt-auto flex flex-col gap-2">
                            <h3
                                class="text-xl font-semibold tracking-tight text-neutral-950"
                            >
                                {{ item.title }}
                            </h3>
                            <p class="text-sm leading-6 text-neutral-500">
                                {{ item.body }}
                            </p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- Founder -->
        <section class="px-5 py-20 sm:px-8 lg:px-12 lg:py-28">
            <div
                class="mx-auto grid max-w-[92rem] items-center gap-10 lg:grid-cols-12 lg:gap-16"
            >
                <div
                    class="relative flex aspect-square max-w-md items-end overflow-hidden rounded-[2rem] bg-neutral-950 p-8 lg:col-span-5 lg:max-w-none"
                    aria-hidden="true"
                >
                    <span
                        class="absolute top-8 right-8 size-4 rounded-full bg-brand"
                    />
                    <span
                        class="text-[clamp(5rem,14vw,11rem)] leading-none font-semibold tracking-tighter text-white"
                    >
                        PAH
                    </span>
                </div>

                <div class="flex flex-col items-start gap-6 lg:col-span-7">
                    <p class="text-sm font-medium text-brand-red">
                        {{ t('about.founder_eyebrow') }}
                    </p>
                    <h2
                        class="text-3xl leading-tight font-semibold tracking-tight text-neutral-950 sm:text-4xl lg:text-5xl"
                    >
                        {{ t('about.founder_name') }}
                    </h2>
                    <p
                        class="rounded-full bg-[#F3F3F3] px-4 py-1.5 text-sm font-medium text-neutral-700"
                    >
                        {{ t('about.founder_role') }}
                    </p>
                    <p
                        class="max-w-2xl border-l-2 border-brand-red pl-6 text-xl leading-8 text-neutral-700 sm:text-2xl sm:leading-10"
                    >
                        {{ t('about.founder_body') }}
                    </p>
                    <div
                        class="flex max-w-2xl flex-col gap-4 text-base leading-7 text-neutral-500"
                    >
                        <p
                            v-for="paragraph in founderParagraphs"
                            :key="paragraph"
                        >
                            {{ paragraph }}
                        </p>
                    </div>
                    <ul class="flex flex-wrap gap-x-6 gap-y-3 pt-2">
                        <li
                            v-for="point in founderPoints"
                            :key="point"
                            class="inline-flex items-center gap-2 text-sm font-medium text-neutral-900"
                        >
                            <span
                                class="flex size-6 items-center justify-center rounded-full bg-brand-red/10 text-brand-red"
                                aria-hidden="true"
                            >
                                <svg
                                    class="size-3.5"
                                    viewBox="0 0 20 20"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2.4"
                                >
                                    <path
                                        d="m5 10.5 3.2 3.2L15 6.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </span>
                            {{ point }}
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Appointment -->
        <section
            id="rendez-vous"
            class="scroll-mt-24 px-5 pb-20 sm:px-8 lg:px-12 lg:pb-28"
        >
            <div
                class="relative mx-auto max-w-[92rem] overflow-hidden rounded-[2rem] bg-brand-red px-6 py-12 text-white sm:px-10 lg:px-16 lg:py-16"
            >
                <div
                    class="absolute -right-16 -bottom-24 size-72 rounded-full border-[3rem] border-white/10"
                    aria-hidden="true"
                />

                <div
                    class="relative grid items-end gap-10 lg:grid-cols-2 lg:gap-16"
                >
                    <div class="flex flex-col gap-4">
                        <h2
                            class="text-3xl leading-tight font-semibold tracking-tight text-balance sm:text-4xl lg:text-5xl"
                        >
                            {{ t('about.appointment_title') }}
                        </h2>
                        <p class="max-w-lg text-base leading-7 text-white/80">
                            {{ t('about.appointment_body') }}
                        </p>
                    </div>

                    <div
                        class="flex flex-col gap-3 sm:items-start lg:items-end"
                    >
                        <a
                            :href="phone.href"
                            class="inline-flex items-center gap-3 rounded-full bg-white px-6 py-3.5 text-base font-semibold text-neutral-950 transition-opacity hover:opacity-90"
                        >
                            <svg
                                class="size-5 text-brand-red"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <path
                                    d="M5 4h3.5l1.5 4.5-2 1.5a11 11 0 0 0 6 6l1.5-2 4.5 1.5V19a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"
                                    stroke-linejoin="round"
                                />
                            </svg>
                            <span class="sr-only">{{ t('about.call') }}</span>
                            {{ phone.display }}
                        </a>
                        <a
                            :href="`mailto:${email}`"
                            class="inline-flex items-center gap-3 rounded-full border border-white/40 px-6 py-3.5 text-base font-semibold break-all text-white transition-colors hover:bg-white/10"
                        >
                            <svg
                                class="size-5 shrink-0"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <rect
                                    x="3"
                                    y="5"
                                    width="18"
                                    height="14"
                                    rx="2.5"
                                />
                                <path
                                    d="m4 7 8 6 8-6"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                            <span class="sr-only">{{ t('about.email') }}</span>
                            {{ email }}
                        </a>
                        <Link
                            :href="localized('/contact')"
                            class="px-2 py-1 text-sm font-semibold text-white/80 underline-offset-4 hover:text-white hover:underline"
                        >
                            {{ t('about.contact_form') }} →
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
