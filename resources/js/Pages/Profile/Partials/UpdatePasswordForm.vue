<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { useForm } from 'vee-validate';
import { typedUpdatePasswordSchema } from '@/utils/validation';
import Password from 'primevue/password';
import Button from 'primevue/button';
import Message from 'primevue/message';
import { ref } from 'vue';

const recentlySuccessful = ref(false);

const { defineField, handleSubmit, errors, resetForm } = useForm({
    validationSchema: typedUpdatePasswordSchema,
    initialValues: {
        current_password: '',
        password: '',
        password_confirmation: '',
    },
});

const [current_password, currentPasswordAttrs] = defineField('current_password');
const [password, passwordAttrs] = defineField('password');
const [password_confirmation, passwordConfirmationAttrs] = defineField('password_confirmation');

const updatePassword = handleSubmit((values) => {
    router.put(route('password.update'), values, {
        preserveScroll: true,
        onSuccess: () => {
            resetForm();
            recentlySuccessful.value = true;
            setTimeout(() => {
                recentlySuccessful.value = false;
            }, 2000);
        },
    });
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Update Password
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Ensure your account is using a long, random password to stay
                secure.
            </p>
        </header>

        <form @submit="updatePassword" class="mt-6 space-y-6">
            <div>
                <label for="current_password" class="mb-2 block text-sm font-medium text-gray-700">
                    Current Password
                </label>
                <Password
                    id="current_password"
                    v-model="current_password"
                    v-bind="currentPasswordAttrs"
                    :invalid="!!errors.current_password"
                    :feedback="false"
                    toggleMask
                    autocomplete="current-password"
                    class="w-full"
                    inputClass="w-full"
                />
                <Message v-if="errors.current_password" severity="error" :closable="false" size="small" variant="simple" class="mt-2">
                    {{ errors.current_password }}
                </Message>
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-medium text-gray-700">
                    New Password
                </label>
                <Password
                    id="password"
                    v-model="password"
                    v-bind="passwordAttrs"
                    :invalid="!!errors.password"
                    :feedback="false"
                    toggleMask
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
                    :feedback="false"
                    toggleMask
                    autocomplete="new-password"
                    class="w-full"
                    inputClass="w-full"
                />
                <Message v-if="errors.password_confirmation" severity="error" :closable="false" size="small" variant="simple" class="mt-2">
                    {{ errors.password_confirmation }}
                </Message>
            </div>

            <div class="flex items-center gap-4">
                <Button type="submit" label="Save" />

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="recentlySuccessful" class="text-sm text-gray-600">
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
