import { router } from "@inertiajs/vue3";
import { ref } from "vue";

export interface ReportFilters extends Record<string, any> {
    start_date: string;
    end_date: string;
}

interface RequestOptions {
    onSuccess?: () => void;
    onError?: (errors: Record<string, string>) => void;
}

export const useReportStore = () => {
    const isLoading = ref(false);
    const errors = ref<Record<string, string>>({});

    const applyFilters = async (
        filters: ReportFilters,
        options?: RequestOptions
    ): Promise<void> => {
        isLoading.value = true;
        errors.value = {};

        return new Promise<void>((resolve, reject) => {
            router.get(
                route("reports.index"),
                filters,
                {
                    preserveState: true,
                    preserveScroll: true,
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
        applyFilters,
        clearErrors,
        reset,
    };
};