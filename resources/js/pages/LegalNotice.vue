<script setup lang="ts">
import type { LegalSection } from '@/components/LegalSections.vue';
import LegalPage from '@/components/LegalPage.vue';
import LegalSections from '@/components/LegalSections.vue';
import { useLocale } from '@/composables/useLocale';

const { t, trans } = useLocale();
</script>

<template>
    <LegalPage
        :title="t('legal_notice.title')"
        :description="t('legal_notice.description')"
    >
        <template #intro>
            {{ t('legal_notice.intro') }}
        </template>

        <LegalSections
            :sections="trans<LegalSection[]>('legal_notice.sections') ?? []"
        />
    </LegalPage>
</template>
