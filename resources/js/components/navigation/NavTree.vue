<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronRight, FolderKanban } from '@lucide/vue';
import { computed, ref } from 'vue';
import {
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavigationNode } from '@/types';

defineOptions({ name: 'NavTree' });

const props = defineProps<{
    nodes: NavigationNode[];
    level?: number;
}>();

const { isCurrentUrl, isCurrentOrParentUrl } = useCurrentUrl();
const openNodes = ref<Record<string, boolean>>({});

const containsActiveNode = (node: NavigationNode): boolean => {
    if (node.href && isCurrentOrParentUrl(node.href)) {
        return true;
    }

    return Boolean(node.children?.some(containsActiveNode));
};

const isOpen = (node: NavigationNode) => {
    const key = `${props.level ?? 0}:${node.title}`;

    return openNodes.value[key] ?? containsActiveNode(node);
};

const toggleNode = (node: NavigationNode) => {
    const key = `${props.level ?? 0}:${node.title}`;

    openNodes.value[key] = !isOpen(node);
};

const hasChildren = (node: NavigationNode) => Boolean(node.children?.length);

const isActive = (node: NavigationNode) =>
    Boolean(node.href && isCurrentUrl(node.href));

const isParentActive = (node: NavigationNode) => containsActiveNode(node);

const menuItems = computed(() => props.nodes);
</script>

<template>
    <SidebarMenu>
        <SidebarMenuItem v-for="node in menuItems" :key="node.title">
            <SidebarMenuButton
                v-if="hasChildren(node)"
                :is-active="isParentActive(node)"
                :tooltip="node.title"
                :aria-expanded="isOpen(node)"
                :aria-label="`${node.title}, ${isOpen(node) ? 'open' : 'closed'}`"
                @click="toggleNode(node)"
            >
                <component :is="node.icon ?? FolderKanban" />
                <span>{{ node.title }}</span>
                <ChevronRight
                    class="ml-auto transition-transform duration-200"
                    :class="{ 'rotate-90': isOpen(node) }"
                />
            </SidebarMenuButton>

            <SidebarMenuButton
                v-else-if="node.href"
                as-child
                :is-active="isActive(node)"
                :tooltip="node.title"
            >
                <Link :href="node.href">
                    <component v-if="node.icon" :is="node.icon" />
                    <span>{{ node.title }}</span>
                </Link>
            </SidebarMenuButton>

            <SidebarMenuButton v-else disabled :tooltip="node.title">
                <component v-if="node.icon" :is="node.icon" />
                <span>{{ node.title }}</span>
            </SidebarMenuButton>

            <SidebarMenuSub v-if="hasChildren(node) && isOpen(node)">
                <SidebarMenuSubItem>
                    <NavTree
                        :nodes="node.children ?? []"
                        :level="(level ?? 0) + 1"
                    />
                </SidebarMenuSubItem>
            </SidebarMenuSub>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
