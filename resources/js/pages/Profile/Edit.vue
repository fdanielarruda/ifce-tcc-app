<script setup lang="ts">
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import UpdatePasswordForm from '@/components/profile/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/components/profile/UpdateProfileInformationForm.vue';
import DeleteUserForm from '@/components/profile/DeleteUserForm.vue'; 
import { Head } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>

    <Head title="Profile" />

    <AuthenticatedLayout>
        <div class="mx-auto space-y-4">
            <div class="bg-white lg:p-4 p-2 border sm:rounded-lg dark:bg-gray-800 dark:border-gray-700">
                <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" class="max-w-xl" />
            </div>

            <div class="bg-white lg:p-4 p-2 border sm:rounded-lg dark:bg-gray-800 dark:border-gray-700">
                <UpdatePasswordForm class="max-w-xl" />
            </div>

            <div class="bg-white lg:p-4 p-2 border sm:rounded-lg dark:bg-gray-800 dark:border-gray-700">
                <DeleteUserForm class="max-w-xl" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>