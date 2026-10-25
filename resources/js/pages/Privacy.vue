<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import LegalPage from '@/components/LegalPage.vue';
import { useLocale } from '@/composables/useLocale';

const { localized } = useLocale();
</script>

<template>
    <LegalPage
        title="Politique de confidentialité"
        description="Politique de confidentialité de PAH Immo : comment nous collectons, utilisons et protégeons vos données personnelles, conformément au RGPD."
    >
        <template #intro>
            Cette politique explique quelles données PAH Immo collecte,
            pourquoi, avec qui elles peuvent être partagées, et quels droits
            vous avez, conformément au règlement général sur la protection des
            données (RGPD).
        </template>

        <section class="flex flex-col gap-3">
            <h2>1. Introduction</h2>
            <p>
                PAH Immo, agence immobilière, s’engage à protéger vos données
                personnelles et à respecter votre vie privée lorsque vous
                consultez le site ou nous confiez un projet de vente, d’achat
                ou de location.
            </p>
            <p>
                En utilisant le site ou nos services, vous êtes informé des
                pratiques décrites ici.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>2. Responsable de traitement</h2>
            <p>
                Le responsable de traitement est PAH Immo, agence immobilière
                établie en France.
            </p>
            <p>
                Pour toute question relative à vos données ou pour exercer vos
                droits, utilisez la page
                <Link :href="localized('/contact')" prefetch>Contact</Link>.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>3. Données que nous collectons</h2>
            <p>
                Selon l’usage du site, nous pouvons collecter les catégories
                suivantes :
            </p>
            <ul>
                <li>
                    <strong>Identité</strong> : nom, prénom.
                </li>
                <li>
                    <strong>Contact</strong> : adresse e-mail, numéro de
                    téléphone, éventuellement une adresse postale si vous nous
                    la communiquez.
                </li>
                <li>
                    <strong>Projet</strong> : type de bien recherché ou à
                    vendre, budget, ville, message, date de la demande.
                </li>
                <li>
                    <strong>Données techniques</strong> : adresse IP, type de
                    navigateur, pages consultées, durée de visite, informations
                    sur l’appareil.
                </li>
                <li>
                    <strong>Cookies</strong> : témoins nécessaires au
                    fonctionnement du site et, le cas échéant, à la mesure
                    d’audience.
                </li>
            </ul>
        </section>

        <section class="flex flex-col gap-3">
            <h2>4. Finalités</h2>
            <p>Nous utilisons vos données pour :</p>
            <ul>
                <li>répondre à vos demandes et organiser les visites ;</li>
                <li>
                    vous accompagner dans la vente, l’achat ou la location d’un
                    bien ;
                </li>
                <li>améliorer le site et la qualité de nos services ;</li>
                <li>assurer la sécurité et le bon fonctionnement du site ;</li>
                <li>établir des statistiques d’usage anonymisées ;</li>
                <li>
                    vous envoyer des informations sur nos biens ou services
                    lorsque vous y avez consenti ;
                </li>
                <li>respecter nos obligations légales.</li>
            </ul>
        </section>

        <section class="flex flex-col gap-3">
            <h2>5. Bases légales</h2>
            <p>Conformément au RGPD, nous traitons vos données sur la base :</p>
            <ul>
                <li>de votre consentement, lorsque celui-ci est requis ;</li>
                <li>
                    de l’exécution des mesures précontractuelles ou du mandat
                    que vous nous confiez ;
                </li>
                <li>du respect de nos obligations légales ;</li>
                <li>
                    de notre intérêt légitime à exploiter, sécuriser et
                    améliorer le site, sans porter atteinte à vos droits.
                </li>
            </ul>
        </section>

        <section class="flex flex-col gap-3">
            <h2>6. Destinataires</h2>
            <p>
                Nous ne vendons pas et ne louons pas vos données personnelles.
                Elles peuvent toutefois être communiquées :
            </p>
            <ul>
                <li>
                    aux conseillers PAH Immo en charge de votre dossier ;
                </li>
                <li>
                    aux prestataires techniques (hébergement, maintenance,
                    messagerie) dans la limite nécessaire à leur mission ;
                </li>
                <li>
                    au notaire, aux diagnostiqueurs ou à d’autres
                    professionnels lorsque la transaction l’exige ;
                </li>
                <li>aux autorités compétentes lorsque la loi l’impose.</li>
            </ul>
        </section>

        <section class="flex flex-col gap-3">
            <h2>7. Demandes de contact</h2>
            <p>
                Lorsque vous envoyez un formulaire, les informations
                transmises servent uniquement à traiter votre projet avec
                PAH Immo. Les échanges ultérieurs relèvent de la relation
                entre vous et l’agence.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>8. Durée de conservation</h2>
            <p>
                Vos données sont conservées uniquement le temps nécessaire aux
                finalités pour lesquelles elles ont été collectées, ou pour
                respecter une obligation légale.
            </p>
            <ul>
                <li>
                    demandes de renseignement : le temps du traitement, puis
                    une durée limitée de suivi commercial si vous y avez
                    consenti ;
                </li>
                <li>
                    dossiers de vente, d’achat ou de location : la durée du
                    mandat et les délais de conservation légaux ;
                </li>
                <li>
                    données techniques et cookies : la durée prévue par la
                    réglementation applicable.
                </li>
            </ul>
        </section>

        <section class="flex flex-col gap-3">
            <h2>9. Hébergement et sécurité</h2>
            <p>
                Nous mettons en œuvre des mesures techniques et
                organisationnelles adaptées pour protéger vos données contre
                l’accès non autorisé, la perte, la divulgation ou l’altération.
            </p>
            <p>
                Le site est hébergé sur des serveurs professionnels. Aucun
                dispositif ne peut toutefois garantir une sécurité absolue.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>10. Transferts hors Union européenne</h2>
            <p>
                Certains prestataires techniques peuvent traiter des données
                hors de l’Union européenne. Le cas échéant, des garanties
                appropriées sont mises en place conformément au RGPD
                (clauses types, décision d’adéquation ou autre mécanisme
                reconnu).
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>11. Vos droits</h2>
            <p>
                Conformément au RGPD et à la loi Informatique et Libertés, vous
                disposez notamment des droits suivants :
            </p>
            <ul>
                <li>droit d’accès à vos données ;</li>
                <li>droit de rectification ;</li>
                <li>
                    droit à l’effacement, lorsque les conditions légales sont
                    réunies ;
                </li>
                <li>droit d’opposition à certains traitements ;</li>
                <li>droit à la limitation du traitement ;</li>
                <li>droit à la portabilité, lorsque cela s’applique ;</li>
                <li>
                    droit de retirer votre consentement à tout moment, sans
                    remettre en cause le traitement antérieur.
                </li>
            </ul>
            <p>
                Pour exercer ces droits, contactez-nous via la page
                <Link :href="localized('/contact')" prefetch>Contact</Link>. Vous pouvez
                également introduire une réclamation auprès de la CNIL
                (<a href="https://www.cnil.fr" rel="noopener noreferrer"
                    >www.cnil.fr</a
                >).
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>12. Mineurs</h2>
            <p>
                Le site s’adresse à des personnes majeures. Nous ne collectons
                pas sciemment de données concernant des mineurs de moins de 18
                ans.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>13. Décisions automatisées</h2>
            <p>
                PAH Immo ne prend aucune décision produisant des effets
                juridiques ou vous affectant de manière significative sur le
                seul fondement d’un traitement automatisé.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>14. Liens vers des sites tiers</h2>
            <p>
                Le site peut contenir des liens vers des sites externes. Nous
                ne sommes pas responsables de leurs pratiques de
                confidentialité ni de leurs contenus.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>15. Cookies</h2>
            <p>
                Nous utilisons des cookies essentiels au fonctionnement du
                site (session, sécurité, navigation). D’autres cookies, par
                exemple de mesure d’audience, ne sont déposés qu’avec votre
                accord lorsqu’ils ne sont pas strictement nécessaires.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>16. Modifications</h2>
            <p>
                Cette politique peut être mise à jour pour refléter
                l’évolution de nos services, de nos pratiques ou de la loi. La
                version publiée sur cette page est la version en vigueur.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>17. Contact</h2>
            <p>
                Pour toute question sur cette politique ou pour exercer vos
                droits, rendez-vous sur la page
                <Link :href="localized('/contact')" prefetch>Contact</Link>.
            </p>
            <p>
                Voir aussi les
                <Link :href="localized('/mentions-legales')" prefetch
                    >mentions légales</Link
                >.
            </p>
        </section>
    </LegalPage>
</template>
