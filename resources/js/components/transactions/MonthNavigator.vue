<script setup lang="ts">
import { ref } from "vue";
import { ChevronLeftIcon, ChevronRightIcon, CalendarDaysIcon } from "@heroicons/vue/24/outline";

interface Props {
    currentMonth: string;
}

defineProps<Props>();

const emit = defineEmits<{
    navigate: [direction: "prev" | "next"];
    goToDate: [date: string];
}>();

const dateInputRef = ref<HTMLInputElement | null>(null);

const openDatePicker = () => {
    dateInputRef.value?.showPicker();
};

const onDateChange = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.value) {
        emit("goToDate", input.value);
    }
};
</script>

<template>
    <div class="flex bg-white dark:bg-gray-800 items-center justify-between w-full rounded-xl shadow-sm p-1">
        <button @click="emit('navigate', 'prev')"
            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
            <ChevronLeftIcon class="w-5 h-5 text-gray-600 dark:text-gray-300" />
        </button>

        <div class="relative flex items-center gap-2 px-2 py-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors cursor-pointer"
            @click="openDatePicker">
            <span class="text-base font-medium text-gray-700 dark:text-gray-300">
                {{ currentMonth }}
            </span>
            <CalendarDaysIcon class="w-4 h-4 text-gray-500 dark:text-gray-400" />

            <input ref="dateInputRef" type="month" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                @change="onDateChange" />
        </div>

        <button @click="emit('navigate', 'next')"
            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
            <ChevronRightIcon class="w-5 h-5 text-gray-600 dark:text-gray-300" />
        </button>
    </div>
</template>