<script setup lang="ts">
import { computed, ref } from 'vue';
import { useLocale } from '@/composables/useLocale';

const { t, trans } = useLocale();

const items = computed(
    () =>
        trans<{ question: string; answer: string }[]>('faq.items') ?? [],
);

const openIndex = ref(0);

function toggle(index: number): void {
    openIndex.value = openIndex.value === index ? -1 : index;
}
</script>

<template>
    <section
        id="faq"
        class="bg-white px-5 pb-20 sm:px-8 lg:px-12 lg:pb-28"
    >
        <div class="mx-auto max-w-[92rem]">
            <div
                class="mb-10 flex flex-col gap-6 lg:mb-14 lg:flex-row lg:items-end lg:justify-between"
            >
                <h2
                    class="text-3xl leading-tight font-semibold tracking-tight text-neutral-950 sm:text-4xl lg:text-5xl"
                >
                    {{ t('faq.title') }}
                </h2>
                <p
                    class="max-w-lg text-sm leading-6 text-neutral-500 lg:text-right"
                >
                    {{ t('faq.intro') }}
                </p>
            </div>

            <div class="flex flex-col gap-3">
                <div
                    v-for="(item, index) in items"
                    :key="item.question"
                    class="rounded-[1.5rem] bg-[#F6F6F6] px-6 transition-colors sm:px-8"
                    :class="
                        openIndex === index ? 'bg-[#F3F3F3]' : 'hover:bg-[#F3F3F3]'
                    "
                >
                    <button
                        type="button"
                        class="flex w-full items-center justify-between gap-6 py-5 text-left sm:py-6"
                        :aria-expanded="openIndex === index"
                        @click="toggle(index)"
                    >
                        <span
                            class="text-base font-semibold tracking-tight transition-colors sm:text-lg"
                            :class="
                                openIndex === index
                                    ? 'text-brand-red'
                                    : 'text-neutral-950'
                            "
                        >
                            {{ item.question }}
                        </span>
                        <span
                            class="flex size-8 shrink-0 items-center justify-center rounded-full transition-colors"
                            :class="
                                openIndex === index
                                    ? 'bg-brand-red text-white'
                                    : 'text-neutral-900'
                            "
                            aria-hidden="true"
                        >
                            <svg
                                class="size-4 transition-transform duration-300"
                                :class="openIndex === index ? 'rotate-180' : ''"
                                viewBox="0 0 20 20"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    d="m5 7.5 5 5 5-5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </span>
                    </button>

                    <div
                        class="grid transition-[grid-template-rows] duration-300 ease-out"
                        :class="
                            openIndex === index
                                ? 'grid-rows-[1fr]'
                                : 'grid-rows-[0fr]'
                        "
                    >
                        <div class="overflow-hidden">
                            <p
                                class="max-w-4xl pb-6 text-sm leading-6 text-neutral-500"
                            >
                                {{ item.answer }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
