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

const fields = [
    {
        name: 'first_name',
        label: 'contact.first_name',
        type: 'text',
        autocomplete: 'given-name',
    },
    {
        name: 'last_name',
        label: 'contact.last_name',
        type: 'text',
        autocomplete: 'family-name',
    },
    {
        name: 'email',
        label: 'contact.email',
        type: 'email',
        autocomplete: 'email',
    },
    {
        name: 'phone',
        label: 'contact.phone',
        type: 'tel',
        autocomplete: 'tel',
    },
] as const;

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
    'h-12 w-full rounded-2xl border border-transparent bg-[#F3F3F3] px-5 text-sm text-neutral-900 outline-none transition-colors placeholder:text-neutral-400 focus:border-neutral-300 focus:bg-white';
</script>

<template>
    <div>
        <div
            v-if="sent"
            class="flex flex-col items-center gap-4 px-4 py-12 text-center"
            role="status"
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
                class="max-w-sm text-base leading-7 font-medium text-neutral-900"
            >
                {{ page.flash.success }}
            </p>
        </div>

        <form
            v-else
            class="grid gap-x-4 gap-y-5 sm:grid-cols-2"
            @submit.prevent="submit"
        >
            <label
                v-for="field in fields"
                :key="field.name"
                class="flex flex-col gap-2"
            >
                <span class="text-sm font-medium text-neutral-800">
                    {{ t(field.label) }}
                </span>
                <input
                    v-model="form[field.name]"
                    :type="field.type"
                    :name="field.name"
                    :autocomplete="field.autocomplete"
                    :class="inputClass"
                />
                <span
                    v-if="form.errors[field.name]"
                    class="text-sm text-brand-red"
                >
                    {{ form.errors[field.name] }}
                </span>
            </label>

            <label class="flex flex-col gap-2 sm:col-span-2">
                <span class="text-sm font-medium text-neutral-800">
                    {{ t('contact.message') }}
                </span>
                <textarea
                    v-model="form.message"
                    name="message"
                    rows="4"
                    class="w-full resize-none rounded-2xl border border-transparent bg-[#F3F3F3] px-5 py-4 text-sm leading-6 text-neutral-900 transition-colors outline-none focus:border-neutral-300 focus:bg-white"
                />
                <span v-if="form.errors.message" class="text-sm text-brand-red">
                    {{ form.errors.message }}
                </span>
            </label>

            <button
                type="submit"
                :disabled="form.processing"
                class="inline-flex items-center justify-center gap-2 rounded-full bg-brand-red px-7 py-4 text-sm font-semibold text-white transition-opacity hover:opacity-90 disabled:opacity-60 sm:col-span-2"
            >
                {{ form.processing ? t('contact.sending') : t('contact.send') }}
                <span v-if="!form.processing" aria-hidden="true">→</span>
            </button>
        </form>
    </div>
</template>
