import { createInertiaApp } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'PAH Immo';

void createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    pages: './pages',
    layout: () => PublicLayout,
    progress: {
        color: '#d1504a',
    },
});
