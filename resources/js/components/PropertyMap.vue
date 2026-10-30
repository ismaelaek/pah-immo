<script setup lang="ts">
import 'leaflet/dist/leaflet.css';
import { router } from '@inertiajs/vue3';
import type { DivIcon, Map as LeafletMap, Marker } from 'leaflet';
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import type { Property } from '@/components/PropertyCard.vue';
import { useLocale } from '@/composables/useLocale';
import { usePropertyFormatting } from '@/composables/usePropertyFormatting';
import { propertyVisit } from '@/composables/usePropertyVisit';

type Leaflet = typeof import('leaflet');

type LocatedProperty = Property & {
    latitude: number;
    longitude: number;
};

export type MapBounds = {
    south: number;
    west: number;
    north: number;
    east: number;
};

const props = withDefaults(
    defineProps<{
        properties: Property[];
        initialBounds?: MapBounds | null;
        activeSlug?: string | null;
        loading?: boolean;
    }>(),
    {
        initialBounds: null,
        activeSlug: null,
        loading: false,
    },
);

const emit = defineEmits<{
    select: [slug: string];
    moved: [bounds: MapBounds];
}>();

const { localized, t, tc } = useLocale();
const { formatPrice, formatCompactPrice, formatAddress } =
    usePropertyFormatting();

const container = ref<HTMLElement | null>(null);

const markerClasses =
    'inline-flex -translate-x-1/2 -translate-y-full items-center whitespace-nowrap rounded-full px-3 py-1.5 text-xs font-semibold shadow-[0_6px_20px_rgba(15,23,42,0.25)] transition-colors';
const idleMarkerClasses = 'bg-white text-neutral-950';
const activeMarkerClasses = 'bg-brand-red text-white';

let leaflet: Leaflet | null = null;
let map: LeafletMap | null = null;
let ignoreMovesUntil = 0;

/**
 * Moves started by the code (initial framing, fitting results, popup nudges)
 * must not trigger a new area search.
 */
function ignoreProgrammaticMoves(): void {
    ignoreMovesUntil = performance.now() + 600;
}
const markers = new Map<string, Marker>();

/**
 * Metropolitan France, from Brittany to Alsace and Dunkirk to Corsica.
 */
const franceBounds: [[number, number], [number, number]] = [
    [41.3, -5.2],
    [51.1, 9.6],
];

function locatedProperties(): LocatedProperty[] {
    return props.properties.filter(
        (property): property is LocatedProperty =>
            property.latitude !== null && property.longitude !== null,
    );
}

function markerIcon(property: Property, active: boolean): DivIcon {
    const label = document.createElement('span');
    label.className = `${markerClasses} ${active ? activeMarkerClasses : idleMarkerClasses}`;
    label.textContent = formatCompactPrice(property);

    return leaflet!.divIcon({
        html: label,
        className: '',
        iconSize: [0, 0],
    });
}

function node<K extends keyof HTMLElementTagNameMap>(
    tag: K,
    className: string,
    text?: string,
): HTMLElementTagNameMap[K] {
    const element = document.createElement(tag);
    element.className = className;

    if (text !== undefined) {
        element.textContent = text;
    }

    return element;
}

function visitProperty(event: Event, href: string): void {
    event.preventDefault();
    router.visit(href, propertyVisit);
}

function popupContent(property: Property): HTMLElement {
    const href = localized(`/properties/${property.slug}`);
    const card = node('div', 'font-sans text-neutral-950 antialiased');

    const media = node('a', 'relative block overflow-hidden bg-neutral-100');
    media.href = href;
    media.setAttribute('aria-label', property.title);
    media.addEventListener('click', (event) => visitProperty(event, href));

    if (property.image) {
        const image = node('img', 'aspect-[16/10] w-full object-cover');
        image.src = property.image;
        image.alt = property.imageAlt;
        media.append(image);
    } else {
        media.append(node('div', 'aspect-[16/10] w-full bg-neutral-100'));
    }

    const badges = node('div', 'absolute top-3 left-3 flex flex-wrap gap-1.5');
    badges.append(
        node(
            'span',
            'rounded-full bg-brand-red px-2.5 py-1 text-[11px] font-medium text-white shadow-sm',
            property.offerType.label,
        ),
        node(
            'span',
            'rounded-full bg-white px-2.5 py-1 text-[11px] font-medium text-neutral-900 shadow-sm',
            property.type.label,
        ),
    );
    media.append(badges);

    const body = node('div', 'flex flex-col gap-2 px-4 py-4');

    const title = node(
        'a',
        'line-clamp-2 text-base leading-snug font-semibold tracking-tight !text-neutral-950 no-underline',
        property.title,
    );
    title.href = href;
    title.addEventListener('click', (event) => visitProperty(event, href));

    const address = node(
        'p',
        'inline-flex min-w-0 items-center gap-1 text-xs text-neutral-500',
    );
    address.innerHTML =
        '<svg class="size-3.5 shrink-0" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M10 17s6-5.1 6-9.2A6 6 0 1 0 4 7.8C4 11.9 10 17 10 17Z" stroke-linecap="round" stroke-linejoin="round"/><circle cx="10" cy="7.8" r="1.8"/></svg>';
    address.append(node('span', 'truncate', formatAddress(property)));

    const specs = node('p', 'text-xs text-neutral-500');
    specs.append(`${property.area}\u00a0m²`);

    if (property.bedrooms !== null) {
        specs.append(
            node('span', 'text-neutral-300', ' · '),
            `${tc('property.bedrooms', property.bedrooms)}`,
        );
    }

    const footer = node('div', 'flex items-center justify-between gap-3 pt-1');
    footer.append(
        node(
            'p',
            'text-base font-semibold text-brand-red',
            formatPrice(property),
        ),
    );

    const link = node(
        'a',
        'inline-flex shrink-0 items-center gap-1 rounded-full bg-brand-red px-3.5 py-1.5 text-xs font-semibold !text-white no-underline transition-opacity hover:opacity-90',
        t('featured.see_property'),
    );
    link.href = href;
    link.append(node('span', '', '→'));
    link.lastElementChild?.setAttribute('aria-hidden', 'true');
    link.addEventListener('click', (event) => visitProperty(event, href));

    footer.append(link);
    body.append(title, address, specs, footer);
    card.append(media, body);

    return card;
}

/**
 * Add markers for new properties and drop the ones that left the results,
 * keeping the markers (and any open popup) that are still in view.
 */
function syncMarkers(): void {
    if (leaflet === null || map === null) {
        return;
    }

    const located = locatedProperties();
    const slugs = new Set(located.map((property) => property.slug));

    markers.forEach((marker, slug) => {
        if (!slugs.has(slug)) {
            marker.remove();
            markers.delete(slug);
        }
    });

    located.forEach((property) => {
        if (markers.has(property.slug)) {
            return;
        }

        const active = property.slug === props.activeSlug;
        const marker = leaflet!
            .marker([property.latitude, property.longitude], {
                icon: markerIcon(property, active),
                title: property.title,
                riseOnHover: true,
                zIndexOffset: active ? 1000 : 0,
            })
            .bindPopup(popupContent(property), {
                className: 'property-map-popup',
                maxWidth: 300,
                minWidth: 280,
                offset: [0, -32],
                autoPanPaddingTopLeft: [24, 80],
                autoPanPaddingBottomRight: [24, 24],
            })
            .on('click', () => emit('select', property.slug))
            .addTo(map!);

        markers.set(property.slug, marker);
    });
}

function showInitialArea(): void {
    if (leaflet === null || map === null) {
        return;
    }

    ignoreProgrammaticMoves();

    if (props.initialBounds) {
        const { south, west, north, east } = props.initialBounds;

        map.fitBounds(
            [
                [south, west],
                [north, east],
            ],
            { animate: false },
        );

        return;
    }

    map.fitBounds(franceBounds, { animate: false });
}

/**
 * Frame the map around the current results, e.g. after a new filter search.
 */
function fitToProperties(animate = true): void {
    const located = locatedProperties();

    if (leaflet === null || map === null || located.length === 0) {
        return;
    }

    ignoreProgrammaticMoves();

    map.fitBounds(
        leaflet.latLngBounds(
            located.map((property) => [property.latitude, property.longitude]),
        ),
        { padding: [48, 48], maxZoom: 14, animate },
    );
}

function emitMoved(): void {
    if (map === null) {
        return;
    }

    if (performance.now() < ignoreMovesUntil) {
        return;
    }

    const bounds = map.getBounds();

    emit('moved', {
        south: bounds.getSouth(),
        west: bounds.getWest(),
        north: bounds.getNorth(),
        east: bounds.getEast(),
    });
}

/**
 * Open a property's popup where the map currently is. If the popup has to
 * nudge the map to fit, that nudge does not trigger a new search.
 */
function openPropertyPopup(slug: string): void {
    const marker = markers.get(slug);

    if (map === null || marker === undefined) {
        return;
    }

    let nudged = false;
    const onMoveStart = (): void => {
        nudged = true;
    };

    map.once('movestart', onMoveStart);
    marker.openPopup();
    map.off('movestart', onMoveStart);

    if (nudged) {
        ignoreProgrammaticMoves();
    }
}

defineExpose({ openPropertyPopup, fitToProperties });

watch(
    () => props.activeSlug,
    (slug, previousSlug) => {
        const located = locatedProperties();

        [previousSlug, slug].forEach((currentSlug) => {
            const property = located.find((item) => item.slug === currentSlug);
            const marker = currentSlug ? markers.get(currentSlug) : undefined;

            if (property && marker) {
                const active = currentSlug === slug;

                marker.setIcon(markerIcon(property, active));
                marker.setZIndexOffset(active ? 1000 : 0);
            }
        });
    },
);

watch(() => props.properties, syncMarkers);

onMounted(async () => {
    const leafletModule = await import('leaflet');
    leaflet = (
        'default' in leafletModule ? leafletModule.default : leafletModule
    ) as Leaflet;

    if (container.value === null) {
        return;
    }

    map = leaflet.map(container.value, { scrollWheelZoom: true });

    leaflet
        .tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        })
        .addTo(map);

    showInitialArea();
    syncMarkers();

    map.on('moveend', emitMoved);
});

onBeforeUnmount(() => {
    map?.remove();
    map = null;
    markers.clear();
});
</script>

<template>
    <div class="relative isolate size-full overflow-hidden rounded-[1.75rem]">
        <div
            ref="container"
            class="size-full bg-neutral-100"
            role="region"
            :aria-label="t('properties.map_label')"
        />

        <slot />

        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="-translate-y-2 opacity-0"
            leave-active-class="transition duration-200 ease-in"
            leave-to-class="-translate-y-2 opacity-0"
        >
            <div
                v-if="loading"
                class="pointer-events-none absolute top-4 left-1/2 z-[1000] -translate-x-1/2"
                role="status"
                aria-live="polite"
            >
                <span
                    class="inline-flex items-center gap-2.5 rounded-full bg-white px-4 py-2 text-sm font-semibold text-neutral-900 shadow-[0_12px_40px_rgba(15,23,42,0.18)]"
                >
                    <span class="flex items-center gap-1" aria-hidden="true">
                        <span
                            class="size-1.5 animate-bounce rounded-full bg-brand-red [animation-delay:-0.3s]"
                        />
                        <span
                            class="size-1.5 animate-bounce rounded-full bg-brand-red [animation-delay:-0.15s]"
                        />
                        <span
                            class="size-1.5 animate-bounce rounded-full bg-brand-red"
                        />
                    </span>
                    {{ t('properties.map_loading') }}
                </span>
            </div>
        </Transition>
    </div>
</template>

<style>
.property-map-popup .leaflet-popup-content-wrapper {
    padding: 0;
    overflow: hidden;
    color: inherit;
    background: #fff;
    border-radius: 1.5rem;
    box-shadow: 0 16px 50px rgba(15, 23, 42, 0.16);
}

.property-map-popup .leaflet-popup-content {
    width: 18rem !important;
    margin: 0;
    font-size: inherit;
    line-height: inherit;
}

.property-map-popup .leaflet-popup-content p {
    margin: 0;
}

.property-map-popup .leaflet-popup-tip {
    background: #fff;
    box-shadow: none;
}

.property-map-popup .leaflet-popup-close-button {
    top: 0.7rem;
    right: 0.7rem;
    display: flex;
    width: 2rem;
    height: 2rem;
    align-items: center;
    justify-content: center;
    padding: 0;
    border-radius: 9999px;
    background: #fff;
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.12);
    color: #171717 !important;
    font:
        18px/1 'Plus Jakarta Sans',
        ui-sans-serif,
        system-ui,
        sans-serif;
}

.property-map-popup .leaflet-popup-close-button:hover {
    background: #f5f5f5;
}
</style>
