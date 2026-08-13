<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import Button from '@/components/base/Button.vue';
import FormField from '@/components/base/FormField.vue';
import Input from '@/components/base/Input.vue';
import PasswordInput from '@/components/base/PasswordInput.vue';
import TextLink from '@/components/base/TextLink.vue';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/lib/authRoutes';
import { login } from '@/routes';

defineProps<{
    passwordRules: string;
}>();

defineOptions({
    layout: {
        title: 'Create an account',
        description: 'Enter your details below to create your account',
    },
});
</script>

<template>
    <Head title="Register" />

    <Form
        :action="register()"
        method="post"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-6">
            <FormField id="name" label="Name" :error="errors.name" required>
                <template #default="field">
                    <Input
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        name="name"
                        placeholder="Full name"
                        v-bind="field"
                    />
                </template>
            </FormField>

            <FormField
                id="email"
                label="Email address"
                :error="errors.email"
                required
            >
                <template #default="field">
                    <Input
                        type="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        name="email"
                        placeholder="email@example.com"
                        v-bind="field"
                    />
                </template>
            </FormField>

            <FormField
                id="password"
                label="Password"
                :error="errors.password"
                required
            >
                <template #default="field">
                    <PasswordInput
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        name="password"
                        placeholder="Password"
                        :passwordrules="passwordRules"
                        v-bind="field"
                    />
                </template>
            </FormField>

            <FormField
                id="password_confirmation"
                label="Confirm password"
                :error="errors.password_confirmation"
                required
            >
                <template #default="field">
                    <PasswordInput
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="Confirm password"
                        :passwordrules="passwordRules"
                        v-bind="field"
                    />
                </template>
            </FormField>

            <Button
                type="submit"
                class="mt-2 w-full"
                tabindex="5"
                :disabled="processing"
                data-test="register-user-button"
            >
                <Spinner v-if="processing" />
                Create account
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            Already have an account?
            <TextLink
                :href="login()"
                class="underline underline-offset-4"
                :tabindex="6"
                >Log in</TextLink
            >
        </div>
    </Form>
</template>
