<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import ConfirmDialog from 'primevue/confirmdialog';
import Toast from 'primevue/toast';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';

interface User {
    id: number;
    name: string;
    email: string;
    role: 'admin' | 'member';
    created_at: string;
}

interface PaginatedUsers {
    data: User[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

const props = defineProps<{
    users: PaginatedUsers;
}>();

const confirm = useConfirm();
const toast = useToast();

const deleteUser = (user: User) => {
    confirm.require({
        message: `Are you sure you want to delete ${user.name}?`,
        header: 'Confirm Deletion',
        icon: 'pi pi-exclamation-triangle',
        rejectClass: 'p-button-secondary p-button-outlined',
        rejectLabel: 'Cancel',
        acceptLabel: 'Delete',
        acceptClass: 'p-button-danger',
        accept: () => {
            router.delete(route('users.destroy', user.id), {
                onSuccess: () => {
                    toast.add({
                        severity: 'success',
                        summary: 'Success',
                        detail: 'User deleted successfully',
                        life: 3000,
                    });
                },
                onError: () => {
                    toast.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'Failed to delete user',
                        life: 3000,
                    });
                },
            });
        },
    });
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="User Management" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    User Management
                </h2>
                <Link :href="route('users.create')">
                    <Button label="Create User" icon="pi pi-plus" />
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <Toast />
                        <ConfirmDialog />

                        <DataTable
                            :value="users.data"
                            :paginator="true"
                            :rows="users.per_page"
                            :totalRecords="users.total"
                            stripedRows
                            class="p-datatable-sm"
                        >
                            <Column field="name" header="Name" sortable />
                            <Column field="email" header="Email" sortable />
                            <Column field="role" header="Role" sortable>
                                <template #body="{ data }">
                                    <span
                                        :class="{
                                            'bg-blue-100 text-blue-800': data.role === 'admin',
                                            'bg-gray-100 text-gray-800': data.role === 'member',
                                        }"
                                        class="rounded-full px-3 py-1 text-xs font-semibold uppercase"
                                    >
                                        {{ data.role }}
                                    </span>
                                </template>
                            </Column>
                            <Column field="created_at" header="Created" sortable>
                                <template #body="{ data }">
                                    {{ formatDate(data.created_at) }}
                                </template>
                            </Column>
                            <Column header="Actions">
                                <template #body="{ data }">
                                    <div class="flex gap-2">
                                        <Link :href="route('users.edit', data.id)">
                                            <Button
                                                icon="pi pi-pencil"
                                                severity="info"
                                                text
                                                size="small"
                                            />
                                        </Link>
                                        <Button
                                            icon="pi pi-trash"
                                            severity="danger"
                                            text
                                            size="small"
                                            @click="deleteUser(data)"
                                        />
                                    </div>
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
