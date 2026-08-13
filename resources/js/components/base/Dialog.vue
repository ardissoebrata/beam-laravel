<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';

const props = withDefaults(
    defineProps<{
        title?: string;
        description?: string;
        class?: HTMLAttributes['class'];
        showCloseButton?: boolean;
    }>(),
    {
        showCloseButton: true,
    },
);

const open = defineModel<boolean>('open');

const close = () => {
    open.value = false;
};
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger v-if="$slots.trigger" as-child>
            <slot name="trigger" />
        </DialogTrigger>

        <DialogContent
            :class="props.class"
            :show-close-button="showCloseButton"
        >
            <DialogHeader
                v-if="
                    $slots.header ||
                    $slots.title ||
                    $slots.description ||
                    title ||
                    description
                "
            >
                <slot name="header" />
                <DialogTitle v-if="$slots.title || title">
                    <slot name="title">{{ title }}</slot>
                </DialogTitle>
                <DialogDescription v-if="$slots.description || description">
                    <slot name="description">{{ description }}</slot>
                </DialogDescription>
            </DialogHeader>

            <slot :close="close" />
        </DialogContent>
    </Dialog>
</template>
