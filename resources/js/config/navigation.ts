import {
    BookOpen,
    Database,
    FolderGit2,
    LayoutGrid,
    Settings,
    Settings2,
    Users,
} from '@lucide/vue';
import { dashboard } from '@/routes';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editProfile } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
import { index as usersIndex } from '@/routes/users';
import type {
    NavItem,
    NavigationGroup,
} from '@/types';

export const treeNavGroups: NavigationGroup[] = [
    {
        label: 'Workspace',
        nodes: [
            {
                title: 'Dashboard',
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
                title: 'Users',
                href: usersIndex().url,
                icon: Users,
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

export const footerNavItems: NavItem[] = [
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

export const userNavItems: NavItem[] = [
    {
        title: 'Settings',
        href: editProfile(),
        icon: Settings,
    },
];
