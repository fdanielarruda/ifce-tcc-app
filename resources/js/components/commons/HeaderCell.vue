<script setup lang="ts">
interface Props {
    title: string;
}

const props = defineProps<Props>();
</script>

<template>
    <div class="block sm:hidden bg-white dark:bg-gray-800 shadow-sm">
        <div class="p-4 flex items-center justify-center">
            <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                {{ title }}
            </h1>
        </div>
    </div>
</template>
