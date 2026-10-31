<?php

declare(strict_types=1);

return [
    'nav' => [
        'home' => 'Home',
        'properties' => 'Properties',
        'about' => 'About',
        'blog' => 'Blog',
        'contact' => 'Contact',
        'faq' => 'FAQ',
        'legal' => 'Legal notice',
        'privacy' => 'Privacy policy',
        'primary' => 'Primary',
        'mobile' => 'Mobile',
        'toggle_menu' => 'Open menu',
        'language' => 'Language',
        'footer' => 'Footer',
        'footer_secondary' => 'Secondary footer',
    ],
    'footer' => [
        'rights' => '© :year PAH Immo. All rights reserved.',
    ],
    'property' => [
        'statuses' => [
            'sale' => 'For sale',
            'rent' => 'For rent',
        ],
        'bedrooms' => ':count bedroom|:count bedrooms',
        'bathrooms' => ':count bathroom|:count bathrooms',
        'per_month' => '/ month',
        'price_on_request' => 'Price on request',
    ],
    'home' => [
        'title' => 'Real estate agency',
        'description' => 'PAH Immo, real estate agency: sale, purchase and rental of villas, houses and apartments. Find the right property with tailored support.',
        'hero_alt' => 'Contemporary villa offered by PAH Immo, real estate agency',
        'hero_title' => 'Find the property that builds your future.',
        'search_title' => 'Find the right property',
        'type' => 'Property type',
        'all_types' => 'All types',
        'price' => 'Price',
        'city' => 'City',
        'all_cities' => 'All cities',
        'all_rooms' => 'All sizes',
        'rooms' => 'Rooms',
        'search' => 'Search',
        'prices' => [
            'all' => 'All prices',
            'under_250' => 'Under €250,000',
            '250_500' => '€250,000 – €500,000',
            '500_1m' => '€500,000 – €1m',
            'over_1m' => 'Over €1m',
        ],
        'room_options' => [
            'studio' => 'Studio',
            '1' => '1 bedroom',
            '2' => '2 bedrooms',
            '3' => '3 bedrooms',
            '4' => '4 bedrooms or more',
        ],
    ],
    'about' => [
        'title' => 'About',
        'description' => 'PAH Immo supports private clients and developers in selling and buying new and existing properties on the French Riviera. By appointment only.',
        'eyebrow' => 'Real estate agency · French Riviera',
        'hero_title' => 'Your property project on the French Riviera, supported from start to finish.',
        'hero_body' => 'PAH Immo supports private clients and developers in selling and buying new and existing properties on the French Riviera: apartments, houses, land and prestige properties.',
        'hero_alt' => 'Bright living room in a property handled by PAH Immo',
        'badge_title' => 'By appointment',
        'badge_body' => 'A single point of contact, available for you.',
        'facts' => [
            'Private clients & developers',
            'Selling & buying',
            'New & existing',
        ],
        'expertise_title' => 'The properties we handle',
        'expertise' => [
            [
                'title' => 'Apartments',
                'body' => 'New or existing, to sell or to buy.',
            ],
            [
                'title' => 'Houses',
                'body' => 'For a main or second home.',
            ],
            [
                'title' => 'Land',
                'body' => 'To build on or for a development project.',
            ],
            [
                'title' => 'Prestige properties',
                'body' => 'Exceptional properties, with all the discretion they call for.',
            ],
        ],
        'founder_eyebrow' => 'Managing director',
        'founder_name' => 'Pierre-Alain Héraud',
        'founder_role' => 'Sole trader',
        'founder_body' => 'The agency is run by Pierre-Alain Héraud, a sole trader and real estate transaction professional.',
        'founder_paragraphs' => [
            'He handles each project himself, from the first meeting to the signing: a single point of contact who knows your file and can be reached at every step.',
            'Sellers or buyers, private clients or developers: every project starts with an appointment, taking the time to understand your expectations before suggesting the right approach.',
        ],
        'founder_points' => [
            'A single point of contact',
            'By appointment',
            'No client funds held',
        ],
        'appointment_title' => 'By appointment only',
        'appointment_body' => 'To book an appointment, call or write to us: we will agree on a time together to talk about your project.',
        'call' => 'Call',
        'email' => 'Send an email',
        'contact_form' => 'Contact form',
    ],
    'properties' => [
        'title' => 'Properties',
        'description' => 'Browse PAH Immo properties for sale and for rent: villas, houses and apartments selected for their location, price and potential.',
        'results' => ':count property|:count properties',
        'empty' => 'No properties match your search.',
        'loading' => 'Loading properties…',
        'reset' => 'Reset filters',
        'previous' => 'Previous',
        'next' => 'Next',
        'missing' => 'Property not found',
        'back' => 'Back to properties',
        'details' => 'Details',
        'about' => 'Description',
        'gallery' => 'Photos',
        'equipment' => 'Equipment',
        'location' => 'Location',
        'view_on_map' => 'View on the map',
        'reference' => 'Reference',
        'type' => 'Type',
        'offer' => 'Offer',
        'price' => 'Price',
        'area' => 'Area',
        'bedrooms' => 'Bedrooms',
        'bathrooms' => 'Bathrooms',
        'city' => 'City',
        'neighborhood' => 'Neighbourhood',
        'contact_title' => 'Interested in this property?',
        'contact_body' => 'An adviser will answer your questions and arrange a viewing. Mention reference :reference in your message.',
        'map_cta' => 'Show on the map',
        'map_title' => 'Properties on the map',
        'map_description' => 'Explore PAH Immo properties on the map: shops, transport, schools and green spaces around each property for sale or rent.',
        'map_results' => ':count property in this area|:count properties in this area',
        'map_empty' => 'No properties in this area. Try zooming out or moving the map.',
        'map_label' => 'Map of properties',
        'map_search_area' => 'Search this area',
        'map_search_as_move' => 'Search as I move the map',
        'map_loading' => 'Searching…',
        'inquiry_message' => 'Hello, I would like more information about ":title" (ref. :reference).',
        'similar' => 'Similar properties',
        'similar_intro' => 'Other listings that may suit the same search.',
        'gallery_prev' => 'Previous photo',
        'gallery_next' => 'Next photo',
        'gallery_close' => 'Close gallery',
        'open_photo' => 'View photo :number',
        'reference_short' => 'Ref. :reference',
        'show_all_photos' => 'Show all :count photos',
        'share' => 'Share',
        'link_copied' => 'Link copied',
        'request_visit' => 'Request a viewing',
        'read_more' => 'Read more',
        'read_less' => 'Show less',
        'promise_reply' => 'Reply within 24 hours',
        'promise_visit' => 'Viewings at a time that suits you',
        'promise_support' => 'Support through to signing',
    ],
    'blog' => [
        'title' => 'Blog',
        'description' => 'PAH Immo advice for selling, buying or renting: market notes, checklists and agency insights to read before every important property decision.',
        'hero_alt' => 'Signing a property contract with PAH Immo',
        'hero_title' => 'Advice, trends and behind the scenes of real estate.',
        'latest' => 'Latest articles',
        'empty' => 'No articles yet.',
        'read_more' => 'Read more',
        'latest_intro' => 'Advice, trends and agency insights to sell, buy or rent with confidence.',
        'see_all' => 'See all articles',
        'missing' => 'Article not found',
        'back' => 'Back to the journal',
        'views' => ':count view|:count views',
    ],
    'contact' => [
        'title' => 'Contact',
        'description' => 'Contact PAH Immo, real estate agency: an adviser will help you sell, buy or rent a property.',
        'hero_alt' => 'Contemporary villa with pool offered by PAH Immo',
        'hero_title' => 'Let’s talk about your property project.',
        'form_title' => 'Write to us',
        'first_name' => 'First name',
        'last_name' => 'Last name',
        'email' => 'Email',
        'phone' => 'Phone',
        'message' => 'Message',
        'send' => 'Send',
        'sending' => 'Sending…',
        'success' => 'Your message has been sent. A PAH Immo adviser will get back to you shortly.',
        'errors' => [
            'first_name_required' => 'Please enter your first name.',
            'last_name_required' => 'Please enter your last name.',
            'email_required' => 'Please enter your email address.',
            'email_email' => 'Please enter a valid email address.',
            'phone_required' => 'Please enter your phone number.',
            'message_required' => 'Please write your message.',
            'message_max' => 'The message may not be greater than 2000 characters.',
        ],
        'info' => [
            'address_title' => 'Address',
            'address_body' => 'PAH Immo, real estate agency in France. Appointments on request in Paris, Lyon, Bordeaux and Nice.',
            'email_title' => 'Write to the team',
            'phone_title' => 'An adviser ready to help',
            'phone_body' => 'Our advisers support you to sell, buy or rent a property, from the first conversation through to signing.',
        ],
    ],
    'highlights' => [
        'title' => 'Ready to change property?',
        'subtitle' => 'The right home, even a compact one, changes everything.',
        'body' => 'PAH Immo, a real estate agency, helps you sell your house at the right price, buy an apartment or villa, and find a rental that fits your project.',
        'more' => 'Learn more',
        'aside' => 'Our advisers select properties for sale or rent and stay with you until signing.',
        'from_price' => 'From €250,000',
        'see_properties' => 'See properties',
        'prev' => 'Previous photo',
        'next' => 'Next photo',
        'slide' => 'View photo :number',
        'side_alt' => 'White villa with pool for sale, supported by PAH Immo',
        'slides' => [
            'Contemporary villa with pool offered for sale by PAH Immo',
            'Bright living room in a property for sale or rent',
            'Modern kitchen in a house supported by PAH Immo',
            'Contemporary bedroom in a property selected by PAH Immo',
        ],
        'stats' => [
            'Personal follow-up',
            'Services: sale, purchase, rental',
            'Cities covered',
            'Client satisfaction',
        ],
    ],
    'featured' => [
        'title' => 'Discover our exceptional properties',
        'intro' => 'Each listing highlights a unique property: a sought-after location, carefully chosen finishes and PAH Immo support to sell, buy or rent with confidence.',
        'see_all' => 'See all properties',
        'see_property' => 'View property',
    ],
    'best_value' => [
        'title' => 'Discover the best-value properties',
        'body' => 'Shops, transport, schools and green spaces: we select properties for sale or rent for their location, price and potential, so every square metre really counts.',
        'cta' => 'See nearby properties',
        'alt' => 'Neighbourhood map with shops, transport and services around a house selected by PAH Immo',
    ],
    'faq' => [
        'title' => 'Frequently asked questions',
        'intro' => 'PAH Immo supports you to sell, buy or rent a property: pricing, viewings and the process through to signing.',
        'items' => [
            [
                'question' => 'What types of property do you handle?',
                'answer' => 'PAH Immo, a real estate agency, helps you sell, buy or rent a house, apartment, villa, studio, duplex, office or commercial space. Each property is selected according to your project, budget and preferred neighbourhood.',
            ],
            [
                'question' => 'How do I know if a property is a good investment?',
                'answer' => 'We review the price per square metre, rental potential, condition of the property and local demand. You get a clear reading of the market so you can decide with confidence, whether you plan to live there or rent it out.',
            ],
            [
                'question' => 'Why use a real estate agency?',
                'answer' => 'A PAH Immo adviser values the property, organises viewings, negotiates and secures each step through to signing. You save time, avoid surprises and stay supported at every decision.',
            ],
            [
                'question' => 'How does a purchase or sale work?',
                'answer' => 'After a first conversation, we define your criteria, present suitable properties, plan viewings, then follow the offer, surveys and the appointment with the notary. The same support applies if you sell: valuation, marketing and guidance through to completion.',
            ],
            [
                'question' => 'Can I view a property before buying or renting?',
                'answer' => 'Yes. Viewings are by appointment, alone or with your adviser. You have time to assess the property, the neighbourhood and the features before any decision.',
            ],
        ],
    ],
    'legal_notice' => [
        'title' => 'Legal notice',
        'description' => 'Legal notice and terms of use of the PAH Immo website, a real estate agency in France: sale, purchase and rental of properties.',
        'intro' => 'Read the terms of use of the PAH Immo website, a real estate agency that helps you sell, buy or rent a property in France.',
        'sections' => [
            [
                'title' => '1. Introduction',
                'paragraphs' => [
                    'Welcome to the PAH Immo website. By accessing these pages or using our services, you accept these notices and terms. If you do not agree, please do not use the site.',
                    'PAH Immo is a real estate agency established in France. We support private individuals and professionals to sell, buy or rent a house, apartment, villa, studio, duplex, office or commercial space.',
                ],
            ],
            [
                'title' => '2. Publisher',
                'paragraphs' => [
                    'The site is published by PAH Immo, a real estate agency. The publisher’s identification details (legal form, registered office, registration) are available on request via the <a href=":contact">Contact</a> page.',
                    'Publication director: the legal representative of PAH Immo.',
                ],
            ],
            [
                'title' => '3. Definitions',
                'items' => [
                    '<strong>“PAH Immo”, “we”</strong>: the real estate agency and the publisher of the site.',
                    '<strong>“User” or “you”</strong>: anyone who browses the site or contacts us.',
                    '<strong>“Property”</strong>: a home or premises offered for sale or rent.',
                    '<strong>“Listing”</strong>: the information, photos and descriptions of a property shown on the site.',
                    '<strong>“Enquiry”</strong>: any message, call or form requesting a viewing, valuation or information.',
                ],
            ],
            [
                'title' => '4. Use of the site',
                'paragraphs' => ['By using PAH Immo, you agree to:'],
                'items' => [
                    'comply with the laws and regulations applicable in France;',
                    'provide accurate information when submitting a contact request;',
                    'not attempt to access the site’s systems or data without authorisation;',
                    'not disrupt the site, including by sending viruses or malicious content;',
                    'not extract or copy the site’s content at scale without prior written permission.',
                ],
                'after' => ['We may suspend or restrict access in the event of misuse or failure to follow these rules.'],
            ],
            [
                'title' => '5. Our services',
                'paragraphs' => ['PAH Immo supports you with the sale, purchase and rental of property. The site allows you in particular to:'],
                'items' => [
                    'discover properties selected by the agency;',
                    'read general information;',
                    'request a viewing, a valuation or a conversation with an adviser.',
                ],
                'after' => ['Information is published for guidance only. It does not, on its own, constitute a contractual offer.'],
            ],
            [
                'title' => '6. Accuracy of information',
                'paragraphs' => [
                    'We carefully check the information we publish. That check alone does not guarantee the complete accuracy of floor areas, availability, surveys or any detail that depends on the owner, a notary or a third-party provider.',
                    'Only the documents provided at the time of the transaction are binding.',
                ],
            ],
            [
                'title' => '7. Contact requests',
                'paragraphs' => [
                    'When you contact us, the information you send is used to handle your project: viewing, valuation, sale, purchase or rental. How we process your data is described in the <a href=":privacy">privacy policy</a>.',
                ],
            ],
            [
                'title' => '8. Role of the agency',
                'paragraphs' => [
                    'PAH Immo acts as a real estate agency. We value properties, organise viewings, support negotiation and follow the file through to signing, within the mandate or assignment entrusted to us.',
                    'The sale deed or lease is concluded between the parties, generally before a notary or in the form required by law. PAH Immo is not a party to that deed unless a written mandate says otherwise.',
                ],
            ],
            [
                'title' => '9. Price and availability',
                'paragraphs' => [
                    'Prices, availability, floor areas and descriptions may change at any time. Information on the site may be updated without notice. Only details confirmed by the agency and signed documents are binding.',
                ],
            ],
            [
                'title' => '10. Listing content',
                'paragraphs' => [
                    'Photographs, plans and visuals are provided for illustration. They may differ from the actual condition of the property at the time of viewing. We always invite you to visit the property before any decision.',
                ],
            ],
            [
                'title' => '11. Intellectual property',
                'paragraphs' => [
                    'The texts, logos, visuals, layout and features of the site are protected. Any reproduction, distribution or commercial use, in whole or in part, without prior authorisation is prohibited.',
                    'Photographs and descriptions of properties remain the property of their owners. Publication on the site only authorises the display needed to present the listings.',
                ],
            ],
            [
                'title' => '12. User-submitted content',
                'paragraphs' => [
                    'Information you send through a form must be accurate and must not be unlawful, defamatory, abusive or contrary to third-party rights. We may decline to process a request that does not respect these principles.',
                ],
            ],
            [
                'title' => '13. Indexing',
                'paragraphs' => [
                    'The site’s content may be indexed by search engines. PAH Immo does not control how those results are displayed by third parties.',
                ],
            ],
            [
                'title' => '14. Site availability',
                'paragraphs' => [
                    'We take reasonable steps to keep the site available. Interruptions may still occur for maintenance, updates, technical incidents or external causes. Continuous access is not guaranteed.',
                ],
            ],
            [
                'title' => '15. Prohibited activities',
                'paragraphs' => ['It is prohibited:'],
                'items' => [
                    'to use the site for unlawful purposes;',
                    'to attempt unauthorised access to our systems;',
                    'to copy the content at scale;',
                    'to disrupt the operation of the site;',
                    'to impersonate a third party;',
                    'to send false or misleading information.',
                ],
            ],
            [
                'title' => '16. Limitation of liability',
                'paragraphs' => ['To the extent permitted by law, PAH Immo cannot be held liable for:'],
                'items' => [
                    'errors, omissions or inaccuracies in a listing;',
                    'decisions taken solely on the basis of information on the site;',
                    'the conclusion, performance or termination of a transaction between the parties;',
                    'a temporary or permanent interruption of the site.',
                ],
                'after' => [
                    'Use of the site is at your own risk. Nothing in these terms excludes a liability that cannot be excluded under French law.',
                ],
            ],
            [
                'title' => '17. Personal data',
                'paragraphs' => [
                    'The processing of personal data is described in the <a href=":privacy">privacy policy</a>.',
                ],
            ],
            [
                'title' => '18. Changes',
                'paragraphs' => [
                    'These notices may be updated to reflect changes in our services or in the law. The version published on this page is the version in force.',
                ],
            ],
            [
                'title' => '19. Governing law',
                'paragraphs' => [
                    'These terms are governed by French law. Any dispute relating to their interpretation or performance falls under the competent courts in France, subject to mandatory consumer-protection rules.',
                ],
            ],
            [
                'title' => '20. Contact',
                'paragraphs' => [
                    'For any question about these notices, write to us via the <a href=":contact">Contact</a> page.',
                ],
            ],
        ],
    ],
    'privacy' => [
        'title' => 'Privacy policy',
        'description' => 'PAH Immo privacy policy: how we collect, use and protect your personal data, in accordance with the GDPR.',
        'intro' => 'This policy explains which data PAH Immo collects, why, with whom it may be shared, and what rights you have, in accordance with the General Data Protection Regulation (GDPR).',
        'sections' => [
            [
                'title' => '1. Introduction',
                'paragraphs' => [
                    'PAH Immo, a real estate agency, is committed to protecting your personal data and respecting your privacy when you browse the site or entrust us with a sale, purchase or rental project.',
                    'By using the site or our services, you are informed of the practices described here.',
                ],
            ],
            [
                'title' => '2. Data controller',
                'paragraphs' => [
                    'The data controller is PAH Immo, a real estate agency established in France.',
                    'For any question about your data or to exercise your rights, use the <a href=":contact">Contact</a> page.',
                ],
            ],
            [
                'title' => '3. Data we collect',
                'paragraphs' => ['Depending on how you use the site, we may collect the following categories:'],
                'items' => [
                    '<strong>Identity</strong>: last name, first name.',
                    '<strong>Contact</strong>: email address, phone number, and a postal address if you give us one.',
                    '<strong>Project</strong>: type of property sought or for sale, budget, city, message, date of the enquiry.',
                    '<strong>Technical data</strong>: IP address, browser type, pages viewed, visit duration, device information.',
                    '<strong>Cookies</strong>: cookies required for the site to work and, where applicable, audience measurement.',
                ],
            ],
            [
                'title' => '4. Purposes',
                'paragraphs' => ['We use your data to:'],
                'items' => [
                    'answer your requests and organise viewings;',
                    'support you with the sale, purchase or rental of a property;',
                    'improve the site and the quality of our services;',
                    'keep the site secure and running properly;',
                    'produce anonymised usage statistics;',
                    'send you information about our properties or services when you have consented;',
                    'comply with our legal obligations.',
                ],
            ],
            [
                'title' => '5. Legal bases',
                'paragraphs' => ['In accordance with the GDPR, we process your data on the basis of:'],
                'items' => [
                    'your consent, where it is required;',
                    'pre-contractual steps or the mandate you entrust to us;',
                    'compliance with our legal obligations;',
                    'our legitimate interest in operating, securing and improving the site, without overriding your rights.',
                ],
            ],
            [
                'title' => '6. Recipients',
                'paragraphs' => [
                    'We do not sell or rent your personal data. It may, however, be shared with:',
                ],
                'items' => [
                    'PAH Immo advisers handling your file;',
                    'technical providers (hosting, maintenance, email) to the extent needed for their assignment;',
                    'the notary, surveyors or other professionals when the transaction requires it;',
                    'competent authorities when the law requires it.',
                ],
            ],
            [
                'title' => '7. Contact requests',
                'paragraphs' => [
                    'When you submit a form, the information sent is used only to handle your project with PAH Immo. Later exchanges form part of the relationship between you and the agency.',
                ],
            ],
            [
                'title' => '8. Retention period',
                'paragraphs' => [
                    'Your data is kept only for as long as needed for the purposes for which it was collected, or to comply with a legal obligation.',
                ],
                'items' => [
                    'information requests: for the time needed to handle them, then a limited commercial follow-up period if you have consented;',
                    'sale, purchase or rental files: the duration of the mandate and the legal retention periods;',
                    'technical data and cookies: the period provided by applicable regulation.',
                ],
            ],
            [
                'title' => '9. Hosting and security',
                'paragraphs' => [
                    'We implement appropriate technical and organisational measures to protect your data against unauthorised access, loss, disclosure or alteration.',
                    'The site is hosted on professional servers. No measure can, however, guarantee absolute security.',
                ],
            ],
            [
                'title' => '10. Transfers outside the European Union',
                'paragraphs' => [
                    'Some technical providers may process data outside the European Union. Where that happens, appropriate safeguards are put in place in accordance with the GDPR (standard clauses, adequacy decision or another recognised mechanism).',
                ],
            ],
            [
                'title' => '11. Your rights',
                'paragraphs' => [
                    'Under the GDPR and the French Data Protection Act, you have in particular the following rights:',
                ],
                'items' => [
                    'right of access to your data;',
                    'right to rectification;',
                    'right to erasure, where the legal conditions are met;',
                    'right to object to certain processing;',
                    'right to restriction of processing;',
                    'right to portability, where it applies;',
                    'right to withdraw your consent at any time, without affecting prior processing.',
                ],
                'after' => [
                    'To exercise these rights, contact us via the <a href=":contact">Contact</a> page. You may also lodge a complaint with the CNIL (<a href="https://www.cnil.fr" rel="noopener noreferrer">www.cnil.fr</a>).',
                ],
            ],
            [
                'title' => '12. Minors',
                'paragraphs' => [
                    'The site is intended for adults. We do not knowingly collect data relating to minors under 18.',
                ],
            ],
            [
                'title' => '13. Automated decisions',
                'paragraphs' => [
                    'PAH Immo does not take any decision producing legal effects or significantly affecting you based solely on automated processing.',
                ],
            ],
            [
                'title' => '14. Links to third-party sites',
                'paragraphs' => [
                    'The site may contain links to external websites. We are not responsible for their privacy practices or their content.',
                ],
            ],
            [
                'title' => '15. Cookies',
                'paragraphs' => [
                    'We use cookies that are essential to the site (session, security, browsing). Other cookies, for example for audience measurement, are only placed with your consent when they are not strictly necessary.',
                ],
            ],
            [
                'title' => '16. Changes',
                'paragraphs' => [
                    'This policy may be updated to reflect changes in our services, practices or the law. The version published on this page is the version in force.',
                ],
            ],
            [
                'title' => '17. Contact',
                'paragraphs' => [
                    'For any question about this policy or to exercise your rights, go to the <a href=":contact">Contact</a> page.',
                    'See also the <a href=":legal">legal notice</a>.',
                ],
            ],
        ],
    ],
];
