import type { PageProps } from '@inertiajs/core';

/**
 * Open a property page instantly: Inertia renders `PropertyShow` with a
 * skeleton while the real property loads. Shared props (translations,
 * locale…) are kept so the layout renders normally in the meantime.
 */
export const propertyVisit = {
    component: 'PropertyShow',
    pageProps: (
        _currentProps: PageProps,
        sharedProps: Partial<PageProps>,
    ): Record<string, unknown> => ({
        ...sharedProps,
        pending: true,
    }),
} as const;
