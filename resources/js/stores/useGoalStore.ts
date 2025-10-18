import { router } from "@inertiajs/vue3";
import { ref } from "vue";

export interface GoalFormData extends Record<string, any> {
    category_id: number | null;
    max_per_month: number;
    message?: string;
}

interface RequestOptions {
    onSuccess?: () => void;
    onError?: (errors: Record<string, string>) => void;
}

export const useGoalStore = () => {
    const isLoading = ref(false);
    const errors = ref<Record<string, string>>({});

    const createGoal = async (
        data: GoalFormData,
        options?: RequestOptions
    ): Promise<void> => {
        isLoading.value = true;
        errors.value = {};

        return new Promise<void>((resolve, reject) => {
            router.post(
                route("goals.store"),
                data,
                {
                    onSuccess: () => {
                        options?.onSuccess?.();
                        resolve();
                    },
                    onError: (errorResponse) => {
                        errors.value = errorResponse as Record<string, string>;
                        options?.onError?.(errors.value);
                        reject(errorResponse);
                    },
                    onFinish: () => {
                        isLoading.value = false;
                    },
                    preserveScroll: true,
                }
            );
        });
    };

    const deleteGoal = async (
        id: number,
        options?: {
            onSuccess?: () => void;
            onError?: () => void;
            confirmMessage?: string;
        }
    ): Promise<void> => {
        const shouldDelete = confirm(
            options?.confirmMessage || "Tem certeza que deseja deletar esta meta?"
        );

        if (!shouldDelete) {
            return Promise.reject(new Error("Cancelled"));
        }

        isLoading.value = true;

        return new Promise<void>((resolve, reject) => {
            router.delete(
                route("goals.destroy", id),
                {
                    onSuccess: () => {
                        options?.onSuccess?.();
                        resolve();
                    },
                    onError: () => {
                        options?.onError?.();
                        reject();
                    },
                    onFinish: () => {
                        isLoading.value = false;
                    },
                    preserveScroll: true,
                }
            );
        });
    };

    const clearErrors = (): void => {
        errors.value = {};
    };

    const reset = (): void => {
        isLoading.value = false;
        errors.value = {};
    };

    return {
        isLoading,
        errors,
        createGoal,
        deleteGoal,
        clearErrors,
        reset,
    };
};