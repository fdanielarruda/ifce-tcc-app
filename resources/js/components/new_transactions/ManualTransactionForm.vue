<script setup lang="ts">
import { ref } from "vue";
import TransactionFormFields from "./TransactionFormFields.vue";
import { useTransactionStore } from "@/stores/useTransactionStore";

interface Props {
    categories: Array<{
        id: number;
        title: string;
    }>;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    success: [];
}>();

const transactionStore = useTransactionStore();

const formData = ref({
    description: "",
    amount: "",
    category_id: null as number | null,
    type: "despesa" as "receita" | "despesa",
    date: new Date().toISOString().split("T")[0],
    time: new Date().toTimeString().slice(0, 5),
});

const submit = async () => {
    const numericAmount = parseFloat(formData.value.amount.replace(",", "."));

    try {
        await transactionStore.createTransaction(
            {
                ...formData.value,
                amount: numericAmount,
            },
            {
                onSuccess: () => {
                    // Resetar form
                    formData.value = {
                        description: "",
                        amount: "",
                        category_id: null,
                        type: "despesa",
                        date: new Date().toISOString().split("T")[0],
                        time: new Date().toTimeString().slice(0, 5),
                    };
                    emit("success");
                },
            }
        );
    } catch (error) {
        console.error("Erro ao criar transação:", error);
    }
};
</script>

<template>
    <div class="space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
            <form @submit.prevent="submit" class="space-y-4">
                <TransactionFormFields
                    v-model:description="formData.description"
                    v-model:amount="formData.amount"
                    v-model:category_id="formData.category_id"
                    v-model:type="formData.type"
                    v-model:date="formData.date"
                    v-model:time="formData.time"
                    :categories="categories"
                    :errors="transactionStore.errors.value"
                    :show-type-selector="true"
                />

                <button
                    type="submit"
                    :disabled="transactionStore.isLoading.value"
                    class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 dark:disabled:bg-gray-600 text-white font-semibold py-4 rounded-xl transition-colors disabled:cursor-not-allowed"
                >
                    <span v-if="transactionStore.isLoading.value">Salvando...</span>
                    <span v-else>Salvar Transação</span>
                </button>
            </form>
        </div>
    </div>
</template>
