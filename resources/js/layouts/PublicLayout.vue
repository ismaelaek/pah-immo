<script setup lang="ts">
import Footer from '@/components/Footer.vue';
import Header from '@/components/Header.vue';

withDefaults(
    defineProps<{
        overlayHeader?: boolean;
        bare?: boolean;
    }>(),
    {
        overlayHeader: false,
        bare: false,
    },
);
</script>

<template>
    <div class="min-h-svh bg-white text-neutral-950">
        <Header v-if="!bare" :overlay="overlayHeader" />
        <main>
            <slot />
        </main>
        <Footer v-if="!bare" />
    </div>
</template>
