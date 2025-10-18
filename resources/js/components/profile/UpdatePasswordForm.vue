<script setup lang="ts">
import InputError from "@/components/InputError.vue";
import InputLabel from "@/components/InputLabel.vue";
import PrimaryButton from "@/components/PrimaryButton.vue";
import TextInput from "@/components/TextInput.vue";
import { useProfileStore } from "@/stores/useProfileStore";
import { ref } from "vue";

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const { updatePassword, isLoading, errors, clearErrors } = useProfileStore();

const form = ref({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const recentlySuccessful = ref(false);

const resetForm = () => {
    form.value = {
        current_password: "",
        password: "",
        password_confirmation: "",
    };
    clearErrors();
};

const submit = async () => {
    recentlySuccessful.value = false;

    await updatePassword(form.value, {
        onSuccess: () => {
            resetForm();
            recentlySuccessful.value = true;
            setTimeout(() => {
                recentlySuccessful.value = false;
            }, 2000);
        },
        onError: (errorResponse) => {
            if (errorResponse.password) {
                form.value.password = "";
                form.value.password_confirmation = "";
                passwordInput.value?.focus();
            }
            if (errorResponse.current_password) {
                form.value.current_password = "";
                currentPasswordInput.value?.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Atualizar Senha
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Garanta que sua conta esteja usando uma senha longa e aleatória para se
                manter segura.
            </p>
        </header>

        <form @submit.prevent="submit" class="mt-4 space-y-4">
            <div>
                <InputLabel for="current_password" value="Senha Atual" />

                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="current-password"
                />

                <InputError :message="errors.current_password" class="mt-2" />
            </div>

            <div>
                <InputLabel for="password" value="Nova Senha" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />

                <InputError :message="errors.password" class="mt-2" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirmar Senha" />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />

                <InputError :message="errors.password_confirmation" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="isLoading">Salvar</PrimaryButton>
            </div>
        </form>
    </section>
</template>
