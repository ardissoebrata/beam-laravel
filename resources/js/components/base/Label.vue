<script setup lang="ts">
import type { LabelProps } from 'reka-ui';
import type { HTMLAttributes } from 'vue';
import { Label as UiLabel } from '@/components/ui/label';

const props = defineProps<LabelProps & { class?: HTMLAttributes['class'] }>();
</script>

<template>
    <UiLabel v-bind="props" :class="props.class">
        <slot />
    </UiLabel>
</template>
