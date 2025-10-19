<script setup lang="ts">
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import { ArrowDownTrayIcon } from "@heroicons/vue/24/outline";

interface Props {
    initialStartDate: string;
    initialEndDate: string;
    isLoading?: boolean;
}

interface Emits {
    (e: "apply", filters: { start_date: string; end_date: string }): void;
    (e: "export", filters: { start_date: string; end_date: string }): void;
}

const props = withDefaults(defineProps<Props>(), {
    isLoading: false,
});

const emit = defineEmits<Emits>();

const startDate = ref(props.initialStartDate);
const endDate = ref(props.initialEndDate);
const isExporting = ref(false);

const handleApply = () => {
    emit("apply", {
        start_date: startDate.value,
        end_date: endDate.value,
    });
};

const handleExport = () => {
    emit("export", {
        start_date: startDate.value,
        end_date: endDate.value,
    });
};
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filtros</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                >
                    Data Início
                </label>
                <input
                    v-model="startDate"
                    type="date"
                    :disabled="isLoading || isExporting"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                />
            </div>
            <div>
                <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                >
                    Data Fim
                </label>
                <input
                    v-model="endDate"
                    type="date"
                    :disabled="isLoading || isExporting"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                />
            </div>
            <div class="flex items-end">
                <button
                    @click="handleApply"
                    :disabled="isLoading || isExporting"
                    class="w-full px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ isLoading ? "Aplicando..." : "Aplicar Filtros" }}
                </button>
            </div>
            <div class="flex items-end">
                <button
                    @click="handleExport"
                    :disabled="isLoading || isExporting"
                    class="w-full px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                >
                    <ArrowDownTrayIcon class="w-5 h-5" />
                    {{ isExporting ? "Exportando..." : "Exportar CSV" }}
                </button>
            </div>
        </div>
    </div>
</template>
