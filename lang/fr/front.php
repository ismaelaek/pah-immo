<?php

declare(strict_types=1);

return [
    'nav' => [
        'home' => 'Accueil',
        'properties' => 'Biens',
        'about' => 'À propos',
        'blog' => 'Blog',
        'contact' => 'Contact',
        'faq' => 'FAQ',
        'legal' => 'Mentions légales',
        'privacy' => 'Politique de confidentialité',
        'primary' => 'Principal',
        'mobile' => 'Mobile',
        'toggle_menu' => 'Ouvrir le menu',
        'language' => 'Langue',
        'footer' => 'Pied de page',
        'footer_secondary' => 'Pied de page secondaire',
    ],
    'footer' => [
        'rights' => '© :year PAH Immo. Tous droits réservés.',
    ],
    'property' => [
        'types' => [
            'apartment' => 'Appartement',
            'house' => 'Maison',
            'villa' => 'Villa',
            'studio' => 'Studio',
            'commercial_space' => 'Local commercial',
            'land' => 'Terrain',
            'office' => 'Bureau',
            'duplex' => 'Duplex',
        ],
        'statuses' => [
            'sale' => 'À vendre',
            'rent' => 'À louer',
        ],
        'stages' => [
            'off-plan' => 'Sur plan',
            'under-construction' => 'En construction',
            'ready' => 'Prêt à livrer',
        ],
        'bedrooms' => ':count chambre|:count chambres',
        'bathrooms' => ':count salle de bain|:count salles de bain',
    ],
    'home' => [
        'title' => 'Agence immobilière',
        'description' => 'PAH Immo, agence immobilière : vente, achat et location de villas, maisons et appartements. Trouvez le bien idéal avec un accompagnement personnalisé.',
        'hero_alt' => 'Villa contemporaine proposée par PAH Immo, agence immobilière',
        'hero_title' => 'Trouvez le bien immobilier qui construit votre avenir.',
        'search_title' => 'Trouvez le bien idéal',
        'type' => 'Type de bien',
        'all_types' => 'Tous les types',
        'price' => 'Prix',
        'city' => 'Ville',
        'all_cities' => 'Toutes les villes',
        'rooms' => 'Pièces',
        'search' => 'Rechercher',
        'prices' => [
            'all' => 'Tous les prix',
            'under_250' => 'Moins de 250 000 €',
            '250_500' => '250 – 500 000 €',
            '500_1m' => '500 000 – 1 M €',
            'over_1m' => 'Plus de 1 M €',
        ],
        'locations' => [
            'all' => 'Toute la France',
            'paris' => 'Paris',
            'lyon' => 'Lyon',
            'bordeaux' => 'Bordeaux',
            'nice' => 'Nice',
        ],
        'room_options' => [
            'studio' => 'Studio',
            '1' => '1 chambre',
            '2' => '2 chambres',
            '3' => '3 chambres',
            '4' => '4 chambres et plus',
        ],
    ],
    'about' => [
        'title' => 'À propos',
    ],
    'properties' => [
        'title' => 'Biens immobiliers',
    ],
    'blog' => [
        'title' => 'Blog',
        'description' => 'Conseils PAH Immo pour vendre, acheter ou louer : lectures de marché, checklists et coulisses d’agence, à lire avant chaque décision immobilière importante.',
        'hero_alt' => 'Signature d’un contrat immobilier accompagnée par PAH Immo',
        'hero_title' => 'Conseils, tendances et coulisses de l’immobilier.',
        'latest' => 'Les derniers articles',
        'empty' => 'Aucun article pour le moment.',
        'read_more' => 'Lire plus',
        'latest_intro' => 'Conseils, tendances et coulisses de l’agence pour vendre, acheter ou louer en toute confiance.',
        'see_all' => 'Voir tous les articles',
        'missing' => 'Article introuvable',
        'back' => 'Retour au journal',
        'views' => ':count vue|:count vues',
    ],
    'contact' => [
        'title' => 'Contact',
        'description' => 'Contactez PAH Immo, agence immobilière : un conseiller vous accompagne pour vendre, acheter ou louer un bien.',
        'hero_alt' => 'Villa contemporaine avec piscine proposée par PAH Immo',
        'hero_title' => 'Parlons de votre projet immobilier.',
        'form_title' => 'Écrivez-nous',
        'first_name' => 'Prénom',
        'last_name' => 'Nom',
        'email' => 'E-mail',
        'phone' => 'Téléphone',
        'message' => 'Message',
        'send' => 'Envoyer',
        'sending' => 'Envoi…',
        'success' => 'Votre message a bien été envoyé. Un conseiller PAH Immo vous répondra rapidement.',
        'errors' => [
            'first_name_required' => 'Indiquez votre prénom.',
            'last_name_required' => 'Indiquez votre nom.',
            'email_required' => 'Indiquez votre adresse e-mail.',
            'email_email' => 'Indiquez une adresse e-mail valide.',
            'phone_required' => 'Indiquez votre numéro de téléphone.',
            'message_required' => 'Écrivez votre message.',
            'message_max' => 'Le message ne peut pas dépasser 2000 caractères.',
        ],
        'info' => [
            'address_title' => 'Adresse',
            'address_body' => 'PAH Immo, agence immobilière en France. Rendez-vous sur demande à Paris, Lyon, Bordeaux et Nice.',
            'email_title' => 'Écrire à l’équipe',
            'phone_title' => 'Un conseiller à votre écoute',
            'phone_body' => 'Nos conseillers vous accompagnent pour vendre, acheter ou louer un bien, du premier échange jusqu’à la signature.',
        ],
    ],
    'highlights' => [
        'title' => 'Prêt à changer de bien ?',
        'subtitle' => 'Un bon bien, même compact, change tout.',
        'body' => 'PAH Immo, agence immobilière, vous accompagne pour vendre votre maison au juste prix, acheter un appartement ou une villa, et trouver une location adaptée à votre projet.',
        'more' => 'En savoir plus',
        'aside' => 'Nos conseillers sélectionnent des biens à vendre ou à louer et restent à vos côtés jusqu’à la signature.',
        'from_price' => 'À partir de 250 000 €',
        'see_properties' => 'Voir les biens',
        'prev' => 'Photo précédente',
        'next' => 'Photo suivante',
        'slide' => 'Voir la photo :number',
        'side_alt' => 'Villa blanche avec piscine à vendre accompagnée par PAH Immo',
        'slides' => [
            'Villa contemporaine avec piscine proposée à la vente par PAH Immo',
            'Salon lumineux d’un bien immobilier à vendre ou à louer',
            'Cuisine moderne d’une maison accompagnée par PAH Immo',
            'Chambre contemporaine dans un bien sélectionné par PAH Immo',
        ],
        'stats' => [
            'Suivi personnalisé',
            'Services : vente, achat, location',
            'Villes d’intervention',
            'Satisfaction client',
        ],
    ],
    'featured' => [
        'title' => 'Découvrez nos biens d’exception',
        'intro' => 'Chaque annonce met en avant un bien unique : emplacement recherché, prestations soignées et un accompagnement PAH Immo pour vendre, acheter ou louer en toute confiance.',
        'see_all' => 'Voir tous les biens',
        'see_property' => 'Voir le bien',
        'alts' => [
            'horizon' => 'Villa contemporaine à vendre à Lyon, proposée par PAH Immo',
            'parc' => 'Maison avec piscine à vendre à Bordeaux, accompagnée par PAH Immo',
            'cote' => 'Villa blanche à vendre à Nice, sélectionnée par PAH Immo',
            'lumiere' => 'Salon d’un appartement lumineux à louer à Paris',
            'michel' => 'Cuisine d’une maison familiale à vendre à Lyon',
            'jardins' => 'Chambre d’un duplex à louer à Bordeaux, proposé par PAH Immo',
        ],
    ],
    'best_value' => [
        'title' => 'Découvrez les biens avec la meilleure valeur',
        'body' => 'Commerces, transports, écoles et espaces verts : nous sélectionnons des biens à vendre ou à louer pour leur emplacement, leur prix et leur potentiel, afin que chaque mètre carré compte vraiment.',
        'cta' => 'Voir les biens à proximité',
        'alt' => 'Carte d’un quartier avec commerces, transports et services autour d’une maison sélectionnée par PAH Immo',
    ],
    'faq' => [
        'title' => 'Questions fréquentes',
        'intro' => 'PAH Immo vous accompagne pour vendre, acheter ou louer un bien : prix, visites et démarches jusqu’à la signature.',
        'items' => [
            [
                'question' => 'Quels types de biens accompagnez-vous ?',
                'answer' => 'PAH Immo, agence immobilière, vous accompagne pour vendre, acheter ou louer une maison, un appartement, une villa, un studio, un duplex, un bureau ou un local commercial. Chaque bien est sélectionné selon votre projet, votre budget et le quartier recherché.',
            ],
            [
                'question' => 'Comment savoir si un bien est un bon investissement ?',
                'answer' => 'Nous analysons le prix au mètre carré, le potentiel locatif, l’état du bien et la demande du secteur. Vous recevez une lecture claire du marché pour décider en toute confiance, que vous souhaitiez habiter le bien ou le mettre en location.',
            ],
            [
                'question' => 'Pourquoi passer par une agence immobilière ?',
                'answer' => 'Un conseiller PAH Immo estime le bien, organise les visites, négocie et sécurise les étapes jusqu’à la signature. Vous gagnez du temps, évitez les mauvaises surprises et restez accompagné à chaque décision.',
            ],
            [
                'question' => 'Comment se déroule un achat ou une vente ?',
                'answer' => 'Après un premier échange, nous définissons vos critères, présentons des biens adaptés, planifions les visites puis suivons l’offre, les diagnostics et le rendez-vous chez le notaire. Le même suivi s’applique si vous vendez : estimation, diffusion et accompagnement jusqu’à la vente.',
            ],
            [
                'question' => 'Puis-je visiter un bien avant d’acheter ou de louer ?',
                'answer' => 'Oui. Les visites se font sur rendez-vous, seules ou accompagnées de votre conseiller. Vous prenez le temps d’évaluer le bien, le quartier et les prestations avant toute décision.',
            ],
        ],
    ],
    'legal_notice' => [
        'title' => 'Mentions légales',
        'description' => 'Mentions légales et conditions d’utilisation du site PAH Immo, agence immobilière en France : vente, achat et location de biens.',
        'intro' => 'Consultez les règles d’utilisation du site PAH Immo, agence immobilière qui vous accompagne pour vendre, acheter ou louer un bien en France.',
        'sections' => [
            [
                'title' => '1. Introduction',
                'paragraphs' => [
                    'Bienvenue sur le site PAH Immo. En accédant à ces pages ou en utilisant nos services, vous acceptez les présentes mentions et conditions. Si vous n’y consentez pas, merci de ne pas utiliser le site.',
                    'PAH Immo est une agence immobilière établie en France. Nous accompagnons les particuliers et les professionnels pour vendre, acheter ou louer une maison, un appartement, une villa, un studio, un duplex, un bureau ou un local commercial.',
                ],
            ],
            [
                'title' => '2. Éditeur du site',
                'paragraphs' => [
                    'Le site est édité par PAH Immo, agence immobilière. Les informations d’identification de l’éditeur (forme juridique, siège, immatriculation) sont communiquées sur demande via la page <a href=":contact">Contact</a>.',
                    'Directeur de la publication : le représentant légal de PAH Immo.',
                ],
            ],
            [
                'title' => '3. Définitions',
                'items' => [
                    '<strong>« PAH Immo », « nous »</strong> : l’agence immobilière et l’éditeur du site.',
                    '<strong>« Utilisateur » ou « vous »</strong> : toute personne qui consulte le site ou nous contacte.',
                    '<strong>« Bien »</strong> : un logement ou un local proposé à la vente ou à la location.',
                    '<strong>« Annonce »</strong> : les informations, photos et descriptifs d’un bien présentés sur le site.',
                    '<strong>« Demande »</strong> : tout message, appel ou formulaire visant une visite, une estimation ou un renseignement.',
                ],
            ],
            [
                'title' => '4. Utilisation du site',
                'paragraphs' => ['En utilisant PAH Immo, vous vous engagez à :'],
                'items' => [
                    'respecter les lois et règlements applicables en France ;',
                    'transmettre des informations exactes lors d’une demande de contact ;',
                    'ne pas tenter d’accéder sans autorisation aux systèmes ou aux données du site ;',
                    'ne pas perturber le fonctionnement du site, notamment par l’envoi de virus ou de contenus malveillants ;',
                    'ne pas extraire ou copier le contenu du site à grande échelle sans autorisation écrite préalable.',
                ],
                'after' => ['Nous pouvons suspendre ou limiter l’accès en cas d’usage abusif ou de non-respect de ces règles.'],
            ],
            [
                'title' => '5. Nos services',
                'paragraphs' => ['PAH Immo vous accompagne dans la vente, l’achat et la location de biens immobiliers. Le site permet notamment de :'],
                'items' => [
                    'découvrir des biens sélectionnés par l’agence ;',
                    'prendre connaissance d’informations générales ;',
                    'demander une visite, une estimation ou un échange avec un conseiller.',
                ],
                'after' => ['Les informations publiées le sont à titre indicatif. Elles ne constituent pas, à elles seules, une offre contractuelle.'],
            ],
            [
                'title' => '6. Exactitude des informations',
                'paragraphs' => [
                    'Nous vérifions avec soin les éléments que nous mettons en ligne. Cette vérification ne garantit pas, à elle seule, l’exactitude complète des surfaces, des disponibilités, des diagnostics ou de tout élément qui dépend du propriétaire, d’un notaire ou d’un prestataire tiers.',
                    'Seuls les documents remis lors de la transaction font foi.',
                ],
            ],
            [
                'title' => '7. Demandes de contact',
                'paragraphs' => [
                    'Lorsque vous nous contactez, les informations transmises servent à traiter votre projet : visite, estimation, vente, achat ou location. Le traitement de vos données est décrit dans la <a href=":privacy">politique de confidentialité</a>.',
                ],
            ],
            [
                'title' => '8. Rôle de l’agence',
                'paragraphs' => [
                    'PAH Immo agit en tant qu’agence immobilière. Nous estimons les biens, organisons les visites, accompagnons la négociation et suivons le dossier jusqu’à la signature, dans le cadre du mandat ou de la mission qui nous est confiée.',
                    'L’acte de vente ou le bail est conclu entre les parties, généralement devant notaire ou selon les formes prévues par la loi. PAH Immo n’est pas partie à cet acte, sauf mention contraire dans un mandat écrit.',
                ],
            ],
            [
                'title' => '9. Prix et disponibilité',
                'paragraphs' => [
                    'Les prix, disponibilités, surfaces et descriptifs peuvent évoluer à tout moment. Les informations affichées sur le site sont susceptibles d’être mises à jour sans préavis. Seuls les éléments confirmés par l’agence et les actes signés sont opposables.',
                ],
            ],
            [
                'title' => '10. Contenus des annonces',
                'paragraphs' => [
                    'Les photographies, plans et visuels sont fournis à titre d’illustration. Ils peuvent différer de l’état réel du bien au moment de la visite. Nous vous invitons à toujours visiter le bien avant toute décision.',
                ],
            ],
            [
                'title' => '11. Propriété intellectuelle',
                'paragraphs' => [
                    'Les textes, logos, visuels, mise en page et fonctionnalités du site sont protégés. Toute reproduction, diffusion ou usage commercial, total ou partiel, sans autorisation préalable est interdit.',
                    'Les photographies et descriptifs des biens restent la propriété de leurs titulaires. Leur publication sur le site n’autorise que l’affichage nécessaire à la présentation des annonces.',
                ],
            ],
            [
                'title' => '12. Contenus transmis par l’utilisateur',
                'paragraphs' => [
                    'Les informations que vous envoyez via un formulaire doivent être exactes et ne pas être illicites, diffamatoires, abusives ou contraires aux droits des tiers. Nous pouvons ne pas traiter une demande qui ne respecte pas ces principes.',
                ],
            ],
            [
                'title' => '13. Indexation',
                'paragraphs' => [
                    'Le contenu du site peut être indexé par les moteurs de recherche. PAH Immo n’a pas la maîtrise de l’affichage de ces résultats chez des tiers.',
                ],
            ],
            [
                'title' => '14. Disponibilité du site',
                'paragraphs' => [
                    'Nous mettons en œuvre des moyens raisonnables pour assurer l’accès au site. Des interruptions peuvent toutefois survenir pour maintenance, mise à jour, incident technique ou cause extérieure. L’accès continu n’est pas garanti.',
                ],
            ],
            [
                'title' => '15. Activités interdites',
                'paragraphs' => ['Il est interdit :'],
                'items' => [
                    'd’utiliser le site à des fins illicites ;',
                    'de tenter d’accéder sans droit à nos systèmes ;',
                    'de copier le contenu à grande échelle ;',
                    'de perturber le fonctionnement du site ;',
                    'd’usurper l’identité d’un tiers ;',
                    'de transmettre des informations fausses ou trompeuses.',
                ],
            ],
            [
                'title' => '16. Limitation de responsabilité',
                'paragraphs' => ['Dans les limites prévues par la loi, PAH Immo ne saurait être tenue responsable :'],
                'items' => [
                    'd’erreurs, omissions ou inexactitudes dans une annonce ;',
                    'des décisions prises sur la seule base des informations du site ;',
                    'de la conclusion, de l’exécution ou de la résolution d’une transaction entre les parties ;',
                    'd’une interruption temporaire ou définitive du site.',
                ],
                'after' => [
                    'L’utilisation du site se fait sous votre responsabilité. Rien dans les présentes n’exclut une responsabilité qui ne peut être écartée en droit français.',
                ],
            ],
            [
                'title' => '17. Données personnelles',
                'paragraphs' => [
                    'Le traitement des données personnelles est décrit dans la <a href=":privacy">politique de confidentialité</a>.',
                ],
            ],
            [
                'title' => '18. Modifications',
                'paragraphs' => [
                    'Ces mentions peuvent être mises à jour pour refléter l’évolution de nos services ou de la réglementation. La version publiée sur cette page est la version en vigueur.',
                ],
            ],
            [
                'title' => '19. Droit applicable',
                'paragraphs' => [
                    'Les présentes sont régies par le droit français. Tout litige relatif à leur interprétation ou à leur exécution relève des juridictions compétentes en France, sous réserve des règles d’ordre public applicables aux consommateurs.',
                ],
            ],
            [
                'title' => '20. Contact',
                'paragraphs' => [
                    'Pour toute question sur ces mentions, écrivez-nous via la page <a href=":contact">Contact</a>.',
                ],
            ],
        ],
    ],
    'privacy' => [
        'title' => 'Politique de confidentialité',
        'description' => 'Politique de confidentialité de PAH Immo : comment nous collectons, utilisons et protégeons vos données personnelles, conformément au RGPD.',
        'intro' => 'Cette politique explique quelles données PAH Immo collecte, pourquoi, avec qui elles peuvent être partagées, et quels droits vous avez, conformément au règlement général sur la protection des données (RGPD).',
        'sections' => [
            [
                'title' => '1. Introduction',
                'paragraphs' => [
                    'PAH Immo, agence immobilière, s’engage à protéger vos données personnelles et à respecter votre vie privée lorsque vous consultez le site ou nous confiez un projet de vente, d’achat ou de location.',
                    'En utilisant le site ou nos services, vous êtes informé des pratiques décrites ici.',
                ],
            ],
            [
                'title' => '2. Responsable de traitement',
                'paragraphs' => [
                    'Le responsable de traitement est PAH Immo, agence immobilière établie en France.',
                    'Pour toute question relative à vos données ou pour exercer vos droits, utilisez la page <a href=":contact">Contact</a>.',
                ],
            ],
            [
                'title' => '3. Données que nous collectons',
                'paragraphs' => ['Selon l’usage du site, nous pouvons collecter les catégories suivantes :'],
                'items' => [
                    '<strong>Identité</strong> : nom, prénom.',
                    '<strong>Contact</strong> : adresse e-mail, numéro de téléphone, éventuellement une adresse postale si vous nous la communiquez.',
                    '<strong>Projet</strong> : type de bien recherché ou à vendre, budget, ville, message, date de la demande.',
                    '<strong>Données techniques</strong> : adresse IP, type de navigateur, pages consultées, durée de visite, informations sur l’appareil.',
                    '<strong>Cookies</strong> : témoins nécessaires au fonctionnement du site et, le cas échéant, à la mesure d’audience.',
                ],
            ],
            [
                'title' => '4. Finalités',
                'paragraphs' => ['Nous utilisons vos données pour :'],
                'items' => [
                    'répondre à vos demandes et organiser les visites ;',
                    'vous accompagner dans la vente, l’achat ou la location d’un bien ;',
                    'améliorer le site et la qualité de nos services ;',
                    'assurer la sécurité et le bon fonctionnement du site ;',
                    'établir des statistiques d’usage anonymisées ;',
                    'vous envoyer des informations sur nos biens ou services lorsque vous y avez consenti ;',
                    'respecter nos obligations légales.',
                ],
            ],
            [
                'title' => '5. Bases légales',
                'paragraphs' => ['Conformément au RGPD, nous traitons vos données sur la base :'],
                'items' => [
                    'de votre consentement, lorsque celui-ci est requis ;',
                    'de l’exécution des mesures précontractuelles ou du mandat que vous nous confiez ;',
                    'du respect de nos obligations légales ;',
                    'de notre intérêt légitime à exploiter, sécuriser et améliorer le site, sans porter atteinte à vos droits.',
                ],
            ],
            [
                'title' => '6. Destinataires',
                'paragraphs' => [
                    'Nous ne vendons pas et ne louons pas vos données personnelles. Elles peuvent toutefois être communiquées :',
                ],
                'items' => [
                    'aux conseillers PAH Immo en charge de votre dossier ;',
                    'aux prestataires techniques (hébergement, maintenance, messagerie) dans la limite nécessaire à leur mission ;',
                    'au notaire, aux diagnostiqueurs ou à d’autres professionnels lorsque la transaction l’exige ;',
                    'aux autorités compétentes lorsque la loi l’impose.',
                ],
            ],
            [
                'title' => '7. Demandes de contact',
                'paragraphs' => [
                    'Lorsque vous envoyez un formulaire, les informations transmises servent uniquement à traiter votre projet avec PAH Immo. Les échanges ultérieurs relèvent de la relation entre vous et l’agence.',
                ],
            ],
            [
                'title' => '8. Durée de conservation',
                'paragraphs' => [
                    'Vos données sont conservées uniquement le temps nécessaire aux finalités pour lesquelles elles ont été collectées, ou pour respecter une obligation légale.',
                ],
                'items' => [
                    'demandes de renseignement : le temps du traitement, puis une durée limitée de suivi commercial si vous y avez consenti ;',
                    'dossiers de vente, d’achat ou de location : la durée du mandat et les délais de conservation légaux ;',
                    'données techniques et cookies : la durée prévue par la réglementation applicable.',
                ],
            ],
            [
                'title' => '9. Hébergement et sécurité',
                'paragraphs' => [
                    'Nous mettons en œuvre des mesures techniques et organisationnelles adaptées pour protéger vos données contre l’accès non autorisé, la perte, la divulgation ou l’altération.',
                    'Le site est hébergé sur des serveurs professionnels. Aucun dispositif ne peut toutefois garantir une sécurité absolue.',
                ],
            ],
            [
                'title' => '10. Transferts hors Union européenne',
                'paragraphs' => [
                    'Certains prestataires techniques peuvent traiter des données hors de l’Union européenne. Le cas échéant, des garanties appropriées sont mises en place conformément au RGPD (clauses types, décision d’adéquation ou autre mécanisme reconnu).',
                ],
            ],
            [
                'title' => '11. Vos droits',
                'paragraphs' => [
                    'Conformément au RGPD et à la loi Informatique et Libertés, vous disposez notamment des droits suivants :',
                ],
                'items' => [
                    'droit d’accès à vos données ;',
                    'droit de rectification ;',
                    'droit à l’effacement, lorsque les conditions légales sont réunies ;',
                    'droit d’opposition à certains traitements ;',
                    'droit à la limitation du traitement ;',
                    'droit à la portabilité, lorsque cela s’applique ;',
                    'droit de retirer votre consentement à tout moment, sans remettre en cause le traitement antérieur.',
                ],
                'after' => [
                    'Pour exercer ces droits, contactez-nous via la page <a href=":contact">Contact</a>. Vous pouvez également introduire une réclamation auprès de la CNIL (<a href="https://www.cnil.fr" rel="noopener noreferrer">www.cnil.fr</a>).',
                ],
            ],
            [
                'title' => '12. Mineurs',
                'paragraphs' => [
                    'Le site s’adresse à des personnes majeures. Nous ne collectons pas sciemment de données concernant des mineurs de moins de 18 ans.',
                ],
            ],
            [
                'title' => '13. Décisions automatisées',
                'paragraphs' => [
                    'PAH Immo ne prend aucune décision produisant des effets juridiques ou vous affectant de manière significative sur le seul fondement d’un traitement automatisé.',
                ],
            ],
            [
                'title' => '14. Liens vers des sites tiers',
                'paragraphs' => [
                    'Le site peut contenir des liens vers des sites externes. Nous ne sommes pas responsables de leurs pratiques de confidentialité ni de leurs contenus.',
                ],
            ],
            [
                'title' => '15. Cookies',
                'paragraphs' => [
                    'Nous utilisons des cookies essentiels au fonctionnement du site (session, sécurité, navigation). D’autres cookies, par exemple de mesure d’audience, ne sont déposés qu’avec votre accord lorsqu’ils ne sont pas strictement nécessaires.',
                ],
            ],
            [
                'title' => '16. Modifications',
                'paragraphs' => [
                    'Cette politique peut être mise à jour pour refléter l’évolution de nos services, de nos pratiques ou de la loi. La version publiée sur cette page est la version en vigueur.',
                ],
            ],
            [
                'title' => '17. Contact',
                'paragraphs' => [
                    'Pour toute question sur cette politique ou pour exercer vos droits, rendez-vous sur la page <a href=":contact">Contact</a>.',
                    'Voir aussi les <a href=":legal">mentions légales</a>.',
                ],
            ],
        ],
    ],
];
