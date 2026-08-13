<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import Button from '@/components/base/Button.vue';
import Input from '@/components/base/Input.vue';
import InputError from '@/components/base/InputError.vue';
import Label from '@/components/base/Label.vue';
import TextLink from '@/components/base/TextLink.vue';
import { Spinner } from '@/components/ui/spinner';
import { useTranslations } from '@/composables/useTranslations';
import { forgotPassword } from '@/lib/authRoutes';
import { login } from '@/routes';

const { t } = useTranslations();

defineOptions({
    layout: {
        title: 'Lupa kata sandi',
        description: 'Masukkan email Anda untuk menerima tautan pengaturan ulang kata sandi',
    },
});

defineProps<{
    status?: string;
}>();
</script>

<template>
    <Head :title="t('auth.forgotTitle')" />

    <div
        v-if="status"
        class="mb-4 text-center text-sm font-medium text-green-600"
    >
        {{ status }}
    </div>

    <div class="space-y-6">
        <Form
            :action="forgotPassword()"
            method="post"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-2">
                <Label for="email">{{ t('auth.emailAddress') }}</Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    autocomplete="off"
                    autofocus
                    placeholder="email@example.com"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="my-6 flex items-center justify-start">
                <Button
                    class="w-full"
                    :disabled="processing"
                    data-test="email-password-reset-link-button"
                >
                    <Spinner v-if="processing" />
                    {{ t('auth.sendResetLink') }}
                </Button>
            </div>
        </Form>

        <div class="space-x-1 text-center text-sm text-muted-foreground">
            <span>{{ t('auth.orReturnTo') }}</span>
            <TextLink :href="login()">{{ t('auth.login').toLowerCase() }}</TextLink>
        </div>
    </div>
</template>
