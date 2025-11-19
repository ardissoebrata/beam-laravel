<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { useForm } from 'vee-validate';
import { typedLoginSchema } from '@/utils/validation';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Checkbox from 'primevue/checkbox';
import Button from 'primevue/button';
import Message from 'primevue/message';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const { defineField, handleSubmit, errors } = useForm({
    validationSchema: typedLoginSchema,
    initialValues: {
        email: '',
        password: '',
        remember: false,
    },
});

const [email, emailAttrs] = defineField('email');
const [password, passwordAttrs] = defineField('password');
const [remember, rememberAttrs] = defineField('remember');

const submit = handleSubmit((values) => {
    router.post(route('login'), values, {
        onFinish: () => {
            password.value = '';
        },
    });
});
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <Message v-if="status" severity="success" :closable="false" class="mb-4">
            {{ status }}
        </Message>

        <form @submit="submit" class="space-y-6">
            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
                    Email
                </label>
                <InputText
                    id="email"
                    v-model="email"
                    v-bind="emailAttrs"
                    type="email"
                    :invalid="!!errors.email"
                    autocomplete="username"
                    class="w-full"
                    autofocus
                />
                <Message v-if="errors.email" severity="error" :closable="false" class="mt-2">
                    {{ errors.email }}
                </Message>
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-medium text-gray-700">
                    Password
                </label>
                <Password
                    id="password"
                    v-model="password"
                    v-bind="passwordAttrs"
                    :invalid="!!errors.password"
                    :feedback="false"
                    toggleMask
                    autocomplete="current-password"
                    class="w-full"
                    inputClass="w-full"
                />
                <Message v-if="errors.password" severity="error" :closable="false" class="mt-2">
                    {{ errors.password }}
                </Message>
            </div>

            <div class="flex items-center">
                <Checkbox
                    v-model="remember"
                    v-bind="rememberAttrs"
                    inputId="remember"
                    :binary="true"
                />
                <label for="remember" class="ml-2 text-sm text-gray-600 cursor-pointer">
                    Remember me
                </label>
            </div>

            <div class="flex items-center justify-between">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-gray-600 underline hover:text-gray-900"
                >
                    Forgot your password?
                </Link>

                <Button
                    type="submit"
                    label="Log in"
                />
            </div>
        </form>
    </GuestLayout>
</template>
