import { ref, reactive } from 'vue';

const state = reactive({
    isDarkMode: ref(false),
});

export function useDarkStore() {
    return state;
}