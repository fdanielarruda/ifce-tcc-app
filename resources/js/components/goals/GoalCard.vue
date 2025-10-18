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
    CheckCircleIcon,
    ExclamationCircleIcon,
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
        current_expense: number;
        percentage: number;
        is_over_limit: boolean;
    };
    categories?: Array<{
        id: number;
        title: string;
    }>;
}

const props = defineProps<Props>();

const goalStore = useGoalStore();
const showActions = ref(false);

const formatCurrency = (amount: string | number) => {
    return `R$ ${Number(amount).toFixed(2).replace(".", ",")}`;
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

const getStatusColor = () => {
    if (props.goal.is_over_limit) {
        return {
            bg: "bg-red-100 dark:bg-red-900/30",
            icon: "text-red-600 dark:text-red-400",
            border: "border-red-200 dark:border-red-800",
        };
    }
    return {
        bg: "bg-green-100 dark:bg-green-900/30",
        icon: "text-green-600 dark:text-green-400",
        border: "border-green-200 dark:border-green-800",
    };
};

const statusColors = getStatusColor();
</script>

<template>
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 cursor-pointer transition-shadow hover:shadow-md border-2"
        :class="statusColors.border"
        @click="toggleActions"
    >
        <div class="flex items-start justify-between gap-3 mb-3">
            <div class="flex items-center gap-3 min-w-0 flex-1">
                <div
                    class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0"
                    :class="statusColors.bg"
                >
                    <component
                        :is="getIconComponent(goal.category.icon)"
                        class="w-6 h-6"
                        :class="statusColors.icon"
                    />
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <p class="font-semibold text-gray-800 dark:text-gray-200">
                            {{ goal.category.title }}
                        </p>
                        <component
                            :is="
                                goal.is_over_limit
                                    ? ExclamationCircleIcon
                                    : CheckCircleIcon
                            "
                            class="w-5 h-5"
                            :class="statusColors.icon"
                        />
                    </div>
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

        <!-- Barra de progresso -->
        <div class="space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400">
                    Gasto: {{ formatCurrency(goal.current_expense) }}
                </span>
                <span class="text-gray-600 dark:text-gray-400">
                    Meta: {{ formatCurrency(goal.max_per_month) }}
                </span>
            </div>

            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                <div
                    class="h-2.5 rounded-full transition-all duration-300"
                    :class="goal.is_over_limit ? 'bg-red-600' : 'bg-green-600'"
                    :style="{ width: `${Math.min(goal.percentage, 100)}%` }"
                ></div>
            </div>

            <div class="flex justify-between text-xs">
                <span
                    class="font-medium"
                    :class="
                        goal.is_over_limit
                            ? 'text-red-600 dark:text-red-400'
                            : 'text-green-600 dark:text-green-400'
                    "
                >
                    {{ goal.percentage.toFixed(1) }}% da meta
                </span>
                <span
                    v-if="goal.is_over_limit"
                    class="text-red-600 dark:text-red-400 font-medium"
                >
                    Excedeu em
                    {{ formatCurrency(goal.current_expense - goal.max_per_month) }}
                </span>
                <span v-else class="text-green-600 dark:text-green-400 font-medium">
                    Restam {{ formatCurrency(goal.max_per_month - goal.current_expense) }}
                </span>
            </div>
        </div>
    </div>
</template>
