import type { BlogPost } from '@/components/BlogPostCard.vue';

export const blogPosts: BlogPost[] = [
    {
        slug: 'estimer-son-bien-avant-de-vendre',
        title: 'Estimer son bien avant de vendre : les 5 signaux qui comptent',
        description:
            'Prix au mètre carré, travaux, quartier et délai de vente : une lecture claire pour fixer un juste prix et attirer les bons acquéreurs.',
        author: 'Camille Moreau',
        publishedAt: '2026-10-22T09:00:00',
        webp: '/assets/images/living-room.webp',
        jpg: '/assets/images/living-room.jpg',
        alt: 'Salon lumineux d’un bien préparé pour une estimation',
    },
    {
        slug: 'acheter-sur-plan-sans-mauvaise-surprise',
        title: 'Acheter sur plan sans mauvaise surprise',
        description:
            'Garanties, échéancier et clauses à relire avant de signer. Ce qu’un conseiller PAH Immo vérifie pour vous.',
        author: 'Julien Bernard',
        publishedAt: '2026-10-19T10:30:00',
        webp: '/assets/images/highlights-1.webp',
        jpg: '/assets/images/highlights-1.jpg',
        alt: 'Villa contemporaine vendue sur plan',
    },
    {
        slug: 'dossier-de-location',
        title: 'Dossier de location : les pièces qui font la différence',
        description:
            'Revenus, garanties et présentation du dossier. Les réflexes pour rassurer un propriétaire dès la première visite.',
        author: 'Sarah El Amrani',
        publishedAt: '2026-10-17T08:15:00',
        webp: '/assets/images/bedroom.webp',
        jpg: '/assets/images/bedroom.jpg',
        alt: 'Chambre d’un appartement proposé à la location',
    },
    {
        slug: 'lyon-bordeaux-nice',
        title: 'Lyon, Bordeaux, Nice : où le mètre carré reste intéressant',
        description:
            'Trois marchés, trois dynamiques. On compare la demande, les prix et le potentiel locatif pour arbitrer sereinement.',
        author: 'Thomas Lefèvre',
        publishedAt: '2026-10-10T11:00:00',
        webp: '/assets/images/highlights-2.webp',
        jpg: '/assets/images/highlights-2.jpg',
        alt: 'Villa blanche dans un quartier recherché',
    },
    {
        slug: 'visiter-un-bien',
        title: 'Visiter un bien : les erreurs qui coûtent cher',
        description:
            'Lumière, nuisances, copropriété et diagnostics : une checklist courte pour ne rien laisser au hasard le jour J.',
        author: 'Camille Moreau',
        publishedAt: '2026-09-24T14:00:00',
        webp: '/assets/images/kitchen.webp',
        jpg: '/assets/images/kitchen.jpg',
        alt: 'Cuisine d’une maison visitée avec un conseiller PAH Immo',
    },
    {
        slug: 'travaux-avant-vente',
        title: 'Travaux avant vente : ce qui vaut vraiment le coup',
        description:
            "Peinture, cuisine, extérieur. On sépare les finitions qui séduisent des dépenses qui n'augmentent pas le prix.",
        author: 'Julien Bernard',
        publishedAt: '2026-09-12T09:45:00',
        webp: '/assets/images/home-hero.webp',
        jpg: '/assets/images/home-hero.jpg',
        alt: 'Villa contemporaine après une mise en valeur avant vente',
    },
];
