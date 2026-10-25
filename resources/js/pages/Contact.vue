<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ContactInfo from '@/components/ContactInfo.vue';
import { useLocale } from '@/composables/useLocale';

defineOptions({
    layout: {
        overlayHeader: true,
    },
});

const page = usePage();
const { localized } = useLocale();
const sent = computed(() => Boolean(page.flash.success));
const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    message: '',
});

function submit(): void {
    form.post(localized('/contact'), {
        onSuccess: () => form.reset(),
    });
}

const inputClass =
    'h-12 rounded-full bg-[#F3F3F3] px-5 text-sm text-neutral-900 outline-none focus:ring-2 focus:ring-neutral-200';
</script>

<template>
    <div>
        <Head title="Contact">
            <meta
                head-key="description"
                name="description"
                content="Contactez PAH Immo, agence immobilière : un conseiller vous accompagne pour vendre, acheter ou louer un bien."
            />
        </Head>

        <section>
            <div class="relative isolate min-h-svh overflow-hidden">
                <picture>
                    <source
                        srcset="/assets/images/hero-contact.webp"
                        type="image/webp"
                    />
                    <img
                        src="/assets/images/hero-contact.jpg"
                        alt="Villa contemporaine avec piscine proposée par PAH Immo"
                        class="absolute inset-0 size-full object-cover"
                    />
                </picture>

                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/25 to-black/20"
                />

                <div
                    class="relative flex min-h-svh flex-col justify-end px-5 pt-28 pb-5 sm:px-8 lg:px-12 lg:pb-8"
                >
                    <div
                        class="mx-auto flex w-full max-w-6xl flex-col gap-8 lg:gap-10"
                    >
                    <div class="text-center">
                        <h1
                            class="text-4xl leading-[1.05] font-semibold tracking-tight text-white text-shadow-lg sm:text-5xl lg:text-7xl"
                        >
                            Parlons de votre projet immobilier.
                        </h1>
                    </div>

                    <div
                        class="rounded-[1.75rem] bg-white p-5 shadow-[0_20px_60px_rgba(15,23,42,0.12)] sm:p-7 lg:rounded-[2rem] lg:p-8"
                    >
                        <h2
                            class="text-2xl font-semibold tracking-tight text-neutral-950 lg:text-3xl"
                            :class="sent ? 'mb-8' : 'mb-6'"
                        >
                            Écrivez-nous
                        </h2>

                        <div
                            v-if="sent"
                            class="flex flex-col items-center gap-4 py-8 text-center sm:py-10"
                        >
                            <span
                                class="flex size-16 items-center justify-center rounded-full bg-emerald-50 text-emerald-600"
                                aria-hidden="true"
                            >
                                <svg
                                    class="size-8"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        d="M5 13l4 4L19 7"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </span>
                            <p
                                class="max-w-md text-sm font-medium leading-6 text-emerald-700 sm:text-base"
                            >
                                {{ page.flash.success }}
                            </p>
                        </div>

                        <form v-else @submit.prevent="submit">
                        <div class="mb-6 grid gap-4 sm:grid-cols-2">
                            <label class="flex flex-col gap-2">
                                <span
                                    class="text-sm font-medium text-neutral-800"
                                >
                                    Prénom
                                </span>
                                <input
                                    v-model="form.first_name"
                                    type="text"
                                    name="first_name"
                                    autocomplete="given-name"
                                    :class="inputClass"
                                />
                                <span
                                    v-if="form.errors.first_name"
                                    class="text-sm text-brand-red"
                                >
                                    {{ form.errors.first_name }}
                                </span>
                            </label>

                            <label class="flex flex-col gap-2">
                                <span
                                    class="text-sm font-medium text-neutral-800"
                                >
                                    Nom
                                </span>
                                <input
                                    v-model="form.last_name"
                                    type="text"
                                    name="last_name"
                                    autocomplete="family-name"
                                    :class="inputClass"
                                />
                                <span
                                    v-if="form.errors.last_name"
                                    class="text-sm text-brand-red"
                                >
                                    {{ form.errors.last_name }}
                                </span>
                            </label>

                            <label class="flex flex-col gap-2">
                                <span
                                    class="text-sm font-medium text-neutral-800"
                                >
                                    E-mail
                                </span>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    name="email"
                                    autocomplete="email"
                                    :class="inputClass"
                                />
                                <span
                                    v-if="form.errors.email"
                                    class="text-sm text-brand-red"
                                >
                                    {{ form.errors.email }}
                                </span>
                            </label>

                            <label class="flex flex-col gap-2">
                                <span
                                    class="text-sm font-medium text-neutral-800"
                                >
                                    Téléphone
                                </span>
                                <input
                                    v-model="form.phone"
                                    type="tel"
                                    name="phone"
                                    autocomplete="tel"
                                    :class="inputClass"
                                />
                                <span
                                    v-if="form.errors.phone"
                                    class="text-sm text-brand-red"
                                >
                                    {{ form.errors.phone }}
                                </span>
                            </label>
                        </div>

                        <label class="mb-6 flex flex-col gap-2">
                            <span class="text-sm font-medium text-neutral-800">
                                Message
                            </span>
                            <textarea
                                v-model="form.message"
                                name="message"
                                rows="5"
                                class="rounded-[1.5rem] bg-[#F3F3F3] px-5 py-4 text-sm text-neutral-900 outline-none focus:ring-2 focus:ring-neutral-200"
                            />
                            <span
                                v-if="form.errors.message"
                                class="text-sm text-brand-red"
                            >
                                {{ form.errors.message }}
                            </span>
                        </label>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="cursor-pointer rounded-full bg-brand-red px-7 py-3.5 text-sm font-semibold text-white transition-opacity hover:opacity-90 disabled:opacity-60"
                            >
                                {{ form.processing ? 'Envoi…' : 'Envoyer' }}
                            </button>
                        </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </section>

        <ContactInfo />
    </div>
</template>
