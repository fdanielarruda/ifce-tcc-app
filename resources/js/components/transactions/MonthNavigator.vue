<script setup lang="ts">
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/24/outline";

interface Props {
    currentMonth: string;
}

defineProps<Props>();

const emit = defineEmits<{
    navigate: [direction: "prev" | "next"];
}>();
</script>

<template>
    <div
        class="flex bg-white dark:bg-gray-800 items-center justify-between w-full rounded-xl shadow-sm p-1"
    >
        <button
            @click="emit('navigate', 'prev')"
            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
        >
            <ChevronLeftIcon class="w-5 h-5 text-gray-600 dark:text-gray-300" />
        </button>
        <span class="text-base font-medium text-gray-700 dark:text-gray-300">
            {{ currentMonth }}
        </span>
        <button
            @click="emit('navigate', 'next')"
            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
        >
            <ChevronRightIcon class="w-5 h-5 text-gray-600 dark:text-gray-300" />
        </button>
    </div>
</template>
