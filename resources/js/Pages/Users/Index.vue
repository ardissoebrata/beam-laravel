<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import Select from 'primevue/select';
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
    filters?: {
        search?: string;
        sortField?: string;
        sortOrder?: string;
    };
}>();

const confirm = useConfirm();
const toast = useToast();

// Local state for filters
const search = ref(props.filters?.search || '');
const loading = ref(false);
const perPage = ref(props.users.per_page);
// Row click navigates to edit
const onRowClick = (event: any) => {
    const user: User = event.data;
    if (user?.id) {
        router.visit(route('users.edit', user.id));
    }
};

// Rows per page change handler for Select component
const onRowsChange = () => {
    loadData({ page: 1, perPage: perPage.value });
};

// Computed first & last record numbers for current page
const firstRecord = computed(() => {
    if (!props.users.data.length) return 0;
    return (props.users.current_page - 1) * props.users.per_page + 1;
});
const lastRecord = computed(() => {
    if (!props.users.data.length) return 0;
    return firstRecord.value + props.users.data.length - 1;
});

// Handle pagination
const onPage = (event: any) => {
    const page = event.page + 1; // PrimeVue uses 0-based index
    perPage.value = event.rows;
    loadData({ page, perPage: event.rows });
};

// Handle sorting
const onSort = (event: any) => {
    const sortField = event.sortField;
    const sortOrder = event.sortOrder === 1 ? 'asc' : 'desc';
    loadData({ sortField, sortOrder });
};

// Load data with filters
const loadData = (params: any = {}) => {
    loading.value = true;

    router.get(
        route('users.index'),
        {
            search: search.value,
            page: params.page || props.users.current_page,
            perPage: params.perPage || perPage.value,
            sortField: params.sortField || props.filters?.sortField,
            sortOrder: params.sortOrder || props.filters?.sortOrder,
        },
        {
            preserveState: true,
            preserveScroll: true,
            onFinish: () => {
                loading.value = false;
            },
        }
    );
};

// Debounced search
let searchTimeout: ReturnType<typeof setTimeout>;
watch(search, (value) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadData({ page: 1 });
    }, 300);
});

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

                        <div class="flex flex-col md:flex-row justify-between gap-2 mb-4">
                            <div class="flex items-center text-sm">
                                <span v-if="users.total > 0">
                                    Showing {{ firstRecord }} to {{ lastRecord }} of
                                    {{ users.total }} users
                                </span>
                                <span v-else>
                                    -
                                </span>
                            </div>
                            <IconField>
                                <InputIcon class="pi pi-search" />
                                <InputText
                                    v-model="search"
                                    placeholder="Search users..."
                                    class="w-full md:w-96"
                                />
                            </IconField>
                        </div>

                        <DataTable
                            :value="users.data"
                            :lazy="true"
                            :paginator="true"
                            :rows="perPage"
                            :totalRecords="users.total"
                            :loading="loading"
                            :first="(users.current_page - 1) * users.per_page"
                            @page="onPage"
                            @sort="onSort"
                            @rowClick="onRowClick"
                            dataKey="id"
                            stripedRows
                            removableSort
                            class="p-datatable-sm cursor-pointer"
                            scrollable
                            scrollHeight="flex"
                            tableStyle="min-width: 50rem"
                            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
                        >
                            <template #paginatorstart>
                            </template>
                            <template #paginatorend>
                                <div class="flex items-center gap-2">
                                    <Select
                                        inputId="rows-select"
                                        v-model="perPage"
                                        :options="[10, 25, 50, 100]"
                                        @change="onRowsChange"
                                        size="small"
                                    />
                                </div>
                            </template>
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
                                    <div class="flex gap-2" @click.stop>
                                        <Button
                                            icon="pi pi-trash"
                                            severity="danger"
                                            text
                                            size="small"
                                            @click.stop="deleteUser(data)"
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
