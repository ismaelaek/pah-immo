<script setup lang="ts">
import Footer from '@/components/Footer.vue';
import Header from '@/components/Header.vue';

withDefaults(
    defineProps<{
        overlayHeader?: boolean;
    }>(),
    {
        overlayHeader: false,
    },
);
</script>

<template>
    <div class="min-h-svh bg-white text-neutral-950">
        <Header :overlay="overlayHeader" />
        <main>
            <slot />
        </main>
        <Footer />
    </div>
</template>
