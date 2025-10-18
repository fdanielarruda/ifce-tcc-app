import { router } from "@inertiajs/vue3";
import { ref } from "vue";

export interface ProfileFormData extends Record<string, any> {
    name: string;
    email: string;
}

export interface PasswordFormData extends Record<string, any> {
    current_password: string;
    password: string;
    password_confirmation: string;
}

export interface DeleteAccountFormData extends Record<string, any> {
    password: string;
}

interface RequestOptions {
    onSuccess?: () => void;
    onError?: (errors: Record<string, string>) => void;
}

export const useProfileStore = () => {
    const isLoading = ref(false);
    const errors = ref<Record<string, string>>({});

    const updateProfile = async (
        data: ProfileFormData,
        options?: RequestOptions
    ): Promise<void> => {
        isLoading.value = true;
        errors.value = {};

        return new Promise<void>((resolve, reject) => {
            router.patch(
                route("profile.update"),
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

    const updatePassword = async (
        data: PasswordFormData,
        options?: RequestOptions
    ): Promise<void> => {
        isLoading.value = true;
        errors.value = {};

        return new Promise<void>((resolve, reject) => {
            router.put(
                route("password.update"),
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

    const deleteAccount = async (
        data: DeleteAccountFormData,
        options?: {
            onSuccess?: () => void;
            onError?: () => void;
            confirmMessage?: string;
        }
    ): Promise<void> => {
        const shouldDelete = confirm(
            options?.confirmMessage || "Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita."
        );

        if (!shouldDelete) {
            return Promise.reject(new Error("Cancelled"));
        }

        isLoading.value = true;

        return new Promise<void>((resolve, reject) => {
            router.delete(
                route("profile.destroy"),
                {
                    data,
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

    const resendVerificationEmail = async (
        options?: RequestOptions
    ): Promise<void> => {
        isLoading.value = true;
        errors.value = {};

        return new Promise<void>((resolve, reject) => {
            router.post(
                route("verification.send"),
                {},
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
        updateProfile,
        updatePassword,
        deleteAccount,
        resendVerificationEmail,
        clearErrors,
        reset,
    };
};