<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import Button from '@/components/base/Button.vue';
import Input from '@/components/base/Input.vue';
import Label from '@/components/base/Label.vue';
import TextLink from '@/components/base/TextLink.vue';
import FormField from '@/components/base/FormField.vue';
import PasswordInput from '@/components/base/PasswordInput.vue';
import { Checkbox } from '@/components/ui/checkbox';
import { Spinner } from '@/components/ui/spinner';
import { forgotPassword, register } from '@/lib/authRoutes';
import { store } from '@/routes/login';

defineOptions({
    layout: {
        title: 'Log in to your account',
        description: 'Enter your email and password below to log in',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();
</script>

<template>
    <Head title="Log in" />

    <div
        v-if="status"
        class="mb-4 text-center text-sm font-medium text-green-600"
    >
        {{ status }}
    </div>

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-6">
            <FormField
                id="email"
                label="Email address"
                :error="errors.email"
                required
            >
                <template #default="field">
                    <Input
                        type="email"
                        name="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
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
                    <div class="flex items-center justify-between">
                        <span class="sr-only">Password</span>
                        <TextLink
                            v-if="canResetPassword"
                            :href="forgotPassword()"
                            class="text-sm"
                            :tabindex="5"
                        >
                            Forgot your password?
                        </TextLink>
                    </div>
                    <PasswordInput
                        name="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        placeholder="Password"
                        v-bind="field"
                    />
                </template>
            </FormField>

            <div class="flex items-center justify-between">
                <Label for="remember" class="flex items-center space-x-3">
                    <Checkbox id="remember" name="remember" :tabindex="3" />
                    <span>Remember me</span>
                </Label>
            </div>

            <Button
                type="submit"
                class="mt-4 w-full"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
            >
                <Spinner v-if="processing" />
                Log in
            </Button>
        </div>

        <div
            v-if="$page.props.auth.features.registration"
            class="text-center text-sm text-muted-foreground"
        >
            Don't have an account?
            <TextLink :href="register()" :tabindex="5">Sign up</TextLink>
        </div>
    </Form>
</template>
