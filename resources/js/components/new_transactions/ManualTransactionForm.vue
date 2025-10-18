<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import TransactionFormFields from "./TransactionFormFields.vue";

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

const form = useForm({
    description: "",
    amount: "",
    category_id: null,
    type: "despesa" as "receita" | "despesa",
    date: new Date().toISOString().split("T")[0],
    time: new Date().toTimeString().slice(0, 5),
});

const submit = () => {
    const numericAmount = parseFloat(form.amount.replace(",", "."));
    
    form.transform((data) => ({
        ...data,
        amount: numericAmount,
    })).post(route("transactions.store"), {
        onSuccess: () => {
            form.reset();
            emit("success");
        },
    });
};
</script>

<template>
    <div class="space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
            <form @submit.prevent="submit" class="space-y-4">
                <TransactionFormFields
                    v-model:description="form.description"
                    v-model:amount="form.amount"
                    v-model:category_id="form.category_id"
                    v-model:type="form.type"
                    v-model:date="form.date"
                    v-model:time="form.time"
                    :categories="categories"
                    :errors="form.errors"
                    :show-type-selector="true"
                />

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 dark:disabled:bg-gray-600 text-white font-semibold py-4 rounded-xl transition-colors disabled:cursor-not-allowed"
                >
                    <span v-if="form.processing">Salvando...</span>
                    <span v-else>Salvar Transação</span>
                </button>
            </form>
        </div>
    </div>
</template>