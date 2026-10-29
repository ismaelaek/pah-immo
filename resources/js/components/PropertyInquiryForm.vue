<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useLocale } from '@/composables/useLocale';

const props = defineProps<{
    title: string;
    reference: string;
}>();

const page = usePage();
const { localized, t } = useLocale();
const sent = computed(() => Boolean(page.flash.success));

const defaultMessage = computed(() =>
    t('properties.inquiry_message', {
        title: props.title,
        reference: props.reference,
    }),
);

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    message: defaultMessage.value,
});

function submit(): void {
    form.post(localized('/contact'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            form.message = defaultMessage.value;
        },
    });
}

const inputClass =
    'h-12 rounded-full bg-[#F3F3F3] px-5 text-sm text-neutral-900 outline-none focus:ring-2 focus:ring-neutral-200';
</script>

<template>
    <div
        class="rounded-[1.75rem] bg-white p-6 shadow-[0_12px_40px_rgba(15,23,42,0.06)] sm:p-7"
    >
        <h2 class="text-xl font-semibold tracking-tight text-neutral-950">
            {{ t('properties.contact_title') }}
        </h2>
        <p class="mt-3 text-sm leading-6 text-neutral-500">
            {{ t('properties.contact_body', { reference }) }}
        </p>

        <div
            v-if="sent"
            class="mt-6 flex flex-col items-center gap-3 py-6 text-center"
        >
            <span
                class="flex size-14 items-center justify-center rounded-full bg-emerald-50 text-emerald-600"
                aria-hidden="true"
            >
                <svg
                    class="size-7"
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
            <p class="text-sm font-medium leading-6 text-emerald-700">
                {{ page.flash.success }}
            </p>
        </div>

        <form v-else class="mt-6 flex flex-col gap-4" @submit.prevent="submit">
            <label class="flex flex-col gap-2">
                <span class="text-sm font-medium text-neutral-800">
                    {{ t('contact.first_name') }}
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
                <span class="text-sm font-medium text-neutral-800">
                    {{ t('contact.last_name') }}
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
                <span class="text-sm font-medium text-neutral-800">
                    {{ t('contact.email') }}
                </span>
                <input
                    v-model="form.email"
                    type="email"
                    name="email"
                    autocomplete="email"
                    :class="inputClass"
                />
                <span v-if="form.errors.email" class="text-sm text-brand-red">
                    {{ form.errors.email }}
                </span>
            </label>

            <label class="flex flex-col gap-2">
                <span class="text-sm font-medium text-neutral-800">
                    {{ t('contact.phone') }}
                </span>
                <input
                    v-model="form.phone"
                    type="tel"
                    name="phone"
                    autocomplete="tel"
                    :class="inputClass"
                />
                <span v-if="form.errors.phone" class="text-sm text-brand-red">
                    {{ form.errors.phone }}
                </span>
            </label>

            <label class="flex flex-col gap-2">
                <span class="text-sm font-medium text-neutral-800">
                    {{ t('contact.message') }}
                </span>
                <textarea
                    v-model="form.message"
                    name="message"
                    rows="4"
                    class="rounded-[1.5rem] bg-[#F3F3F3] px-5 py-4 text-sm text-neutral-900 outline-none focus:ring-2 focus:ring-neutral-200"
                />
                <span
                    v-if="form.errors.message"
                    class="text-sm text-brand-red"
                >
                    {{ form.errors.message }}
                </span>
            </label>

            <button
                type="submit"
                :disabled="form.processing"
                class="mt-2 inline-flex items-center justify-center rounded-full bg-brand-red px-6 py-3.5 text-sm font-semibold text-white transition-opacity hover:opacity-90 disabled:opacity-60"
            >
                {{ form.processing ? t('contact.sending') : t('contact.send') }}
            </button>
        </form>
    </div>
</template>
