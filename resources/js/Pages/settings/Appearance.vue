<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Heading from '@/components/base/Heading.vue';
import AppearanceTabs from '@/components/settings/AppearanceTabs.vue';
import { useTranslations } from '@/composables/useTranslations';
import { edit } from '@/routes/appearance';

const { t } = useTranslations();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Pengaturan tampilan',
                href: edit(),
            },
        ],
    },
});
</script>

<template>
    <Head :title="t('settings.appearanceTitle')" />

    <h1 class="sr-only">{{ t('settings.appearanceTitle') }}</h1>

    <div class="space-y-6">
        <Heading
            variant="small"
            :title="t('settings.appearanceTitle')"
            description="Perbarui pengaturan tampilan akun Anda"
        />
        <AppearanceTabs />
    </div>
</template>
