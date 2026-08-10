<script setup lang="ts">
import AppContent from '@/components/layout/AppContent.vue';
import AppShell from '@/components/layout/AppShell.vue';
import AppSidebarHeader from '@/components/layout/AppSidebarHeader.vue';
import AppSidebar from '@/components/navigation/AppSidebar.vue';
import { Toaster } from '@/components/ui/sonner';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
        </AppContent>
        <Toaster />
    </AppShell>
</template>
