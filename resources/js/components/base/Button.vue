<script setup lang="ts">
import type { PrimitiveProps } from 'reka-ui';
import type { HTMLAttributes } from 'vue';
import { useAttrs } from 'vue';
import { Button as UiButton } from '@/components/ui/button';
import type { ButtonVariants } from '@/components/ui/button';

defineOptions({ inheritAttrs: false });

interface Props extends PrimitiveProps {
    variant?: ButtonVariants['variant'];
    size?: ButtonVariants['size'];
    class?: HTMLAttributes['class'];
}

const props = withDefaults(defineProps<Props>(), {
    as: 'button',
});

const attrs = useAttrs();
</script>

<template>
    <UiButton
        v-bind="attrs"
        :variant="variant"
        :size="size"
        :as="as"
        :as-child="asChild"
        :class="props.class"
    >
        <slot />
    </UiButton>
</template>
