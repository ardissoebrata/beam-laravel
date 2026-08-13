<script setup lang="ts">
import { Form, Head, usePage, router } from '@inertiajs/vue3';
import { Plus, X } from '@lucide/vue';
import { computed, ref } from 'vue';
import UserController from '@/actions/App/Http/Controllers/UserController';
import Button from '@/components/base/Button.vue';
import Dialog from '@/components/base/Dialog.vue';
import FormField from '@/components/base/FormField.vue';
import Heading from '@/components/base/Heading.vue';
import Input from '@/components/base/Input.vue';
import PasswordInput from '@/components/base/PasswordInput.vue';
import { DataTable } from '@/components/data-table';
import type {
    DataTableColumn,
    DataTablePagination,
    DataTableRowClickEvent,
} from '@/components/data-table';
import { useTranslations } from '@/composables/useTranslations';
import { index as usersIndex } from '@/routes/users';

interface User {
    id: number;
    name: string;
    email: string;
    created_at: string;
}

interface UserPagination extends DataTablePagination {
    data: User[];
}

const props = defineProps<{
    users: UserPagination;
    filters?: {
        search?: string;
    };
}>();

const { t } = useTranslations();

const columns: DataTableColumn[] = [
    { field: 'name', header: t('users.name'), sortable: true },
    { field: 'email', header: t('users.email'), sortable: true },
    {
        field: 'created_at',
        header: t('users.created'),
        sortable: true,
        width: '100px',
        align: 'right',
    },
];

const page = usePage();
const currentUserId = computed(() => page.props.auth.user.id);
const formOpen = ref(false);
const deleteOpen = ref(false);
const selectedUser = ref<User | null>(null);

const openCreate = () => {
    selectedUser.value = null;
    formOpen.value = true;
};

const openEdit = (user: User) => {
    selectedUser.value = user;
    formOpen.value = true;
};

const handleRowClick = (event: DataTableRowClickEvent<User>) => {
    const target = event.originalEvent.target;

    if (
        target instanceof Element &&
        target.closest('button, a, input, [role="button"]')
    ) {
        return;
    }

    openEdit(event.data);
};

const openDelete = (user: User) => {
    selectedUser.value = user;
    deleteOpen.value = true;
};

const closeForm = () => {
    formOpen.value = false;
    selectedUser.value = null;
};

const closeDelete = () => {
    deleteOpen.value = false;
    selectedUser.value = null;
};

const deleteUser = () => {
    if (!selectedUser.value) {
        return;
    }

    router.delete(UserController.destroy.url(selectedUser.value.id), {
        preserveScroll: true,
        onSuccess: closeDelete,
    });
};

const formatDate = (date: string) =>
    new Intl.DateTimeFormat('id', {
        dateStyle: 'medium',
    }).format(new Date(date));

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Pengguna',
                href: usersIndex(),
            },
        ],
    },
});
</script>

<template>
    <Head :title="t('users.title')" />

    <div class="flex flex-col px-4 py-6">
        <Heading
            :title="t('users.title')"
            :description="t('users.description')"
        />

        <DataTable
            :rows="props.users.data"
            :columns="columns"
            :pagination="props.users"
            :url="usersIndex().url"
            :search="props.filters?.search"
            :empty-message="t('users.noUsers')"
            @row-click="handleRowClick"
        >
            <template #header>
                <Button type="button" @click="openCreate">
                    <Plus class="size-4" />
                    {{ t('users.add') }}
                </Button>
            </template>
            <template #body:name="{ data }">
                <span class="font-base">{{ data.name }}</span>
            </template>
            <template #body:email="{ data }">
                <span class="text-muted-foreground">{{ data.email }}</span>
            </template>
            <template #body:created_at="{ data }">
                <span class="text-muted-foreground">{{
                    formatDate(data.created_at)
                }}</span>
            </template>
            <template #actions="{ data }">
                <div class="flex justify-end gap-1">
                    <Button
                        type="button"
                        variant="ghost"
                        size="icon"
                        :title="t('users.delete')"
                        :disabled="data.id === currentUserId"
                        @click="openDelete(data)"
                        class="cursor-pointer"
                    >
                        <X class="size-4" />
                        <span class="sr-only">{{ t('users.deleteLabel', { name: data.name }) }}</span>
                    </Button>
                </div>
            </template>
        </DataTable>
    </div>

    <Dialog
        v-model:open="formOpen"
        :title="selectedUser ? t('users.edit') : t('users.add')"
        :description="
            selectedUser
                ? t('users.updateDescription')
                : t('users.createDescription')
        "
    >
        <template #default="{ close }">
            <Form
                v-if="selectedUser"
                v-bind="UserController.update.form(selectedUser.id)"
                class="space-y-6"
                @success="closeForm"
                v-slot="{ errors, processing, validate, invalid, validating }"
            >
                <FormField
                    id="edit-name"
                    :label="t('users.name')"
                    :error="errors.name"
                    required
                >
                    <template #default="field">
                        <Input
                            name="name"
                            :default-value="selectedUser.name"
                            required
                            @blur="validate('name')"
                            @input="invalid('name') && validate('name')"
                            v-bind="field"
                        />
                    </template>
                </FormField>
                <FormField
                    id="edit-email"
                    :label="t('users.email')"
                    :error="errors.email"
                    required
                >
                    <template #default="field">
                        <Input
                            name="email"
                            type="email"
                            :default-value="selectedUser.email"
                            required
                            @blur="validate('email')"
                            @input="invalid('email') && validate('email')"
                            v-bind="field"
                        />
                    </template>
                </FormField>
                <FormField
                    id="edit-password"
                    :label="t('users.newPassword')"
                    :error="errors.password"
                >
                    <template #default="field">
                        <PasswordInput
                            name="password"
                            autocomplete="new-password"
                            @blur="validate('password')"
                            @input="invalid('password') && validate('password')"
                            v-bind="field"
                        />
                    </template>
                </FormField>
                <FormField
                    id="edit-password-confirmation"
                    :label="t('users.confirmNewPassword')"
                    :error="errors.password_confirmation"
                >
                    <template #default="field">
                        <PasswordInput
                            name="password_confirmation"
                            autocomplete="new-password"
                            @blur="validate('password_confirmation')"
                            @input="
                                invalid('password_confirmation') &&
                                validate('password_confirmation')
                            "
                            v-bind="field"
                        />
                    </template>
                </FormField>
                <div
                    class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end"
                >
                    <Button type="button" variant="secondary" @click="close"
                        >{{ t('common.cancel') }}</Button
                    >
                    <Button type="submit" :disabled="processing || validating"
                        >{{ t('common.save') }}</Button
                    >
                </div>
            </Form>

            <Form
                v-else
                v-bind="UserController.store.form()"
                class="space-y-6"
                reset-on-success
                @success="closeForm"
                v-slot="{ errors, processing, validate, invalid, validating }"
            >
                <FormField
                    id="name"
                    :label="t('users.name')"
                    :error="errors.name"
                    required
                >
                    <template #default="field">
                        <Input
                            name="name"
                            @blur="validate('name')"
                            @input="invalid('name') && validate('name')"
                            v-bind="field"
                        />
                    </template>
                </FormField>
                <FormField
                    id="email"
                    :label="t('users.email')"
                    :error="errors.email"
                    required
                >
                    <template #default="field">
                        <Input
                            name="email"
                            type="email"
                            @blur="validate('email')"
                            @input="invalid('email') && validate('email')"
                            v-bind="field"
                        />
                    </template>
                </FormField>
                <FormField
                    id="password"
                    :label="t('auth.password')"
                    :error="errors.password"
                    required
                >
                    <template #default="field">
                        <PasswordInput
                            name="password"
                            autocomplete="new-password"
                            @blur="validate('password')"
                            @input="invalid('password') && validate('password')"
                            v-bind="field"
                        />
                    </template>
                </FormField>
                <FormField
                    id="password-confirmation"
                    :label="t('auth.confirmPassword')"
                    :error="errors.password_confirmation"
                    required
                >
                    <template #default="field">
                        <PasswordInput
                            name="password_confirmation"
                            autocomplete="new-password"
                            @blur="validate('password_confirmation')"
                            @input="
                                invalid('password_confirmation') &&
                                validate('password_confirmation')
                            "
                            v-bind="field"
                        />
                    </template>
                </FormField>
                <div
                    class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end"
                >
                    <Button type="button" variant="secondary" @click="close"
                        >{{ t('common.cancel') }}</Button
                    >
                    <Button type="submit" :disabled="processing || validating"
                        >{{ t('users.add') }}</Button
                    >
                </div>
            </Form>
        </template>
    </Dialog>

    <Dialog
        v-model:open="deleteOpen"
        :title="`${t('users.delete')}?`"
        :description="`${t('users.deleteLabel', { name: selectedUser?.name ?? '' })}. Tindakan ini tidak dapat dibatalkan.`"
    >
        <template #default="{ close }">
            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <Button type="button" variant="secondary" @click="close"
                    >{{ t('common.cancel') }}</Button
                >
                <Button type="button" variant="destructive" @click="deleteUser"
                    >{{ t('users.delete') }}</Button
                >
            </div>
        </template>
    </Dialog>
</template>
