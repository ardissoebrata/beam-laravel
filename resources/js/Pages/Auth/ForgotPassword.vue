<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { useForm } from 'vee-validate';
import { typedForgotPasswordSchema } from '@/utils/validation';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Message from 'primevue/message';

defineProps<{
    status?: string;
}>();

const { defineField, handleSubmit, errors } = useForm({
    validationSchema: typedForgotPasswordSchema,
    initialValues: {
        email: '',
    },
});

const [email, emailAttrs] = defineField('email');

const submit = handleSubmit((values) => {
    router.post(route('password.email'), values);
});
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just let us know your email
            address and we will email you a password reset link that will allow
            you to choose a new one.
        </div>

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
                <Message v-if="errors.email" severity="error" :closable="false" size="small" variant="simple" class="mt-2">
                    {{ errors.email }}
                </Message>
            </div>

            <div class="flex items-center justify-end">
                <Button
                    type="submit"
                    label="Email Password Reset Link"
                />
            </div>
        </form>
    </GuestLayout>
</template>
