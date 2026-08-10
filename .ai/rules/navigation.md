---
paths:
  - 'resources/js/config/navigation.ts|resources/js/types/navigation.ts|resources/js/components/navigation/**|resources/js/components/layout/AppHeader.vue|resources/js/layouts/settings/Layout.vue|resources/js/composables/useCurrentUrl.ts'
---

# Vue Navigation And Components

## Component structure
Use Vue 3 single-file components with `<script setup lang="ts">` and typed props. Keep responsibilities separated:

- `resources/js/config/navigation.ts` stores reusable global navigation data.
- `resources/js/types/navigation.ts` defines `NavItem`, recursive `NavigationNode`, and `NavigationGroup` contracts.
- `resources/js/components/navigation/` contains navigation renderers such as `AppSidebar`, `NavTree`, `NavMain`, `NavFooter`, and user-menu components.
- `resources/js/components/layout/` contains layout-level navigation such as `AppHeader` and sidebar headers.
- `resources/js/components/ui/` contains reusable UI primitives; use them through composition instead of changing their behavior for one menu.

Use `<Link>` from `@inertiajs/vue3` for internal navigation. Use Wayfinder functions from `@/routes` (or `@/actions`) for internal routes and keep external URLs as normal external links with the existing `target` and `rel` behavior.

## Navigation sources
Update the source that owns the menu being changed:

- `treeNavGroups` in `config/navigation.ts` drives the authenticated sidebar. Use `NavigationGroup` entries and recursive `children` for nested items rendered by `NavTree`.
- `footerNavItems` in `config/navigation.ts` drives sidebar footer links rendered by `NavFooter`.
- `userNavItems` in `config/navigation.ts` drives the user dropdown rendered by `UserMenuContent`.
- `mainNavItems` and `rightNavItems` in `components/layout/AppHeader.vue` are local responsive header menus. They do not automatically follow `config/navigation.ts`.
- `sidebarNavItems` in `layouts/settings/Layout.vue` is the local settings sidebar menu. It does not automatically follow the global sidebar configuration.

When adding an item, use the matching type, a descriptive `title`, a Lucide icon from `@lucide/vue` where the renderer supports icons, and a Wayfinder route for internal destinations. Preserve the surrounding ordering and group semantics.

## Renderer and active state rules
Use `NavTree` for nested `NavigationNode` data and `NavMain` for flat `NavItem[]` data. `NavTree` recursively renders `children`, derives parent activity, and keeps expanded state local to the component. A node without `href` is a group or disabled display item, not a navigable route.

Use the `useCurrentUrl()` helper consistently:

- `isCurrentUrl` marks an exact active item.
- `isCurrentOrParentUrl` marks a settings item or parent route active when the current path starts with its path.
- `whenCurrentUrl` selects a header class or value for an exact active item.

The `permission` field exists on navigation types, but the current renderers do not filter items by it. Do not treat adding `permission` as an implemented access check; authorization must remain enforced by backend routes and any future UI filtering must be added deliberately to the owning renderer.
