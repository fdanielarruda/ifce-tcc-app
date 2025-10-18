<script setup lang="ts">
import { ref } from "vue";
import {
    ShoppingCartIcon,
    BanknotesIcon,
    TruckIcon,
    TrashIcon,
    BookOpenIcon,
    BriefcaseIcon,
    ChartBarIcon,
    FaceSmileIcon,
    HeartIcon,
    HomeIcon,
    QuestionMarkCircleIcon,
} from "@heroicons/vue/24/outline";
import { useGoalStore } from "@/stores/useGoalStore";

interface Props {
    goal: {
        id: number;
        category_id: number;
        category: {
            id: number;
            title: string;
            icon?: string;
        };
        max_per_month: number;
        message?: string;
    };
    categories?: Array<{
        id: number;
        title: string;
    }>;
}

const props = defineProps<Props>();

const goalStore = useGoalStore();
const showActions = ref(false);

const formatCurrency = (amount: number) => {
    return `R$ ${amount.toFixed(2).replace(".", ",")}`;
};

const getIconComponent = (iconType?: string) => {
    const icons: Record<string, any> = {
        "shopping-cart": ShoppingCartIcon,
        banknotes: BanknotesIcon,
        briefcase: BriefcaseIcon,
        "chart-bar": ChartBarIcon,
        home: HomeIcon,
        car: TruckIcon,
        "face-smile": FaceSmileIcon,
        heart: HeartIcon,
        "book-open": BookOpenIcon,
        "question-mark-circle": QuestionMarkCircleIcon,
    };

    return icons[iconType || "question-mark-circle"] || QuestionMarkCircleIcon;
};

const deleteGoal = async () => {
    try {
        await goalStore.deleteGoal(props.goal.id, {
            confirmMessage: "Tem certeza que deseja deletar esta meta?",
        });
    } catch (error) {
        if (error instanceof Error && error.message !== "Cancelled") {
            console.error("Erro ao deletar meta:", error);
        }
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
                    class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0"
                >
                    <component
                        :is="getIconComponent(goal.category.icon)"
                        class="w-6 h-6 text-blue-600 dark:text-blue-400"
                    />
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-gray-800 dark:text-gray-200">
                        {{ goal.category.title }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Limite mensal: {{ formatCurrency(Number(goal.max_per_month)) }}
                    </p>
                    <p
                        v-if="goal.message"
                        class="text-xs text-gray-400 dark:text-gray-500 mt-1"
                    >
                        {{ goal.message }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <Transition
                    enter-active-class="transition-all duration-200"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition-all duration-150"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div v-if="showActions" class="flex gap-1" @click.stop>
                        <button
                            @click="deleteGoal"
                            class="p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                            title="Deletar"
                            :disabled="goalStore.isLoading.value"
                        >
                            <TrashIcon class="w-5 h-5 text-red-600 dark:text-red-400" />
                        </button>
                    </div>
                </Transition>
            </div>
        </div>
    </div>
</template>
