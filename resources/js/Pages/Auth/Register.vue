<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { useForm } from 'vee-validate';
import { typedRegisterSchema } from '@/utils/validation';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Button from 'primevue/button';
import Message from 'primevue/message';

const { defineField, handleSubmit, errors } = useForm({
    validationSchema: typedRegisterSchema,
    initialValues: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    },
});

const [name, nameAttrs] = defineField('name');
const [email, emailAttrs] = defineField('email');
const [password, passwordAttrs] = defineField('password');
const [password_confirmation, passwordConfirmationAttrs] = defineField('password_confirmation');

const submit = handleSubmit((values) => {
    router.post(route('register'), values, {
        onFinish: () => {
            password.value = '';
            password_confirmation.value = '';
        },
    });
});
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit="submit" class="space-y-6">
            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                    Name
                </label>
                <InputText
                    id="name"
                    v-model="name"
                    v-bind="nameAttrs"
                    :invalid="!!errors.name"
                    autocomplete="name"
                    class="w-full"
                    autofocus
                />
                <Message v-if="errors.name" severity="error" :closable="false" size="small" variant="simple" class="mt-2">
                    {{ errors.name }}
                </Message>
            </div>

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
                />
                <Message v-if="errors.email" severity="error" :closable="false" size="small" variant="simple" class="mt-2">
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
                    toggleMask
                    :feedback="false"
                    autocomplete="new-password"
                    class="w-full"
                    inputClass="w-full"
                />
                <Message v-if="errors.password" severity="error" :closable="false" size="small" variant="simple" class="mt-2">
                    {{ errors.password }}
                </Message>
            </div>

            <div>
                <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-700">
                    Confirm Password
                </label>
                <Password
                    id="password_confirmation"
                    v-model="password_confirmation"
                    v-bind="passwordConfirmationAttrs"
                    :invalid="!!errors.password_confirmation"
                    toggleMask
                    :feedback="false"
                    autocomplete="new-password"
                    class="w-full"
                    inputClass="w-full"
                />
                <Message v-if="errors.password_confirmation" severity="error" :closable="false" size="small" variant="simple" class="mt-2">
                    {{ errors.password_confirmation }}
                </Message>
            </div>

            <div class="flex items-center justify-between">
                <Link
                    :href="route('login')"
                    class="text-sm text-gray-600 underline hover:text-gray-900"
                >
                    Already registered?
                </Link>

                <Button
                    type="submit"
                    label="Register"
                />
            </div>
        </form>
    </GuestLayout>
</template>
