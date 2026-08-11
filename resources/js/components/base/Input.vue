<script setup lang="ts">
import { useVModel } from '@vueuse/core';
import type { HTMLAttributes } from 'vue';
import { useAttrs } from 'vue';
import { Input as UiInput } from '@/components/ui/input';

defineOptions({ inheritAttrs: false });

const props = defineProps<{
    defaultValue?: string | number;
    modelValue?: string | number;
    class?: HTMLAttributes['class'];
}>();

const emits = defineEmits<{
    (event: 'update:modelValue', payload: string | number): void;
}>();

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue,
});

const attrs = useAttrs();
</script>

<template>
    <UiInput
        v-bind="attrs"
        v-model="modelValue"
        :default-value="props.defaultValue"
        :class="props.class"
    />
</template>
