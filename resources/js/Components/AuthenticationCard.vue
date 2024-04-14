<script setup>
import { onMounted, provide } from 'vue';
import { useDarkStore } from '@/darkStore';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import DarkSwitcher from '@/Components/DarkSwitcher.vue';

const darkStore = useDarkStore();
provide('darkStore', darkStore);

onMounted(() => {
    if (!localStorage.getItem('darkMode')) {
        darkStore.isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
    } else {
        darkStore.isDarkMode = localStorage.getItem('darkMode') === 'true';
    }
    document.documentElement.classList.toggle('dark', darkStore.isDarkMode);
});
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div class="absolute top-0 right-0 p-4">
            <LanguageSwitcher />
            <DarkSwitcher />
        </div>
        <div>
            <slot name="logo" />
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>
