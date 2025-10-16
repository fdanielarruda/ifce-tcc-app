<script setup lang="ts">
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import FeatureCard from '@/components/dashboard/FeatureCard.vue';
import TextButton from '@/components/itens/TextButton.vue';
import {
    ClipboardDocumentListIcon,
    UsersIcon,
    MegaphoneIcon,
    NewspaperIcon
} from '@heroicons/vue/24/solid';

const authUser = usePage().props.auth.user;
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="p-2">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">
                Bem-vindo(a)!
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Este é o seu painel de controle
            </p>
        </div>
    </AuthenticatedLayout>
</template>