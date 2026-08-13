import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import type { FrontendTranslations } from '@/types';

type TranslationValue = string | Record<string, unknown>;

export function useTranslations() {
    const page = usePage();
    const translations = computed(
        () => page.props.translations as FrontendTranslations,
    );

    const t = (key: string, replacements: Record<string, string | number> = {}): string => {
        const value = key.split('.').reduce<TranslationValue | undefined>(
            (current, part) => {
                if (!current || typeof current === 'string') {
                    return undefined;
                }

                return current[part] as TranslationValue | undefined;
            },
            translations.value,
        );

        if (typeof value !== 'string') {
            return key;
        }

        return Object.entries(replacements).reduce(
            (result, [name, replacement]) =>
                result.replaceAll(`:${name}`, String(replacement)),
            value,
        );
    };

    return { locale: computed(() => page.props.locale), t };
}
