<script setup lang="ts">
import { Form, Head, usePage, router } from '@inertiajs/vue3';
import { Plus, X } from '@lucide/vue';
import { computed, ref } from 'vue';
import UserController from '@/actions/App/Http/Controllers/UserController';
import Button from '@/components/base/Button.vue';
import Dialog from '@/components/base/Dialog.vue';
import Heading from '@/components/base/Heading.vue';
import Input from '@/components/base/Input.vue';
import InputError from '@/components/base/InputError.vue';
import Label from '@/components/base/Label.vue';
import PasswordInput from '@/components/base/PasswordInput.vue';
import { DataTable } from '@/components/data-table';
import type {
    DataTableColumn,
    DataTablePagination,
    DataTableRowClickEvent,
} from '@/components/data-table';
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

const columns: DataTableColumn[] = [
    { field: 'name', header: 'Name', sortable: true },
    { field: 'email', header: 'Email', sortable: true },
    {
        field: 'created_at',
        header: 'Created',
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
                title: 'Users',
                href: usersIndex(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Users" />

    <div class="flex flex-col px-4 py-6">
        <Heading
            title="Users"
            description="Manage the users who can access this application"
        />

        <DataTable
            :rows="props.users.data"
            :columns="columns"
            :pagination="props.users"
            :url="usersIndex().url"
            :search="props.filters?.search"
            empty-message="No users found."
            @row-click="handleRowClick"
        >
            <template #header>
                <Button type="button" @click="openCreate">
                    <Plus class="size-4" />
                    Add user
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
                        title="Delete user"
                        :disabled="data.id === currentUserId"
                        @click="openDelete(data)"
                        class="cursor-pointer"
                    >
                        <X class="size-4" />
                        <span class="sr-only">Delete {{ data.name }}</span>
                    </Button>
                </div>
            </template>
        </DataTable>
    </div>

    <Dialog
        v-model:open="formOpen"
        :title="selectedUser ? 'Edit user' : 'Add user'"
        :description="
            selectedUser
                ? 'Update this user account.'
                : 'Create a user account with a secure password.'
        "
    >
        <template #default="{ close }">
            <Form
                v-if="selectedUser"
                v-bind="UserController.update.form(selectedUser.id)"
                class="space-y-4"
                @success="closeForm"
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="edit-name">Name</Label>
                    <Input
                        id="edit-name"
                        name="name"
                        :default-value="selectedUser.name"
                        required
                    />
                    <InputError :message="errors.name" />
                </div>
                <div class="grid gap-2">
                    <Label for="edit-email">Email</Label>
                    <Input
                        id="edit-email"
                        name="email"
                        type="email"
                        :default-value="selectedUser.email"
                        required
                    />
                    <InputError :message="errors.email" />
                </div>
                <div class="grid gap-2">
                    <Label for="edit-password">New password</Label>
                    <PasswordInput
                        id="edit-password"
                        name="password"
                        autocomplete="new-password"
                    />
                    <InputError :message="errors.password" />
                </div>
                <div class="grid gap-2">
                    <Label for="edit-password-confirmation"
                        >Confirm new password</Label
                    >
                    <PasswordInput
                        id="edit-password-confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                    />
                </div>
                <div
                    class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end"
                >
                    <Button type="button" variant="secondary" @click="close"
                        >Cancel</Button
                    >
                    <Button type="submit" :disabled="processing"
                        >Save changes</Button
                    >
                </div>
            </Form>

            <Form
                v-else
                v-bind="UserController.store.form()"
                class="space-y-4"
                reset-on-success
                @success="closeForm"
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" name="name" required />
                    <InputError :message="errors.name" />
                </div>
                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input id="email" name="email" type="email" required />
                    <InputError :message="errors.email" />
                </div>
                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <PasswordInput
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        required
                    />
                    <InputError :message="errors.password" />
                </div>
                <div class="grid gap-2">
                    <Label for="password-confirmation">Confirm password</Label>
                    <PasswordInput
                        id="password-confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        required
                    />
                </div>
                <div
                    class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end"
                >
                    <Button type="button" variant="secondary" @click="close"
                        >Cancel</Button
                    >
                    <Button type="submit" :disabled="processing"
                        >Add user</Button
                    >
                </div>
            </Form>
        </template>
    </Dialog>

    <Dialog
        v-model:open="deleteOpen"
        title="Delete user?"
        :description="`This permanently removes ${selectedUser?.name} and cannot be undone.`"
    >
        <template #default="{ close }">
            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <Button type="button" variant="secondary" @click="close"
                    >Cancel</Button
                >
                <Button type="button" variant="destructive" @click="deleteUser"
                    >Delete user</Button
                >
            </div>
        </template>
    </Dialog>
</template>
