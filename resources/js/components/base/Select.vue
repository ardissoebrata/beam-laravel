<script setup lang="ts">
import type { AcceptableValue } from 'reka-ui';
import type { HTMLAttributes } from 'vue';
import {
    Select as UiSelect,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

export interface SelectOption {
    value: string | number;
    label: string;
    disabled?: boolean;
}

const props = withDefaults(
    defineProps<{
        options: SelectOption[];
        modelValue?: string | number;
        placeholder?: string;
        class?: HTMLAttributes['class'];
    }>(),
    { placeholder: 'Select an option' },
);

const emits = defineEmits<{
    (event: 'update:modelValue', value: string | number): void;
}>();

const handleUpdate = (value: AcceptableValue) => {
    if (value === null) {
        return;
    }

    const option = props.options.find((item) => String(item.value) === String(value));

    if (option) {
        emits('update:modelValue', option.value);
    }
};
</script>

<template>
    <UiSelect
        :model-value="modelValue === undefined ? undefined : String(modelValue)"
        @update:model-value="handleUpdate"
    >
        <SelectTrigger :class="props.class">
            <SelectValue :placeholder="placeholder" />
        </SelectTrigger>
        <SelectContent>
            <SelectItem
                v-for="option in options"
                :key="String(option.value)"
                :value="String(option.value)"
                :disabled="option.disabled"
            >
                {{ option.label }}
            </SelectItem>
        </SelectContent>
    </UiSelect>
</template>
