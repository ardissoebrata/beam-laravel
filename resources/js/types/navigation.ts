import type { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from '@lucide/vue';

export type BreadcrumbItem = {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
};

export type NavItem = {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
    permission?: string;
};

export type NavigationNode = {
    title: string;
    icon?: LucideIcon;
    href?: string;
    children?: NavigationNode[];
    permission?: string;
};

export type NavigationGroup = {
    label: string;
    nodes: NavigationNode[];
};
