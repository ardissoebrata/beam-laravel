<script setup lang="ts">
import { Form, Head, usePage, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2 } from '@lucide/vue';
import { computed, ref } from 'vue';
import UserController from '@/actions/App/Http/Controllers/UserController';
import Heading from '@/components/base/Heading.vue';
import InputError from '@/components/form/InputError.vue';
import PasswordInput from '@/components/form/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index as usersIndex } from '@/routes/users';

interface User {
    id: number;
    name: string;
    email: string;
    created_at: string;
}

const props = defineProps<{
    users: User[];
}>();

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
    new Intl.DateTimeFormat(undefined, {
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

    <div class="flex flex-col gap-6 px-4 py-6">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <Heading
                title="Users"
                description="Manage the users who can access this application"
            />
            <Button type="button" @click="openCreate">
                <Plus class="size-4" />
                Add user
            </Button>
        </div>

        <div class="overflow-hidden rounded-lg border bg-card">
            <div v-if="props.users.length === 0" class="p-8 text-center">
                <p class="font-medium">No users found</p>
                <p class="mt-1 text-sm text-muted-foreground">
                    Add the first user to get started.
                </p>
            </div>

            <table v-else class="w-full text-left text-sm">
                <thead class="border-b bg-muted/40 text-muted-foreground">
                    <tr>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Email</th>
                        <th class="px-4 py-3 font-medium">Created</th>
                        <th class="w-28 px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <tr v-for="user in props.users" :key="user.id">
                        <td class="px-4 py-3 font-medium">{{ user.name }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ user.email }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ formatDate(user.created_at) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-1">
                                <Button
                                    type="button"
                                    variant="ghost"
                                    size="icon"
                                    title="Edit user"
                                    @click="openEdit(user)"
                                >
                                    <Pencil class="size-4" />
                                    <span class="sr-only">Edit {{ user.name }}</span>
                                </Button>
                                <Button
                                    type="button"
                                    variant="ghost"
                                    size="icon"
                                    title="Delete user"
                                    :disabled="user.id === currentUserId"
                                    @click="openDelete(user)"
                                >
                                    <Trash2 class="size-4" />
                                    <span class="sr-only">Delete {{ user.name }}</span>
                                </Button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <Dialog v-model:open="formOpen">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ selectedUser ? 'Edit user' : 'Add user' }}</DialogTitle>
                <DialogDescription>
                    {{ selectedUser ? 'Update this user account.' : 'Create a user account with a secure password.' }}
                </DialogDescription>
            </DialogHeader>

            <Form
                v-if="selectedUser"
                v-bind="UserController.update.form(selectedUser.id)"
                class="space-y-4"
                @success="closeForm"
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="edit-name">Name</Label>
                    <Input id="edit-name" name="name" :default-value="selectedUser.name" required />
                    <InputError :message="errors.name" />
                </div>
                <div class="grid gap-2">
                    <Label for="edit-email">Email</Label>
                    <Input id="edit-email" name="email" type="email" :default-value="selectedUser.email" required />
                    <InputError :message="errors.email" />
                </div>
                <div class="grid gap-2">
                    <Label for="edit-password">New password</Label>
                    <PasswordInput id="edit-password" name="password" autocomplete="new-password" />
                    <InputError :message="errors.password" />
                </div>
                <div class="grid gap-2">
                    <Label for="edit-password-confirmation">Confirm new password</Label>
                    <PasswordInput id="edit-password-confirmation" name="password_confirmation" autocomplete="new-password" />
                </div>
                <DialogFooter>
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Cancel</Button>
                    </DialogClose>
                    <Button type="submit" :disabled="processing">Save changes</Button>
                </DialogFooter>
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
                    <PasswordInput id="password" name="password" autocomplete="new-password" required />
                    <InputError :message="errors.password" />
                </div>
                <div class="grid gap-2">
                    <Label for="password-confirmation">Confirm password</Label>
                    <PasswordInput id="password-confirmation" name="password_confirmation" autocomplete="new-password" required />
                </div>
                <DialogFooter>
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Cancel</Button>
                    </DialogClose>
                    <Button type="submit" :disabled="processing">Add user</Button>
                </DialogFooter>
            </Form>
        </DialogContent>
    </Dialog>

    <Dialog v-model:open="deleteOpen">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Delete user?</DialogTitle>
                <DialogDescription>
                    This permanently removes {{ selectedUser?.name }} and cannot be undone.
                </DialogDescription>
            </DialogHeader>
            <DialogFooter>
                <DialogClose as-child>
                    <Button type="button" variant="secondary">Cancel</Button>
                </DialogClose>
                <Button type="button" variant="destructive" @click="deleteUser">Delete user</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
