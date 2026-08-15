import { LayoutGrid, Settings, Users } from '@lucide/vue';
import { dashboard } from '@/routes';
import { edit as editProfile } from '@/routes/profile';
import { index as usersIndex } from '@/routes/users';
import type { NavItem, NavigationGroup } from '@/types';

export const treeNavGroups: NavigationGroup[] = [
    {
        label: 'Utama',
        nodes: [
            {
                title: 'Dasbor',
                href: dashboard().url,
                icon: LayoutGrid,
            },
        ],
    },
    {
        label: 'Sumber daya',
        nodes: [
            {
                title: 'Pengguna',
                href: usersIndex().url,
                icon: Users,
                permission: 'users.manage',
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
        title: 'Pengaturan',
        href: editProfile(),
        icon: Settings,
    },
];
