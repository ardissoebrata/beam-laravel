<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { useForm } from 'vee-validate';
import { typedConfirmPasswordSchema } from '@/utils/validation';
import Password from 'primevue/password';
import Button from 'primevue/button';
import Message from 'primevue/message';

const { defineField, handleSubmit, errors } = useForm({
    validationSchema: typedConfirmPasswordSchema,
    initialValues: {
        password: '',
    },
});

const [password, passwordAttrs] = defineField('password');

const submit = handleSubmit((values) => {
    router.post(route('password.confirm'), values, {
        onFinish: () => {
            password.value = '';
        },
    });
});
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <div class="mb-4 text-sm text-gray-600">
            This is a secure area of the application. Please confirm your
            password before continuing.
        </div>

        <form @submit="submit" class="space-y-6">
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
                    autofocus
                />
                <Message v-if="errors.password" severity="error" :closable="false" size="small" variant="simple" class="mt-2">
                    {{ errors.password }}
                </Message>
            </div>

            <div class="flex justify-end">
                <Button
                    type="submit"
                    label="Confirm"
                />
            </div>
        </form>
    </GuestLayout>
</template>
