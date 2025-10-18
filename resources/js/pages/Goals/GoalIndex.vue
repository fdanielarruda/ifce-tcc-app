<script setup lang="ts">
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import GoalCard from "@/components/goals/GoalCard.vue";
import CreateGoalModal from "@/components/goals/CreateGoalModal.vue";
import { PlusIcon } from "@heroicons/vue/24/outline";
import HeaderCell from "@/components/commons/HeaderCell.vue";

interface Goal {
    id: number;
    category_id: number;
    category: {
        id: number;
        title: string;
        icon?: string;
    };
    max_per_month: number;
    message?: string;
    current_expense: number;
    percentage: number;
    is_over_limit: boolean;
}

interface Category {
    id: number;
    title: string;
    icon?: string;
}

interface Props {
    goals: Goal[];
    categories: Category[];
    currentMonth: string;
}

const props = defineProps<Props>();

const showCreateModal = ref(false);

const openCreateModal = () => {
    showCreateModal.value = true;
};

const closeCreateModal = () => {
    showCreateModal.value = false;
};
</script>

<template>
    <Head title="Metas" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
            <HeaderCell title="Metas" />

            <div class="p-4 space-y-4">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ currentMonth }}
                    </p>
                    <button
                        @click="openCreateModal"
                        class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm"
                    >
                        <PlusIcon class="w-5 h-5" />
                        Nova Meta
                    </button>
                </div>

                <div v-if="goals.length > 0" class="space-y-4">
                    <GoalCard
                        v-for="goal in goals"
                        :key="goal.id"
                        :goal="goal"
                        :categories="categories"
                    />
                </div>

                <div
                    v-else
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-12 text-center"
                >
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                        Você ainda não criou nenhuma meta de gastos
                    </p>
                    <button
                        @click="openCreateModal"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm"
                    >
                        <PlusIcon class="w-5 h-5" />
                        Primeira Meta
                    </button>
                </div>
            </div>

            <CreateGoalModal
                :show="showCreateModal"
                :categories="categories"
                @close="closeCreateModal"
            />
        </div>
    </AuthenticatedLayout>
</template>
