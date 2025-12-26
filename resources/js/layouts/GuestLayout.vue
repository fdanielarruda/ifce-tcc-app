<script setup lang="ts">
import Alert from '@/components/Alert.vue';
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0 dark:bg-gray-900 p-4">
        <div>
            <Link href="/"
                class="text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-950 bg-clip-text text-transparent hover:from-gray-700 hover:to-gray-900 transition-all duration-300 tracking-tight dark:from-gray-200 dark:to-gray-400 dark:hover:from-gray-100 dark:hover:to-gray-300">
                Numera
            </Link>
        </div>

        <div class="mt-6 w-full overflow-hidden bg-white px-4 py-4 shadow-md sm:max-w-md rounded-lg dark:bg-gray-800">
            <slot />
        </div>
    </div>

    <Alert :flash-message="page.props.flash ?? null" />
</template>
