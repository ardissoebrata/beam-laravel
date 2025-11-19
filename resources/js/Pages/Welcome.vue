<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();
</script>

<template>
    <Head title="Welcome" />
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-gray-100 to-gray-50">
        <header class="fixed left-0 right-0 top-0 z-10">
            <nav class="mx-auto flex max-w-7xl items-center justify-end gap-2 px-6 py-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 transition hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500"
                >
                    Dashboard
                </Link>

                <template v-else-if="canLogin">
                    <Link
                        :href="route('login')"
                        class="rounded-md px-3 py-2 font-medium text-gray-700 transition hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500"
                    >
                        Log in
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="rounded-md px-3 py-2 font-medium text-gray-700 transition hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>

        <main class="flex min-h-screen flex-col items-center justify-center px-6">
            <div class="flex flex-col items-center gap-6 text-center">
                <ApplicationLogo class="h-32 w-32 fill-current text-gray-500" />
                <h1 class="text-4xl font-semibold text-gray-800 sm:text-5xl">
                    Welcome to Beam-Laravel
                </h1>
            </div>
        </main>
    </div>
</template>
