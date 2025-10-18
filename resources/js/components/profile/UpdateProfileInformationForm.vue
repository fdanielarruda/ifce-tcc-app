<script setup lang="ts">
import InputError from "@/components/InputError.vue";
import InputLabel from "@/components/InputLabel.vue";
import PrimaryButton from "@/components/PrimaryButton.vue";
import TextInput from "@/components/TextInput.vue";
import { usePage } from "@inertiajs/vue3";
import { useProfileStore } from "@/stores/useProfileStore";
import { ref } from "vue";

defineProps<{
    mustVerifyEmail?: Boolean;
    status?: String;
}>();

const user = usePage().props.auth.user;
const { updateProfile, resendVerificationEmail, isLoading, errors } = useProfileStore();

const form = ref({
    name: user.name,
    email: user.email,
});

const recentlySuccessful = ref(false);

const submit = async () => {
    recentlySuccessful.value = false;

    await updateProfile(form.value, {
        onSuccess: () => {
            recentlySuccessful.value = true;
            setTimeout(() => {
                recentlySuccessful.value = false;
            }, 2000);
        },
    });
};

const sendVerification = async () => {
    await resendVerificationEmail();
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Informações do Perfil
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Atualize as informações de perfil e o endereço de e-mail da sua conta.
            </p>
        </header>

        <form @submit.prevent="submit" class="mt-4 space-y-4">
            <div>
                <InputLabel for="name" value="Nome" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                    Seu endereço de e-mail não foi verificado.
                    <button
                        type="button"
                        @click="sendVerification"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                    >
                        Clique aqui para reenviar o e-mail de verificação.
                    </button>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
                >
                    Um novo link de verificação foi enviado para o seu endereço de e-mail.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="isLoading">Salvar</PrimaryButton>
            </div>
        </form>
    </section>
</template>
