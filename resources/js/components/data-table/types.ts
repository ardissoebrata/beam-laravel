export type { DataTableRowClickEvent } from 'primevue/datatable';

export interface DataTableColumn {
    field: string;
    header: string;
    sortable?: boolean;
    width?: string;
    align?: 'left' | 'center' | 'right';
    class?: string;
}

export interface DataTablePagination {
    current_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
}
