<template>
    <DataTable
        ref="el"
        v-bind="props"
        unstyled
        :pt="theme"
        :ptOptions="{
            mergeProps: ptViewMerge,
        }"
    >
        <template
            #paginatorcontainer="{
                page,
                pageCount,
                first,
                last,
                rows,
                totalRecords,
                rowChangeCallback,
                prevPageCallback,
                nextPageCallback,
            }"
        >
            <div
                class="flex flex-wrap items-center justify-between gap-4 w-full"
            >
                <span class="text-sm text-muted-foreground">
                    {{
                        totalRecords
                            ? `${first}-${last} dari ${totalRecords}`
                            : '0-0 dari 0'
                    }}
                </span>
                <div class="flex items-center gap-1">
                    <Button
                        variant="secondary"
                        size="icon"
                        @click="prevPageCallback"
                        :disabled="page === 0"
                    >
                        <AngleLeftIcon />
                    </Button>
                    <Button
                        variant="secondary"
                        size="icon"
                        @click="nextPageCallback"
                        :disabled="page === pageCount! - 1"
                    >
                        <AngleRightIcon />
                    </Button>
                </div>
                <label class="flex items-center gap-2 text-sm text-surface-700 dark:text-surface-0">
                    <Select
                        :model-value="rows"
                        :options="(props.rowsPerPageOptions ?? [rows]).map((option) => ({ value: option, label: `${option} per halaman` }))"
                        class="h-9 rounded-md border border-surface-300 bg-surface-0 px-2 text-sm dark:border-surface-700 dark:bg-surface-950"
                        @update:model-value="(value) => rowChangeCallback(Number(value))"
                    >
                    </Select>
                </label>
            </div>
        </template>
        <template #loadingicon>
            <SpinnerIcon class="h-8 w-8 animate-spin text-[2rem]" />
        </template>
        <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
            <slot :name="slotName" v-bind="slotProps ?? {}" />
        </template>
    </DataTable>
</template>

<script setup lang="ts">
import AngleLeftIcon from '@primevue/icons/angleleft';
import AngleRightIcon from '@primevue/icons/angleright';
import SpinnerIcon from '@primevue/icons/spinner';
import DataTable from 'primevue/datatable';
import type {
    DataTablePassThroughOptions,
    DataTableProps,
} from 'primevue/datatable';
import { ref } from 'vue';
import Button from '@/components/base/Button.vue';
import Select from '@/components/base/Select.vue';
import { ptViewMerge } from './utils';

const props = defineProps<DataTableProps>();

const theme = ref<DataTablePassThroughOptions>({
    root: `relative p-flex-scrollable:flex p-flex-scrollable:flex-col p-flex-scrollable:h-full`,
    tableContainer: `p-scrollable:relative p-flex-scrollable:flex p-flex-scrollable:flex-col p-flex-scrollable:flex-1 p-flex-scrollable:h-full`,
    header: `p-2 border-b border-surface-200 dark:border-surface-700
        bg-surface-50 dark:bg-surface-900
        text-surface-700 dark:text-surface-0`,
    table: `border-spacing-0 w-full border-separate`,
    thead: `p-scrollable:bg-surface-0 dark:p-scrollable:bg-surface-900 p-scrollable:top-0 p-scrollable:z-10`,
    tbody: `p-hoverable:*:cursor-pointer p-hoverable:*:hover:bg-surface-100 p-hoverable:*:hover:text-surface-700 dark:p-hoverable:*:hover:bg-surface-800 dark:p-hoverable:*:hover:text-surface-0
        p-frozen:sticky p-frozen:z-10`,
    bodyRow:
        'text-surface-700 dark:text-surface-0 p-selectable:cursor-pointer p-selected:bg-highlight!',
    tfoot: `p-scrollable:bg-surface-0 dark:p-scrollable:bg-surface-900 p-scrollable:bottom-0 p-scrollable:z-10`,
    footer: `p-2 border-b border-surface-200 dark:border-surface-700
        bg-surface-50 dark:bg-surface-900
        text-surface-700 dark:text-surface-0`,
    mask: `bg-black/50 text-surface-200 absolute z-10 flex items-center justify-center w-full h-full backdrop-blur-md`,
    column: {
        root: ``,
        headerCell: `group p-2 font-normal text-start transition-colors duration-200
            border-b border-surface-200 dark:border-surface-700
            bg-surface-50 dark:bg-surface-900
            text-surface-700 dark:text-surface-0
            p-sortable:cursor-pointer p-sortable:select-none p-sortable:focus-visible:outline p-sortable:focus-visible:outline-1 p-sortable:focus-visible:-outline-offset-1 p-sortable:focus-visible:outline-primary
            p-sortable:not-p-sorted:hover:bg-surface-200 p-sortable:not-p-sorted:hover:text-surface-800
            dark:p-sortable:not-p-sorted:hover:bg-surface-800 dark:p-sortable:not-p-sorted:hover:text-surface-0
            p-sorted:bg-highlight
            p-frozen:sticky p-frozen:bg-surface-0 dark:p-frozen:bg-surface-900 p-frozen:z-10
        `,
        columnHeaderContent: `flex items-center gap-2`,
        columnTitle: `font-semibold`,
        bodyCell: `text-start p-2 border-b border-surface-200 dark:border-surface-800
            p-frozen:sticky p-frozen:bg-surface-0 dark:p-frozen:bg-surface-900`,
        bodyCellContent: ``,
        footerCell: `text-start p-2 border-b border-surface-200 dark:border-surface-800
            bg-surface-0 dark:bg-surface-900
            text-surface-700 dark:text-surface-0
            p-frozen:sticky p-frozen:bg-surface-0 dark:p-frozen:bg-surface-900`,
        columnFooter: `font-semibold`,
        columnResizer: `block absolute top-0 end-0 m-0 w-2 h-full p-0 cursor-col-resize border border-transparent`,
        sort: ``,
        sortIcon: `text-surface-500 dark:text-surface-400 transition-colors duration-200
            group-p-sortable:not-group-p-sorted:group-hover:text-surface-600 dark:group-p-sortable:not-group-p-sorted:group-hover:text-surface-300
            group-p-sorted:bg-highlight`,
        pcSortBadge: {
            root: `bg-primary text-primary-contrast rounded-full min-w-6 h-6 inline-flex items-center justify-center text-xs font-bold`,
        },
        pcHeaderCheckbox: {
            root: `relative inline-flex select-none w-5 h-5 align-bottom`,
            input: `peer cursor-pointer disabled:cursor-default appearance-none
                absolute start-0 top-0 w-full h-full m-0 p-0 opacity-0 z-10
                border border-transparent rounded-xs`,
            box: `flex justify-center items-center rounded-sm w-5 h-5
                border border-surface-300 dark:border-surface-700
                bg-surface-0 dark:bg-surface-950
                text-surface-700 dark:text-surface-0
                peer-enabled:peer-hover:border-surface-400 dark:peer-enabled:peer-hover:border-surface-600
                p-checked:border-primary p-checked:bg-primary p-checked:text-primary-contrast
                peer-enabled:peer-hover:p-checked:bg-primary-emphasis peer-enabled:peer-hover:p-checked:border-primary-emphasis
                peer-focus-visible:outline-1 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-primary peer-focus-visible:outline
                p-disabled:bg-surface-200 dark:p-disabled:bg-surface-400 p-disabled:border-surface-300 dark:p-disabled:border-surface-700 p-disabled:text-surface-700 dark:p-disabled:text-surface-400
                shadow-[0_1px_2px_0_rgba(18,18,23,0.05)] transition-colors duration-200`,
            icon: `text-sm w-[0.875rem] h-[0.875rem] transition-none`,
        },
        pcRowRadiobutton: {
            root: `relative inline-flex select-none w-5 h-5`,
            input: `peer cursor-pointer disabled:cursor-default appearance-none absolute start-0 top-0 w-full h-full m-0 p-0 opacity-0 z-10
                border border-transparent rounded-full`,
            box: `flex justify-center items-center rounded-full
                border border-surface-300 dark:border-surface-700
                bg-surface-0 dark:bg-surface-950
                peer-enabled:peer-hover:border-surface-400 dark:peer-enabled:peer-hover:border-surface-600
                p-checked:border-primary p-checked:bg-primary
                peer-enabled:peer-hover:p-checked:bg-primary-emphasis peer-enabled:peer-hover:p-checked:border-primary-emphasis
                peer-focus-visible:outline-1 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-primary peer-focus-visible:outline
                p-filled:bg-surface-50 dark:p-filled:bg-surface-800
                p-invalid:border-red-400 dark:p-invalid:border-red-300
                p-disabled:bg-surface-200 dark:p-disabled:bg-surface-400 p-disabled:border-surface-300 dark:p-disabled:border-surface-700
                shadow-[0_1px_2px_0_rgba(18,18,23,0.05)] transition-colors duration-200
                w-5 h-5`,
            icon: `bg-transparent text-xs w-3 h-3 rounded-full
                transition-all duration-200 backface-hidden scale-[0.1]
                p-checked:bg-primary-contrast p-checked:visible p-checked:scale-100
                p-disabled:bg-surface-700 dark:p-disabled:bg-surface-400`,
        },
        pcRowCheckbox: {
            root: `relative inline-flex select-none w-5 h-5 align-bottom`,
            input: `peer cursor-pointer disabled:cursor-default appearance-none
                absolute start-0 top-0 w-full h-full m-0 p-0 opacity-0 z-10
                border border-transparent rounded-xs`,
            box: `flex justify-center items-center rounded-sm w-5 h-5
                border border-surface-300 dark:border-surface-700
                bg-surface-0 dark:bg-surface-950
                text-surface-700 dark:text-surface-0
                peer-enabled:peer-hover:border-surface-400 dark:peer-enabled:peer-hover:border-surface-600
                p-checked:border-primary p-checked:bg-primary p-checked:text-primary-contrast
                peer-enabled:peer-hover:p-checked:bg-primary-emphasis peer-enabled:peer-hover:p-checked:border-primary-emphasis
                peer-focus-visible:outline-1 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-primary peer-focus-visible:outline
                p-disabled:bg-surface-200 dark:p-disabled:bg-surface-400 p-disabled:border-surface-300 dark:p-disabled:border-surface-700 p-disabled:text-surface-700 dark:p-disabled:text-surface-400
                shadow-[0_1px_2px_0_rgba(18,18,23,0.05)] transition-colors duration-200`,
            icon: `text-sm w-[0.875rem] h-[0.875rem] transition-none`,
        },
        rowToggleButton: `inline-flex items-center justify-center overflow-hidden relative w-7 h-7 cursor-pointer select-none
            transition-colors duration-200 rounded-full border-none bg-transparent
            text-surface-500 enabled:hover:bg-surface-100 enabled:hover:text-surface-700
            dark:text-surface-400 dark:enabled:hover:bg-surface-800 dark:enabled:hover:text-surface-0
            focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-primary
            p-selected:hover:bg-surface-0 dark:p-selected:hover:bg-surface-900 p-selected:hover:text-primary`,
        rowToggleIcon: ``,
        reorderableRowHandle: ``,
    },
    loadingIcon: ``,
    pcPaginator: {
        paginatorContainer: `p-bottom:border-b border-surface-200 dark:border-surface-700`,
        root: `flex items-center justify-center flex-wrap p-2 rounded-md gap-1
            bg-surface-50 dark:bg-surface-900 text-surface-700 dark:text-surface-0`,
    },
    columnResizeIndicator: `w-px absolute z-10 hidden bg-primary`,
    rowReorderIndicatorUp: `absolute hidden`,
    rowReorderIndicatorDown: `absolute hidden`,
});

const el = ref();
defineExpose({
    exportCSV: () => el.value.exportCSV(),
});
</script>
