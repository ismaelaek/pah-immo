<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useLocale } from '@/composables/useLocale';

const { t } = useLocale();
</script>

<template>
    <div>
        <Head :title="t('properties.title')" />
    </div>
</template>
