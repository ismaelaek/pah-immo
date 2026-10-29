import { useLocale } from '@/composables/useLocale';
import type { Property } from '@/components/PropertyCard.vue';

export function usePropertyFormatting() {
    const { locale, t } = useLocale();

    function formatPrice(property: Property): string {
        const amount = new Intl.NumberFormat(locale.value, {
            style: 'currency',
            currency: 'EUR',
            maximumFractionDigits: 0,
        }).format(property.price);

        return property.offerType.value === 'rent'
            ? `${amount} ${t('property.per_month')}`
            : amount;
    }

    function formatCompactPrice(property: Property): string {
        if (property.offerType.value === 'rent') {
            return formatPrice(property);
        }

        return new Intl.NumberFormat(locale.value, {
            style: 'currency',
            currency: 'EUR',
            notation: 'compact',
            maximumFractionDigits: 1,
        }).format(property.price);
    }

    function formatAddress(property: Property): string {
        return [property.neighborhood, property.city]
            .filter((part): part is string => Boolean(part))
            .join(', ');
    }

    return {
        formatPrice,
        formatCompactPrice,
        formatAddress,
    };
}
