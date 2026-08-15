<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogo from '@/components/layout/AppLogo.vue';
import NavFooter from '@/components/navigation/NavFooter.vue';
import NavTree from '@/components/navigation/NavTree.vue';
import NavUser from '@/components/navigation/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { footerNavItems, treeNavGroups } from '@/config/navigation';
import { dashboard } from '@/routes';
import type { NavigationNode } from '@/types';

const page = usePage();

const canViewNode = (node: NavigationNode): boolean => {
    if (
        node.permission &&
        !page.props.auth.permissions.includes(node.permission)
    ) {
        return false;
    }

    return !node.children?.length || node.children.some(canViewNode);
};

const visibleNavGroups = computed(() =>
    treeNavGroups
        .map((group) => ({
            ...group,
            nodes: group.nodes.filter(canViewNode),
        }))
        .filter((group) => group.nodes.length > 0),
);
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <SidebarGroup
                v-for="group in visibleNavGroups"
                :key="group.label"
                class="px-2 py-0"
            >
                <SidebarGroupLabel>{{ group.label }}</SidebarGroupLabel>
                <NavTree :nodes="group.nodes" />
            </SidebarGroup>
        </SidebarContent>

        <SidebarFooter>
            <NavFooter
                v-if="footerNavItems.length > 0"
                :items="footerNavItems"
            />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
