<script setup lang="ts">
import { SparklesIcon, PencilSquareIcon } from "@heroicons/vue/24/outline";

const modelValue = defineModel<"ai" | "manual">({ required: true });
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-2">
        <div class="grid grid-cols-2 gap-2">
            <button
                @click="modelValue = 'ai'"
                :class="[
                    'flex items-center justify-center gap-2 px-4 py-3 rounded-xl font-medium transition-all',
                    modelValue === 'ai'
                        ? 'bg-blue-600 text-white shadow-md'
                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700',
                ]"
            >
                <SparklesIcon class="w-5 h-5" />
                <span>Com IA</span>
            </button>
            <button
                @click="modelValue = 'manual'"
                :class="[
                    'flex items-center justify-center gap-2 px-4 py-3 rounded-xl font-medium transition-all',
                    modelValue === 'manual'
                        ? 'bg-blue-600 text-white shadow-md'
                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700',
                ]"
            >
                <PencilSquareIcon class="w-5 h-5" />
                <span>Manual</span>
            </button>
        </div>
    </div>
</template>
