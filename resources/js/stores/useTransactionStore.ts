import { router } from "@inertiajs/vue3";
import { ref } from "vue";

export interface TransactionFormData extends Record<string, any> {
    description: string;
    amount: number;
    category_id: number | null;
    type: "receita" | "despesa";
    date: string;
    time: string;
}

export interface AiTransactionData extends Record<string, any> {
    description: string;
}

export interface TransactionFilters extends Record<string, any> {
    month?: number;
    year?: number;
}

interface RequestOptions {
    onSuccess?: () => void;
    onError?: (errors: Record<string, string>) => void;
}

export const useTransactionStore = () => {
    const isLoading = ref(false);
    const errors = ref<Record<string, string>>({});

    const createTransaction = async (
        data: TransactionFormData,
        options?: RequestOptions
    ): Promise<void> => {
        isLoading.value = true;
        errors.value = {};

        return new Promise<void>((resolve, reject) => {
            router.post(
                route("transactions.store"),
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

    const createAiTransaction = async (
        data: AiTransactionData,
        options?: RequestOptions
    ): Promise<void> => {
        isLoading.value = true;
        errors.value = {};

        return new Promise<void>((resolve, reject) => {
            router.post(
                route("transactions.store.ai"),
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

    const updateTransaction = async (
        id: number,
        data: TransactionFormData,
        options?: RequestOptions
    ): Promise<void> => {
        isLoading.value = true;
        errors.value = {};

        return new Promise<void>((resolve, reject) => {
            router.put(
                route("transactions.update", id),
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

    const deleteTransaction = async (
        id: number,
        options?: {
            onSuccess?: () => void;
            onError?: () => void;
            confirmMessage?: string;
        }
    ): Promise<void> => {
        const shouldDelete = confirm(
            options?.confirmMessage || "Tem certeza que deseja deletar esta transação?"
        );

        if (!shouldDelete) {
            return Promise.reject(new Error("Cancelled"));
        }

        isLoading.value = true;

        return new Promise<void>((resolve, reject) => {
            router.delete(
                route("transactions.destroy", id),
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

    const navigateToMonth = (filters: TransactionFilters): void => {
        router.get(
            route("transactions.index"),
            filters,
            {
                preserveState: true,
                preserveScroll: true,
            }
        );
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
        createTransaction,
        createAiTransaction,
        updateTransaction,
        deleteTransaction,
        navigateToMonth,
        clearErrors,
        reset,
    };
};