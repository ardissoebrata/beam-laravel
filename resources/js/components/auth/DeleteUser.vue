<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { useTemplateRef } from 'vue';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import Button from '@/components/base/Button.vue';
import Dialog from '@/components/base/Dialog.vue';
import Heading from '@/components/base/Heading.vue';
import InputError from '@/components/base/InputError.vue';
import Label from '@/components/base/Label.vue';
import PasswordInput from '@/components/base/PasswordInput.vue';
import { useTranslations } from '@/composables/useTranslations';

const passwordInput = useTemplateRef('passwordInput');
const { t } = useTranslations();
</script>

<template>
    <div class="space-y-6">
        <Heading
            variant="small"
            :title="t('account.deleteTitle')"
            description="Hapus akun dan semua sumber dayanya"
        />
        <div
            class="space-y-4 rounded-lg border border-red-100 bg-red-50 p-4 dark:border-red-200/10 dark:bg-red-700/10"
        >
            <div class="relative space-y-0.5 text-red-600 dark:text-red-100">
                <p class="font-medium">Peringatan</p>
                <p class="text-sm">
                    Harap lanjutkan dengan hati-hati, tindakan ini tidak dapat
                    dibatalkan.
                </p>
            </div>
            <Dialog
                :title="t('account.deleteConfirm')"
                :description="t('account.passwordToConfirm')"
            >
                <template #trigger>
                    <Button
                        variant="destructive"
                        data-test="delete-user-button"
                        >{{ t('account.deleteTitle') }}</Button
                    >
                </template>
                <template #default="{ close }">
                    <Form
                        v-bind="ProfileController.destroy.form()"
                        reset-on-success
                        @error="() => passwordInput?.focus()"
                        :options="{
                            preserveScroll: true,
                        }"
                        class="space-y-6"
                        v-slot="{ errors, processing, reset, clearErrors }"
                    >
                        <div class="grid gap-2">
                            <Label for="password" class="sr-only">{{
                                t('auth.password')
                            }}</Label>
                            <PasswordInput
                                id="password"
                                name="password"
                                ref="passwordInput"
                                :placeholder="t('auth.password')"
                            />
                            <InputError :message="errors.password" />
                        </div>

                        <div
                            class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end"
                        >
                            <Button
                                variant="secondary"
                                @click="
                                    () => {
                                        clearErrors();
                                        reset();
                                        close();
                                    }
                                "
                            >
                                {{ t('common.cancel') }}
                            </Button>

                            <Button
                                type="submit"
                                variant="destructive"
                                :disabled="processing"
                                data-test="confirm-delete-user-button"
                            >
                                {{ t('account.deleteTitle') }}
                            </Button>
                        </div>
                    </Form>
                </template>
            </Dialog>
        </div>
    </div>
</template>
