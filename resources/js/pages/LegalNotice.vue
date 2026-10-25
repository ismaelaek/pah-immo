<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import LegalPage from '@/components/LegalPage.vue';
import { useLocale } from '@/composables/useLocale';

const { localized } = useLocale();
</script>

<template>
    <LegalPage
        title="Mentions légales"
        description="Mentions légales et conditions d’utilisation du site PAH Immo, agence immobilière en France : vente, achat et location de biens."
    >
        <template #intro>
            Consultez les règles d’utilisation du site PAH Immo, agence
            immobilière qui vous accompagne pour vendre, acheter ou louer un
            bien en France.
        </template>

        <section class="flex flex-col gap-3">
            <h2>1. Introduction</h2>
            <p>
                Bienvenue sur le site PAH Immo. En accédant à ces pages ou en
                utilisant nos services, vous acceptez les présentes mentions et
                conditions. Si vous n’y consentez pas, merci de ne pas utiliser
                le site.
            </p>
            <p>
                PAH Immo est une agence immobilière établie en France. Nous
                accompagnons les particuliers et les professionnels pour
                vendre, acheter ou louer une maison, un appartement, une villa,
                un studio, un duplex, un bureau ou un local commercial.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>2. Éditeur du site</h2>
            <p>
                Le site est édité par PAH Immo, agence immobilière. Les
                informations d’identification de l’éditeur (forme juridique,
                siège, immatriculation) sont communiquées sur demande via la
                page
                <Link :href="localized('/contact')" prefetch>Contact</Link>.
            </p>
            <p>
                Directeur de la publication : le représentant légal de PAH
                Immo.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>3. Définitions</h2>
            <ul>
                <li>
                    <strong>« PAH Immo », « nous »</strong> : l’agence
                    immobilière et l’éditeur du site.
                </li>
                <li>
                    <strong>« Utilisateur » ou « vous »</strong> : toute
                    personne qui consulte le site ou nous contacte.
                </li>
                <li>
                    <strong>« Bien »</strong> : un logement ou un local proposé
                    à la vente ou à la location.
                </li>
                <li>
                    <strong>« Annonce »</strong> : les informations, photos et
                    descriptifs d’un bien présentés sur le site.
                </li>
                <li>
                    <strong>« Demande »</strong> : tout message, appel ou
                    formulaire visant une visite, une estimation ou un
                    renseignement.
                </li>
            </ul>
        </section>

        <section class="flex flex-col gap-3">
            <h2>4. Utilisation du site</h2>
            <p>En utilisant PAH Immo, vous vous engagez à :</p>
            <ul>
                <li>
                    respecter les lois et règlements applicables en France ;
                </li>
                <li>
                    transmettre des informations exactes lors d’une demande de
                    contact ;
                </li>
                <li>
                    ne pas tenter d’accéder sans autorisation aux systèmes ou
                    aux données du site ;
                </li>
                <li>
                    ne pas perturber le fonctionnement du site, notamment par
                    l’envoi de virus ou de contenus malveillants ;
                </li>
                <li>
                    ne pas extraire ou copier le contenu du site à grande
                    échelle sans autorisation écrite préalable.
                </li>
            </ul>
            <p>
                Nous pouvons suspendre ou limiter l’accès en cas d’usage abusif
                ou de non-respect de ces règles.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>5. Nos services</h2>
            <p>
                PAH Immo vous accompagne dans la vente, l’achat et la location
                de biens immobiliers. Le site permet notamment de :
            </p>
            <ul>
                <li>découvrir des biens sélectionnés par l’agence ;</li>
                <li>prendre connaissance d’informations générales ;</li>
                <li>
                    demander une visite, une estimation ou un échange avec un
                    conseiller.
                </li>
            </ul>
            <p>
                Les informations publiées le sont à titre indicatif. Elles ne
                constituent pas, à elles seules, une offre contractuelle.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>6. Exactitude des informations</h2>
            <p>
                Nous vérifions avec soin les éléments que nous mettons en ligne.
                Cette vérification ne garantit pas, à elle seule, l’exactitude
                complète des surfaces, des disponibilités, des diagnostics ou
                de tout élément qui dépend du propriétaire, d’un notaire ou
                d’un prestataire tiers.
            </p>
            <p>
                Seuls les documents remis lors de la transaction font foi.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>7. Demandes de contact</h2>
            <p>
                Lorsque vous nous contactez, les informations transmises
                servent à traiter votre projet : visite, estimation, vente,
                achat ou location. Le traitement de vos données est décrit dans
                la
                <Link :href="localized('/confidentialite')" prefetch
                    >politique de confidentialité</Link
                >.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>8. Rôle de l’agence</h2>
            <p>
                PAH Immo agit en tant qu’agence immobilière. Nous estimons les
                biens, organisons les visites, accompagnons la négociation et
                suivons le dossier jusqu’à la signature, dans le cadre du
                mandat ou de la mission qui nous est confiée.
            </p>
            <p>
                L’acte de vente ou le bail est conclu entre les parties,
                généralement devant notaire ou selon les formes prévues par la
                loi. PAH Immo n’est pas partie à cet acte, sauf mention
                contraire dans un mandat écrit.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>9. Prix et disponibilité</h2>
            <p>
                Les prix, disponibilités, surfaces et descriptifs peuvent
                évoluer à tout moment. Les informations affichées sur le site
                sont susceptibles d’être mises à jour sans préavis. Seuls les
                éléments confirmés par l’agence et les actes signés sont
                opposables.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>10. Contenus des annonces</h2>
            <p>
                Les photographies, plans et visuels sont fournis à titre
                d’illustration. Ils peuvent différer de l’état réel du bien au
                moment de la visite. Nous vous invitons à toujours visiter le
                bien avant toute décision.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>11. Propriété intellectuelle</h2>
            <p>
                Les textes, logos, visuels, mise en page et fonctionnalités du
                site sont protégés. Toute reproduction, diffusion ou usage
                commercial, total ou partiel, sans autorisation préalable est
                interdit.
            </p>
            <p>
                Les photographies et descriptifs des biens restent la propriété
                de leurs titulaires. Leur publication sur le site n’autorise
                que l’affichage nécessaire à la présentation des annonces.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>12. Contenus transmis par l’utilisateur</h2>
            <p>
                Les informations que vous envoyez via un formulaire doivent
                être exactes et ne pas être illicites, diffamatoires, abusives
                ou contraires aux droits des tiers. Nous pouvons ne pas traiter
                une demande qui ne respecte pas ces principes.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>13. Indexation</h2>
            <p>
                Le contenu du site peut être indexé par les moteurs de
                recherche. PAH Immo n’a pas la maîtrise de l’affichage de ces
                résultats chez des tiers.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>14. Disponibilité du site</h2>
            <p>
                Nous mettons en œuvre des moyens raisonnables pour assurer
                l’accès au site. Des interruptions peuvent toutefois survenir
                pour maintenance, mise à jour, incident technique ou cause
                extérieure. L’accès continu n’est pas garanti.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>15. Activités interdites</h2>
            <p>Il est interdit :</p>
            <ul>
                <li>d’utiliser le site à des fins illicites ;</li>
                <li>de tenter d’accéder sans droit à nos systèmes ;</li>
                <li>de copier le contenu à grande échelle ;</li>
                <li>de perturber le fonctionnement du site ;</li>
                <li>d’usurper l’identité d’un tiers ;</li>
                <li>
                    de transmettre des informations fausses ou trompeuses.
                </li>
            </ul>
        </section>

        <section class="flex flex-col gap-3">
            <h2>16. Limitation de responsabilité</h2>
            <p>
                Dans les limites prévues par la loi, PAH Immo ne saurait être
                tenue responsable :
            </p>
            <ul>
                <li>
                    d’erreurs, omissions ou inexactitudes dans une annonce ;
                </li>
                <li>
                    des décisions prises sur la seule base des informations du
                    site ;
                </li>
                <li>
                    de la conclusion, de l’exécution ou de la résolution d’une
                    transaction entre les parties ;
                </li>
                <li>d’une interruption temporaire ou définitive du site.</li>
            </ul>
            <p>
                L’utilisation du site se fait sous votre responsabilité. Rien
                dans les présentes n’exclut une responsabilité qui ne peut être
                écartée en droit français.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>17. Données personnelles</h2>
            <p>
                Le traitement des données personnelles est décrit dans la
                <Link :href="localized('/confidentialite')" prefetch
                    >politique de confidentialité</Link
                >.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>18. Modifications</h2>
            <p>
                Ces mentions peuvent être mises à jour pour refléter
                l’évolution de nos services ou de la réglementation. La version
                publiée sur cette page est la version en vigueur.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>19. Droit applicable</h2>
            <p>
                Les présentes sont régies par le droit français. Tout litige
                relatif à leur interprétation ou à leur exécution relève des
                juridictions compétentes en France, sous réserve des règles
                d’ordre public applicables aux consommateurs.
            </p>
        </section>

        <section class="flex flex-col gap-3">
            <h2>20. Contact</h2>
            <p>
                Pour toute question sur ces mentions, écrivez-nous via la page
                <Link :href="localized('/contact')" prefetch>Contact</Link>.
            </p>
        </section>
    </LegalPage>
</template>
