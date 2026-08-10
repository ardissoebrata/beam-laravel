<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    BookOpen,
    Database,
    FolderGit2,
    LayoutGrid,
    Settings2,
    Users,
} from '@lucide/vue';
import AppLogo from '@/components/layout/AppLogo.vue';
import NavFooter from '@/components/navigation/NavFooter.vue';
import NavMain from '@/components/navigation/NavMain.vue';
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
import { dashboard } from '@/routes';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editProfile } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

const treeNavGroups = [
    {
        label: 'Workspace',
        nodes: [
            {
                title: 'Overview',
                href: dashboard().url,
                icon: LayoutGrid,
            },
            {
                title: 'Projects',
                icon: FolderGit2,
                children: [
                    { title: 'Active projects' },
                    { title: 'Archived projects' },
                    { title: 'Project templates' },
                ],
            },
            {
                title: 'Team',
                icon: Users,
                children: [
                    { title: 'Members' },
                    { title: 'Roles and permissions' },
                    { title: 'Invitations' },
                ],
            },
        ],
    },
    {
        label: 'Resources',
        nodes: [
            {
                title: 'Data library',
                icon: Database,
                children: [
                    {
                        title: 'Reports',
                        children: [
                            { title: 'Monthly reports' },
                            { title: 'Quarterly reports' },
                            { title: 'Export history' },
                        ],
                    },
                    { title: 'Datasets' },
                    { title: 'Integrations' },
                ],
            },
            {
                title: 'Settings',
                icon: Settings2,
                children: [
                    { title: 'Profile', href: editProfile().url },
                    { title: 'Security', href: editSecurity().url },
                    { title: 'Appearance', href: editAppearance().url },
                ],
            },
        ],
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: FolderGit2,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
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
            <NavMain :items="mainNavItems" />
            <SidebarGroup
                v-for="group in treeNavGroups"
                :key="group.label"
                class="px-2 py-0"
            >
                <SidebarGroupLabel>{{ group.label }}</SidebarGroupLabel>
                <NavTree :nodes="group.nodes" />
            </SidebarGroup>
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
