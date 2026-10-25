import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export function useLocale() {
    const page = usePage();

    const locale = computed(() => page.props.locale);
    const locales = computed(() => page.props.locales);

    function localized(href: string): string {
        const url = new URL(href, 'http://n');
        const path =
            url.pathname === '/'
                ? `/${locale.value}`
                : `/${locale.value}${url.pathname}`;

        return `${path}${url.search}${url.hash}`;
    }

    function switchUrl(nextLocale: string): string {
        const url = new URL(page.url, 'http://n');
        const segments = url.pathname.split('/').filter(Boolean);

        if (locales.value.some((item) => item.value === segments[0])) {
            segments[0] = nextLocale;
        } else {
            segments.unshift(nextLocale);
        }

        return `/${segments.join('/')}${url.search}${url.hash}`;
    }

    const path = computed(() => {
        const pathname = page.url.split('?')[0] ?? '/';
        const stripped = pathname.replace(
            new RegExp(`^/${locale.value}(?=/|$)`),
            '',
        );

        return stripped === '' ? '/' : stripped;
    });

    return {
        locale,
        locales,
        localized,
        switchUrl,
        path,
    };
}
