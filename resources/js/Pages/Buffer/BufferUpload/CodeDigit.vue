<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    digit: number;
    focused: boolean;
    value?: string;
}>();

// Dynamické len to, čo sa mení podľa props
const borderClass = computed(() =>
    props.focused
        ? "border-my-white ring-2 ring-my-white scale-105 animate-pulse"
        : "border-gray-300 dark:border-gray-700"
);

const valueClass = computed(() =>
    props.value ? "text-gray-900 dark:text-white" : "text-gray-400"
);
</script>

<template>
    <div
        class="flex items-center justify-center aspect-square flex-grow flex-shrink basis-1/6
        transition-all duration-200 bg-gradient-to-b from-white/80 to-my-white/60
        dark:from-gray-800/80 dark:to-gray-900/60 shadow-lg border-2 cursor-pointer
        select-none lg:p-8 sm:p-4 md:p-4 rounded-2xl"
        :class="borderClass"
        @click="$emit('focus')"
    >
        <p
            class="p-0 m-0 sm:text-2xl lg:text-3xl font-mono font-semibold"
            :class="valueClass"
        >
            {{ value ?? "_" }}
        </p>
    </div>
</template>
