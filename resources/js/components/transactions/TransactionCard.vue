<script setup lang="ts">
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import {
    ShoppingCartIcon,
    BanknotesIcon,
    TruckIcon,
    PencilIcon,
    TrashIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import TransactionFormFields from "../new_transactions/TransactionFormFields.vue";

interface Props {
    transaction: {
        id: number;
        description: string;
        category: string;
        category_id?: number;
        time: string;
        date?: string;
        amount: number;
        type: "receita" | "despesa";
        icon: string;
    };
    categories?: Array<{
        id: number;
        title: string;
    }>;
}

const props = defineProps<Props>();

const showModal = ref(false);
const showActions = ref(false);

const editForm = ref({
    description: props.transaction.description,
    amount: Math.abs(props.transaction.amount).toFixed(2).replace(".", ","),
    category_id: props.transaction.category_id || "",
    type: props.transaction.type,
    date: props.transaction.date || new Date().toISOString().split("T")[0],
    time: props.transaction.time,
});

const formatCurrency = (amount: number) => {
    const prefix = amount >= 0 ? "+" : "-";
    const absAmount = Math.abs(amount);
    return `${prefix}R$ ${absAmount.toFixed(2).replace(".", ",")}`;
};

const getIconComponent = (iconType: string) => {
    const icons: Record<string, any> = {
        shopping: ShoppingCartIcon,
        salary: BanknotesIcon,
        fuel: TruckIcon,
    };
    return icons[iconType] || ShoppingCartIcon;
};

const openModal = () => {
    editForm.value = {
        description: props.transaction.description,
        amount: Math.abs(props.transaction.amount).toFixed(2).replace(".", ","),
        category_id: props.transaction.category_id || "",
        type: props.transaction.type,
        date: props.transaction.date || new Date().toISOString().split("T")[0],
        time: props.transaction.time,
    };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const saveEdit = () => {
    const numericAmount = parseFloat(editForm.value.amount.replace(",", "."));

    const payload: any = {
        description: editForm.value.description,
        amount: numericAmount,
        type: editForm.value.type,
        date: editForm.value.date,
        time: editForm.value.time,
    };

    if (editForm.value.category_id) {
        payload.category_id = parseInt(editForm.value.category_id.toString());
    }

    router.put(route("transactions.update", props.transaction.id), payload, {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
        },
    });
};

const deleteTransaction = () => {
    if (confirm("Tem certeza que deseja deletar esta transação?")) {
        router.delete(route("transactions.destroy", props.transaction.id), {
            preserveScroll: true,
        });
    }
};

const toggleActions = () => {
    showActions.value = !showActions.value;
};
</script>

<template>
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 cursor-pointer transition-shadow hover:shadow-md"
        @click="toggleActions"
    >
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0 flex-1">
                <div
                    class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0"
                >
                    <component
                        :is="getIconComponent(transaction.icon)"
                        class="w-5 h-5 text-gray-600 dark:text-gray-300"
                    />
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-medium text-gray-800 dark:text-gray-200">
                        {{ transaction.description }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ transaction.category }} • {{ transaction.time }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span
                    :class="[
                        'font-semibold text-base whitespace-nowrap',
                        transaction.type === 'receita'
                            ? 'text-green-600 dark:text-green-400'
                            : 'text-red-600 dark:text-red-400',
                    ]"
                >
                    {{ formatCurrency(transaction.amount) }}
                </span>

                <Transition
                    enter-active-class="transition-all duration-200"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition-all duration-150"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div v-if="showActions" class="flex gap-1 ml-2" @click.stop>
                        <button
                            @click="openModal"
                            class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            title="Editar"
                        >
                            <PencilIcon
                                class="w-4 h-4 text-gray-600 dark:text-gray-400"
                            />
                        </button>
                        <button
                            @click="deleteTransaction"
                            class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                            title="Deletar"
                        >
                            <TrashIcon class="w-4 h-4 text-red-600 dark:text-red-400" />
                        </button>
                    </div>
                </Transition>
            </div>
        </div>

        <Teleport to="body">
            <Transition
                enter-active-class="transition-opacity duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="showModal"
                    class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
                    @click.self="closeModal"
                >
                    <Transition
                        enter-active-class="transition-all duration-200"
                        enter-from-class="opacity-0 scale-95"
                        enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition-all duration-200"
                        leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95"
                    >
                        <div
                            v-if="showModal"
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md p-6"
                        >
                            <div class="flex items-center justify-between mb-6">
                                <h2
                                    class="text-xl font-semibold text-gray-900 dark:text-gray-100"
                                >
                                    Editar Transação
                                </h2>
                                <button
                                    @click="closeModal"
                                    class="p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                >
                                    <XMarkIcon
                                        class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                    />
                                </button>
                            </div>

                            <TransactionFormFields
                                v-model:description="editForm.description"
                                v-model:amount="editForm.amount"
                                v-model:category-id="editForm.category_id"
                                v-model:type="editForm.type"
                                v-model:date="editForm.date"
                                v-model:time="editForm.time"
                                :categories="categories || []"
                                :errors="{}"
                                :show-type-selector="false"
                            />

                            <div class="flex gap-3 justify-end mt-6">
                                <button
                                    @click="closeModal"
                                    class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                >
                                    Cancelar
                                </button>
                                <button
                                    @click="saveEdit"
                                    class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors shadow-sm"
                                >
                                    Salvar
                                </button>
                            </div>
                        </div>
                    </Transition>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>
