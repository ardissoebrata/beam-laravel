<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { useForm } from 'vee-validate';
import { typedDeleteUserSchema } from '@/utils/validation';
import Password from 'primevue/password';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import Message from 'primevue/message';
import { ref } from 'vue';

const visible = ref(false);

const { defineField, handleSubmit, errors, resetForm } = useForm({
    validationSchema: typedDeleteUserSchema,
    initialValues: {
        password: '',
    },
});

const [password, passwordAttrs] = defineField('password');

const deleteUser = handleSubmit((values) => {
    router.delete(route('profile.destroy'), {
        data: values,
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => {
            resetForm();
        },
    });
});

const closeModal = () => {
    visible.value = false;
    resetForm();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Delete Account
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Once your account is deleted, all of its resources and data will
                be permanently deleted. Before deleting your account, please
                download any data or information that you wish to retain.
            </p>
        </header>

        <Button
            label="Delete Account"
            severity="danger"
            @click="visible = true"
        />

        <Dialog
            v-model:visible="visible"
            modal
            header="Are you sure you want to delete your account?"
            :style="{ width: '30rem' }"
        >
            <p class="mb-4 text-sm text-gray-600">
                Once your account is deleted, all of its resources and data
                will be permanently deleted. Please enter your password to
                confirm you would like to permanently delete your account.
            </p>

            <form @submit="deleteUser">
                <div class="mb-4">
                    <label for="password" class="sr-only">
                        Password
                    </label>
                    <Password
                        id="password"
                        v-model="password"
                        v-bind="passwordAttrs"
                        :invalid="!!errors.password"
                        :feedback="false"
                        toggleMask
                        placeholder="Password"
                        class="w-full"
                        inputClass="w-full"
                        autofocus
                    />
                    <Message v-if="errors.password" severity="error" :closable="false" size="small" variant="simple" class="mt-2">
                        {{ errors.password }}
                    </Message>
                </div>

                <div class="flex justify-end gap-2">
                    <Button
                        type="button"
                        label="Cancel"
                        severity="secondary"
                        outlined
                        @click="closeModal"
                    />
                    <Button
                        type="submit"
                        label="Delete Account"
                        severity="danger"
                    />
                </div>
            </form>
        </Dialog>
    </section>
</template>
