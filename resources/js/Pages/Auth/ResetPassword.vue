<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import Button from '@/components/base/Button.vue';
import Input from '@/components/base/Input.vue';
import InputError from '@/components/base/InputError.vue';
import Label from '@/components/base/Label.vue';
import PasswordInput from '@/components/base/PasswordInput.vue';
import { Spinner } from '@/components/ui/spinner';
import { useTranslations } from '@/composables/useTranslations';
import { resetPassword } from '@/lib/authRoutes';

const { t } = useTranslations();

defineOptions({
    layout: {
        title: 'Atur ulang kata sandi',
        description: 'Masukkan kata sandi baru Anda di bawah ini',
    },
});

const props = defineProps<{
    token: string;
    email: string;
    passwordRules: string;
}>();

const inputEmail = ref(props.email);
</script>

<template>
    <Head :title="t('auth.resetTitle')" />

    <Form
        :action="resetPassword(token)"
        method="post"
        :transform="(data) => ({ ...data, token, email })"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
    >
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="email">{{ t('auth.emailAddress') }}</Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    autocomplete="email"
                    v-model="inputEmail"
                    class="mt-1 block w-full"
                    readonly
                />
                <InputError :message="errors.email" class="mt-2" />
            </div>

            <div class="grid gap-2">
                <Label for="password">{{ t('auth.password') }}</Label>
                <PasswordInput
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    class="mt-1 block w-full"
                    autofocus
                    :placeholder="t('auth.password')"
                    :passwordrules="passwordRules"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">{{ t('auth.confirmPassword') }}</Label>
                <PasswordInput
                    id="password_confirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                    class="mt-1 block w-full"
                    :placeholder="t('auth.confirmPassword')"
                    :passwordrules="passwordRules"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <Button
                type="submit"
                class="mt-4 w-full"
                :disabled="processing"
                data-test="reset-password-button"
            >
                <Spinner v-if="processing" />
                {{ t('auth.resetPassword') }}
            </Button>
        </div>
    </Form>
</template>
