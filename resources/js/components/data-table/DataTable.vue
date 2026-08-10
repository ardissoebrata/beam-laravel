<script setup lang="ts" generic="TRow extends object">
import { router } from '@inertiajs/vue3';
import PrimeColumn from 'primevue/column';
import { onBeforeUnmount, ref, watch } from 'vue';
import VoltDataTable from '@/components/volt/DataTable.vue';
import type {
    DataTableColumn,
    DataTablePagination,
    DataTableRowClickEvent,
} from './types';

interface Props {
    rows: TRow[];
    columns: DataTableColumn[];
    pagination: DataTablePagination;
    url: string;
    dataKey?: string;
    loading?: boolean;
    searchable?: boolean;
    search?: string;
    searchPlaceholder?: string;
    perPageOptions?: number[];
    emptyMessage?: string;
}

interface QueryState {
    [key: string]: string | number | undefined;
    page: number;
    per_page: number;
    search?: string;
    sort_field?: string;
    sort_order?: 'asc' | 'desc';
}

const emit = defineEmits<{
    'row-click': [event: DataTableRowClickEvent<TRow>];
}>();

const props = withDefaults(defineProps<Props>(), {
    dataKey: 'id',
    loading: false,
    searchable: true,
    search: '',
    searchPlaceholder: 'Search...',
    perPageOptions: () => [10, 25, 50],
    emptyMessage: 'No records found.',
});

const searchValue = ref(props.search);
let searchTimeout: ReturnType<typeof setTimeout> | undefined;

const headerAlignmentClasses: Record<
    NonNullable<DataTableColumn['align']>,
    string
> = {
    left: 'justify-start',
    center: 'justify-center',
    right: 'justify-end',
};

const getHeaderAlignmentClass = (align: DataTableColumn['align']) =>
    headerAlignmentClasses[align ?? 'left'];

watch(
    () => props.search,
    (value) => {
        searchValue.value = value;
    },
);

const visit = (changes: Partial<QueryState>) => {
    const query: QueryState = {
        page: props.pagination.current_page,
        per_page: props.pagination.per_page,
        search: searchValue.value || undefined,
        ...changes,
    };

    if (!query.search) {
        delete query.search;
    }

    router.get(props.url, query, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

const handlePage = (event: { page: number; rows: number }) => {
    visit({
        page: event.page + 1,
        per_page: event.rows,
    });
};

const handleSort = (event: {
    sortField?: string | ((item: unknown) => string);
    sortOrder?: 1 | -1 | 0 | null;
}) => {
    if (typeof event.sortField !== 'string' || !event.sortOrder) {
        visit({
            page: 1,
            sort_field: undefined,
            sort_order: undefined,
        });

        return;
    }

    visit({
        page: 1,
        sort_field: event.sortField,
        sort_order: event.sortOrder === 1 ? 'asc' : 'desc',
    });
};

const handleRowClick = (event: DataTableRowClickEvent<TRow>) => {
    emit('row-click', event);
};

const handleSearch = () => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }

    searchTimeout = setTimeout(() => {
        visit({ page: 1 });
    }, 300);
};

onBeforeUnmount(() => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
});
</script>

<template>
    <div class="flex flex-col gap-4">
        <div v-if="searchable" class="flex justify-end">
            <input
                v-model="searchValue"
                type="search"
                :placeholder="searchPlaceholder"
                class="h-9 w-full rounded-md border border-input bg-background px-3 text-sm outline-none transition-colors placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-2 focus-visible:ring-ring/50 sm:w-72"
                @input="handleSearch"
            />
        </div>

        <div class="overflow-x-auto rounded-lg border">
            <VoltDataTable
                :value="rows"
                :data-key="dataKey"
                :lazy="true"
                :loading="loading"
                paginator
                :first="(pagination.current_page - 1) * pagination.per_page"
                :rows="pagination.per_page"
                :total-records="pagination.total"
                :rows-per-page-options="perPageOptions"
                :show-headers="true"
                removable-sort
                row-hover
                striped-rows
                scrollable
                class="min-w-2xl text-sm"
                @page="handlePage"
                @sort="handleSort"
                @row-click="handleRowClick"
            >
                <template v-if="$slots.header" #header>
                    <slot name="header" />
                </template>

                <PrimeColumn
                    v-for="column in columns"
                    :key="column.field"
                    :field="column.field"
                    :sortable="column.sortable"
                    :style="column.width ? { width: column.width } : undefined"
                    :header-style="{ textAlign: column.align ?? 'left' }"
                    :body-style="{ textAlign: column.align ?? 'left' }"
                    :pt="{
                        columnHeaderContent: getHeaderAlignmentClass(column.align),
                    }"
                    :class="column.class"
                >
                    <template #header>
                        <span class="font-medium">{{ column.header }}</span>
                    </template>
                    <template #body="slotProps">
                        <slot
                            :name="`body:${column.field}`"
                            v-bind="slotProps"
                        >
                            {{ slotProps.data[column.field] }}
                        </slot>
                    </template>
                </PrimeColumn>

                <PrimeColumn v-if="$slots.actions" :style="'width: 50px;'">
                    <template #body="slotProps">
                        <slot name="actions" v-bind="slotProps" />
                    </template>
                </PrimeColumn>

                <template #empty>
                    <slot name="empty">
                        <div class="p-8 text-center">
                            <p class="font-medium">{{ emptyMessage }}</p>
                        </div>
                    </slot>
                </template>

                <template #loading>
                    <slot name="loading">
                        <div class="p-8 text-center text-muted-foreground">
                            Loading...
                        </div>
                    </slot>
                </template>
            </VoltDataTable>
        </div>
    </div>
</template>
