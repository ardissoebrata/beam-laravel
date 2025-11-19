<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { useForm } from 'vee-validate';
import { typedUserSchema } from '@/utils/validation';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Button from 'primevue/button';
import Select from 'primevue/select';
import Message from 'primevue/message';

interface User {
    id: number;
    name: string;
    email: string;
    role: 'admin' | 'member';
}

const props = defineProps<{
    user: User;
}>();

const { defineField, handleSubmit, errors } = useForm({
    validationSchema: typedUserSchema,
    initialValues: {
        name: props.user.name,
        email: props.user.email,
        role: props.user.role,
        password: '',
        password_confirmation: '',
    },
});

const [name, nameAttrs] = defineField('name');
const [email, emailAttrs] = defineField('email');
const [password, passwordAttrs] = defineField('password');
const [password_confirmation, passwordConfirmationAttrs] = defineField('password_confirmation');
const [role, roleAttrs] = defineField('role');

const roleOptions = [
    { label: 'Admin', value: 'admin' },
    { label: 'Member', value: 'member' },
];

const onSubmit = handleSubmit((values) => {
    router.patch(route('users.update', props.user.id), values, {
        onError: (errors) => {
            console.error('Validation errors:', errors);
        },
    });
});
</script>

<template>
    <Head title="Edit User" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit User
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit="onSubmit" class="space-y-6">
                            <div>
                                <label
                                    for="name"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Name
                                </label>
                                <InputText
                                    id="name"
                                    v-model="name"
                                    v-bind="nameAttrs"
                                    :invalid="!!errors.name"
                                    class="w-full"
                                />
                                <Message v-if="errors.name" severity="error" :closable="false" class="mt-2">
                                    {{ errors.name }}
                                </Message>
                            </div>

                            <div>
                                <label
                                    for="email"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Email
                                </label>
                                <InputText
                                    id="email"
                                    v-model="email"
                                    v-bind="emailAttrs"
                                    :invalid="!!errors.email"
                                    type="email"
                                    class="w-full"
                                />
                                <Message v-if="errors.email" severity="error" :closable="false" class="mt-2">
                                    {{ errors.email }}
                                </Message>
                            </div>

                            <div>
                                <label
                                    for="role"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Role
                                </label>
                                <Select
                                    id="role"
                                    v-model="role"
                                    v-bind="roleAttrs"
                                    :options="roleOptions"
                                    optionLabel="label"
                                    optionValue="value"
                                    placeholder="Select a role"
                                    :invalid="!!errors.role"
                                    class="w-full"
                                />
                                <Message v-if="errors.role" severity="error" :closable="false" class="mt-2">
                                    {{ errors.role }}
                                </Message>
                            </div>

                            <div>
                                <label
                                    for="password"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Password (leave blank to keep current)
                                </label>
                                <Password
                                    id="password"
                                    v-model="password"
                                    v-bind="passwordAttrs"
                                    :invalid="!!errors.password"
                                    toggleMask
                                    :feedback="false"
                                    class="w-full"
                                    inputClass="w-full"
                                />
                                <Message v-if="errors.password" severity="error" :closable="false" class="mt-2">
                                    {{ errors.password }}
                                </Message>
                            </div>

                            <div>
                                <label
                                    for="password_confirmation"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Confirm Password
                                </label>
                                <Password
                                    id="password_confirmation"
                                    v-model="password_confirmation"
                                    v-bind="passwordConfirmationAttrs"
                                    :invalid="!!errors.password_confirmation"
                                    toggleMask
                                    :feedback="false"
                                    class="w-full"
                                    inputClass="w-full"
                                />
                                <Message
                                    v-if="errors.password_confirmation"
                                    severity="error"
                                    :closable="false"
                                    class="mt-2"
                                >
                                    {{ errors.password_confirmation }}
                                </Message>
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <Link :href="route('users.index')">
                                    <Button
                                        label="Cancel"
                                        severity="secondary"
                                        outlined
                                        type="button"
                                    />
                                </Link>
                                <Button label="Update User" type="submit" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
