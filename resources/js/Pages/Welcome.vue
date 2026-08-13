<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { useTranslations } from '@/composables/useTranslations';
import { register } from '@/lib/authRoutes';
import { dashboard, login } from '@/routes';

const { t } = useTranslations();
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
</script>

<template>
    <Head :title="t('pages.welcome')">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="flex min-h-screen flex-col items-center bg-[linear-gradient(to_bottom_right,var(--background),var(--primary))] p-5 text-foreground lg:p-8"
    >
        <header
            class="mb-6 w-full max-w-6xl text-sm not-has-[nav]:hidden"
        >
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboard()"
                    class="inline-block rounded-md border border-[#c8ced5] bg-white px-4 py-2 text-sm font-medium leading-normal text-[#18212b] shadow-sm transition hover:border-[#8d98a5] hover:shadow dark:border-[#3b4652] dark:bg-[#171d23] dark:text-[#f4f6f8] dark:hover:border-[#687582]"
                >
                    {{ t('navigation.dashboard') }}
                </Link>
                <template v-else>
                    <Link
                        :href="login()"
                        class="inline-block rounded-md border border-transparent px-4 py-2 text-sm font-medium leading-normal text-[#4b5968] transition hover:border-[#c8ced5] hover:bg-white hover:text-[#18212b] dark:text-[#c4ccd4] dark:hover:border-[#3b4652] dark:hover:bg-[#171d23] dark:hover:text-[#f4f6f8]"
                    >
                        {{ t('auth.login') }}
                    </Link>
                    <Link
                        v-if="$page.props.auth.features.registration"
                        :href="register()"
                        class="inline-block rounded-md border border-[#c8ced5] bg-white px-4 py-2 text-sm font-medium leading-normal text-[#18212b] shadow-sm transition hover:border-[#8d98a5] hover:shadow dark:border-[#3b4652] dark:bg-[#171d23] dark:text-[#f4f6f8] dark:hover:border-[#687582]"
                    >
                        {{ t('auth.register') }}
                    </Link>
                </template>
            </nav>
        </header>
        <div class="flex w-full flex-1 items-center justify-center">
            <h1
                class="bg-linear-to-r from-primary to-foreground bg-clip-text text-center text-4xl font-semibold tracking-tight text-transparent sm:text-5xl"
            >
                {{ appName }}
            </h1>
        </div>

        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
