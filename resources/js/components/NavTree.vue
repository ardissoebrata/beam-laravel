<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronRight, FolderKanban } from '@lucide/vue';
import type { Component } from 'vue';
import { computed, ref } from 'vue';
import {
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/composables/useCurrentUrl';

defineOptions({ name: 'NavTree' });

type TreeNode = {
    title: string;
    icon?: Component;
    href?: string;
    children?: TreeNode[];
};

const props = defineProps<{
    nodes: TreeNode[];
    level?: number;
}>();

const { isCurrentUrl, isCurrentOrParentUrl } = useCurrentUrl();
const openNodes = ref<Record<string, boolean>>({});

const containsActiveNode = (node: TreeNode): boolean => {
    if (node.href && isCurrentOrParentUrl(node.href)) {
        return true;
    }

    return Boolean(node.children?.some(containsActiveNode));
};

const isOpen = (node: TreeNode) => {
    const key = `${props.level ?? 0}:${node.title}`;

    return openNodes.value[key] ?? containsActiveNode(node);
};

const toggleNode = (node: TreeNode) => {
    const key = `${props.level ?? 0}:${node.title}`;

    openNodes.value[key] = !isOpen(node);
};

const hasChildren = (node: TreeNode) => Boolean(node.children?.length);

const isActive = (node: TreeNode) =>
    Boolean(node.href && isCurrentUrl(node.href));

const isParentActive = (node: TreeNode) => containsActiveNode(node);

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
