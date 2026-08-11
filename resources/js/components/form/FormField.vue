<script setup lang="ts">
import { computed, useId } from 'vue';
import Label from '@/components/base/Label.vue';

type Props = {
    id: string;
    label: string;
    hint?: string;
    error?: string;
    required?: boolean;
};

const props = withDefaults(defineProps<Props>(), {
    hint: undefined,
    error: undefined,
    required: false,
});

const generatedId = useId();
const hintId = computed(() => `${props.id}-${generatedId}-hint`);
const errorId = computed(() => `${props.id}-${generatedId}-error`);
const describedBy = computed(() => {
    const ids = [];

    if (props.hint) {
        ids.push(hintId.value);
    }

    if (props.error) {
        ids.push(errorId.value);
    }

    return ids.length > 0 ? ids.join(' ') : undefined;
});
</script>

<template>
    <div class="grid gap-2">
        <Label :for="id">
            {{ label }}
            <span v-if="required" aria-hidden="true">*</span>
        </Label>

        <slot
            :id="id"
            :aria-describedby="describedBy"
            :aria-invalid="error ? 'true' : undefined"
        />

        <p v-if="hint" :id="hintId" class="text-sm text-muted-foreground">
            {{ hint }}
        </p>

        <p
            v-if="error"
            :id="errorId"
            class="text-sm text-red-600 dark:text-red-500"
        >
            {{ error }}
        </p>
    </div>
</template>
