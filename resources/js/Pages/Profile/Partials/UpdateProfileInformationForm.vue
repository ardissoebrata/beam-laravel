<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { useForm } from 'vee-validate';
import { typedUpdateProfileSchema } from '@/utils/validation';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Message from 'primevue/message';
import { ref } from 'vue';

defineProps<{
    mustVerifyEmail?: Boolean;
    status?: String;
}>();

const user = usePage().props.auth.user;
const recentlySuccessful = ref(false);

const { defineField, handleSubmit, errors } = useForm({
    validationSchema: typedUpdateProfileSchema,
    initialValues: {
        name: user.name,
        email: user.email,
    },
});

const [name, nameAttrs] = defineField('name');
const [email, emailAttrs] = defineField('email');

const submit = handleSubmit((values) => {
    router.patch(route('profile.update'), values, {
        onSuccess: () => {
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
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit="submit" class="mt-6 space-y-6">
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
                <Message v-if="errors.name" severity="error" :closable="false" class="mt-2">
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
                <Message v-if="errors.email" severity="error" :closable="false" class="mt-2">
                    {{ errors.email }}
                </Message>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="text-sm text-gray-600 underline hover:text-gray-900"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <Message
                    v-show="status === 'verification-link-sent'"
                    severity="success"
                    :closable="false"
                    class="mt-2"
                >
                    A new verification link has been sent to your email address.
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
