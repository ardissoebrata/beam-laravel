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
        label: 'Main',
        nodes: [
            {
                title: 'Dashboard',
                href: dashboard().url,
                icon: LayoutGrid,
            },
        ],
    },
    {
        label: 'Resources',
        nodes: [
            {
                title: 'Users',
                href: usersIndex().url,
                icon: Users,
            },
            // {
            //     title: 'Data library',
            //     icon: Database,
            //     children: [
            //         {
            //             title: 'Reports',
            //             children: [
            //                 { title: 'Monthly reports' },
            //                 { title: 'Quarterly reports' },
            //                 { title: 'Export history' },
            //             ],
            //         },
            //         { title: 'Datasets' },
            //         { title: 'Integrations' },
            //     ],
            // },
        ],
    },
];

export const footerNavItems: NavItem[] = [
    // {
    //     title: 'Repository',
    //     href: 'https://github.com/laravel/vue-starter-kit',
    //     icon: FolderGit2,
    // },
    // {
    //     title: 'Documentation',
    //     href: 'https://laravel.com/docs/starter-kits#vue',
    //     icon: BookOpen,
    // },
];

export const userNavItems: NavItem[] = [
    {
        title: 'Settings',
        href: editProfile(),
        icon: Settings,
    },
];
