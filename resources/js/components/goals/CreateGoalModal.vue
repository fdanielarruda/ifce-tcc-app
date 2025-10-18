<script setup lang="ts">
import { ref, watch } from "vue";
import { XMarkIcon } from "@heroicons/vue/24/outline";
import { useGoalStore } from "@/stores/useGoalStore";

interface Props {
    show: boolean;
    categories: Array<{
        id: number;
        title: string;
        icon?: string;
    }>;
}

const props = defineProps<Props>();
const emit = defineEmits<{
    close: [];
}>();

const goalStore = useGoalStore();

const form = ref({
    category_id: null as number | null,
    max_per_month: "",
    message: "",
});

watch(
    () => props.show,
    (newValue) => {
        if (newValue) {
            resetForm();
        }
    }
);

const resetForm = () => {
    form.value = {
        category_id: null,
        max_per_month: "",
        message: "",
    };
    goalStore.clearErrors();
};

const closeModal = () => {
    emit("close");
};

const saveGoal = async () => {
    const numericAmount = parseFloat(form.value.max_per_month.replace(",", "."));

    try {
        await goalStore.createGoal(
            {
                category_id: form.value.category_id,
                max_per_month: numericAmount,
                message: form.value.message || undefined,
            },
            {
                onSuccess: () => {
                    closeModal();
                },
            }
        );
    } catch (error) {
        console.error("Erro ao criar meta:", error);
    }
};

const formatAmount = (event: Event) => {
    const input = event.target as HTMLInputElement;
    let value = input.value.replace(/[^\d,]/g, "");

    const parts = value.split(",");
    if (parts.length > 2) {
        value = parts[0] + "," + parts.slice(1).join("");
    }

    if (parts[1] && parts[1].length > 2) {
        value = parts[0] + "," + parts[1].slice(0, 2);
    }

    form.value.max_per_month = value;
};
</script>

<template>
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
                v-if="show"
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
                        v-if="show"
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md p-6"
                    >
                        <div class="flex items-center justify-between mb-6">
                            <h2
                                class="text-xl font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Nova Meta de Gastos
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

                        <form @submit.prevent="saveGoal" class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    Categoria
                                </label>
                                <select
                                    v-model="form.category_id"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{
                                        'border-red-500':
                                            goalStore.errors.value.category_id,
                                    }"
                                >
                                    <option :value="null" disabled>
                                        Selecione uma categoria
                                    </option>
                                    <option
                                        v-for="category in categories"
                                        :key="category.id"
                                        :value="category.id"
                                    >
                                        {{ category.title }}
                                    </option>
                                </select>
                                <p
                                    v-if="goalStore.errors.value.category_id"
                                    class="mt-1 text-sm text-red-600 dark:text-red-400"
                                >
                                    {{ goalStore.errors.value.category_id }}
                                </p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    Valor Máximo Mensal
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400"
                                    >
                                        R$
                                    </span>
                                    <input
                                        v-model="form.max_per_month"
                                        @input="formatAmount"
                                        type="text"
                                        placeholder="0,00"
                                        class="w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{
                                            'border-red-500':
                                                goalStore.errors.value.max_per_month,
                                        }"
                                    />
                                </div>
                                <p
                                    v-if="goalStore.errors.value.max_per_month"
                                    class="mt-1 text-sm text-red-600 dark:text-red-400"
                                >
                                    {{ goalStore.errors.value.max_per_month }}
                                </p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    Mensagem (opcional)
                                </label>
                                <textarea
                                    v-model="form.message"
                                    placeholder="Ex: Reduzir gastos com delivery"
                                    rows="3"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                    :class="{
                                        'border-red-500': goalStore.errors.value.message,
                                    }"
                                ></textarea>
                                <p
                                    v-if="goalStore.errors.value.message"
                                    class="mt-1 text-sm text-red-600 dark:text-red-400"
                                >
                                    {{ goalStore.errors.value.message }}
                                </p>
                            </div>

                            <div class="flex gap-3 justify-end mt-6">
                                <button
                                    type="button"
                                    @click="closeModal"
                                    :disabled="goalStore.isLoading.value"
                                    class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors disabled:opacity-50"
                                >
                                    Cancelar
                                </button>
                                <button
                                    type="submit"
                                    :disabled="goalStore.isLoading.value"
                                    class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors shadow-sm disabled:opacity-50"
                                >
                                    <span v-if="goalStore.isLoading.value"
                                        >Salvando...</span
                                    >
                                    <span v-else>Criar Meta</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
