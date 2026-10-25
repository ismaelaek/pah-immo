declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
    }
}

declare module '@inertiajs/core' {
    export interface InertiaConfig {
        sharedPageProps: {
            name: string;
            locale: string;
            locales: {
                value: string;
                label: string;
            }[];
            translations: Record<string, unknown>;
            auth: {
                user: {
                    id: number;
                    name: string;
                    email: string;
                } | null;
            };
            [key: string]: unknown;
        };
    }
}
