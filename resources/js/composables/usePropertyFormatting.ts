import { useLocale } from '@/composables/useLocale';
import type { Property } from '@/components/PropertyCard.vue';

export function usePropertyFormatting() {
    const { locale, t } = useLocale();

    function rentSuffix(property: Property): string {
        return property.pricePeriod
            ? `/ ${property.pricePeriod.toLocaleLowerCase(locale.value)}`
            : t('property.per_month');
    }

    function formatPrice(property: Property): string {
        if (property.price === null) {
            return t('property.price_on_request');
        }

        const amount = new Intl.NumberFormat(locale.value, {
            style: 'currency',
            currency: 'EUR',
            maximumFractionDigits: 0,
        }).format(property.price);

        return property.offerType.value === 'rent'
            ? `${amount} ${rentSuffix(property)}`
            : amount;
    }

    function formatCompactPrice(property: Property): string {
        if (property.price === null || property.offerType.value === 'rent') {
            return formatPrice(property);
        }

        return new Intl.NumberFormat(locale.value, {
            style: 'currency',
            currency: 'EUR',
            notation: 'compact',
            maximumFractionDigits: 1,
        }).format(property.price);
    }

    function formatArea(area: number): string {
        return `${new Intl.NumberFormat(locale.value, { maximumFractionDigits: 0 }).format(area)} m²`;
    }

    function formatAddress(property: Property): string {
        return [property.neighborhood, property.city]
            .filter((part): part is string => Boolean(part))
            .join(', ');
    }

    return {
        formatPrice,
        formatCompactPrice,
        formatArea,
        formatAddress,
    };
}
