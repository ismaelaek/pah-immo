import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

function lookup(source: unknown, key: string): unknown {
    return key.split('.').reduce<unknown>((current, segment) => {
        if (current !== null && typeof current === 'object' && segment in current) {
            return (current as Record<string, unknown>)[segment];
        }

        return undefined;
    }, source);
}

function replace(template: string, values: Record<string, string | number>): string {
    return Object.entries(values).reduce(
        (result, [name, value]) => result.replaceAll(`:${name}`, String(value)),
        template,
    );
}

export function useLocale() {
    const page = usePage();

    const locale = computed(() => page.props.locale);
    const locales = computed(() => page.props.locales);
    const translations = computed(() => page.props.translations);

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

    function t(key: string, values: Record<string, string | number> = {}): string {
        const value = lookup(translations.value, key);

        if (typeof value !== 'string') {
            return key;
        }

        return replace(value, {
            contact: localized('/contact'),
            privacy: localized('/confidentialite'),
            legal: localized('/mentions-legales'),
            ...values,
        });
    }

    function tc(key: string, count: number, values: Record<string, string | number> = {}): string {
        const value = lookup(translations.value, key);

        if (typeof value !== 'string') {
            return key;
        }

        const parts = value.split('|');
        const template = (count === 1 ? parts[0] : parts[1]) ?? parts[0] ?? value;

        return replace(template, { count, ...values });
    }

    function trans<T = unknown>(key: string): T {
        return lookup(translations.value, key) as T;
    }

    return {
        locale,
        locales,
        translations,
        localized,
        switchUrl,
        path,
        t,
        tc,
        trans,
    };
}
