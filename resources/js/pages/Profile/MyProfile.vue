<script setup lang="ts">
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout.vue";
import UpdatePasswordForm from "@/components/profile/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "@/components/profile/UpdateProfileInformationForm.vue";
import DeleteUserForm from "@/components/profile/DeleteUserForm.vue";
import { Head } from "@inertiajs/vue3";
import HeaderCell from "@/components/commons/HeaderCell.vue";

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900 pb-20">
            <HeaderCell title="Perfil" />

            <div class="p-4">
                <div
                    class="bg-white p-4 mb-4 rounded-lg dark:bg-gray-800"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div
                    class="bg-white p-4 mb-4 rounded-lg dark:bg-gray-800"
                >
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div
                    class="bg-white p-4 mb-4 rounded-lg dark:bg-gray-800"
                >
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
