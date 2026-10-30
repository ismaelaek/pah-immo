import { computed } from 'vue';
import type { Ref } from 'vue';
import type { PropertyOption } from '@/components/PropertyCard.vue';
import { useLocale } from '@/composables/useLocale';

export type PropertyFilters = {
    type: string;
    price: string;
    city: string;
    rooms: string;
};

const priceKeys = ['under_250', '250_500', '500_1m', 'over_1m'] as const;
const roomKeys = ['studio', '1', '2', '3', '4'] as const;

export function emptyPropertyFilters(): PropertyFilters {
    return { type: '', price: '', city: '', rooms: '' };
}

export function filledPropertyFilters(
    filters: PropertyFilters,
): Record<string, string> {
    return Object.fromEntries(
        Object.entries(filters).filter(([, value]) => value !== ''),
    );
}

/**
 * Query for a new search. The server keeps the last filters in the session,
 * so an empty search must say so explicitly to clear them.
 */
export function propertySearchQuery(
    filters: PropertyFilters,
): Record<string, string> {
    const query = filledPropertyFilters(filters);

    return Object.keys(query).length > 0 ? query : { reset: '1' };
}

export function usePropertySearchFields(
    propertyTypes: Ref<PropertyOption[]>,
    cities: Ref<string[]>,
) {
    const { t } = useLocale();

    const fields = computed(() => [
        {
            name: 'type' as const,
            label: t('home.type'),
            placeholder: t('home.all_types'),
            options: propertyTypes.value,
        },
        {
            name: 'price' as const,
            label: t('home.price'),
            placeholder: t('home.prices.all'),
            options: priceKeys.map((value) => ({
                value,
                label: t(`home.prices.${value}`),
            })),
        },
        {
            name: 'city' as const,
            label: t('home.city'),
            placeholder: t('home.all_cities'),
            options: cities.value.map((city) => ({ value: city, label: city })),
        },
        {
            name: 'rooms' as const,
            label: t('home.rooms'),
            placeholder: t('home.all_rooms'),
            options: roomKeys.map((value) => ({
                value,
                label: t(`home.room_options.${value}`),
            })),
        },
    ]);

    return { fields };
}
