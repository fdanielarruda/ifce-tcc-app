<script setup lang="ts">
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import ModeSelector from "@/components/new_transactions/ModeSelector.vue";
import AiTransactionForm from "@/components/new_transactions/AiTransactionForm.vue";
import ManualTransactionForm from "@/components/new_transactions/ManualTransactionForm.vue";
import HeaderCell from "@/components/commons/HeaderCell.vue";

interface Props {
    categories: Array<{
        id: number;
        title: string;
    }>;
}

const props = defineProps<Props>();

const mode = ref<"ai" | "manual">("ai");

const handleAiSuccess = () => {
    // router.visit(route("transactions.index"));
};

const handleManualSuccess = () => {
    // router.visit(route("transactions.index"));
};
</script>

<template>
    <Head title="Nova Transação" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900 pb-20">
            <HeaderCell title="Nova Transação" />

            <div class="p-4">
                <ModeSelector v-model="mode" class="mb-6" />

                <AiTransactionForm v-if="mode === 'ai'" @success="handleAiSuccess" />

                <ManualTransactionForm
                    v-if="mode === 'manual'"
                    :categories="categories"
                    @success="handleManualSuccess"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
