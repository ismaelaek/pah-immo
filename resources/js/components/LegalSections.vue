<script setup lang="ts">
import { useLocale } from '@/composables/useLocale';

export type LegalSection = {
    title: string;
    paragraphs?: string[];
    items?: string[];
    after?: string[];
};

defineProps<{
    sections: LegalSection[];
}>();

const { localized } = useLocale();

function html(text: string): string {
    return text
        .replaceAll(':contact', localized('/contact'))
        .replaceAll(':privacy', localized('/confidentialite'))
        .replaceAll(':legal', localized('/mentions-legales'));
}
</script>

<template>
    <section
        v-for="section in sections"
        :key="section.title"
        class="flex flex-col gap-3"
    >
        <h2>{{ section.title }}</h2>
        <p
            v-for="(paragraph, index) in section.paragraphs"
            :key="`p-${index}`"
            v-html="html(paragraph)"
        />
        <ul v-if="section.items?.length">
            <li
                v-for="(item, index) in section.items"
                :key="`i-${index}`"
                v-html="html(item)"
            />
        </ul>
        <p
            v-for="(paragraph, index) in section.after"
            :key="`a-${index}`"
            v-html="html(paragraph)"
        />
    </section>
</template>
