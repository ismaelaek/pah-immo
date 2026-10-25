<script setup lang="ts">
import type { LegalSection } from '@/components/LegalSections.vue';
import LegalPage from '@/components/LegalPage.vue';
import LegalSections from '@/components/LegalSections.vue';
import { useLocale } from '@/composables/useLocale';

const { t, trans } = useLocale();
</script>

<template>
    <LegalPage
        :title="t('privacy.title')"
        :description="t('privacy.description')"
    >
        <template #intro>
            {{ t('privacy.intro') }}
        </template>

        <LegalSections
            :sections="trans<LegalSection[]>('privacy.sections') ?? []"
        />
    </LegalPage>
</template>
