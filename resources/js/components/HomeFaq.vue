<script setup lang="ts">
import { ref } from 'vue';

const items = [
    {
        question: 'Quels types de biens accompagnez-vous ?',
        answer: 'PAH Immo, agence immobilière, vous accompagne pour vendre, acheter ou louer une maison, un appartement, une villa, un studio, un duplex, un bureau ou un local commercial. Chaque bien est sélectionné selon votre projet, votre budget et le quartier recherché.',
    },
    {
        question: 'Comment savoir si un bien est un bon investissement ?',
        answer: 'Nous analysons le prix au mètre carré, le potentiel locatif, l’état du bien et la demande du secteur. Vous recevez une lecture claire du marché pour décider en toute confiance, que vous souhaitiez habiter le bien ou le mettre en location.',
    },
    {
        question: 'Pourquoi passer par une agence immobilière ?',
        answer: 'Un conseiller PAH Immo estime le bien, organise les visites, négocie et sécurise les étapes jusqu’à la signature. Vous gagnez du temps, évitez les mauvaises surprises et restez accompagné à chaque décision.',
    },
    {
        question: 'Comment se déroule un achat ou une vente ?',
        answer: 'Après un premier échange, nous définissons vos critères, présentons des biens adaptés, planifions les visites puis suivons l’offre, les diagnostics et le rendez-vous chez le notaire. Le même suivi s’applique si vous vendez : estimation, diffusion et accompagnement jusqu’à la vente.',
    },
    {
        question: 'Puis-je visiter un bien avant d’acheter ou de louer ?',
        answer: 'Oui. Les visites se font sur rendez-vous, seules ou accompagnées de votre conseiller. Vous prenez le temps d’évaluer le bien, le quartier et les prestations avant toute décision.',
    },
] as const;

const openIndex = ref(0);

function toggle(index: number): void {
    openIndex.value = openIndex.value === index ? -1 : index;
}
</script>

<template>
    <section
        id="faq"
        class="bg-white px-5 pb-20 sm:px-8 lg:px-12 lg:pb-28"
    >
        <div class="mx-auto max-w-[92rem]">
            <div
                class="mb-10 flex flex-col gap-6 lg:mb-14 lg:flex-row lg:items-end lg:justify-between"
            >
                <h2
                    class="whitespace-nowrap text-3xl leading-tight font-semibold tracking-tight text-neutral-950 sm:text-4xl lg:text-5xl"
                >
                    Questions fréquentes
                </h2>
                <p
                    class="max-w-lg text-sm leading-6 text-neutral-500 lg:text-right"
                >
                    PAH Immo vous accompagne pour vendre, acheter ou louer un
                    bien : prix, visites et démarches jusqu’à la signature.
                </p>
            </div>

            <div class="flex flex-col gap-3">
                <div
                    v-for="(item, index) in items"
                    :key="item.question"
                    class="rounded-[1.5rem] bg-[#F6F6F6] px-6 transition-colors sm:px-8"
                    :class="
                        openIndex === index ? 'bg-[#F3F3F3]' : 'hover:bg-[#F3F3F3]'
                    "
                >
                    <button
                        type="button"
                        class="flex w-full items-center justify-between gap-6 py-5 text-left sm:py-6"
                        :aria-expanded="openIndex === index"
                        @click="toggle(index)"
                    >
                        <span
                            class="text-base font-semibold tracking-tight transition-colors sm:text-lg"
                            :class="
                                openIndex === index
                                    ? 'text-brand-red'
                                    : 'text-neutral-950'
                            "
                        >
                            {{ item.question }}
                        </span>
                        <span
                            class="flex size-8 shrink-0 items-center justify-center rounded-full transition-colors"
                            :class="
                                openIndex === index
                                    ? 'bg-brand-red text-white'
                                    : 'text-neutral-900'
                            "
                            aria-hidden="true"
                        >
                            <svg
                                class="size-4 transition-transform duration-300"
                                :class="openIndex === index ? 'rotate-180' : ''"
                                viewBox="0 0 20 20"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    d="m5 7.5 5 5 5-5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </span>
                    </button>

                    <div
                        class="grid transition-[grid-template-rows] duration-300 ease-out"
                        :class="
                            openIndex === index
                                ? 'grid-rows-[1fr]'
                                : 'grid-rows-[0fr]'
                        "
                    >
                        <div class="overflow-hidden">
                            <p
                                class="max-w-4xl pb-6 text-sm leading-6 text-neutral-500"
                            >
                                {{ item.answer }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
