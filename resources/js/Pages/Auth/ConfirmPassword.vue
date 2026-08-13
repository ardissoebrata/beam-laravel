<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import Button from '@/components/base/Button.vue';
import InputError from '@/components/base/InputError.vue';
import Label from '@/components/base/Label.vue';
import PasswordInput from '@/components/base/PasswordInput.vue';
import { Spinner } from '@/components/ui/spinner';
import { useTranslations } from '@/composables/useTranslations';
import { store } from '@/routes/password/confirm';

const { t } = useTranslations();

defineOptions({
    layout: {
        title: 'Konfirmasi kata sandi',
        description: 'Ini adalah area aman aplikasi. Konfirmasikan kata sandi Anda sebelum melanjutkan.',
    },
});
</script>

<template>
    <Head :title="t('auth.confirmTitle')" />

    <Form
        v-bind="store.form()"
        reset-on-success
        v-slot="{ errors, processing }"
    >
        <div class="space-y-6">
            <div class="grid gap-2">
                <Label htmlFor="password">{{ t('auth.password') }}</Label>
                <PasswordInput
                    id="password"
                    name="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="current-password"
                    autofocus
                />

                <InputError :message="errors.password" />
            </div>

            <div class="flex items-center">
                <Button
                    class="w-full"
                    :disabled="processing"
                    data-test="confirm-password-button"
                >
                    <Spinner v-if="processing" />
                    {{ t('auth.confirmPassword') }}
                </Button>
            </div>
        </div>
    </Form>
</template>
