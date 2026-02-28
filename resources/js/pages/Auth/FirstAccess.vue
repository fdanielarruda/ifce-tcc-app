<script setup lang="ts">
import GuestLayout from '@/layouts/GuestLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const submitted = ref(false);

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('first-access.process'), {
        onSuccess: () => {
            submitted.value = true;
        },
    });
};
</script>

<template>
    <GuestLayout>

        <Head title="Primeiro Acesso" />

        <div v-if="submitted" class="text-center py-4">
            <div class="mb-4 flex justify-center">
                <div class="rounded-full bg-green-100 p-3 dark:bg-green-900">
                    <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>

            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                Verifique seu email!
            </h2>

            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Você recebeu um email com seu código de acesso.<br />
                Acesse sua caixa de entrada e utilize o código para fazer login.
            </p>

            <Link :href="route('login')"
                class="mt-6 inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Clique aqui para fazer login
            </Link>

            <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                Não recebeu o email?
                <button type="button" class="underline hover:text-gray-700 dark:hover:text-gray-300"
                    @click="submitted = false">
                    Tentar novamente
                </button>
            </p>
        </div>

        <template v-else>
            <div class="mb-4">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                    Solicitar Código de Acesso
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Digite seu email cadastrado para receber um novo código de acesso
                </p>
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                        autocomplete="email" />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <Link :href="route('login')"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800">
                        Já tenho uma conta
                    </Link>

                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Gerar Código
                    </PrimaryButton>
                </div>
            </form>
        </template>
    </GuestLayout>
</template>